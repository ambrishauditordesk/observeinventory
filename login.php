<?php
    include 'dbconnection.php';
	if(!isset($_SESSION)){
       session_start();
    }

	if (isset($_SESSION['logged_in_date']) && !empty($_SESSION['logged_in_date'])){
        $currentDate = date_create(date("Y-m-d H:i:s",strtotime(date_format(date_create("now", new DateTimeZone('Asia/Kolkata')), "Y-m-d H:i:s"))));
        $loggedInDate = date_create(date("Y-m-d H:i:s",strtotime($_SESSION['logged_in_date'])));
        $diff=date_diff($currentDate,$loggedInDate);
		if($diff->format("%a") > 1 || $diff->format("%m") > 1 || $diff->format("%y") > 1){
			header('Location: logout');
		}
		else{
			if (isset($_SESSION['email']) && !empty($_SESSION['email'])) {
				header("Location: admin/clientList");
			}
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
<link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
<link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<link href="css/custom.css" rel="stylesheet">
<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="vendor/jquery/jquery.min.js"></script>

</head>
<body oncontextmenu="return false">
<div class="login-reg-panel">
		<div class="login-info-box">
			<h2>Remember Password??</h2><br>
			<p>Kindly Login</p><br>
			<label id="label-register" for="log-reg-show">Login</label>
			<input type="radio" name="active-log-panel" id="log-reg-show"  checked="checked">
		</div>
							
		<div class="register-info-box">
			<h2>Forgot Password?</h2><br>
			<p>Kindly reset password</p><br>
			<label id="label-login" for="log-login-show">Reset Password</label>
			<input type="radio" name="active-log-panel" id="log-login-show">
		</div>
							
		<div class="white-panel">
			<div class="login-show">
				<h2>LOGIN</h2>
					<form method = "post" action = "validate.php">
						<input type="text" name = "email" placeholder="Email" required>
						<input type="password" name = "password" placeholder="Password" required>
						<div class="form-group" style="display:flex; align-items:center">
							<img src="getCaptcha?rand=<?php echo rand(); ?>" id='captcha'>
							<a href="#"><i id="reloadCaptcha" class="fas fa-redo-alt"></i></a>
						</div>
						<div class="form-group">
							<input type="text" name="vercode" class="form-control" placeholder="Enter the Security Code" autocomplete="off" required>
						</div>
						<input type="submit"  value= "Login">
					</form>
			</div>
			<div class="register-show">
				<h2>Reset Password</h2>
				<form method = "post" action = "resetPassword">
					<input type="email" name="email" placeholder="Email" required>
					<input type="submit" value="Reset">
				</form>
			</div>
		</div>
    </div>
	<script src="js/custom.js"></script>
<script>
		$(document).ready(function(){

			document.getElementsByTagName("html")[0].style.visibility = "visible";
			
		    $('.login-info-box').fadeOut();
		    $('.login-show').addClass('show-log-panel');
		});


		$('.login-reg-panel input[type="radio"]').on('change', function() {
		    if($('#log-login-show').is(':checked')) {
        $('.register-info-box').fadeOut(); 
        $('.login-info-box').fadeIn();
        
        $('.white-panel').addClass('right-log');
        $('.register-show').addClass('show-log-panel');
        $('.login-show').removeClass('show-log-panel');
        
		    }
		    else if($('#log-reg-show').is(':checked')) {
        $('.register-info-box').fadeIn();
        $('.login-info-box').fadeOut();
        
        $('.white-panel').removeClass('right-log');
        
        $('.login-show').addClass('show-log-panel');
        $('.register-show').removeClass('show-log-panel');
		    }
		});

		$("#reloadCaptcha").click(function(){
			var captchaImage = $('#captcha').attr('src');	
			captchaImage = captchaImage.substring(0,captchaImage.lastIndexOf("?"));
			captchaImage = captchaImage+"?rand="+Math.random()*1000;
			$('#captcha').attr('src', captchaImage);
		});
</script>
  
</body>
</html>