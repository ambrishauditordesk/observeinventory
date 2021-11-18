<?php 
include '../dbconnection.php';
if(!isset($_SESSION)){
       session_start();
    }
if (!isset($_SESSION['email']) && empty($_SESSION['email'])){
    header("Location: ../index");    
}
if(!$_POST){      
    header("Location: ../index");
}

$role =$_SESSION['role'];  
$userId  = $_SESSION['id'];

if($role == 2 || $role == 3 || $role == 4){
    // $query = "select a.id aid, a.name aname, b.const con, a.added_by_date adate, a.active aact FROM client a INNER JOIN constitution b on a.const_id= b.id where a.id in (select client_id from user_client_log where user_id=$userId)";
    $query = "select a.id aid, a.name aname, b.const con, a.added_by_date adate, a.active aact FROM client a INNER JOIN constitution b on a.const_id= b.id inner join user_client_log on a.id=user_client_log.client_id where user_client_log.user_id =$userId";
}
else{
   // $query = "select a.id aid, a.name aname, b.const con, a.added_by_date adate, a.active aact FROM client a INNER JOIN constitution b on a.const_id= b.id";
    $query = "SELECT user.id,firm_name,firm_details.id,firm_details.used_workspace,firm_details.storage,firm_details.plan FROM user inner join role on user.accessLevel = role.id left join firm_user_log on firm_user_log.user_id = user.id left join firm_details on firm_details.id = firm_user_log.firm_id where accessLevel != -1";
}

$column = array('','name');

if(isset($_POST["search"]["value"]) && !empty($_POST["search"]["value"])){
    $query .= ' and a.name LIKE "%'.$_POST["search"]["value"].'%"';
}
if(isset($_POST['order'])){
    $query .= ' ORDER BY '.$column[$_POST['order']['0']['column']].' '.$_POST['order']['0']['dir'].' ';
}
else{
    $query .= ' ORDER BY firm_name DESC ';
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

        $sub_array[] = "<label class='mt-2'><span class='helpDesign help_11'>11</span></label>&nbsp;<a href='../workspace?gid=".base64_encode(md5(trim($row['firm_name'])))."&firm_name=".base64_encode(md5(trim($row['firm_name'])))."&firm_name=".base64_encode(md5(trim($row['firm_name'])))."&firm_name=".base64_encode(md5(trim($row['firm_name'])))."&firm_name=".base64_encode(md5(trim($row['firm_name'])))."&firm_name=".base64_encode(md5(trim($row['firm_name'])))."&firm_name=".base64_encode(trim($row['firm_name']))."'>".$row['firm_name']."</a>";
        $sub_array[]= getTotalClient($row['id']);
        $sub_array[] = getTotalWorkspace($row['used_workspace']);
        $sub_array[] = getTotalStorage($row['storage']);
        $sub_array[] = getTotalPlan($row['plan']);
    }

 $data[] = $sub_array;
}
function get_all_data($con)
{
    
  $query = "SELECT count(a.name) total FROM client a ";
  $statement = $con->query($query)->fetch_assoc()["total"];
 return $statement;
}
function getTotalClient($id){
    global $con;
    $sql = "SELECT COUNT(id) as total FROM firm_user_log where firm_id='$id'";
    $executeQuery = mysqli_query($con,$sql);
    $result = mysqli_fetch_assoc($executeQuery);
    $html = "<label class='mt-2'><span class='helpDesign help_11'>11</span>".$result['total']."</label>";
    return $html;
}

function getTotalWorkspace($used_workspace){
    global $con;
    $sql = "SELECT COUNT(used_workspace) as total FROM firm_details where used_workspace";
    $executeQuery = mysqli_query($con,$sql);
    $result = mysqli_fetch_assoc($executeQuery);
    $html = "<label class='mt-2'><span class='helpDesign help_11'>11</span>".$result['total']."</label>";
    return $html;
}

function getTotalStorage($storage){
    global $con;
    $sql = "SELECT COUNT(storage) as total FROM firm_details where storage";
    $executeQuery = mysqli_query($con,$sql);
    $result = mysqli_fetch_assoc($executeQuery);
    $html = "<label class='mt-2'><span class='helpDesign help_11'>11</span>".$result['total']."</label>";
    return $html;
}

function getTotalPlan($plan){
    global $con;
    $sql = "SELECT COUNT(plan) as total FROM firm_details where plan";
    $executeQuery = mysqli_query($con,$sql);
    $result = mysqli_fetch_assoc($executeQuery);
    $html = "<label class='mt-2'><span class='helpDesign help_11'>11</span>".$result['total']."</label>";
    return $html;
}
$output = array(
 "draw"       =>  intval($_POST["draw"]),
 "recordsTotal"   =>  get_all_data($con),
 "recordsFiltered"  =>  $number_filter_row,
 "data"       =>  $data
);
echo json_encode($output);
?>