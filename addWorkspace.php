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
    <link href="css/custom.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- JQuery CDN -->
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    
    <!-- Datatable CDN -->
    <link href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css" rel="stylesheet">
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous"></script>
</head>

<body>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>
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

if ($con->query($query) === true) 
{
    $wid = $con->insert_id;
    $query1 = "insert into workspace_log(workspace_id,program_id) select '$wid' as workspace_id, id from program where def_prog=1";
    $query2="insert into materiality(name,prog_id,workspace_id) SELECT name,prog_id,'$wid' workspace_id from materiality where def_prog='1'";
    $query3="insert into sub_materiality(workspace_id) values ('$wid')";


    $res1=$con->query($query1);
    $res2=$con->query($query2);
    $res3=$con->query($query3);
    if($res1 === false && $res2 === false && $res3 === false)
    {
        $con->query("delete from workspace where id='$wid");
        $con->query("delete from workspace_log where workspace_id='$wid'");
        $con->query("delete from materiality where workspace_id='$wid'");
        echo "<script>
        $(document).ready(function() {
            $('#unsuccessModal').modal();
        });
        </script>";
    }
    else {

        $res4 = $con->query("SELECT * FROM inquiring_of_management_questions");
        while($row = $res4->fetch_assoc()){
            $question_data = $row['question'];
            $con->query("INSERT INTO inquiring_of_management_questions_answer(workspace_id, inquiring_of_management_questions, answer_option, answer_textarea) VALUES('$wid','$question_data','','')");
        }

        $res4 = $con->query("SELECT * FROM going_concern_default_procedure");
        while($row = $res4->fetch_assoc()){
            $procedure_data = $row['procedure'];
            $part = $row['part'];
            $con->query("INSERT INTO going_concern_procedures(workspace_id, procedure_data, free_text, part) VALUES('$wid','$procedure_data','','$part')");
        }

        $res4 = $con->query("SELECT * FROM going_concern_default_conclusion");
        while($row = $res4->fetch_assoc()){
            $conclusion_text = $row['conclusion_text'];
            $con->query("INSERT INTO going_concern_conclusion(workspace_id, going_concern_conclusion_data) VALUES('$wid','$conclusion_text')");
        }

        $clientName = $con->query("select name from client where id = $clientID")->fetch_assoc()['name'];
        
        // shell_exec('mkdir -p uploads/'.$clientID.$clientName.'/'.$wid.'/');
        // shell_exec('chmod -R 777 uploads/'.$clientID.$clientName.'/'.$wid.'/');

        echo "<script>
    $(document).ready(function() {
        $('#successModal').modal();
    });
    </script>";
    }
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


