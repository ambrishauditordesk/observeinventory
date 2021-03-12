 <?php
    include '../dbconnection.php';
    session_start();
    if (!isset($_SESSION['email']) && empty($_SESSION['email'])) {
        header("Location: ../login");
    }
    if (isset($_SESSION['accessLevel']) && !empty($_SESSION['accessLevel']) && $_SESSION['accessLevel'] != '1') {
        header('Location: ../login');
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
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.min.js"
        integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>

    <!-- Datatable CDN -->
    <link href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css" rel="stylesheet">
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>

    <!-- SweetAlert -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"
        integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA=="
        crossorigin="anonymous"></script>
</head>

<body style="overflow-y: scroll" oncontextmenu="return true">

    <!-- Navbar -->
    <nav class="navbar sticky-top navbar-expand-lg navbar-mainbg border-bottom">
        <!-- Topbar Navbar -->
        <ul class="navbar-nav ml-auto">
            <?php 
                if($_SESSION['role'] != 3){
                    ?>
            <li class="nav-item d-flex">
                <a class="nav-link d-flex align-items-center" href="members.php">
                <img class="nav-icon" src="../Icons/Group 4.svg"/>&nbsp;&nbsp;
                    <span>Team Members</span>
                    <!-- <i class="fas fa-users fa-1x"></i> -->
                </a>
            </li>
            <?php } 
            ?>
            <?php 
                if($_SESSION['role'] != 3){
                    ?>
            <li class="nav-item d-flex">
                <a class="nav-link d-flex align-items-center" href="#" data-toggle="modal" data-target="#addClientModal">
                    <img class="nav-icon" src="../Icons/Group 5.svg"/>&nbsp;&nbsp;
                    <span>Add Client</span>
                </a>
            </li>
            <?php } 
            ?>
            <li class="nav-item d-flex" style="background-color: rgba(232,240,255,1); border-radius: 15px;">
                <span class="nav-icon d-flex align-items-center" style="padding: 0 0 0 10px !important;">
                    <i class="fas fa-user-circle fa-2x" aria-hidden="true"></i>
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
                    <!-- <a class="dropdown-item" href="#" data-toggle="modal" data-target="#changePasswordModal">
                            <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                            Change Password
                        </a>
                    <div class="dropdown-divider"></div> -->
                    <?php 
                        if($_SESSION['role'] == '-1'){
                    ?>
                        <a class="dropdown-item" href="loginLog"><i class="fas fa-list"></i>Login Log</a>
                    <?php
                    } 
                    ?>
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
                    <div class="settings-items settingsmodal">
                        <img class="sidenav-icon" src="../Icons/settings.svg" style="width:24px !important; height:24px !important;"/> &nbsp;
                        Settings
                    </div>
                    <div class="settings-items">
                        <img class="sidenav-icon" src="../Icons/help-circle.svg" style="width:24px !important; height:24px !important;"/> &nbsp;
                        Help
                    </div>
                </div>
                <a href="../logout"><button type="button" class="btn btn-primary"><i class="fas fa-sign-out-alt"></i> Logout</button></a>
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
                                    <p class="text-count"><?php $userId = $_SESSION['id'];
                                        echo $con->query("SELECT count(id) total FROM client where active = 1 group by id")->num_rows; ?>
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
                                    <p class="text-count"><?php $userId = $_SESSION['id'];
                                        echo $con->query("select count(a.id) progress from workspace a inner join workspace_log b on a.id=b.workspace_id where b.status = '0' group by a.client_id")->num_rows;?>
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
                                    <p class="text-count"><?php $userId = $_SESSION['id'];
                                        echo $con->query("select count(id) completed from workspace where workspace.freeze = '1'")->fetch_assoc()['completed'];?>
                                    </p>
                                    <h6 class="card-subtitle mb-2">Audits</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
      
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
                                <input id="gstCheck" type="text" class="form-control" name="gst" maxlength="15" style="text-transform:uppercase" required>
                            </div>
                            <div class="form-group ">
                                <label for="name">TAN No.</label>
                                <input id="tanCheck" type="text" class="form-control" name="tan" maxlength="10" style="text-transform:uppercase" required>
                            </div>
                            <div class="form-group ">
                                <label for="name">CIN No.</label>
                                <input id="cinCheck" type="text" class="form-control" name="cin" maxlength="21" style="text-transform:uppercase" required>
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
                                <input type="text" id="gstCheckEdit" class="form-control" name="gst" maxlength="15" value= "" style="text-transform:uppercase" required>
                            </div>
                            <div class="form-group ">
                                <label for="name">TAN No.</label>
                                <input type="text" id="tanCheckEdit" class="form-control" name="tan" maxlength="10" value= "" style="text-transform:uppercase" required>
                            </div>
                            <div class="form-group ">
                                <label for="name">CIN No.</label>
                                <input type="text" id="cinCheckEdit" class="form-control" name="cin" maxlength="21" value= "" style="text-transform:uppercase" required>
                            </div>
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
                        </div>
                        <div class="modal-footer  d-flex align-items-center justify-content-center">
                            <!-- <button class="btn btn-danger" type="button" data-dismiss="modal">Cancel</button> -->
                            <input class="btn btn-warning" type="reset" value="Reset">
                            <input class="btn btn-primary" type="submit" value="Done">
                        </div>
                    </form>
                </div>
            </div>
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
            "fnRowCallback": function(nRow, aData, iDisplayIndex) {
                $("td:first", nRow).html(iDisplayIndex + 1);
                return nRow;
            },
            "drawCallback": function(settings) {
                        var pagination = $(this).closest('.dataTables_wrapper').find('.dataTables_paginate');
                        pagination.toggle(this.api().page.info().pages > 1);
                    },
            "ajax": {
                url: "clientListFetchAjax.php",
                type: "POST"
            }
        });
    }

    $(document).ready(function() {

        get_data();

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
            url: "../darkmode.php",
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
                // $("#editModal").modal('hide');
            }
        });
    });
    </script>
</body>

</html>