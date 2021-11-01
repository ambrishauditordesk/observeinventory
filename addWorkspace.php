<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="icon" href="https://ksacademy.co.in/images/chartered_accountants/ca.png">

    <title>Auditors Desk</title>
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
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'dbconnection.php';
if(!isset($_SESSION)){
       session_start();
    }

$fromDate = trim($_POST['from']);
$toDate = trim($_POST['to']);
$clientID = trim($_POST['clientID']);
$flag = 0;

$check = $con->query("select * from workspace where client_id = '$clientID' and datefrom = '$fromDate' and dateto = '$toDate'")->num_rows;

if($check == 0){
    $subscription = $con->query("select subscribed_workspace,used_workspace from firm_details where id =".$_SESSION['firm_id'])->fetch_assoc();
    $from=date_create($fromDate);
    $to=date_create($toDate);
    $diff=date_diff($from,$to);
    $diff = (int)$diff->format("%R%a");
    if( $diff > 0 && $diff <= 730){
        if($subscription['subscribed_workspace'] > $subscription['used_workspace']){
            $con->query("insert into workspace(client_id,datefrom,dateto) values('$clientID','$fromDate','$toDate')");
            $flag = 1;
        
            $wid = $con->insert_id;
            $query1 = "insert into workspace_log(workspace_id,program_id) select '$wid' as workspace_id, id from program where def_prog=1";
            $query2= "insert into materiality(name,prog_id,workspace_id) SELECT name,prog_id,'$wid' workspace_id from materiality where def_prog='1'";
            $query3= "insert into sub_materiality(workspace_id) values ('$wid')";
            $con->query("update firm_details set used_workspace = used_workspace+1 where id =".$_SESSION['firm_id']);       
        
        
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
        
                $clientName = $con->query("select added_by_id, added_by_date, name from client where id = $clientID")->fetch_assoc();
                $name = str_replace(' ', '', $clientName['name']);
        
                if($_SESSION['role'] == 1 || $_SESSION['role'] == -1){
                    $firmId = $con->query("select firm_details.id id from user inner join user_client_log on user_client_log.user_id = user.id inner join firm_user_log on user.id = firm_user_log.user_id inner join firm_details on firm_user_log.firm_id = firm_details.id where user_client_log.client_id = $clientID and user.accessLevel = 4")->fetch_assoc()['id'];
                    shell_exec('mkdir -p uploads/'.$firmId.'/'.escapeshellarg($clientID).$name.'/'.$wid.'/');
                    shell_exec('sudo chown -R root:root uploads/'.$firmId.'/'.escapeshellarg($clientID).$name.'/'.$wid.'/');
                    shell_exec('sudo chmod -R 777 uploads/'.$firmId.'/'.escapeshellarg($clientID).$name.'/'.$wid.'/');
                }
                else{
                    shell_exec('mkdir -p uploads/'.$_SESSION['firm_id'].'/'.escapeshellarg($clientID).$name.'/'.$wid.'/');
                    shell_exec('sudo chown -R root:root uploads/'.$_SESSION['firm_id'].'/'.escapeshellarg($clientID).$name.'/'.$wid.'/');
                    shell_exec('sudo chmod -R 777 uploads/'.$_SESSION['firm_id'].'/'.escapeshellarg($clientID).$name.'/'.$wid.'/');
                }
        
            echo "<script>
            $(document).ready(function() {
                $('#successModal').modal({backdrop: 'static', keyboard: false});
            });
            </script>";
            }
        } 
        else{
            echo "<script>
                $(document).ready(function() {
                    $('#unsuccessModal').modal({backdrop: 'static', keyboard: false});
                });
            </script>";
        }
    }
    else{
        echo "<script>
            $(document).ready(function() {
                $('#unsuccessWorkspaceModal').modal({backdrop: 'static', keyboard: false});
            });
        </script>";
    }
}
else{
    echo "<script>
    $(document).ready(function() {
        $('#unsuccessDateModal').modal({backdrop: 'static', keyboard: false});
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
            </div>
            <div class="modal-body">Successfully added Workspace</div>
            <div class="modal-footer">
                <a class="btn btn-primary" href="workspace?fid=<?php echo base64_encode(md5($clientID)); ?>&xid=<?php echo base64_encode(md5($clientID)); ?>&uid=<?php echo base64_encode(md5($clientID)); ?>&cid=<?php echo base64_encode($clientID); ?>&aid=<?php echo base64_encode(md5($clientID)); ?>&zid=<?php echo base64_encode(md5($clientID)); ?>&qid=<?php echo base64_encode(md5($clientID)); ?>">OK</a>
            </div>
        </div>
    </div>
</div>  

 <!--Unsuccess Modal-->
 <div class="modal fade" id="unsuccessModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Hey <?php echo $_SESSION['name']; ?>!</h5>
            </div>
            <div class="modal-body">
                <?php
                    if($_SESSION['role'] != 4){
                ?>
                    Workspace Addition Failed! Kindly contact your Firm Admin.
                <?php 
                } else {
                    ?>  
                    Workspace Addition Failed! Kindly buy more WORKSPACE.
                <?php 
                }
                ?>
            </div>
            <div class="modal-footer justify-content-center">
                <?php
                    if($_SESSION['role'] != 4){
                ?>
                    <a class="btn btn-primary" href="workspace?fid=<?php echo base64_encode(md5($clientID)); ?>&xid=<?php echo base64_encode(md5($clientID)); ?>&uid=<?php echo base64_encode(md5($clientID)); ?>&cid=<?php echo base64_encode($clientID); ?>&aid=<?php echo base64_encode(md5($clientID)); ?>&zid=<?php echo base64_encode(md5($clientID)); ?>&qid=<?php echo base64_encode(md5($clientID)); ?>">OK</a>
                <?php 
                } else {
                    ?> 
                    <a class="btn btn-success" href="settings">BUY MORE!</a>
                    <a class="btn btn-primary" href="workspace?fid=<?php echo base64_encode(md5($clientID)); ?>&xid=<?php echo base64_encode(md5($clientID)); ?>&uid=<?php echo base64_encode(md5($clientID)); ?>&cid=<?php echo base64_encode($clientID); ?>&aid=<?php echo base64_encode(md5($clientID)); ?>&zid=<?php echo base64_encode(md5($clientID)); ?>&qid=<?php echo base64_encode(md5($clientID)); ?>">OK</a>
                <?php 
                }
            ?> 
            </div>
        </div>
    </div>
</div> 

<!--Unsuccess Modal-->
<div class="modal fade" id="unsuccessWorkspaceModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Hey <?php echo $_SESSION['name']; ?>!</h5>
            </div>
            <div class="modal-body">
                Incorect Date! Date must be in a range of maximum 2 years.
            </div>
            <div class="modal-footer justify-content-center">
                <a class="btn btn-primary" href="workspace?fid=<?php echo base64_encode(md5($clientID)); ?>&xid=<?php echo base64_encode(md5($clientID)); ?>&uid=<?php echo base64_encode(md5($clientID)); ?>&cid=<?php echo base64_encode($clientID); ?>&aid=<?php echo base64_encode(md5($clientID)); ?>&zid=<?php echo base64_encode(md5($clientID)); ?>&qid=<?php echo base64_encode(md5($clientID)); ?>">OK</a>
            </div>
        </div>
    </div>
</div> 

<!--Unsuccess Modal-->
<div class="modal fade" id="unsuccessDateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Hey <?php echo $_SESSION['name']; ?>!</h5>
            </div>
            <div class="modal-body">
                Workspace Addition Failed! Workspace with same date can't be added.
            </div>
            <div class="modal-footer justify-content-center">
                <a class="btn btn-primary" href="workspace?fid=<?php echo base64_encode(md5($clientID)); ?>&xid=<?php echo base64_encode(md5($clientID)); ?>&uid=<?php echo base64_encode(md5($clientID)); ?>&cid=<?php echo base64_encode($clientID); ?>&aid=<?php echo base64_encode(md5($clientID)); ?>&zid=<?php echo base64_encode(md5($clientID)); ?>&qid=<?php echo base64_encode(md5($clientID)); ?>">OK</a>
            </div>
        </div>
    </div>
</div> 