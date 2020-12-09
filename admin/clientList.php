<?php
    include '../dbconnection.php';
    session_start();
    if (!isset($_SESSION['email']) && empty($_SESSION['email'])) {
        header("Location: ../login");
    }
    if (isset($_SESSION['role_id']) && !empty($_SESSION['role_id']) && $_SESSION['role_id'] != '1') {
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
    <link href="../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="../css/custom.css" rel="stylesheet">
    <link href="../css/bootstrap.min.css" rel="stylesheet">
</head>

<body style="overflow-y: scroll">

<!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-mainbg">
        <a class="navbar-brand navbar-logo" href="clientList">Audit-EDG</a>
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
                <!-- <li class="nav-item">
                    <a class="nav-link" href="clientList"><i class="fas fa-list"></i>List Clients</a>
                </li> -->
                <!-- <li class="nav-item">
                    <a class="nav-link" href="#"><i class="far fa-chart-bar"></i>Profile</a>
                </li> -->
                <li class="nav-item">
                    <a class="nav-link" href="../logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
                </li>
            </ul>
        </div>
    </nav>
	
<!-- HEADER -->
<div id="header"><div class="container-fluid shadow border border-bottom" stickylevel="0" style="z-index:1200;">

    
    <div class="row pt-1">
        <div class="col-md-4">
            <!-- <img class="float-left" src="../vendor/img/audit-edge-logo.svg" style="height:45px;"> -->
            <div class="ml-2 font-1 h3 py-1 d-inline-block float-left"></div>
        </div>
        <div class="col-md-4 text-center font-2 getContent" href="dashboard">
            <h3><?php echo strtoupper($_SESSION['name'] . " Clients"); ?></h3>
        </div>    
    </div>
</div>

<!-- CARDS -->
<div id="content" class="toggleContents"><div class="container pt-4">

    <div class="card-deck font-counter-1">

        <div class="card bg-light border-primary">
            <div class="card-body text-center">
                <h5 class="card-title">Total Assigned</h5>
                <h6 class="card-subtitle mb-2 text-muted">Audits</h6>
                <p class = "text-primary"><?php $userId = $_SESSION['id'];
                    echo $con->query("SELECT count(id) total FROM client where active = 1 group by id")->num_rows; ?>
                </p>
            </div>
        </div>
        
        <div class="card bg-light border-warning">
            <div class="card-body text-center">
                <h5 class="card-title">In Progress</h5>
                <h6 class="card-subtitle mb-2">Audits</h6>
                <p class="text-warning">0</p>
            </div>
        </div>

        <div class="card bg-light border-success">
            <div class="card-body text-center">
                <h5 class="card-title">Completed</h5>
                <h6 class="card-subtitle mb-2 text-success">Audits</h6>
                <p class="text-success">0</p>
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
                                    <table id="clientListTable"
                                           class="table display table-bordered table-striped">
                                        <thead>
                                        <tr>
                                            <th scope="col">Sl</th>
                                            <th scope="col">Client Name</th>
                                            <th scope="col">Type</th>
                                            <th scope="col">Created On</th>
                                            <th scope="col">Status</th>
                                            <!-- <th scope="col">% of Completion</th> -->
                                            <th scope="col">Actions</th>
                                            <!-- <th scope="col">Workspace</th> -->
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
                        <form action="addClient" method="post" id="addClientForm" enctype="multipart/form-data"
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

 <!-- Edit Client Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Client Details</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                </div>
                <form method = "post" action = "editAClient">
                    <div class="modal-body">
                        <div class="form-group ">
                            <label for="name">Client Name</label>
                            <input type="text" id="name" class="form-control" name="name" required value="">
                        </div>
                        <div class="form-group ">
                            <label for="name">Type</label>
                            <input type="text" id="const_id" class="form-control" name="const_id" readonly value="">               
                        </div>
                        <div class="form-group ">
                            <label for="name">Created On</label>
                            <input type="text" class="form-control" name="added_by_date" id="added_by_date" readonly value="">
                        </div>
                        <div class="form-group ">
                            <label for="name">Status</label>
                            <select name="active" id="active" class="form-control" required>
                                <option value="0">Inactive</option>
                                <option value="1">Active</option>
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-danger" type="button" data-dismiss="modal">Cancel</button>
                            <input class="btn btn-primary" id="submit" type="submit" value="Done">
                    </div>
                </form>
            </div>
        </div>
    </div>

<!-- Alert Modal -->
    <div class="modal fade" id="alertModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"></h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body"></div>
                <div class="modal-footer">
                    <a class="btn btn-primary" data-dismiss="modal" href="#" onclick="get_data()">OK</a>
                </div>
            </div>
        </div>
    </div>


    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Core plugin JavaScript-->
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
    <!-- Custom scripts for all pages-->
    <script src="../js/sb-admin-2.min.js"></script>
    <!-- Page level plugins -->
    <script src="../vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <!-- Page level custom scripts -->
    <script src="../js/demo/datatables-demo.js"></script>
    <script src="../js/custom.js"></script>
    <script>
    function get_data() {
                var dataTable = $('#clientListTable').DataTable({
                    "destroy": true,
                    "processing": true,
                    "serverSide": true,
                    "searching": true,
                    "order": [],
                    "fnRowCallback" : function(nRow, aData, iDisplayIndex){
                        $("td:first", nRow).html(iDisplayIndex +1);
                        return nRow;
                        },
                    "ajax": {
                        url: "clientListFetchAjax.php",
                        type: "POST"
                    }
                });
    }
    
$(document).ready(function(){
    
    get_data();

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

$(document).on('click','#submit',function(e){
            e.preventDefault();
            var name = $("#name").val();
            var type = $("#const_id").val();
            var date = $("#added_by_date").val();
            var active = $("#active").val();
            $.ajax({
                url: "editAClient.php",
                type: "POST",
                data: {
                    name:name,
                    type:const_id,
                    date:added_by_date,
                    active:active
                },
                success: function(data){
                    obj = JSON.parse(data);
                    $("#editModal").modal('hide');
                    $("#alertModal").modal('show');
                    $("#alertModal .modal-title").html(obj[0].title);
                    $("#alertModal .modal-body").html(obj[0].body);
                }
            });
            
            
        });

        $(document).on('click', '.editClient', function () {
        var id = $(this).attr("id");
        $("#editModal #active > option:selected").removeAttr('selected');
        $.ajax({
            url : "clientEditFetchAjax.php",
            type : "POST",
            data : {id:id},
            success : function(data){
                obj = JSON.parse(data);
                id = obj.id;
                $("#editModal #name").val(obj.name);
                $("#editModal #const_id").val(obj.const);
                $("#editModal #added_by_date").val(obj.added_by_date);             
                $("#editModal #active option[value="+obj.active+"]").attr('selected','selected');
                $("#editModal").modal('show');
            }
        });
        });
</script>