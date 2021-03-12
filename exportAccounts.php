<?php 
include 'dbconnection.php';
session_start();
if (!isset($_SESSION['email']) && empty($_SESSION['email'])) {
    header("Location: index");
}
if(isset($_GET['wid']) && !empty($_GET['wid'])){
    $wid = trim($_GET['wid']);
}

$prog_id = $_GET["pid"];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title> <?php echo strtoupper($_SESSION['name'] . " Dashboard"); ?> </title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Custom Fav icon -->
    <!-- <link rel="icon" href="img/atllogo.png" type="image/gif" sizes="16x16"> -->

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="css/custom.css" rel="stylesheet">

</head>


<body style="overflow-y: scroll">

    <table hidden class="table table-stripped" id="tb1">
        <thead>
            <tr>
                <th></th>
                <th colspan=3>
                    <?php if($prog_id == 239){
                        ?> Balance Assets Scope
                    <?php } 
                    else{
                        ?> PL- Income Scope
                    <?php }
                    ?>
                </th>
                <th></th>
                <th colspan=3>
                    <?php if($prog_id == 239){
                        ?> Balance Liability Scope
                    <?php } 
                    else{
                        ?> PL- Expense Scope
                    <?php }
                    ?>
                </th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $query1 = "select pl_income, pl_expense,balance_asset, balance_liability from sub_materiality where workspace_id = '$wid'";
            $result1 = $con->query($query1);
            while($row1 = $result1->fetch_assoc()){
            ?>
            <tr>
                <td></td>
                <td colspan=3>
                    <?php 
                        if($prog_id == 239)
                        echo $row1['balance_asset'];
                        else 
                        echo $row1['pl_income'];
                    ?>
                </td>
                <td></td>
                <td colspan=3>
                    <?php 
                        if($prog_id == 239)
                        echo $row1['balance_liability'];
                        else 
                        echo $row1['pl_expense'];
                     ?>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    <!-- </table> -->

    <!-- <table hidden class="table table-stripped" id="tb2"> -->
        <thead>
            <tr></tr>
            <tr>
                <th scope="col">Sl No.</th>
                <th scope="col">Asset Accounts</th>
                <th scope="col">Amount</th>
                <th scope="col">Type</th>
                <th scope="col">Risk</th>
                <th scope="col">Import</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query = "select assets_liabilities_check.program_name,workspace_log.amount, workspace_log.type, workspace_log.risk, workspace_log.import from program inner join workspace_log on program.id=workspace_log.program_id inner join assets_liabilities_check on assets_liabilities_check.id=program.id where program.parent_id=2 and workspace_log.workspace_id=$wid order by program._seq";
            $result = $con->query($query);
            while($row = $result->fetch_assoc()){
                ?>
                <tr>
                    <td><?php echo ++$i; ?></td>
                    <td><?php echo $row['program_name']; ?></td>
                    <td><?php echo $row['amount']; ?></td>
                    <td><?php echo $row['type']; ?></td>
                    <td><?php echo $row['risk']; ?></td>
                    <td><?php echo $row['import']; ?></td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>

    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/jstree.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- For export -->
    <script src="http://cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js"></script> 
    <script src="js/custom.js"></script>
    <script>
    $(document).ready(function() {
            $("#tb1").table2excel({ 
            filename: "<?php echo $prog_id == 239?"Balance Sheet":"Profit & Loss"; ?>"+".xls"
        });
        setTimeout(() => {
            window.close();
        }, 1000);
    });
    </script>
</body>