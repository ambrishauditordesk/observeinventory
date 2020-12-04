<?php 
include '../dbconnection.php';
session_start();
$column = array('sl','name','const_id','added_by_date','active','action');
$query = "select a.id aid, a.name aname, b.const con, a.added_by_date adate, a.active aact FROM client a INNER JOIN constitution b on a.const_id= b.id";
if(isset($_POST["search"]["value"]))
{
 $query .= ' and a.name LIKE "%'.$_POST["search"]["value"].'%"';
}
 $query .= ' group by aname ';
if(isset($_POST['order']))
{
 $query .= 'ORDER BY '.$column[$_POST['order']['0']['column']].' '.$_POST['order']['0']['dir'].' ';
}
else
{
 $query .= 'ORDER BY aname DESC ';
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
 $sub_array[] = $row['aname'];
 $sub_array[] = $row['con'];
 $sub_array[] = $row['adate'];
 {
    if($row['aact'] == 1){
        $sub_array[] = "<span class='badge badge-success small id= '".$row['aact']."''>ACTIVE</span>";
    }
    else{
        $sub_array[] = "<span class='badge badge-danger small id= '".$row['aact']."''>INACTIVE</span>";
    }
}
    $sub_array[] = "<a href='#'><i class='fas fa-edit editClient' id='".$row['aid']."'></i></a>&nbsp;
     <a href='../workspace.php?cid=".trim($row['aid'])."'><i class='far fa-plus-square'></i></a>";
 
 $data[] = $sub_array;
}
function get_all_data($con)
{
 $query = "SELECT count(a.id) total FROM client a ";
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