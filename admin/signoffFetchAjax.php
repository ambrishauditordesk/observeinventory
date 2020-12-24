<?php
include '../dbconnection.php';
$id = $_POST['id'];
$wid = $_POST['wid'];

$data2 = $con->query("select program_name from program inner join workspace_log on program.id = workspace_log.program_id where program_id='$id' and workspace_id='$wid'");
$data1 = $con->query("select b.id,b.file from signoff_files b inner join signoff_log a on a.id=b.signoff_id where a.workspace_id='$wid' and a.prog_id='$id'");
$data = $con->query("select id,comment from signoff_log where workspace_id='$wid' and prog_id='$id'");

$newData = array();
$newData['comment'] = $data->fetch_assoc();
$newData['file'] = $data1->fetch_all();
$newData['pname'] = $data2->fetch_assoc();

echo json_encode($newData);
