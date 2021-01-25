<?php 
include 'dbconnection.php';
session_start();
if (!isset($_SESSION['email']) && empty($_SESSION['email'])) {
    header("Location: index");
}
if(isset($_GET['wid']) && !empty($_GET['wid'])){
    $wid = trim($_GET['wid']);
}
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
    <table hidden class="table table-stripped">
        <thead>
            <tr>
                <th scope="col">Sl No.</th>
                <th scope="col">Account Name</th>
                <th scope="col">Description</th>
                <th scope="col">Client Assign</th>
                <th scope="col">Documents Uploaded</th>
                <th scope="col">Requested By</th>
                <th scope="col">Date Requested</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query = "SELECT accounts.account,description,user.name name,accounts_log_docs.documents,request,date FROM `accounts_log` inner join accounts on accounts_log.accounts_id=accounts.id inner join user on accounts_log.client_contact_id=user.id inner join accounts_log_docs on accounts_log.id=accounts_log_docs.accounts_log_id where accounts_log.workspace_id=$wid";
            $result = $con->query($query);
            while($row = $result->fetch_assoc()){
                ?>
                    <tr>
                        <td><?php echo ++$i; ?></td>
                        <td><?php echo $row['account']; ?></td>
                        <td><?php echo $row['description']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['documents']; ?></td>
                        <td><?php echo $row['request']; ?></td>
                        <td><?php echo $row['date']; ?></td>
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
        $("table").table2excel({ 
            filename: "<?php  $result = $con->query("select name,datefrom,dateto from workspace inner join client on workspace.client_id=client.id where workspace.id=$wid")->fetch_assoc();
                echo $result['name'].' -Request Client Assistance- ('.$result['datefrom'].' to '.$result['dateto'].')'; ?>.xls"
        });
        setTimeout(() => {
            window.close();
        }, 1000);
    });
    </script>
</body>