 <?php
    include '../dbconnection.php';
    session_start();
    
    if (isset($_SESSION['workspace_id']) && !empty($_SESSION['workspace_id'])){
        unset($_SESSION['workspace_id']);
    }

    if (!isset($_SESSION['email']) && empty($_SESSION['email'])) {
        header("Location: ../login");
    }
    
    if (isset($_SESSION['logged_in_date']) && !empty($_SESSION['logged_in_date'])){
        $currentDate = date_create(date("Y-m-d H:i:s",strtotime(date_format(date_create("now", new DateTimeZone('Asia/Kolkata')), "Y-m-d H:i:s"))));
        $loggedInDate = date_create(date("Y-m-d H:i:s",strtotime($_SESSION['logged_in_date'])));
        $diff=date_diff($currentDate,$loggedInDate);
		if($diff->format("%a") > 1 || $diff->format("%m") > 1 || $diff->format("%y") > 1){
			header('Location: ../logout');
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
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i">
    <link rel="stylesheet" href="https://cdn.datatables.net/plug-ins/1.10.24/features/searchHighlight/dataTables.searchHighlight.css">

    <!-- Custom styles for this template-->
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../css/custom.css" rel="stylesheet">
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/uiux.css" rel="stylesheet" type="text/css">

    <!-- JQuery CDN -->
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.min.js"
        integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>

    <!-- Datatable CDN -->
    <link href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css" rel="stylesheet">
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/plug-ins/1.10.24/features/searchHighlight/dataTables.searchHighlight.min.js"></script>
    <script src="https://bartaz.github.io/sandbox.js/jquery.highlight.js"></script>
    <!-- SweetAlert -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous"></script>
</head>

<body style="overflow-y: scroll" oncontextmenu="return true">

    <!-- Navbar -->
    <nav class="navbar sticky-top navbar-expand-lg navbar-mainbg border-bottom">
        <!-- Topbar Navbar -->
        <ul class="navbar-nav ml-auto">
        <?php
            if($_SESSION['role'] == 1){
                ?>
                <li class="nav-item d-flex">
                <a class="nav-link d-flex align-items-center" data-toggle="modal" data-target="#addFirmModal" href="#">
                <img class="nav-icon" style="width:30px;height:30px;" src="../Icons/plus-circle-1.svg"/>&nbsp;&nbsp;
                    <span>Add Firm</span>
                </a>
            </li>
        <?php
            }
        ?>
            <?php 
                if($_SESSION['role'] != 5){
                    ?>
            <li class="nav-item d-flex">
                <label class="d-flex justify-content-center align-items-center mt-2"><span class="helpDesign help_2">2</span></label>
                <a class="nav-link d-flex align-items-center" href="members.php">
                <img class="nav-icon" src="../Icons/Group 4.svg"/>&nbsp;&nbsp;
                    <span>Firm Members</span>
                </a>
            </li>
            <?php } 
            ?>
            <?php 
                if($_SESSION['role'] != 3 && $_SESSION['role'] != 5){
                    ?>
            <li class="nav-item d-flex">
                <label class="d-flex justify-content-center align-items-center mt-2"><span class="helpDesign help_1">1</span></label>
                <a class="nav-link d-flex align-items-center" href="#" data-toggle="modal" data-target="#addClientModal">
                    <img class="nav-icon" src="../Icons/Group 5.svg"/>&nbsp;&nbsp;
                    <span>Add Client</span>
                </a>
            </li>
            <?php } 
            ?>
            <li class="nav-item d-flex" style="background-color: rgba(232,240,255,1); border-radius: 15px;">
                <label class="d-flex justify-content-center align-items-center mt-2"><span class="helpDesign help_3">3</span></label>
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
                            <a class="dropdown-item" href="loginLog"><i class="fas fa-list"></i>Login Log</a>
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
                <img class="sidenav-icon" src="../Icons/Group -1.svg"/> &nbsp;
                Audit Edg
            </div>
        </div>
        <div class="side-footer">
            <div class="side-body">
                <div class="dash">
                    <img class="sidenav-icon" src="../Icons/pie-chart.svg" style="width:24px !important; height:24px !important;"/> &nbsp;
                    Dashboard
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
                    <label class="d-flex justify-content-center align-items-center mt-2"><span class="helpDesign help_12">12</span></label>
                </div>
            </div>
        </div>
    </div>
    <div class="mar">
        <!-- HEADER -->
        <!-- <div id="header">
            <div class="container-fluid border-top" stickylevel="0" style="z-index:1200;">


                <div class="row pt-1">
                    <div class="col-md-4">
                        <img class="float-left" src="../vendor/img/audit-edge-logo.svg" style="height:45px;">
                        <div class="ml-2 font-1 h3 py-1 d-inline-block float-left"></div>
                    </div>
                    <div class="col-md-4 text-center font-2">
                        <h3 style="padding-top: 5px;"><?php //echo strtoupper($_SESSION['name'] . " Clients"); ?> PROJECT STATISTICS</h3>
                    </div>
                </div>
            </div>
        </div> -->
    
        <div class="row" style="padding: 0 0 0 2% !important; width: 100% !important; height: auto !important; margin: 0 !important;">
            <div class="col-md-10" style="width: 100% !important;"> 
                <!-- DATATABLE -->
                <div class="container pt-4">
                    <div class="row">
                        <div class="card-body" style="width:10px; height:100% !important; border-radius: 12px; background-color: white;">
                        <!-- UI PArt in custom.css -->
                        <span class="client-list"> Client List </span> <hr>
                            <div class="table-responsive">
                                <div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <table id="clientListTable">
                                                <thead class="dark-invert">
                                                    <tr class="ro">
                                                        <th scope="col">Sl</th>
                                                        <th scope="col">Client Name</th>
                                                        <th scope="col">Client Profile</th>
                                                        <th scope="col">Type</th>
                                                        <th scope="col">Created On</th>
                                                        <th scope="col">Status</th>
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
            </div>
            <div class="col-md-2">
                <!-- CARDS -->
                <div id="content" class="toggleContents">
                    <div class="container pt-4">
                        <div class="">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Total Assigned</h5>
                                    <h6 class="">
                                    <img src="../Icons/Group 3.svg">
                                    </h6>
                                    <label class="d-flex justify-content-center align-items-center mt-2"><span class="helpDesign help_4">4</span></label>
                                    <p class="text-count">
                                        <?php 
                                            $userId = $_SESSION['id'];
                                            if($_SESSION['role'] != 1 && $_SESSION['role'] != -1){
                                                $assigned = $con->query("select count(client.id) assigned FROM client inner join user_client_log on user_client_log.client_id=client.id where user_client_log.user_id= $userId and active = 1")->fetch_assoc()['assigned'];
                                            }
                                            else{
                                                $assigned = $con->query("select count(client.id) assigned FROM client where  active = 1")->fetch_assoc()['assigned'];
                                            }
                                            echo $assigned == ''? '0':$assigned;
                                        ?>
                                    </p>
                                    <h6 class="card-subtitle mb-2">Audits</h6>
                                </div>
                            </div>
                            <div class="card card-margin">
                                <div class="card-body">
                                    <h5 class="card-title">In Progress</h5>
                                    <h6 class="">
                                        <img src="../Icons/Group 2.svg">
                                    </h6>
                                    <label class="d-flex justify-content-center align-items-center mt-2"><span class="helpDesign help_5">5</span></label>
                                    <p class="text-count">
                                        <?php $userId = $_SESSION['id'];
                                            if($_SESSION['role'] != 1 && $_SESSION['role'] != -1){
                                                $progress = $con->query("select sum( if( (select count(freeze) from workspace where workspace.client_id = client.id ) <> ( select count(freeze) from workspace where workspace.client_id = client.id and freeze = 1), 1, 0)) progress FROM client inner join user_client_log on user_client_log.client_id=client.id where user_client_log.user_id=$userId and active = 1")->fetch_assoc()['progress'];
                                            }
                                            else{
                                                $progress = $con->query("select sum( if( (select count(freeze) from workspace where workspace.client_id = client.id ) <> ( select count(freeze) from workspace where workspace.client_id = client.id and freeze = 1), 1, 0)) progress FROM client where active = 1")->fetch_assoc()['progress'];
                                            }
                                            echo $progress == ''? '0':$progress;
                                        ?>
                                    </p>
                                    <h6 class="card-subtitle mb-2">Audits</h6>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title success">Completed</h5>
                                    <h6 class="">
                                    <img src="../Icons/Group 1.svg">
                                    </h6>
                                    <label class="d-flex justify-content-center align-items-center mt-2"><span class="helpDesign help_6">6</span></label>
                                    <p class="text-count">
                                        <?php $userId = $_SESSION['id'];
                                            if($_SESSION['role'] != 1 && $_SESSION['role'] != -1){
                                                $completed = $con->query("select sum( if( (select count(freeze) from workspace where workspace.client_id = client.id ) = ( select count(freeze) from workspace where workspace.client_id = client.id and freeze = 1), 1, 0)) completed FROM client inner join user_client_log on client.id=user_client_log.client_id where user_client_log.user_id = $userId and active = 1")->fetch_assoc()['completed'];   
                                            }
                                            else{
                                                $completed = $con->query("select sum( if( (select count(freeze) from workspace where workspace.client_id = client.id ) = ( select count(freeze) from workspace where workspace.client_id = client.id and freeze = 1), 1, 0)) completed FROM client where active = 1")->fetch_assoc()['completed'];
                                            }
                                            echo $completed == ''? '0':$completed;
                                        ?>
                                    </p>
                                    <h6 class="card-subtitle mb-2">Audits</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
      <?php
        if($_SESSION['role'] != 3 || $_SESSION['role'] != 5){
            ?>

        <!--Add Client Form -->
        <div class="modal fade" id="addClientModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="addClient" method="post" id="addClientForm" enctype="multipart/form-data"
                        autocomplete="off">
                        <div class="modal-body">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Basic Details<h5>
                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <div class="form-group ">
                                <label for="name">Client Name</label>
                                <input type="text" class="form-control" maxlength="99" name="clientname" required>
                            </div>
                            <?php
                            if($_SESSION['role'] == 1 || $_SESSION['role'] == -1){
                                ?>
                                <div class="form-group ">
                                    <label for="country">Firm </label>
                                    <select class="form-control" name="firm_id" required>
                                        <option>Select a Firm !</option>
                                            <?php
                                                $consQuery = $con->query("select id,firm_name from firm_details");
                                                while ($consResult = $consQuery->fetch_assoc()) {
                                            ?>
                                        <option value="<?php echo $consResult['id']; ?>">
                                            <?php echo $consResult['firm_name']; ?></option>
                                            <?php
                                                }
                                            ?>
                                    </select>
                                </div>
                            <?php
                                }
                            ?>
                            <div class="form-group ">
                                <label for="name">Nick Name</label>
                                <input type="text" class="form-control" maxlength="99" name="nickname">
                            </div>
                            <div class="form-group ">
                                <label for="name">Date of Incorporation/ Birth</label>
                                <input type="date" class="form-control" name="dob" required>
                            </div>
                            <div class="form-group ">
                                <label for="country">Constitution</label>
                                <select class="form-control" name="constitution" required>
                                    <option>Select Constitution !</option>
                                        <?php
                                            $consQuery = $con->query("select * from constitution");
                                            while ($consResult = $consQuery->fetch_assoc()) {
                                        ?>
                                    <option value="<?php echo $consResult['id']; ?>">
                                        <?php echo $consResult['const']; ?></option>
                                        <?php
                                            }
                                        ?>
                                </select>
                            </div>
                            <div class="form-group ">
                                <label for="country">Industry</label>
                                <select class="form-control" name="industry" required>
                                    <option>Select Industry !</option>
                                    <?php
                                                    $indusQuery = $con->query("select * from industry");
                                                    while ($indusResult = $indusQuery->fetch_assoc()) {
                                                ?>
                                    <option value="<?php echo $indusResult['id']; ?>">
                                        <?php echo $indusResult['industry']; ?></option>
                                    <?php
                                                }
                                                ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="name">Address</label>
                                <input type="text" class="form-control" name="add" maxlength="200" required>
                            </div>
                            <div class="form-group">
                                <label for="country">Country</label>
                                <input type="text" class="form-control" name="country" maxlength="15" required>
                            </div>
                            <div class="form-group" id="stateEntryIdDiv">
                                <label for="state">State</label>
                                <input type="text" class="form-control" name="state" maxlength="15" required>
                            </div>
                            <div class="form-group" id="citiesEntryIdDiv">
                                <label for="city">City</label>
                                <input type="text" class="form-control" name="city" maxlength="15" required>
                            </div>
                            <div class="form-group ">
                                <label for="name">Pincode</label>
                                <input type="text" class="form-control" name="pincode" maxlength="7" required>
                            </div>
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Satuatory Information<h5>
                            </div>
                            <div class="form-group ">
                                <label for="name">Pan No.</label>
                                <input id="panCheck" type="text" class="form-control" name="pan" maxlength="10" style="text-transform:uppercase" required>
                            </div>
                            <div class="form-group ">
                                <label for="name">GST No.</label>
                                <input id="gstCheck" type="text" class="form-control" name="gst" maxlength="15" style="text-transform:uppercase">
                            </div>
                            <div class="form-group ">
                                <label for="name">TAN No.</label>
                                <input id="tanCheck" type="text" class="form-control" name="tan" maxlength="10" style="text-transform:uppercase">
                            </div>
                            <div class="form-group ">
                                <label for="name">CIN No.</label>
                                <input id="cinCheck" type="text" class="form-control" name="cin" maxlength="21" style="text-transform:uppercase">
                            </div>
                            <div class="form-group ">
                                <label for="name">Client Contact</label>
                            </div>
                            <!-- <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Contact Person<h5>
                            </div> -->
                            <div class="row">
                                <div class="col">
                                    <table class="table table-bordered table-hover" id="tab_logic" style="border-collapse: inherit !important;">
                                        <thead>
                                            <tr>
                                                <th class="text-center shadow-remove" style=""> Name</th>
                                                <th class="text-center shadow-remove"> Email</th>
                                                <th class="text-center shadow-remove"> Password</th>
                                                <th class="text-center shadow-remove" style=""> Designation</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr id='addr0'>
                                                <td><input type="text" class="form-control" name="cname[]" maxlength="50" required>
                                                </td>
                                                <td><input type="email" class="form-control" name="email[]" maxlength="50" required></td>
                                                <td><input type="password" class="form-control" name="pass[]" maxlength="50" required>
                                                </td>
                                                <td><input type="text" name='designation[]' class="form-control" maxlength="50" required />
                                                </td>
                                            </tr>
                                            <tr id='addr1'></tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <br>
                            <hr>
                            <div class="row">
                                <div class="col d-flex justify-content-between">
                                    <a href="#" id="add_row" class="btn btn-outline-primary pull-left">Add
                                        Row</a>
                                    <a href="#" id='delete_row' class="btn btn-outline-danger">Delete
                                        Row</a>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-danger" type="button" data-dismiss="modal">Cancel</button>
                            <input class="btn btn-warning" type="reset" value="Reset">
                            <input class="btn btn-primary" type="submit" value="Done">
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!--Edit ClientProfile Modal -->
        <div class="modal fade" id="editClientProfile" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel" style="font-weight: 800 !important;">Edit Client Profile<h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <form action="editClientProfile.php" method="post">
                        <div class="modal-body">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Basic Details<h5>
                            </div>
                            <input type="hidden" id="date" class="form-control" name="date" value= "" required>
                            <input type="hidden" id="id" class="form-control" name="id" value= "" required>
                            <input type="hidden" id="cid" class="form-control" name="cid" value= "" required>
                            <input type="hidden" id="active" class="form-control" name="active" value= "" required>
                            <div class="form-group ">
                                <label for="name">Client Name</label>
                                <input type="text" id="clientname" class="form-control" maxlength="99" name="clientname" value= "" required>
                            </div>
                            <div class="form-group ">
                                <label for="name">Nick Name</label>
                                <input type="text" id="nickname" class="form-control" maxlength="99" name="nickname" value= "">
                            </div>
                            <div class="form-group ">
                                <label for="name">Date of Incorporation/ Birth</label>
                                <input type="date" id="dob" class="form-control" name="dob" value= "" required>
                            </div>
                            <div class="form-group ">
                                <label for="country">Constitution</label>
                                <select class="form-control" id="constitution" name="constitution" required>
                                    <option>Select Constitution !</option>
                                        <?php
                                            $consQuery = $con->query("select * from constitution");
                                            while ($consResult = $consQuery->fetch_assoc()) {
                                        ?>
                                    <option value="<?php echo $consResult['id']; ?>"><?php echo $consResult['const']; ?></option>
                                        <?php
                                            }
                                        ?>
                                </select>
                            </div>
                            <div class="form-group ">
                                <label for="country">Industry</label>
                                <select class="form-control" id="industry" name="industry" required>
                                    <option>Select Industry !</option>
                                    <?php
                                                    $indusQuery = $con->query("select * from industry");
                                                    while ($indusResult = $indusQuery->fetch_assoc()) {
                                                ?>
                                    <option value="<?php echo $indusResult['id']; ?>"><?php echo $indusResult['industry']; ?></option>
                                    <?php
                                                }
                                                ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="name">Address</label>
                                <input type="text" id="add" class="form-control" name="add" maxlength="200" value= "">
                            </div>
                            <div class="form-group">
                                <label for="country">Country</label>
                                <input type="text" id="country" class="form-control" name="country" maxlength="15" value= "">
                            </div>
                            <div class="form-group">
                                <label for="state">State</label>
                                <input type="text" id="state" class="form-control" name="state" maxlength="15" value= "">
                            </div>
                            <div class="form-group">
                                <label for="city">City</label>
                                <input type="text" id="city" class="form-control" name="city" maxlength="15" value= "">
                            </div>
                            <div class="form-group ">
                                <label for="name">Pincode</label>
                                <input type="text" id="pincode" class="form-control" name="pincode" maxlength="8" value= "" required>
                            </div>
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Satuatory Information<h5>
                            </div>
                            <div class="form-group ">
                                <label for="name">Pan No.</label>
                                <input type="text" id="panCheckEdit" class="form-control" name="pan" maxlength="10" value= "" style="text-transform:uppercase" required>
                            </div>
                            <div class="form-group ">
                                <label for="name">GST No.</label>
                                <input type="text" id="gstCheckEdit" class="form-control" name="gst" maxlength="15" value= "" style="text-transform:uppercase">
                            </div>
                            <div class="form-group ">
                                <label for="name">TAN No.</label>
                                <input type="text" id="tanCheckEdit" class="form-control" name="tan" maxlength="10" value= "" style="text-transform:uppercase">
                            </div>
                            <div class="form-group ">
                                <label for="name">CIN No.</label>
                                <input type="text" id="cinCheckEdit" class="form-control" name="cin" maxlength="21" value= "" style="text-transform:uppercase">
                            </div>
                            <?php
                                if($_SESSION['role'] < 3)
                                {
                            ?>
                            <div class="form-group ">
                                <label for="name">Status</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="active" id="cactive0" value="0">
                                <label class="form-check-label" for="exampleRadios1">
                                    Inactive
                                </label> &nbsp; &nbsp; &nbsp; &nbsp;
                                <input class="form-check-input" type="radio" name="active" id="cactive1" value="1">
                                <label class="form-check-label" for="exampleRadios2" name="active">
                                    Active
                                </label>
                            </div>
                            <?php
                                }
                            ?>
                        </div>
                        <div class="modal-footer  d-flex align-items-center justify-content-center">
                            <!-- <button class="btn btn-danger" type="button" data-dismiss="modal">Cancel</button> -->
                            <?php
                                if($_SESSION['role'] < 3)
                                {
                            ?>
                            <input class="btn btn-warning" type="reset" value="Reset">
                            <input class="btn btn-primary" type="submit" value="Done">
                            <?php 
                                }
                            ?>
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
                            <p>1. Add Client icon helps you add client to your client list.</p>
                            <p>To add a client enter all the required client details and you are required to enter 1 client contact minimum. You can add more than one client contact by using add row section on bottom left section</p>
                            <p>Client list is identified with a unique PAN, GST, or TAN number. If you are having an error in saving the client information, please ensure that the PAN, GST, or TAX number is unique for each client.</p>
                            <p>If you wish to add a clients with similar PAN, GST or TAN number please contact firm administrator.</p>
                            <p>Once done click on “Done” to save. </p>
                            <p>If you wish to change or erase all the information, click on reset.</p>
                        </div>
                        <div id="help_2">
                            <p>2. Team members button helps you add you add your team members and allocation clients to each specific member.</p>
                        </div>
                        <div id="help_3">
                            <p>3. Profile: User profile reflects brief details about the user and can be edits by firm administrator.</p>
                        </div>
                        <div id="help_4">
                            <p>4. Total Assigned – Reflects the count of engagements assigned to you included InProgress and completed engagement.</p>
                        </div>
                        <div id="help_5">
                            <p>5. In progress – Reflects the count of current engagement you are assigned to and currently in actively in edit and in progress. Reach out to firm administrator to get your self allocated to any specific engagement.</p> 
                        </div>
                        <div id="help_6">
                            <p>6. Completed – Reflects the count of engagements feezed and not available for edits. Reach out to firm administrator to unlock any freezed engagement.</p>
                        </div>
                        <div id="help_7">
                            <p>7. Settings – Your Settings are personalized based on your role in your firm and can be accessed at all times for chat, email and reaching out to a specialist for any help.</p>
                        </div>
                        <div id="help_8">
                            <p>8. Client profile – Once a client is added , you can edit information or update client profile using this feature.</p>
                        </div>
                        <div id="help_9">
                            <p>9. Client Contacts – You can add client contacts to give them access to see your client document list. All the members on the client contacts can only view your clint request list.</p>
                        </div>
                        <div id="help_10">
                            <p>10. Status – Active status reflect that the engagement is still in progress and not been freezed. Inactive status means the engagements is no longer available for edits and you should reach out to a firm administrator for further help.</p>
                        </div>
                        <div id="help_11">
                            <p>11. Client name – Click on your designed client name to access client workspace.</p>
                        </div>
                        <div id="help_12">
                            <p>12. Log out- Simply use this button to log out of your firm workspace and client list.</p>
                        </div>
                        <i id="left-arrow" class="fas fa-arrow-left"></i>
                        <i id="right-arrow" class="fas fa-arrow-right"></i>
                    </div>
                </div>
            </div>
        </div>

         <!-- Add Firm Modal -->
         <div class="modal fade" id="addFirmModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-size" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Firm Details</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                    </div>
                    <form action="addFirm" method="POST" autocomplete="off">
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="name">Firm Name</label>
                                <input type="text" class="form-control" name="firmName">
                            </div>
                            <div class="form-group">
                                <label for="name">Firm Address</label>
                                <textarea class="form-control" name="firmAdd"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="name">Firm Email</label>
                                <input type="email" class="form-control" name="firmEmail">
                            </div>
                            <!-- <div class="form-group ">
                                <label for="name">Upload Photo</label>
                                <input type="file" class="form-control" name="image" accept="image/x-png,image/gif,image/jpeg,image/jpg" required>
                            </div> -->
                        <div> 
                        <div class="modal-footer justify-content-center">
                            <button class="btn btn-danger" type="button" data-dismiss="modal">Cancel</button>
                            <input class="btn btn-primary" type="submit" id="addFirm" value="Add">
                        </div>
                    </form>
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
                        <div> 
                        <div class="modal-footer justify-content-center">
                            <button class="btn btn-danger" type="button" data-dismiss="modal">Cancel</button>
                            <input class="btn btn-primary" type="submit" id="registerSubmit" value="Update">
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
    <script>
    function get_data() {
        var dataTable = $('#clientListTable').DataTable({
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
                var pagination = $(this).closest('.dataTables_wrapper').find('.dataTables_paginate');
                pagination.toggle(this.api().page.info().pages > 1);
                $(".helpDesign, #helpDescription").hide();
            },
            "columnDefs": [
                { orderable: false, targets: -6 },
                { orderable: false, targets: -4 }
            ],
            "ajax": {
                url: "clientListFetchAjax.php",
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

    $(document).ready(function() {

        document.getElementsByTagName("html")[0].style.visibility = "visible";

        get_data();

        $("#helpDescription > div > div > .close").click(function(e){
            $(".helpDesign, #helpDescription").toggle();
        });

        $(".helpDesign, #helpDescription").hide();

        $("#helpButton").click(function(e){
            $(".helpDesign, #helpDescription").toggle();
            $("#help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8, #help_9, #help_10, #help_11, #help_12").hide();
            $("#help_1").show();
            $(".help_1").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
            $(".help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8, .help_9, .help_10, .help_11, .help_12").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
        });

        $(".help_1").click(function(e){
            $(".help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8, .help_9, .help_10, .help_11, .help_12").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
            $(".help_1").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
            $("#help_1").show();
            $("#help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8, #help_9, #help_10, #help_11, #help_12").hide();
        });

        $(".help_2").click(function(e){
            $(".help_1, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8, .help_9, .help_10, .help_11, .help_12").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
            $(".help_2").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
            $("#help_2").show();
            $("#help_1, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8, #help_9, #help_10, #help_11, #help_12").hide();
        });

        $(".help_3").click(function(e){
            $(".help_1, .help_2, .help_4, .help_5, .help_6, .help_7, .help_8, .help_9, .help_10, .help_11, .help_12").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
            $(".help_3").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
            $("#help_3").show();
            $("#help_1, #help_2, #help_4, #help_5, #help_6, #help_7, #help_8, #help_9, #help_10, #help_11, #help_12").hide();
        });

        $(".help_4").click(function(e){
            $(".help_1, .help_2, .help_3, .help_5, .help_6, .help_7, .help_8, .help_9, .help_10, .help_11, .help_12").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
            $(".help_4").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
            $("#help_4").show();
            $("#help_1, #help_2, #help_3, #help_5, #help_6, #help_7, #help_8, #help_9, #help_10, #help_11, #help_12").hide();
        });

        $(".help_5").click(function(e){
            $(".help_1, .help_2, .help_3, .help_4, .help_6, .help_7, .help_8, .help_9, .help_10, .help_11, .help_12").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
            $(".help_5").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
            $("#help_5").show();
            $("#help_1, #help_2, #help_3, #help_4, #help_6, #help_7, #help_8, #help_9, #help_10, #help_11, #help_12").hide();
        });
        
        $(".help_6").click(function(e){
            $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_7, .help_8, .help_9, .help_10, .help_11, .help_12").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
            $(".help_6").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
            $("#help_6").show();
            $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_7, #help_8, #help_9, #help_10, #help_11, #help_12").hide();
        });

        $(".help_7").click(function(e){
            $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6, .help_8, .help_9, .help_10, .help_11, .help_12").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
            $(".help_7").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
            $("#help_7").show();
            $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6, #help_8, #help_9, #help_10, #help_11, #help_12").hide();
        });

        $(".help_8").click(function(e){
            $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_9, .help_10, .help_11, .help_12").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
            $(".help_8").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
            $("#help_8").show();
            $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_9, #help_10, #help_11, #help_12").hide();
        });

        $(".help_9").click(function(e){
            $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8, .help_10, .help_11, .help_12").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
            $(".help_9").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
            $("#help_9").show();
            $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8, #help_10, #help_11, #help_12").hide();
        });

        $(".help_10").click(function(e){
            $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8, .help_9, .help_11, .help_12").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
            $(".help_10").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
            $("#help_10").show();
            $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8, #help_9, #help_11, #help_12").hide();
        });

        $(".help_11").click(function(e){
            $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8, .help_9, .help_10, .help_12").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
            $(".help_11").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
            $("#help_11").show();
            $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8, #help_9, #help_10, #help_12").hide();
        });

        $(".help_12").click(function(e){
            $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8, .help_9, .help_10, .help_11").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
            $(".help_12").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
            $("#help_12").show();
            $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8, #help_9, #help_10, #help_11").hide();
        });

        $("#right-arrow").click(function(e){
            if($(".help_1").hasClass("helpDesignSelected")){
                $(".help_1, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8, .help_9, .help_10, .help_11, .help_12").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                $(".help_2").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                $("#help_2").show();
                $("#help_1, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8, #help_9, #help_10, #help_11, #help_12").hide();
            }
            else if($(".help_2").hasClass("helpDesignSelected")){
                $(".help_1, .help_2, .help_4, .help_5, .help_6, .help_7, .help_8, .help_9, .help_10, .help_11, .help_12").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                $(".help_3").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                $("#help_3").show();
                $("#help_1, #help_2, #help_4, #help_5, #help_6, #help_7, #help_8, #help_9, #help_10, #help_11, #help_12").hide();
            }
            else if($(".help_3").hasClass("helpDesignSelected")){
                $(".help_1, .help_2, .help_3, .help_5, .help_6, .help_7, .help_8, .help_9, .help_10, .help_11, .help_12").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                $(".help_4").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                $("#help_4").show();
                $("#help_1, #help_2, #help_3, #help_5, #help_6, #help_7, #help_8, #help_9, #help_10, #help_11, #help_12").hide();
            }
            else if($(".help_4").hasClass("helpDesignSelected")){
                $(".help_1, .help_2, .help_3, .help_4, .help_6, .help_7, .help_8, .help_9, .help_10, .help_11, .help_12").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                $(".help_5").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                $("#help_5").show();
                $("#help_1, #help_2, #help_3, #help_4, #help_6, #help_7, #help_8, #help_9, #help_10, #help_11, #help_12").hide();
            }
            else if($(".help_5").hasClass("helpDesignSelected")){
                $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_7, .help_8, .help_9, .help_10, .help_11, .help_12").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                $(".help_6").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                $("#help_6").show();
                $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_7, #help_8, #help_9, #help_10, #help_11, #help_12").hide();
            }
            else if($(".help_6").hasClass("helpDesignSelected")){
                $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6, .help_8, .help_9, .help_10, .help_11, .help_12").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                $(".help_7").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                $("#help_7").show();
                $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6, #help_8, #help_9, #help_10, #help_11, #help_12").hide();
            }
            else if($(".help_7").hasClass("helpDesignSelected")){
                $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_9, .help_10, .help_11, .help_12").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                $(".help_8").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                $("#help_8").show();
                $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_9, #help_10, #help_11, #help_12").hide();
            }
            else if($(".help_8").hasClass("helpDesignSelected")){
                $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8, .help_10, .help_11, .help_12").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                $(".help_9").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                $("#help_9").show();
                $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8, #help_10, #help_11, #help_12").hide();
            }
            else if($(".help_9").hasClass("helpDesignSelected")){
                $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8, .help_9, .help_11, .help_12").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                $(".help_10").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                $("#help_10").show();
                $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8, #help_9, #help_11, #help_12").hide();
            }
            else if($(".help_10").hasClass("helpDesignSelected")){
                $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8, .help_9, .help_10, .help_12").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                $(".help_11").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                $("#help_11").show();
                $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8, #help_9, #help_10, #help_12").hide();
            }
            else if($(".help_11").hasClass("helpDesignSelected")){
                $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8, .help_9, .help_10, .help_11").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                $(".help_12").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                $("#help_12").show();
                $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8, #help_9, #help_10, #help_11").hide();
            }
            else if($(".help_12").hasClass("helpDesignSelected")){
                $(".help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8, .help_9, .help_10, .help_11, .help_12").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                $(".help_1").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                $("#help_1").show();
                $("#help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8, #help_9, #help_10, #help_11, #help_12").hide();
            }
        });

        $("#left-arrow").click(function(e){
            if($(".help_1").hasClass("helpDesignSelected")){
                $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8, .help_9, .help_10, .help_11").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                $(".help_12").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                $("#help_12").show();
                $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8, #help_9, #help_10, #help_11").hide();
            }
            else if($(".help_2").hasClass("helpDesignSelected")){
                $(".help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8, .help_9, .help_10, .help_11, .help_12").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                $(".help_1").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                $("#help_1").show();
                $("#help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8, #help_9, #help_10, #help_11, #help_12").hide();
            }
            else if($(".help_3").hasClass("helpDesignSelected")){
                $(".help_1, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8, .help_9, .help_10, .help_11, .help_12").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                $(".help_2").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                $("#help_2").show();
                $("#help_1, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8, #help_9, #help_10, #help_11, #help_12").hide();
            }
            else if($(".help_4").hasClass("helpDesignSelected")){
                $(".help_1, .help_2, .help_4, .help_5, .help_6, .help_7, .help_8, .help_9, .help_10, .help_11, .help_12").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                $(".help_3").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                $("#help_3").show();
                $("#help_1, #help_2, #help_4, #help_5, #help_6, #help_7, #help_8, #help_9, #help_10, #help_11, #help_12").hide();
            }
            else if($(".help_5").hasClass("helpDesignSelected")){
                $(".help_1, .help_2, .help_3, .help_5, .help_6, .help_7, .help_8, .help_9, .help_10, .help_11, .help_12").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                $(".help_4").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                $("#help_4").show();
                $("#help_1, #help_2, #help_3, #help_5, #help_6, #help_7, #help_8, #help_9, #help_10, #help_11, #help_12").hide();
            }
            else if($(".help_6").hasClass("helpDesignSelected")){
                $(".help_1, .help_2, .help_3, .help_4, .help_6, .help_7, .help_8, .help_9, .help_10, .help_11, .help_12").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                $(".help_5").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                $("#help_5").show();
                $("#help_1, #help_2, #help_3, #help_4, #help_6, #help_7, #help_8, #help_9, #help_10, #help_11, #help_12").hide();
            }
            else if($(".help_7").hasClass("helpDesignSelected")){
                $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_7, .help_8, .help_9, .help_10, .help_11, .help_12").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                $(".help_6").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                $("#help_6").show();
                $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_7, #help_8, #help_9, #help_10, #help_11, #help_12").hide();
            }
            else if($(".help_8").hasClass("helpDesignSelected")){
                $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6, .help_8, .help_9, .help_10, .help_11, .help_12").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                $(".help_7").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                $("#help_7").show();
                $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6, #help_8, #help_9, #help_10, #help_11, #help_12").hide();
            }
            else if($(".help_9").hasClass("helpDesignSelected")){
                $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_9, .help_10, .help_11, .help_12").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                $(".help_8").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                $("#help_8").show();
                $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_9, #help_10, #help_11, #help_12").hide();
            }
            else if($(".help_10").hasClass("helpDesignSelected")){
                $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8, .help_10, .help_11, .help_12").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                $(".help_9").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                $("#help_9").show();
                $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8, #help_10, #help_11, #help_12").hide();
            }
            else if($(".help_11").hasClass("helpDesignSelected")){
                $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8, .help_9, .help_11, .help_12").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                $(".help_10").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                $("#help_10").show();
                $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8, #help_9, #help_11, #help_12").hide();
            }
            else if($(".help_12").hasClass("helpDesignSelected")){
                $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8, .help_9, .help_10, .help_12").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                $(".help_11").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                $("#help_11").show();
                $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8, #help_9, #help_10, #help_12").hide();
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

        let darkmode = <?php echo $_SESSION['darkmode']; ?>;
        if(darkmode)
        {
            document.documentElement.classList.toggle('dark-mode');
            
        }
        else if(!darkmode){
            document.documentElement.classList.remove('dark-mode');
        }
    });

    let panRegex = /[A-Z]{5}\d{4}[A-Z]{1}/;
    $(document).on('keyup','#panCheckEdit', function(){
        if ($(this).val().length == 10){
            if (!panRegex.test($(this).val().toUpperCase())){
                swal({
                        icon: "error",
                        text: "PAN Number invalid!",
                    }).then(function(isConfirm) {
                        $('#panCheckEdit').val('');
                    });
            }
        }
    });
    $('#panCheck').val('').on('keyup', function () {
        if ($('#panCheck').val().length == 10){
            if (!panRegex.test($('#panCheck').val().toUpperCase())){
                swal({
                        icon: "error",
                        text: "PAN Number invalid!",
                    }).then(function(isConfirm) {
                        $('#panCheck').val('');
                    });
            }
        }
    });

    let gstRegex = /\d{2}[A-Z]{5}\d{4}[A-Z]{1}[A-Z\d]{1}[Z]{1}[A-Z\d]{1}/;
    $(document).on('keyup','#gstCheckEdit', function(){
        if ($(this).val().length === 15){
            if ($(this).val().toUpperCase()){
                swal({
                        icon: "error",
                        text: "GST Number invalid!",
                    }).then(function(isConfirm) {
                        $('#gstCheckEdit').val('');
                    });
            }
        }
    })
    $('#gstCheck').val('').on('keyup', function () {
        if ($('#gstCheck').val().length === 15){
            if (!gstRegex.test($('#gstCheck').val().toUpperCase())){
                swal({
                        icon: "error",
                        text: "GST Number invalid!",
                    }).then(function(isConfirm) {
                        $('#gstCheck').val('');
                    });
            }
        }
    });

    let tanRegex = /[A-Z]{4}\d{5}[A-Z]{1}/;
    $(document).on('keyup','#tanCheckEdit', function(){
        if ($(this).val().length == 10){
            if (!tanRegex.test($(this).val().toUpperCase())){
                swal({
                        icon: "error",
                        text: "TAN Number invalid!",
                    }).then(function(isConfirm) {
                        $("#tanCheckEdit").val('');
                    });
            }
        }
    })
    $('#tanCheck').val('').on('keyup', function () {
        if ($('#tanCheck').val().length == 10){
            if (!tanRegex.test($('#tanCheck').val().toUpperCase())){
                swal({
                        icon: "error",
                        text: "TAN Number invalid!",
                    }).then(function(isConfirm) {
                        $('#tanCheck').val('');
                    });
            }
        }
    });
    
    let cinRegex = /[L|U]{1}\d{5}[A-Z]{2}\d{4}[A-Z]{3}\d{6}/;
    $(document).on('keyup','#cinCheckEdit', function(){
        if ($(this).val().length == 21){
            if (!cinRegex.test($(this).val().toUpperCase())){
                swal({
                        icon: "error",
                        text: "CIN Number invalid!",
                    }).then(function(isConfirm) {
                        $('#cinCheckEdit').val('');
                    });
            }
        }
    })
    $('#cinCheck').val('').on('keyup', function () {
        if ($('#cinCheck').val().length == 21){
            if (!cinRegex.test($('#cinCheck').val().toUpperCase())){
                swal({
                        icon: "error",
                        text: "CIN Number invalid!",
                    }).then(function(isConfirm) {
                        $('#cinCheck').val('');
                    });
            }
        }
    });

    $(document).on('click', '#done', function(e) {
        e.preventDefault();
        var id = $("#cid").val();
        var name = $("#cname").val();
        // var active = $(".cative").val();
        var active = $('input[name="active"]:checked').val();
        $.ajax({
            url: "editAClient.php",
            type: "POST",
            data: {
                id: id,
                name: name,
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
                $("#editModal").modal('hide');
            }
        });
    });

    $(document).on('click', '.editClientProfile', function() {
        var id = $(this).attr("id");
        // $("#editModal #active > option:selected").removeAttr('selected');
        $.ajax({
            url: "clientProfileFetchAjax.php",
            type: "POST",
            data: {
                id: id
            },
            success: function(data) {
                obj = JSON.parse(data);
                console.log(obj);
                $("#editClientProfile #date").val(obj.added_by_date);
                $("#editClientProfile #id").val(obj.added_by_id);
                $("#editClientProfile #cid").val(obj.id);
                $("#editClientProfile #active").val(obj.active);
                $("#editClientProfile #clientname").val(obj.name);
                $("#editClientProfile #nickname").val(obj.nickname);
                $("#editClientProfile #dob").val(obj.incorp_date);
                $("#editClientProfile #add").val(obj.address);
                $("#editClientProfile #country").val(obj.country);
                $("#editClientProfile #state").val(obj.state);
                $("#editClientProfile #city").val(obj.city);
                $("#editClientProfile #pincode").val(obj.pincode);
                $("#editClientProfile #panCheckEdit").val(obj.pan);
                $("#editClientProfile #gstCheckEdit").val(obj.gst);
                $("#editClientProfile #tanCheckEdit").val(obj.tan);
                $("#editClientProfile #cinCheckEdit").val(obj.cin);
                $("#editClientProfile #constitution option[value=" + obj.const_id + "]").attr('selected','selected');
                $("#editClientProfile #industry option[value=" + obj.industry_id + "]").attr('selected','selected');
                if(obj.active == 1)
                $("#editClientProfile #cactive1").attr('checked','checked');
                else
                $("#editClientProfile #cactive0").attr('checked','checked');
                $("#editClientProfile").modal('show');
            }
        });
    });

    </script>
</body>

</html>