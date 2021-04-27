<?php
    include 'dbconnection.php';
    session_start();
    if (!isset($_SESSION['email']) && empty($_SESSION['email'])) {
        header("Location: login");
    }
    if (isset($_SESSION['accessLevel']) && !empty($_SESSION['accessLevel']) && $_SESSION['accessLevel'] != '1') {
        header('Location: login');
    }
    $clientID = base64_decode($_GET['cid']);
    if($con->query("select * from client where id = $clientID")->num_rows == 0){
        header('Location: login');
    }

    $_SESSION['client_id'] = $clientID;
    $_SESSION['cname'] = $clientName = $con->query("select name from client where id = $clientID ")->fetch_assoc()["name"];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title> WORKSPACE </title>
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
    <link href="css/pace-theme.css" rel="stylesheet">
    <link href="css/custom.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/uiux.css" rel="stylesheet" type="text/css">
    
    <!-- JQuery CDN -->
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>

    <!-- Datatable CDN -->
    <link href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css" rel="stylesheet">
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous"></script>

</head>

<body style="overflow-y: scroll" oncontextmenu="return false">

    <!-- Navbar -->
    <nav class="navbar sticky-top navbar-expand-lg navbar-mainbg border-bottom">
        <!-- Topbar Navbar -->
        <ul class="navbar-nav ml-auto">
            <!-- <li class="nav-item d-flex">
                <a class="nav-link d-flex align-items-center" href="clientList">
                    <span>List Clients</span>&nbsp;&nbsp;
                    <i class="fas fa-list fa-1x"></i>
                </a>
            </li> -->
            <li class="nav-item d-flex">
            <label class="d-flex justify-content-center align-items-center mt-2"><span class="helpDesign help_2">2</span></label>
                <a class="nav-link d-flex align-items-center" href="admin/clientList">
                    <img class="nav-icon" src="Icons/Group 3.svg"/>&nbsp;&nbsp;
                    <span>Clients List</span>
                </a>
            </li>
            <div class="d-flex justify-content-between align-items-center">
                <label class="d-flex justify-content-center align-items-center mt-2"><span class="helpDesign help_4">4</span></label>
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
            </div>
        </ul>
    </nav>

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
                    <img class="sidenav-icon" src="Icons/pie-chart.svg" style="width:24px !important; height:24px !important;"/> &nbsp;
                    Workspace
                </div>
            </div>
            <div class="settings">
                <div class="settings-items-top-div">
                    <div class="settings-items d-flex justify-content-between align-items-center">
                        <a href="settings" class="text-decoration-none">
                            <img class="sidenav-icon" src="Icons/settings.svg" style="width:24px !important; height:24px !important;"/> &nbsp;Settings
                        </a>
                        <label class="d-flex justify-content-center align-items-center mt-2"><span class="helpDesign help_5">5</span></label>
                    </div>
                    <div id="helpButton" class="settings-items">
                        <a href="#" class="text-decoration-none"><img class="sidenav-icon" src="Icons/help-circle.svg" style="width:24px !important; height:24px !important;"/> &nbsp;
                        Help</a>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <a href="logout"><button type="button" class="btn btn-primary"><i class="fas fa-sign-out-alt"></i> Logout</button></a>
                    <label class="d-flex justify-content-center align-items-center mt-2"><span class="helpDesign help_6">6</span></label>
                </div>
            </div>
        </div>
    </div>

    <div class="mar">
        <!-- HEADER -->
        <div id="header"><div class="container-fluid shadow border border-bottom" stickylevel="0" style="z-index:1200;">
            <div class="row pt-1">
                <div class="col-md-4">
                    <div class="ml-2 font-1 h3 py-1 d-inline-block float-left"></div>
                </div>
                <div class="col-md-4 text-center font-2 getContent" href="admin/clientList">
                    <h3><?php echo strtoupper($clientName . " Workspace"); ?></h3>
                </div>    
            </div>
        </div><br>

        <!-- ADD WORKSPACE BUTTON -->
        <?php 
            if($_SESSION['role'] != 3 && $_SESSION['role'] != 2 && $_SESSION['external'] != 1){
        ?>
        <div class = "row justify-content-md-center" style="width: 100% !important;">   
            <div class="col-xl-3 col-md-6 mb-4 ">                       
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                    <a class="nav-link" href="#" data-toggle="modal" data-target="#addWorkspaceModal">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <span>Add Workspace</span>
                                </div>
                            </div>
                            <div class="col-auto">  
                                <i class="fas fa-fw fa-user-plus fa-2x text-gray-300"></i>
                            </div>
                        </div>
                        </a>
                        <label class="d-flex justify-content-center align-items-center mt-2"><span class="helpDesign help_1">1</span></label>
                    </div>
                </div>
            </div>
        </div>  
        <?php } 
        ?>
        <!-- TABLE -->
        <?php
        $query = "Select * from workspace where client_id = $clientID";
        $result = $con->query($query);
        if ($result->num_rows > 0) {
            ?>
            <div class="container pt-4">
                <div class="row">
                    <div class="card-body" style="width:10px;">
                        <div class="table-responsive" style="border-radius: 12px !important;">
                            <div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <table id="workspaceTable" class="table display table-bordered table-striped" style="border-collapse: inherit !important;">
                                            <thead>
                                            <tr>
                                                <th scope="col shadow-remove">From</th>
                                                <th scope="col shadow-remove">To</th>
                                                <th scope="col shadow-remove">Actions</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php     
                                                while ($row = $result->fetch_assoc()) {
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $row['datefrom']; ?></td>
                                                        <td><?php echo $row['dateto']; ?></td>
                                                        <td>
                                                        <?php
                                                        if(!$row['freeze']){
                                                            if($_SESSION['external'] != 1){
                                                                ?>
                                                                    <a href="clientDashboard?<?php echo md5(base64_encode($clientName)); ?>&gid=<?php echo md5(base64_encode($clientName)); ?>&fid=<?php echo md5(base64_encode($clientName)); ?>&eid=<?php echo md5(base64_encode($clientName)); ?>&cid=<?php echo md5(base64_encode($clientName)); ?>&bid=<?php echo md5(base64_encode($clientName)); ?>&aid=<?php echo md5(base64_encode($clientName)); ?>&zid=<?php echo md5(base64_encode($clientName)); ?>&yid=<?php echo md5(base64_encode($clientName)); ?>&wid=<?php echo base64_encode($row['id']); ?>&xid=<?php echo md5(base64_encode($clientName)); ?>" class="icon-hide">
                                                                        <img class="hue" src="Icons/edit-1.svg"><img class="hue" src="Icons/edit-2.svg">
                                                                    </a>
                                                                <?php
                                                            }
                                                            else{
                                                                ?>
                                                                    <a href="subProgram?<?php echo md5(base64_encode($clientName)); ?>&gid=<?php echo md5(base64_encode($clientName)); ?>&fid=<?php echo md5(base64_encode($clientName)); ?>&eid=<?php echo md5(base64_encode($clientName)); ?>&pid=<?php echo base64_encode('247'); ?>&cid=<?php echo md5(base64_encode($clientName)); ?>&bid=<?php echo md5(base64_encode($clientName)); ?>&aid=<?php echo md5(base64_encode($clientName)); ?>&zid=<?php echo md5(base64_encode($clientName)); ?>&yid=<?php echo md5(base64_encode($clientName)); ?>&wid=<?php echo base64_encode($row['id']); ?>&xid=<?php echo md5(base64_encode($clientName)); ?>">
                                                                        <img class="hue" src="Icons/edit-1.svg"><img class="hue" src="Icons/edit-2.svg">
                                                                    </a>
                                                                <?php
                                                            }
                                                        }
                                                        else{
                                                            if($_SESSION['role'] == '1' || $_SESSION['role'] == '-1'){
                                                            ?>
                                                                <a id="<?php echo $row['id']; ?>" class="freeze" href="#"><i class="fas fa-unlock"></i></a>
                                                            <?php
                                                            }
                                                            else{
                                                                ?>
                                                                <span class="badge badge-danger">Locked!</span>
                                                                <?php
                                                            }
                                                        }
                                                        ?>
                                                            <label class="d-flex justify-content-center align-items-center mt-2"><span class="helpDesign help_3">3</span></label>
                                                            
                                                        </td>
                                                    </tr>
                                                <?php
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php
        }
        else{
                                                    
            header('Location: login');
        }
        ?>

        <div class="d-flex justify-content-center">
            <div id="helpDescription" class="col-md-11">
                <div class="card" style="border: 4px solid rgb(134, 189, 255, 0.65) !important;box-shadow: 0px 0px 20px 1px rgba(0,0,0,0.5);">
                    <div class="card-body">
                        <div id="help_1">
                            <p>1. Add workspace: For every client your can create multiple workspace based on the period of your engagement.</p>
                            <p>Once you click on add workspace and enter the dates you will be able to access the Audit dashboard.</p><br>
                            <p>Once you click done your workspace is now ready to use.</p>
                            <p>You can not delete a workspace once created. It can only be freezed out by using the freeze workspace feature.</p>
                            <p> Only firm administrator can unlock a workspace once it’s been freeze by the audit team.</p>
                        </div>
                        <div id="help_2">
                            <p>2. Client list: Will take you your main page with list of all clients allocated to you</p>
                        </div>
                        <div id="help_3">
                            <p>3. Action: You can access the respective workspace using this button and enter the audit dashboard</p>
                        </div>
                        <div id="help_4">
                            <p>4. Profile: User profile reflects brief details about the user and can be edits by firm administrator.</p>
                        </div>
                        <div id="help_5">
                            <p>5. Settings – Your Settings are personalized based on your role in your firm and can be accessed at all times for chat, email and reaching out to a specialist for any help.</p> 
                        </div>
                        <div id="help_6">
                            <p>6. Log out- Simply use this button to log out of your firm workspace and client list.</p>
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

        <!--ADD WORKSPACE -->
        <div class="modal fade" id="addWorkspaceModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered  modal-size" role="document">
                <div class="modal-content">
                    
                    <form action="addWorkspace" method="post" id="addWorkspace">
                        <div class="modal-body">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Workspace for Financial Year<h5>
                                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group ">
                                        <label for="name">From</label>
                                        <input type="date" class="form-control" name="from" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group ">
                                        <label for="name">To</label>
                                        <input type="date" class="form-control" name="to" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer  d-flex align-items-center justify-content-center">
                            <input class="btn btn-primary" type="submit" id="" value="Done">
                            <input type= "hidden" name = "clientID" value = "<?php echo $clientID; ?>" >
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>


    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous"></script>
    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>
    <!-- Page level custom scripts -->
    <script src="js/custom.js"></script>
    <script>

    $(document).ready(function(){

        let darkmode = <?php echo $_SESSION['darkmode']; ?>;
        if(darkmode)
        {
            document.documentElement.classList.toggle('dark-mode');
            
        }
        else if(!darkmode){
            document.documentElement.classList.remove('dark-mode');
        }

        $(".helpDesign, #helpDescription").hide();

        $("#helpButton").click(function(e){
            $(".helpDesign, #helpDescription").toggle();
            $("#help_2, #help_3, #help_4, #help_5, #help_6").hide();
            $("#help_1").show();
            $(".help_1").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
            $(".help_2, .help_3, .help_4, .help_5, .help_6").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
        });

        $(".help_1").click(function(e){
            $(".help_2, .help_3, .help_4, .help_5, .help_6").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
            $(".help_1").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
            $("#help_1").show();
            $("#help_2, #help_3, #help_4, #help_5, #help_6").hide();
        });

        $(".help_2").click(function(e){
            $(".help_1, .help_3, .help_4, .help_5, .help_6").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
            $(".help_2").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
            $("#help_2").show();
            $("#help_1, #help_3, #help_4, #help_5, #help_6").hide();
        });

        $(".help_3").click(function(e){
            $(".help_1, .help_2, .help_4, .help_5, .help_6").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
            $(".help_3").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
            $("#help_3").show();
            $("#help_1, #help_2, #help_4, #help_5, #help_6").hide();
        });

        $(".help_4").click(function(e){
            $(".help_1, .help_2, .help_3, .help_5, .help_6").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
            $(".help_4").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
            $("#help_4").show();
            $("#help_1, #help_2, #help_3, #help_5, #help_6").hide();
        });

        $(".help_5").click(function(e){
            $(".help_1, .help_2, .help_3, .help_4, .help_6").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
            $(".help_5").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
            $("#help_5").show();
            $("#help_1, #help_2, #help_3, #help_4, #help_6").hide();
        });
        
        $(".help_6").click(function(e){
            $(".help_1, .help_2, .help_3, .help_4, .help_5").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
            $(".help_6").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
            $("#help_6").show();
            $("#help_1, #help_2, #help_3, #help_4, #help_5").hide();
        });

        $("#right-arrow").click(function(e){
            if($(".help_1").hasClass("helpDesignSelected")){
                $(".help_1, .help_3, .help_4, .help_5, .help_6").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                $(".help_2").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                $("#help_1, #help_3, #help_4, #help_5, #help_6").hide();
                $("#help_2").show();
            }
            else if($(".help_2").hasClass("helpDesignSelected")){
                $(".help_1, .help_2, .help_4, .help_5, .help_6").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                $(".help_3").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                $("#help_1, #help_2, #help_4, #help_5, #help_6").hide();
                $("#help_3").show();
            }
            else if($(".help_3").hasClass("helpDesignSelected")){
                $(".help_1, .help_2, .help_3, .help_5, .help_6").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                $(".help_4").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                $("#help_4").show();
                $("#help_1, #help_2, #help_3, #help_5, #help_6").hide();
            }
            else if($(".help_4").hasClass("helpDesignSelected")){
                $(".help_1, .help_2, .help_3, .help_4, .help_6").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                $(".help_5").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                $("#help_5").show();
                $("#help_1, #help_2, #help_3, #help_4, #help_6").hide();
            }
            else if($(".help_5").hasClass("helpDesignSelected")){
                $(".help_1, .help_2, .help_3, .help_4, .help_5").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                $(".help_6").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                $("#help_6").show();
                $("#help_1, #help_2, #help_3, #help_4, #help_5").hide();
            }
            else if($(".help_6").hasClass("helpDesignSelected")){
                $(".help_2, .help_3, .help_4, .help_5, .help_6").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                $(".help_1").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                $("#help_1").show();
                $("#help_2, #help_3, #help_4, #help_5, #help_6").hide();
            }
        });

        $("#left-arrow").click(function(e){
            if($(".help_1").hasClass("helpDesignSelected")){
                $(".help_1, .help_2, .help_3, .help_4, .help_5").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                $(".help_6").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                $("#help_1, #help_2, #help_3, #help_4, #help_5").hide();
                $("#help_6").show();
            }
            else if($(".help_2").hasClass("helpDesignSelected")){
                $(".help_2, .help_3, .help_4, .help_5, .help_6").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                $(".help_1").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                $("#help_1").show();
                $("#help_2, #help_3, #help_4, #help_5, #help_6").hide();
            }
            else if($(".help_3").hasClass("helpDesignSelected")){
                $(".help_1, .help_3, .help_4, .help_5, .help_6").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                $(".help_2").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                $("#help_2").show();
                $("#help_1, #help_3, #help_4, #help_5, #help_6").hide();
            }
            else if($(".help_4").hasClass("helpDesignSelected")){
                $(".help_1, .help_2, .help_4, .help_5, .help_6").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                $(".help_3").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                $("#help_3").show();
                $("#help_1, #help_2, #help_4, #help_5, #help_6").hide();
            }
            else if($(".help_5").hasClass("helpDesignSelected")){
                $(".help_1, .help_2, .help_3, .help_5, .help_6").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                $(".help_4").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                $("#help_4").show();
                $("#help_1, #help_2, #help_3, #help_5, #help_6").hide();
            }
            else if($(".help_6").hasClass("helpDesignSelected")){
                $(".help_1, .help_2, .help_3, .help_4, .help_6").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                $(".help_5").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                $("#help_5").show();
                $("#help_1, #help_2, #help_3, #help_4, #help_6").hide();
            }
        });
    });

    $(document).ready(function(){
        var i=1;
        b = i-1;
        $("#add_row").click(function () {
                $('#addr' + i).html($('#addr' + b).html()).find('td:first-child');
                $('#tab_logic').append('<tr id="addr' + (i + 1) + '"></tr>');
            i++;
        });
        //Delete Row Function for sales add form
        $("#delete_row").click(function () {
            if (i > 1) {
                $("#addr" + (i - 1)).html('');
                i--;
            }
        });
    });
    </script>