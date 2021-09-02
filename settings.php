<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
    include 'dbconnection.php';
    include "decimal2point.php";
    session_start();

    if (isset($_SESSION['external']) && !empty($_SESSION['external']) && $_SESSION['external'] == 1){
        $checkAccess = $con->query("select id from accounts_log where client_contact_id = ".$_SESSION['id'])->num_rows;
        if($checkAccess){
            $clientName = 1;
            $location =  base64_encode(md5($clientName)).'&gid='. base64_encode(md5($clientName)).'&fid='. base64_encode(md5($clientName)).'&eid='.base64_encode(md5($clientName)).'&cid='.base64_encode($_SESSION['external_client_id']);
            header('Location: workspace?vid='.$location);
        }
        else{
            header("Location: logout");
        }
    }

    if (!isset($_SESSION['email']) && empty($_SESSION['email'])) {
        header("Location: login");
    }
    // if(isset($_GET['wid']) && !empty($_GET['wid']))
    //     $wid = $_GET['wid'];
    // $clientId= $_GET['cid'];
    // $clientName = $con->query("select name from client where id = $clientId ")->fetch_assoc()["name"];
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
    <meta name="google" content="notranslate" />

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="css/custom.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/uiux.css" rel="stylesheet" type="text/css">
    <link href="css/chat.css" rel="stylesheet" type="text/css">

    <!-- JQuery CDN -->
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.min.js"
        integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>

    <!-- Datatable CDN -->
    <link href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css" rel="stylesheet">
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
    <!-- SweetAlert -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"
        integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA=="
        crossorigin="anonymous"></script>

        <style>
        .card-body{
            margin: 0 13.5%;
        }
        .custom-control{
            padding-left: 2.5rem;
        }
        </style>
</head>

