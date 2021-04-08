<?php
    include 'dbconnection.php';
    session_start();
    if (!isset($_SESSION['email']) && empty($_SESSION['email'])) {
        header("Location: login");
    }
    // if(isset($_GET['wid']) && !empty($_GET['wid']))
    //     $wid = $_GET['wid'];
    // $clientId= $_GET['cid'];
    // $clientName = $con->query("select name from client where id = $clientId ")->fetch_assoc()["name"];
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
                <a class="nav-link d-flex align-items-center" href="clientDashboard?wid=<?php echo $wid ?>">
                    <img class="nav-icon" src="Icons/Group 3.svg"/>&nbsp;&nbsp;
                    <span>Dashboard</span>
                </a>
            </li>
            <?php }
            else{ ?>
            <li class="nav-item d-flex">
                <a class="nav-link d-flex align-items-center" href="admin/clientList">
                    <img class="nav-icon" src="Icons/Group 3.svg"/>&nbsp;&nbsp;
                    <span>List Clients</span>
                </a>
            </li>
            <?php } ?>
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
                    <a class="dropdown-item" href="logout"><i class="fas fa-sign-out-alt"></i>Logout</a>
                </div>
            </li>
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
                    Contacts
                    </svg>
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

    <div class="mar">

        <!-- HEADER -->
        <div id="header">
            <div class="container-fluid shadow" stickylevel="0" style="z-index:1200;">
                <div class="row pt-1">
                    <div class="col-md-4">
                        <!-- <img class="float-left" src="vendor/img/audit-edge-logo.svg" style="height:45px;"> -->
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
                    <a href="#profile" data-toggle="tab" class="nav-item nav-link has-icon nav-link-faded size active">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user mr-2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>Firm Profile
                    </a>
                    <a href="#account" data-toggle="tab" class="nav-item nav-link has-icon nav-link-faded size">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-settings mr-2"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>Tools
                    </a>
                    <a href="#billing" data-toggle="tab" class="nav-item nav-link has-icon nav-link-faded size">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-credit-card mr-2"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>Billing
                    </a>
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
                        <a href="#billing" data-toggle="tab" class="nav-link has-icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-credit-card"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg></a>
                        </li>
                    </ul>
                </div>
                <div class="card-body tab-content">
                    <div class="tab-pane active" id="profile">
                        <h6>YOUR FIRM INFORMATION</h6>
                        <hr>
                        <form>
                        <div class="form-group">
                            <label for="fullName">Firm Name</label>
                            <input type="text" class="form-control" id="fullName" aria-describedby="fullNameHelp" placeholder="Enter your Firm Name">
                        </div>
                        <div class="form-group">
                            <label for="bio">Contact Address</label>
                            <textarea class="form-control autosize" id="bio" placeholder="Enter contact address" style="overflow: hidden; overflow-wrap: break-word; resize: none; height: 62px;"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="url">Firm Email</label>
                            <input type="text" class="form-control" id="url" placeholder="Enter your email address" value="">
                        </div>
                        <div class="form-group">
                            <label for="location">Firm Leader</label>
                            <input type="text" class="form-control" id="location" placeholder="Enter your Firm Leader" value="">
                        </div>
                        <div class="form-group">
                            <label>Multicurrency</label>
                            <select class="form-select">
                                <option value="Yes">Yes</option>
                                <option value="No" selected>No</option>
                            </select>
                        </div>
                        <!-- <button type="button" class="btn btn-primary">Update Profile</button>
                        <button type="reset" class="btn btn-light">Reset Changes</button> -->
                        </form>
                    </div>
                    <div class="tab-pane" id="account">
                        <h6>Mode</h6>
                        <hr>
                        <form>
                        <div class="form-group">
                            <label for="username">Chat Assistance</label>
                            <input type="text" class="form-control" id="username" aria-describedby="usernameHelp" placeholder="Enter your username" value="">
                        </div>
                        <hr>
                        <div class="form-group">
                            <label class="d-block text-danger">Help Desk Number</label>
                            <input type="text" class="form-control" id="number" aria-describedby="numberHelp" value="1800-600-5958">
                        </div>
                        <div class="form-group">
                            <label class="d-block text-danger">Email Us</label>
                            <input type="text" class="form-control" id="email" aria-describedby="emailHelp" value="yourfirmaudit@gmail.com">
                        </div>
                        <div class="form-group">
                            <label class="d-block text-danger">24/7 online chat assistance</label>
                            <input type="text" class="form-control" id="chat" aria-describedby="chatHelp" value="">
                        </div>
                        <div class="form-group">
                            <label class="d-block text-danger">24/7 Help Desk Number</label>
                            <input type="text" class="form-control" id="24Number" aria-describedby="24NumberHelp" value="">
                        </div>
                        <!-- <button class="btn btn-danger" type="button">Delete Account</button> -->
                        </form>
                    </div>
                    <div class="tab-pane" id="billing">
                        <h6>BILLING SETTINGS</h6>
                        <hr>
                        <form>
                            <div class="form-group">
                                <label for="fullName">Subcription</label>
                                <input type="text" class="form-control" id="subcription" aria-describedby="fullNameHelp" placeholder="It's simply free option to upgrade">
                            </div>
                            <div class="form-group mb-0">
                                <label for="fullName">Add Workspace</label>
                                <input type="text" class="form-control" id="add_workspace" aria-describedby="fullNameHelp" placeholder="Form open up to submit request">
                            </div>
                            <div class="form-group mb-0">
                                <label for="fullName">Billing</label>
                                <input type="text" class="form-control" id="billing_subsection" aria-describedby="fullNameHelp" placeholder="Only for Paid customers blank for free">
                            </div>
                            <div class="form-group mb-0">
                                <label for="fullName">Payment History</label>
                                <input type="text" class="form-control" id="payment_history" aria-describedby="fullNameHelp" placeholder="Only for Paid customers blank for free">
                            </div>
                            <div class="form-group mb-0">
                                <label for="fullName">Firm Storage space</label>
                                <input type="text" class="form-control" id="firm_storage_space" aria-describedby="fullNameHelp" placeholder="10MB for free customers">
                            </div>
                            <div class="form-group mb-0">
                                <label for="fullName">Storage space used</label>
                                <input type="text" class="form-control" id="firm_storage_space_used" aria-describedby="fullNameHelp" placeholder="Only for Paid customers blank for free">
                            </div>
                            <div class="form-group mb-0">
                                <label for="fullName">Add Storage space</label>
                                <input type="text" class="form-control" id="firm_storage_space_add" aria-describedby="fullNameHelp" placeholder="Only for Paid customers blank for free">
                            </div>
                        </form>
                    </div>
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
        get_data();

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

    $(document).on('click', '.editClient', function() {
        var id = $(this).attr("id");
        $.ajax({
            url: "editClientMemberFetchAjax.php",
            type: "POST",
            data: {
                id: id,
                cid: <?php echo $clientId; ?>
            },
            success: function(data) {
                obj = JSON.parse(data);
                console.log(obj);
                id = obj.id;
                $("#editModal #name1").val(obj.name);
                $("#editModal #email1").val(obj.email);
                $("#editModal #active1 option[value=" + obj.active + "]").attr(
                    'selected', 'selected');
                $("#editModal #design").val(obj.designation);
                $("#editModal").modal('show');
            }
        });
    });


    function get_data() {
        var dataTable = $('#clientMemberTable').DataTable({
            "destroy": true,
            "processing": true,
            "serverSide": true,
            "searching": true,
            "order": [],
            "fnRowCallback": function(nRow, aData, iDisplayIndex) {
                $("td:first", nRow).html(iDisplayIndex + 1);
                return nRow;
            },
            "ajax": {
                url: "clientMemberProfileFetchAjax.php",
                type: "POST",
                data: {
                    cid: <?php echo $clientId ?>
                }
            }
        });
    }

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

</html>