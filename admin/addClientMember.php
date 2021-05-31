<?php
    if(isset($_POST))
    {
        include '../dbconnection.php';
        include '../customMailer.php';

        session_start();
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $pass = md5(trim($_POST['password']));
        $tempPass = trim($_POST['password']);
        $design = trim($_POST['design']);
        $cid= trim($_POST['cid']);
        $ser = $_SERVER['HTTP_REFERER'];

        $duplicate = $con->query("select * from user where email = '$email'");
        if ($duplicate->num_rows > 0)
        {
            echo 0;
        }
        else
        {
            $res= $con->query("insert into user(client_id,name,email,password,accessLevel,active,designation,reset_code,img) values('$cid','$name', '$email', '$pass', '5', '1','$design','','')");
            $uid= $con->insert_id;
        
            $sub = "You have been registered as a Client member";
            if($_SERVER['HTTP_ORIGIN'] == 'http://localhost'){
                $loginLink = $_SERVER['HTTP_ORIGIN'].'/AuditSoft/login';
            }
            elseif($_SERVER['HTTP_ORIGIN'] == 'http://atlats.in'){
                $loginLink = $_SERVER['HTTP_ORIGIN'].'/audit/login';
            }
            elseif($_SERVER['HTTP_ORIGIN'] == 'http://yourfirmaudit.com'){
                $loginLink = $_SERVER['HTTP_ORIGIN'].'/AuditSoft/login';
            }

            $msg = "<div>
            <div>Hello ".$name.",</div>
            <br />
            <div>You have been added as a Client member to join Digital audit workspace by your auditor. Use your user
            id to login to the client request list you have been allocated to. You can uploaded documents and reply
            to auditors request using the below details.</div>
            <br />
            <div>Your email id: ".$email."</div>
            <div>Your temporary password: ".$tempPass."</div>
            <br/>
            <a href='".$loginLink."'><button style=' background-color: #008CBA; border: none; color: white; padding: 15px 32px; text-align: center; text-decoration: none; display: inline-block; font-size: 16px; cursor:pointer;'>Login</button></a>
            <br />
            <br />
            <div>Note:- For security purposes, please do not share this email with anyone as it contains your account</div>
            <div>information. If you have login problems or questions, or you are having problems with this email, please</div>
            <div>contact the Help desk or your firm administrator.</div>
            <br />
            <div>Thank you.</div>
            <br />
            <div>The Auditedg Team</div>
            </div>";

            $con->query("insert into user_client_log(user_id,client_id) values('$uid','$cid')");
            if($res && customMailer($email,$msg,$sub))
            {
                echo 1;
            }
            else
            {
                echo 0;
            }
        }
    }
?>

