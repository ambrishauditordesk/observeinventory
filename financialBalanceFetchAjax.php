<?php 
include 'dbconnection.php';
session_start();
if (!isset($_SESSION['email']) && empty($_SESSION['email'])){
    header("Location: ../index");    
}
$wid = $_POST['wid'];
$query = "SELECT max(account_type) account_type, max(account_class) account_class, max(financial_statement) financial_statement, sum(cy_final_bal) cy_final_bal, sum(cy_beg_bal) cy_beg_bal FROM `trial_balance` where workspace_id = $wid group by account_type, account_class, financial_statement";

$column = array('','account_type','account_class','financial_statement','cy_final_bal','cy_beg_bal');

if(isset($_POST["search"]["value"]) && !empty($_POST["search"]["value"])){
    $query .= ' and financial_statement LIKE "%'.$_POST["search"]["value"].'%"';
}
if(isset($_POST['order'])){
    $query .= ' ORDER BY '.$column[$_POST['order']['0']['column']].' '.$_POST['order']['0']['dir'].' ';
}
else{
    $query .= ' ORDER BY account_type ';
}

$query1 = '';
if($_POST["length"] != -1){
    $query1 = ' LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
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
$sub_array[] = $row['account_type'];
$sub_array[] = $row['account_class'];
$sub_array[] = $row['financial_statement'];
$sub_array[] = $row['cy_final_bal'];
$sub_array[] = $row['cy_beg_bal'];
$diff = $row['cy_final_bal']-$row['cy_beg_bal'];
if($diff < 0 )
    $sub_array[] = "<label class='red-color'>".$diff."</label>";
else
    $sub_array[] = "<label class='green-color'>".$diff."</label>";
$data[] = $sub_array;
}
function get_all_data($con)
{
$wid = $_POST['wid'];
$query = "SELECT max(account_type) account_type, max(account_class) account_class, max(financial_statement) financial_statement, sum(cy_final_bal) cy_final_bal, sum(cy_beg_bal) cy_beg_bal FROM `trial_balance` where workspace_id = $wid group by account_type, account_class, financial_statement";
$statement = $con->query($query)->num_rows;
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