<?php 
include '../dbconnection.php';
if(!isset($_SESSION)){
       session_start();
    }
if (!isset($_SESSION['email']) && empty($_SESSION['email'])){
    header("Location: ../index");    
}
// if(!$_POST){
//     header("Location: ../index");
// }
if($_SESSION['role'] == 1 || $_SESSION['role'] == -1){
    $query = "SELECT id,email,activity_date_time,activity_captured FROM activity_log where 1 ";
}
else{
    $query = "SELECT activity_log.id id,activity_log.email email,activity_date_time,activity_captured FROM activity_log inner join user on activity_log.email = user.email where user.accessLevel != 1 and user.accessLevel != -1 ";
}

$column = array('','email','activity_date_time','activity_captured');

if (isset($_SESSION['workspace_id']) && !empty($_SESSION['workspace_id'])){
    $wid = $_SESSION['workspace_id'];
    $query .= ' and workspace_id = '.$wid;
}


if(isset($_POST["search"]["value"]) && !empty($_POST["search"]["value"])){
    $query .= ' and email LIKE "%'.$_POST["search"]["value"].'%"';
}
if(isset($_POST['order'])){
    $query .= ' ORDER BY '.$column[$_POST['order']['0']['column']].' '.$_POST['order']['0']['dir'].' ';
}
else{
    $query .= ' ORDER BY activity_log.id DESC ';
}

$query1 = '';
if($_POST["length"] != -1){
    $query1 = 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
}

//$statement = $con->prepare($query);
$statement = $con->query($query);
//$statement->execute();
$number_filter_row = $statement->num_rows;
//$statement = $con->prepare($query . $query1);
$statement = $con->query($query . $query1);
//$statement->execute();
$result = $statement->fetch_all(MYSQLI_ASSOC);


$data = array();
foreach($result as $row)
{
$sub_array = array();
$sub_array[] = '';
$sub_array[] = $row['email'];
$sub_array[] = $row['activity_date_time'];
$sub_array[] = $row['activity_captured'];
$data[] = $sub_array;
}
function get_all_data($con)
{
$query = "SELECT count(id) total FROM activity_log ";
$statement = $con->query($query)->fetch_assoc()["total"];
return $statement;
}
$output = array(
"draw"       =>  intval($_POST["draw"]),
"recordsTotal"   =>  get_all_data($con),
"recordsFiltered"  =>  $number_filter_row,
"data"       =>  $data
);
echo json_encode($output);
?>