<?php 
include '../dbconnection.php';
session_start();
$cid = $_POST['cid'];
$column = array('','name','email','active','design','edit');
$query = "SELECT * FROM `user` where client_id='$cid'";
if(isset($_POST["search"]["value"]) && !empty($_POST["search"]["value"]))
{
 $query .= ' and name LIKE "%'.$_POST["search"]["value"].'%"';
}
if(isset($_POST['order']))
{
 $query .= ' ORDER BY '.$column[$_POST['order']['0']['column']].' '.$_POST['order']['0']['dir'].' ';
}
else
{
 $query .= ' ORDER BY name DESC ';
}
 $query1 = '';
if($_POST["length"] != -1)
{
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
 $sub_array[] = $row['name'];
 $sub_array[] = $row['email'];
    {
        if($row['active'] == 1){
            $sub_array[] = "<span class='badge badge-success small id= '".$row['active']."''>Allowed</span>";;
        }
        else{
            $sub_array[] = "<span class='badge badge-danger small id= '".$row['active']."''>Access Denied</span>";;
        }
    }
 $sub_array[] = $row['designation'];
 $sub_array[] = "<a href='#' class='icon-hide'><img class='datatable-icon editClient' src='../Icons/edit-1.svg' id='".$row['id']."'><img class='datatable-icon editClient' src='../Icons/edit-2.svg' id='".$row['id']."'></a>";
 $data[] = $sub_array;
}
function get_all_data($con)
{
 $query = "SELECT count(id) total FROM user";
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