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
        $res= $con->query("insert into user(name,email,password,accessLevel,active,reg_date,signoff_init,reset_code,img) values('$name', '$email', '$pass', '$role', '1', '$regDate', '$signOff','','')");
        $user_id = $con->insert_id;

        if($_SESSION['role'] == 1 || $_SESSION['role'] == -1){
            $firm_id = trim($_POST['firm_id']);
        }
        else{
            $firm_id = $_SESSION['firm_id'];
        }
        $result = $con->query("insert into firm_user_log(firm_id,user_id) values('$firm_id','$user_id')");
        if($result)
        {
            echo 1;
        }
        else
        {
            echo 0;
        }
    }
?>