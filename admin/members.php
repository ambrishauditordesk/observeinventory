<?php
    include '../dbconnection.php';
    session_start();

    if (isset($_SESSION['external']) && !empty($_SESSION['external']) && $_SESSION['external'] == 1){
        $checkAccess = $con->query("select id from accounts_log where client_contact_id = ".$_SESSION['id'])->num_rows;
        if($checkAccess){
            $clientName = 1;
            $location =  base64_encode(md5($clientName)).'&gid='. base64_encode(md5($clientName)).'&fid='. base64_encode(md5($clientName)).'&eid='.base64_encode(md5($clientName)).'&cid='.base64_encode($_SESSION['external_client_id']);
            header('Location: ../workspace?vid='.$location);
        }
        else{
            header("Location: ../logout");
        }
    }

    if (!isset($_SESSION['email']) && empty($_SESSION['email'])) {
        header("Location: ../login");
    }
    if (isset($_SESSION['role']) && !empty($_SESSION['role']) && $_SESSION['role'] == '5') {
        header('Location: ../login');
    }
    if (isset($_SESSION['logged_in_date']) && !empty($_SESSION['logged_in_date'])){
        $currentDate = date_create(date("Y-m-d H:i:s",strtotime(date_format(date_create("now", new DateTimeZone('Asia/Kolkata')), "Y-m-d H:i:s"))));
        $loggedInDate = date_create(date("Y-m-d H:i:s",strtotime($_SESSION['logged_in_date'])));
        $diff=date_diff($currentDate,$loggedInDate);
        if($diff->format("%a") > 1 || $diff->format("%m") > 1 || $diff->format("%y") > 1){
            header('Location: logout');
        }
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

    <!-- Custom fonts for this template-->
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../css/custom.css" rel="stylesheet">
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/uiux.css" rel="stylesheet" type="text/css">

    <!-- JQuery CDN -->
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>

    <!-- Datatable CDN -->
    <link href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/plug-ins/1.10.24/features/searchHighlight/dataTables.searchHighlight.css">
    
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/plug-ins/1.10.24/features/searchHighlight/dataTables.searchHighlight.min.js"></script>
    <script src="https://bartaz.github.io/sandbox.js/jquery.highlight.js"></script>

    <!-- SweetAlert -->
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
                <label class="d-flex justify-content-center align-items-center mt-2"><span class="helpDesign help_6">6</span></label>
                <a class="nav-link d-flex align-items-center" href="clientList">
                    <img class="nav-icon" src="../Icons/Group 3.svg"/>&nbsp;&nbsp;
                    <span>Clients List</span>
                </a>
            </li>
            <li class="nav-item d-flex" style="background-color: rgba(232,240,255,1); border-radius: 15px;">
                <label class="d-flex justify-content-center align-items-center mt-2"><span class="helpDesign help_5">5</span></label>
                <span class="nav-icon d-flex align-items-center" style="padding: 0 0 0 10px !important;">
                    <?php
                        $img_query = $con->query("SELECT * FROM user WHERE id = ".$_SESSION['id']." and img != ''");
                        if($img_query->num_rows == 1){
                            $row = $img_query->fetch_assoc();
                            ?>
                            <img class = "profilePhoto" src="../images/<?php echo $row['img']; ?>">
                            <?php
                        }
                        else{
                            ?>
                            <i class="fas fa-user-circle fa-2x" aria-hidden="true"></i>
                            <?php
                        }                       
                    ?>
                </span>
                <a class="nav-link d-flex align-items-center" href="#" id="userDropdown"
                    role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span>
                        <?php echo $_SESSION['name']; ?>
                        <img class="nav-icon" src="../Icons/Group 6.svg" style="width:15px !important;"/>
                    </span>
                </a>
                <!-- Dropdown - User Information -->
                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                    <?php 
                        if($_SESSION['role'] == '-1' || $_SESSION['role'] == '1'){
                        ?>
                            <a class="dropdown-item" href="#"><i class="fas fa-user-tie hue" style="color:blue;"></i><?php echo $_SESSION['name']; ?></a>
                            <a class="dropdown-item" href="#"><i class="fas fa-signature hue" style="color:blue;"></i><?php echo $_SESSION['signoff']; ?></a>
                            <a class="dropdown-item" href="#"><i class="fas fa-at hue" style="color:blue;"></i><?php echo $_SESSION['email']; ?></a>
                        <?php
                        }   
                        else{
                            ?>
                            <a class="dropdown-item" href="#"><i class="fas fa-user-tie hue" style="color:blue;"></i><?php echo $_SESSION['name']; ?></a>
                            <a class="dropdown-item" href="#"><i class="fas fa-signature hue" style="color:blue;"></i><?php echo $_SESSION['signoff']; ?></a>
                            <a class="dropdown-item" href="#"><i class="fas fa-at hue" style="color:blue;"></i><?php echo $_SESSION['email']; ?></a>
                            <a class="dropdown-item" href="#"><i class="fas fa-briefcase hue" style="color:blue;"></i>Firm Name -<?php echo $_SESSION['firm_details']['firm_name']; ?></a>
                            <?php
                        }
                    ?>
                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#photoModal"><i class="fas fa-user-circle hue" style="color:blue;"></i>Update Profile Photo</a>
                </div>
            </li>
        </ul>
    </nav>

    <!-- SideBar -->
    <div class="sidenav">
        <div class="side-header">
            <!-- <div style="border-bottom:1px solid;"> -->
            <div>
                <img class="sidenav-icon" src="../Icons/Group-1.png"/> &nbsp;
               
            </div>
        </div>
        <div class="side-footer">
            <div class="side-body">
                <div class="dash">
                    <img class="sidenav-icon" src="../Icons/pie-chart.svg" style="width:24px !important; height:24px !important;"/> &nbsp;
                    Members
                    </svg>
                </div>
            </div>
            <div class="settings">
                <div class="settings-items-top-div">
                    <div class="settings-items d-flex justify-content-between align-items-center">
                        <a href="../settings" class="text-decoration-none">
                            <img class="sidenav-icon" src="../Icons/settings.svg" style="width:24px !important; height:24px !important;"/> &nbsp;Settings
                        </a>
                        <label class="d-flex justify-content-center align-items-center mt-2"><span class="helpDesign help_7">7</span></label>
                    </div>
                    <div id="helpButton" class="settings-items">
                        <a href="#" class="text-decoration-none"><img class="sidenav-icon" src="../Icons/help-circle.svg" style="width:24px !important; height:24px !important;"/> &nbsp;
                        Help</a>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <a href="../logout"><button type="button" class="btn btn-primary"><i class="fas fa-sign-out-alt"></i> Logout</button></a>
                    <label class="d-flex justify-content-center align-items-center mt-2"><span class="helpDesign help_8">8</span></label>
                </div>
            </div>
        </div>
    </div>

    <div class="mar">
        <br>

        <div class="row justify-content-md-center" style="width: 100% !important;">
            <!-- Total Members -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Firm Members
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <?php
                                    if($_SESSION['role'] != 1 && $_SESSION['role'] != -1 ){
                                        $query = "SELECT COUNT(id) AS total FROM firm_user_log where firm_id = ".$_SESSION['firm_id'];
                                    }
                                    elseif($_SESSION['role'] == 1){
                                        $query = "SELECT COUNT(id) AS total FROM user where client_id is null and accessLevel != -1";
                                    }
                                    elseif($_SESSION['role'] == -1){
                                        $query = "SELECT COUNT(id) AS total FROM user where client_id is null ";
                                    }
                                
                                $totalMembers = $con->query($query);
                                if ($totalMembers->num_rows != 0) {
                                    $count = $totalMembers->fetch_assoc();
                                    echo " " . $count['total'];
                                    if ($count['total'] > 1) {
                                        echo " Members";
                                    } else {
                                        echo " Members";
                                    }
                                } else {
                                    echo " 0 Member";
                                }
                            ?>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-user-tie fa-2x text-gray-300"></i>
                            </div>
                        </div>
                        <label class=' mt-2'><span class='helpDesign help_1'>1</span></label>
                    </div>
                </div>
            </div>

            <?php
                if($_SESSION['role'] != 3 && $_SESSION['role'] != 5){
            ?>

            <!-- Register a Member -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <a class="nav-link" href="#" data-toggle="modal" data-target="#registerMemberModal">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        <span>Register</span>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-fw fa-user-plus fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </a>
                        <label class=' mt-2'><span class='helpDesign help_2'>2</span></label>
                    </div>
                </div>
            </div>
            <?php
                }
            ?>

        </div>

        <!-- DATATABLE -->
        <div class="container pt-4">
            <div class="row">
                <div class="card-body" style="width:10px;">
                    <div class="table-responsive" style="border-radius: 15px !important;">
                        <div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4">
                            <div class="row">
                                <div class="col-sm-12">
                                    <table id="membersTable" class="table display table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th scope="col">Sl</th>
                                                <th scope="col">Name</th>
                                                <?php
                                                    if(isset($_SESSION['role']) && ( $_SESSION['role'] == 1 || $_SESSION['role'] == -1) ){                                                        ?>
                                                            <th scope="col">Firm Name</th>
                                                        <?php                       
                                                    }
                                                ?>
                                                <th scope="col">Email</th>
                                                <th scope="col">Role</th>
                                                <th scope="col">Status</th>
                                                <th scope="col">Registration Date</th>
                                                <th scope="col">SignOff Initials</th>
                                                <?php
                                                    if(isset($_SESSION['role']) && $_SESSION['role'] != 3 && $_SESSION['role'] != 5){
                                                    ?>
                                                        <th scope="col">Edit</th>
                                                        <?php 
                                                            if(isset($_SESSION['role']) && $_SESSION['role'] != 1)
                                                            {
                                                                ?>
                                                                    <th scope="col">Client</th>
                                                                <?php
                                                            }
                                                        ?>
                                                    <?php
                                                    }
                                                ?>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="sticky-footer">
            <div class="container my-auto">
                <div class="copyright text-center my-auto">
                    <span><strong><span style="color: #4eb92b;">Auditors</span><span style="color: #254eda;">Desk</span>&copy;
                    <?php echo date("Y"); ?></strong></span>
                </div>
            </div>
        </footer>


        <?php
            if(isset($_SESSION['role']) && ($_SESSION['role'] != 3 && $_SESSION['role'] != 5)){
        ?>

        <!-- Register a Member Form -->
        <div class="modal fade" id="registerMemberModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-size" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Register A Member
                            <h5>
                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                    </div>
                    <form autocomplete="off">
                        <div class="modal-body">
                            <div class="form-group ">
                                <label for="name">Full Name</label>
                                <input type="text" class="form-control" name="name" id="name" required>
                            </div>
                            <div class="form-group ">
                                <label for="name">Email Address</label>
                                <input type="email" class="form-control" name="email" id="email" autocomplete="off" required>
                            </div>
                            <div class="form-group ">
                                <label for="name">Password</label>
                                <input type="password" class="form-control" name="password" id="password" autocomplete="off" required>
                            </div>
                            <div class="form-group ">
                                <label for="name">Role</label>
                                <select name="role" id="role" class="form-control" required>
                                    <option value="">Select role</option>
                                    <?php
                                    if($_SESSION['role'] == -1)
                                    {
                                    ?>
                                    <option value="1">Software Admin</option>
                                    <option value="4">Firm Admin</option>
                                    <option value="2">Audit Admin</option>
                                    <option value="3">Audit Member</option>
                                    <?php
                                    }
                                    elseif($_SESSION['role'] == 1){
                                        ?>
                                        <option value="4">Firm Admin</option>
                                        <option value="2">Audit Admin</option>
                                        <option value="3">Audit Member</option>
                                        <?php
                                    }
                                    elseif($_SESSION['role'] == 4){
                                        ?>
                                        <option value="2">Audit Admin</option>
                                        <option value="3">Audit Member</option>
                                        <?php
                                    }
                                    elseif($_SESSION['role'] == 2){
                                        ?>
                                        <option value="3">Audit Member</option>
                                        <?php
                                    }
                                    
                                    ?>
                                </select>
                            </div>
                            <?php
                                    if($_SESSION['role'] == -1 || $_SESSION['role'] == 1)
                                    {
                                    ?>
                            <div class="form-group ">
                                <label for="name">Firm</label>
                                <select name="firm_id" id="firm_id" class="form-control" required>
                                    <option value="">Select Firm</option>
                                    <?php
                                        $result = $con->query("Select * from firm_details");
                                        while($row = $result->fetch_assoc()){
                                            ?>
                                            <option value="<?php echo $row['id']; ?>"><?php echo $row['firm_name']; ?></option>
                                            <?php
                                        }
                                    ?>
                                </select>
                            </div>
                                <?php
                            }
                            
                            ?>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-danger" type="button" data-dismiss="modal">Cancel</button>
                            <input class="btn btn-primary" type="submit" id="registerSubmit" value="Done">
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit a Member Form -->
        <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-size" role="document">
                <div class="modal-content">
                    <form>
                        <div class="modal-body">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Member<h5>
                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div><br>
                            <div class="form-group ">
                                <label for="name">Full Name</label>
                                <input type="text" class="form-control" name="name" id="name1" readonly>
                            </div>
                            <div class="form-group ">
                                <label for="name">Email Address</label>
                                <input type="email" class="form-control" name="email" id="email1" autocomplete="off"
                                    readonly>
                            </div>
                            <div class="form-group ">
                                <label for="name">Role</label>
                                <select name="role" id="role1" class="form-control">
                                    <option value="">Select role</option>
                                    <?php 
                                        if($_SESSION['role'] == 4){
                                            ?>
                                        <option value="4">Firm Admin</option>
                                       <?php }
                                    ?>
                                    <option value="2">Audit Admin</option>
                                    <option value="3">Audit Member</option>
                                </select>
                            </div>
                            <div class="form-group ">
                                <label for="name">Status</label>
                                <select name="active" id="active1" class="form-control">
                                    <option value="">Select Status</option>
                                    <option value="0">Access Denied</option>
                                    <option value="1">Allowed</option>
                                </select>
                            </div>
                            <div class="form-group ">
                                <label for="name">Sign-Off Initial</label>
                                <input type="text" class="form-control" name="signoff" id="signoff1" readonly>
                            </div>
                        </div>
                        <div class="modal-footer d-flex align-items-center justify-content-center">
                            <!-- <button class="btn btn-danger" type="button" data-dismiss="modal">Cancel</button> -->
                            <input class="btn btn-primary" type="submit" id="submit1" value="Done">
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Allocate Client Modal -->
        <div class="modal fade" id="allocate" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Clients<h5>
                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                    </div>
                    <form>
                        <div class="modal-body">
                            <div class="form-group ">
                                <input type="hidden" id="memberId" name="memberId" value="">
                                <label for="name">Name</label>
                                <input type="text" id="name2" class="form-control" name="name2" readonly>
                            </div>
                            <div class="row d-flex justify-content-between">
                                <div class="col-md-5 d-flex justify-content-center" style="margin-right:178px;">
                                    <span class="badge badge-primary">All Clients</span>
                                </div>
                                <div class="col-md-5 d-flex justify-content-around">
                                    <span class="badge badge-danger">Allocated Clients</span>
                                </div><br>
                            </div><br>
                            <div class="row">
                                <div class="col-md-5">
                                    <select name="from[]" id="lstview" class="form-control" size="20" multiple>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" id="lstview_rightAll" class="btn btn-default btn-block">
                                        <i class="fas fa-angle-double-right"></i>
                                    </button>
                                    <button type="button" id="lstview_rightSelected" class="btn btn-default btn-block">
                                        <i class="fas fa-arrow-right"></i>
                                    </button>
                                    <button type="button" id="lstview_leftSelected" class="btn btn-default btn-block">
                                        <i class="fas fa-arrow-left"></i>
                                    </button>
                                    <button type="button" id="lstview_leftAll" class="btn btn-default btn-block">
                                        <i class="fas fa-angle-double-left"></i>
                                    </button>
                                </div>
                                <div class="col-md-5">
                                    <select name="to[]" id="lstview_to" class="form-control" size="20" multiple></select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-danger" type="button" data-dismiss="modal">Cancel
                            </button>
                            <input class="btn btn-primary" id="submit2" type="submit" value="Done">
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <?php
            }
        ?>

        <div id = "helpDescriptionTop" class="d-flex justify-content-center">
            <div id="helpDescription" class="col-md-11">
                <div class="card" style="border: 4px solid rgb(134, 189, 255, 0.65) !important;box-shadow: 0px 0px 20px 1px rgba(0,0,0,0.5);">
                    <div class="card-body">
                        <button type="button" class="close" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <div id="help_1">
                            <p>1. Total members: Reflects the count of your firm members that are actively added your firm software.</p>
                        </div>
                        <div id="help_2">
                            <p>2. Register: Use register button to add new team members to your firm, once you register a member you can allocate clients.</p>
                        </div>
                        <div id="help_3">
                            <p>3. Edit: You can edit team member information using the edit feature.</p>
                        </div>
                        <div id="help_4">
                            <p>4. Allocate: Using the allocate button you can assign a specific client to a specific team member.</p>
                        </div>
                        <div id="help_5">
                            <p>5. Profile: User profile reflects brief details about the user and can be edited by firm administrator.</p> 
                        </div>
                        <div id="help_6">
                            <p>6. Client list: Will take you to the main page where the list of all clients allocated to you is present.</p>
                        </div>
                        <div id="help_7">
                            <p>7. Settings – Your Settings are personalized based on your role in your firm and can be accessed at all times for chat, email and reaching out to a specialist for any help.</p>
                        </div>
                        <div id="help_8">
                            <p>8. Log out- Simply use this button to log out of your firm workspace and client list.</p>
                        </div>
                        <i id="left-arrow" class="fas fa-arrow-left"></i>
                        <i id="right-arrow" class="fas fa-arrow-right"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Photo Modal -->
        <div class="modal fade" id="photoModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-size" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Update Profile Photo </h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                    </div>
                    <form action="../updatePhoto" method="POST" enctype="multipart/form-data" autocomplete="off">
                        <div class="modal-body">
                            <div class="form-group">
                                <input type="hidden" name="uid" value="<?php echo $_SESSION['id']; ?>">
                            </div>
                            <div class="form-group ">
                                <label for="name">Upload Photo</label>
                                <input type="file" class="form-control" name="image" accept="image/x-png,image/gif,image/jpeg,image/jpg" required>
                            </div>
                        </div> 
                        <div class="modal-footer justify-content-center">
                            <button class="btn btn-danger" type="button" data-dismiss="modal">Cancel</button>
                            <input class="btn btn-primary" type="submit" value="Update">
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>


    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Core plugin JavaScript-->
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
    <!-- Custom scripts for all pages-->
    <script src="../js/sb-admin-2.min.js"></script>
    <!-- Page level custom scripts -->
    <script src="../js/custom.js"></script>
    <!-- MULTISELECT JS -->
    <script src="../js/multiselect-master/dist/js/multiselect.js"></script>
    <script>
    $(document).ready(function() {

        document.getElementsByTagName("html")[0].style.visibility = "visible";
        
        get_data();

        $("#helpDescription > div > div > .close").click(function(e){
            $(".helpDesign, #helpDescription").toggle();
        });

        $(".helpDesign, #helpDescription").hide();

        $("#helpButton").click(function(e){
            $(".helpDesign, #helpDescription").toggle();
            $("#help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8").hide();
            $("#help_1").show();
            $(".help_1").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
            $(".help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
        });
        $(".help_1").click(function(e){
            $(".help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
            $(".help_1").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
            $("#help_1").show();
            $("#help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8").hide();
        });

        $(".help_2").click(function(e){
            $(".help_1, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
            $(".help_2").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
            $("#help_2").show();
            $("#help_1, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8").hide();
        });

        $(".help_3").click(function(e){
            $(".help_1, .help_2, .help_4, .help_5, .help_6, .help_7, .help_8").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
            $(".help_3").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
            $("#help_3").show();
            $("#help_1, #help_2, #help_4, #help_5, #help_6, #help_7, #help_8").hide();
        });

        $(".help_4").click(function(e){
            $(".help_1, .help_2, .help_3, .help_5, .help_6, .help_7, .help_8").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
            $(".help_4").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
            $("#help_4").show();
            $("#help_1, #help_2, #help_3, #help_5, #help_6, #help_7, #help_8").hide();
        });

        $(".help_5").click(function(e){
            $(".help_1, .help_2, .help_3, .help_4, .help_6, .help_7, .help_8").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
            $(".help_5").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
            $("#help_5").show();
            $("#help_1, #help_2, #help_3, #help_4, #help_6, #help_7, #help_8").hide();
        });
        
        $(".help_6").click(function(e){
            $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_7, .help_8").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
            $(".help_6").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
            $("#help_6").show();
            $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_7, #help_8").hide();
        });

        $(".help_7").click(function(e){
            $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6, .help_8").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
            $(".help_7").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
            $("#help_7").show();
            $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6, #help_8").hide();
        });

        $(".help_8").click(function(e){
            $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6, .help_7").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
            $(".help_8").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
            $("#help_8").show();
            $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6, #help_7").hide();
        });

        $("#right-arrow").click(function(e){
            if($(".help_1").hasClass("helpDesignSelected")){
                $(".help_1, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8, .help_9").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                $(".help_2").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                $("#help_2").show();
                $("#help_1, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8, #help_9").hide();
            }
            else if($(".help_2").hasClass("helpDesignSelected")){
                $(".help_1, .help_2, .help_4, .help_5, .help_6, .help_7, .help_8, .help_9").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                $(".help_3").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                $("#help_3").show();
                $("#help_1, #help_2, #help_4, #help_5, #help_6, #help_7, #help_8, #help_9").hide();
            }
            else if($(".help_3").hasClass("helpDesignSelected")){
                $(".help_1, .help_2, .help_3, .help_5, .help_6, .help_7, .help_8, .help_9").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                $(".help_4").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                $("#help_4").show();
                $("#help_1, #help_2, #help_3, #help_5, #help_6, #help_7, #help_8, #help_9").hide();
            }
            else if($(".help_4").hasClass("helpDesignSelected")){
                $(".help_1, .help_2, .help_3, .help_4, .help_6, .help_7, .help_8, .help_9").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                $(".help_5").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                $("#help_5").show();
                $("#help_1, #help_2, #help_3, #help_4, #help_6, #help_7, #help_8, #help_9").hide();
            }
            else if($(".help_5").hasClass("helpDesignSelected")){
                $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_7, .help_8, .help_9").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                $(".help_6").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                $("#help_6").show();
                $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_7, #help_8, #help_9").hide();
            }
            else if($(".help_6").hasClass("helpDesignSelected")){
                $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6, .help_8, .help_9").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                $(".help_7").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                $("#help_7").show();
                $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6, #help_8, #help_9").hide();
            }
            else if($(".help_7").hasClass("helpDesignSelected")){
                $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_9").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                $(".help_8").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                $("#help_8").show();
                $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_9").hide();
            }
            else if($(".help_8").hasClass("helpDesignSelected")){
                $(".help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                $(".help_1").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                $("#help_1").show();
                $("#help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8").hide();
            }
        });

        $("#left-arrow").click(function(e){
            if($(".help_1").hasClass("helpDesignSelected")){
                $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6, .help_7").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                $(".help_8").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                $("#help_8").show();
                $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6, #help_7").hide();
            }
            else if($(".help_2").hasClass("helpDesignSelected")){
                $(".help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                $(".help_1").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                $("#help_1").show();
                $("#help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8").hide();
            }
            else if($(".help_3").hasClass("helpDesignSelected")){
                $(".help_1, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                $(".help_2").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                $("#help_2").show();
                $("#help_1, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8").hide();
            }
            else if($(".help_4").hasClass("helpDesignSelected")){
                $(".help_1, .help_2, .help_4, .help_5, .help_6, .help_7, .help_8").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                $(".help_3").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                $("#help_3").show();
                $("#help_1, #help_2, #help_4, #help_5, #help_6, #help_7, #help_8").hide();
            }
            else if($(".help_5").hasClass("helpDesignSelected")){
                $(".help_1, .help_2, .help_3, .help_5, .help_6, .help_7, .help_8").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                $(".help_4").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                $("#help_4").show();
                $("#help_1, #help_2, #help_3, #help_5, #help_6, #help_7, #help_8").hide();
            }
            else if($(".help_6").hasClass("helpDesignSelected")){
                $(".help_1, .help_2, .help_3, .help_4, .help_6, .help_7, .help_8").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                $(".help_5").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                $("#help_5").show();
                $("#help_1, #help_2, #help_3, #help_4, #help_6, #help_7, #help_8").hide();
            }
            else if($(".help_7").hasClass("helpDesignSelected")){
                $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_7, .help_8").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                $(".help_6").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                $("#help_6").show();
                $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_7, #help_8").hide();
            }
            else if($(".help_8").hasClass("helpDesignSelected")){
                $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6, .help_8").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                $(".help_7").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                $("#help_7").show();
                $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6, #help_8").hide();
            }
        });

        $('#lstview').multiselect();
        $('#lstview_to').multiselect();

        $(document).on('click','#submit2',function(e){
            e.preventDefault();
            var selectedValues = []; 
            $("#lstview_to option").each(function(){
                selectedValues.push($(this).val()); 
            });
            var id = $("#memberId").val();
            var name = $("#name2").val(); 
            
            $("#allocate").modal('hide');
            $.ajax({
                url: "clientAllocate.php",
                type: "POST",
                data: {
                    memberId:id,
                    name:name,
                    selectedValues:selectedValues
                },
                success: function(data){  
                    data = JSON.parse(data);  
                    swal({
            closeOnClickOutside: false,
                        icon: data['status'] == true? 'success':'error',
                        text: data['text'],
                    }).then(function(isConfirm) {
                        if (isConfirm) {
                            location.reload();
                        }
                    });
                }
            });    
        });

        let darkmode = <?php echo $_SESSION['darkmode']; ?>;
        if(darkmode)
        {
            document.documentElement.classList.toggle('dark-mode');
            
        }
        else if(!darkmode){
            document.documentElement.classList.remove('dark-mode');
        }

        $("#addClientForm").submit(function(event){
            let firm = $("#firm_id").val();
            if(firm.length == ''){
                event.preventDefault();
                swal({
            closeOnClickOutside: false,
                    icon: "error",
                    text: "Select Firm!",
                }).then(function(isConfirm) {
                    $('#firm_id').focus();
                });
            }
        });
    });

    $(document).on('click', '.editMember', function() {
        var id = $(this).attr("id");
        $("#editModal #active1 > option:selected").removeAttr('selected');
        $.ajax({
            url: "editMemberFetchAjax.php",
            type: "POST",
            data: {
                id: id
            },
            success: function(data) {
                obj = JSON.parse(data);
                id = obj.id;
                $("#editModal #name1").val(obj.name);
                $("#editModal #email1").val(obj.email);
                $("#editModal #role1").val(obj.accessLevel);
                $("#editModal #active1 option[value=" + obj.active + "]").attr(
                    'selected', 'selected');
                $("#editModal #signoff1").val(obj.signoff_init);
                $("#editModal").modal('show');
            }
        });
    });

    function get_data() {
        var dataTable = $('#membersTable').DataTable({
            "destroy": true,
            "processing": true,
            "serverSide": true,
            "searching": true,
            "order": [],
            "bInfo": false,
            "fnRowCallback": function(nRow, aData, iDisplayIndex) {
                $("td:first", nRow).html(iDisplayIndex + 1);
                return nRow;
            },
            "drawCallback": function(settings) {
                $(".helpDesign, #helpDescription").hide();
            },
            "columnDefs": [
                <?php
                    if(isset($_SESSION['role']) && $_SESSION['role'] == -1){
                ?>
                    { orderable: false, targets: -10 },
                    { orderable: false, targets: -2 },
                    { orderable: false, targets: -1 }
                <?php
                    }
                    elseif(isset($_SESSION['role']) && ( $_SESSION['role'] == 4 || $_SESSION['role'] == 2) ){
                        ?>
                            { orderable: false, targets: -9 },
                            { orderable: false, targets: -2 },
                            { orderable: false, targets: -1 }
                        <?php
                    }
                    elseif(isset($_SESSION['role']) && $_SESSION['role'] == 1){
                        ?>
                            { orderable: false, targets: -9 },
                            { orderable: false, targets: -1 }
                        <?php
                    }
                    else {
                        ?>
                { orderable: false, targets: -7 }
                        <?php
                    }
                    ?>
            ],
            "ajax": {
                url: "memberFetchAjax.php",
                type: "POST",
                async: false
            }
        });

        dataTable.on( 'draw', function () {
            var body = $( dataTable.table().body() );
    
            body.unhighlight();
            body.highlight( dataTable.search() );  
        });
    }

    $(document).on('click', '#submit1', function(e) {
        e.preventDefault();
        var name = $("#name1").val();
        var email = $("#email1").val();
        var role = $("#role1").val();
        var active = $("#active1").val();
        $("#editModal").modal('hide');

        if(role == 4){
            swal({
            closeOnClickOutside: false,
                title: "Are you sure?",
                text: "You want to change Firm Admin?",
                icon: "warning",
                buttons: ['No','Yes'],
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: "editAMember.php",
                        type: "POST",
                        data: {
                            name: name,
                            email: email,
                            role: role,
                            active: active
                        },
                        success: function(data) {
                            if (data) {
                                swal({
            closeOnClickOutside: false,
                                    icon: "success",
                                    text: "Updated",
                                }).then(function(isConfirm) {
                                    if (isConfirm) {
                                        location.reload();
                                    }
                                });
                            } else {
                                swal({
            closeOnClickOutside: false,
                                    icon: "error",
                                    text: "Failed!",
                                }).then(function(isConfirm) {
                                    if (isConfirm) {
                                        location.reload();
                                    }
                                });
                            }
                        }
                    });
                } 
                else 
                {
                    swal({
            closeOnClickOutside: false,
                        text: "Updation Cancelled!",
                        icon: "error",
                    })
                }
            });
        }
        else{
            $.ajax({
                url: "editAMember.php",
                type: "POST",
                data: {
                    name: name,
                    email: email,
                    role: role,
                    active: active
                },
                success: function(data) {
                    if (data) {
                        swal({
            closeOnClickOutside: false,
                            icon: "success",
                            text: "Updated",
                        }).then(function(isConfirm) {
                            if (isConfirm) {
                                location.reload();
                            }
                        });
                    } else {
                        swal({
            closeOnClickOutside: false,
                            icon: "error",
                            text: "Failed!",
                        }).then(function(isConfirm) {
                            if (isConfirm) {
                                location.reload();
                            }
                        });
                    }
                }
            });
        }
    });

    $(document).on('click', '.allocate', function() {
        var id = $(this).attr("id");
        $.ajax({
            url: "clientMemberFetchAjax.php",
            type: "POST",
            data: {
                id: id
            },
            success: function(data) {
                obj = JSON.parse(data);
                id = obj.id;
                $("#allocate #name2").val(obj.name);
                $("#allocate #memberId").val(obj.id);
                fromSelect(id);
                toSelect(id);
                $("#allocate").modal('show');
            }
        });

        function fromSelect(id) {
            $("#allocate #lstview").empty();
            $.ajax({
                url: "fromClientAjax.php",
                type: "POST",
                data: {
                    id: id
                },
                success: function(data) {
                    cObj = JSON.parse(data);
                    for (var i = 0; i < cObj.length; i++) {
                        $("#allocate #lstview").append('<option value="' + cObj[i].id + '">' + cObj[i].name + '</option>');
                    }
                }
            });
        }

        function toSelect(id) {
            $("#allocate #lstview_to").empty();
            $.ajax({
                url: "toClientAjax.php",
                type: "POST",
                data: {
                    id: id
                },
                success: function(data) {
                    cObj = JSON.parse(data);
                    for (var i = 0; i < cObj.length; i++) {
                        $("#allocate #lstview_to").append('<option value="' + cObj[i].id + '">' + cObj[i].name + '</option>');
                    }
                }
            });
        }

    });

    $('#registerSubmit').on('click', function(e) {
        e.preventDefault();
        var name = $("#name").val();
        var email = $("#email").val();
        var password = $("#password").val();
        var role = $("#role").val();        
        
        <?php
            if(isset($_SESSION['role']) && !empty($_SESSION['role']) && ( $_SESSION['role'] == 1 || $_SESSION['role'] == -1 )){
                ?>
                let firm = $("#firm_id").val();
                if(firm == '' || name == '' || email == '' || password == '' || role == ''){
                    e.preventDefault();
                    swal({
                        closeOnClickOutside: false,
                        icon: "error",
                        text: "All the fields are required!",
                    }).then(function(isConfirm) {
                        if(name == ''){
                            $('#name').focus();
                        }
                        else if(email == ''){
                            $('#email').focus();
                        }
                        else if(password == ''){
                            $('#password').focus();
                        }
                        else if(role == ''){
                            $('#role').focus();
                        }
                        else{
                            $('#firm_id').focus();
                        }
                    });
                }
                else{
                    $.ajax({
                        url: "addMember.php",
                        type: "POST",
                        data: {
                            name: name,
                            email: email,
                            password: password,
                            role:role,
                            firm_id:$('#firm_id').val()
                        },
                        success: function(response) {
                            console.log(response);
                            if (response == 1) {
                                swal({
                                    closeOnClickOutside: false,
                                    icon: "success",
                                    text: name + " Added",
                                }).then(function(isConfirm) {
                                    if (isConfirm) {
                                        location.reload();
                                    }
                                });
                            } else {
                                swal({
                                    closeOnClickOutside: false,
                                    icon: "error",
                                    text: "Already Exists!",
                                }).then(function(isConfirm) {
                                    if (isConfirm) {
                                        location.reload();
                                    }
                                });
                            }
                        }
                    });
                }
                <?php
            }
            else{
                ?>
                 $.ajax({
                    url: "addMember.php",
                    type: "POST",
                    data: {
                        name: name,
                        email: email,
                        password: password,
                        role:role
                    },
                    success: function(response) {
                        console.log(response);
                        if (response == 1) {
                            swal({
            closeOnClickOutside: false,
                                icon: "success",
                                text: name + " Added",
                            }).then(function(isConfirm) {
                                if (isConfirm) {
                                    location.reload();
                                }
                            });
                        } else {
                            swal({
            closeOnClickOutside: false,
                                icon: "error",
                                text: "Already Exists!",
                            }).then(function(isConfirm) {
                                if (isConfirm) {
                                    location.reload();
                                }
                            });
                        }
                    }
                });
                <?php
            }
        ?>
    });
    </script>
</body>

</html>