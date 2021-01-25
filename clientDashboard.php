<?php
include 'dbconnection.php';
session_start();
if (!isset($_SESSION['email']) && empty($_SESSION['email'])) {
    header("Location: index");
}
$clientName = $_SESSION['cname'];
$wid = $_GET['wid'];
$_SESSION['breadcrumb'] = array();
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
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="css/custom.css" rel="stylesheet">

</head>

<body style="overflow-y: scroll">

    <div id="wrapper" class="">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index">
                <div class="sidebar-brand-icon">
                    <a class="navbar-brand navbar-logo" href="admin/clientList">Audit-EDG</a>
                </div>
            </a>
            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">
            <!-- Nav Item - Pages Collapse Menu -->
            <li id="employees" class="nav-item active">
                <a class="nav-link" href="#">
                    <i class="fas fa-fw fa-dolly-flatbed"></i>
                    <span>Audit Program</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseAdmin" aria-expanded="true" aria-controls="collapsePages">
                    <i class="fas fa-fw fa-folder"></i>
                    <span>Quick Links</span>
                </a>
                <div id="collapseAdmin" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Quick Links</h6>
                        <a class="collapse-item" href="subProgram.php?pid=1&parent_id=0&wid=<?php echo $wid; ?>">Trial Balance CY</a>
                        <a class="collapse-item" href="subProgram.php?pid=1&parent_id=0&wid=<?php echo $wid; ?>">Trial Balance PY</a>
                        <a class="collapse-item" href="subProgram.php?pid=3&parent_id=0&wid=<?php echo $wid; ?>">Financial Statements CY</a>
                        <a class="collapse-item" href="subProgram.php?pid=3&parent_id=0&wid=<?php echo $wid; ?>">Financial Statements PY</a>
                        <a class="collapse-item" href="subProgram.php?pid=1&parent_id=0&wid=<?php echo $wid; ?>">Client Assistance Schedule</a>
                        <a class="collapse-item" href="subProgram.php?pid=3&parent_id=0&wid=<?php echo $wid; ?>">Opinion CY</a>
                        <a class="collapse-item" href="subProgram.php?pid=3&parent_id=0&wid=<?php echo $wid; ?>">Opinion PY</a>
                    </div>
                </div>
            </li>
            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">
            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>
        </ul>

        <div id="content-wrapper" class="d-flex flex-column">
            <div class="content">
                <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-mainbg">
        <!-- Topbar Navbar -->
        <ul class="navbar-nav ml-auto">
            <?php 
                if($_SESSION['role'] != 3){
                    ?>
                    <li class="nav-item d-flex">
                        <a class="nav-link d-flex align-items-center" target="_blank" href="diagnosticReport?wid=<?php echo $wid; ?>">
                            <span>Diagnostic Report</span>&nbsp;&nbsp;
                            <i class="fas fa-download fa-1x"></i>
                        </a>
                    </li>
                    <li class="nav-item d-flex">
                        <a class="nav-link d-flex align-items-center" href="admin/members.php">
                            <span>Members</span>&nbsp;&nbsp;
                            <i class="fas fa-users fa-1x"></i>
                        </a>
                    </li>
            <?php } 
            ?>
            <li class="nav-item d-flex">
                <a class="nav-link d-flex align-items-center" href="admin/clientList">
                    <span>List Clients</span>&nbsp;&nbsp;
                    <i class="fas fa-list fa-1x"></i>
                </a>
            </li>
            <li class="nav-item dropdown no-arrow ">
                <a class="nav-link dropdown-toggle d-flex justify-contents-center" href="#" id="userDropdown"
                    role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="d-flex align-items-center">
                        <span><?php echo $_SESSION['name']; ?>&nbsp;</span>
                        <span class="rounded-circle d-flex justify-contents-center">
                            <i class="fas fa-user-circle fa-2x" aria-hidden="true"></i>
                        </span>
                    </div>

                </a>
                <!-- Dropdown - User Information -->
                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                    <!-- <a class="dropdown-item" href="#" data-toggle="modal" data-target="#changePasswordModal">
                            <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                            Change Password
                        </a>
                        <div class="dropdown-divider"></div> -->
                    <a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
                </div>
            </li>
        </ul>
    </nav>
                <!-- HEADER -->
                <div id="header">
                    <div class="container-fluid shadow border border-bottom" stickylevel="0" style="z-index:1200;">
                        <div class="row pt-1">
                            <div class="row text-center cdrow" href="#">
                                <h2><?php echo strtoupper($clientName . " - Dashboard"); ?></h2>
                            </div>
                        </div>
                    </div><br>
                    <!-- Body Starts -->
                    <div class="container-fluid">
                        <div class="col-md-12 d-flex" style="align-items:center;">
                            <h1 class="col-md-4">Audit Programme</h1>
                            <?php
                                $querys1 = $con->query("select count(program.id) cnt from program inner join workspace_log on program.id=workspace_log.program_id where workspace_log.workspace_id=1 and workspace_log.status=1")->fetch_assoc()['cnt'];
                                $querys = $con->query("select count(program.id) cnt from program inner join workspace_log on program.id=workspace_log.program_id where workspace_log.workspace_id=1")->fetch_assoc()['cnt'];
                                $per = 0;
                                if($querys != 0){
                                    $per = ($querys1/$querys)*100;
                                }
                            ?>
                            <div class="progress col-md-8 p-0" style="height:30px;">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="<?php echo ceil($per); ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo ceil($per); ?>%; color:<?php if(ceil($per) == 0) echo "#000"; else echo "#fff"; ?>;"><?php echo ceil($per)."%"; ?></div>
                            </div>
                        </div><br>
                        <div class="col-md-12 d-flex" style="flex-direction:column;">
                            <?php
                            $query = "select program.* from program inner join workspace_log on program.id=workspace_log.program_id where program.parent_id='0' and workspace_log.workspace_id='$wid' order by _seq";
                            $exquery = $con->query($query);
                            if ($exquery->num_rows != 0) {
                                while ($queryrow = $exquery->fetch_assoc()) {?>
                            <div class="list-group col-md-12" style="flex-direction:row; align-items:center;">
                                <div class="col-md-6">
                                <a href="subProgram.php?pid=<?php echo $queryrow['id']; ?>&parent_id=<?php echo $queryrow['parent_id']; ?>&wid=<?php echo $wid; ?>"
                                    class="list-group-item list-group-item-action"><b><?php echo trim($queryrow['program_name']); ?></b></a>
                                </div>
                                <?php
                                    $querys1 = $con->query("select count(program.id) cnt from program inner join workspace_log on program.id=workspace_log.program_id where parent_id='".$queryrow['id']."' and workspace_log.workspace_id='$wid' and workspace_log.status=1")->fetch_assoc()['cnt'];
                                    $querys = $con->query("select count(program.id) cnt from program inner join workspace_log on program.id=workspace_log.program_id where parent_id='".$queryrow['id']."' and workspace_log.workspace_id='$wid'")->fetch_assoc()['cnt'];
                                    $per = 0;
                                    if($querys != 0){
                                        $per = ($querys1/$querys)*100;
                                    }
                                ?>
                                <div class="progress col-md-6 p-0" style="height:30px;">
                                    <div class="progress-bar" role="progressbar" style="width: <?php echo ceil($per); ?>%; color: <?php if(ceil($per) == 0) echo "#000"; else echo "#fff"; ?>;" aria-valuenow="<?php echo ceil($per); ?>" aria-valuemin="0" aria-valuemax="100"><?php echo ceil($per)."%"; ?></div>
                                </div>
                            </div>
                            <?php }}
                                ?>
                                <br>
                        </div>
                        <?php
                        $status = $con->query("select status from workspace_log where workspace_id=$wid and program_id=248")->fetch_assoc()['status'];
                        if($status && $_SESSION['role'] != '3'){
                            ?>
                            <div class="col-md-12 d-flex justify-content-center">
                                <button id="freeze" type="button" class="btn btn-lg btn-warning">Freeze Workspace</button>
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
            <!-- Footer -->
            <footer class="sticky-footer bg-light">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span><strong><span style="color: #8E1C1C;">Audit-EDG </span>&copy;
                                <?php echo date("Y"); ?></strong></span>
                    </div>
                </div>
            </footer>
        </div>

    </div> 

    <!-- Subprogram Open Modal-->
    <div class="modal fade" id="spOpenModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="container card bg-light font-2 py-2">
                        <div class="row d-flex justify-content-between">
                            <div class="col-md-6">
                                <h5>Obtain Client Acceptance Engagement Letter</h5>
                            </div>
                            <div class="col-md-6 text-right">
                                <a class="btn btn-outline-dark btn-sm py-0 menu-02">
                                    Sign-Off
                                </a>
                                <a class="btn btn-outline-dark btn-sm py-0 menu-02">
                                    Review
                                </a>
                            </div>
                        </div>
                        <div class="row d-flex justify-content-between">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-8">
                                        <label>Documents</label>
                                    </div>
                                    <div class="col-md-12 text-right">
                                        <a class="btn btn-outline-dark btn-sm py-0 menu-02">
                                            <i class="fas fa-upload upload"></i>
                                        </a>
                                    </div>
                                </div>
                                <ul class="list-group h5">
                                    <li class="list-group-item"></li>
                                    <li class="list-group-item"></li>
                                    <li class="list-group-item"></li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <label>Comments</label>
                                <textarea id="comments" class="form-control" style="height:200px;"></textarea>
                            </div>
                        </div>



                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>


    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/jstree.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>
            <!-- sweetalert cdn -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous"></script>

    <script src="js/custom.js"></script>
    <script>
    $(document).ready(function() {
        var i = 1;
        b = i - 1;
        $("#add_row").click(function() {
            $('#addr' + i).html($('#addr' + b).html()).find('td:first-child');
            $('#tab_logic').append('<tr id="addr' + (i + 1) + '"></tr>');
            i++;
        });
        //Delete Row Function for sales add form
        $("#delete_row").click(function() {
            if (i > 1) {
                $("#addr" + (i - 1)).html('');
                i--;
            }
        });

        $(document).on('click','#freeze',function(){
            $.ajax({
                url: 'freeze.php',
                type: 'POST',
                data: {id: <?php echo $wid; ?>,freeze: 1},
                success: function(data){
                    if (data) {
                            swal({
                                icon: "success",
                                text: "Thank You for Freezing",
                            }).then(function (isConfirm) {
                                if (isConfirm) {
                                    window.location.href = "workspace?cid=<?php echo $_SESSION['client_id']; ?>";
                                }
                            });
                        }
                }
            })
        })
    });
    </script>
</body>