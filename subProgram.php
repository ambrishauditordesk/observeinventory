<?php
    include 'dbconnection.php';
    session_start();
    if (!isset($_SESSION['email']) && empty($_SESSION['email'])) {
        header("Location: ../login");
    }
    $clientName = $_SESSION['cname'];
    $prog_id = $_GET['pid'];
    $prog_parentId = $_GET['parent_id'];
    $wid=$_GET['wid'];
    $bread = $_SESSION['breadcrumb'];
    $tmp = array();
    $flag = 0;
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
                    <a class="navbar-brand navbar-logo" href="admin/clientList">Audit-EDG</a>
                </div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">
            <!-- Nav Item - Pages Collapse Menu -->

            <?php
                $query = "select program.* from program inner join workspace_log on program.id=workspace_log.program_id where program.parent_id='$prog_parentId' and workspace_log.workspace_id='$wid'";
                $exquery = $con->query($query);
                if ($exquery->num_rows != 0) 
                {
                    while($queryrow = $exquery->fetch_assoc())
                    { 
                        if($con->query("select * from program where parent_id='".$queryrow['id']."'")->num_rows > 0)
                        {
                            ?>
            <li id="employees" class="nav-item">
                <a class="nav-link d-flex align-items-center"
                    href="subProgram.php?pid=<?php echo $queryrow['id']; ?>&parent_id=<?php echo $queryrow['parent_id']; ?>&wid=<?php echo $wid; ?>">
                    <i class="fas fa-fw fa-dolly-flatbed"></i>
                    <span><?php echo trim($queryrow['program_name']); ?></span>
                </a>
            </li> <?php
                        }
                    } 
                }
            ?>

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
                        <div class="row d-flex justify-content-space-between">
                            <div class="col-md-5">
                                <h1>Audit Programme</h1>
                            </div>
                            <div class="col-md-7">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb" style="justify-content:flex-end; background-color:transparent;">
                                    <?php
                                        if(sizeof($bread) != 0){
                                            $y = 0;
                                            for($x = 0;$x<sizeof($bread); $x++){
                                                if($bread[$x]['pid'] != $prog_id){
                                                    $tmp[$y]['pid'] = $bread[$x]['pid'];
                                                    $tmp[$y]['name'] = $bread[$x]['name'];
                                                    $tmp[$y++]['parent_id'] = $bread[$x]['parent_id'];
                                                }
                                                else{
                                                    $tmp[$y]['pid'] = $bread[$x]['pid'];
                                                    $tmp[$y]['name'] = $bread[$x]['name'];
                                                    $tmp[$y++]['parent_id'] = $bread[$x]['parent_id'];
                                                    $flag = 1;
                                                break;
                                                }
                                            }
                                            if(!$flag){
                                                $i = sizeof($bread);
                                                $bread[$i]['pid'] = $prog_id;
                                                $bread[$i]['name'] = $con->query("select program_name from program where id = ".$prog_id)->fetch_assoc()['program_name'];
                                                $bread[$i++]['parent_id'] = $prog_parentId;
                                            }
                                            else{
                                                $bread = $tmp;
                                            }
                                            
                                        }
                                        elseif(sizeof($bread) == 0){
                                            $bread[0]['pid'] = $prog_id;
                                            $bread[0]['name'] = $con->query("select program_name from program where id = ".$prog_id)->fetch_assoc()['program_name'];
                                            $bread[0]['parent_id'] = $prog_parentId;
                                            
                                        } 
                                        $_SESSION['breadcrumb'] = $bread;
                                        // var_dump($bread);
                                        for($i = 0; $i<sizeof($bread); $i++){
                                            if($i == sizeof($bread)-1){
                                                ?>
                                                <li class="breadcrumb-item font-weight-bold"><span><?php echo $bread[$i]['name']; ?></span></li>
                                                <?php
                                            }
                                            else{
                                            ?>
                                            <li class="breadcrumb-item"><a href="subProgram.php?pid=<?php echo $bread[$i]['pid']; ?>&parent_id=<?php echo $bread[$i]['parent_id']; ?>&wid=<?php echo $wid; ?>"><?php echo $bread[$i]['name']; ?></a></li>
                                            <?php
                                            }
                                        }

                                    ?>
                                    </ol>
                                </nav>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <?php
                        $query = "select program.*,workspace_log.status from program inner join workspace_log on program.id=workspace_log.program_id where program.parent_id='$prog_id' and workspace_log.workspace_id='$wid'";
                        $exquery = $con->query($query);
                        if ($exquery->num_rows != 0) 
                        {
                            while($queryrow = $exquery->fetch_assoc())
                            { 
                               if($con->query("select * from program where parent_id='".$queryrow['id']."'")->num_rows > 0)
                               {
                                ?>
                            <div class="list-group">
                                <a href="subProgram.php?pid=<?php echo $queryrow['id']; ?>&parent_id=<?php echo $queryrow['parent_id']; ?>&wid=<?php echo $wid; ?>"
                                    class="list-group-item list-group-item-action"><b><?php echo trim($queryrow['program_name']); ?></b></a>
                            </div>
                            <?php 
                                }
                                else
                                {
                                ?>
                            <div class="list-group">
                                <a href="#" data-target="#spOpenModal" data-toggle="modal"
                                    class="list-group-item list-group-item-action"><?php echo trim($queryrow['program_name']); ?>
                                    &nbsp;&nbsp; <i class="fas fa-external-link-alt" style="color:blue !important;"></i>
                                    <?php if($queryrow['status']==1)
                                        { ?>
                                    <i class="fas fa-check-circle" style="color:green !important;"></i>
                                    <?php }  
                                        else
                                        { ?>
                                    <i class="fas fa-times-circle" style="color:red !important;"></i>
                                    <?php }                                   
                                    ?>
                                </a>
                            </div>
                            <?php
                                }
                            } 
                        }
                            ?>

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
    });
    </script>
</body>