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
    <link rel="icon" href="Icons/fav.png" type="image/gif" sizes="16x16">

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="css/custom.css" rel="stylesheet">

</head>

<body style="overflow-y: scroll">
    <table hidden class="table table-stripped">
        <thead>
            <tr>
                <th>Sl No.</th>
                <th>Program Name</th>
                <th>Comment Count</th>
                <th>Files Count</th>
                <th>Prepare Sign off Count</th>
                <th>Review Sign off Count</th>
            </tr>
        </thead>
        <tbody>
            <?php

            $query = "SELECT a.program_name,a.active,
            (SELECT COUNT(*) FROM signoff_files_log WHERE file !='' and signoff_files_log.prog_id = a.id and signoff_files_log.workspace_id=$wid) as FileCount,
            (SELECT COUNT(*) FROM signoff_comments_log WHERE comments !='' and signoff_comments_log.prog_id = a.id and signoff_comments_log.workspace_id=$wid) as CommentCount,
            (SELECT COUNT(*) FROM signoff_prepare_log WHERE signoff_prepare_log.prepare_signoff_date !='' and signoff_prepare_log.prog_id= a.id and signoff_prepare_log.workspace_id=$wid) as PrepareCount,
            (SELECT COUNT(*) FROM signoff_review_log WHERE signoff_review_log.review_signoff_date !='' and signoff_review_log.prog_id= a.id and signoff_review_log.workspace_id=$wid) as ReviewCount
            FROM (SELECT DISTINCT program.id,program_name,active FROM program inner join workspace_log on program.id=workspace_log.program_id where hasChild = 0 and workspace_log.workspace_id =$wid) a";

            $result = $con->query($query);
            $i = 0;
            while($row = $result->fetch_assoc()){
                ?>
                    <tr>
                        <td><?php echo ++$i; ?></td>
                        <td><?php echo $row['program_name']; ?></td>
                        <?php 
                            if($row['active']){
                        ?>
                        <td><?php echo $row['CommentCount']; ?></td>
                        <td><?php echo $row['FileCount']; ?></td>
                        <td><?php echo $row['PrepareCount']; ?></td>
                        <td><?php echo $row['ReviewCount']; ?></td>
                                <?php 
                            }
                            else{
                                ?>
                                <td>NA</td>
                                <td>NA</td>
                                <td>NA</td>
                                <td>NA</td>                                
                                <?php
                            }
            ?>
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
            // filename: "Diagnostic Report <?php // echo date_format(date_create("now", new DateTimeZone('Asia/Kolkata')), "d-m-Y H:m:s"); ?>.xls"
            filename: "<?php  $result = $con->query("select name,datefrom,dateto from workspace inner join client on workspace.client_id=client.id where workspace.id=$wid")->fetch_assoc();
            echo $result['name'].'('.$result['datefrom'].' to '.$result['dateto'].')'; ?>.xls"
        });
        setTimeout(() => {
            window.close();
        }, 500);
    });
    </script>
</body>