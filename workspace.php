<?php
    include 'dbconnection.php';
    session_start();
    if (!isset($_SESSION['email']) && empty($_SESSION['email'])) {
        header("Location: ../login");
    }
    if (isset($_SESSION['role_id']) && !empty($_SESSION['role_id']) && $_SESSION['role_id'] != '1') {
        header('Location: ../login');
    }
    $clientID = $_GET['cid'];
    $clientName = $con->query("select name from client where id = $clientID ")->fetch_assoc()["name"];
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
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="css/pace-theme.css" rel="stylesheet">
    <link href="css/custom.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
</head>

<body style="overflow-y: scroll">

<!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-mainbg">
        <a class="navbar-brand navbar-logo" href="admin/clientList">Audit-EDG</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <i class="fas fa-bars text-white"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ml-auto">
                <div class="hori-selector"><div class="left"></div><div class="right"></div></div>
                <!-- <li class="nav-item">
                    <a class="nav-link" href="#"><i class="fas fa-clipboard"></i>Doodle/Notes</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="#"><i class="far fa-address-book"></i>Support/Tickets</a>
                </li> -->
                <li class="nav-item">
                    <a class="nav-link" href="#" data-toggle="modal" data-target="#addClientModal"><i class="fas fa-user-plus"></i>Add Clients</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="admin/clientList"><i class="fas fa-list"></i>List Clients</a>
                </li>
                <!-- <li class="nav-item">
                    <a class="nav-link" href="#"><i class="far fa-chart-bar"></i>Profile</a>
                </li> -->
                <li class="nav-item">
                    <a class="nav-link" href="logout"><i class="fas fa-sign-out-alt"></i>Logout</a>
                </li>
            </ul>
        </div>
    </nav>
	
<!-- HEADER -->
<div id="header"><div class="container-fluid shadow border border-bottom" stickylevel="0" style="z-index:1200;">
    <div class="row pt-1">
        <div class="col-md-4">
            <div class="ml-2 font-1 h3 py-1 d-inline-block float-left"></div>
        </div>
        <div class="col-md-4 text-center font-2 getContent" href="dashboard">
            <h3><?php echo strtoupper($clientName . " Workspace"); ?></h3>
        </div>    
    </div>
</div><br>

<!-- ADD WORKSPACE BUTTON -->
            <div class = "row justify-content-md-center">   
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

<!-- DATATABLE -->
        <div class="container pt-4">
            <div class="row">
                <div class="card-body" style="width:10px;">
                    <div class="table-responsive">
                        <div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4">
                            <div class="row">
                                <div class="col-sm-12">
                                    <table id="workspaceTable"
                                           class="table display table-bordered table-striped">
                                        <thead>
                                        <tr>
                                            <th scope="col">From</th>
                                            <th scope="col">To</th>
                                            <th scope="col">Actions</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                                $_SESSION['cname'] = $con->query("select name from client where id = $clientID" )->fetch_assoc()['name'];
                                                $query = "Select * from workspace where client_id = $clientID";
                                                $result = $con->query($query);
                                                if ($result->num_rows != 0) {
                                                    while ($row = $result->fetch_assoc()) {
                                            ?>
                                            <tr>
                                                <td><?php echo $row['datefrom']; ?></td>
                                                <td><?php echo $row['dateto']; ?></td>
                                                <td>
                                                    <a href="clientDashboard?wid=<?php echo $row['id'];?>"><i class="fas fa-external-link-alt"></i></a>
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


<!--Add Client Form -->
            <div class="modal fade" id="addClientModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Fill in the Client details<h5>
                                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                        </div>
                        <form action="admin/addClient" method="post" id="addClientForm" enctype="multipart/form-data"
                              autocomplete="off">
                            <div class="modal-body">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Basic Details<h5>
                            </div>
                                <div class="form-group ">
                                    <label for="name">Client Name</label>
                                    <input type="text" class="form-control" name="clientname" required>
                                </div>
                                <div class="form-group ">
                                    <label for="name">Nick Name</label>
                                    <input type="text" class="form-control" name="nickname">
                                </div>
                                <div class="form-group ">
                                    <label for="name">Date of Incorporation/ Birth</label>
                                    <input type="date" class="form-control" name="dob" required>
                                </div>
                                <div class="form-group ">
                                    <label for="country">Constitution</label>
                                        <select class="form-control" name="constitution" required>
                                            <option >Select Constitution !</option>
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
                                            <option >Select Industry !</option>
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
                                    <input type="text" class="form-control" name="add">
                                </div>
                                <div class="form-group">
                                    <label for="country">Country</label>
                                    <input type="text" class="form-control" name="country">
                                </div>
                                <div class="form-group" id="stateEntryIdDiv">
                                    <label for="state">State</label>
                                    <input type="text" class="form-control" name="state">
                                </div>
                                <div class="form-group" id="citiesEntryIdDiv">
                                    <label for="city">City</label>
                                    <input type="text" class="form-control" name="city">
                                </div>
                                <div class="form-group ">
                                    <label for="name">Pincode</label>
                                    <input type="text" class="form-control" name="pincode" required>
                                </div>
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Satuatory Information<h5>
                                </div>
                                <div class="form-group ">
                                    <label for="name">Pan No.</label>
                                    <input type="text" class="form-control" name="pan" required>
                                </div>   
                                <div class="form-group ">
                                    <label for="name">GST No.</label>
                                    <input type="text" class="form-control" name="gst" required>
                                </div>     
                                <div class="form-group ">
                                    <label for="name">TAN No.</label>
                                    <input type="text" class="form-control" name="tan" required>
                                </div> 
                                <div class="form-group ">
                                    <label for="name">CIN No.</label>
                                    <input type="text" class="form-control" name="cin" required>
                                </div>
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Contact Person<h5>
                                </div>
                                <div class="row">
                                                <div class="col">
                                                    <table class="table table-bordered table-hover" id="tab_logic">
                                                        <thead>
                                                        <tr>
                                                            <th class="text-center"> Name</th>
                                                            <th class="text-center"> Email</th>
                                                            <th class="text-center"> Phone</th>
                                                            <th class="text-center"> Designation</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr id='addr0'>
                                                            <td><input type="text" class="form-control" name="pname[]" required></td>
                                                            <td><input type ="email" class="form-control" name="email[]" required></td>
                                                            <td><input type="text" class="form-control" name="phone[]" required></td>
                                                            <td><input type="text" name='designation[]' class="form-control" required/></td>
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
                                <input class="btn btn-primary" type="submit" id="dataEntrySubmit" value="Done">
                            </div>
                        </form>
                    </div>
                </div>
            </div>

<!--ADD WORKSPACE -->
    <div class="modal fade" id="addWorkspaceModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Workspace for Financial Year<h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                </div>
                <form action="addWorkspace" method="post" id="addWorkspace">
                    <div class="modal-body">
                        <div class="form-group ">
                            <label for="name">From</label>
                            <input type="date" class="form-control" name="from" required>
                        </div>
                        <div class="form-group ">
                            <label for="name">To</label>
                            <input type="date" class="form-control" name="to" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-danger" type="button" data-dismiss="modal">Cancel</button>
                        <input class="btn btn-primary" type="submit" id="" value="Done">
                        <input type= "hidden" name = "clientID" value = "<?php echo $clientID; ?>" >
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>
    <!-- Page level plugins -->
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <!-- Page level custom scripts -->
    <script src="js/demo/datatables-demo.js"></script>
    <script src="js/pace.min.js"></script>
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
});
    </script>