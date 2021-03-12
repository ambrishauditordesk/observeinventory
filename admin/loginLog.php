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

<body style="overflow-y: scroll; height: 100% !important;" oncontextmenu="return false">

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
                <a class="nav-link d-flex align-items-center" href="clientList">
                    <img class="nav-icon" src="../Icons/Group 3.svg"/>&nbsp;&nbsp;
                    <span>List Clients</span>
                </a>
            </li>
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
                    <a class="dropdown-item" href="../logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
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
                    Session Log
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
        <!-- DATATABLE -->
        <div class="container pt-4">
            <div class="row">
                <div class="card-body" style="width:10px; height:100% !important; border-radius: 12px; background-color: white;">
                    <div class="table-responsive">
                        <div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4">
                            <div class="row">
                                <div class="col-sm-12">
                                    <table id="clientListTable" class="table display table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th scope="col">Sl</th>
                                                <th scope="col">Email</th>
                                                <th scope="col">IP Address</th>
                                                <th scope="col">Date</th>
                                                <th scope="col">Location</th>
                                                <th scope="col">Browser</th>
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

    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Core plugin JavaScript-->
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
    <!-- Custom scripts for all pages-->
    <script src="../js/sb-admin-2.min.js"></script>
    <!-- Page level custom scripts -->
    <script src="../js/custom.js"></script>
    <script>
    $(document).ready(function() {
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
            "ajax": {
                url: "loginLogFetchAjax.php",
                type: "POST"
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
            }
        });
    });
    </script>
</body>

</html>