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
                    <a href="clientDashboard?wid=<?php echo $wid;?>"><img class="sidenav-icon" src="Icons/pie-chart.svg" style="width:24px !important; height:24px !important;"/> &nbsp;
                    Workspace
                    </a>
                </div>
            </div>
            <div class="settings">
                <div class="settings-items-top-div">
                    <div class="settings-items settingsmodal">
                        <img class="sidenav-icon" src="Icons/settings.svg" style="width:24px !important; height:24px !important;"/> &nbsp;
                        Settings
                    </div>
                    <div class="settings-items">
                        <img class="sidenav-icon" src="Icons/help-circle.svg" style="width:24px !important; height:24px !important;"/> &nbsp;
                        Help
                    </div>
                </div>
                <a href="logout"><button type="button" class="btn btn-primary"><i class="fas fa-sign-out-alt"></i> Logout</button></a>
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
                        <a class="nav-link d-flex align-items-center" target="_blank" href="diagnosticReport?wid=<?php echo $wid; ?>">
                            <img class="nav-icon" src="Icons/download.jpg" style="height: 40px; width: 40px;" />&nbsp;&nbsp;
                            <span>Diagonistic Report</span>
                        </a>
                    </li>
                    <li class="nav-item d-flex">
                        <a class="nav-link d-flex align-items-center" href="admin/clientMember?cid=<?php echo $_SESSION['client_id'];?>&wid=<?php echo $wid;?>">
                        <img class="nav-icon" src="Icons/Group 4.svg"/>&nbsp;&nbsp;
                        <span>Members</span>
                        </a>
                    </li>
            <?php } 
            ?>
            <li class="nav-item d-flex">
                <a class="nav-link d-flex align-items-center" href="admin/clientList">
                    <img class="nav-icon" src="Icons/Group 3.svg"/>&nbsp;&nbsp;
                    <span>List Clients</span>
                </a>
            </li>
            <!-- Dropdown -->
            <li class="nav-item d-flex" style="background-color: rgba(232,240,255,1); border-radius: 15px;">
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
                <div class="col-md-12 custom-list" style="flex-direction:row; align-items:center;">
                    <div class="col-md-12">
                    <a href="subProgram.php?pid=<?php echo $queryrow['id']; ?>&parent_id=<?php echo $queryrow['parent_id']; ?>&wid=<?php echo $wid; ?>"
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

    <!-- Settings Modal -->
    <div class="modal fade" id="settingsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-size" role="document">
            <div class="modal-content">
                <!-- <form method="post" action="editAClient"> -->
                <form>
                    <div class="modal-body">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Settings</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div><br>
                        <div class="form-group ">
                            <label for="name">Dark Mode</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input darkmode" type="radio" name="darkmode" id="dark-inactive" value="0">
                            <label class="form-check-label" for="exampleRadios1">
                                Inactive
                            </label> &nbsp; &nbsp; &nbsp; &nbsp;
                            <input class="form-check-input darkmode" type="radio" name="darkmode" id="dark-active" value="1">
                            <label class="form-check-label" for="exampleRadios2" name="active">
                                Active
                            </label>
                        </div>
                        <div class="modal-footer d-flex align-items-center justify-content-center">
                            <!-- <button class="btn btn-danger" type="button" data-dismiss="modal">Cancel</button> -->
                            <input class="btn btn-success" id="save" type="submit" value="Save">
                        </div>
                    </div>
                </form>
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
        });

        let darkmode = <?php echo $_SESSION['darkmode']; ?>;
        if(darkmode)
        {
            document.documentElement.classList.toggle('dark-mode');
            // document.querySelectorAll('.dark-invert').forEach((result) => {
            //     result.classList.toggle('invert-dark-mode');
            // });
            $("#settingsModal #dark-active").attr('checked','checked');
        }
        else if(!darkmode){
            document.documentElement.classList.remove('dark-mode');
            $("#settingsModal #dark-inactive").attr('checked','checked');
        }
    });

    $(document).on('click','.settingsmodal', function() {
        $("#settingsModal").modal('show');
    });

    $('input[type=radio][name=darkmode]').change(function() {
        if(this.value == '1')
        {
            document.documentElement.classList.toggle('dark-mode');
            // document.querySelectorAll('.dark-invert').forEach((result) => {
            //     result.classList.toggle('invert-dark-mode');
            // });
        }
        else if(this.value == '0'){
            document.documentElement.classList.remove('dark-mode');
            document.documentElement.classList.remove('invert-dark-mode');
        }
    });

    $(document).on('click', '#save', function(e) {
        e.preventDefault();
        var id = <?php echo $_SESSION['id']; ?>;
        var active = $('input[name="darkmode"]:checked').val();
        $.ajax({
            url: "darkmode.php",
            type: "POST",
            data: {
                id: id,
                active: active
            },
            success: function(response) {
                console.log(response);
                if (response) {
                    swal({
                        icon: "success",
                        text: "Updated!",
                    }).then(function(isConfirm) {
                        if (isConfirm) {
                            window.location.reload();
                        }
                    });
                } else {
                    swal({
                        icon: "error",
                        text: "Failed!",
                    }).then(function(isConfirm) {
                        if (isConfirm) {
                            window.location.reload();
                        }
                    });
                }
            }
        });
    });
    </script>
</body>