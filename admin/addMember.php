<?php
    if(isset($_POST))
    {
        include '../dbconnection.php';
        session_start();
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $pass = md5(trim($_POST['password']));
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
        $regDate = date_format(date_create("now", new DateTimeZone('Asia/Kolkata')), "d-m-Y");
        $res= $con->query("insert into user(name,email,password,accessLevel,active,reg_date,signoff_init) values('$name', '$email', '$pass', '$role', '1', '$regDate', '$signOff')");
        if($res)
        {
            echo 1;
        }
        else
        {
            echo 0;
        }
    }
?>