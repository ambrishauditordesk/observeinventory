<!DOCTYPE html>
<html lang="en">

<head>
    <title>Audit-EDG</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Custom fonts for this template-->
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
            href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
            rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../css/pace-theme.css" rel="stylesheet">
    <link href="../css/custom.css" rel="stylesheet">
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous"></script>

</head>

<body>
<?php
include '../dbconnection.php';
include '../customMailer.php';
include '../checkDuplicateEmail.php';
session_start();

$cname = array();
$email = array();
$pass = array();
$tempPass = array();
$desig = array();


$addedById = $_SESSION['id'];

// Getting the CST Time
$addedByDate = date_format(date_create("now", new DateTimeZone('Asia/Kolkata')), "Y-m-d");

$uploadOk = 1;

// Name
$name = '';
$error = '';

if (!empty($_POST['clientname']) && isset($_POST['clientname'])) {
    $name = trim($_POST['clientname']);
    if($con->query("select * from client where name = '$name'")->num_rows != 0){
        $error .= "<p><strong>".$name."</strong> is already present.</p><br>";
        $uploadOk = 0;
    }
}

if($_SESSION['role'] == -1 || $_SESSION['role'] == 1){
    $firm_id = trim($_POST['firm_id']);
    $firmLeaderId = $con->query("SELECT user.id id FROM user inner join firm_user_log on firm_user_log.user_id = user.id where firm_user_log.firm_id = $firm_id and user.accessLevel = 4")->fetch_assoc()['id'];
}

//Nickname
$nickName = trim($_POST['nickname']);

//Date
$date = trim($_POST['dob']);

//Constitution
$const = trim($_POST['constitution']);

//Industry
$industry = trim($_POST['industry']);

//Address
$add = trim($_POST['add']);

//City
$city = trim($_POST['city']);

//State
$state = trim($_POST['state']);

//Pincode
$pin = trim($_POST['pincode']);

//Country
$country = trim($_POST['country']);

//PAN
$pan = trim($_POST['pan']);

//GST
$gst = trim($_POST['gst']);

//TAN
$tan = trim($_POST['tan']);

//CIN
$cin = trim($_POST['cin']);

//Person Name
$i =0;

foreach (($_POST['cname']) as $personname) {
    $cname[$i++] = trim($personname);
}

//Email
$i =0;

foreach (($_POST['email']) as $emailID) {
    $email[$i++] = trim($emailID);
}

//Phone
$i =0;

foreach (($_POST['pass']) as $password) {
    $pass[$i] = md5(trim($password));
    $tempPass[$i++] = trim($password);
}

//Designation
$i =0;

foreach (($_POST['designation']) as $designation) {
    $desig[$i++] = trim($designation);
}
$count = $i;
$successEmailList = $unSuccessEmailList = '';

