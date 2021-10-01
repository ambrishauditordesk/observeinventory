<?php
    if(isset($_POST))
    {
        include '../dbconnection.php';
        include '../customMailer.php';
        include '../checkDuplicateEmail.php';
        session_start();

        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $pass = md5(trim($_POST['password']));
        $tempPass = trim($_POST['password']);
        $role = trim($_POST['role']);

        // SignOff Init code
        $signOffArray = explode(' ',$name);
        $signOff = $signOffArray[0][0];
        $arraySize = sizeof($signOffArray)-1;
        if($arraySize){
            $signOff .= $signOffArray[$arraySize][0];
        }
        else{
            $signOff .= $signOffArray[0][1];
        }
        $signOff = strtoupper($signOff);
        $signOffInit = $con->query("SELECT signoff_init FROM `user` where signoff_init like '$signOff%' order by id desc limit 1");
        if($signOffInit->num_rows > 0){
            $signOffInit = $signOffInit->fetch_assoc()['signoff_init'];
            $signOffInit = substr($signOffInit,-1);
            if(is_numeric($signOffInit)){
                $signOff .=(++$signOffInit);
            }
            else{
                $signOff .='1';
            }
        }
        
        $checkMail = checkDuplicateEmail($email);
        if($checkMail){
            $regDate = date_format(date_create("now", new DateTimeZone('Asia/Kolkata')), "d-m-Y");
            $res= $con->query("insert into user(name,email,password,accessLevel,active,reg_date,signoff_init,reset_code,img) values('$name', '$email', '$pass', '$role', '1', '$regDate', '$signOff','','')");
            $user_id = $con->insert_id;
            $roleName = $con->query("select role_name from role where id = $role")->fetch_assoc()['role_name'];
            $sub = "You have been registered as a ".$roleName;
            $loginLink = 'http://auditorsdesk.com/login';

            $msg = "<div>
            <div>Hello ".$name.",</div>
            <br />
            <div>You have been registered as a ".$roleName." to join Digital audit workspace. Use your user
            id and temporary password to login to the workspace and reset the password.</div>
            <br />
            <div>Your email id: ".$email."</div>
            <div>Your temporary password: ".$tempPass."</div>
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
            <div>Auditor's Desk Team</div>
            </div>";
            if($_SESSION['role'] == 1 || $_SESSION['role'] == -1){
                $firm_id = trim($_POST['firm_id']);
            }
            else{
                $firm_id = $_SESSION['firm_id'];
            }
            $result = $con->query("insert into firm_user_log(firm_id,user_id) values('$firm_id','$user_id')");
            if($result && customMailer($email,$msg,$sub))
            {
                echo 1;
            }
            else
            {
                echo 0;
            }
        }
        if(!$checkMail){
            echo 0;
        }
    }
?>