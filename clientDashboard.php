<?php
include 'dbconnection.php';
session_start();
if (!isset($_SESSION['email']) && empty($_SESSION['email'])) {
    header("Location: index");
}
$clientName = $_SESSION['cname'];

$clientId = $_SESSION['client_id'];
$wid = base64_decode($_GET['wid']);

$_SESSION['fileLocation'] = 'uploads/'.$clientId.$clientName.'/'.$wid;

if($con->query("select * from workspace where id = $wid and client_id = $clientId")->num_rows == 0){
    header('Location: login');
}
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="css/custom.css" rel="stylesheet">
    <link href="css/uiux.css" rel="stylesheet" type="text/css">

</head>

<body style="overflow-y: scroll; height: 100% !important;" oncontextmenu="return false">


    <!-- SideBar -->
    <div class="sidenav">
        <div class="side-header">
            <!-- <div style="border-bottom:1px solid;"> -->
            <div>
                <img class="sidenav-icon" src="Icons/Group -1.svg"/> &nbsp;
                Audit Edg
            </div>
        </div>
        <div class="side-footer">
            <div class="side-body">
                <div class="dash">
                    <a href="clientDashboard?<?php echo md5(base64_encode($clientName)); ?>&gid=<?php echo md5(base64_encode($clientName)); ?>&fid=<?php echo md5(base64_encode($clientName)); ?>&eid=<?php echo md5(base64_encode($clientName)); ?>&pid=<?php echo base64_encode($queryrow['id']); ?>&cid=<?php echo md5(base64_encode($clientName)); ?>&bid=<?php echo md5(base64_encode($clientName)); ?>&aid=<?php echo md5(base64_encode($clientName)); ?>&parent_id=<?php echo base64_encode($queryrow['parent_id']); ?>&zid=<?php echo md5(base64_encode($clientName)); ?>&yid=<?php echo md5(base64_encode($clientName)); ?>&wid=<?php echo base64_encode($wid); ?>&xid=<?php echo md5(base64_encode($clientName)); ?>"><img class="sidenav-icon" src="Icons/pie-chart.svg" style="width:24px !important; height:24px !important;"/> &nbsp;
                    Workspace
                    </a>
                </div>
            </div>
            <div class="settings">
                <div class="settings-items-top-div">
                    <div class="settings-items d-flex justify-content-between align-items-center">
                        <a href="settings" class="text-decoration-none">
                            <img class="sidenav-icon" src="Icons/settings.svg" style="width:24px !important; height:24px !important;"/> &nbsp;Settings
                        </a>
                        <label class="d-flex justify-content-center align-items-center mt-2"><span class="helpDesign help_6">6</span></label>
                    </div>
                    <div id="helpButton" class="settings-items">
                        <a href="#" class="text-decoration-none"><img class="sidenav-icon" src="Icons/help-circle.svg" style="width:24px !important; height:24px !important;"/> &nbsp;
                        Help</a>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <a href="logout"><button type="button" class="btn btn-primary"><i class="fas fa-sign-out-alt"></i> Logout</button></a>
                    <label class="d-flex justify-content-center align-items-center mt-2"><span class="helpDesign help_7">7</span></label>
                </div>
            </div>
        </div>
    </div>

    <!-- Navbar -->
    <nav class="navbar sticky-top navbar-expand-lg navbar-mainbg border-bottom">
        <!-- Topbar Navbar -->
        <ul class="navbar-nav ml-auto">
            <?php 
                if($_SESSION['role'] != 3){
                    ?>
                    <li class="nav-item d-flex">
                        <label class="d-flex justify-content-center align-items-center mt-2"><span class="helpDesign help_5">5</span></label>
                        <a class="nav-link d-flex align-items-center" target="_blank" href="diagnosticReport?wid=<?php echo $wid; ?>">
                            <img class="nav-icon" src="Icons/download.jpg" style="height: 40px; width: 40px;" />&nbsp;&nbsp;
                            <span>Diagonistic Report</span>
                        </a>
                    </li>
                    <li class="nav-item d-flex">
                        <label class="d-flex justify-content-center align-items-center mt-2"><span class="helpDesign help_2">2</span></label>
                        <a class="nav-link d-flex align-items-center" href="admin/clientTeamMembers?sid=<?php echo md5(base64_encode($clientName)); ?>&gid=<?php echo md5(base64_encode($clientName)); ?>&fid=<?php echo md5(base64_encode($clientName)); ?>&eid=<?php echo md5(base64_encode($clientName)); ?>&cid=<?php echo base64_encode($_SESSION['client_id']); ?>&yid=<?php echo md5(base64_encode($clientName)); ?>&bid=<?php echo md5(base64_encode($clientName)); ?>&aid=<?php echo md5(base64_encode($clientName)); ?>&zid=<?php echo md5(base64_encode($clientName)); ?>&jid=<?php echo md5(base64_encode($clientName)); ?>&wid=<?php echo base64_encode($wid); ?>&xid=<?php echo md5(base64_encode($clientName)); ?>">
                        <img class="nav-icon" src="Icons/Group 4.svg"/>&nbsp;&nbsp;
                        <span>Team Members</span>
                        </a>
                    </li>
            <?php } 
            ?>
            <li class="nav-item d-flex">
            <label class="d-flex justify-content-center align-items-center mt-2"><span class="helpDesign help_3">3</span></label>
                <a class="nav-link d-flex align-items-center" href="admin/clientList">
                    <img class="nav-icon" src="Icons/Group 3.svg"/>&nbsp;&nbsp;
                    <span>Clients List</span>
                </a>
            </li>
            <!-- Dropdown -->
            <li class="nav-item d-flex" style="background-color: rgba(232,240,255,1); border-radius: 15px;">
                <label class="d-flex justify-content-center align-items-center mt-2"><span class="helpDesign help_4">4</span></label>
                <span class="nav-icon d-flex align-items-center" style="padding: 0 0 0 10px !important;">
                    <i class="fas fa-user-circle fa-2x" aria-hidden="true"></i>
                </span>
                <a class="nav-link d-flex align-items-center" href="#" id="userDropdown"
                    role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span>
                        <?php echo $_SESSION['name']; ?>
                        <img class="nav-icon" src="Icons/Group 6.svg" style="width:15px !important;"/>
                    </span>
                </a>
                <!-- Dropdown - User Information -->
                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                    <?php 
                        if($_SESSION['role'] == '-1'){
                        ?>
                            <a class="dropdown-item" href="admin/loginLog"><i class="fas fa-list"></i>Login Log</a>
                            <a class="dropdown-item" href="admin/activityLog"><i class="fas fa-list"></i>Activity Log</a>
                            <a class="dropdown-item" href="#"><i class="fas fa-user-tie hue" style="color:blue;"></i><?php echo $_SESSION['name']; ?></a>
                            <a class="dropdown-item" href="#"><i class="fas fa-signature hue" style="color:blue;"></i><?php echo $_SESSION['signoff']; ?></a>
                            <a class="dropdown-item" href="#"><i class="fas fa-at hue" style="color:blue;"></i><?php echo $_SESSION['email']; ?></a>
                            <a class="dropdown-item" href="#"><i class="fas fa-briefcase hue" style="color:blue;"></i>Firm Name - ABC</a>
                        <?php
                        }   
                        else{
                            ?>
                            <a class="dropdown-item" href="#"><i class="fas fa-user-tie hue" style="color:blue;"></i><?php echo $_SESSION['name']; ?></a>
                            <a class="dropdown-item" href="#"><i class="fas fa-signature hue" style="color:blue;"></i><?php echo $_SESSION['signoff']; ?></a>
                            <a class="dropdown-item" href="#"><i class="fas fa-at hue" style="color:blue;"></i><?php echo $_SESSION['email']; ?></a>
                            <a class="dropdown-item" href="#"><i class="fas fa-briefcase hue" style="color:blue;"></i>Firm Name - ABC</a>
                            <?php
                        }
                        ?>
                </div>
            </li>
        </ul>
    </nav>

    <div class="mar">
        <!-- HEADER -->
        <br><div id="header">
            <div class="container-fluid" stickylevel="0" style="z-index:1200;">
                <div class="row pt-1">
                    <div class="row text-center cdrow" href="#">
                        <h2><?php echo strtoupper($clientName . " - Workspace"); ?></h2>
                    </div>
                </div>
            </div>
        </div><br>
        <!-- Body Starts -->
        <div class="container-fluid prog">
            <div class="col-md-12 d-flex align-items-center justify-content-center" style="padding-bottom: 2%; border-bottom: 2px solid #e1e2e9;">
                <div class="col-md-4 d-flex align-items-center">
                    <span class="span-heading">Audit Program</span>
                    <?php
                        $querys1 = $con->query("select count(program.id) cnt from program inner join workspace_log on program.id=workspace_log.program_id where workspace_log.workspace_id=$wid and workspace_log.status=1")->fetch_assoc()['cnt'];
                        $querys = $con->query("select count(program.id) cnt from program inner join workspace_log on program.id=workspace_log.program_id where workspace_log.workspace_id=$wid")->fetch_assoc()['cnt'];
                        $per = number_format((float)0, 2, '.', '');
                        if($querys1 != 0){
                            $per = number_format((float)($querys1/$querys)*100, 2, '.', '');
                        }
                    ?>
                    <span class="color-block"><?php echo $per."%"; ?></span>
                </div>
                <div class="progress col-md-8 p-0" style="height:25px; margin:0 !important;">
                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" aria-valuenow="<?php echo $per; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $per; ?>%; color:<?php if($per == 0) echo "#000"; else echo "#fff"; ?>;"></div>
                </div>
            </div><br>
            <div class="col-md-12 d-flex" style="flex-direction:column;">
                <?php
                $query = "select program.* from program inner join workspace_log on program.id=workspace_log.program_id where program.parent_id='0' and workspace_log.workspace_id='$wid' order by _seq";
                $exquery = $con->query($query);
                if ($exquery->num_rows != 0) {
                    while ($queryrow = $exquery->fetch_assoc()) {?>
                    <label class="mt-2"><span class="helpDesign help_1">1</span></label>
                <div class="col-md-12 custom-list" style="flex-direction:row; align-items:center;">
                    <div class="col-md-12">
                    <a href="subProgram.php?did=<?php echo md5(base64_encode($clientName)); ?>&gid=<?php echo md5(base64_encode($clientName)); ?>&fid=<?php echo md5(base64_encode($clientName)); ?>&eid=<?php echo md5(base64_encode($clientName)); ?>&pid=<?php echo base64_encode($queryrow['id']); ?>&cid=<?php echo md5(base64_encode($clientName)); ?>&bid=<?php echo md5(base64_encode($clientName)); ?>&aid=<?php echo md5(base64_encode($clientName)); ?>&parent_id=<?php echo base64_encode($queryrow['parent_id']); ?>&zid=<?php echo md5(base64_encode($clientName)); ?>&yid=<?php echo md5(base64_encode($clientName)); ?>&wid=<?php echo base64_encode($wid); ?>&xid=<?php echo md5(base64_encode($clientName)); ?>"
                        class="custom-list-items"><b><?php echo trim($queryrow['program_name']); ?></b>
                    </a>
                        <?php
                        if($queryrow['id']==1)
                            $color='bg-primary';
                        elseif($queryrow['id']==12)
                            $color='bg-info';
                        elseif($queryrow['id']==262)
                            $color='bg-darkg';
                        elseif($queryrow['id']==2)
                            $color='bg-primary';
                        elseif($queryrow['id']==19)
                            $color='bg-violet';
                        $querys1 = $con->query("select count(program.id) cnt from program inner join workspace_log on program.id=workspace_log.program_id where parent_id='".$queryrow['id']."' and workspace_log.workspace_id='$wid' and workspace_log.status=1")->fetch_assoc()['cnt'];
                        $querys = $con->query("select count(program.id) cnt from program inner join workspace_log on program.id=workspace_log.program_id where parent_id='".$queryrow['id']."' and workspace_log.workspace_id='$wid'")->fetch_assoc()['cnt'];
                        $per = number_format((float)0, 2, '.', '');
                        if($querys != 0){
                            $per = number_format((float)($querys1/$querys)*100, 2, '.', '');
                        }
                    ?>
                    <span class="completion <?php echo $color; ?>"><?php echo $per."%"; ?></span>
                    <div class="progress p-0">
                        <div class="progress-bar progress-bar-striped progress-bar-animated <?php echo $color; ?>" role="progressbar" style="width: <?php echo $per; ?>%; color: <?php if($per == 0) echo "#000"; else echo "#fff"; ?>;" aria-valuenow="<?php echo $per; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    </div>

                </div>
                <?php }}
                    ?>
                    <br>
            </div>
            <?php
            $status = $con->query("select status from workspace_log where workspace_id=$wid and program_id=248")->fetch_assoc()['status'];
            if($status){
                ?>
                <div class="col-md-12 d-flex justify-content-center">
                    <button id="freeze" type="button" class="btn btn-lg btn-custom d-flex align-items-center"><img class="nav-icon" src="Icons/pause-circle.svg"/> &nbsp; Freeze Workspace</button>
                </div>
            <?php
            }
            ?>
        </div>

        <div class="d-flex justify-content-center">
            <div id="helpDescription" class="col-md-11">
                <div class="card" style="border: 4px solid rgb(134, 189, 255, 0.65) !important;box-shadow: 0px 0px 20px 1px rgba(0,0,0,0.5);">
                    <div class="card-body">
                        <div id="help_1">
                            <p>1. Audit Pillars : We have divided fieldwork in five pillars. You can not add or remove any pillar .</p>
                        </div>
                        <div id="help_2">
                            <p>2. Audit members : This shows the list of members that currently have access to the particular client file.</p>
                            <p>This only reflects the members in the currently open client file.</p>
                        </div>
                        <div id="help_3">
                            <p>3. Client List: Will take you your main page with list of all clients allocated to you.</p>
                        </div>
                        <div id="help_4">
                            <p>4. Profile: User profile reflects brief details about the user and can be edits by firm administrator.</p>
                        </div>
                        <div id="help_5">
                            <p>5. Diagnostics: Diagnostics report gives you a summary of all applicable work steps being signed off by preparer and reviewer.</p>
                            <p>Diagnostics reports give you a quick view of the number of comments and number of files within a particular workplace.</p>
                            <p>You can review this report to ensure accuracy and completeness of audit before Concluding.</p> 
                        </div>
                        <div id="help_6">
                            <p>6. Settings – Your Settings are personalized based on your role in your firm and can be always accessed for chat, email and reaching out to a specialist for any help.</p>
                        </div>
                        <div id="help_7">
                            <p>7. Log out- Simply use this button to log out of your firm workspace and client list.</p>
                        </div>
                        <i id="left-arrow" class="fas fa-arrow-left"></i>
                        <i id="right-arrow" class="fas fa-arrow-right"></i>
                    </div>
                </div>
            </div>
        </div>
                    
        <!-- Footer -->
        <footer class="sticky-footer">
            <div class="container my-auto">
                <div class="copyright text-center my-auto">
                    <span><strong><span style="color: #8E1C1C;">Audit-EDG </span>&copy;
                            <?php echo date("Y"); ?></strong></span>
                </div>
            </div>
        </footer>
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

        $(".helpDesign, #helpDescription").hide();

        $("#helpButton").click(function(e){
            $(".helpDesign, #helpDescription").toggle();
            $("#help_2, #help_3, #help_4, #help_5, #help_6, #help_7").hide();
            $("#help_1").show();
            $(".help_1").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
            $(".help_2, .help_3, .help_4, .help_5, .help_6, .help_7").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
        });

        $(".help_1").click(function(e){
            $(".help_2, .help_3, .help_4, .help_5, .help_6, .help_7").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
            $(".help_1").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
            $("#help_1").show();
            $("#help_2, #help_3, #help_4, #help_5, #help_6, #help_7").hide();
        });

        $(".help_2").click(function(e){
            $(".help_1, .help_3, .help_4, .help_5, .help_6, .help_7").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
            $(".help_2").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
            $("#help_2").show();
            $("#help_1, #help_3, #help_4, #help_5, #help_6, #help_7").hide();
        });

        $(".help_3").click(function(e){
            $(".help_1, .help_2, .help_4, .help_5, .help_6, #help_7").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
            $(".help_3").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
            $("#help_3").show();
            $("#help_1, #help_2, #help_4, #help_5, #help_6, #help_7").hide();
        });

        $(".help_4").click(function(e){
            $(".help_1, .help_2, .help_3, .help_5, .help_6, .help_7").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
            $(".help_4").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
            $("#help_4").show();
            $("#help_1, #help_2, #help_3, #help_5, #help_6, #help_7").hide();
        });

        $(".help_5").click(function(e){
            $(".help_1, .help_2, .help_3, .help_4, .help_6, .help_7").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
            $(".help_5").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
            $("#help_5").show();
            $("#help_1, #help_2, #help_3, #help_4, #help_6, #help_7").hide();
        });
        
        $(".help_6").click(function(e){
            $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_7").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
            $(".help_6").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
            $("#help_6").show();
            $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_7").hide();
        });

        $(".help_7").click(function(e){
            $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
            $(".help_7").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
            $("#help_7").show();
            $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6").hide();
        });

        $("#right-arrow").click(function(e){
            if($(".help_1").hasClass("helpDesignSelected")){
                $(".help_1, .help_3, .help_4, .help_5, .help_6, .help_7").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                $(".help_2").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                $("#help_2").show();
                $("#help_1, #help_3, #help_4, #help_5, #help_6, #help_7").hide();
            }
            else if($(".help_2").hasClass("helpDesignSelected")){
                $(".help_1, .help_2, .help_4, .help_5, .help_6, #help_7").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                $(".help_3").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                $("#help_3").show();
                $("#help_1, #help_2, #help_4, #help_5, #help_6, #help_7").hide();
            }
            else if($(".help_3").hasClass("helpDesignSelected")){
                $(".help_1, .help_2, .help_3, .help_5, .help_6, .help_7").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                $(".help_4").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                $("#help_4").show();
                $("#help_1, #help_2, #help_3, #help_5, #help_6, #help_7").hide();
            }
            else if($(".help_4").hasClass("helpDesignSelected")){
                $(".help_1, .help_2, .help_3, .help_4, .help_6, .help_7").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                $(".help_5").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                $("#help_5").show();
                $("#help_1, #help_2, #help_3, #help_4, #help_6, #help_7").hide();
            }
            else if($(".help_5").hasClass("helpDesignSelected")){
                $(".help_1, .help_2, .help_3, .help_4, .help_5").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                $(".help_6").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                $("#help_6").show();
                $("#help_1, #help_2, #help_3, #help_4, #help_5").hide();
            }
            else if($(".help_6").hasClass("helpDesignSelected")){
                $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                $(".help_7").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                $("#help_7").show();
                $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6").hide();
            }
            else if($(".help_7").hasClass("helpDesignSelected")){
                $(".help_2, .help_3, .help_4, .help_5, .help_6, .help_7").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                $(".help_1").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                $("#help_1").show();
                $("#help_2, #help_3, #help_4, #help_5, #help_6, #help_7").hide();
            }
        });

        $("#left-arrow").click(function(e){
            if($(".help_1").hasClass("helpDesignSelected")){
                $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                $(".help_7").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                $("#help_7").show();
                $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6").hide();
            }
            else if($(".help_2").hasClass("helpDesignSelected")){
                $(".help_2, .help_3, .help_4, .help_5, .help_6, .help_7").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                $(".help_1").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                $("#help_1").show();
                $("#help_2, #help_3, #help_4, #help_5, #help_6, #help_7").hide();
            }
            else if($(".help_3").hasClass("helpDesignSelected")){
                $(".help_1, .help_3, .help_4, .help_5, .help_6, .help_7").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                $(".help_2").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                $("#help_2").show();
                $("#help_1, #help_3, #help_4, #help_5, #help_6, #help_7").hide();
            }
            else if($(".help_4").hasClass("helpDesignSelected")){
                $(".help_1, .help_2, .help_4, .help_5, .help_6, #help_7").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                $(".help_3").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                $("#help_3").show();
                $("#help_1, #help_2, #help_4, #help_5, #help_6, #help_7").hide();
            }
            else if($(".help_5").hasClass("helpDesignSelected")){
                $(".help_1, .help_2, .help_3, .help_5, .help_6, .help_7").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                $(".help_4").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                $("#help_4").show();
                $("#help_1, #help_2, #help_3, #help_5, #help_6, #help_7").hide();
            }
            else if($(".help_6").hasClass("helpDesignSelected")){
                $(".help_1, .help_2, .help_3, .help_4, .help_6, .help_7").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                $(".help_5").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                $("#help_5").show();
                $("#help_1, #help_2, #help_3, #help_4, #help_6, #help_7").hide();
            }
            else if($(".help_7").hasClass("helpDesignSelected")){
                $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_7").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                $(".help_6").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                $("#help_6").show();
                $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_7").hide();
            }
        });


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
        });

        let darkmode = <?php echo $_SESSION['darkmode']; ?>;
        if(darkmode)
        {
            document.documentElement.classList.toggle('dark-mode');
            
        }
        else if(!darkmode){
            document.documentElement.classList.remove('dark-mode');
        }
    });

    </script>
</body>