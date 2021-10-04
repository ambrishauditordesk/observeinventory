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
$query = "SELECT id,email,ip,dateTime,location,browser,status FROM loginlog where 1 ";

$column = array('','email','ip','dateTime','location','browser','status');

if(isset($_POST["search"]["value"]) && !empty($_POST["search"]["value"])){
    $query .= ' and email LIKE "%'.$_POST["search"]["value"].'%"';
}
if(isset($_POST['order'])){
    $query .= ' ORDER BY '.$column[$_POST['order']['0']['column']].' '.$_POST['order']['0']['dir'].' ';
}
else{
    $query .= ' ORDER BY id DESC ';
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
$sub_array[] = $row['ip'];
$sub_array[] = $row['dateTime'];
$sub_array[] = $row['location'];
$sub_array[] = $row['browser'];
if($row['status'] == 'Success')
    $sub_array[] = '<label class= "badge badge-success">'.$row['status'].'</label>';
elseif($row['status'] == 'Access Denied')
    $sub_array[] = '<label class= "badge badge-warning">'.$row['status'].'</label>';
else
    $sub_array[] = '<label class= "badge badge-danger">'.$row['status'].'</label>';
$data[] = $sub_array;
}
function get_all_data($con)
{
$query = "SELECT count(id) total FROM loginlog ";
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