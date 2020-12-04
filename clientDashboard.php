<?php
    include 'dbconnection.php';
    session_start();
    if (!isset($_SESSION['email']) && empty($_SESSION['email'])) {
        header("Location: ../login");
    }
    $clientName = $_SESSION['cname'];
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

    <!-- Custom Fav icon -->
    <!-- <link rel="icon" href="img/atllogo.png" type="image/gif" sizes="16x16"> -->

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
        
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css">

    <!-- Custom styles for this template-->
    <style type="text/css">
        .jstree li > a > .jstree-icon {  display:none !important; } 
        .jstree-anchor{
            font-size: 18px;
            color: black !important;
            height: 36px !important;
        }
        .jstree-open, .jstree-closed{
            font-weight: bold;
        }
        .jstree-leaf{
            font-weight: 200;
        }
    </style>
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="css/custom.css" rel="stylesheet">

</head>

<body style="overflow-y: scroll">

    <div id="wrapper" class="">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index">
                <div class="sidebar-brand-icon">
                    <a class="navbar-brand navbar-logo" href="admin/dashboard">Audit-EDG</a>
                </div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">
            <!-- Nav Item - Pages Collapse Menu -->
            <li id="employees" class="nav-item">
                <a class="nav-link" href="#">
                    <i class="fas fa-fw fa-dolly-flatbed"></i>
                    <span>Audit Programme</span>
                </a>
            </li>
            <!-- <li id="viewupload" class="nav-item">
                <a class="nav-link" href="xyz">
                    <i class="fas fa-fw fa-user-tie"></i>
                    <span>Audit Member</span>
                </a>
            </li> -->

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>

        <div id="content-wrapper" class="d-flex flex-column">
            <div class="content">
                <!-- Navbar -->
                <nav class="navbar navbar-expand-lg navbar-mainbg">
                    <!-- <a class="navbar-brand navbar-logo" href="admin/dashboard">Audit-EDG</a> -->
                    <button class="navbar-toggler" type="button" data-toggle="collapse"
                        data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <i class="fas fa-bars text-white"></i>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav ml-auto">
                            <div class="hori-selector">
                                <div class="left"></div>
                                <div class="right"></div>
                            </div>
                            <!-- <li class="nav-item">
                                <a class="nav-link" href="#"><i class="fas fa-clipboard"></i>Doodle/Notes</a>
                            </li>
                            <li class="nav-item active">
                                <a class="nav-link" href="#"><i class="far fa-address-book"></i>Support/Tickets</a>
                            </li> -->
                            <li class="nav-item">
                                <a class="nav-link" href="#" data-toggle="modal" data-target="#addClientModal"><i
                                        class="fas fa-user-plus"></i>Add Clients</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="admin/clientList"><i class="fas fa-list"></i>List Clients</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="logout"><i class="fas fa-sign-out-alt"></i>Logout</a>
                            </li>
                        </ul>
                    </div>
                </nav>
                <!-- HEADER -->
                <div id="header">
                    <div class="container-fluid shadow border border-bottom" stickylevel="0" style="z-index:1200;">
                        <div class="row pt-1">
                            <div class="row text-center cdrow" href="#">
                                <h2><?php echo strtoupper($clientName . " - Dashboard"); ?></h2>
                            </div>
                        </div>
                    </div><br>
                    <!-- Body Starts -->
                    <div class="container-fluid">
                        <div class="col-md-8">
                            <h1>Audit Programme</h1>
                        </div><br>
                        <div class="col-md-12" id="treeview_json"></div>
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
                                            <td><input type="email" class="form-control" name="email[]" required></td>
                                            <td><input type="text" class="form-control" name="phone[]" required></td>
                                            <td><input type="text" name='designation[]' class="form-control" required />
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
                        <input class="btn btn-primary" type="submit" id="dataEntrySubmit" value="Done">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Subprogram Open Modal-->
    <div class="modal fade" id="spOpenModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="container card bg-light font-2 py-2">
                        <div class="row d-flex justify-content-between">
                            <div class="col-md-6">
                                <h5>Obtain Client Acceptance Engagement Letter</h5>
                            </div>
                            <div class="col-md-6 text-right">
                                <a class="btn btn-outline-dark btn-sm py-0 menu-02">
                                    Sign-Off
                                </a>
                                <a class="btn btn-outline-dark btn-sm py-0 menu-02">
                                    Review
                                </a>
                            </div>
                        </div>
                        <div class="row d-flex justify-content-between">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-8">
                                        <label>Documents</label>
                                    </div>
                                    <div class="col-md-12 text-right">
                                        <a class="btn btn-outline-dark btn-sm py-0 menu-02">
                                            <i class="fas fa-upload upload"></i>
                                        </a>
                                    </div>
                                </div>
                                <ul class="list-group h5">
                                    <li class="list-group-item"></li>
                                    <li class="list-group-item"></li>
                                    <li class="list-group-item"></li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <label>Comments</label>
                                <textarea id="comments" class="form-control" style="height:200px;"></textarea>
                            </div>
                        </div>
                        
                            
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>


    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/jstree.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    
    <script src="js/custom.js"></script>
    <script>
    $(document).ready(function() {
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
        $.ajax({
            type: "GET",
            url: "ajax.php",
            data:{id:"<?php echo $_GET['wid']; ?>"},
            dataType: "json",
            success: function(response) {
                var root = response;
                $('#treeview_json').jstree({ 'core' : {
                    'data' : function(obj, cb){
                        cb.call(this, obj.id=="#" ? root: obj.response);
                    },
                    'multiple': false
                } });
            }
        });

        $('#treeview_json').on("select_node.jstree", function (e, data) {
             var programId = data.node.id;
             console.log(data)
             if(data.node.children.length == 0){
                $("#spOpenModal").modal("show");
                $("'#"+programId+"'").append(" <b>Appended text</b>.");
             }
             
        });
        
    });
    </script>
</body>