if($uploadOk) {
    $con->query("insert into client(active,added_by_id,added_by_date,name,nickname,incorp_date,const_id,industry_id,address,city,state,pincode,country,pan,gst,tan,cin)
    values('1','$addedById','$addedByDate','$name','$nickName','$date','$const','$industry','$add','$city','$state','$pin','$country','$pan','$gst','$tan','$cin')");
    $cid = $con->insert_id;
    $name = str_replace(' ', '', $name);
    if($_SESSION['role'] != -1 && $_SESSION['role'] != 1){
        $con->query("insert into user_client_log(user_id,client_id) values('$addedById','$cid')");
        shell_exec('mkdir -p ../uploads/'.$_SESSION['firm_id'].'/'.$cid.$name.'/');
        shell_exec('sudo chown -R root:root ../uploads/'.$_SESSION['firm_id'].'/'.$cid.$name.'/');
        shell_exec('sudo chmod -R 777 ../uploads/'.$_SESSION['firm_id'].'/'.$cid.$name.'/');
    }
    else{
        $con->query("insert into user_client_log(user_id,client_id) values('$firmLeaderId','$cid')");
        shell_exec('mkdir -p ../uploads/'.$firm_id.'/'.$cid.$name.'/');
        shell_exec('sudo chown -R root:root ../uploads/'.$firm_id.'/'.$cid.$name.'/');
        shell_exec('sudo chmod -R 777 ../uploads/'.$firm_id.'/'.$cid.$name.'/');
    }
    $sub = "You have been added as a Client member";
    if($_SERVER['HTTP_ORIGIN'] == 'http://localhost'){
        $loginLink = $_SERVER['HTTP_ORIGIN'].'/AuditSoft/login';
     }
     elseif($_SERVER['HTTP_ORIGIN'] == 'http://atlats.in'){
        $loginLink = $_SERVER['HTTP_ORIGIN'].'/audit/login';
     }
     elseif($_SERVER['HTTP_ORIGIN'] == 'http://yourfirmaudit.com'){
        $loginLink = $_SERVER['HTTP_ORIGIN'].'/AuditSoft/login';
     }

     for($i=0;$i<$count;$i++){
        $checkDuplicateEmail = checkDuplicateEmail($email[$i]);
        if(!$checkDuplicateEmail){
            echo "<script>
                $(document).ready(function() {
                    document.getElementsByTagName('html')[0].style.visibility = 'visible';
                    $('#unsuccessModal').modal();
                });
            </script>";
            break;
        }
        if($checkDuplicateEmail){
            $con->query("insert into user(client_id,name,email,password,accessLevel,active,designation,reset_code,img) values('$cid','$cname[$i]','$email[$i]','$pass[$i]','5','1','$desig[$i]','','')");
            $uid = $con->insert_id;
            $con->query("insert into user_client_log(user_id,client_id) values('$uid','$cid')");
            $msg = "<div>
            <div>Hello ".$cname[$i].",</div>
            <br />
            <div>You have been added as a Client member to join Digital audit workspace by your auditor. Use your user
            id to login to the client request list you have been allocated to. You can uploaded documents and reply
            to auditors request using the below details.</div>
            <br />
            <div>Your email id: ".$email[$i]."</div>
            <div>Your temporary password: ".$tempPass[$i]."</div>
            <br/>
            <a href='".$loginLink."'><button style=' background-color: #008CBA; border: none; color: white; padding: 15px 32px; text-align: center; text-decoration: none; display: inline-block; font-size: 16px; cursor:pointer;'>Login</button></a>
            <br />
            <br />
            <div><b>This is a system generated email, please do not reply to this email.</b></div>
            <br />
            <div>Note:- For security purposes, please do not share this email with anyone as it contains your account</div>
            <div>information. If you have login problems or questions, or you are having problems with this email, please</div>
            <div>contact the Help desk or your firm administrator.</div>
            <br />
            <div>Thank you.</div>
            <br />
            <div>The Auditedg Team</div>
            </div>";
            if(customMailer($email[$i],$msg,$sub)){
                if(empty($successEmailList))
                    $successEmailList = $email[$i];
                else
                    $successEmailList .= ','.$email[$i];
                sleep(1);
            }
            else{
                if(empty($unSuccessEmailList))
                    $unSuccessEmailList = $email[$i];
                else
                    $unSuccessEmailList .= ','.$email[$i];
            }
        }
    }
    
    echo "<script>
            $(document).ready(function() {
                document.getElementsByTagName('html')[0].style.visibility = 'visible';
                $('#successModal').modal();
            });
        </script>"; 
}
else{
    echo "<script>
            $(document).ready(function() {
                document.getElementsByTagName('html')[0].style.visibility = 'visible';
                $('#unsuccessModal').modal();
            });
        </script>";
}
?>

<!--Success Modal-->
<div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5>Hey <?php echo $_SESSION['name']; ?>!</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                Successfully added <?php echo $name; ?>.<a href="clientList">Click Me!</a>
                <p>
                    <?php
                        if(!empty($successEmailList)){
                            ?>
                            Emails has been send with invitation link to:-<br><?php echo $successEmailList; ?><br>
                            <?php
                            if(!empty($unSuccessEmailList)){
                                ?>
                                    Emails has not been send to:-<br><?php echo $unSuccessEmailList; ?><br>
                                <?php
                            }
                        }
                        else{
                            ?>
                                Emails has not been send to:-<br><?php echo $unSuccessEmailList; ?><br>
                            <?php
                        }
                    ?>
                </p>
            </div>
            <div class="modal-footer">
                <a class="btn btn-primary" href="clientList">OK</a>
            </div>
        </div>
    </div>
</div>  

 <!--Unsuccess Modal-->
 <div class="modal fade" id="unsuccessModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Client Addition Failed! Emails Already Exists </div>
            <div class="modal-footer">
                <a class="btn btn-primary" href="clientList">OK</a>
            </div>
        </div>
    </div>
</div> 


