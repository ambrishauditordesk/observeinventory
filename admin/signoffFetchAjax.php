<?php
include '../dbconnection.php';
$id = $_POST['id'];
$wid = $_POST['wid'];
$newData = array();

$data = $con->query("select program_name from program inner join workspace_log on program.id = workspace_log.program_id where program_id='$id' and workspace_id='$wid'");
$newData['pname'] = $data->fetch_assoc();

$data = $con->query("select id,file from signoff_files_log where workspace_id='$wid' and prog_id='$id' and status = 0");
$newData['file'] = $data->fetch_all();

$data = $con->query("select id,comments,comments_date from signoff_comments_log where workspace_id='$wid' and prog_id='$id'");
$newData['comment'] = $data->fetch_all();

$data = $con->query("select user.signoff_init,prepare_signoff_date from signoff_prepare_log inner join user on signoff_prepare_log.user_id=user.id where workspace_id='$wid' and prog_id='$id'");
$newData['prepareSignOff'] = $data->fetch_all();

// $data = $con->query("select user.signoff_init,review_signoff_date from signoff_review_log inner join user on signoff_review_log.user_id=user.id where workspace_id='$wid' and prog_id='$id'");
// $newData['reviewSignOff'] = $data->fetch_all();

echo json_encode($newData);
