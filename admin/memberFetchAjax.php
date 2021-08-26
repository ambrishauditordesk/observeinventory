<?php 
include '../dbconnection.php';
session_start();
if (!isset($_SESSION['email']) && empty($_SESSION['email'])){
    header("Location: ../index");    
}
if(!$_POST){
    header("Location: ../index");
}

$role = $_SESSION['role'];

if($role == 3 || $role == 5){
    $query = "SELECT name, email, role_name, active, reg_date, signoff_init FROM user inner join firm_user_log on firm_user_log.user_id = user.id inner join role on user.accessLevel = role.id where firm_id = ".$_SESSION['firm_id'];
}
elseif($role == 4 || $role == 2){
    $query = "SELECT user.id id, name, email, role_name, active, reg_date, signoff_init FROM user inner join firm_user_log on firm_user_log.user_id = user.id inner join role on user.accessLevel = role.id where firm_id = ".$_SESSION['firm_id'];
}
elseif($role == 1){
    $query = "SELECT user.id id, name, email, firm_name, role_name, active, reg_date, signoff_init FROM user inner join role on user.accessLevel = role.id left join firm_user_log on firm_user_log.user_id = user.id left join firm_details on firm_details.id = firm_user_log.firm_id where accessLevel != -1 and client_id is null";
}
elseif($role == -1){
    $query = "SELECT user.id id, name, email, firm_name, role_name, active, reg_date, signoff_init FROM user inner join role on user.accessLevel = role.id left join firm_user_log on firm_user_log.user_id = user.id left join firm_details on firm_details.id = firm_user_log.firm_id where client_id is null";
}
    

if($_SESSION['role'] == 1 || $_SESSION['role'] == -1){    
    $column = array('','name','firm_name','email','role_name','active','reg_date','signoff_init','edit','allocate');
}
elseif($_SESSION['role'] == 4 || $_SESSION['role'] == 2){
    $column = array('','name','email','role_name','active','reg_date','signoff_init','edit','allocate');
}
else{
    $column = array('','name','email','role_name','active','reg_date','signoff_init');
}

if(isset($_POST["search"]["value"]) && !empty($_POST["search"]["value"]))
{
    if($_SESSION['role'] == 1 || $_SESSION['role'] == -1){
        $query .= ' and ( name LIKE "%'.$_POST["search"]["value"].'%"';
        $query .= ' or email LIKE "%'.$_POST["search"]["value"].'%"';
        $query .= ' or firm_name LIKE "%'.$_POST["search"]["value"].'%"';
        $query .= ' or role_name LIKE "%'.$_POST["search"]["value"].'%"';
        $query .= ' or reg_date LIKE "%'.$_POST["search"]["value"].'%" ) ';
    }
    else{
        $query .= ' and ( name LIKE "%'.$_POST["search"]["value"].'%"';
        $query .= ' or email LIKE "%'.$_POST["search"]["value"].'%"';
        $query .= ' or role_name LIKE "%'.$_POST["search"]["value"].'%"';
        $query .= ' or reg_date LIKE "%'.$_POST["search"]["value"].'%" ) ';
    }
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
 {
    if($_SESSION['role'] == 1 || $_SESSION['role'] == -1){  
        $sub_array[] = $row['firm_name'];
    }
 }
 $sub_array[] = $row['email'];
 $sub_array[] = $row['role_name'];
    {
        if($row['active'] == 1){
            $sub_array[] = "<span class='badge badge-success small id= '".$row['active']."''>Allowed</span>";;
        }
        else{
            $sub_array[] = "<span class='badge badge-danger small id= '".$row['active']."''>Access Denied</span>";;
        }
    }
 $sub_array[] = $row['reg_date'];
 $sub_array[] = $row['signoff_init'];
 
 if($_SESSION['role'] != 3 && $_SESSION['role'] != 5){       
    if($_SESSION['id'] != $row['id']){
        if($_SESSION['role'] == 2){
            $accessLevel = $con->query("select accessLevel from user where id = ".$row['id'])->fetch_assoc()['accessLevel'];
            if($accessLevel == 3){
                $sub_array[] = "<label class='mt-2'><span class='helpDesign help_3'>3</span></label>&nbsp;<a href='#' class='icon-hide'><img class='datatable-icon editMember' src='../Icons/edit-1.svg' id='".$row['id']."' style='width: 35% !important;'><img class='datatable-icon editMember' src='../Icons/edit-2.svg' id='".$row['id']."' style='width: 35% !important;'></a>";
                $sub_array[] = "<label class='mt-2'><span class='helpDesign help_4'>4</span></label><a href='#' class='badge badge-primary allocate' id='".$row['id']."'></i>ALLOCATE</a>";
            }
            else{
                $sub_array[] = "";
                $sub_array[] = "";
            }
        }
        elseif($_SESSION['role'] == -1){
            $accessLevel = $con->query("select accessLevel from user where id = ".$row['id'])->fetch_assoc()['accessLevel'];
            if($accessLevel == 1){
                $sub_array[] = "<label class='mt-2'><span class='helpDesign help_3'>3</span></label>&nbsp;<a href='#' class='icon-hide'><img class='datatable-icon editMember' src='../Icons/edit-1.svg' id='".$row['id']."' style='width: 35% !important;'><img class='datatable-icon editMember' src='../Icons/edit-2.svg' id='".$row['id']."' style='width: 35% !important;'></a>";
                $sub_array[] = "";
            }
            else{
                $sub_array[] = "<label class='mt-2'><span class='helpDesign help_3'>3</span></label>&nbsp;<a href='#' class='icon-hide'><img class='datatable-icon editMember' src='../Icons/edit-1.svg' id='".$row['id']."' style='width: 35% !important;'><img class='datatable-icon editMember' src='../Icons/edit-2.svg' id='".$row['id']."' style='width: 35% !important;'></a>";
                $sub_array[] = "<label class='mt-2'><span class='helpDesign help_4'>4</span></label><a href='#' class='badge badge-primary allocate' id='".$row['id']."'></i>ALLOCATE</a>";
            }
        }
        else{
            $sub_array[] = "<label class='mt-2'><span class='helpDesign help_3'>3</span></label>&nbsp;<a href='#' class='icon-hide'><img class='datatable-icon editMember' src='../Icons/edit-1.svg' id='".$row['id']."' style='width: 35% !important;'><img class='datatable-icon editMember' src='../Icons/edit-2.svg' id='".$row['id']."' style='width: 35% !important;'></a>";
            $sub_array[] = "<label class='mt-2'><span class='helpDesign help_4'>4</span></label><a href='#' class='badge badge-primary allocate' id='".$row['id']."'></i>ALLOCATE</a>";
        }
    }
    else{
        $sub_array[] = "";
        $sub_array[] = "";
    }
 }
 
 $data[] = $sub_array;
}
function get_all_data($con)
{   
 $query = "SELECT count(id) total FROM user where accessLevel > '".$_SESSION['role']."' and client_id is null";
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