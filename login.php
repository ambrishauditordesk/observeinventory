<?php
    include 'dbconnection.php';
	session_start();
	if (isset($_SESSION['email']) && !empty($_SESSION['email'])){
            header("Location: admin/clientList");    
        }
?>
<!DOCTYPE html>
<html>
<head>
<link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<link href="css/custom.css" rel="stylesheet">
<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="vendor/jquery/jquery.min.js"></script>

</head>
<body>
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
							<input type="text" name = "email" placeholder="Email">
							<input type="password" name = "password" placeholder="Password">
							<input type="submit"  value= "Login">
						</form>
			</div>
			<div class="register-show">
				<h2>Reset Password</h2>		
				<input type="text" placeholder="Email">
				<input type="submit" value="Reset">
			</div>
		</div>
    </div>
<script>
		    $(document).ready(function(){
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
</script>
  
</body>
</html>