<body style="overflow-y: scroll">

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
            <?php 
            if(!empty($wid))
            {
            ?>
            <li class="nav-item d-flex">
                <a class="nav-link d-flex align-items-center" href="clientDashboard?qid=<?php echo base64_encode(md5($clientName)); ?>&gid=<?php echo base64_encode(md5($clientName)); ?>&fid=<?php echo base64_encode(md5($clientName)); ?>&eid=<?php echo base64_encode(md5($clientName)); ?>&cid=<?php echo base64_encode($_SESSION['client_id']); ?>&yid=<?php echo base64_encode(md5($clientName)); ?>&bid=<?php echo base64_encode(md5($clientName)); ?>&aid=<?php echo base64_encode(md5($clientName)); ?>&zid=<?php echo base64_encode(md5($clientName)); ?>&jid=<?php echo base64_encode(md5($clientName)); ?>&wid=<?php echo base64_encode($wid); ?>&xid=<?php echo base64_encode(md5($clientName)); ?>">
                    <img class="nav-icon" src="Icons/Group 3.svg"/>&nbsp;&nbsp;
                    <span>Dashboard</span>
                </a>
            </li>
            <?php }
            else{ ?>
            <li class="nav-item d-flex">
                <a class="nav-link d-flex align-items-center" href="admin/clientList">
                    <img class="nav-icon" src="Icons/Group 3.svg"/>&nbsp;&nbsp;
                    <span>Client List</span>
                </a>
            </li>
            <?php } ?>
            <li class="nav-item d-flex" style="background-color: rgba(232,240,255,1); border-radius: 15px;">
                <span class="nav-icon d-flex align-items-center" style="padding: 0 0 0 10px !important;">
                    <?php
                        $img_query = $con->query("SELECT * FROM user WHERE id = ".$_SESSION['id']." and img != ''");
                        if($img_query->num_rows == 1){
                            $row = $img_query->fetch_assoc();
                            ?>
                            <img class = "profilePhoto" src="images/<?php echo $row['img']; ?>">
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
                        <img class="nav-icon" src="Icons/Group 6.svg" style="width:15px !important;"/>
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
                <img class="sidenav-icon" src="Icons/Group-1.png"/> &nbsp;
               
            </div>
        </div>
        <div class="side-footer">
            <div class="side-body">
                <!-- <div class="dash">
                    <img class="sidenav-icon" src="Icons/pie-chart.svg" style="width:24px !important; height:24px !important;"/> &nbsp;
                    Contacts
                    </svg>
                </div> -->
            </div>
            <div class="settings">
                <div class="settings-items-top-div">
                    <div class="settings-items">
                        <a href="settings" class="text-decoration-none">
                            <img class="sidenav-icon" src="Icons/settings.svg" style="width:24px !important; height:24px !important;"/> &nbsp;Settings
                        </a>
                    </div>
                    <!-- <div class="settings-items">
                        <img class="sidenav-icon" src="Icons/help-circle.svg" style="width:24px !important; height:24px !important;"/> &nbsp;
                        Help
                    </div> -->
                </div>
                <a href="logout"><button type="button" class="btn btn-primary"><i class="fas fa-sign-out-alt"></i> Logout</button></a>
            </div>
        </div>
    </div>

    <div class="mar">

        <!-- HEADER -->
        <div id="header">
            <div class="container-fluid shadow" stickylevel="0" style="z-index:1200;">
                <div class="row pt-1">
                    <div class="col-md-4">
                        <!-- <img class="float-left" src="vendor/img/Auditors Deske-logo.svg" style="height:45px;"> -->
                        <div class="ml-2 font-1 h3 py-1 d-inline-block float-left"></div>
                    </div>
                    <div class="col-md-4 text-center font-2 getContent" href="#">
                        <h3>SETTINGS</h3>
                    </div>
                </div>
            </div>
        </div><br>

        <!-- Body -->
        <div class="row gutters-sm">
            <div class="col-md-12 d-none d-md-block">
                <div class="nav flex-row nav-pills nav-gap-y-1 justify-content-center align-items-center">
                    <a href="#profile" data-toggle="tab" class="nav-item nav-link has-icon nav-link-faded size active" id="profileTab">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user mr-2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>Firm Profile
                    </a>
                    <a href="#account" data-toggle="tab" class="nav-item nav-link has-icon nav-link-faded size" id="accountTab">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-settings mr-2"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                        <?php
                            if($_SESSION['role'] == 1 || $_SESSION['role'] == -1){
                                echo 'Chat';
                            }
                            else{
                                echo 'Tools';
                            }
                        ?>
                    </a>
                    <a href="#billing" data-toggle="tab" class="nav-item nav-link has-icon nav-link-faded size" id="billingTab">
                        <?php
                            if($_SESSION['role'] == 1 || $_SESSION['role'] == -1 || $_SESSION['role'] == 4){
                                ?>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-credit-card mr-2"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>
                                <?php 
                                    if($_SESSION['role'] == 1 || $_SESSION['role'] == -1){
                                        echo 'Storage';
                                    }
                                    else{
                                        echo 'Billing';
                                    }
                                    ?>
                                <?php
                            } 
                        ?>    
                    </a>
                    <?php 
                        if($_SESSION['role'] == 1 || $_SESSION['role'] == -1){
                    ?>
                    <a href="#subscription" data-toggle="tab" class="nav-item nav-link has-icon nav-link-faded size">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user mr-2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>Subscription
                    </a>
                    <?php } ?>
                </div>
            </div>
        </div><br><br>
        <div class="col-md-12">
            <div class="d-flex col-md-12">
                <div class="card-header border-bottom mb-3 d-flex d-md-none">
                    <ul class="nav nav-tabs card-header-tabs nav-gap-x-1" role="tablist">
                        <li class="nav-item">
                        <a href="#profile" data-toggle="tab" class="nav-link has-icon active"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg></a>
                        </li>
                        <li class="nav-item">
                        <a href="#account" data-toggle="tab" class="nav-link has-icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-settings"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg></a>
                        </li>
                        <li class="nav-item">
                        <a href="#billing" data-toggle="tab" class="nav-link has-icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-folder"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg></a>
                        </li>
                        <li class="nav-item">
                        <a href="#subscription" data-toggle="tab" class="nav-link has-icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-credit-card"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg></a>
                        </li>
                    </ul>
                </div>
                <div class="card-body tab-content" id="addMargin">
                    <div class="tab-pane active" id="profile">
                        <?php 
                            if($_SESSION['role'] != 1 && $_SESSION['role'] != -1){
                        ?>
                        <h6>YOUR FIRM INFORMATION</h6>
                        <hr>
                        <form>
                            <div class="form-group">
                                <label for="fullName">Firm Name</label>
                                <input type="text" class="form-control" id="fullName" aria-describedby="fullNameHelp" placeholder="Enter your Firm Name" value="<?php echo $_SESSION['firm_details']['firm_name']; ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label for="bio">Contact Address</label>
                                <textarea class="form-control autosize" id="bio" placeholder="Enter contact address" style="overflow: hidden; overflow-wrap: break-word; resize: none; height: 62px;" readonly><?php echo $_SESSION['firm_details']['firm_address']; ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="url">Firm Email</label>
                                <input type="text" class="form-control" id="url" placeholder="Enter your email address" value="<?php echo $_SESSION['firm_details']['firm_email']; ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label for="location">Firm Leader</label>
                                <input type="text" class="form-control" id="location" placeholder="Enter your Firm Leader" value="<?php $firm_leader = $con->query("SELECT name FROM user inner join firm_user_log on user.id=firm_user_log.user_id where firm_user_log.firm_id=".$_SESSION['firm_id']." and accessLevel = 4"); if($firm_leader->num_rows > 0){ $firm_lead = $firm_leader->fetch_assoc()['name']; echo (trim($firm_lead)); } ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label>Multicurrency</label>
                                <select class="form-control" disabled>
                                    <option value="Yes">Yes</option>
                                    <option value="No" selected>No</option>
                                </select>
                            </div>
                        <!-- <button type="button" class="btn btn-primary">Update Profile</button>
                        <button type="reset" class="btn btn-light">Reset Changes</button> -->
                        </form>
                        <?php
                        }
                        else{
                            ?>
                                <table class="table">
                                    <thead>
                                        <th>Firm Name</th>
                                        <th>Firm Unique ID</th>
                                        <th>Firm Address</th>
                                        <th>Firm Email</th>
                                        <!-- <th>Action</th> -->
                                    </thead>
                                    <tbody>  
                                    <?php
                                        $result = $con->query("SELECT * from firm_details");
                                        if($result->num_rows){
                                            while($row = $result->fetch_assoc()){
                                                ?>
                                                    <tr>
                                                        <td><?php echo $row['firm_name']; ?></td>
                                                        <td><?php echo 'AE/'.date('Y').'/'.strtoupper(substr(md5($row['id']),3,8)); ?></td>
                                                        <td><?php echo $row['firm_address']; ?></td>
                                                        <td><?php echo $row['firm_email']; ?></td>
                                                    </tr>
                                                <?php
                                            }
                                        }
                                        else{
                                            ?>
                                                <tr>
                                                    <td colspan="4">No Firm is registered.</td>
                                                </tr>
                                            <?php
                                        }
                                    ?>
                                        
                                    </tbody>
                                </table>
                            <?php
                        }
                        ?>
                    </div>
                    <div class="tab-pane" id="account">
                        <?php 
                            if($_SESSION['role'] != 1 && $_SESSION['role'] != -1){
                        ?>
                        <h6>Tools Settings</h6>
                        <hr>
                        <form>
                            <div class="d-flex">
                                <label>Mode</label>
                                <div class="form-group custom-control custom-switch">    
                                    <input type="checkbox" class="custom-control-input" id="customSwitch1" value="true">
                                    <label class="custom-control-label" for="customSwitch1">Toggle this switch to turn on/off dark mode</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="username">Chat Assistance</label><br>
                                <input type="button" class="btn btn-primary" id="chatAssistance"value="Chat Now!">
                            </div>
                            <hr>
                            <div class="form-group">
                                <label class="d-block text-danger">Help Desk Number</label>
                                <input type="text" class="form-control" id="number" aria-describedby="numberHelp" value="1800-600-5958" readonly>
                            </div>
                            <div class="form-group">
                                <label class="d-block text-danger">Email Us</label>
                                <input type="text" class="form-control" id="email" aria-describedby="emailHelp" value="yourfirmaudit@gmail.com" readonly>
                            </div>
                            <div class="form-group">
                                <label class="d-block text-danger">24/7 Help Desk Number</label>
                                <input type="text" class="form-control" id="24Number" aria-describedby="24NumberHelp" value="+91 9971130410" readonly>
                            </div>
                            <!-- <button class="btn btn-danger" type="button">Delete Account</button> -->
                        </form>
                        <div class="messenger">
                            <div id="live-chat">
                                <header class="clearfix">
                                    <h4>Chat</h4>
                                    <!-- <span class="chat-message-counter">3</span> -->
                                </header>
                                <a href="#" class="chat-close"><i class="fas fa-times"></i></a>
                                <div class="chat">
                                    
                                    <div class="chat-history">

                                    </div> <!-- end chat-history -->

                                    <form id="chatForm" enctype="multipart/form-data">
                                        <input type="hidden" id="user_id" name="user_id">
                                        <input type="text" placeholder="Enter Your Queries" id="chatText" name="chatText" autofocus>&nbsp;
                                        <div class="image_upload">
                                            <label for="file"><i class="far fa-2x fa-images pl-3 pt-1"></i></label>
                                            <input type="file" name="file" id="file" accept=".jpg, .png, .jpeg, .gif, .pdf" />
                                        </div>
                                        <input class="btn btn-outline-dark ml-2" type="submit" value="Send">
                                    </form>
                                    <div><i class="fas fa-info-circle m-0" style="color:#FFAE42;"></i> For security purposes you are not allowed to send documents over the chat, you can only share SS of the issue you are facing.</div><br>
                                </div> <!-- end chat -->
                            </div>
                        </div>
                        <?php
                        }
                        else{
                            ?>
                            <form>
                                <div class="d-flex justify-content-center">
                                    <label>Dark Mode</label>
                                    <div class="form-group custom-control custom-switch">    
                                        <input type="checkbox" class="custom-control-input" id="customSwitch1" value="true">
                                        <label class="custom-control-label" for="customSwitch1">Toggle this switch to turn on/off dark mode</label>
                                    </div>
                                </div>
                                <h5 class="text-muted text-uppercase text-center">Chat Tool</h5>
                                <table class="table">
                                    <thead>
                                        <th>Firm Name</th>
                                        <th>Employee Name</th>
                                        <th>Employee Role</th>
                                        <th>Action</th>
                                    </thead>
                                    <tbody>  
                                    <?php
                                        $result = $con->query("SELECT firm_name, user.id id, user.name name, role.role_name role from user inner join firm_user_log on user.id = firm_user_log.user_id inner join firm_details on firm_user_log.firm_id = firm_details.id inner join role on user.accessLevel = role.id where role.id != 3 and role.id != 5");
                                        if($result->num_rows){
                                            while($row = $result->fetch_assoc()){
                                                ?>
                                                    <tr>
                                                        <td><?php echo $row['firm_name']; ?></td>
                                                        <td><?php echo $row['name']; ?></td>
                                                        <td><?php echo $row['role']; ?></td>
                                                        <td><a href="#" class="selectedUser" id="<?php echo $row['id']; ?>" ><span class="badge badge-success">Chat Now</span></a></td>
                                                    </tr>
                                                <?php
                                            }
                                        }
                                        else{
                                            ?>
                                            <tr>
                                                <td colspan="4">No member available for chat</td>
                                            </tr>
                                        <?php
                                        }
                                    ?>
                                    </tbody>
                                </table>
                            </form>

                            <div class="messenger">
                                <div id="live-chat">
                                    <header class="clearfix">
                                        <h4>Chat</h4>
                                        <!-- <span class="chat-message-counter">3</span> -->
                                    </header>
                                    <a href="#" class="chat-close"><i class="fas fa-times"></i></a>
                                    <div class="chat">
                                        
                                        <div class="chat-history">

                                        </div> <!-- end chat-history -->

                                        <form id="chatForm">
                                            <input type="hidden" id="user_id" name="user_id">
                                            <input type="text" placeholder="Enter Your Queries" id="chatText" name="chatText" autofocus>&nbsp;
                                            <div class="image_upload">
                                            <label for="file"><i class="far fa-2x fa-images pl-3 pt-1"></i></label>
                                            <input type="file" name="file" id="file" accept=".jpg, .png" />
                                            </div>
                                            <input class="btn btn-outline-dark ml-2" type="submit" value="Send">
                                        </form>
                                    </div> <!-- end chat -->
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                    <div class="tab-pane" id="billing">
                        <?php 
                            if($_SESSION['role'] != 1 && $_SESSION['role'] != -1){
                                $firmDetails = $con->query("select * from firm_details where id =".$_SESSION['firm_id']);
                                if($firmDetails->num_rows > 0){
                                    while($row = $firmDetails->fetch_assoc()){
                                    ?>
                                    <div class="container">
                                        <h6>BILLING SETTINGS</h6>
                                        <hr>
                                        <div class="row">
                                            <div class="col">
                                                <div class="form-group">
                                                    <label for="fullName">Subcription</label>
                                                    <form  id = "subsForm" action="razorpay/pay" method="post">
                                                        <div class="p-0">
                                                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                                            <input type="hidden" name="name" value="<?php echo $row['firm_name']; ?>">
                                                            <input type="hidden" name="address" value="<?php echo $row['firm_address']; ?>">
                                                            <input type="hidden" name="email" value="<?php echo $row['firm_email']; ?>">
                                                            <input type="hidden" id ="amount" name="amount">
                                                            <input type="hidden" name="amountCheck" value="1">
                                                            <input type="number" id="noOfWorkspace" name ="subscription" class="form-control" placeholder="Enter the number of clients" required>
                                                        </div>
                                                        <div class="mt-1">
                                                            <label><h6>&#8377;1499/Client</h6></label>
                                                        </div>
                                                        <div class="col-md-4 p-0">
                                                            <input type="submit" class="btn btn-block btn-primary text-uppercase" style="border-radius:20px;" value="Buy">
                                                        </div>
                                                       
                                                    </form>
                                                </div>
                                                <div class="form-group mb-0 p-0">
                                                    <label for="fullName">Subscribed Clients</label>
                                                    <input type="text" class="form-control" id="subscribed_clients" aria-describedby="fullNameHelp" value="<?php echo $row['subscribed_workspace']; ?>" readonly>
                                                </div>
                                                <div class="form-group mb-0 p-0">
                                                    <label for="fullName">Clients Available</label>
                                                    <input type="text" class="form-control" id="clients_available" aria-describedby="fullNameHelp" value="<?php echo $row['subscribed_workspace']-$row['used_workspace'];?>" readonly>
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="form-group mb-0">
                                                    <label for="fullName">Add Storage space</label><br>
                                                    <form id="storageForm" action="razorpay/pay" method="post">
                                                        <div class="p-0">
                                                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                                            <input type="hidden" name="name" value="<?php echo $row['firm_name']; ?>">
                                                            <input type="hidden" name="address" value="<?php echo $row['firm_address']; ?>">
                                                            <input type="hidden" name="email" value="<?php echo $row['firm_email']; ?>">
                                                            <input type="hidden" id ="storageAmount" name="storageAmount">
                                                            <input type="hidden" name="amountCheck" value="2">
                                                            <select class="form-control" id="storage" name="firmStorage" aria-label="Default select example" required>
                                                                <option value="" disabled selected>Select Storage</option>
                                                                <option value="1000000">1 GB</option>
                                                                <option value="5000000">5 GB</option>
                                                                <option value="10000000">10 GB</option>
                                                                <option value="15000000">15 GB</option>
                                                                <option value="20000000">20 GB</option>
                                                                <option value="25000000">25 GB</option>
                                                            </select>
                                                        </div>
                                                        <div class="mt-1">
                                                            <label><h6>&#8377;100/GB monthly</h6></label>
                                                        </div>
                                                        <div class="col-md-4 p-0">
                                                            <input type="submit" class="btn btn-block btn-primary text-uppercase" style="border-radius:20px;" value="Buy">
                                                        </div>
                                                    </form>
                                                </div><br>
                                                <div class="form-group mb-0 p-0">
                                                    <label for="fullName">Firm Storage space</label>
                                                    <input type="text" class="form-control" id="firm_storage_space" aria-describedby="fullNameHelp" value = "<?php echo ($row['storage']/1000000).' GB'; ?>" readonly>
                                                </div>
                                                <div class="form-group mb-0 p-0">
                                                    <label for="fullName">Storage space used</label><br>
                                                    <input type ="text" class="form-control" id="firm_storage_space_used" value="<?php echo strlen(($row['storage_used'])) <= 3 ? decimal2point((float)($row['storage_used'])).' KB':decimal2point((float)($row['storage_used']/1000)).' MB'?>" readonly>
                                                </div>
                                            </div>
                                        </div><br>
                                        <div class="form-group mb-0 p-0 text-center">
                                            <a id="paymentHistory" target="_blanc" href="paymentHistory"><button class="btn btn-primary">Payment History</button></a>
                                        </div>
                                    </div>
                                    <!-- <div class="col-md-3"> -->
                                        <!-- CARDS -->
                                        <!-- <div id="content" class="toggleContents">
                                            <div class="container pt-4">
                                                <div class="">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <h5 class="card-title">Promotion 1</h5>
                                                            <h6 class="">
                                                            <img src="Icons/Group 1.svg">
                                                            </h6>
                                                            <p class="text-count">
                                                                
                                                            </p>
                                                            <h6 class="card-subtitle mb-2">High Priority</h6>
                                                        </div>
                                                    </div>
                                                    <div class="card card-margin">
                                                        <div class="card-body">
                                                            <h5 class="card-title">Promotion 2</h5>
                                                            <h6 class="">
                                                                <img src="Icons/Group 1.svg">
                                                            </h6>
                                                            <p class="text-count">
                                                                
                                                            </p>
                                                            <h6 class="card-subtitle mb-2">Moderate Priority</h6>
                                                        </div>
                                                    </div>
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <h5 class="card-title">Promotion 3</h5>
                                                            <h6 class="">
                                                            <img src="Icons/Group 1.svg">
                                                            </h6>
                                                            <p class="text-count">
                                                                
                                                            </p>
                                                            <h6 class="card-subtitle mb-2">Low Priority</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> -->
                                    <!-- </div> -->
                        <?php 
                                    } 
                                }
                            }
                        else
                        {
                        ?>
                        <table class="table">
                            <thead>
                                <th>Firm Name</th>
                                <th>Storage Bought</th>
                                <th>Storage Used</th>
                                <th>Storage Available</th>
                            </thead>
                            <tbody>  
                                <?php
                                    $result = $con->query("SELECT * from firm_details");
                                    if($result->num_rows){
                                        while($row = $result->fetch_assoc()){
                                            $storage = decimal2point((float)($row['storage']/1000));
                                            $storage_used = decimal2point((float)($row['storage_used']/1000));
                                            ?>
                                            <tr>
                                                <td><?php echo $row['firm_name']; ?></td>
                                                <td><?php echo $storage.' MB';?></td>
                                                <td><?php echo $storage_used.' MB';?></td>
                                                <td><?php echo ($storage-$storage_used).' MB';?></td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    else{
                                        ?>
                                        <tr>
                                            <td colspan="4">No Firm Available</td>
                                        </tr>
                                    <?php
                                    }
                                ?>
                            </tbody>
                        </table>
                        <?php
                        }
                        ?>
                    </div>
                    <div class="tab-pane" id="subscription">
                        <form>
                            <div class="row">
                                <table class="table">
                                    <thead>
                                        <th>Firm Name</th>
                                        <th>Subscribed Workspace</th>
                                        <th>Used Workspace</th>
                                    </thead>
                                    <tbody>  
                                        <?php
                                            $result = $con->query("SELECT * from firm_details");
                                            if($result->num_rows){
                                                while($row = $result->fetch_assoc()){
                                                ?>
                                                    <tr>
                                                        <td><?php echo $row['firm_name']; ?></td>
                                                        <td><?php echo $row['subscribed_workspace'];?></td>
                                                        <td><?php echo $row['used_workspace'];?></td>
                                                    </tr>
                                                    <?php
                                                }
                                            }
                                            else{
                                                ?>
                                                <tr>
                                                    <td colspan="4">No Firm Available</td>
                                                </tr>
                                            <?php
                                            }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </form>
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
                    <form action="updatePhoto" method="POST" enctype="multipart/form-data" autocomplete="off">
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
                            <input class="btn btn-primary" type="submit" id="registerSubmit" value="Update">
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>
    <!-- Page level custom scripts -->
    <script src="js/custom.js"></script>
    <!-- MULTISELECT JS -->
    <script src="js/multiselect-master/dist/js/multiselect.js"></script>
    <script>
    $(document).ready(function() {
        // window.user_id_to = 0;
        $('#live-chat .chat').hide();
        // let user_id_to;

        $(".selectedUser").click(function(e){
            e.preventDefault();
            window.user_id_to = $(this).attr("id");
        })

        document.getElementsByTagName("html")[0].style.visibility = "visible";

        $('#billingTab').click(function(e){
            $('#billing').addClass('d-flex');
            $('#addMargin').addClass('m-0');
        });

        $('#profileTab, #accountTab').click(function(e){
            $('#billing').removeClass('d-flex');
            $('#addMargin').removeClass('m-0');
        });

        let darkmode = <?php echo $_SESSION['darkmode']; ?>;
        if(darkmode)
        {
            document.documentElement.classList.toggle('dark-mode');
            $("#customSwitch1").attr("checked","checked");
        }
        else{
            document.documentElement.classList.remove('dark-mode');
            $("#customSwitch1").removeAttr('checked');
        }

        $('#subsForm').submit(function(){
            let workspace = $('#noOfWorkspace').val();
            amount = workspace * 1499;
            $('#amount').val(amount);
        })

        $('#storageForm').submit(function(){
            let storage = $('#storage').val();
            storageAmount = ((storage/1000000) * 100)
            $('#storageAmount').val(storageAmount);
        })

        $("#customSwitch1").click(function(){
            var id = <?php echo $_SESSION['id']; ?>;
            if($("#customSwitch1").prop('checked') == true){
                document.documentElement.classList.toggle('dark-mode');
                $.ajax({
                    url: "darkmode.php",
                    type: "POST",
                    data: {
                        id: id,
                        active: 1
                    }
                });
            }
            else{
                document.documentElement.classList.remove('dark-mode');
                document.documentElement.classList.remove('invert-dark-mode');
                $.ajax({
                    url: "darkmode.php",
                    type: "POST",
                    data: {
                        id: id,
                        active: 0
                    }
                });
            }
        });
        $('#live-chat header, .selectedUser, #chatAssistance').on('click', function() {
            getChat();
            $('.chat').slideToggle(300, 'swing');
            $('.chat-message-counter').fadeToggle(300, 'swing');
        });

        $('#live-chat .chat-close').on('click', function(e) {
            e.preventDefault();
            $('#live-chat .chat').fadeOut(300);
        });

        $("#chatForm").submit(function(e){
            e.preventDefault();
            <?php
                if($_SESSION['role'] != 1 && $_SESSION['role'] != -1){
            ?>
                $("#user_id").val(<?php echo $con->query("select id from user where accesslevel = 1")->fetch_assoc()['id']; ?>);
            <?php
                }
                else{
                ?>
                $("#user_id").val(window.user_id_to);
            <?php
                }
            ?>
            var form = $('#chatForm')[0];
            var data = new FormData(form);
            $.ajax({
                url: "insertChatAjax.php",
                enctype: 'multipart/form-data',
                type: "POST",
                processData: false,
                contentType: false,
                cache: false,
                data: data,
                success: function(data){
                    if(data){
                        $("#chatText").val('');
                        getChat();
                    }
                }
            });
        });

        setInterval(function(){
            getChat();
        }, 5000);

        function getChat(){
            $.ajax({
                url: "getChatAjax.php",
                type: "POST",
                data: {
                    <?php
                        if($_SESSION['role'] != 1 && $_SESSION['role'] != -1){
                            echo "user_id:".$_SESSION['id'];
                        }
                        else{
                            ?>
                            user_id: window.user_id_to
                            <?php
                        }
                    ?>
                },
                success: function(data){
                    $(".chat-history").empty();
                    data = JSON.parse(data);
                    var chatHistoryStarting = "<div class='chat-message clearfix'><div class='chat-message-content clearfix'>"
                    var chatHistoryEnding = "</div></div><hr>"
                    for(let i in data){
                        $(".chat-history").append(chatHistoryStarting+"<span class='chat-time'>"+data[i][0]+"</span><h5>"+data[i][1]+"</h5><p>"+data[i][2]+"</p>"+chatHistoryEnding);
                    }
                    $(".chat-history").scrollTop($(".chat-history")[0].scrollHeight);
                }
            });
        }
    });

    </script>
</body>

</html>