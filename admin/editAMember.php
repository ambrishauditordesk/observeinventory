<?php
    if(isset($_POST))
    {
        include '../dbconnection.php';
        include '../customMailer.php';
        session_start();
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $active= trim($_POST['active']);
        $role = trim($_POST['role']);
        // $swapRole = trim($_POST['swapRole']);
        $loggedInID = $_SESSION['id'];
        $flag = 0;
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
        $signOffInit = $con->query("SELECT signoff_init FROM `user` where signoff_init like '$signOff%' order by id desc limit 1")->fetch_assoc()['signoff_init'];
        if($signOffInit != ''){
            $signOffInit = substr($signOffInit,-1);
            if(is_numeric($signOffInit)){
                $signOff .=(++$signOffInit);
            }
            else{
                $signOff .='1';
            }
        }

        $valueCheck = $con->query("select * from user where email = '$email'")->fetch_assoc();

        if($role != $valueCheck['accessLevel']){
            if($role == 4){
                $con->query("update user set accessLevel='$role' where email = '$email'");
                $con->query("update user set accessLevel = 2 where id = $loggedInID");

                $sub = "You have been promoted as a Firm Admin";
                $loginLink = 'http://auditorsdesk.com/AuditSoft/login';

                $msg = "<div>
                <div>Hello ".$name.",</div>
                <br />
                <div>You have been promoted as a Firm Admin.</div>
                <br />
                <div>Your email id: ".$email."</div>
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

                customMailer($email,$msg,$sub);
                sleep(1);

                $sub = "Your role has been changed to Audit Admin";

                $msg = "<div>
                <div>Hello ".$_SESSION['name'].",</div>
                <br />
                <div>Your role has been changed to Audit Admin.</div>
                <br />
                <div>Your email id: ".$_SESSION['email']."</div>
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

                customMailer($_SESSION['email'],$msg,$sub);                

            }
            $_SESSION['role'] = 2;
            $con->query("update user set accessLevel='$role' where email = '$email'");
            $flag = 1;
        }

        if($active != $valueCheck['active']){
            $con->query("update user set active = $active where email = '$email'");
            $flag = 1;
        }

        if($signOff != $valueCheck['signoff_init']){
            $con->query("update user set signoff_init = $signOff where email = '$email'");
            $flag = 1;
        }

        // if($swapRole != $valueCheck['accessLevel']){
        //     $con-query("update user set accessLevel = $swapRole where email = '$email'");
        //     $con->query("update user set accessLevel = 2 where id = $loggedInID");
        //     $flag = 1;
        // }

        if($flag)
        {
            echo 1;
        }
        else
        {
            echo 0;
        }
    }
?>