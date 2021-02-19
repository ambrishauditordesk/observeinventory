<?php
    include 'dbconnection.php';
    session_start();
    if (!isset($_SESSION['email']) && empty($_SESSION['email'])) {
        header("Location: login");
    }
    if (isset($_SESSION['accessLevel']) && !empty($_SESSION['accessLevel']) && $_SESSION['accessLevel'] != '1') {
        header('Location: login');
    }

    $_SESSION['client_id'] = $clientID = $_GET['cid'];
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

</head>

<body style="overflow-y: scroll" oncontextmenu="return false">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-mainbg border-bottom">
        <!-- Topbar Navbar -->
        <ul class="navbar-nav ml-auto">
            <!-- <li class="nav-item d-flex">
                <a class="nav-link d-flex align-items-center" href="clientList">
                    <span>List Clients</span>&nbsp;&nbsp;
                    <i class="fas fa-list fa-1x"></i>
                </a>
            </li> -->
            <li class="nav-item d-flex">
                <a class="nav-link d-flex align-items-center" href="admin/clientList">
                    <img class="nav-icon" src="Icons/Group 3.svg"/>&nbsp;&nbsp;
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
                    <a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
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
                    Dashboard
                    </svg>
                </div>
            </div>
            <div class="settings">
                <div class="settings-items-top-div">
                    <div class="settings-items">
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
            if($_SESSION['role'] != 3 && $_SESSION['external'] != 1){
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
                    </div>
                </div>
            </div>
        </div>  
        <?php } 
        ?>
        <!-- TABLE -->
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
                                                $query = "Select * from workspace where client_id = $clientID";
                                                $result = $con->query($query);
                                                if ($result->num_rows != 0) {
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
                                                    <a href="clientDashboard?wid=<?php echo $row['id'];?>"><i class="fas fa-external-link-alt"></i></a>
                                                <?php
                                                    }
                                                    else{
                                                        ?>
                                                    <a href="subProgram?wid=<?php echo $row['id'];?>&pid=247"><i class="fas fa-external-link-alt"></i></a>
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
                                                </td>
                                            </tr>
                                            <?php
                                            }
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

        $(document).on('click','.freeze',function(){
            let id = $(this).attr('id');
            $.ajax({
                url: 'freeze.php',
                type: 'POST',
                data: {id: id,freeze: 0},
                success: function(data){
                    if (data) {
                            swal({
                                icon: "success",
                                text: "Thank You for Unlocking.",
                            }).then(function (isConfirm) {
                                if (isConfirm) {
                                    window.location.href = "workspace?cid=<?php echo $_SESSION['client_id']; ?>";
                                }
                            });
                        }
                }
            })
        })
});
    </script>