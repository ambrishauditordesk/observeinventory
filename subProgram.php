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
                $query = "select program.* from program inner join workspace_log on program.id=workspace_log.program_id where program.parent_id='$prog_parentId' and workspace_log.workspace_id='$wid' order by _seq";
                $exquery = $con->query($query);
                if ($exquery->num_rows != 0) 
                {
                    while($queryrow = $exquery->fetch_assoc())
                    { 
                        if($queryrow['hasChild']==1)
                        {
                            ?>
                <li id="employees" class="nav-item  <?php if($queryrow['id'] == $prog_id) echo 'active'; ?>">
                <a class="nav-link d-flex align-items-center"
                    href="subProgram.php?pid=<?php echo $queryrow['id']; ?>&parent_id=<?php echo $queryrow['parent_id']; ?>&wid=<?php echo $wid; ?>">
                    <i class="fas fa-fw fa-dolly-flatbed"></i>
                    <span><?php echo trim($queryrow['program_name']); ?></span>
                </a>
            </li>
            <?php
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
                            <?php
                                if($prog_id != '2' && $prog_id != '20')
                                {
                            ?>
                            <li class="nav-item">
                                <a class="nav-link" href="#" data-toggle="modal" data-target="#addProgModal"><i
                                        class="fas fa-plus-circle"></i>Add Programme</a>
                            </li>
                            <?php }
                                ?>
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
                        <!-- <div class="row">
                            <div class="col-md-5">
                                <h1>Audit Programme</h1>
                            </div>
                        </div> -->
                        <div class="row">
                            <div class="col-md-12">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb" style="background-color:transparent;">
                                        <li class="breadcrumb-item"><a
                                                href="clientDashboard.php?wid=<?php echo $wid;?>">Dashboard</a>
                                        </li>
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
                                        <li class="breadcrumb-item font-weight-bold h5">
                                            <span><?php echo $bread[$i]['name']; ?></span>
                                        </li>
                                        <?php
                                            }
                                            else{
                                            ?>
                                        <li class="breadcrumb-item"><a
                                                href="subProgram.php?pid=<?php echo $bread[$i]['pid']; ?>&parent_id=<?php echo $bread[$i]['parent_id']; ?>&wid=<?php echo $wid; ?>"><?php echo $bread[$i]['name']; ?></a>
                                        </li>
                                        <?php
                                            }
                                        }

                                    ?>
                                    </ol>
                                </nav>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <?php
                                $query = "select program.*,workspace_log.status status, workspace_log.active active  from program inner join workspace_log on program.id=workspace_log.program_id where program.parent_id='$prog_id' and workspace_log.workspace_id='$wid'";
                                $exquery = $con->query($query);
                                if ($exquery->num_rows != 0) 
                                {
                                    while($queryrow = $exquery->fetch_assoc())
                                    { 
                                    if($queryrow['hasChild']==1)
                                    { ?>
                                            <div class="list-group">
                                                <a href="subProgram.php?pid=<?php echo $queryrow['id']; ?>&parent_id=<?php echo $queryrow['parent_id']; ?>&wid=<?php echo $wid; ?>"
                                                    class="list-group-item list-group-item-action"><b><?php echo trim($queryrow['program_name']); ?></b></a>
                                            </div> <?php 
                                        }
                                        else
                                        { ?>
                                            <div class="list-group">
                                                <div class="list-group-item list-group-item-action">
                                                    <?php echo trim($queryrow['program_name']); ?> &nbsp;&nbsp;
                                                    <?php 
                                                    if($queryrow['active'])
                                                    { ?>
                                                        <a href="#" data-target="#spOpenModal" data-toggle="modal" style="a:hover {text-decoration: none;}">
                                                        <i class="fas fa-external-link-alt" style="color:blue !important;"></i>
                                                        </a> <?php
                                                        if($queryrow['status'])
                                                        { ?>
                                                            <i class="fas fa-check-circle" style="color:green !important;"></i> <?php 
                                                        }  
                                                        else
                                                        { ?>
                                                        <i class="fas fa-times-circle" style="color:red !important;"></i> <?php 
                                                        } ?>
                                                        <a href="#" id="<?php echo $queryrow['id']; ?>" class="buttonActive"><i class="fa fa-thumbs-up float-right" 
                                                        aria-hidden="true" style="color:blue !important;"></i></a> <?php
                                                    }
                                                    else
                                                    { ?>
                                                        <a href="#" id="<?php echo $queryrow['id']; ?>" class="buttonActive">
                                                        <i class="fa fa-ban float-right" aria-hidden="true" style="color:orange !important;"></i></a> <?php
                                                    }                                                   
                                                    ?>
                                                </div>
                                            </div> <?php
                                        }
                                    } 
                                } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Footer -->
            <footer class="sticky-footer bg-light">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span><strong><span style="color: #8E1C1C;">Audit-EDG </span>&copy;
                                <?php echo date("Y"); ?></strong></span>
                    </div>

                </div>
            </footer>
        </div>

    </div>
    <!--Add Programme Form -->
    <div class="modal fade" id="addProgModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Programme/Step<h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                </div>
                <form>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Programme Name</label>
                            <input type="text" class="form-control" name="name" id="prog_name" required>
                        </div>
                        <div class="form-group ">
                            <label for="name">Programme Type</label>
                            <select name="addProg" id="prog_type" class="form-control" required>
                                <option value="">Select Type</option>
                                <option value="1">Add as Programme</option>
                                <option value="0">Add as Step</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-danger" type="button" data-dismiss="modal">Cancel</button>
                        <input class="btn btn-primary" type="submit" id="addProgSubmit" value="Done">
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
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Obtain Client Acceptance Engagement Letter
                        <h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                </div>
                <div class="modal-body">
                    <div class="container card bg-light font-2 py-2">
                        <div class="row d-flex justify-content-between">
                            <div class="col-md-6"></div>
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
                                    <div class="form-group">
                                        <label for="country">Upload Documents</label>
                                        <div class="form-group">
                                            <input type="file" class="form-control-file"
                                                accept="application/msword, application/pdf, .doc, .docx, .pdf, .txt, .rtf"
                                                name="file">
                                        </div>
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
                        <div class="modal-footer">
                            <button class="btn btn-danger" type="button" data-dismiss="modal">Cancel</button>
                            <input class="btn btn-primary" type="submit" id="subProgSubmit" value="Done">
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"
        integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA=="
        crossorigin="anonymous"></script>

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
        $(document).on('click', '.buttonActive', function(){
            var id =$(this).attr('id');
            $.ajax({
                url: "updateActive.php",
                type: "POST",
                data: {
                    prog_id: id,
                    wid: <?php echo $wid; ?>
                },
                success: function(response) {
                    var obj = JSON.parse(response);
                    if (obj.status) {
                        swal({
                            icon: "success",
                            text: obj.text,
                        }).then(function(isConfirm) {
                            if (isConfirm) {
                                window.location.href = window.location
                                    .pathname +
                                    "?pid=<?php echo $prog_id; ?>&parent_id=<?php echo $prog_parentId; ?>&wid=<?php echo $wid; ?>";
                            }
                        });
                    } else {
                        swal({
                            icon: "error",
                            text: obj.text,
                        }).then(function(isConfirm) {
                            if (isConfirm) {
                                window.location.href = window.location
                                    .pathname +
                                    "?pid=<?php echo $prog_id; ?>&parent_id=<?php echo $prog_parentId; ?>&wid=<?php echo $wid; ?>";
                            }
                        });
                    }
                }
            });   
        }); 

        $('#addProgSubmit').on('click', function(e) {
            e.preventDefault();
            var prog_name = $("#prog_name").val();
            var prog_type = $("#prog_type").val();
            $.ajax({
                url: "addProg.php",
                type: "POST",
                data: {
                    prog_id: <?php echo $prog_id; ?>,
                    wid: <?php echo $wid; ?>,
                    name: prog_name,
                    type: prog_type
                },
                success: function(response) {
                    if (response) {
                        swal({
                            icon: "success",
                            text: prog_name + " Added",
                        }).then(function(isConfirm) {
                            if (isConfirm) {
                                window.location.href = window.location
                                    .pathname +
                                    "?pid=<?php echo $prog_id; ?>&parent_id=<?php echo $prog_parentId; ?>&wid=<?php echo $wid; ?>";
                            }
                        });
                    } else {
                        swal({
                            icon: "error",
                            text: "Failed!",
                        }).then(function(isConfirm) {
                            if (isConfirm) {
                                window.location.href = window.location
                                    .pathname +
                                    "?pid=<?php echo $prog_id; ?>&parent_id=<?php echo $prog_parentId; ?>&wid=<?php echo $wid; ?>";
                            }
                        });
                    }
                }
            });
        });

    });
    </script>
</body>