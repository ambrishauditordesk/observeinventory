<?php
    if(isset($_POST))
    {
        include '../dbconnection.php';
        session_start();
        // $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $active= trim($_POST['active']);
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
        $res= $con->query("update user set accessLevel='$role', active='$active', signoff_init='$signOff' where email='$email'");
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