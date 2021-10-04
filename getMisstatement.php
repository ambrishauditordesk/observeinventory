<?php
    include 'dbconnection.php';
    if(!isset($_SESSION)){
       session_start();
    }
    $flag = 0;
    if($_POST){
        $data = array();
        $id = trim($_POST['id']);
        $wid = trim($_POST['wid']);
        
        $result = $con->query("select * FROM summery_of_misstatements WHERE id = $id and workspace_id=$wid")->fetch_assoc();

        $data['adjust_number'] = $result['adjust_number'];
        $data['type'] = $result['type'];
        $data['misstatements'] = $result['misstatements'];
        $data['description'] = $result['description'];
        $summery_of_misstatementsId = $result['id'];

        $resultLog = $con->query("select * FROM summery_of_misstatements_log WHERE summery_of_misstatements_id = $summery_of_misstatementsId");

        $j = 0;
        while($rowLog = $resultLog->fetch_assoc()){
            $data['log'][$j][0] = $rowLog['account'];
            $data['log'][$j++][1] = $rowLog['amount'];
            $flag = 1;
        }
       echo  $flag ? json_encode($data) : json_encode($flag); 
    }
?>