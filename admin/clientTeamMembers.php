<?php
    include '../dbconnection.php';
    session_start();
    if (!isset($_SESSION['email']) && empty($_SESSION['email'])) {
        header("Location: ../login");
    }
    echo 1;
    if(isset($_GET['wid']) && !empty($_GET['wid']))
        $wid = base64_decode($_GET['wid']);
        
    $clientId = base64_decode($_GET['cid']);
    if($con->query("select * from client where id = $clientId")->num_rows == 0)
        header('Location: ../login');
    $clientName = $con->query("select name from client where id = $clientId ")->fetch_assoc()["name"];  
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
                <a class="nav-link d-flex align-items-center" href="../clientDashboard?wid=<?php echo $wid ?>">
                    <img class="nav-icon" src="../Icons/Group 3.svg"/>&nbsp;&nbsp;
                    <span>Dashboard</span>
                </a>
            </li>
            <?php }
            else{ ?>
            <li class="nav-item d-flex">
                <a class="nav-link d-flex align-items-center" href="clientList">
                    <img class="nav-icon" src="../Icons/Group 3.svg"/>&nbsp;&nbsp;
                    <span>Clients List</span>
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
                        <img class="nav-icon" src="../Icons/Group 6.svg" style="width:15px !important;"/>
                    </span>
                </a>
                <!-- Dropdown - User Information -->
                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <?php 
                        if($_SESSION['role'] == '-1'){
                        ?>
                            <a class="dropdown-item" href="admin/loginLog"><i class="fas fa-list"></i>Login Log</a>
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
                    Contacts
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
        <div id="header">
            <div class="container-fluid shadow border border-bottom" stickylevel="0" style="z-index:1200;">
                <div class="row pt-1">
                    <!-- <div class="col-md-3"> -->
                        <!-- <img class="float-left" src="../vendor/img/audit-edge-logo.svg" style="height:45px;"> -->
                        <!-- <div class="ml-2 font-1 h3 py-1 d-inline-block float-left"></div> -->
                    <!-- </div> -->
                    <div class="col-md-12 text-center font-2 getContent" href="#">
                        <h3><?php echo strtoupper($clientName . " - CLIENT TEAM MEMBERS"); ?></h3>
                    </div>
                </div>
            </div>
        </div><br>

        <div class="row justify-content-md-center" style="width: 100% !important;">

            <!-- Total Members -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total
                                    Client Team Contacts
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <?php
                                        $totalMembers = $con->query("SELECT COUNT(user.id) AS total FROM `user` inner join user_client_log on user.id=user_client_log.user_id where user.client_id = '$clientId' or user_client_log.client_id = '$clientId' ORDER BY user.name DESC");
                                    if ($totalMembers->num_rows != 0) {
                                        $count = $totalMembers->fetch_assoc();
                                        echo " " . $count['total'];
                                        if ($count['total'] > 1) {
                                            echo " Contacts";
                                        } else {
                                            echo " Contacts";
                                        }
                                    } else {
                                        echo " 0 Contact";
                                    }
                                ?>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-user-tie fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

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
                    </div>
                </div>
            </div>
        </div>

        <!-- DATATABLE -->
        <div class="container pt-4">
            <div class="row">
                <div class="card-body" style="width:10px; height:100% !important; border-radius: 12px; background-color: white;">
                    <div class="table-responsive">
                        <div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4">
                            <div class="row">
                                <div class="col-sm-12">
                                    <table id="clientMemberTable" class="table display table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th scope="col">Sl</th>
                                                <th scope="col">Name</th>
                                                <th scope="col">Email</th>
                                                <th scope="col">Status</th>
                                                <th scope="col">Designation</th>
                                                <th scope="col">Edit</th>
                                                <!-- <th scope="col">Client</th> -->
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

        <!-- Register a Member Form -->
        <div class="modal fade" id="registerMemberModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-size" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Register A Contact
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
                                <input type="email" class="form-control" name="email" id="email" autocomplete="off"
                                    required>
                            </div>
                            <div class="form-group ">
                                <label for="name">Password</label>
                                <input type="password" class="form-control" name="password" id="password" autocomplete="off"
                                    required>
                            </div>
                            <div class="form-group ">
                                <label for="name">Designation</label>
                                <input type="text" class="form-control" name="design1" id="design1" required>
                            </div>
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
                                <h5 class="modal-title" id="exampleModalLabel">Edit Contact<h5>
                                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                            </div>
                            <br>
                            <div class="form-group ">
                                <label for="name">Full Name</label>
                                <input type="text" class="form-control" name="name" id="name1">
                            </div>
                            <div class="form-group ">
                                <label for="name">Email Address</label>
                                <input type="email" class="form-control" name="email" id="email1" autocomplete="off"
                                    readonly>
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
                                <label for="name">Designation</label>
                                <input type="text" class="form-control" name="design" id="design">
                            </div>
                        </div>
                        <div class="modal-footer  d-flex align-items-center justify-content-center">
                            <!-- <button class="btn btn-danger" type="button" data-dismiss="modal">Cancel</button> -->
                            <input class="btn btn-primary" type="submit" id="submit1" value="Done">
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
    <!-- MULTISELECT JS -->
    <script src="../js/multiselect-master/dist/js/multiselect.js"></script>
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
                cid: <?php echo $clientId ?>
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
            "bInfo": false,
            "fnRowCallback": function(nRow, aData, iDisplayIndex) {
                $("td:first", nRow).html(iDisplayIndex + 1);
                return nRow;
            },
            "ajax": {
                url: "clientTeamMemberProfileFetchAjax.php",
                type: "POST",
                data: {
                    cid: <?php echo $clientId ?>
                }
            }
        });
    }

    $(document).on('click', '#submit1', function(e) {
        e.preventDefault();
        var name = $("#name1").val();
        var email = $("#email1").val();
        var design = $("#design").val();
        var active = $("#active1").val();
        $("#editModal").modal('hide');

        $.ajax({
            url: "editClientMember.php",
            type: "POST",
            data: {
                name: name,
                email: email,
                design: design,
                active: active
            },
            success: function(data) {
                if (data) {
                    swal({
                        icon: "success",
                        text: "Updated",
                    }).then(function(isConfirm) {
                        if (isConfirm) {
                            location.reload();
                        }
                    });
                } else {
                    swal({
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


    });

    $('#registerSubmit').on('click', function(e) {
        e.preventDefault();
        var name = $("#name").val();
        var email = $("#email").val();
        var password = $("#password").val();
        var design = $("#design1").val();
        $.ajax({
            url: "addClientMember.php",
            type: "POST",
            data: {
                name: name,
                email: email,
                password: password,
                design: design,
                cid: <?php echo $clientId; ?>
            },
            success: function(response) {
                if (response) {
                    swal({
                        icon: "success",
                        text: name + " Added",
                    }).then(function(isConfirm) {
                        if (isConfirm) {
                            location.reload();
                        }
                    });
                } else {
                    swal({
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