<?php 
include 'dbconnection.php';
include 'moneyFormatter.php';
if(!isset($_SESSION)){
       session_start();
    }
if (!isset($_SESSION['email']) && empty($_SESSION['email'])){
    header("Location: ../index");    
}
$wid = $_POST['wid'];
$query = "SELECT * FROM trial_balance where 1 and workspace_id = '$wid' ";

// $column = array('','account_number','account_name','cy_beg_bal','cy_interim_bal','cy_activity','cy_end_bal','client_adjustment','audit_adjustment','cy_final_bal','account_type','account_class','financial_statement');
$column = array('account_number','account_name','cy_beg_bal','cy_final_bal','account_type','account_class','financial_statement');

if(isset($_POST["search"]["value"]) && !empty($_POST["search"]["value"])){
    $query .= ' and account_number LIKE "%'.$_POST["search"]["value"].'%"';
}
if(isset($_POST['order'])){
    $query .= ' ORDER BY '.$column[$_POST['order']['0']['column']].' '.$_POST['order']['0']['dir'].' ';
}
else{
    $query .= ' ORDER BY id ';
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
// $sub_array[] = '';
$sub_array[] = $row['account_number'];
$sub_array[] = $row['account_name'];
$sub_array[] = numberToCurrency($row['cy_beg_bal']);
// $sub_array[] = $row['cy_interim_bal'];
// $sub_array[] = $row['cy_activity'];
// $sub_array[] = numberToCurrency($row['cy_end_bal']);
// $sub_array[] = $row['client_adjustment'];
// $sub_array[] = $row['audit_adjustment'];
$sub_array[] = numberToCurrency($row['cy_final_bal']);
$sub_array[] = $row['account_type'];
$sub_array[] = $row['account_class'];
$sub_array[] = $row['financial_statement'];
$data[] = $sub_array;
}
function get_all_data($con)
{
$wid = $_POST['wid'];
$query = "SELECT count(id) total FROM trial_balance where workspace_id = $wid";
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