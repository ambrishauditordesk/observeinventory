<!DOCTYPE html>
<html lang="en">

<head>
    <title>Audit-EDG</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
            href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
            rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="css/custom.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>
    <!-- Page level plugins -->
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <!-- Page level custom scripts -->
    <script src="js/demo/datatables-demo.js"></script>
    <script src="js/custom.js"></script>



<?php
include 'dbconnection.php';
session_start();

$from = trim($_POST['from']);
$to = trim($_POST['to']);
$clientID = trim($_POST['clientID']);

$query = "insert into workspace(client_id,datefrom,dateto) values('$clientID','$from','$to')";

if ($con->query($query) === true) {
    
    echo "<script>
    $(document).ready(function() {
        $('#successModal').modal();
    });
    </script>";
} 
else {
    echo "<script>
    $(document).ready(function() {
        $('#unsuccessModal').modal();
    });
    </script>";
}
?>

<!--Success Modal-->
<div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5>Hey <?php echo $_SESSION['name']; ?>!</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Successfully added Workspace</div>
            <div class="modal-footer">
                <a class="btn btn-primary" href="workspace?cid=<?php echo $clientID?>">OK</a>
            </div>
        </div>
    </div>
</div>  

 <!--Unsuccess Modal-->
 <div class="modal fade" id="unsuccessModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Workspace Addition Failed.</div>
            <div class="modal-footer">
                <a class="btn btn-primary" href="workspace.php">OK</a>
            </div>
        </div>
    </div>
</div> 


