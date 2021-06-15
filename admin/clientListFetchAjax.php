<?php 
include '../dbconnection.php';
session_start();
if (!isset($_SESSION['email']) && empty($_SESSION['email'])){
    header("Location: ../index");    
}
if(!$_POST){
    header("Location: ../index");
}

$role =$_SESSION['role'];
$userId = $_SESSION['id'];

if($role == 2 || $role == 3 || $role == 4){
    // $query = "select a.id aid, a.name aname, b.const con, a.added_by_date adate, a.active aact FROM client a INNER JOIN constitution b on a.const_id= b.id where a.id in (select client_id from user_client_log where user_id=$userId)";
    $query = "select a.id aid, a.name aname, b.const con, a.added_by_date adate, a.active aact FROM client a INNER JOIN constitution b on a.const_id= b.id inner join user_client_log on a.id=user_client_log.client_id where user_client_log.user_id =$userId";
}
else{
    $query = "select a.id aid, a.name aname, b.const con, a.added_by_date adate, a.active aact FROM client a INNER JOIN constitution b on a.const_id= b.id";
}

$column = array('','name','profile','const_id','added_by_date','active');

if(isset($_POST["search"]["value"]) && !empty($_POST["search"]["value"])){
    $query .= ' and a.name LIKE "%'.$_POST["search"]["value"].'%"';
}
if(isset($_POST['order'])){
    $query .= ' ORDER BY '.$column[$_POST['order']['0']['column']].' '.$_POST['order']['0']['dir'].' ';
}
else{
    $query .= ' ORDER BY aname DESC ';
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
 {
    if($row['aact'] == 1){
        $sub_array[] = "<label class='mt-2'><span class='helpDesign help_11'>11</span></label>&nbsp;<a href='../workspace?gid=".base64_encode(md5(trim($row['aid'])))."&xid=".base64_encode(md5(trim($row['aid'])))."&yid=".base64_encode(md5(trim($row['aid'])))."&zid=".base64_encode(md5(trim($row['aid'])))."&aid=".base64_encode(md5(trim($row['aid'])))."&sid=".base64_encode(md5(trim($row['aid'])))."&cid=".base64_encode(trim($row['aid']))."'>".$row['aname']."</a>";
    }
    else{
        $sub_array[] = "<label class='mt-2'><span class='helpDesign help_11'>11</span></label>&nbsp;".$row['aname'];
    }
 }
 $sub_array[] = "<label class='mt-2'><span class='helpDesign help_8'>8</span></label>&nbsp;<a href='#' class='icon-hide'><img class='datatable-icon editClientProfile' src='../Icons/Icon metro-profile.svg' style='width: 15% !important;' id='".trim($row['aid'])."'><img class='datatable-icon editClientProfile' src='../Icons/Icon metro-profile-1.svg' style='width: 15% !important;' id='".trim($row['aid'])."'></a> &nbsp;
 <label class='mt-2'><span class='helpDesign help_9'>9</span></label>&nbsp;<a href='clientMember?gid=".base64_encode(md5(trim($row['aid'])))."&xid=".base64_encode(md5(trim($row['aid'])))."&yid=".base64_encode(md5(trim($row['aid'])))."&zid=".base64_encode(md5(trim($row['aid'])))."&aid=".base64_encode(md5(trim($row['aid'])))."&sid=".base64_encode(md5(trim($row['aid'])))."&cid=".base64_encode(trim($row['aid']))."' class='icon-hide'><img class='datatable-icon' src='../Icons/Group 4.svg' style='width: 15% !important;'><img class='datatable-icon' src='../Icons/Group 8.svg' style='width: 15% !important;'></a>";
 $sub_array[] = $row['con'];
 $sub_array[] = $row['adate'];
 {
    if($row['aact'] == 1){
        $sub_array[] = "<label class='mt-2'><span class='helpDesign help_10'>10</span></label>&nbsp;<span class='badge badge-success small id= '".$row['aact']."''>ACTIVE</span>";
    }
    else{
        $sub_array[] = "<label class='mt-2'><span class='helpDesign help_10'>10</span></label>&nbsp;<span class='badge badge-danger small id= '".$row['aact']."''>INACTIVE</span>";
    }
}

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