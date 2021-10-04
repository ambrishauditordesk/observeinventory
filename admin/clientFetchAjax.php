<?php 
include '../dbconnection.php';
if(!isset($_SESSION)){
       session_start();
    }
$cid = $_POST['cid'];
$column = array('','name','nickname','doi','const','indus','add','country','state','city','pin','pan','gst','tan','cin');
$query = "select a.*,b.const const,c.industry industry from client a inner join constitution b on a.const_id=b.id inner join industry c on a.industry_id=c.id where a.id='$cid'";
if(isset($_POST["search"]["value"]) && !empty($_POST["search"]["value"]))
{
 $query .= ' and a.name LIKE "%'.$_POST["search"]["value"].'%"';
}
if(isset($_POST['order']))
{
 $query .= ' ORDER BY '.$column[$_POST['order']['0']['column']].' '.$_POST['order']['0']['dir'].' ';
}
else
{
 $query .= ' ORDER BY a.name DESC ';
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
 $sub_array[] = $row['nickname'];
 $sub_array[] = $row['incorp_date'];
 $sub_array[] = $row['const']; 
 $sub_array[] = $row['industry'];
 $sub_array[] = $row['address'];
 $sub_array[] = $row['country'];
 $sub_array[] = $row['state'];
 $sub_array[] = $row['city'];
 $sub_array[] = $row['pincode'];
 $sub_array[] = $row['pan'];
 $sub_array[] = $row['gst'];
 $sub_array[] = $row['tan'];
 $sub_array[] = $row['cin'];
 $data[] = $sub_array;
}
function get_all_data($con)
{
 $query = "SELECT count(id) total FROM client";
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