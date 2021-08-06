<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    include 'dbconnection.php';
    include 'moneyFormatter.php';
    include 'decimal2point.php';
    session_start();

    if (!isset($_SESSION['email']) && empty($_SESSION['email'])) {
        header("Location: login");
    }

    if (isset($_SESSION['logged_in_date']) && !empty($_SESSION['logged_in_date'])){
        $currentDate = date_create(date("Y-m-d H:i:s",strtotime(date_format(date_create("now", new DateTimeZone('Asia/Kolkata')), "Y-m-d H:i:s"))));
        $loggedInDate = date_create(date("Y-m-d H:i:s",strtotime($_SESSION['logged_in_date'])));
        $diff=date_diff($currentDate,$loggedInDate);
		if($diff->format("%a") > 1 || $diff->format("%m") > 1 || $diff->format("%y") > 1){
			header('Location: logout');
		}
	}
    if (isset($_SESSION['client_id']) && !empty($_SESSION['client_id'])) 
        $clientId = $_SESSION['client_id'];

    $wid = base64_decode($_GET['wid']);
    if($con->query("select * from workspace where id = $wid and client_id = $clientId")->num_rows == 0){
        header('Location: ./');
    }

    if($con->query("select name from client where id = ".$clientId)->num_rows > 0){
        $clientName = $con->query("select name from client where id = ".$clientId)->fetch_assoc()['name'];
    }

    if($con->query("select name from client where id = ".$clientId)->num_rows == 0){
        header('Location: ./');
    }
    
    $prog_id = base64_decode($_GET['pid']);
    if(isset($_GET['parent_id']))
        $prog_parentId = base64_decode($_GET['parent_id']);
    if(isset($_SESSION['breadcrumb']))
        $bread = $_SESSION['breadcrumb'];
    $tmp = array();
    $flag = 0;
    include 'performanceChildCheck.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title> <?php if (!isset($_SESSION['name']) && empty($_SESSION['name'])) echo strtoupper($_SESSION['name'] . " Dashboard"); ?> </title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Custom stylesheet-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
            rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="css/custom.css" rel="stylesheet">
    <link href="css/uiux.css" rel="stylesheet" type="text/css">

    <!-- bootstrap cdn -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/jstree.min.js"></script>

    <link href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css" rel="stylesheet">
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>

    <!-- sweetalert cdn -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous"></script>
    <link href="node_modules/froala-editor/css/froala_editor.pkgd.min.css" rel="stylesheet" type="text/css" />
    <link href="node_modules/froala-editor/css/froala_style.min.css" rel="stylesheet" type="text/css" />

    <script src="http://cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js"></script> 


    <!-- <script src='//cdn.appdynamics.com/adrum/adrum-latest.js' type='text/javascript' charset='UTF-8'></script> -->

    <style>
        .tableFixHead {
            overflow-y: auto;
            max-height: 700px; 
        }
        .tableFixHead table { 
            width: 100vw;
        }
        .tableFixHead  td { 
            padding: 8px 16px; 
            word-break: keep-all;
        }
        .tableFixHead  th { 
            padding: 8px 16px; 
            white-space: nowrap;
            position: sticky;
            top: 0;
            background:#fff;
            box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.4); 
        }

        .even td, .odd td{
            text-align: left;
        }

        input[type="text"], input[type="number"]{
            height: 2.4rem !important;
            background-color: rgba(232, 240, 255, 1) !important;
            border: 0 !important;
            background-clip: padding-box;
            border-radius: 0.35rem;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }
    </style>

    <!-- Loader Css -->
    <style>
        .stop-scrolling {
            height: 100%;
            overflow: hidden;
        }
        #loader {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            width: 100%;
            background: rgba(0,0,0,0.25);
            z-index: 99999;
        }
        .load{position:absolute;top:50%;left:50%;transform:translate(-50%, -50%);
        /*change these sizes to fit into your project*/
        width:100px;
        height:100px;
        }
        .load hr{border:0;margin:0;width:40%;height:40%;position:absolute;border-radius:50%;animation:spin 2s ease infinite}
        .load :first-child{background:#19A68C;animation-delay:-1.5s}
        .load :nth-child(2){background:#F63D3A;animation-delay:-1s}
        .load :nth-child(3){background:#FDA543;animation-delay:-0.5s}
        .load :last-child{background:#193B48}
        @keyframes spin{
        0%,100%{transform:translate(0)}
        25%{transform:translate(160%)}
        50%{transform:translate(160%, 160%)}
        75%{transform:translate(0, 160%)}
        }
    </style>

</head>

<body style="overflow-y: scroll;" oncontextmenu="return false">

    <!-- SideBar -->
    <div class="sidenav">
        <div class="side-header">
            <!-- <div style="border-bottom:1px solid;"> -->
            <div>
                <a href="<?php 
                    if(isset($_SESSION['external_client_id']) && $_SESSION['external_client_id'] == '') 
                        echo "admin/clientList"; 
                    else 
                        echo "workspace?qid=".base64_encode(md5(time()))."&gid=".base64_encode(md5(time()))."&fid=".base64_encode(md5(time()))."&eid=".base64_encode(md5(time()))."&pid=".base64_encode(md5(time()))."&cid=".base64_encode($clientId)."&bid=".base64_encode(md5(time()))."&aid=".base64_encode(md5(time()))."&parent_id=".base64_encode(md5(time()))."&zid=".base64_encode(md5(time()))."&yid=".base64_encode(md5(time()))."&wid=".base64_encode($wid)."&xid=".base64_encode(md5(time()));
                    ?>">
                    <!-- <img class="sidenav-icon" src="Icons/Group-1.png"/> &nbsp; -->
                    <img class="sidenav-icon" src="Icons/Group-1.png"/> &nbsp;
                </a>
            </div>
        </div>
        <div class="side-footer">
            <div class="side-body">
                <div class="dash">
                <?php
                    if(isset($_SESSION['role']) && $_SESSION['role'] != 5){
                        ?>
                    <a href="clientDashboard?<?php echo base64_encode(md5(time())); ?>&gid=<?php echo base64_encode(md5(time())); ?>&fid=<?php echo base64_encode(md5(time())); ?>&eid=<?php echo base64_encode(md5(time())); ?>&pid=<?php echo base64_encode(md5(time())); ?>&cid=<?php echo base64_encode($clientId); ?>&bid=<?php echo base64_encode(md5(time())); ?>&aid=<?php echo base64_encode(md5(time())); ?>&parent_id=<?php echo base64_encode(md5(time())); ?>&zid=<?php echo base64_encode(md5(time())); ?>&yid=<?php echo base64_encode(md5(time())); ?>&wid=<?php echo base64_encode($wid); ?>&xid=<?php echo base64_encode(md5(time())); ?>"><img class="sidenav-icon" src="Icons/pie-chart.svg" style="width:24px !important; height:24px !important;"/> &nbsp;
                    Dashboard
                    </a>
                    <?php
                    }
                    else{
                        ?>
                        <a href="workspace?<?php echo base64_encode(md5(time())); ?>&gid=<?php echo base64_encode(md5(time())); ?>&fid=<?php echo base64_encode(md5(time())); ?>&eid=<?php echo base64_encode(md5(time())); ?>&pid=<?php echo base64_encode(md5(time())); ?>&cid=<?php echo base64_encode($clientId); ?>&bid=<?php echo base64_encode(md5(time())); ?>&aid=<?php echo base64_encode(md5(time())); ?>&parent_id=<?php echo base64_encode(md5(time())); ?>&zid=<?php echo base64_encode(md5(time())); ?>&yid=<?php echo base64_encode(md5(time())); ?>&wid=<?php echo base64_encode($wid); ?>&xid=<?php echo base64_encode(md5(time())); ?>"><img class="sidenav-icon" src="Icons/pie-chart.svg" style="width:24px !important; height:24px !important;"/> &nbsp;
                        Workspace
                        </a>
                        <?php
                    }
                    ?>
                </div>
                <?php
                    if(isset($_SESSION['external']) && $_SESSION['external']== 0){
                        $query = "select program.* from program inner join workspace_log on program.id=workspace_log.program_id where program.parent_id='0' and workspace_log.workspace_id='$wid' order by _seq";
                        $exquery = $con->query($query);
                        if ($exquery->num_rows != 0) {
                            while ($queryrow = $exquery->fetch_assoc()) {
                                if ($queryrow['hasChild'] == 1) {
                                    ?>
                                        <div class="sub-dash d-flex justify-content-between align-items-center" id="employees" style="margin-top: 1rem !important;">
                                            <a href="subProgram?<?php echo base64_encode(md5(time())); ?>&gid=<?php echo base64_encode(md5(time())); ?>&fid=<?php echo base64_encode(md5(time())); ?>&eid=<?php echo base64_encode(md5(time())); ?>&pid=<?php echo base64_encode($queryrow['id']); ?>&cid=<?php echo base64_encode(md5(time())); ?>&bid=<?php echo base64_encode(md5(time())); ?>&aid=<?php echo base64_encode(md5(time())); ?>&parent_id=<?php echo base64_encode($queryrow['parent_id']); ?>&zid=<?php echo base64_encode(md5(time())); ?>&yid=<?php echo base64_encode(md5(time())); ?>&wid=<?php echo base64_encode($wid); ?>&xid=<?php echo base64_encode(md5(time())); ?>">
                                                <?php echo trim($queryrow['program_name']); ?>
                                            </a>
                                            <?php
                                             if($prog_id == 1){
                                                 echo "<label class=' mt-2'><span class='helpDesign help_1'>1</span></label>";
                                             }
                                             if($prog_id == 254 || $prog_id == 255 || $prog_id == 256 || $prog_id == 257 || $prog_id == 247 || $prog_id == 262 || $prog_id == 266 || $prog_id == 19){
                                                echo "<label class=' mt-2'><span class='helpDesign help_6'>6</span></label>";
                                            }
                                            elseif($prog_id == 245){
                                                echo "<label class=' mt-2'><span class='helpDesign help_5'>5</span></label>";
                                            }
                                            elseif($prog_id == 12 || $prog_id == 2){
                                                echo "<label class=' mt-2'><span class='helpDesign help_3'>3</span></label>";
                                            }
                                            elseif($prog_id == 230){
                                                echo "<label class=' mt-2'><span class='helpDesign help_7'>7</span></label>";
                                            }
                                            elseif($prog_id == 239 || $prog_id == 240){
                                                echo "<label class=' mt-2'><span class='helpDesign help_8'>8</span></label>";
                                            }
                                             ?>
                                        </div>
                                        <?php
                                }
                            }
                        }
                    }
                ?>
            </div>
            <div class="settings">
            <?php
                    if($_SESSION['role'] != 5){
                        ?>
                <div class="settings-items-top-div">
                    <div class="settings-items d-flex justify-content-between align-items-center">
                        <a href="settings" class="text-decoration-none">
                            <img class="sidenav-icon" src="Icons/settings.svg" style="width:24px !important; height:24px !important;"/> &nbsp;Settings
                        </a>
                        <label class="d-flex justify-content-center align-items-center mt-2">
                        <?php
                            if($prog_id == 1){
                                echo "<span class='helpDesign help_6'>6</span>";
                            }
                            elseif($prog_id == 254 || $prog_id == 255 || $prog_id == 256 || $prog_id == 257 || $prog_id == 247 || $prog_id == 262 || $prog_id == 266 || $prog_id == 19){
                                echo "<span class='helpDesign help_8'>8</span>";
                            }
                            elseif($prog_id == 12 || $prog_id == 2){
                                echo "<span class='helpDesign help_5'>5</span>";
                            }
                            elseif($prog_id == 230){
                                echo "<span class='helpDesign help_9'>9</span>";
                            }
                            elseif($prog_id == 239 || $prog_id == 240){
                                echo "<span class='helpDesign help_10'>10</span>";
                            }
                            elseif($prog_id == 245){
                                echo "<span class='helpDesign help_7'>7</span>";
                            }
                        ?></label>
                    </div>
                    <?php
                    if($prog_id == 1 || $prog_id == 2 || $prog_id == 12 || $prog_id == 19 || $prog_id == 230 || $prog_id == 239 || $prog_id == 240 || $prog_id == 245 || $prog_id == 247 || $prog_id == 254 || $prog_id == 255 || $prog_id == 256 || $prog_id == 257 || $prog_id == 262 || $prog_id == 266){
                    ?>
                    <div id="helpButton" class="settings-items">
                        <a href="#" class="text-decoration-none"><img class="sidenav-icon" src="Icons/help-circle.svg" style="width:24px !important; height:24px !important;"/> &nbsp;
                        Help</a>
                    </div>
                    <?php
                    }
                    ?>
                </div>
                <?php
                    }
                    ?>
                <div class="d-flex justify-content-between align-items-center">
                    <a href="logout"><button type="button" class="btn btn-primary"><i class="fas fa-sign-out-alt"></i> Logout</button></a>
                    <label class="d-flex justify-content-center align-items-center mt-2">
                    <?php 
                        if($prog_id == 1){
                            echo "<span class='helpDesign help_7'>7</span>";
                        }
                        elseif($prog_id == 254 || $prog_id == 255 || $prog_id == 256 || $prog_id == 257 || $prog_id == 247 || $prog_id == 262 || $prog_id == 266 || $prog_id == 19){
                           if(isset($_SESSION['role']) && !empty($_SESSION['role']) && $_SESSION['role'] != 5)
                            echo "<span class='helpDesign help_9'>9</span>";
                        }
                        elseif($prog_id == 12 || $prog_id == 2){
                            echo "<span class='helpDesign help_6'>6</span>";
                        }
                        elseif($prog_id == 230){
                            echo "<span class='helpDesign help_10'>10</span>";
                        }
                        elseif($prog_id == 239 || $prog_id == 240){
                            echo "<span class='helpDesign help_11'>11</span>";
                        }
                        elseif($prog_id == 245){
                            echo "<span class='helpDesign help_8'>8</span>";
                        }
                    ?></label>
                </div>
            </div>
        </div>
    </div>

    <!-- Navbar -->
    <nav class="navbar sticky-top navbar-expand-lg navbar-mainbg border-bottom">
        <!-- Topbar Navbar -->
        
        <ul class="navbar-nav ml-auto">
            <?php
                if ($prog_id != '2' && $prog_id != '20' && $prog_id != '230' && $prog_id != '229' && $prog_id != '12' && $prog_id != '239' && $prog_id != '240' && $prog_id != '247' && $prog_id != '496' && $prog_id != '258' && $prog_id != '8' && $prog_id != '259' && $prog_id != '24' && $prog_id != '245') {
                    if($prog_id == 1 || $prog_id == 254 || $prog_id == 255 || $prog_id == 256 || $prog_id == 257 || $prog_id == 247 || $prog_id == 262 || $prog_id == 266 || $prog_id == 19){
                        echo "<label class='d-flex justify-content-center align-items-center mt-2'><span class='helpDesign help_4'>4</span></label>";
                    } 
                    elseif($prog_id == 245){
                        echo "<label class='d-flex justify-content-center align-items-center mt-2'><span class='helpDesign help_3'>3</span></label>";
                    }
                    if($prog_id != '245'){
                        ?>
                            <li class="nav-item d-flex">
                                <a class="nav-link d-flex align-items-center" href="#" data-toggle="modal"
                                    data-target="#addProgModal">
                                    <img class="nav-icon" src="Icons/plus-circle-1.svg" style="height:35px; width:35px;"/>&nbsp;&nbsp;
                                    <span>Add Programme</span>
                                </a>
                            </li>
                        <?php
                    } 
                }
            ?>
            <!-- Dropdown -->
            <li class="nav-item d-flex" style="background-color: rgba(232,240,255,1); border-radius: 15px; padding: 8px !important;">
                <?php
                    if($prog_id == 2 || $prog_id == 12){
                        ?>
                        <label class='d-flex justify-content-center align-items-center mt-2'><span class='helpDesign help_2'>2</span></label>
                        <?php
                    }
                    elseif($prog_id == 240 || $prog_id == 239){
                        ?>
                        <label class='d-flex justify-content-center align-items-center mt-2'><span class='helpDesign help_7'>7</span></label>
                        <?php
                    }
                    elseif($prog_id == 245){
                        echo "<label class='d-flex justify-content-center align-items-center mt-2'><span class='helpDesign help_4'>4</span></label>";
                    }
                    elseif($prog_id == 230){
                        echo "<label class='d-flex justify-content-center align-items-center mt-2'><span class='helpDesign help_6'>6</span></label>";
                    }
                    elseif($prog_id == 1 || $prog_id == 254 || $prog_id == 255 || $prog_id == 256 || $prog_id == 257 || $prog_id == 247 || $prog_id == 262 || $prog_id == 266 || $prog_id == 19){
                        if(isset($_SESSION['role']) && !empty($_SESSION['role']) && $_SESSION['role'] != 5)
                            {
                        ?>
                        <label class='d-flex justify-content-center align-items-center mt-2'><span class='helpDesign help_5'>5</span></label>
                        <?php
                            }
                    }
                ?>
                
                <span class="nav-icon d-flex align-items-center" style="padding: 0 0 0 10px !important;">
                    <?php
                        $img_query = $con->query("SELECT * FROM user WHERE id = ".$_SESSION['id']." and img != ''");
                        if($img_query->num_rows == 1){
                            $row = $img_query->fetch_assoc();
                            ?>
                            <img class = "profilePhoto" src="images/<?php echo $row['img']; ?>">
                            <?php
                        }
                        else{
                            ?>
                            <i class="fas fa-user-circle fa-2x" aria-hidden="true"></i>
                            <?php
                        }
                        
                    ?>
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
                    <?php 
                       if($_SESSION['role'] == '-1' || $_SESSION['role'] == '1'){
                        ?>
                            <a class="dropdown-item" href="deletedFiles"><i class="fas fa-trash-alt hue"></i>Deleted File Log</a>
                            <a class="dropdown-item" href="admin/activityLog"><i class="fas fa-list hue"></i>Activity Log</a>
                            <a class="dropdown-item" href="#"><i class="fas fa-user-tie hue" style="color:blue;"></i><?php echo $_SESSION['name']; ?></a>
                            <a class="dropdown-item" href="#"><i class="fas fa-signature hue" style="color:blue;"></i><?php echo $_SESSION['signoff']; ?></a>
                            <a class="dropdown-item" href="#"><i class="fas fa-at hue" style="color:blue;"></i><?php echo $_SESSION['email']; ?></a>
                        <?php
                        }
                        elseif($_SESSION['role'] == 5){
                            ?>
                            <a class="dropdown-item" href="#"><i class="fas fa-user-tie hue" style="color:blue;"></i><?php echo $_SESSION['name']; ?></a>
                            <a class="dropdown-item" href="#"><i class="fas fa-at hue" style="color:blue;"></i><?php echo $_SESSION['email']; ?></a>
                            <a class="dropdown-item" href="#"><i class="fas fa-briefcase hue" style="color:blue;"></i>Firm Name - <?php echo $_SESSION['firm_details']['firm_name']; ?></a>
                        <?php 
                        }   
                        else{
                            ?>
                            <a class="dropdown-item" href="admin/activityLog"><i class="fas fa-list hue"></i>Activity Log</a>
                            <a class="dropdown-item" href="deletedFiles"><i class="fas fa-trash-alt hue"></i>Deleted File Log</a>
                            <a class="dropdown-item" href="#"><i class="fas fa-user-tie hue" style="color:blue;"></i><?php echo $_SESSION['name']; ?></a>
                            <a class="dropdown-item" href="#"><i class="fas fa-signature hue" style="color:blue;"></i><?php echo $_SESSION['signoff']; ?></a>
                            <a class="dropdown-item" href="#"><i class="fas fa-at hue" style="color:blue;"></i><?php echo $_SESSION['email']; ?></a>
                            <a class="dropdown-item" href="#"><i class="fas fa-briefcase hue" style="color:blue;"></i>Firm Name -<?php echo $_SESSION['firm_details']['firm_name']; ?></a>
                            <?php
                        }
                    ?>
                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#photoModal"><i class="fas fa-user-circle hue" style="color:blue;"></i>Update Profile Photo</a>
                </div>
            </li>
        </ul>
    </nav>
    
    <div class="mar" <?php if($prog_id == 255 || $prog_id==230 || $prog_id==239 || $prog_id==240|| $prog_id==2|| $prog_id==19 || $prog_id==496){ echo "style='height: auto !important;'"; } ?> >
        <!-- HEADER -->
        <div id="header">
            <div class="container-fluid" stickylevel="0" style="z-index:1200;">
                <div class="row pt-1">
                    <div class="row text-center cdrow" href="#">
                        <h2><?php echo strtoupper($clientName . " - Workspace"); ?></h2>
                    </div>
                </div>
            </div>
        </div><br>
            <!-- Body Starts -->
            <div class="container-fluid prog">
                <!-- Breadcrumbs -->
                <div class="row">
                    <div class="col-md-12" style="padding-bottom: 0.1rem; border-bottom: 2px solid #e1e2e9;">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb" style="background-color:transparent;">
                            <?php
                            if($_SESSION['external'] == 0){
                                if($prog_id == 1 || $prog_id == 230){
                                    echo "<label><span class='helpDesign help_8'>8</span></label>";
                                }
                                elseif($prog_id == 254 || $prog_id == 255 || $prog_id == 256 || $prog_id == 247 || $prog_id == 262 || $prog_id == 266 || $prog_id == 19){
                                    echo "<label><span class='helpDesign help_7'>7</span></label>";
                                }
                                elseif($prog_id == 245){
                                    echo "<label><span class='helpDesign help_6'>6</span></label>";
                                }
                                elseif($prog_id == 12 || $prog_id == 2){
                                    echo "<label><span class='helpDesign help_4'>4</span></label>";
                                }
                                elseif($prog_id == 239 || $prog_id == 240){
                                    echo "<label><span class='helpDesign help_9'>9</span></label>";   
                                }
                                ?>
                                <li class="breadcrumb-item"><a href="clientDashboard?<?php echo base64_encode(md5(time())); ?>&gid=<?php echo base64_encode(md5(time())); ?>&fid=<?php echo base64_encode(md5(time())); ?>&eid=<?php echo base64_encode(md5(time())); ?>&pid=<?php echo base64_encode(md5(time())); ?>&cid=<?php echo base64_encode($clientId); ?>&bid=<?php echo base64_encode(md5(time())); ?>&aid=<?php echo base64_encode(md5(time())); ?>&parent_id=<?php echo base64_encode(md5(time())); ?>&zid=<?php echo base64_encode(md5(time())); ?>&yid=<?php echo base64_encode(md5(time())); ?>&wid=<?php echo base64_encode($wid); ?>&xid=<?php echo base64_encode(md5(time())); ?>">Dashboard</a>
                                </li>
                                <?php
                                
                                    if (sizeof($bread) != 0) {
                                        $y = 0;
                                        for ($x = 0; $x < sizeof($bread); $x++) {
                                            if ($bread[$x]['pid'] != $prog_id) {
                                                $tmp[$y]['pid'] = $bread[$x]['pid'];
                                                $tmp[$y]['name'] = $bread[$x]['name'];
                                                $tmp[$y++]['parent_id'] = $bread[$x]['parent_id'];
                                            } else {
                                                $tmp[$y]['pid'] = $bread[$x]['pid'];
                                                $tmp[$y]['name'] = $bread[$x]['name'];
                                                $tmp[$y++]['parent_id'] = $bread[$x]['parent_id'];
                                                $flag = 1;
                                                break;
                                            }
                                        }
                                        if (!$flag) {
                                            $i = sizeof($bread);
                                            $bread[$i]['pid'] = $prog_id;
                                            $bread[$i]['name'] = $con->query("select program_name from program where id = " . $prog_id)->fetch_assoc()['program_name'];
                                            $bread[$i++]['parent_id'] = $prog_parentId;
                                        } else {
                                            $bread = $tmp;
                                        }

                                    } elseif (sizeof($bread) == 0) {
                                        $bread[0]['pid'] = $prog_id;
                                        $bread[0]['name'] = $con->query("select program_name from program where id = " . $prog_id)->fetch_assoc()['program_name'];
                                        $bread[0]['parent_id'] = $prog_parentId;

                                    }
                                    $_SESSION['breadcrumb'] = $bread;
                                    // var_dump($bread);
                                    for ($i = 0; $i < sizeof($bread); $i++) {
                                        if ($i == sizeof($bread) - 1) {
                                            ?>
                                            <li class="breadcrumb-item font-weight-bold h5">
                                                <span><?php echo $bread[$i]['name']; ?></span>
                                            </li>
                                            <?php
                                        } else {
                                            ?>
                                            <li class="breadcrumb-item"><a href="subProgram?<?php echo base64_encode(md5(time())); ?>&gid=<?php echo base64_encode(md5(time())); ?>&fid=<?php echo base64_encode(md5(time())); ?>&eid=<?php echo base64_encode(md5(time())); ?>&pid=<?php echo base64_encode($bread[$i]['pid']); ?>&cid=<?php echo base64_encode(md5(time())); ?>&bid=<?php echo base64_encode(md5(time())); ?>&aid=<?php echo base64_encode(md5(time())); ?>&parent_id=<?php echo base64_encode($bread[$i]['parent_id']); ?>&zid=<?php echo base64_encode(md5(time())); ?>&yid=<?php echo base64_encode(md5(time())); ?>&wid=<?php echo base64_encode($wid); ?>&xid=<?php echo base64_encode(md5(time())); ?>"><?php echo $bread[$i]['name']; ?></a>
                                            </li>
                                            <?php
                                        }
                                    }
                                }
                                ?>
                            </ol>
                        </nav>
                    </div>
                </div>

                <!-- Subprogram Body -->
                <div class="row">
                    <div class="col-md-12">
                        <?php
                        if ($prog_id == 239){
                            $scopes = ($con->query("select balance_asset, balance_liability from sub_materiality where workspace_id = '$wid'"))->fetch_assoc();
                            ?>
                            <!-- Top Part -->
                            <div>
                                <div class="col-md-12 text-center p-top">
                                        <label class="mt-2"><span class="helpDesign help_5">5</span></label>
                                        <a target="_blank" href="exportAccounts?wid=<?php echo $wid; ?>&pid=239"><button class="btn bg-violet">Export</button></a>
                                </div> 
                                <hr>
                                <div class="form-row p-top">
                                    <div class="form-group col-md-6">
                                        <label for="input1">Balance Assets Scope</label>
                                        <label class="mt-2"><span class="helpDesign help_4">4</span></label>
                                        <input type="text" class="form-control" name="aScope"
                                                value="<?php echo $scopes['balance_asset']; ?>" readonly>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="input2">Balance Liability Scope</label>
                                        <label class="mt-2"><span class="helpDesign help_4">4</span></label>
                                        <input type="text" class="form-control" name="lScope"
                                                value="<?php echo $scopes['balance_liability']; ?>" readonly>
                                    </div>
                                </div>
                            
                            </div>

                            <form id="balanceSheetForm" action="accountsSubmit" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="wid" value="<?php echo $wid;?>">
                                <input type="hidden" name="pid" value="<?php echo $prog_id; ?>">
                                <?php
                                    $queryBS = $con->query("SELECT distinct accounts_type,accountTypeSeqNumber from tb_performance_map where workspace_id='$wid' and ( accounts_type not like '%Expense%' and accounts_type not like '%Revenue%' ) order by accountTypeSeqNumber");
                                    $i = 0;
                                    while($rowQueryBS = $queryBS->fetch_assoc()){
                                    ?>
                                        <table class="table table-hover">
                                            <thead>
                                                <tr class="table-secondary">
                                                    <th scope="col" hidden>Id</th>
                                                    <th scope="col"><?php echo $rowQueryBS['accounts_type']; ?> Accounts</th>
                                                    <th scope="col">Amount</th>
                                                    <th scope="col">Type</th>
                                                    <th scope="col">Risk</th>
                                                    <th scope="col">Map Accounts</th>
                                                    <th scope="col">Import</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $queryAccount = $con->query("select * from tb_performance_map where workspace_id=$wid and accounts_type='".$rowQueryBS['accounts_type']."'");
                                                    while ($rowQuery = $queryAccount->fetch_assoc()) {
                                                ?>
                                                    <tr>
                                                        <td scope="row" hidden>
                                                            <input type="hidden" name="submitData[<?php echo $i;?>][0]" value="<?php echo $rowQuery['id']; ?>">
                                                        </td>
                                                        <td scope="row" class="mt-4" style="height: 4rem !important; display: flex; align-items: center; justify-content: start; text-align:left;">
                                                            <?php echo $rowQuery['accounts_name']; ?>
                                                        </td>
                                                        <td scope="row">
                                                            <label class="mt-2"></label>
                                                            <input type="number" name="submitData[<?php echo $i;?>][1]" value="<?php echo $rowQuery['amount']; ?>" size="15" step="0.01">
                                                        </td>
                                                        <td scope="row">
                                                            <label class="mt-2"><span class="helpDesign help_1">1</span></label>
                                                            <select name="submitData[<?php echo $i;?>][2]" class="form-control"
                                                                    required>
                                                                <option <?php if ($rowQuery['type'] == 0) echo "selected"; ?>
                                                                        value="0">Significant
                                                                </option>
                                                                <option <?php if ($rowQuery['type'] == 1) echo "selected"; ?>
                                                                        value="1">Non-Significant
                                                                </option>
                                                            </select>
                                                        </td>
                                                        <td scope="row">
                                                            <label class="mt-2"><span class="helpDesign help_2">2</span></label>
                                                            <select name="submitData[<?php echo $i;?>][3]" class="form-control"required>
                                                                <option <?php if ($rowQuery['risk'] == 0) echo "selected"; ?> value="0">Low</option>
                                                                <option <?php if ($rowQuery['risk'] == 1) echo "selected"; ?> value="1">Moderate</option>
                                                                <option <?php if ($rowQuery['risk'] == 2) echo "selected"; ?> value="2">High
                                                                </option>
                                                            </select>
                                                        </td>
                                                        <td scope="row">
                                                            <label class="mt-2"><span class="helpDesign help_3">3</span></label>
                                                            <select class="form-control" name="submitData[<?php echo $i;?>][4]" required>
                                                                <option value="-1" <?php if($rowQuery['mapped_program_id'] == -1) echo "selected"; ?>>Select Account</option>
                                                                <?php
                                                                    $accountMap = $con->query("SELECT * FROM `program` WHERE parent_id = 2");
                                                                    while ($accountMapResult = $accountMap->fetch_assoc()) {
                                                                ?>
                                                                <option value="<?php echo $accountMapResult['id']; ?>" <?php if($rowQuery['mapped_program_id'] == $accountMapResult['id']) echo "selected"; ?>>
                                                                    <?php echo $accountMapResult['program_name']; ?></option>
                                                                <?php
                                                                }
                                                                ?>
                                                                <option value="0" <?php if($rowQuery['mapped_program_id'] == 0) echo "selected"; ?>>Others</option>
                                                            </select>
                                                        </td>
                                                        <td scope="row">
                                                            <label class="mt-2"><span class="helpDesign help_3">3</span></label>
                                                            <select name="submitData[<?php echo $i++;?>][5]" class="form-control" required>
                                                                <option <?php if ($rowQuery['import'] == 0) echo "selected"; ?> value="0">No</option>
                                                                <option <?php if ($rowQuery['import'] == 1) echo "selected"; ?> value="1">Yes</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                <?php 
                                                    }
                                                ?>
                                            </tbody>
                                        </table>
                                    <?php 
                                    }
                                ?>
                                <div class="row d-flex justify-content-center align-items-center">
                                    <input class="btn btn-upload" type="file" name="file" accept=".pdf, .xls, .xlsx, .txt, .csv, .doc, .docx, .rtf, .xlmb" style="width:30% !important;">
                                </div>
                                    <div class="row d-flex justify-content-center align-items-center p-top">
                                        <?php
                                        $query = "select * from insignificant_files where workspace_id='$wid' and pid='$prog_id'";
                                        $result = $con->query($query);
                                        ?>
                                        <ul class="custom-list list-bg" style="padding-bottom: 2% !important;">
                                            <span class="d-flex justify-content-center align-items-center">Uploaded Files</span>
                                            <?php 
                                            while ($row = $result->fetch_assoc()) {
                                                if($row['fname'] != ''){
                                                    $subMateriality = 1;
                                                }
                                                ?>
                                                <li class="custom-list-items custom-list-items-action"><a href="#" class="fileEditDownload" download target="_blank" id="<?php echo $row['fname']; ?>"><?php echo $row['fname']; ?></a>
                                                </li>
                                                <?php
                                            } ?>
                                        </ul>
                                    </div>
                                    <div class="row d-flex justify-content-center p-top">
                                    <?php 
                                        // if($balanceSheet0 || $balanceSheet1){
                                        //     if($con->query("select count(id) count from signoff_prepare_log where workspace_id = $wid and prog_id = '239'")->fetch_assoc()['count'] != 0){
                                        //         ?>
                                                    <!-- <input type="submit" name="reviewSubmit" class="btn btn-outline-primary" value = "Review Sign Off">&nbsp; -->
                                                    <?php
                                        //     }
                                        //     ?>
                                                <!-- <input type="submit" name="prepareSubmit" class="btn btn-outline-primary" value = "Prepare Sign Off">&nbsp; -->
                                            <?php
                                        // }
                                    ?>
                                    <input type="submit" id="validateSubmit" class="btn btn-success align-middle" value="Save Details"> 
                                </div>
                            </form>

                            <?php
                        } 
                        elseif ($prog_id == 247){
                            $query = "select a.*, b.account,b.id bid from accounts_log a INNER join accounts b on a.accounts_id=b.id where a.workspace_id='$wid'";
                            if($_SESSION['external'] == 1){
                                $query .= " and a.client_contact_id=".$_SESSION['id'];
                            }
                            $result = $con->query($query);
                            $result1 = $con->query("select c.id cid, name from user c inner join workspace w on c.client_id=w.client_id where w.id = '$wid'")->fetch_all();
                            ?>
                            <div class="row">
                            <?php 
                            if($_SESSION['external'] != 1){
                            ?>
                                <div class="col-md-12 text-center p-top">
                                    <label class="mt-2"><span class="helpDesign help_1">1</span></label>
                                    <button class="btn btn-info" data-target="#addAccount" data-toggle="modal"
                                            id="add_acc">Add Request
                                    </button>
                                    <label class="mt-2"><span class="helpDesign help_2">2</span></label>
                                    <a target="_blank" href="exportRequestClient?wid=<?php echo $wid; ?>"><button class="btn btn-success">Export</button></a>
                                </div>
                                <?php } ?>
                            </div><br>
                            <div class="row">    
                                <div class="tableFixHead">
                                    <form style="overflow-x:auto;" action="clientAssistSubmit?&wid=<?php echo $wid; ?>" method="post" enctype="multipart/form-data">
                                        <div class="row" style="margin: 0 !important;">    
                                            <div>
                                                <table>
                                                    <thead class="text-center">
                                                    <tr>
                                                    <?php 
                                                        if($_SESSION['external'] != 1){
                                                        ?>
                                                        <th scope="col">Status</th>
                                                        <?php
                                                        } 
                                                        ?>
                                                        <th scope="col">Account Name</th>
                                                        <th scope="col" hidden>Id</th>
                                                        <th scope="col" class="col-md-1">Description</th>
                                                        <?php 
                                                        if($_SESSION['external'] != 1){
                                                        ?>
                                                        <th scope="col">Client Assign</th>
                                                        <?php } ?>
                                                        <?php 
                                                        if($_SESSION['external'] == 1){
                                                            ?>
                                                            <th scope="col">File to Upload</th>
                                                            <?php
                                                        }
                                                        ?>
                                                        <th scope="col">Documents Uploaded</th>
                                                        <th scope="col">Requested By</th>
                                                        <th scope="col">Date Requested</th>
                                                        <?php 
                                                        if($_SESSION['external'] != 1){
                                                        ?>
                                                        <th scope="col">Action</th>
                                                        <?php
                                                        }
                                                        ?>
                                                    </tr>
                                                    </thead>
                                                    <tbody id="abody">
                                                        <?php
                                                            while ($row = $result->fetch_assoc()) {
                                                                $query1 = $con->query("select id,client_contact_id,mail_send from accounts_log where workspace_id = '$wid' and id = '".$row['id']."'")->fetch_assoc();
                                                                ?>
                                                                <tr>
                                                                    <?php
                                                                        if(isset($_SESSION['external']) && $_SESSION['external'] != 1){
                                                                    ?>
                                                                    <td>
                                                                        <?php
                                                                            if($row['mail_send'] == 1){
                                                                                ?>
                                                                                <span class="badge badge-success">Saved & Sent</span>
                                                                            <?php
                                                                            }
                                                                            else{
                                                                                ?>
                                                                                <span class="badge badge-primary">Saved</span>
                                                                            <?php    
                                                                            }
                                                                        ?>
                                                                    </td>
                                                                    <?php 
                                                                    }
                                                                    ?>
                                                                    <td><label><?php echo $row['account']; ?></label></td>
                                                                    <td scope="row" hidden>
                                                                        <input type="hidden" name="account[id][]"
                                                                            value="<?php echo $query1['id']; ?>">
                                                                    </td>
                                                                    <td><textarea rows="3" class="form-control mb-3 minWidth250" <?php if($_SESSION['external'] == 1) echo "readonly"; ?> name="account[des][]"><?php echo $row['description']; ?></textarea></td>
                                                                    <?php 
                                                                        if(isset($_SESSION['external']) && $_SESSION['external'] != 1){
                                                                        ?>
                                                                    <td>
                                                                        <select class="form-control" name="account[client][]" required>
                                                                            
                                                                            <option>Select Person</option>
                                                                            <?php 
                                                                                foreach($result1 as $key => $value){
                                                                            ?>
                                                                            <option value="<?php echo $value[0]; ?>" <?php if($query1['client_contact_id'] == $value[0]) {echo "Selected";} ?>> 
                                                                            <?php echo $value[1]; ?>
                                                                            </option>
                                                                            <?php } ?>
                                                                        </select>
                                                                    </td>
                                                                        <?php } ?>
                                                                    <?php
                                                                    if(isset($_SESSION['external']) && $_SESSION['external'] == 1){
                                                                        ?>
                                                                    <td>
                                                                        <input type="file" name="file[<?php echo $query1['id']; ?>][]" accept=".pdf, .xls, .xlsx, .txt, .csv, .doc, .docx, .rtf, .xlmb" multiple>
                                                                    </td>
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                    <td>
                                                                        <?php 
                                                                            $count = 1;
                                                                            $documentResult = $con->query("select documents from accounts_log_docs where accounts_log_id =".$query1['id']);
                                                                            while($documentResultRow = $documentResult->fetch_assoc())
                                                                                echo "<label style='white-space:nowrap;'>".$count++.":- <a  href='#' class='fileEditDownload' target='_blank' id='".$documentResultRow['documents']."'>".$documentResultRow['documents']."</a></label><br>";
                                                                        ?> 
                                                                    </td>
                                                                    <td><input class="form-control" <?php if(isset($_SESSION['external']) && $_SESSION['external'] == 1) echo "readonly"; ?> type="text" size="10" name="account[request][]"
                                                                            value="<?php echo $row['request']; ?>"></td>
                                                                    <td><input class="form-control" <?php if(isset($_SESSION['external']) && $_SESSION['external'] == 1) echo "readonly"; ?> type="date" size="10" name="account[date][]"
                                                                            value="<?php echo $row['date']; ?>">
                                                                    </td>
                                                                    <?php 
                                                                    if(isset($_SESSION['external']) && $_SESSION['external'] != 1){
                                                                        ?>
                                                                    <td><a href="#" id="<?php echo $row['id']; ?>" class="deleteAcc">
                                                                            <i class="fas fa-times-circle"
                                                                            style="color:red !important;"></i>
                                                                        </a>
                                                                    </td>
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                </tr>
                                                                <?php
                                                            }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <br>
                                        <?php 
                                            if(isset($_SESSION['external']) && $_SESSION['external'] != 1){
                                                ?>
                                                <div class="col-md-12 row d-flex justify-content-center">
                                                    <i class="fas fa-info-circle" style="color:orange !important;"></i>
                                                    <strong>Click save button to save respective changes before clicking send request.</strong>
                                                </div>
                                                <hr>
                                                <?php } ?>
                                        <div class="row d-flex justify-content-center p-bottom">
                                            <?php
                                                if(isset($_SESSION['role']) && !empty($_SESSION['role']) && $_SESSION['role'] != 5)
                                                {
                                            ?>
                                                <label class="mt-2"><span class="helpDesign help_3">3</span></label>
                                            <?php 
                                                } 
                                            ?>
                                            <input type="submit" class="btn btn-success align-middle" value="Save"> &nbsp;
                                            <?php 
                                            if(isset($_SESSION['external']) && $_SESSION['external'] != 1){
                                                ?>
                                                <label class="mt-2"><span class="helpDesign help_4">4</span></label>
                                                <input id="sendInvitation" type="button" class="btn bg-violet align-middle" value="Send Request">
                                            <?php } ?>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <?php 
                        }
                        elseif ($prog_id == 245){
                            $result = $con->query("SELECT count(id) total from trial_balance where workspace_id='".$wid."'");
                            if($result->fetch_assoc()['total'] == 0){
                                ?>
                                    <div class="col-md-12 text-center p-top">
                                        <label class="mt-2"><span class="helpDesign help_1">1</span></label>
                                        <button class="btn btn-success" data-target="#addExcelModal" data-toggle="modal">Upload Excel</button>
                                        <label class="mt-2"><span class="helpDesign help_2">2</span></label>
                                        <a class="btn bg-violet" target="_blank" href="tbTemplate">
                                            <span>Download Template</span>
                                        </a>
                                        <!-- <a href="assets/TB_Template.xlsx"><button class="btn bg-violet" download="Trial Balance Template.xlsx">Download Template</button></a> -->
                                        <!-- <form method="get" action="tbTemplate">
                                            <button type="submit" class="btn bg-violet">Download Template</button>
                                        </form> -->
                                    </div>
                                    <script>
                                         swal({
                                            title: "Download the Excel Template",
                                            text: "No Trial Balance was there, so download the excel and then upload to that.",
                                            icon: "warning",
                                            button: "Download",
                                            dangerMode: true,
                                        }).then((willOUT) => {
                                            if (willOUT) {
                                                window.open("tbTemplate");
                                            }
                                        });
                                    </script>
                                <?php
                            }
                            else{
                            ?>
                                <div class="col-md-12 text-center p-top d-flex justify-content-center">
                                    <button class="btn btn-success" data-target="#addExcelModal" data-toggle="modal">Upload Excel</button>&nbsp;
                                    <a class="btn bg-violet" target="_blank" href="tbTemplate">
                                        <span>Download Template</span>
                                    </a>
                                    <!-- <form method="get" action="tbTemplate">
                                        <button type="submit" class="btn bg-violet">Download Template</button>
                                    </form> -->
                                    <!-- <a href="financialStatement?wid=<?php //echo $wid; ?>"><button class="btn bg-violet" style="color: white !important;">Lead Sheet Generator</button></a> -->
                                </div>
                                <div class="container">
                                    <div class="row">
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4">
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <table id="trialBalanceTable" class="table display table-bordered table-striped">
                                                                <thead>
                                                                    <tr>
                                                                        <!-- <th scope="col">Sl</th> -->
                                                                        <th scope="col">Account Number</th>
                                                                        <th scope="col">Account Name</th>
                                                                        <th scope="col">CY Begining Balance (PY)</th>
                                                                        <!-- <th scope="col">CY Interim Balance</th>
                                                                        <th scope="col">CY Activity (Movement)</th>
                                                                        <th scope="col">CY End Balance</th>
                                                                        <th scope="col">Client Adujstment</th>
                                                                        <th scope="col">Audit Adjustment</th> -->
                                                                        <th scope="col">CY Final Balance</th>
                                                                        <th scope="col">Account Type</th>
                                                                        <th scope="col">Account Class</th>
                                                                        <th scope="col">Financial Statement</th>
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
                            <?php 
                            }
                        }
                        elseif ($prog_id == 240) {
                            $rowScopes = ($con->query("select pl_income, pl_expense from sub_materiality where workspace_id = '$wid'"))->fetch_assoc();
                            ?>
                            <!-- Top Part -->
                            <div>
                                <div class="col-md-12 text-center p-top">
                                        <label class="mt-2"><span class="helpDesign help_5">5</span></label>
                                        <a target="_blank" href="exportAccounts?wid=<?php echo $wid; ?>&pid=240"><button class="btn bg-violet">Export</button></a>
                                </div> <hr>
                                <div class="form-row p-top">
                                    <div class="form-group col-md-6">
                                        <label for="input1">PL- Income Scope</label>
                                        <label class="mt-2"><span class="helpDesign help_4">4</span></label>
                                        <input type="text" class="form-control" name="aScope"
                                                value="<?php echo $rowScopes['pl_income']; ?>" readonly>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="input2">PL- Expense Scope</label>
                                        <label class="mt-2"><span class="helpDesign help_4">4</span></label>
                                        <input type="text" class="form-control" name="lScope"
                                                value="<?php echo $rowScopes['pl_expense']; ?>" readonly>
                                    </div>
                                </div>
                            </div>


                            <form id="balanceSheetForm" action="accountsSubmit" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="wid" value="<?php echo $wid;?>">
                                <input type="hidden" name="pid" value="<?php echo $prog_id; ?>">
                                <?php
                                    $queryPL = $con->query("SELECT distinct accounts_type,accountTypeSeqNumber from tb_performance_map where workspace_id='$wid' and ( accounts_type like '%Expense%' or accounts_type like '%Revenue%' ) order by accountTypeSeqNumber");
                                    $i = 0;
                                    while($rowQueryPL = $queryPL->fetch_assoc()){
                                    ?>
                                        <table class="table table-hover">
                                            <thead>
                                                <tr class="table-secondary">
                                                    <th scope="col" hidden>Id</th>
                                                    <th scope="col"><?php echo $rowQueryPL['accounts_type']; ?> Accounts</th>
                                                    <th scope="col">Amount</th>
                                                    <th scope="col">Type</th>
                                                    <th scope="col">Risk</th>
                                                    <th scope="col">Map Accounts</th>
                                                    <th scope="col">Import</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    $queryAccount = $con->query("select * from tb_performance_map where workspace_id=$wid and accounts_type='".$rowQueryPL['accounts_type']."'");
                                                    while ($rowQuery = $queryAccount->fetch_assoc()) {
                                                ?>
                                                        <tr>
                                                            <td scope="row" hidden>
                                                                <input type="hidden" name="submitData[<?php echo $i;?>][0]" value="<?php echo $rowQuery['id']; ?>">
                                                            </td>
                                                            <td scope="row" class="mt-4" style="height: 4rem !important; display: flex; align-items: center; justify-content: start;"><?php echo $rowQuery['accounts_name']; ?></td>
                                                            <td scope="row"><input type="number" name="submitData[<?php echo $i;?>][1]" value="<?php echo $rowQuery['amount']; ?>" size="15" step="0.01"></td>
                                                            <td scope="row">
                                                                <label class="mt-2"><span class="helpDesign help_1">1</span></label>
                                                                <select name="submitData[<?php echo $i;?>][2]" class="form-control"
                                                                        required>
                                                                    <option <?php if ($rowQuery['type'] == 0) echo "selected"; ?>
                                                                            value="0">Significant
                                                                    </option>
                                                                    <option <?php if ($rowQuery['type'] == 1) echo "selected"; ?>
                                                                            value="1">Non-Significant
                                                                    </option>
                                                                </select>
                                                            </td>
                                                            <td scope="row">
                                                                <label class="mt-2"><span class="helpDesign help_2">2</span></label>
                                                                <select name="submitData[<?php echo $i;?>][3]" class="form-control"required>
                                                                    <option <?php if ($rowQuery['risk'] == 0) echo "selected"; ?> value="0">Low</option>
                                                                    <option <?php if ($rowQuery['risk'] == 1) echo "selected"; ?> value="1">Moderate</option>
                                                                    <option <?php if ($rowQuery['risk'] == 2) echo "selected"; ?> value="2">High
                                                                    </option>
                                                                </select>
                                                            </td>
                                                            <td scope="row">
                                                                <label class="mt-2"><span class="helpDesign help_3">3</span></label>
                                                                <select class="form-control" style="min-width: 100px !important;" name="submitData[<?php echo $i;?>][4]" required>
                                                                    <option value = "-1" <?php if($rowQuery['mapped_program_id'] == -1) echo "selected"; ?>>Select Account</option>
                                                                    <?php
                                                                        $accountMap = $con->query("SELECT * FROM `program` WHERE parent_id = 2");
                                                                        while ($accountMapResult = $accountMap->fetch_assoc()) {
                                                                    ?>
                                                                    <option value="<?php echo $accountMapResult['id']; ?>" <?php if($rowQuery['mapped_program_id'] == $accountMapResult['id']) echo "selected"; ?>>
                                                                        <?php echo $accountMapResult['program_name']; ?></option>
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                    <option value="0" <?php if($rowQuery['mapped_program_id'] == 0) echo "selected"; ?>>Others</option>
                                                                </select>
                                                            </td>
                                                            <td scope="row">
                                                                <label class="mt-2"><span class="helpDesign help_3">3</span></label>
                                                                <select name="submitData[<?php echo $i++;?>][5]" class="form-control" required>
                                                                    <option <?php if ($rowQuery['import'] == 0) echo "selected"; ?> value="0">No</option>
                                                                    <option <?php if ($rowQuery['import'] == 1) echo "selected"; ?> value="1">Yes</option>
                                                                </select>
                                                            </td>
                                                        </tr>
                                                    <?php
                                                    }
                                                    ?>
                                            </tbody>
                                        </table>
                                    <?php
                                    }
                                    ?>
                                <div class="row d-flex justify-content-center align-items-center">
                                    <input class="btn btn-upload" type="file" name="file" accept=".pdf, .xls, .xlsx, .txt, .csv, .doc, .docx, .rtf, .xlmb" style="width:30% !important;">
                                </div>
                                <div class="row d-flex justify-content-center align-items-center p-top">
                                    <?php
                                    $query = "select * from insignificant_files where workspace_id='$wid' and pid='$prog_id'";
                                    $result = $con->query($query);
                                    ?>
                                    <ul class="custom-list list-bg" style="padding-bottom: 2% !important;">
                                        <span class="d-flex justify-content-center align-items-center">Uploaded Files</span>
                                        <?php 
                                        while ($row = $result->fetch_assoc()) {
                                            if($row['fname'] != ''){
                                                $subMateriality = 1;
                                            }
                                            ?>
                                            <li class="custom-list-items custom-list-items-action"><a href="#" class="fileEditDownload" download target="_blank" id="<?php echo $row['fname']; ?>"><?php echo $row['fname']; ?></a>
                                            </li>
                                            <?php
                                        } ?>
                                    </ul>
                                </div>
                                <div class="row d-flex justify-content-center">
                                    <input type="submit" class="btn btn-success align-middle" value="Submit">
                                </div>
                            </form>
                            <?php
                        } 
                        elseif ($prog_id == 230) {
                            $query = "select * from materiality where workspace_id='$wid' and prog_id='$prog_id'";
                            $result = $con->query($query); ?>
                            <div class="row">
                                <div class="col-md-12 text-center p-top">
                                    <label><span class='helpDesign help_1'>1</span></label>
                                    <button class="btn bg-violet" data-target="#addMethod" data-toggle="modal"
                                            id="add_new">ADD NEW
                                    </button>
                                </div>
                            </div><br>
                            <form action="materialitySubmit?&wid=<?php echo $wid; ?>" method="post"
                                    enctype="multipart/form-data">
                                <table class="table table-hover" id="tab_logic">
                                    <thead class="text-center">
                                    <tr>
                                        <th scope="col" style="border-bottom-left-radius: 0 !important;">Basis</th>
                                        <th scope="col" hidden>Id</th>
                                        <th scope="col" colspan="2">Standard %</th>
                                        <th scope="col">Custom %</th>
                                        <th scope="col">Amount</th>
                                        <th scope="col" style="border-bottom-right-radius: 0 !important;">Action</th>
                                    </tr>
                                    <tr>
                                        <th style="border-top-left-radius: 0 !important;"></th>
                                        <th hidden></th>
                                        <th>High</th>
                                        <th>Low</th>
                                        <th></th>
                                        <th></th>
                                        <th style="border-top-right-radius: 0 !important;"></th>
                                    </tr>
                                    </thead>
                                    <tbody id="abody">
                                    <?php
                                    $materiality = $subMateriality = 0;
                                        while ($row = $result->fetch_assoc()) {
                                            if($row['standard_low'] != '' || $row['standard_high'] != '' || $row['custom'] != '' || $row['amount'] != ''){
                                                $materiality = 1;
                                            }
                                            ?>
                                            <tr>
                                                <td style="height: 4rem !important; display: flex; align-items: left; justify-content: left"><label><?php echo $row['name']; ?></label></td>
                                                <td scope="row" hidden>
                                                    <input type="hidden" name="materialityData[id][]"
                                                            value="<?php echo $row['id']; ?>">
                                                </td>
                                                <td><input type="text" size="10" name="materialityData[sLow][]"
                                                            value="<?php echo $row['standard_low']; ?>"></td>
                                                <td><input type="text" size="10" name="materialityData[sHigh][]"
                                                            value="<?php echo $row['standard_high']; ?>"></td>
                                                <td><input type="text" size="10" name="materialityData[cLow][]"
                                                            value="<?php echo $row['custom']; ?>"></td>
                                                <td><input type="text" size="10" name="materialityData[amount][]"
                                                            value="<?php echo $row['amount']; ?>">
                                                </td>
                                                <td><a href="#" id="<?php echo $row['id']; ?>" class="deleteMat">
                                                        <i class="fas fa-times-circle"
                                                            style="color:red !important;"></i>
                                                    </a>
                                                    <label><span class='helpDesign help_2'>2</span></label>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    ?>
                                    </tbody>
                                </table>
                                <br>
                                <?php
                                    $query = "select * from sub_materiality where workspace_id='$wid'";
                                    $result = $con->query($query);
                                    $row = $result->fetch_assoc() ?>
                                <input type="hidden" class="form-control" name="submat_id"
                                        value="<?php echo $row['id']; ?>">
                                <div class="form-group">
                                    <div class="container-fluid shadow border border-bottom" stickylevel="0">
                                        <div class="row pt-1">
                                            <div class="row text-center">
                                                <h5>Reason behind selecting the basis</h5>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <?php 
                                    if($row['comments'] != '' || $row['balance_asset'] != '' || $row['balance_liability'] != '' || $row['pl_income'] != '' || $row['pl_expense'] != ''){
                                        $subMateriality = 1;
                                    }
                                    
                                    ?>
                                    <label><span class='helpDesign help_3'>3</span></label>
                                    <textarea class="form-control" id="textarea" rows="5"
                                                name="comment"><?php echo $row['comments']; ?></textarea>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="input1">Balance Assets Scope</label>
                                        <label><span class='helpDesign help_4'>4</span></label>
                                        <input type="text" class="form-control" name="aScope"
                                                value="<?php echo $row['balance_asset']; ?>">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="input2">Balance Liability Scope</label>
                                        <label><span class='helpDesign help_4'>4</span></label>
                                        <input type="text" class="form-control" name="lScope"
                                                value="<?php echo $row['balance_liability']; ?>">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="input3">PL- Income Scope</label>
                                        <label><span class='helpDesign help_4'>4</span></label>
                                        <input type="text" class="form-control" name="pliScope"
                                                value="<?php echo $row['pl_income']; ?>">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="input4">PL- Expenses Scope</label>
                                        <label><span class='helpDesign help_4'>4</span></label>
                                        <input type="text" class="form-control" name="pleScope"
                                                value="<?php echo $row['pl_expense']; ?>">
                                    </div>
                                </div>
                                <div class="row d-flex justify-content-center align-items-center">
                                    <label><span class='helpDesign help_5'>5</span></label>
                                    <input class="btn btn-upload" type="file" name="file"
                                    accept=".pdf, .xls, .xlsx, .txt, .csv, .doc, .docx, .rtf, .xlmb" style="width:30% !important;">
                                </div>
                                
                                <div class="row d-flex justify-content-center align-items-center p-top">
                                    <?php
                                    $query = "select * from materiality_files where workspace_id='$wid'";
                                    $result = $con->query($query);
                                    ?>
                                        <ul class="custom-list list-bg" style="padding-bottom: 2% !important;">
                                            <span class="d-flex justify-content-center align-items-center">Uploaded Files</span>
                                        <?php 
                                        while ($row = $result->fetch_assoc()) {
                                            if($row['fname'] != ''){
                                                $subMateriality = 1;
                                            }
                                            ?>
                                            <li class="custom-list-items custom-list-items-action">
                                                <a href="#" class="fileEditDownload" target="_blank" download id="<?php echo $row['fname']; ?>"><?php echo $row['fname']; ?></a>
                                            </li>
                                            <?php
                                        } ?>
                                        </ul>
                                </div>
                            </div>
                            <hr>
                            <div class="col-md-12 d-flex justify-content-center align-items-center">
                                <hr>
                                <i class="fas fa-info-circle" style="color:orange !important;"></i>
                                <strong>Click the save button to save respective files/data before signing off</strong>
                            </div>
                            <div class="row d-flex justify-content-center">
                                <?php
                                if($materiality || $subMateriality ){
                                    if($con->query("select count(id) count from signoff_prepare_log where workspace_id = $wid and prog_id = '230'")->fetch_assoc()['count'] != 0){
                                        ?>
                                        <input type="submit" name="reviewSubmit" class="btn btn-outline-primary" value = "Review Sign Off">&nbsp;
                                        <?php
                                    }
                                    ?>
                                    <input type="submit" name="prepareSubmit" class="btn btn-outline-primary" value = "Prepare Sign Off">&nbsp;
                                    <?php
                                }
                                ?>
                                    <input type="submit" class="btn btn-success align-middle" value="Save Details">
                             </div>
                            </form><br>
                            <div class="row d-flex justify-content-center">
                            <?php
                                $reviewSignoff = $con->query("select count(signoff_review_log.id) total from signoff_review_log inner join user on signoff_review_log.user_id=user.id where workspace_id=".$wid." and prog_id=230")->fetch_assoc();
                                if($reviewSignoff['total']){
                                ?>
                                <button class="btn btn-outline-success fetchReview" id="230">Reviewer Log</button>&nbsp;
                                <?php
                                }
                                $prepareSignoff = $con->query("select count(signoff_prepare_log.id) total from signoff_prepare_log inner join user on signoff_prepare_log.user_id=user.id where workspace_id=".$wid." and prog_id=230")->fetch_assoc();
                                if($prepareSignoff['total']){
                                ?>
                                <button class="btn btn-outline-success fetchPrepare" id="230">Preparer Log</button>
                                <?php
                                }
                                ?>
                            </div>
                            <?php 
                        } 
                        elseif ($prog_id == 12)  {
                                // $query = "select program.*, signoff_log.Prepare_SignOff, signoff_log.prepare_date, signoff_log.Review_SignOff, signoff_log.review_date, workspace_log.status status, workspace_log.active active from program inner join workspace_log on program.id = workspace_log.program_id left join signoff_log on program.id = signoff_log.prog_id and signoff_log.workspace_id = workspace_log.workspace_id where program.parent_id = '$prog_id' and workspace_log.workspace_id = '$wid' and workspace_log.import = 1 order by _seq";
                                $query = "select program.*, workspace_log.status status, workspace_log.active active from program inner join workspace_log on program.id = workspace_log.program_id where program.parent_id = '$prog_id' and workspace_log.workspace_id = '$wid' and workspace_log.import = 1 order by _seq";
                                $exquery = $con->query($query);
                                if ($exquery->num_rows != 0)
                                {
                                while ($queryrow = $exquery->fetch_assoc())
                                {
                                if ($queryrow['hasChild'] == 1)
                                { ?>
                                    <div class="custom-list">
                                        <label class=' mt-2'><span class='helpDesign help_1'>1</span></label>
                                        <a href="subProgram?<?php echo base64_encode(md5(time())); ?>&gid=<?php echo base64_encode(md5(time())); ?>&fid=<?php echo base64_encode(md5(time())); ?>&eid=<?php echo base64_encode(md5(time())); ?>&pid=<?php echo base64_encode($queryrow['id']); ?>&cid=<?php echo base64_encode(md5(time())); ?>&bid=<?php echo base64_encode(md5(time())); ?>&aid=<?php echo base64_encode(md5(time())); ?>&parent_id=<?php echo base64_encode($queryrow['parent_id']); ?>&zid=<?php echo base64_encode(md5(time())); ?>&yid=<?php echo base64_encode(md5(time())); ?>&wid=<?php echo base64_encode($wid); ?>&xid=<?php echo base64_encode(md5(time())); ?>"
                                        class="custom-list-items custom-list-items-action"><b><?php echo trim($queryrow['program_name']); ?></b></a>
                                    </div> <?php
                                }
                                else
                                { ?>
                                <div class="custom-list">
                                <div class="custom-list-items custom-list-items-action">
                                    <?php echo trim($queryrow['program_name']); ?>
                                    <?php }
                                        }
                                        }
                        } 
                        elseif($prog_id == 2){
                            $seq0 = $seq1 = 0;
                            $query = "select program.*, workspace_log.status status, workspace_log.active active from program inner join workspace_log on program.id = workspace_log.program_id where program.parent_id = '$prog_id' and workspace_log.workspace_id = '$wid' and workspace_log.import = 1 order by _seq";
                            $exquery = $con->query($query);
                            if ($exquery->num_rows != 0) {
                                while ($queryrow = $exquery->fetch_assoc()) {
                                    if ($queryrow['hasChild'] == 1) { 
                                        if($queryrow['_seq'] < 10 && $seq0 != 1){
                                            $seq0++;
                                            ?>
                                            <h2 class="p-top"><span class="badge badge-primary" >Balance Sheet</span></h2><br/>
                                            <?php
                                        }
                                        if($queryrow['_seq'] >= 10 && $seq1 != 1){
                                            $seq1++;
                                            ?><br/>
                                            <h2><span class="badge badge-primary" >Profit & Loss</span></h2>
                                            <br/>
                                            <?php
                                        }
                                        ?>
                                        <div class="custom-list">
                                            <a href="subProgram?<?php echo base64_encode(md5(time())); ?>&gid=<?php echo base64_encode(md5(time())); ?>&fid=<?php echo base64_encode(md5(time())); ?>&eid=<?php echo base64_encode(md5(time())); ?>&pid=<?php echo base64_encode($queryrow['id']); ?>&cid=<?php echo base64_encode(md5(time())); ?>&bid=<?php echo base64_encode(md5(time())); ?>&aid=<?php echo base64_encode(md5(time())); ?>&parent_id=<?php echo base64_encode($queryrow['parent_id']); ?>&zid=<?php echo base64_encode(md5(time())); ?>&yid=<?php echo base64_encode(md5(time())); ?>&wid=<?php echo base64_encode($wid); ?>&xid=<?php echo base64_encode(md5(time())); ?>"
                                                class="custom-list-items custom-list-items-action"><b><?php echo trim($queryrow['program_name']); ?></b></a>
                                                <label class="mt-2"><span class="helpDesign help_1">1</span></label>
                                        </div> <?php
                                    }
                                    else {
                                        ?>
                                        <div class="custom-list">
                                            <div class="custom-list-items custom-list-items-action">
                                                <?php echo trim($queryrow['program_name']); ?> &nbsp;&nbsp;
                                                <?php
                                                    if ($queryrow['active']) { ?>
                                                        <a href="#">
                                                            <?php
                                                                if($queryrow['id'] == 247 || $queryrow['id'] == 245){ ?>
                                                                    <a href="subProgram?<?php echo base64_encode(md5(time())); ?>&gid=<?php echo base64_encode(md5(time())); ?>&fid=<?php echo base64_encode(md5(time())); ?>&eid=<?php echo base64_encode(md5(time())); ?>&pid=<?php echo base64_encode($queryrow['id']); ?>&cid=<?php echo base64_encode(md5(time())); ?>&bid=<?php echo base64_encode(md5(time())); ?>&aid=<?php echo base64_encode(md5(time())); ?>&parent_id=<?php echo base64_encode($queryrow['parent_id']); ?>&zid=<?php echo base64_encode(md5(time())); ?>&yid=<?php echo base64_encode(md5(time())); ?>&wid=<?php echo base64_encode($wid); ?>&xid=<?php echo base64_encode(md5(time())); ?>">    
                                                                        <i class="fas fa-external-link-alt"
                                                                            style="color:blue !important;"
                                                                            id="<?php echo $queryrow['id']; ?>">
                                                                        </i>
                                                                    </a>
                                                                <?php } 
                                                                else { ?>    
                                                                    <i class="fas fa-external-link-alt signoffmodal"
                                                                        style="color:blue !important;"
                                                                        id="<?php echo $queryrow['id']; ?>">
                                                                    </i>    
                                                                <?php }
                                                            ?>
                                                        </a> <?php
                                                        // $prearedResult = $con->query("select id,user_id,prepare_signoff_date where workspace_id = '$wid' and prog_id = '$prog_id'")->fetch_all();
                                                        // foreach($prearedResult as $key => $value)
                                                        if ($queryrow['status']) { ?>
                                                            <i class="fas fa-check-circle"
                                                                style="color:green !important;">
                                                            </i>
                                                            <?php
                                                            $prepareSignoff = $con->query("select count(signoff_prepare_log.id) total from signoff_prepare_log inner join user on signoff_prepare_log.user_id=user.id where workspace_id=".$wid." and prog_id=".$queryrow['id'])->fetch_assoc();
                                                            if($prepareSignoff['total']){
                                                            ?>
                                                            <button class="btn btn-outline-primary fetchPrepare" id="<?php echo $queryrow['id']; ?>">Preparer Sign Off</button>
                                                            <?php
                                                            }
                                                            $reviewSignoff = $con->query("select count(signoff_review_log.id) total from signoff_review_log inner join user on signoff_review_log.user_id=user.id where workspace_id=".$wid." and prog_id=".$queryrow['id'])->fetch_assoc();
                                                            if($reviewSignoff['total']){
                                                            ?>
                                                            <button class="btn btn-outline-success fetchReview" id="<?php echo $queryrow['id']; ?>">Reviewer Sign Off</button>
                                                            <?php
                                                            }
                                                        } else { ?>
                                                            <i class="fas fa-times-circle"
                                                                style="color:red !important;">
                                                            </i> <?php
                                                        } ?>
                                                        <a href="#" id="<?php echo $queryrow['id']; ?>"
                                                            class="buttonActive">
                                                            <!-- <i class="fa fa-thumbs-up float-right"
                                                                aria-hidden="true"
                                                                style="color:blue !important;">
                                                            </i> -->
                                                            <img class="float-right" src="Icons/thumbs-up.svg" />
                                                        </a> <?php
                                                    } else { ?>
                                                        <a href="#" id="<?php echo $queryrow['id']; ?>"
                                                            class="buttonActive">
                                                            <img class="float-right" src="Icons/Icon feather-plus.svg" />
                                                            <!-- <i class="fa fa-ban float-right" aria-hidden="true" style="color:orange !important;"></i> -->
                                                        </a> 
                                                        <?php
                                                    }
                                                ?>
                                            </div>
                                        </div> <?php
                                    }
                                }
                            }
                        }
                        elseif($prog_id == 395){
                            ?>
                                <style>
                                    td:nth-child(4),td:nth-child(5) {
                                        font-weight: bold !important;
                                    }
                                    td:nth-child(2) {
                                        font-weight: normal !important;
                                    }
                                    .card{
                                        border: 1px solid #e3e6f0 !important;
                                    }
                                </style>
                                <br>
                                <a href="#" data-toggle="modal" data-target="#financialStatementChangeSequenceModal"><button class="btn btn-secondary">Change Sequence</button></a>
                                <a id="financialStatementPdf" target="_blanc" href="financialStatementPdf"><button class="btn btn-primary">Print to PDF</button></a>
                                <a id="financialStatementWord" target="_blanc" href="financialStatementWord"><button class="btn btn-outline-primary">Export to Word</button></a>
                                <a id="financialStatementExcel" target="_blanc" href="financialStatementExcel"><button class="btn btn-outline-primary">Export to Excel</button></a>
                                <div class="accordion mt-3" id="unauditedBalanceSheetAccordionExample">
                                    <div class="card">
                                        <div class="card-header" id="unauditedBalanceSheetHeadingOne">
                                            <center>
                                                <h2 class="mb-0">
                                                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#unauditedBalanceSheet" aria-expanded="true" aria-controls="unauditedBalanceSheet"><b>Unaudited Balance Sheet</b></button>
                                                </h2>
                                            </center>
                                        </div> 
                                        <div id="unauditedBalanceSheet" class="collapse show" aria-labelledby="unauditedBalanceSheetHeadingOne" data-parent="#unauditedBalanceSheetAccordionExample" style="margin-left: 2.5rem; padding-bottom: 2rem;">
                                            <?php
                                                $accountTypeResult = $con->query("SELECT DISTINCT account_type, accountTypeSeqNumber from trial_balance where workspace_id='$wid' and ( account_type not like '%Expense%' and account_type not like '%Revenue%' ) order by accountTypeSeqNumber");
                                            ?>
                                            <br>
                                            <?php
                                                $i = 0;
                                                while($accountTypeRow = $accountTypeResult->fetch_assoc()){
                                                    ++$i;
                                                    ?>
                                                        <br>
                                                        <div class="accordion" id="unauditedBalanceSheetAccordionExample<?php echo $i; ?>">
                                                            <div class="card">
                                                                <div class="card-header" id="unauditedBalanceSheetHeadingOne<?php echo $i; ?>">
                                                                    <h2 class="mb-0">
                                                                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#account_classBS<?php echo $i; ?>" aria-expanded="true" aria-controls="account_classBS<?php echo $i; ?>"><b><?php echo $accountTypeRow['account_type']; ?></b></button>
                                                                    </h2>
                                                                </div>  
                                                                <div id="account_classBS<?php echo $i; ?>" class="collapse show" aria-labelledby="unauditedBalanceSheetHeadingOne<?php echo $i; ?>" data-parent="#unauditedBalanceSheetAccordionExample<?php echo $i; ?>" style="margin-left: 2.5rem;">
                                                                    <?php
                                                                        $accountClassResult = $con->query("SELECT account_class from trial_balance where account_type ='".$accountTypeRow['account_type']."' and workspace_id='".$wid."' group by account_class");
                                                                        while($accountClassRow = $accountClassResult->fetch_assoc()){
                                                                            $cyFinalBalTotal = $cyBegBalTotal = 0;
                                                                            ?>
                                                                                <br>
                                                                                <h4><?php echo $accountClassRow['account_class']; ?></h4>
                                                                                <table class="table" style="width:100%; text-align: left">
                                                                                    <thead>
                                                                                        <th>Financial Statement</th>
                                                                                        <th>CY Final Balance</th>
                                                                                        <th>CY Begining Balance</th>
                                                                                        <th>Variance ($)</th>
                                                                                        <th>Variance (%)</th>
                                                                                    </thead>
                                                                                    <tbody>
                                                                            <?php
                                                                                $financialStatementResult = $con->query("SELECT max(financial_statement) financial_statement, sum(cy_beg_bal) cy_beg_bal, sum(cy_final_bal) cy_final_bal from trial_balance where account_type ='".$accountTypeRow['account_type']."' and account_class ='".$accountClassRow['account_class']."' and workspace_id='".$wid."' group by account_class,account_class,financial_statement order by financial_statement");
                                                                                $clientID = time();
                                                                                while($financialStatementRow = $financialStatementResult->fetch_assoc()){
                                                                                    $cyFinalBalTotal += $financialStatementRow['cy_final_bal'];
                                                                                    $cyBegBalTotal += $financialStatementRow['cy_beg_bal'];
                                                                                    ?>
                                                                                    <tr>
                                                                                        <td style="text-align: left"><a target ="_blank" href="pivotTable?xid=<?php echo base64_encode(md5($clientID)); ?>&uid=<?php echo base64_encode(md5($clientID)); ?>&cid=<?php echo base64_encode($clientID); ?>&aid=<?php echo base64_encode(md5($clientID));?>&wid=<?php echo base64_encode($wid);?>&account=<?php echo base64_encode($financialStatementRow['financial_statement']);?>&zid=<?php echo base64_encode(md5($clientID)); ?>&qid=<?php echo base64_encode(md5($clientID)); ?>"><?php echo $financialStatementRow['financial_statement'];?></a></td>
                                                                                        <td style="text-align: left"><?php echo numberToCurrency($financialStatementRow['cy_final_bal']);?></td>
                                                                                        <td style="text-align: left"><?php echo numberToCurrency($financialStatementRow['cy_beg_bal']);?></td>
                                                                                        <td style="text-align: left"><?php echo numberToCurrency($financialStatementRow['cy_final_bal']-$financialStatementRow['cy_beg_bal']);?></td>
                                                                                        <td style="text-align: left">
                                                                                        <?php
                                                                                            $diffPercentage = 0.00;
                                                                                            if($financialStatementRow['cy_beg_bal'] != 0)
                                                                                                $diffPercentage = number_format((float)(($financialStatementRow['cy_final_bal']-$financialStatementRow['cy_beg_bal'])/$financialStatementRow['cy_beg_bal'])*100, 2, '.', '');
                                                                                            echo $diffPercentage.'%';
                                                                                        ?>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <?php
                                                                                }
                                                                            ?> 
                                                                                        <tr colspan="5"><td></td></tr>
                                                                                        <tr>
                                                                                            <td style="text-align: left"><h5 style="border-bottom: 1px solid;border-top: 1px solid;">Total <?php echo $accountClassRow['account_class']; ?></h5></td>
                                                                                            <td style="text-align: left"><h5 style="border-bottom: 1px solid;border-top: 1px solid;"><?php echo numberToCurrency($cyFinalBalTotal); ?></h5></td>
                                                                                            <td style="text-align: left"><h5 style="border-bottom: 1px solid;border-top: 1px solid;"><?php echo numberToCurrency($cyBegBalTotal); ?></h5></td>
                                                                                            <td colspan=2></td>
                                                                                        </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            <?php
                                                                        }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php
                                                }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="accordion" id="unauditedProfitLossAccordionExample">
                                    <div class="card">
                                        <div class="card-header" id="unauditedProfitLossHeadingOne">
                                            <center>
                                                <h2 class="mb-0">
                                                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#unauditedProfitLoss" aria-expanded="true" aria-controls="unauditedProfitLoss"><b>Unaudited Profit and Loss</b></button>
                                                </h2>
                                            </center>
                                        </div> 
                                        <div id="unauditedProfitLoss" class="collapse show" aria-labelledby="unauditedProfitLossHeadingOne" data-parent="#unauditedProfitLossAccordionExample" style="margin-left: 2.5rem; padding-bottom: 2rem;">
                                            <?php
                                                $accountTypeResult = $con->query("SELECT DISTINCT account_type, accountTypeSeqNumber from trial_balance where workspace_id='$wid' and ( account_type like '%Expense%' or account_type like '%Revenue%' ) order by accountTypeSeqNumber");
                                            ?>
                                            
                                            <?php
                                                $i = 0;
                                                while($accountTypeRow = $accountTypeResult->fetch_assoc()){
                                                    ++$i;
                                                    ?>
                                                        <br>
                                                        <div class="accordion" id="unauditedProfitLossAccordionExample<?php echo $i; ?>">
                                                            <div class="card">
                                                                <div class="card-header" id="unauditedProfitLossHeadingOne<?php echo $i; ?>">
                                                                    <h2 class="mb-0">
                                                                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#account_classPL<?php echo $i; ?>" aria-expanded="true" aria-controls="account_classPL<?php echo $i; ?>"><b><?php echo $accountTypeRow['account_type']; ?></b></button>
                                                                    </h2>
                                                                </div>  
                                                                <div id="account_classPL<?php echo $i; ?>" class="collapse show" aria-labelledby="unauditedProfitLossHeadingOne<?php echo $i; ?>" data-parent="#unauditedProfitLossAccordionExample<?php echo $i; ?>" style="margin-left: 2.5rem;">
                                                                    <?php
                                                                        $accountClassResult = $con->query("SELECT account_class from trial_balance where account_type ='".$accountTypeRow['account_type']."' and workspace_id='".$wid."' group by account_class");
                                                                        while($accountClassRow = $accountClassResult->fetch_assoc()){
                                                                            $cyFinalBalTotal = $cyBegBalTotal = 0;
                                                                            ?>
                                                                                <br>
                                                                                <h4><?php echo $accountClassRow['account_class']; ?></h4>
                                                                                <table class="table" style="width:100%; text-align: left">
                                                                                    <thead>
                                                                                        <th>Financial Statement</th>
                                                                                        <th>CY Final Balance</th>
                                                                                        <th>CY Begining Balance</th>
                                                                                        <th>Variance ($)</th>
                                                                                        <th>Variance (%)</th>
                                                                                    </thead>
                                                                                    <tbody>
                                                                            <?php
                                                                                $financialStatementResult = $con->query("SELECT max(financial_statement) financial_statement, sum(cy_beg_bal) cy_beg_bal, sum(cy_final_bal) cy_final_bal from trial_balance where account_type ='".$accountTypeRow['account_type']."' and account_class ='".$accountClassRow['account_class']."' and workspace_id='".$wid."' group by account_class,account_class,financial_statement order by financial_statement");
                                                                                while($financialStatementRow = $financialStatementResult->fetch_assoc()){
                                                                                    $cyFinalBalTotal += $financialStatementRow['cy_final_bal'];
                                                                                    $cyBegBalTotal += $financialStatementRow['cy_beg_bal'];
                                                                                    ?>
                                                                                    <tr>
                                                                                        <td style="text-align: left"><a target ="_blank" href="pivotTable?xid=<?php echo base64_encode(md5($clientID)); ?>&uid=<?php echo base64_encode(md5($clientID)); ?>&cid=<?php echo base64_encode($clientID); ?>&aid=<?php echo base64_encode(md5($clientID));?>&wid=<?php echo base64_encode($wid);?>&account=<?php echo base64_encode($financialStatementRow['financial_statement']);?>&zid=<?php echo base64_encode(md5($clientID)); ?>&qid=<?php echo base64_encode(md5($clientID)); ?>"><?php echo $financialStatementRow['financial_statement'];?></a></td>
                                                                                        <td style="text-align: left"><?php echo numberToCurrency($financialStatementRow['cy_final_bal']);?></td>
                                                                                        <td style="text-align: left"><?php echo numberToCurrency($financialStatementRow['cy_beg_bal']);?></td>
                                                                                        <td style="text-align: left"><?php echo numberToCurrency($financialStatementRow['cy_final_bal']-$financialStatementRow['cy_beg_bal']);?></td>
                                                                                        <td style="text-align: left">
                                                                                        <?php
                                                                                            $diffPercentage = 0.00;
                                                                                            if($financialStatementRow['cy_beg_bal'] != 0)
                                                                                                $diffPercentage = number_format((float)(($financialStatementRow['cy_final_bal']-$financialStatementRow['cy_beg_bal'])/$financialStatementRow['cy_beg_bal'])*100, 2, '.', '');
                                                                                            echo $diffPercentage.'%';
                                                                                        ?>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <?php
                                                                                }
                                                                            ?> 
                                                                                        <tr colspan="5"><td></td></tr>
                                                                                        <tr>
                                                                                            <td style="text-align: left"><h5 style="border-bottom: 1px solid;border-top: 1px solid;">Total <?php echo $accountClassRow['account_class']; ?></h5></td>
                                                                                            <td style="text-align: left"><h5 style="border-bottom: 1px solid;border-top: 1px solid;"><?php echo numberToCurrency($cyFinalBalTotal); ?></h5></td>
                                                                                            <td style="text-align: left"><h5 style="border-bottom: 1px solid;border-top: 1px solid;"><?php echo numberToCurrency($cyBegBalTotal); ?></h5></td>
                                                                                            <td colspan=2></td>
                                                                                        </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            <?php
                                                                        }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php
                                                }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            <?php
                        } 
                        elseif($prog_id == 496){
                            $auditReportResult = $con->query("select audit_report from draft_report where workspace_id = $wid");
                            if($auditReportResult->num_rows > 0){
                                ?>
                                <div class="d-flex justify-content-center">
                                    <button id="exportDOC" class="btn bg-violet mb-3 mt-3">Export to Doc</button>
                                    <button id="save_audit_report_update" class="btn btn-success ml-1 mb-3 mt-3">Save Changes</button>
                                </div>
                                <div class="col-md-12 d-flex justify-content-center align-items-center">
                                    <hr>
                                    <i class="fas fa-info-circle" style="color:orange !important;"></i>
                                    <strong>Click the Save Changes button to save respective data before exporting into Doc</strong>
                                </div>
                                <div id="editor">
                                    <div class="fr-view" id="DraftReportHtml">
                                        <?php
                                            echo $auditReportResult->fetch_assoc()['audit_report'];
                                        ?>
                                    </div>
                                </div>
                                <br>
                                    <div class="row d-flex justify-content-center">
                                <?php
                                $checkReviewStatus = $con->query("select count(signoff_prepare_log.id) total from signoff_prepare_log inner join user on signoff_prepare_log.user_id=user.id where workspace_id=".$wid." and prog_id=496")->fetch_assoc();
                                if($checkReviewStatus['total']){
                                    ?>
                                        <button class="btn btn-info" id="reviewSubmitDraft">Review Sign-Off</button>
                                    <?php
                                }
                                ?>
                                    &nbsp;
                                    <button class="btn btn-success" id="prepareSubmitDraft">Prepare Sign-Off</button>
                                    </div>
                                <?php
                            }
                            else{
                            ?>
                                <br>
                                <div class="row d-flex justify-content-start">
                                    <form id="draft_report_form" class="d-flex col-md-10 col-lg-10 col-sm-1">    
                                        <div class="form-group col-md-4 col-lg-4 col-sm-4">    
                                            <label for="">Type of Audit Report</label>
                                            <select class="form-control" name="audit_report" id="audit_report" required onchange="type_audit_report()">
                                                <option selected value="">Choose any One!</option>
                                                <option value="0">Unqualified Opinion</option>
                                                <option value="1">Qualified Opinion</option>
                                            </select>
                                        </div>
                                        <div id="emphasis_of_matters_div" class="form-group col-md-4 col-lg-4 col-sm-4"> 
                                            <label for="">Emphasis of Matters</label>
                                            <select class="form-control" name="emphasis_of_matters" id="emphasis_of_matters">
                                                <option selected value="">Choose any One!</option>
                                                <option value="0">No</option>
                                                <option value="1">Yes</option>
                                            </select>
                                        </div>
                                        <div id="other_matters_div" class="form-group col-md-4 col-lg-4 col-sm-4"> 
                                            <label for="">Other Matters</label>
                                            <select class="form-control" name="other_matters" id="other_matters">
                                                <option selected value="">Choose any One!</option>
                                                <option value="0">No</option>
                                                <option value="1">Yes</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-4 col-lg-4 col-sm-4"> 
                                            <div class="d-flex justify-content-start align-items-end h-100">
                                                <input type="submit" id="draft_report_form" class="btn btn-outline-primary" value="Generate Audit Report">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div id="draft_report_show_div">
                                    <div class="d-flex justify-content-center">
                                        <button id="exportDOC" class="btn bg-violet mb-3">Export to Doc</button>
                                        <button id="save_audit_report" class="btn btn-success mb-3">Save Changes</button>
                                    </div>
                                    <div class="col-md-12 d-flex justify-content-center align-items-center">
                                        <hr>
                                        <i class="fas fa-info-circle" style="color:orange !important;"></i>
                                        <strong>Click the Save Changes button to save respective data before exporting into Doc</strong>
                                    </div>
                                    <?php $clientName = $con->query("select name from workspace inner join client on workspace.client_id = client.id where workspace.id = $wid")->fetch_assoc()['name'];  ?>
                                    <div id="editor">    
                                        <div class="fr-view" id="DraftReportHtml">
                                            <div id="unqualified_opinion">
                                                    <p dir="ltr" style="line-height: 1.295; margin-top: 0pt; margin-bottom: 8pt;"><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(255, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">TO THE MEMBERS OF <?php echo $clientName; ?></span></p>

                                                    <p>
                                                        <br>
                                                    </p>

                                                    <p dir="ltr" style="line-height: 1.295; margin-top: 0pt; margin-bottom: 8pt;"><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(0, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">Report on the Standalone Financial Statements</span></p>

                                                    <p dir="ltr" style="line-height: 1.295; margin-top: 0pt; margin-bottom: 8pt;"><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(0, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">We have audited the accompanying standalone financial statements of&nbsp;</span><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(255, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;"><?php echo $clientName; ?>&nbsp;</span><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(0, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">(&ldquo;the Company&rdquo;), which comprise the Balance Sheet as at&nbsp;</span><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(255, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">31st March, 20XX</span><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(0, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">, the Statement of Profit and Loss, the Cash Flow Statement for the year then ended, and a summary of the significant accounting policies and other explanatory information.</span></p>

                                                    <p dir="ltr" style="line-height: 1.295; margin-top: 0pt; margin-bottom: 8pt;"><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(0, 0, 0); background-color: transparent; font-weight: 700; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">Management&rsquo;s Responsibility for the Standalone Financial Statements</span></p>

                                                    <p dir="ltr" style="line-height: 1.295; margin-top: 0pt; margin-bottom: 8pt;"><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(0, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">The Company&rsquo;s Board of Directors is responsible for the matters stated in Section 134(5) of the Companies Act, 2013 (&ldquo;the Act&rdquo;) with respect to the preparation of these standalone financial statements that give a true and fair view of the financial position, financial performance and cash flows of the Company in accordance with the accounting principles generally accepted in India, including the Accounting Standards specified under Section 133 of the Act, read with Rule 7 of the Companies (Accounts) Rules, 2014. This responsibility also includes maintenance of adequate accounting records in accordance with the provisions of the Act for safeguarding of the assets of the Company and for preventing and detecting frauds and other irregularities; selection and application of appropriate accounting policies; making judgments and estimates that are reasonable and prudent; and design, implementation and maintenance of adequate internal financial controls, that were operating effectively for ensuring the accuracy and completeness of the accounting records, relevant to the preparation and presentation of the financial statements that give a true and fair view and are free from material misstatement, whether due to fraud or error.</span></p>

                                                    <p dir="ltr" style="line-height: 1.295; margin-top: 0pt; margin-bottom: 8pt;"><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(0, 0, 0); background-color: transparent; font-weight: 700; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">Auditor&rsquo;s Responsibility</span></p>

                                                    <p dir="ltr" style="line-height: 1.295; margin-top: 0pt; margin-bottom: 8pt;"><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(0, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">Our responsibility is to express an opinion on these standalone financial statements based on our audit.</span></p>

                                                    <p dir="ltr" style="line-height: 1.295; margin-top: 0pt; margin-bottom: 8pt;"><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(0, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">We have taken into account the provisions of the Act, the accounting and auditing standards and matters which are required to be included in the audit report under the provisions of the Act and the Rules made thereunder.</span></p>

                                                    <p dir="ltr" style="line-height: 1.295; margin-top: 0pt; margin-bottom: 8pt;"><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(0, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">We conducted our audit in accordance with the Standards on Auditing specified under Section 143(10) of the Act. Those Standards require that we comply with ethical requirements and plan and perform the audit to obtain reasonable assurance about whether the financial statements are free from material misstatement.</span></p>

                                                    <p dir="ltr" style="line-height: 1.295; margin-top: 0pt; margin-bottom: 8pt;"><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(0, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">An audit involves performing procedures to obtain audit evidence about the amounts and the disclosures in the financial statements. The procedures selected depend on the auditor&rsquo;s judgment, including the assessment of the risks of material misstatement of the financial statements, whether due to fraud or error. In making those risk assessments, the auditor considers internal financial control relevant to the Company&rsquo;s preparation of the financial statements that give a true and fair view in order to design audit procedures that are appropriate in the circumstances. An audit also includes evaluating the appropriateness of the accounting policies used and the reasonableness of the accounting estimates made by the Company&rsquo;s Directors, as well as evaluating the overall presentation of the financial statements.</span></p>

                                                    <p dir="ltr" style="line-height: 1.295; margin-top: 0pt; margin-bottom: 8pt;"><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(0, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">We believe that the audit evidence we have obtained is sufficient and appropriate to provide a basis for our audit opinion on the standalone financial statements.</span></p>

                                                    <p dir="ltr" style="line-height: 1.295; margin-top: 0pt; margin-bottom: 8pt;"><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(0, 0, 0); background-color: transparent; font-weight: 700; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">Opinion</span></p>

                                                    <p dir="ltr" style="line-height: 1.295; margin-top: 0pt; margin-bottom: 8pt;"><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(0, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">In our opinion and to the best of our information and according to the explanations given to us, the aforesaid standalone financial statements give the information required by the Act in the manner so required and give a true and fair view in conformity with the accounting principles generally accepted in India, of the state of affairs of the Company as at&nbsp;</span><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(255, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">31st March, 20XX</span><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(0, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">, and its profit/loss and its cash flows for the year ended on that date.</span></p>

                                                    <p>
                                                        <br>
                                                    </p>
                                                    <div id="unqualified_opinion_emphasis_of_matters_body">
                                                        <p dir="ltr" style="line-height: 1.295; margin-top: 0pt; margin-bottom: 8pt;"><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(255, 0, 0); background-color: transparent; font-weight: 700; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">Emphasis of Matters (Optional) &nbsp;** PLEASE &nbsp;REVIEW THIS SECTION AND EDIT AND REMOVE AS NEEDED**</span></p>

                                                        <p dir="ltr" style="line-height: 1.295; margin-top: 0pt; margin-bottom: 8pt;"><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(0, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">We draw attention to the following matters in the Notes to the financial statements:</span></p>

                                                        <p dir="ltr" style="line-height: 1.295; margin-top: 0pt; margin-bottom: 8pt;"><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(255, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">Note X&nbsp;</span><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(0, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">to the financial statements which, describes the uncertainty related to the outcome of the lawsuit filed against the Company by&nbsp;</span><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(255, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">XYZ Company</span><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(0, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">.</span></p>

                                                        <p dir="ltr" style="line-height: 1.295; margin-top: 0pt; margin-bottom: 8pt;"><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(255, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">Note Y&nbsp;</span><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(0, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">in the financial statements which indicates that the Company has accumulated losses and its net worth has been fully / substantially eroded, the Company has incurred a net loss/net cash loss during the current and previous year(s) and, the Company&rsquo;s current liabilities exceeded its current assets as at the balance sheet date. These conditions, along with other matters set forth in&nbsp;</span><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(255, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">Note Y</span><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(0, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">, indicate the existence of a material uncertainty that may cast significant doubt about the Company&rsquo;s ability to continue as a going concern. However, the financial statements of the Company have been prepared on a going concern basis for the reasons stated in the said Note.</span></p>

                                                        <p dir="ltr" style="line-height: 1.295; margin-top: 0pt; margin-bottom: 8pt;"><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(0, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">Our opinion is not modified in respect of these matters.</span></p>
                                                    </div>
                                                    <p>
                                                        <br>
                                                    </p>
                                                    <div id="unqualified_opinion_other_matters_body">
                                                        <p dir="ltr" style="line-height: 1.295; margin-top: 0pt; margin-bottom: 8pt;"><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(255, 0, 0); background-color: transparent; font-weight: 700; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">Other Matter (Optional) - ** PLEASE &nbsp;REVIEW THIS SECTION AND EDIT AND REMOVE AS NEEDED**</span></p>

                                                        <p dir="ltr" style="line-height: 1.295; margin-top: 0pt; margin-bottom: 8pt;"><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(0, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">Mention any other matter that is financially or operationally significant to the company, if required.</span></p>

                                                        <p dir="ltr" style="line-height: 1.295; margin-top: 0pt; margin-bottom: 8pt;"><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(0, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">Our opinion is not modified in respect of this matter.</span></p>
                                                    </div>
                                                    <p>
                                                        <br>
                                                    </p>

                                                    <p dir="ltr" style="line-height: 1.295; margin-top: 0pt; margin-bottom: 8pt;"><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(0, 0, 0); background-color: transparent; font-weight: 700; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">Report on Other Legal and Regulatory Requirements</span></p>

                                                    <p dir="ltr" style="line-height: 1.295; margin-top: 0pt; margin-bottom: 8pt;"><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(0, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">As required by Section 143 (3) of the Act, we report that:</span></p>

                                                    <p dir="ltr" style="line-height: 1.295; margin-top: 0pt; margin-bottom: 8pt;"><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(0, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">(a) We have sought and obtained all the information and explanations which to the best of our knowledge and belief were necessary for the purposes of our audit.</span></p>

                                                    <p dir="ltr" style="line-height: 1.295; margin-top: 0pt; margin-bottom: 8pt;"><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(0, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">(b) In our opinion, proper books of account as required by law have been kept by the Company so far as it appears from our examination of those books.</span></p>

                                                    <p>
                                                        <br>
                                                    </p>

                                                    <p dir="ltr" style="line-height: 1.295; margin-top: 0pt; margin-bottom: 8pt;"><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(0, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">(c) The Balance Sheet, the Statement of Profit and Loss, and the Cash Flow Statement dealt with by this Report are in agreement with the books of account.</span></p>

                                                    <p dir="ltr" style="line-height: 1.295; margin-top: 0pt; margin-bottom: 8pt;"><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(0, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">(d) In our opinion, the aforesaid standalone financial statements comply with the Accounting Standards specified under Section 133 of the Act, read with Rule 7 of the Companies (Accounts) Rules, 2014.</span></p>

                                                    <p dir="ltr" style="line-height: 1.295; margin-top: 0pt; margin-bottom: 8pt;"><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(0, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">(e) On the basis of the written representations received from the directors as on&nbsp;</span><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(255, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">31st March, 20XX&nbsp;</span><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(0, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">taken on record by the Board of Directors, none of the directors is disqualified as on&nbsp;</span><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(255, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">31st March, 20XX</span><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(0, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">&nbsp;from being appointed as a director in terms of Section 164 (2) of the Act.</span></p>

                                                    <p dir="ltr" style="line-height: 1.295; margin-top: 0pt; margin-bottom: 8pt;"><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(0, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">(f) With respect to the adequacy of the internal financial controls over financial reporting of the Company and the operating effectiveness of such controls, refer to our separate Report in &ldquo;Annexure A&rdquo;.</span></p>

                                                    <p dir="ltr" style="line-height: 1.295; margin-top: 0pt; margin-bottom: 8pt;"><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(0, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">(g) With respect to the other matters to be included in the Auditor&rsquo;s Report in accordance with Rule 11 of the Companies (Audit and Auditors) Rules, 2014, in our opinion and to the best of our information and according to the explanations given to us:</span></p>

                                                    <p>
                                                        <br>
                                                    </p>

                                                    <p dir="ltr" style="line-height: 1.295; margin-top: 0pt; margin-bottom: 8pt;"><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(255, 0, 0); background-color: transparent; font-weight: 700; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">** PLEASE &nbsp;REVIEW THIS SECTION AND EDIT AND REMOVE AS NEEDED**</span></p>

                                                    <p dir="ltr" style="line-height: 1.295; margin-top: 0pt; margin-bottom: 8pt;"><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(0, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">If applicable, mention any pending litigations which would impact the financial position of the Company.</span></p>

                                                    <p dir="ltr" style="line-height: 1.295; margin-top: 0pt; margin-bottom: 8pt;"><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(0, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">If applicable, mention if the Company has any long-term contracts including derivative contracts for which there were any material foreseeable losses.</span></p>

                                                    <p dir="ltr" style="line-height: 1.295; margin-top: 0pt; margin-bottom: 8pt;"><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(0, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">If applicable, mention any delay in payment of statutory dues.</span></p>

                                                    <p>
                                                        <br>
                                                    </p>

                                                    <p dir="ltr" style="line-height: 1.295; margin-top: 0pt; margin-bottom: 8pt;"><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(0, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">Place:</span></p>

                                                    <p dir="ltr" style="line-height: 1.295; margin-top: 0pt; margin-bottom: 8pt;"><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(0, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">Date:</span></p>

                                                    <p dir="ltr" style="line-height: 1.295; margin-top: 0pt; margin-bottom: 8pt;"><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(0, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">For <?php echo $clientName; ?></span></p>

                                                    <p dir="ltr" style="line-height: 1.295; margin-top: 0pt; margin-bottom: 8pt;"><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(0, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">Chartered Accountants</span></p>

                                                    <p dir="ltr" style="line-height: 1.295; margin-top: 0pt; margin-bottom: 8pt;"><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(0, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">(Firm&rsquo;s Registration No.)</span></p>

                                                    <p>
                                                        <br>
                                                    </p>

                                                    <p dir="ltr" style="line-height: 1.295; margin-top: 0pt; margin-bottom: 8pt;"><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(0, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">Signature</span></p>

                                                    <p>
                                                        <br>
                                                    </p>

                                                    <p dir="ltr" style="line-height: 1.295; margin-top: 0pt; margin-bottom: 8pt;"><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(0, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">(CA. Name)</span></p>

                                                    <p>
                                                        <br>
                                                    </p>

                                                    <p dir="ltr" style="line-height: 1.295; margin-top: 0pt; margin-bottom: 8pt;"><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(0, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">(Designation)</span></p>

                                                    <p dir="ltr" style="line-height: 1.295; margin-top: 0pt; margin-bottom: 8pt;"><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(0, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">(Membership No. XXXX)</span></p>

                                            </div>
                                            <div id="qualified_opinion">
                                                <p dir="ltr" style="line-height: 1.295; margin-top: 0pt; margin-bottom: 8pt;"><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(0, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">TO THE MEMBERS OF&nbsp;</span><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(255, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;"><?php echo $clientName; ?></span></p>

                                                <p>
                                                    <br>
                                                </p>

                                                <p dir="ltr" style="line-height: 1.295; margin-top: 0pt; margin-bottom: 8pt;"><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(0, 0, 0); background-color: transparent; font-weight: 700; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">Report on the Standalone Financial Statements</span></p>

                                                <p dir="ltr" style="line-height: 1.295; margin-top: 0pt; margin-bottom: 8pt;"><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(0, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">We have audited the accompanying standalone financial statements of&nbsp;</span><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(255, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;"><?php echo $clientName; ?>&nbsp;</span><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(0, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">(&ldquo;the Company&rdquo;), which comprise the Balance Sheet as at&nbsp;</span><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(255, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">31st March, 20XX</span><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(0, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">, the Statement of Profit and Loss, the Cash Flow Statement for the year then ended, and a summary of the significant accounting policies and other explanatory information.</span></p>

                                                <p dir="ltr" style="line-height: 1.295; margin-top: 0pt; margin-bottom: 8pt;"><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(0, 0, 0); background-color: transparent; font-weight: 700; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">Management&rsquo;s Responsibility for the Standalone Financial Statements</span></p>

                                                <p dir="ltr" style="line-height: 1.295; margin-top: 0pt; margin-bottom: 8pt;"><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(0, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">The Company&rsquo;s Board of Directors is responsible for the matters stated in Section 134(5) of the Companies Act, 2013 (&ldquo;the Act&rdquo;) with respect to the preparation of these standalone financial statements that give a true and fair view of the financial position, financial performance and cash flows of the Company in accordance with the accounting principles generally accepted in India, including the Accounting Standards specified under Section 133 of the Act, read with Rule 7 of the Companies (Accounts) Rules, 2014. This responsibility also includes maintenance of adequate accounting records in accordance with the provisions of the Act for safeguarding of the assets of the Company and for preventing and detecting frauds and other irregularities; selection and application of appropriate accounting policies; making judgments and estimates that are reasonable and prudent; and design, implementation and maintenance of adequate internal financial controls, that were operating effectively for ensuring the accuracy and completeness of the accounting records, relevant to the preparation and presentation of the financial statements that give a true and fair view and are free from material misstatement, whether due to fraud or error.</span></p>

                                                <p dir="ltr" style="line-height: 1.295; margin-top: 0pt; margin-bottom: 8pt;"><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(0, 0, 0); background-color: transparent; font-weight: 700; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">Auditor&rsquo;s Responsibility</span></p>

                                                <p dir="ltr" style="line-height: 1.295; margin-top: 0pt; margin-bottom: 8pt;"><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(0, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">Our responsibility is to express an opinion on these standalone financial statements based on our audit.</span></p>

                                                <p dir="ltr" style="line-height: 1.295; margin-top: 0pt; margin-bottom: 8pt;"><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(0, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">We have taken into account the provisions of the Act, the accounting and auditing standards and matters which are required to be included in the audit report under the provisions of the Act and the Rules made thereunder.</span></p>

                                                <p dir="ltr" style="line-height: 1.295; margin-top: 0pt; margin-bottom: 8pt;"><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(0, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">We conducted our audit in accordance with the Standards on Auditing specified under Section 143(10) of the Act. Those Standards require that we comply with ethical requirements and plan and perform the audit to obtain reasonable assurance about whether the financial statements are free from material misstatement.</span></p>

                                                <p dir="ltr" style="line-height: 1.295; margin-top: 0pt; margin-bottom: 8pt;"><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(0, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">An audit involves performing procedures to obtain audit evidence about the amounts and the disclosures in the financial statements. The procedures selected depend on the auditor&rsquo;s judgment, including the assessment of the risks of material misstatement of the financial statements, whether due to fraud or error. In making those risk assessments, the auditor considers internal financial control relevant to the Company&rsquo;s preparation of the financial statements that give a true and fair view in order to design audit procedures that are appropriate in the circumstances. An audit also includes evaluating the appropriateness of the accounting policies used and the reasonableness of the accounting estimates made by the Company&rsquo;s Directors, as well as evaluating the overall presentation of the financial statements.</span></p>

                                                <p dir="ltr" style="line-height: 1.295; margin-top: 0pt; margin-bottom: 8pt;"><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(0, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">We believe that the audit evidence we have obtained is sufficient and appropriate to provide a&nbsp;</span><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(255, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">basis for our qualified audit opinion.</span></p>

                                                <p>
                                                    <br>
                                                </p>

                                                <p dir="ltr" style="line-height: 1.295; margin-top: 0pt; margin-bottom: 8pt;"><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(0, 0, 0); background-color: transparent; font-weight: 700; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">Basis for Qualified Opinion&nbsp;</span><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(255, 0, 0); background-color: transparent; font-weight: 700; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">(Required in a Qualified Opinion)</span><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(255, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">&nbsp;** PLEASE &nbsp;REVIEW THIS SECTION AND EDIT AND REMOVE AS NEEDED**</span></p>

                                                <p dir="ltr" style="line-height: 1.295; margin-top: 0pt; margin-bottom: 8pt;"><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(255, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">List reasons for qualification &ndash; Examples provided below</span></p>

                                                <p dir="ltr" style="line-height: 1.295; margin-top: 0pt; margin-bottom: 8pt;"><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(0, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">Included in debtors shown on the balance sheet is an amount of Rs. due from&nbsp;</span><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(255, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">XXX Private Limited</span><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(0, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">, a company that has ceased operations. The Company has no security for this debt. On the basis that no security has been obtained and no cash has been received during the financial year, in our opinion the Company should make a full provision for impairment&nbsp;</span><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(255, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">of Rs.XXX</span><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(0, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">, reducing profit before taxation for the year and net assets at&nbsp;</span><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(255, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">31, March 20XX&nbsp;</span><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(0, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">by that amount.</span></p>

                                                <p dir="ltr" style="line-height: 1.295; margin-top: 0pt; margin-bottom: 8pt;"><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(0, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">We were appointed as Auditors of the company on&nbsp;</span><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(255, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">31 December 20XX&nbsp;</span><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(0, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">and thus did not observe the counting of the physical inventories at the beginning of the financial year. We were unable to satisfy ourselves by alternative means concerning inventory quantities held on&nbsp;</span><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(255, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">31st March 20XX.</span></p>

                                                <p dir="ltr" style="line-height: 1.295; margin-top: 0pt; margin-bottom: 8pt;"><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(0, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">The Company&rsquo;s recorded turnover comprises cash sales, over which there was no system of internal control on which we could rely for the purpose of our audit. There were no other satisfactory audit procedures that we could adopt to satisfy ourselves that the recorded turnover was free from material misstatements.</span></p>

                                                <p dir="ltr" style="line-height: 1.295; margin-top: 0pt; margin-bottom: 8pt;"><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(0, 0, 0); background-color: transparent; font-weight: 700; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">Report on Other Legal and Regulatory Requirements</span></p>

                                                <p dir="ltr" style="line-height: 1.295; margin-top: 0pt; margin-bottom: 8pt;"><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(0, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">As required by Section 143 (3) of the Act, we report that:</span></p>

                                                <p dir="ltr" style="line-height: 1.295; margin-top: 0pt; margin-bottom: 8pt;"><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(0, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">(a) We have sought and obtained all the information and explanations which to the best of our knowledge and belief were necessary for the purposes of our audit.</span></p>

                                                <p dir="ltr" style="line-height: 1.295; margin-top: 0pt; margin-bottom: 8pt;"><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(0, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">(b) In our opinion, proper books of account as required by law have been kept by the Company so far as it appears from our examination of those books.</span></p>

                                                <p dir="ltr" style="line-height: 1.295; margin-top: 0pt; margin-bottom: 8pt;"><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(0, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">(c) The Balance Sheet, the Statement of Profit and Loss, and the Cash Flow Statement dealt with by this Report are in agreement with the books of account.</span></p>

                                                <p dir="ltr" style="line-height: 1.295; margin-top: 0pt; margin-bottom: 8pt;"><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(0, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">(d) In our opinion, the aforesaid standalone financial statements comply with the Accounting Standards specified under Section 133 of the Act, read with Rule 7 of the Companies (Accounts) Rules, 2014.</span></p>

                                                <p dir="ltr" style="line-height: 1.295; margin-top: 0pt; margin-bottom: 8pt;"><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(0, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">(e) On the basis of the written representations received from the directors as on 31st March, 20XX taken on record by the Board of Directors, none of the directors is disqualified as on 31st March, 20XX from being appointed as a director in terms of Section 164 (2) of the Act.</span></p>

                                                <p dir="ltr" style="line-height: 1.295; margin-top: 0pt; margin-bottom: 8pt;"><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(0, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">(f) With respect to the adequacy of the internal financial controls over financial reporting of the Company and the operating effectiveness of such controls, refer to our separate Report in &ldquo;Annexure A&rdquo;.</span></p>

                                                <p dir="ltr" style="line-height: 1.295; margin-top: 0pt; margin-bottom: 8pt;"><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(0, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">(g) With respect to the other matters to be included in the Auditor&rsquo;s Report in accordance with Rule 11 of the Companies (Audit and Auditors) Rules, 2014, in our opinion and to the best of our information and according to the explanations given to us:</span></p>

                                                <p>
                                                    <br>
                                                </p>

                                                <p dir="ltr" style="line-height: 1.295; margin-top: 0pt; margin-bottom: 8pt;"><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(255, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">** PLEASE &nbsp;REVIEW THIS SECTION AND EDIT AND REMOVE AS NEEDED**</span></p>

                                                <p dir="ltr" style="line-height: 1.295; margin-top: 0pt; margin-bottom: 8pt;"><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(0, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">If applicable, mention any pending litigations which would impact the financial position of the Company.</span></p>

                                                <p dir="ltr" style="line-height: 1.295; margin-top: 0pt; margin-bottom: 8pt;"><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(0, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">If applicable, mention if the Company has any long-term contracts including derivative contracts for which there were any material foreseeable losses.</span></p>

                                                <p dir="ltr" style="line-height: 1.295; margin-top: 0pt; margin-bottom: 8pt;"><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(0, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">If applicable, mention any delay in payment of statutory dues.</span></p>

                                                <p>
                                                    <br>
                                                </p>

                                                <p dir="ltr" style="line-height: 1.295; margin-top: 0pt; margin-bottom: 8pt;"><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(255, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">Place:</span></p>

                                                <p dir="ltr" style="line-height: 1.295; margin-top: 0pt; margin-bottom: 8pt;"><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(255, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">Date:</span></p>

                                                <p dir="ltr" style="line-height: 1.295; margin-top: 0pt; margin-bottom: 8pt;"><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(255, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">For <?php echo $clientName; ?></span></p>

                                                <p dir="ltr" style="line-height: 1.295; margin-top: 0pt; margin-bottom: 8pt;"><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(255, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">Chartered Accountants</span></p>

                                                <p dir="ltr" style="line-height: 1.295; margin-top: 0pt; margin-bottom: 8pt;"><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(255, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">(Firm&rsquo;s Registration No.)</span></p>

                                                <p>
                                                    <br>
                                                </p>

                                                <p dir="ltr" style="line-height: 1.295; margin-top: 0pt; margin-bottom: 8pt;"><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(255, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">Signature</span></p>

                                                <p>
                                                    <br>
                                                </p>

                                                <p dir="ltr" style="line-height: 1.295; margin-top: 0pt; margin-bottom: 8pt;"><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(255, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">(CA. Name)</span></p>

                                                <p>
                                                    <br>
                                                </p>

                                                <p dir="ltr" style="line-height: 1.295; margin-top: 0pt; margin-bottom: 8pt;"><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(255, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">(Designation)</span></p>

                                                <p>
                                                    <br>
                                                </p>

                                                <p dir="ltr" style="line-height: 1.295; margin-top: 0pt; margin-bottom: 8pt;"><span style="font-size: 11pt; font-family: Calibri, sans-serif; color: rgb(255, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-variant-east-asian: normal; font-variant-position: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">(Membership No. XXXX)</span></p>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                        }
                        elseif($prog_id == 24){
                            $row1 = ($con->query("select balance_asset, balance_liability,pl_income,pl_expense from sub_materiality where workspace_id = '$wid'"))->fetch_assoc();
                            ?><br>
                            <!-- Name-Period -->
                            <div class="form-group col-md-12 d-flex">
                                <div class="form-group col-md-6 text-center">
                                    <label for="">Name :- <?php echo $clientName ?></label>
                                </div>
                                <div class="form-group col-md-6 text-center">
                                    <label for="">Period :- <?php $period = $con->query("select datefrom,dateto from workspace where id='$wid'")->fetch_assoc();
                                        $from = explode('-',$period['datefrom']);
                                        $to = explode('-',$period['dateto']);
                                        echo $from[0]."-".$to[0];
                                        ?>
                                    </label>
                                </div>
                            </div><hr>
                            <!-- Input Box -->
                            <div class="row">
                                <div class="form-row p-top">
                                    <div class="form-group col-md-6">
                                        <label for="input1">Balance Assets Scope</label>
                                        <input type="text" class="form-control" name="aScope"
                                                value="<?php echo $row1['balance_asset']; ?>" readonly>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="input2">Balance Liability Scope</label>
                                        <input type="text" class="form-control" name="lScope"
                                                value="<?php echo $row1['balance_liability']; ?>" readonly>
                                    </div>
                                </div>
                                <div class="form-row p-top">
                                    <div class="form-group col-md-6">
                                        <label for="input1">PL- Income Scope</label>
                                        <input type="text" class="form-control" name="aScope"
                                                value="<?php echo $row1['pl_income']; ?>" readonly>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="input2">PL- Expense Scope</label>
                                        <input type="text" class="form-control" name="lScope"
                                                value="<?php echo $row1['pl_expense']; ?>" readonly>
                                    </div>
                                </div>
                            </div><br><br>
                            <!-- Add Misstatements Button -->
                            <div class="row d-flex justify-content-center">
                                <div class="form-group d-flex align-items-center">
                                    <input class="btn btn-primary" type="submit" value="Add Misstatements" data-toggle="modal" data-target="#audit_summery_modal">
                                </div> &nbsp;
                                <a target="#" href="#"><button class="btn bg-violet" id="export2excel">Export</button></a>
                            </div><br>
                            <div id="export_misstatements">
                                <?php
                                $misstatemnentsResult = $con->query("select * from summery_of_misstatements where workspace_id=$wid");
                                if($misstatemnentsResult->num_rows){
                                    
                                    while($misstatementsRow=$misstatemnentsResult->fetch_assoc()){
                                    ?>
                                        <div>
                                            <div class="row col-md-8">
                                                <label for="" class=" d-flex">
                                                    <label class="form-group col-md-11" style="color:blue; font-weight:bold;" for=""><?php echo $misstatementsRow['adjust_number'];?></label>
                                                    <label for="" class="">
                                                        <i class="fas fa-pen editMisstatement" style="color:blue !important; cursor: pointer;" id="<?php echo $misstatementsRow['id']; ?>" ></i>
                                                        <i class="fas fa-trash-alt deleteMisstatementModal" style="color:red !important; cursor: pointer;" id="<?php echo $misstatementsRow['id']; ?>" ></i>
                                                    </label>
                                                </label><br><br>
                                                <label class="form-group" for="">Type : <?php echo $misstatementsRow['type'];?></label><br>
                                                <label class="form-group" for="">Misstatement : <?php echo $misstatementsRow['misstatements'];?></label><br>
                                                <label class="form-control" readonly><?php echo $misstatementsRow['description'];?></label><hr>

                                            </div>
                                            <div class="form-group col-md-8">
                                                <table class="table table-borderless col-md-12" id="audit_misstatements">
                                                    <tbody class="col-md-12">
                                                    <style> 
                                                        td, tr, table{
                                                            text-align: left;
                                                        }
                                                    </style>
                                                        <?php
                                                            $misstatemnentsLogResult = $con->query("select * from summery_of_misstatements_log where summery_of_misstatements_id='".$misstatementsRow['id']."'");
                                                            while($misstatementsLogRow=$misstatemnentsLogResult->fetch_assoc()){
                                                        ?>
                                                            <tr class="d-flex justify-content-between mr-5">                                                            
                                                                <td style="padding:0;"><?php echo $misstatementsLogRow['account']; ?></td>
                                                                <td style="padding:0;"><?php echo numberToCurrency($misstatementsLogRow['amount']); ?></td>       
                                                            </tr>
                                                        <?php 
                                                        } 
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div><br><br>
                                        </div>
                                    <?php
                                    }
                                ?>
                                    <div class="row d-flex justify-content-center">
                                        <?php
                                            $checkReviewStatus = $con->query("select count(signoff_prepare_log.id) total from signoff_prepare_log inner join user on signoff_prepare_log.user_id=user.id where workspace_id=".$wid." and prog_id=24")->fetch_assoc();
                                            if($checkReviewStatus['total']){
                                                ?>
                                                    <button class="btn btn-info" id="reviewSubmitAuditSummery">Review Sign-Off</button>
                                                <?php
                                            }
                                            ?>
                                            &nbsp;
                                            <button class="btn btn-success" id="prepareSubmitAuditSummery">Prepare Sign-Off</button> 
                                    </div>
                                <?php
                                } 
                                ?>
                            </div>
                            <?php
                        }
                        elseif($prog_id == 258){
                            ?>
                            <div class="flex-column col-md-10 col-lg-10 col-sm-10 mt-3">
                                <div class="form-group col-md-12 col-lg-12 col-sm-12">    
                                    <p><strong>Entity name</strong> : <?php echo $clientName = $con->query("select name from workspace inner join client on workspace.client_id = client.id where workspace.id = $wid")->fetch_assoc()['name'];  ?></p>

                                    <p><strong>Date of financial statement</strong> :<?php $workspaceDateRange = $con->query("select datefrom, dateto from workspace where id = $wid")->fetch_assoc(); echo $workspaceDateRange['datefrom'].' TO '.$workspaceDateRange['dateto']; ?> </p>

                                    <p><strong>Purpose</strong></p>

                                    <p>This form is used to document Management responses for initial inquiries.</p>

                                    
                                    <p>The partner in charge of the audit evidence approval of:</p>

                                    <ul class="ml-5">
                                        <li>Appropriate inquiries have been made to the management.</li>
                                        <li>Also Whether the documentation included in the workpapers is adequate</li>
                                    </ul>
                                </div>
                                <div id="question_div" class="form-group col-md-12 col-lg-12 col-sm-12"> 
                                    <!-- <p><strong>Add Question</strong></p> -->
                                    <button class="btn btn-outline-primary mb-3 ml-1" href="#" data-toggle="modal" data-target="#addQuestionModal">
                                        <span>Add Question</span>
                                    </button>
                                    <form id="inquiring_management_form" action="inquiringManagementSubmit" method="post" enctype="multipart/form-data">
                                            <?php 
                                            $count = $i = 0;
                                                $questionResult = $con->query("select id, inquiring_of_management_questions question, answer_option, answer_textarea from inquiring_of_management_questions_answer where workspace_id = '$wid'");
                                                while($questionRow = $questionResult->fetch_assoc()){
                                                    $i++;
                                                    ?>
                                                    <div class="mb-3">
                                                        <input type="hidden" name="answer[<?php echo $count; ?>][0]" value="<?php echo $questionRow['id']; ?>">
                                                        <label for=""><?php echo $i.') '.$questionRow['question']; ?></label>
                                                        <div class="d-flex">
                                                            <select name="answer[<?php echo $count; ?>][1]" class="form-control">
                                                                <option value="">Choose a option</option>
                                                                <option value="YES" <?php if($questionRow['answer_option'] == 'YES') echo "selected"; ?>>YES</option>
                                                                <option value="NO" <?php if($questionRow['answer_option'] == 'NO') echo "selected"; ?>>NO</option>
                                                                <option value="NA" <?php if($questionRow['answer_option'] == 'NA') echo "selected"; ?>>NA</option>
                                                            </select>
                                                            &ensp;
                                                            <input name="answer[<?php echo $count; ?>][2]" class="form-control" placeholder="Have anything on mind type here..." value="<?php echo $questionRow['answer_textarea']; ?>">
                                                            <?php
                                                                $checkQuestion = $con->query("select inquiring_of_management_questions_answer.id id from inquiring_of_management_questions_answer where workspace_id = $wid and inquiring_of_management_questions not in ( select question from inquiring_of_management_questions )  and inquiring_of_management_questions = '".$questionRow['question']."'");
                                                                if($checkQuestion->num_rows){
                                                                    $questionId = $checkQuestion->fetch_assoc();
                                                                    ?>
                                                                        <i class="fas fa-edit editInquiringManagement" style="color:blue !important; cursor: pointer;" id="<?php echo $questionId['id']; ?>" ></i>
                                                                        <i class="fas fa-trash-alt deleteInquiringManagementModal" style="color:red !important; cursor: pointer;" id="<?php echo $questionId['id']; ?>" ></i>
                                                                    <?php
                                                                }                                                    
                                                            ?>
                                                        </div>
                                                    </div>
                                            <?php
                                            $count++;
                                                }
                                            ?>
                                        <input type="hidden" name="wid" value="<?php echo $wid; ?>">
                                        <textarea class="form-control mb-3" name="textarea" id="" cols="30" rows="5" placeholder="Any Other Observations..."><?php echo $con->query("select count(textarea) total from inquiring_of_management_questions_textarea where workspace_id = $wid")->fetch_assoc()['total'] == 1 ? trim($con->query("select textarea from inquiring_of_management_questions_textarea where workspace_id = $wid")->fetch_assoc()['textarea']) : ''; ?></textarea>

                                        <div class="row d-flex justify-content-center align-items-center">
                                            <input class="btn btn-upload" type="file" name="file" accept=".pdf, .xls, .xlsx, .txt, .csv, .doc, .docx, .rtf, .xlmb" style="width:30% !important;">&nbsp;
                                            <input type="submit" id="inquiring_of_management_questions_form" class="btn btn-success align-middle" value="Save"> 
                                        </div><br>
                                    </form>
                                </div>
                                <div class="row d-flex justify-content-center align-items-center p-top">
                                    <?php
                                    $query = "select * from inquiring_of_management_files where wid='$wid'";
                                    $result = $con->query($query);
                                    ?>
                                        <ul class="custom-list list-bg" style="padding-bottom: 2% !important;">
                                            <span class="d-flex justify-content-center align-items-center">Uploaded Files</span>
                                            <?php 
                                            while ($row = $result->fetch_assoc()) {
                                                ?>
                                                <li class="custom-list-items custom-list-items-action">
                                                    <a href="#" class="fileEditDownload" target="_blank" download id="<?php echo $row['files']; ?>"><?php echo $row['files']; ?></a>
                                                </li>
                                                <?php
                                            } ?>
                                        </ul>
                                </div>   
                                <div class="row d-flex justify-content-center">
                                    <?php
                                    $checkReviewStatus = $con->query("select count(signoff_prepare_log.id) total from signoff_prepare_log inner join user on signoff_prepare_log.user_id=user.id where workspace_id=".$wid." and prog_id=258")->fetch_assoc();
                                    if($checkReviewStatus['total']){
                                        ?>
                                            <button class="btn btn-info" id="reviewSubmitInquiryManagement">Review Sign-Off</button>
                                        <?php
                                    }
                                    ?>
                                        &nbsp;
                                        <button class="btn btn-success" id="prepareSubmitInquiryManagement">Prepare Sign-Off</button>
                                </div>
                            </div>
                            <?php
                        }
                        elseif($prog_id == 259){
                            $row1 = ($con->query("select balance_asset, balance_liability,pl_income,pl_expense from sub_materiality where workspace_id = '$wid'"))->fetch_assoc();
                            ?><br>
                            <!-- Name-End Date -->
                            <div class="form-group col-md-12 d-flex">
                                <div class="form-group col-md-6 text-center">
                                    <label for="">Entity Name :- <?php echo $clientName ?></label>
                                </div>
                                <div class="form-group col-md-6 text-center">
                                    <label for="">Year End Date :- <?php $period = $con->query("select dateto from workspace where id='$wid'")->fetch_assoc();
                                        echo $period['dateto'];
                                        ?>
                                    </label>
                                </div>
                            </div><hr> 

                            <!-- Export Estimate -->
                            <div class="row d-flex justify-content-center">
                                <a target="#" href="#"><button class="btn bg-violet" id="exportEstimate">Export</button></a>
                            </div><br>

                            <!-- Purpose DIV -->
                            <div>
                                <label>Purpose:</label><br>
                                <span>
                                    Team has assessed the level of risk for each estimate and then categorize each estimate 
                                    (i.e., lower risk estimate, higher risk estimate or significant risk estimate) 
                                    to assist in determining further procedures and same has been approved by Partner in charge.<br>
                                    Team can discuss these estimates during planning event, including discussion of 
                                    estimates and risks of material misstatement due to fraud and error
                                    Perform procedures to review accounting estimates for evidence of management bias.
                                </span>
                            </div><hr>

                             <!-- Add Estimate Button -->
                             <div class="row d-flex justify-content-center">
                                <div class="form-group d-flex align-items-center">
                                    <input class="btn btn-primary" type="submit" value="Add Estimate" id="addEstimateRow">
                                </div>
                            </div><br>

                            <!-- Estimate Table -->
                            <div class="row" id="export_Estimate_page">    
                                <div class="tableFixHead">
                                    <form action="accountingEstimatesSubmit?&wid=<?php echo $wid; ?>" method="post" enctype="multipart/form-data">
                                        <div class="row" style="margin: 0 !important;">    
                                            <div>
                                                <table id="addEstimate">
                                                    <thead>
                                                        <tr>
                                                            <th style="border-bottom-left-radius: 0px !important;">Type of Estimate</th>
                                                            <th>Name of Estimate</th>
                                                            <th>Related accounts and disclosures</th>
                                                            <th colspan="2">Amounts</th>
                                                            <th colspan="5">Risk Assessment</th>
                                                            <th>Overall Risk</th>
                                                            <th style="border-bottom-right-radius: 0px !important;">Action</th>
                                                        </tr>
                                                        <tr>
                                                            <th style="border-top-left-radius: 0px !important;"></th>
                                                            <th></th>
                                                            <th></th>
                                                            <th>PY</th>
                                                            <th>CY</th>
                                                            <th>C</th>
                                                            <th>E/O</th>
                                                            <th>M/V</th>
                                                            <th>R&O</th>
                                                            <th>P&D</th>
                                                            <th></th>
                                                            <th style="border-top-right-radius: 0px !important;"></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="accounting_estimates">
                                                    <?php
                                                        $result = $con->query("select * from accounting_estimates where workspace_id=$wid");
                                                        if($result->num_rows){
                                                            while($row=$result->fetch_assoc()){
                                                                ?>
                                                                <tr>
                                                                    <td>
                                                                        <select name="submitEstimate[type][]" class="form-control minWidth150" required>
                                                                            <option value="Quantitative" <?php if($row['type'] == "Quantitative") echo "selected"; ?>>Quantitative</option>
                                                                            <option value="Qualitative"  <?php if($row['type'] == "Qualitative") echo "selected"; ?>>Qualitative</option>
                                                                        </select>
                                                                    </td>
                                                                    <td><input name="submitEstimate[name][]" type="text" value="<?php echo $row['nameEstimate']; ?>"></td>
                                                                    <td><input name="submitEstimate[account][]" type="text" value="<?php echo $row['account']; ?>"></td>
                                                                    <td><input name="submitEstimate[py][]" type="text" value="<?php echo numberToCurrency(trim($row['py'])); ?>"></td>
                                                                    <td><input name="submitEstimate[cy][]" type="text" value="<?php echo numberToCurrency(trim($row['cy'])); ?>"></td>
                                                                    <td>
                                                                    <select name="submitEstimate[c][]" class="form-control minWidth150"required>
                                                                        <option value="Low" <?php if($row['c'] == "Low") echo "selected"; ?>>Low</option>
                                                                        <option value="Moderate" <?php if($row['c'] == "Moderate") echo "selected"; ?>>Moderate</option>
                                                                        <option value="High" <?php if($row['c'] == "High") echo "selected"; ?>>High</option>
                                                                        <option value="NA" <?php if($row['c'] == "NA") echo "selected"; ?>>NA</option>
                                                                    </select>
                                                                    </td>
                                                                    <td>
                                                                    <select name="submitEstimate[eo][]" class="form-control minWidth150"required>
                                                                        <option value="Low" <?php if($row['eo'] == "Low") echo "selected"; ?>>Low</option>
                                                                        <option value="Moderate" <?php if($row['eo'] == "Moderate") echo "selected"; ?>>Moderate</option>
                                                                        <option value="High" <?php if($row['eo'] == "High") echo "selected"; ?>>High</option>
                                                                        <option value="NA" <?php if($row['eo'] == "NA") echo "selected"; ?>>NA</option>
                                                                    </select>
                                                                    </td>
                                                                    <td>
                                                                    <select name="submitEstimate[mv][]" class="form-control minWidth150"required>
                                                                        <option value="Low" <?php if($row['mv'] == "Low") echo "selected"; ?>>Low</option>
                                                                        <option value="Moderate" <?php if($row['mv'] == "Moderate") echo "selected"; ?>>Moderate</option>
                                                                        <option value="High" <?php if($row['mv'] == "High") echo "selected"; ?>>High</option>
                                                                        <option value="NA" <?php if($row['mv'] == "NA") echo "selected"; ?>>NA</option>
                                                                    </select>
                                                                    </td>
                                                                    <td>
                                                                    <select name="submitEstimate[ro][]" class="form-control minWidth150"required>
                                                                        <option value="Low" <?php if($row['ro'] == "Low") echo "selected"; ?>>Low</option>
                                                                        <option value="Moderate" <?php if($row['ro'] == "Moderate") echo "selected"; ?>>Moderate</option>
                                                                        <option value="High" <?php if($row['ro'] == "High") echo "selected"; ?>>High</option>
                                                                        <option value="NA" <?php if($row['ro'] == "NA") echo "selected"; ?>>NA</option>
                                                                    </select>
                                                                    </td>
                                                                    <td>
                                                                    <select name="submitEstimate[pd][]" class="form-control minWidth150"required>
                                                                        <option value="Low" <?php if($row['pd'] == "Low") echo "selected"; ?>>Low</option>
                                                                        <option value="Moderate" <?php if($row['pd'] == "Moderate") echo "selected"; ?>>Moderate</option>
                                                                        <option value="High" <?php if($row['pd'] == "High") echo "selected"; ?>>High</option>
                                                                        <option value="NA"  <?php if($row['pd'] == "NA") echo "selected"; ?>>NA</option>
                                                                    </select>
                                                                    </td>
                                                                    <td>
                                                                    <select name="submitEstimate[risk][]" class="form-control minWidth150"required>
                                                                        <option value="Low Risk" <?php if($row['risk'] == "Low Risk") echo "selected"; ?>>Low Risk</option>
                                                                        <option value="Significant Risk" <?php if($row['risk'] == "Significant Risk") echo "selected"; ?>>Significant Risk</option>
                                                                        <option value="High Risk" <?php if($row['risk'] == "High Risk") echo "selected"; ?>>High Risk</option>
                                                                        <option value="NA" <?php if($row['risk'] == "NA") echo "selected"; ?>>NA</option>
                                                                    </select>
                                                                    </td>
                                                                    <td>
                                                                        <i class="fas fa-trash-alt deleteAccountingEstimate" style="color:red !important; cursor: pointer;" id="<?php echo $row['id']; ?>" ></i>
                                                                    </td>
                                                                </tr>
                                                           <?php }
                                                        }
                                                    else{
                                                    ?>
                                                    <tr id="addRowEstimate0">
                                                        <td>
                                                            <select name="submitEstimate[type][]" class="form-control minWidth150" required>
                                                                <option value="Quantitative">Quantitative</option>
                                                                <option value="Qualitative">Qualitative</option>
                                                            </select>
                                                        </td>
                                                        <td><input name="submitEstimate[name][]" type="text"></td>
                                                        <td><input name="submitEstimate[account][]" type="text"></td>
                                                        <td> <input name="submitEstimate[py][]" type="number"> </td>
                                                        <td> <input name="submitEstimate[cy][]" type="number"> </td>
                                                        <td>
                                                            <select name="submitEstimate[c][]" class="form-control minWidth150"required>
                                                                <option value="Low">Low</option>
                                                                <option value="Moderate">Moderate</option>
                                                                <option value="High">High</option>
                                                                <option value="NA">NA</option>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <select name="submitEstimate[eo][]" class="form-control minWidth150"required>
                                                                <option value="Low">Low</option>
                                                                <option value="Moderate">Moderate</option>
                                                                <option value="High">High</option>
                                                                <option value="NA">NA</option>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <select name="submitEstimate[mv][]" class="form-control minWidth150"required>
                                                                <option value="Low">Low</option>
                                                                <option value="Moderate">Moderate</option>
                                                                <option value="High">High</option>
                                                                <option value="NA">NA</option>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <select name="submitEstimate[ro][]" class="form-control minWidth150"required>
                                                                <option value="Low">Low</option>
                                                                <option value="Moderate">Moderate</option>
                                                                <option value="High">High</option>
                                                                <option value="NA">NA</option>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <select name="submitEstimate[pd][]" class="form-control minWidth150"required>
                                                                <option value="Low">Low</option>
                                                                <option value="Moderate">Moderate</option>
                                                                <option value="High">High</option>
                                                                <option value="NA">NA</option>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <select name="submitEstimate[risk][]" class="form-control minWidth150"required>
                                                                <option value="Low Risk">Low Risk</option>
                                                                <option value="Significant Risk">Significant Risk</option>
                                                                <option value="High Risk">High Risk</option>
                                                                <option value="NA">NA</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                        <tr id="addRowEstimate1"></tr>
                                                    <?php }
                                                    ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Documents Observations</label>
                                            <textarea name= "comments" class="form-control" rows="3"><?php $documentObserve = $con->query("select comments from accounting_estimates_comments where workspace_id=$wid"); if($documentObserve->num_rows > 1) $documentObserve->fetch_assoc()['comments'];?></textarea>
                                        </div>
                                        <div class="row d-flex justify-content-center align-items-center">
                                            <input class="btn btn-upload" type="file" name="file" accept=".pdf, .xls, .xlsx, .txt, .csv, .doc, .docx, .rtf, .xlmb" style="width:30% !important;">&nbsp;
                                            <input type="submit" class="btn btn-success align-middle" value="Save"> 
                                        </div><br>
                                    </form> 
                                    <div class="row d-flex justify-content-center">
                                        <?php
                                            $checkReviewStatus = $con->query("select count(signoff_prepare_log.id) total from signoff_prepare_log inner join user on signoff_prepare_log.user_id=user.id where workspace_id=".$wid." and prog_id=259")->fetch_assoc();
                                            if($checkReviewStatus['total']){
                                                ?>
                                                    <button class="btn btn-info" id="reviewSubmitEstimate">Review Sign-Off</button>
                                                <?php
                                            }
                                            ?>
                                            &nbsp;
                                            <button class="btn btn-success" id="prepareSubmitEstimate">Prepare Sign-Off</button> 
                                    </div><br>
                                    <div class="row d-flex justify-content-center align-items-center p-top">
                                        <?php
                                        $query = "select * from accounting_estimates_files where workspace_id='$wid'";
                                        $result = $con->query($query);
                                        ?>
                                            <ul class="custom-list list-bg" style="padding-bottom: 2% !important;">
                                                <span class="d-flex justify-content-center align-items-center">Uploaded Files</span>
                                            <?php 
                                            while ($row = $result->fetch_assoc()) {
                                                ?>
                                                <li class="custom-list-items custom-list-items-action">
                                                    <a href="#" class="fileEditDownload" target="_blank" download id="<?php echo $row['file_name']; ?>"><?php echo $row['file_name']; ?></a>
                                                </li>
                                                <?php
                                            } ?>
                                            </ul>
                                    </div>                         
                                </div>
                            </div>

                            <?php
                        }
                        elseif($prog_id == 8){
                            ?>
                            <div class="flex-column col-md-10 col-lg-10 col-sm-10 mt-3">
                                <button id="exportGoingConcern" class="btn bg-violet mb-3 ml-2" onclick = "exportGoingConcern()">Export</button>
                                <?php 
                                    $goingConcernDecRadio = $con->query("select * from going_concern where workspace_id = $wid");            
                                    $goingConcernDecRadio = $goingConcernDecRadio->num_rows >= 1 ? $goingConcernDecRadio->fetch_assoc(): '0';                                
                                ?>
                                <form action="goingConcernAjax" method="post" enctype="multipart/form-data">
                                    <div id="goingConcernDiv" class="form-group col-md-12 col-lg-12 col-sm-12">
                                        <p><strong>Entity name</strong> : <?php echo $clientName = $con->query("select name from workspace inner join client on workspace.client_id = client.id where workspace.id = $wid")->fetch_assoc()['name'];  ?></p>

                                        <p><strong>Date of financial statement</strong> :<?php $workspaceDateRange = $con->query("select datefrom, dateto from workspace where id = $wid")->fetch_assoc(); echo $workspaceDateRange['datefrom'].' TO '.$workspaceDateRange['dateto']; ?> </p>

                                        <p><strong>Purpose</strong></p>

                                        <p>This form is used to document our procedures to meet the requirements of Going Concern assessment.</p>
                                        
                                        <p>The partner in charge of the audit evidence approval of:</p>

                                        <ul class="ml-5">
                                            <li>The conclusion on the appropriateness of management’s use of the going concern basis of accounting in the preparation of the financial statements.</li>
                                            <li>The conclusion reached as to whether there is substantial doubt about an entity’s ability to continue as a going concern for a reasonable period.</li>
                                            <li>Whether the documentation included in the workpapers is adequate</li>
                                        </ul>

                                        <p><strong>Information produced by the entity.</strong></p>
                                        <p>The risks of information provided by the entity, when applicable, are addressed as part of our going concern procedures.</p>
                                        <br>
                                        <p><h3>Part A: Scope &amp; Strategy</h3></p>
                                        <p><b>Consider whether there are conditions or events that raise substantial doubt about Going Concern</b></p>
                                        <p>We may make inquiries of management when considering whether there are conditions or events that raise substantial doubt. If we do, indicate the names and titles of whom we made inquiries.</p>
                                        <br>
                                    
                                        <table id="addPartAATable" class="table thead-light table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th><b>Name:</b></th>
                                                    <th><b>Title:</b></th>
                                                    <th><b>Date:</b></th>
                                                </tr>
                                            </thead>
                                            <tbody>    
                                            <?php 
                                            $result = $con->query("select name, title, date from going_concern_name_title_date where workspace_id = '$wid' and part = 'A'");
                                            if($result->num_rows > 0){
                                                $i = 0;
                                                while($row = $result->fetch_assoc()){
                                                    ?>
                                                    <tr id="<?php echo $i; ?>">
                                                        <td><input type="text" name="going_concern_name_title_date_a[<?php echo $i; ?>][0]" class="form-group col-md-12 col-lg-12 col-sm-12" value="<?php echo $row['name']; ?>"></td>
                                                        <td><input type="text" name="going_concern_name_title_date_a[<?php echo $i; ?>][1]" class="form-group col-md-12 col-lg-12 col-sm-12" value="<?php echo $row['title']; ?>"></td>
                                                        <td><input type="date" class="form-control" name="going_concern_name_title_date_a[<?php echo $i++; ?>][2]" class="form-group col-md-12 col-lg-12 col-sm-12" value="<?php echo $row['date']; ?>"></td>
                                                    </tr>
                                                    <?php
                                                }
                                            }
                                            else{
                                                ?>
                                                <tr id="0">
                                                    <td><input type="text" name="going_concern_name_title_date_a[0][0]" class="form-group col-md-12 col-lg-12 col-sm-12"></td>
                                                    <td><input type="text" name="going_concern_name_title_date_a[0][1]" class="form-group col-md-12 col-lg-12 col-sm-12"></td>
                                                    <td><input type="date" class="form-control" name="going_concern_name_title_date_a[0][2]" class="form-group col-md-12 col-lg-12 col-sm-12"></td>
                                                </tr>
                                                <?php
                                            }
                                            ?>
                                            </tbody>
                                        </table>
                                        <button id="addPartAARow" class="btn btn-outline-primary mb-3">Add Row</button>
                                        <style>
                                            td, tr, table{
                                                text-align: left;
                                            }
                                            .table thead th{
                                                vertical-align: middle;
                                            }
                                        </style>
                                        <table id="addPartABTable" class="table thead-light table-stripped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th><b>Procedures</b></th>
                                                    <th><b>Document results of procedures here and cross reference to the applicable documentation:</b></th>
                                                    <th><b>Action</b></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            $i = 0;
                                            $alphabet = 'a';
                                            $result = $con->query("select id, procedure_data, free_text from going_concern_procedures where workspace_id = '$wid' and part = 'A' ");
                                            while($row = $result->fetch_assoc()){
                                            ?>
                                                <tr>
                                                    <td><label for=""><?php echo $alphabet++.')'.$row['procedure_data']; ?></label></td>
                                                    <input type="hidden" name="freeTextA[<?php echo $i; ?>][0]" value="<?php echo $row['id']; ?>">
                                                    <td><input type="text" name="freeTextA[<?php echo $i++; ?>][1]" class="form-group col-md-12 col-lg-12 col-sm-12" value="<?php echo $row['free_text']; ?>"></td>
                                                    <td>
                                                    <?php
                                                        $checkQuestion = $con->query("select id from going_concern_procedures where workspace_id = '$wid' and going_concern_procedures.part = 'A' and procedure_data not in (SELECT going_concern_default_procedure.procedure from going_concern_default_procedure where part = 'A') and id= '".$row['id']."'");
                                                        if($checkQuestion->num_rows){
                                                            ?>
                                                                <i class="fas fa-edit editProcedure" style="color:blue !important; cursor: pointer;" id="<?php echo $row['id']; ?>" ></i>
                                                                <i class="fas fa-trash-alt deleteProcedureModal" style="color:red !important; cursor: pointer;" id="<?php echo $row['id']; ?>" ></i>
                                                            <?php
                                                        }                                                    
                                                    ?></td>
                                                </tr>
                                                <?php       
                                            }
                                            ?>
                                            </tbody>
                                        </table>
                                        <button id="addPartABRow" class="btn btn-outline-primary mb-3 ml-1" href="#" data-toggle="modal" data-target="#addProcedureABModal">
                                            <span>Add Procedure</span>
                                        </button>
                                        <p>Document additional considerations as needed:</p>
                                        <textarea name="desc_a" class="form-control mb-3" cols="30" rows="5"><?php echo $goingConcernDecRadio != 0 ? $goingConcernDecRadio['desc_a'] : '' ; ?></textarea>
                                        <br>
                                        <p><h3>Part B: Execute &amp; Conclude</h3></p>
                                        <p>Procedures to evaluate the entity’s ability to continue as a going concern</p>
                                        <p>We evaluate whether there is substantial doubt about the entity’s ability to continue as a going concern for a reasonable period of time based on the results of our procedures performed during the audit. We consider whether our procedures identify conditions or events that may raise substantial doubt about the entity’s ability to continue as a going concern.</p>
                                        <p>Management’s evaluation and supporting analysis of going concern is often an important consideration to our evaluation of the entity’s ability to continue as a going concern.</p>
                                        <p>We may make inquiries of management when evaluating the entity’s ability to continue as a going concern. If we do, indicate the names and titles of whom we made inquiries</p>
                                        <table id="addPartBATable" class="table thead-light table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th><b>Name:</b></th>
                                                    <th><b>Title:</b></th>
                                                    <th><b>Date:</b></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php 
                                            $result = $con->query("select name, title, date from going_concern_name_title_date where workspace_id = '$wid' and part = 'B'");
                                            if($result->num_rows > 0){
                                                $i = 0;
                                                while($row = $result->fetch_assoc()){
                                                    ?>
                                                    <tr id="<?php echo $i; ?>">
                                                        <td><input type="text" name="going_concern_name_title_date_b[<?php echo $i; ?>][0]" class="form-group col-md-12 col-lg-12 col-sm-12" value="<?php echo $row['name']; ?>"></td>
                                                        <td><input type="text" name="going_concern_name_title_date_b[<?php echo $i; ?>][1]" class="form-group col-md-12 col-lg-12 col-sm-12" value="<?php echo $row['title']; ?>"></td>
                                                        <td><input type="date" class="form-control" name="going_concern_name_title_date_b[<?php echo $i++; ?>][2]" class="form-group col-md-12 col-lg-12 col-sm-12" value="<?php echo $row['date']; ?>"></td>
                                                    </tr>
                                                    <?php
                                                }
                                            }
                                            else{
                                                ?>
                                                <tr id="0">
                                                    <td><input type="text" name="going_concern_name_title_date_b[0][0]" class="form-group col-md-12 col-lg-12 col-sm-12"></td>
                                                    <td><input type="text" name="going_concern_name_title_date_b[0][1]" class="form-group col-md-12 col-lg-12 col-sm-12"></td>
                                                    <td><input type="date" class="form-control" name="going_concern_name_title_date_b[0][2]" class="form-group col-md-12 col-lg-12 col-sm-12"></td>
                                                </tr>
                                                <?php
                                            }
                                            ?>
                                            </tbody>
                                        </table>
                                        <button id="addPartBARow" class="btn btn-outline-primary mb-3">Add Row</button>
                                        <style>
                                            td, tr, table{
                                                text-align: left;
                                            }
                                            .table thead th{
                                                vertical-align: middle;
                                            }
                                        </style> 
                                        <table id="addPartBBTable" class="table thead-light table-stripped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th><b>Procedures</b></th>
                                                    <th><b>Document results of procedures here and cross reference to the applicable documentation:</b></th>
                                                    <th><b>Action</b></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            $i = 0;
                                            $alphabet = 'a';
                                            $result = $con->query("select id, procedure_data, free_text from going_concern_procedures where workspace_id = '$wid' and part = 'B' ");
                                            while($row = $result->fetch_assoc()){
                                            ?>
                                                <tr>
                                                    <td><label for=""><?php echo $alphabet++.')'.$row['procedure_data']; ?></label></td>
                                                    <input type="hidden" name="freeTextB[<?php echo $i; ?>][0]" value="<?php echo $row['id']; ?>">
                                                    <td><input type="text" name="freeTextB[<?php echo $i++; ?>][1]" class="form-group col-md-12 col-lg-12 col-sm-12" value="<?php echo $row['free_text']; ?>"></td>
                                                    <td>
                                                    <?php
                                                        $checkQuestion = $con->query("select id from going_concern_procedures where workspace_id = '$wid' and going_concern_procedures.part = 'B' and procedure_data not in (SELECT going_concern_default_procedure.procedure from going_concern_default_procedure where part = 'B') and id= '".$row['id']."'");
                                                        if($checkQuestion->num_rows){
                                                            ?>
                                                                <i class="fas fa-edit editProcedure" style="color:blue !important; cursor: pointer;" id="<?php echo $row['id']; ?>" ></i>
                                                                <i class="fas fa-trash-alt deleteProcedureModal" style="color:red !important; cursor: pointer;" id="<?php echo $row['id']; ?>" ></i>
                                                            <?php
                                                        }                                                    
                                                    ?></td>
                                                </tr>
                                                <?php       
                                            }
                                            ?>
                                            </tbody>
                                        </table>
                                        <button id="addPartBBRow" class="btn btn-outline-primary mb-3 ml-1" href="#" data-toggle="modal" data-target="#addProcedureBBModal">
                                            <span>Add Procedure</span>
                                        </button>
                                        <p>Document additional considerations as needed:</p>
                                        <textarea name="desc_b" class="form-control mb-3" cols="30" rows="5"><?php echo $goingConcernDecRadio != 0 ? $goingConcernDecRadio['desc_b'] : '' ; ?></textarea>
                                        <br>
                                        <p><b>Conclusion</b></p>
                                        <p><b><i><?php echo $goingConcernDecRadio == '0' ? 'We did not give consideration to modification of our auditor’s report': $goingConcernDecRadio['conclusion_text'] ;?></i></b><i class="fas fa-edit editConclusion" style="color:blue !important; cursor: pointer;" id="<?php echo $goingConcernDecRadio != 0 ? $goingConcernDecRadio['id'] : '0'; ?>" ></i></p>
                                        <p>Choose anyone from the options given below:</p>
                                        <?php
                                        $conclusionNumber = 0;
                                            $conclusionResult = $con->query("select * from going_concern_conclusion where workspace_id = $wid");
                                            while($conclusionRow = $conclusionResult->fetch_assoc()){
                                                ?>
                                                <input type="radio" id="<?php echo $conclusionRow['id']; ?>" name="conclusion" value="<?php echo $conclusionNumber; ?>" 
                                                <?php 
                                                    if($goingConcernDecRadio != 0)
                                                        if($goingConcernDecRadio['going_concern_radio'] == $conclusionNumber) 
                                                            echo 'checked';
                                                ?> >
                                                <label for="<?php echo $conclusionNumber++; ?>"><?php echo $conclusionRow['going_concern_conclusion_data']; ?>&nbsp;<i class="fas fa-edit editTextarea" style="color:blue !important; cursor: pointer;" id="<?php echo $conclusionRow['id']; ?>" ></i></label><br>
                                                <?php
                                            }
                                        ?>
                                        <br>
                                        <p>Document additional considerations as needed:</p>
                                        <textarea name="desc_c" class="form-control mb-3" cols="30" rows="5"><?php echo $goingConcernDecRadio != 0 ? $goingConcernDecRadio['desc_c'] : '' ; ?></textarea>
                                    </div>
                                    <input type="hidden" name="wid" value="<?php echo $wid; ?>">
                                    <input type="hidden" name="pid" value="<?php echo $prog_id; ?>">
                                    <input type="hidden" name="parent_id" value="<?php echo $prog_parentId; ?>">
                                    <input type="hidden" name="status" value="1">
                                    <div class="row d-flex justify-content-center align-items-center">
                                        <input class="btn btn-upload" type="file" name="file" accept=".pdf, .xls, .xlsx, .txt, .csv, .doc, .docx, .rtf, .xlmb" style="width:30% !important;">
                                    </div>
                                    
                                    <div class="row d-flex justify-content-center align-items-center p-top">
                                        <?php
                                        $query = "select * from going_concern_files where workspace_id='$wid'";
                                        $result = $con->query($query);
                                        ?>
                                            <ul class="custom-list list-bg" style="padding-bottom: 2% !important;">
                                                <span class="d-flex justify-content-center align-items-center">Uploaded Files</span>
                                            <?php 
                                            while ($row = $result->fetch_assoc()) {
                                                ?>
                                                <li class="custom-list-items custom-list-items-action">
                                                    <a href="#" class="fileEditDownload" target="_blank" download id="<?php echo $row['fname']; ?>"><?php echo $row['fname']; ?></a>
                                                </li>
                                                <?php
                                            } ?>
                                            </ul>
                                    </div>
                                    <hr>
                                    <div class="col-md-12 d-flex justify-content-center align-items-center">
                                        <hr>
                                        <i class="fas fa-info-circle" style="color:orange !important;"></i>
                                        <strong>Click the save button to save respective files/data before signing off</strong>
                                    </div>
                                    <div class="text-center">
                                        <button id="goingConcernSubmit" class="btn btn-success ">Save</button>
                                    </div> <br>
                                </form>
                                <div class="row d-flex justify-content-center">
                                    <?php
                                        $checkReviewStatus = $con->query("select count(signoff_prepare_log.id) total from signoff_prepare_log inner join user on signoff_prepare_log.user_id=user.id where workspace_id=".$wid." and prog_id=8")->fetch_assoc();
                                        if($checkReviewStatus['total']){
                                            ?>
                                                <button class="btn btn-info" id="reviewSubmitGoingConcern">Review Sign-Off</button>
                                            <?php
                                        }
                                        ?>
                                        &nbsp;
                                        <button class="btn btn-success" id="prepareSubmitGoingConcern">Prepare Sign-Off</button> 
                                </div>
                            </div>
                            <?php
                        }
                        elseif($prog_id == 525){
                            ?>
                                <style>
                                    td:nth-child(4),td:nth-child(5) {
                                        font-weight: bold !important;
                                    }
                                    td:nth-child(2) {
                                        font-weight: normal !important;
                                    }
                                    .card{
                                        border: 1px solid #e3e6f0 !important;
                                    }
                                </style>
                                <br>
                                <a href="#" data-toggle="modal" data-target="#financialStatementChangeSequenceModal"><button class="btn btn-secondary">Change Sequence</button></a>
                                <!-- <a id="financialStatementPdf" target="_blanc" href="financialStatementPdf"><button class="btn btn-primary">Print to PDF</button></a> -->
                                <a id="financialStatementExcel" target="_blanc" href="bridgeExcel"><button class="btn btn-outline-primary">Export to Excel</button></a>
                                <div class="accordion mt-3" id="unauditedBalanceSheetAccordionExample">
                                    <div class="card">
                                        <div class="card-header" id="unauditedBalanceSheetHeadingOne">
                                            <center>
                                                <h2 class="mb-0">
                                                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#unauditedBalanceSheet" aria-expanded="true" aria-controls="unauditedBalanceSheet"><b>Unaudited Balance Sheet</b></button>
                                                </h2>
                                            </center>
                                        </div> 
                                        <div id="unauditedBalanceSheet" class="collapse show" aria-labelledby="unauditedBalanceSheetHeadingOne" data-parent="#unauditedBalanceSheetAccordionExample" style="margin-left: 2.5rem; padding-bottom: 2rem;">
                                            <?php
                                                $accountTypeResult = $con->query("SELECT DISTINCT accounts_type from tb_performance_map where workspace_id='$wid' and ( accounts_type not like '%Expense%' and accounts_type not like '%Revenue%' ) order by accountTypeSeqNumber");
                                            ?>
                                            <br>
                                            <?php
                                                $i = 0;
                                                while($accountTypeRow = $accountTypeResult->fetch_assoc()){
                                                    ++$i;
                                                    ?>
                                                        <br>
                                                        <div class="accordion" id="unauditedBalanceSheetAccordionExample<?php echo $i; ?>">
                                                            <div class="card">
                                                                <div class="card-header" id="unauditedBalanceSheetHeadingOne<?php echo $i; ?>">
                                                                    <h2 class="mb-0">
                                                                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#account_classBS<?php echo $i; ?>" aria-expanded="true" aria-controls="account_classBS<?php echo $i; ?>"><b><?php echo $accountTypeRow['accounts_type']; ?></b></button>
                                                                    </h2>
                                                                </div>  
                                                                <div id="account_classBS<?php echo $i; ?>" class="collapse show" aria-labelledby="unauditedBalanceSheetHeadingOne<?php echo $i; ?>" data-parent="#unauditedBalanceSheetAccordionExample<?php echo $i; ?>" style="margin-left: 2.5rem;">
                                                                    <?php
                                                                        $accountClassResult = $con->query("SELECT accounts_class from tb_performance_map where accounts_type ='".$accountTypeRow['accounts_type']."' and workspace_id='".$wid."' group by accounts_class");
                                                                        while($accountClassRow = $accountClassResult->fetch_assoc()){
                                                                            $unauditedTotal = $auditedTotal = $adjustmentTotal =  0;
                                                                            ?>
                                                                                <br>
                                                                                <h4><?php echo $accountClassRow['accounts_class']; ?></h4>
                                                                                <table class="table" style="width:100%; text-align: left">
                                                                                    <thead>
                                                                                        <th>Financial Statement</th>
                                                                                        <th>Unaudited</th>
                                                                                        <th>Adjustments</th>
                                                                                        <th>Audited</th>
                                                                                    </thead>
                                                                                    <tbody>
                                                                            <?php
                                                                                $financialStatementResult = $con->query("SELECT accounts_name, sum(tb_performance_map.amount) unaudited from tb_performance_map where workspace_id = $wid and accounts_type = '".$accountTypeRow['accounts_type']."' and accounts_class = '".$accountClassRow['accounts_class']."' GROUP BY accounts_name");
                                                                                $clientID = time();
                                                                                while($financialStatementRow = $financialStatementResult->fetch_assoc()){
                                                                                    $adjustment = $con->query("SELECT summery_of_misstatements_log.account, sum(summery_of_misstatements_log.amount) adj from summery_of_misstatements_log INNER join summery_of_misstatements on summery_of_misstatements_log.summery_of_misstatements_id=summery_of_misstatements.id where summery_of_misstatements.workspace_id = $wid and summery_of_misstatements_log.account = '".$financialStatementRow['accounts_name']."' GROUP BY summery_of_misstatements_log.account");
                                                                                    $adjustment = $adjustment->num_rows > 0 ? $adjustment->fetch_assoc()['adj'] : 0;
                                                                                    $unauditedTotal += $financialStatementRow['unaudited'];
                                                                                    $auditedTotal += $financialStatementRow['unaudited']+$adjustment;
                                                                                    $adjustmentTotal += $adjustment;
                                                                                    ?>
                                                                                    <tr>
                                                                                        <td style="text-align: left"><?php echo $financialStatementRow['accounts_name'];?></td>
                                                                                        <td style="text-align: left"><?php echo numberToCurrency($financialStatementRow['unaudited']);?></td>
                                                                                        <td style="text-align: left"><a href="subProgram?did=<?php echo base64_encode(md5(time())); ?>&gid=<?php echo base64_encode(md5(time())); ?>&fid=<?php echo base64_encode(md5(time())); ?>&eid=<?php echo base64_encode(md5(time())); ?>&pid=<?php echo base64_encode(24); ?>&cid=<?php echo base64_encode(md5(time())); ?>&bid=<?php echo base64_encode(md5(time())); ?>&aid=<?php echo base64_encode(md5(time())); ?>&parent_id=<?php echo base64_encode(19); ?>&zid=<?php echo base64_encode(md5(time())); ?>&yid=<?php echo base64_encode(md5(time())); ?>&wid=<?php echo base64_encode($wid); ?>&xid=<?php echo base64_encode(md5(time()));?>"><?php echo numberToCurrency($adjustment);?></a></td>
                                                                                        <td style="text-align: left"><?php echo numberToCurrency($financialStatementRow['unaudited']+ $adjustment);?></td>
                                                                                    </tr>
                                                                                    <?php
                                                                                }
                                                                            ?> 
                                                                                        <tr colspan="5"><td></td></tr>
                                                                                        <tr>
                                                                                            <td style="text-align: left"><h5 style="border-bottom: 1px solid;border-top: 1px solid;">Total</h5></td>
                                                                                            <td style="text-align: left"><h5 style="border-bottom: 1px solid;border-top: 1px solid;"><?php echo numberToCurrency($unauditedTotal); ?></h5></td>
                                                                                            <td style="text-align: left"><h5 style="border-bottom: 1px solid;border-top: 1px solid;"><?php echo numberToCurrency($adjustmentTotal); ?></h5></td>
                                                                                            <td style="text-align: left"><h5 style="border-bottom: 1px solid;border-top: 1px solid;"><?php echo numberToCurrency($auditedTotal); ?></h5></td>
                                                                                        </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            <?php
                                                                        }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php
                                                }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="accordion" id="unauditedProfitLossAccordionExample">
                                    <div class="card">
                                        <div class="card-header" id="unauditedProfitLossHeadingOne">
                                            <center>
                                                <h2 class="mb-0">
                                                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#unauditedProfitLoss" aria-expanded="true" aria-controls="unauditedProfitLoss"><b>Unaudited Profit and Loss</b></button>
                                                </h2>
                                            </center>
                                        </div> 
                                        <div id="unauditedProfitLoss" class="collapse show" aria-labelledby="unauditedProfitLossHeadingOne" data-parent="#unauditedProfitLossAccordionExample" style="margin-left: 2.5rem; padding-bottom: 2rem;">
                                            <?php
                                                $accountTypeResult = $con->query("SELECT DISTINCT accounts_type from tb_performance_map where workspace_id='$wid' and ( accounts_type like '%Expense%' or accounts_type like '%Revenue%' ) order by accountTypeSeqNumber");
                                            ?>
                                            
                                            <?php
                                                $i = 0;
                                                while($accountTypeRow = $accountTypeResult->fetch_assoc()){
                                                    ++$i;
                                                    ?>
                                                        <br>
                                                        <div class="accordion" id="unauditedBalanceSheetAccordionExample<?php echo $i; ?>">
                                                            <div class="card">
                                                                <div class="card-header" id="unauditedBalanceSheetHeadingOne<?php echo $i; ?>">
                                                                    <h2 class="mb-0">
                                                                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#account_classBS<?php echo $i; ?>" aria-expanded="true" aria-controls="account_classBS<?php echo $i; ?>"><b><?php echo $accountTypeRow['accounts_type']; ?></b></button>
                                                                    </h2>
                                                                </div>  
                                                                <div id="account_classBS<?php echo $i; ?>" class="collapse show" aria-labelledby="unauditedBalanceSheetHeadingOne<?php echo $i; ?>" data-parent="#unauditedBalanceSheetAccordionExample<?php echo $i; ?>" style="margin-left: 2.5rem;">
                                                                    <?php
                                                                        $accountClassResult = $con->query("SELECT accounts_class from tb_performance_map where accounts_type ='".$accountTypeRow['accounts_type']."' and workspace_id='".$wid."' group by accounts_class");
                                                                        while($accountClassRow = $accountClassResult->fetch_assoc()){
                                                                            $unauditedTotal = $auditedTotal = $adjustmentTotal = 0;
                                                                            ?>
                                                                                <br>
                                                                                <h4><?php echo $accountClassRow['accounts_class']; ?></h4>
                                                                                <table class="table" style="width:100%; text-align: left">
                                                                                    <thead>
                                                                                        <th>Financial Statement</th>
                                                                                        <th>Unaudited</th>
                                                                                        <th>Adjustments</th>
                                                                                        <th>Audited</th>
                                                                                    </thead>
                                                                                    <tbody>
                                                                            <?php
                                                                                $financialStatementResult = $con->query("SELECT accounts_name, sum(tb_performance_map.amount) unaudited from tb_performance_map where workspace_id = $wid and accounts_type = '".$accountTypeRow['accounts_type']."' and accounts_class = '".$accountClassRow['accounts_class']."' GROUP BY accounts_name");
                                                                                $clientID = time();
                                                                                while($financialStatementRow = $financialStatementResult->fetch_assoc()){
                                                                                    $adjustment = $con->query("SELECT summery_of_misstatements_log.account, sum(summery_of_misstatements_log.amount) adj from summery_of_misstatements_log INNER join summery_of_misstatements on summery_of_misstatements_log.summery_of_misstatements_id=summery_of_misstatements.id where summery_of_misstatements.workspace_id = $wid and summery_of_misstatements_log.account = '".$financialStatementRow['accounts_name']."' GROUP BY summery_of_misstatements_log.account");
                                                                                    $adjustment = $adjustment->num_rows > 0 ? $adjustment->fetch_assoc()['adj'] : 0;
                                                                                    $unauditedTotal += $financialStatementRow['unaudited'];
                                                                                    $auditedTotal += $financialStatementRow['unaudited']+$adjustment;
                                                                                    $adjustmentTotal += $adjustment;
                                                                                    ?>
                                                                                    <tr>
                                                                                        <td style="text-align: left"><?php echo $financialStatementRow['accounts_name'];?></td>
                                                                                        <td style="text-align: left"><?php echo numberToCurrency($financialStatementRow['unaudited']);?></td>
                                                                                        <td style="text-align: left"><a href="subProgram?did=<?php echo base64_encode(md5(time())); ?>&gid=<?php echo base64_encode(md5(time())); ?>&fid=<?php echo base64_encode(md5(time())); ?>&eid=<?php echo base64_encode(md5(time())); ?>&pid=<?php echo base64_encode(24); ?>&cid=<?php echo base64_encode(md5(time())); ?>&bid=<?php echo base64_encode(md5(time())); ?>&aid=<?php echo base64_encode(md5(time())); ?>&parent_id=<?php echo base64_encode(19); ?>&zid=<?php echo base64_encode(md5(time())); ?>&yid=<?php echo base64_encode(md5(time())); ?>&wid=<?php echo base64_encode($wid); ?>&xid=<?php echo base64_encode(md5(time()));?>"><?php echo numberToCurrency($adjustment);?></a></td>
                                                                                        <td style="text-align: left"><?php echo numberToCurrency($financialStatementRow['unaudited']+ $adjustment);?></td>
                                                                                    </tr>
                                                                                    <?php
                                                                                }
                                                                            ?> 
                                                                                        <tr colspan="5"><td></td></tr>
                                                                                        <tr>
                                                                                            <td style="text-align: left"><h5 style="border-bottom: 1px solid;border-top: 1px solid;">Total</h5></td>
                                                                                            <td style="text-align: left"><h5 style="border-bottom: 1px solid;border-top: 1px solid;"><?php echo numberToCurrency($unauditedTotal); ?></h5></td>
                                                                                            <td style="text-align: left"><h5 style="border-bottom: 1px solid;border-top: 1px solid;"><?php echo numberToCurrency($adjustmentTotal); ?></h5></td>
                                                                                            <td style="text-align: left"><h5 style="border-bottom: 1px solid;border-top: 1px solid;"><?php echo numberToCurrency($auditedTotal); ?></h5></td>
                                                                                        </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            <?php
                                                                        }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php
                                                }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            <?php
                        }
                        elseif($prog_id == 526){
                            ?>
                            <style>
                                td:nth-child(4),td:nth-child(5) {
                                    font-weight: bold !important;
                                }
                                td:nth-child(2) {
                                    font-weight: normal !important;
                                }
                                .card{
                                    border: 1px solid #e3e6f0 !important;
                                }
                            </style>
                            <br>
                            <a href="#" data-toggle="modal" data-target="#financialStatementChangeSequenceModal"><button class="btn btn-secondary">Change Sequence</button></a>
                            <!-- <a id="financialStatementPdf" target="_blanc" href="financialStatementPdf"><button class="btn btn-primary">Print to PDF</button></a> -->
                            <a id="financialStatementExcel" target="_blanc" href="auditedFinancialExcel"><button class="btn btn-outline-primary">Export to Excel</button></a>
                            <div class="accordion mt-3" id="unauditedBalanceSheetAccordionExample">
                                <div class="card">
                                    <div class="card-header" id="unauditedBalanceSheetHeadingOne">
                                        <center>
                                            <h2 class="mb-0">
                                                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#unauditedBalanceSheet" aria-expanded="true" aria-controls="unauditedBalanceSheet"><b>Audited Balance Sheet</b></button>
                                            </h2>
                                        </center>
                                    </div> 
                                    <div id="unauditedBalanceSheet" class="collapse show" aria-labelledby="unauditedBalanceSheetHeadingOne" data-parent="#unauditedBalanceSheetAccordionExample" style="margin-left: 2.5rem; padding-bottom: 2rem;">
                                        <?php
                                            $accountTypeResult = $con->query("SELECT DISTINCT accounts_type from tb_performance_map where workspace_id='$wid' and ( accounts_type not like '%Expense%' and accounts_type not like '%Revenue%' ) order by accountTypeSeqNumber");
                                        ?>
                                        <br>
                                        <?php
                                            $i = 0;
                                            while($accountTypeRow = $accountTypeResult->fetch_assoc()){
                                                ++$i;
                                                ?>
                                                    <br>
                                                    <div class="accordion" id="unauditedBalanceSheetAccordionExample<?php echo $i; ?>">
                                                        <div class="card">
                                                            <div class="card-header" id="unauditedBalanceSheetHeadingOne<?php echo $i; ?>">
                                                                <h2 class="mb-0">
                                                                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#account_classBS<?php echo $i; ?>" aria-expanded="true" aria-controls="account_classBS<?php echo $i; ?>"><b><?php echo $accountTypeRow['accounts_type']; ?></b></button>
                                                                </h2>
                                                            </div>  
                                                            <div id="account_classBS<?php echo $i; ?>" class="collapse show" aria-labelledby="unauditedBalanceSheetHeadingOne<?php echo $i; ?>" data-parent="#unauditedBalanceSheetAccordionExample<?php echo $i; ?>" style="margin-left: 2.5rem;">
                                                                <?php
                                                                    $accountClassResult = $con->query("SELECT accounts_class from tb_performance_map where accounts_type ='".$accountTypeRow['accounts_type']."' and workspace_id='".$wid."' group by accounts_class");
                                                                    while($accountClassRow = $accountClassResult->fetch_assoc()){
                                                                        $begBalTotal = $auditedTotal = $adjustmentTotal =  0;
                                                                        ?>
                                                                            <br>
                                                                            <h4><?php echo $accountClassRow['accounts_class']; ?></h4>
                                                                            <table class="table" style="width:100%; text-align: left">
                                                                                <thead>
                                                                                    <th>Financial Statement</th>
                                                                                    <th>CY Final Balance</th>
                                                                                    <th>CY Beginning Balance</th>
                                                                                    <th>Variance(&#8377;)</th>
                                                                                    <th>Variance(%)</th>
                                                                                </thead>
                                                                                <tbody>
                                                                        <?php
                                                                            $financialStatementResult = $con->query("SELECT accounts_name, sum(tb_performance_map.amount) unaudited, sum(tb_performance_map.beg_amount) beg_bal from tb_performance_map where workspace_id = $wid and accounts_type = '".$accountTypeRow['accounts_type']."' and accounts_class = '".$accountClassRow['accounts_class']."' GROUP BY accounts_name");
                                                                            $clientID = time();
                                                                            while($financialStatementRow = $financialStatementResult->fetch_assoc()){
                                                                                $adjustment = $con->query("SELECT summery_of_misstatements_log.account, sum(summery_of_misstatements_log.amount) adj from summery_of_misstatements_log INNER join summery_of_misstatements on summery_of_misstatements_log.summery_of_misstatements_id=summery_of_misstatements.id where summery_of_misstatements.workspace_id = $wid and summery_of_misstatements_log.account = '".$financialStatementRow['accounts_name']."' GROUP BY summery_of_misstatements_log.account");
                                                                                $adjustment = $adjustment->num_rows > 0 ? $adjustment->fetch_assoc()['adj'] : 0;
                                                                                $audited = $financialStatementRow['unaudited']+$adjustment;
                                                                                $auditedTotal += $financialStatementRow['unaudited']+$adjustment;
                                                                                $begBalTotal += $financialStatementRow['beg_bal'];
                                                                                ?>
                                                                                <tr>
                                                                                    <td style="text-align: left"><?php echo $financialStatementRow['accounts_name'];?></td>
                                                                                    <td style="text-align: left"><?php echo numberToCurrency($audited);?></td>
                                                                                    <td style="text-align: left"><?php echo numberToCurrency($financialStatementRow['beg_bal']);?></td>
                                                                                    <td style="text-align: left"><?php echo numberToCurrency($audited - $financialStatementRow['beg_bal']);?></td>
                                                                                    <td>
                                                                                    <?php
                                                                                        $diffPercentage = 0.00;
                                                                                        if($financialStatementRow['beg_bal'] != 0)
                                                                                            $diffPercentage = number_format((float)(($audited-$financialStatementRow['beg_bal'])/$financialStatementRow['beg_bal'])*100, 2, '.', '');
                                                                                        echo $diffPercentage.'%';
                                                                                    ?>
                                                                                    </td>
                                                                                </tr>
                                                                                <?php
                                                                            }
                                                                        ?> 
                                                                                    <tr colspan="5"><td></td></tr>
                                                                                    <tr>
                                                                                        <td style="text-align: left"><h5 style="border-bottom: 1px solid;border-top: 1px solid;">Total</h5></td>
                                                                                        <td style="text-align: left"><h5 style="border-bottom: 1px solid;border-top: 1px solid;"><?php echo numberToCurrency($auditedTotal); ?></h5></td>
                                                                                        <td style="text-align: left"><h5 style="border-bottom: 1px solid;border-top: 1px solid;"><?php echo numberToCurrency($begBalTotal); ?></h5></td>
                                                                                        <td colspan = "2"></td>
                                                                                    </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        <?php
                                                                    }
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php
                                            }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="accordion" id="unauditedProfitLossAccordionExample">
                                <div class="card">
                                    <div class="card-header" id="unauditedProfitLossHeadingOne">
                                        <center>
                                            <h2 class="mb-0">
                                                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#unauditedProfitLoss" aria-expanded="true" aria-controls="unauditedProfitLoss"><b>Audited Profit and Loss</b></button>
                                            </h2>
                                        </center>
                                    </div> 
                                    <div id="unauditedProfitLoss" class="collapse show" aria-labelledby="unauditedProfitLossHeadingOne" data-parent="#unauditedProfitLossAccordionExample" style="margin-left: 2.5rem; padding-bottom: 2rem;">
                                        <?php
                                            $accountTypeResult = $con->query("SELECT DISTINCT accounts_type from tb_performance_map where workspace_id='$wid' and ( accounts_type like '%Expense%' or accounts_type like '%Revenue%' ) order by accountTypeSeqNumber");
                                        ?>
                                        <br>
                                        <?php
                                            $i = 0;
                                            while($accountTypeRow = $accountTypeResult->fetch_assoc()){
                                                ++$i;
                                                ?>
                                                    <br>
                                                    <div class="accordion" id="unauditedProfitLossAccordionExample<?php echo $i; ?>">
                                                        <div class="card">
                                                            <div class="card-header" id="unauditedProfitLossHeadingOne<?php echo $i; ?>">
                                                                <h2 class="mb-0">
                                                                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#account_classPL<?php echo $i; ?>" aria-expanded="true" aria-controls="account_classPL<?php echo $i; ?>"><b><?php echo $accountTypeRow['accounts_type']; ?></b></button>
                                                                </h2>
                                                            </div>  
                                                            <div id="account_classPL<?php echo $i; ?>" class="collapse show" aria-labelledby="unauditedProfitLossHeadingOne<?php echo $i; ?>" data-parent="#unauditedProfitLossAccordionExample<?php echo $i; ?>" style="margin-left: 2.5rem;">
                                                                <?php
                                                                    $accountClassResult = $con->query("SELECT accounts_class from tb_performance_map where accounts_type ='".$accountTypeRow['accounts_type']."' and workspace_id='".$wid."' group by accounts_class");
                                                                    while($accountClassRow = $accountClassResult->fetch_assoc()){
                                                                        $begBalTotal = $auditedTotal = $adjustmentTotal =  0;
                                                                        ?>
                                                                            <br>
                                                                            <h4><?php echo $accountClassRow['accounts_class']; ?></h4>
                                                                            <table class="table" style="width:100%; text-align: left">
                                                                                <thead>
                                                                                    <th>Financial Statement</th>
                                                                                    <th>CY Final Balance</th>
                                                                                    <th>CY Beginning Balance</th>
                                                                                    <th>Variance(&#8377;)</th>
                                                                                    <th>Variance(%)</th>
                                                                                </thead>
                                                                                <tbody>
                                                                        <?php
                                                                            $financialStatementResult = $con->query("SELECT accounts_name, sum(tb_performance_map.amount) unaudited, sum(tb_performance_map.beg_amount) beg_bal from tb_performance_map where workspace_id = $wid and accounts_type = '".$accountTypeRow['accounts_type']."' and accounts_class = '".$accountClassRow['accounts_class']."' GROUP BY accounts_name");
                                                                            $clientID = time();
                                                                            while($financialStatementRow = $financialStatementResult->fetch_assoc()){
                                                                                $adjustment = $con->query("SELECT summery_of_misstatements_log.account, sum(summery_of_misstatements_log.amount) adj from summery_of_misstatements_log INNER join summery_of_misstatements on summery_of_misstatements_log.summery_of_misstatements_id=summery_of_misstatements.id where summery_of_misstatements.workspace_id = $wid and summery_of_misstatements_log.account = '".$financialStatementRow['accounts_name']."' GROUP BY summery_of_misstatements_log.account");
                                                                                $adjustment = $adjustment->num_rows > 0 ? $adjustment->fetch_assoc()['adj'] : 0;
                                                                                $audited = $financialStatementRow['unaudited']+$adjustment;
                                                                                $auditedTotal += $financialStatementRow['unaudited']+$adjustment;
                                                                                $begBalTotal += $financialStatementRow['beg_bal'];
                                                                                ?>
                                                                                <tr>
                                                                                    <td style="text-align: left"><?php echo $financialStatementRow['accounts_name'];?></td>
                                                                                    <td style="text-align: left"><?php echo numberToCurrency($audited);?></td>
                                                                                    <td style="text-align: left"><?php echo numberToCurrency($financialStatementRow['beg_bal']);?></td>
                                                                                    <td style="text-align: left"><?php echo numberToCurrency($audited - $financialStatementRow['beg_bal']);?></td>
                                                                                    <td>
                                                                                    <?php
                                                                                        $diffPercentage = 0.00;
                                                                                        if($financialStatementRow['beg_bal'] != 0)
                                                                                            $diffPercentage = number_format((float)(($audited-$financialStatementRow['beg_bal'])/$financialStatementRow['beg_bal'])*100, 2, '.', '');
                                                                                        echo $diffPercentage.'%';
                                                                                    ?>
                                                                                    </td>
                                                                                </tr>
                                                                                <?php
                                                                            }
                                                                        ?> 
                                                                                    <tr colspan="5"><td></td></tr>
                                                                                    <tr>
                                                                                        <td style="text-align: left"><h5 style="border-bottom: 1px solid;border-top: 1px solid;">Total</h5></td>
                                                                                        <td style="text-align: left"><h5 style="border-bottom: 1px solid;border-top: 1px solid;"><?php echo numberToCurrency($auditedTotal); ?></h5></td>
                                                                                        <td style="text-align: left"><h5 style="border-bottom: 1px solid;border-top: 1px solid;"><?php echo numberToCurrency($begBalTotal); ?></h5></td>
                                                                                        <td colspan = "2"></td>
                                                                                    </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        <?php
                                                                    }
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php
                                            }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        <?php 
                        }
                        else{
                            $query = "select program.*, workspace_log.status status, workspace_log.active active from program inner join workspace_log on program.id = workspace_log.program_id where program.parent_id = '$prog_id' and workspace_log.workspace_id = '$wid' and workspace_log.import = 1 order by _seq";
                            $exquery = $con->query($query);
                            if ($exquery->num_rows != 0) {
                                while ($queryrow = $exquery->fetch_assoc()) {
                                    if ($queryrow['hasChild'] == 1) { 
                                        ?>
                                            <div class="custom-list">
                                                <?php
                                                    if($prog_id == 262){
                                                        echo "<label class=' mt-2'><span class='helpDesign help_10'>10</span></label>";
                                                    }
                                                ?>
                                                <a href="subProgram?<?php echo base64_encode(md5(time())); ?>&gid=<?php echo base64_encode(md5(time())); ?>&fid=<?php echo base64_encode(md5(time())); ?>&eid=<?php echo base64_encode(md5(time())); ?>&pid=<?php echo base64_encode($queryrow['id']); ?>&cid=<?php echo base64_encode(md5(time())); ?>&bid=<?php echo base64_encode(md5(time())); ?>&aid=<?php echo base64_encode(md5(time())); ?>&parent_id=<?php echo base64_encode($queryrow['parent_id']); ?>&zid=<?php echo base64_encode(md5(time())); ?>&yid=<?php echo base64_encode(md5(time())); ?>&wid=<?php echo base64_encode($wid); ?>&xid=<?php echo base64_encode(md5(time())); ?>"
                                                    class="custom-list-items custom-list-items-action"><b><?php echo trim($queryrow['program_name']); ?></b></a>
                                                    <?php
                                                        if($prog_id == 1){
                                                            echo "<label class=' mt-2'><span class='helpDesign help_2'>2</span></label>";
                                                        }
                                                    ?>
                                            </div>
                                        <?php
                                    } else { ?>
                                        <div class="custom-list">
                                            <div class="custom-list-items custom-list-items-action">
                                                <?php
                                                    if ($queryrow['active']) { ?>
                                                        <a href="#">
                                                            <?php
                                                                if($queryrow['id'] == 395 || $queryrow['id'] == 525 || $queryrow['id'] == 526){
                                                                    $trialBalanceResult = $con->query("select count(id) total from trial_balance where workspace_id = '".$wid."'");
                                                                    if($trialBalanceResult->fetch_assoc()['total'] == 0){
                                                                        $con->query("UPDATE workspace_log SET import = 0 WHERE workspace_id = '".$wid."' and program_id = 395");
                                                                    }
                                                                }
                                                                if($prog_id == 254 || $prog_id == 255 || $prog_id == 256 || $prog_id == 257 || $prog_id == 262 || $prog_id == 266 || $prog_id == 19){
                                                                    echo "<label class=' mt-2'><span class='helpDesign help_1'>1</span></label>";
                                                                 } 
                                                                if($queryrow['id'] == 247 || $queryrow['id'] == 245 || $queryrow['id'] == 395 || $queryrow['id'] == 496 || $queryrow['id'] == 258 || $queryrow['id'] == 8 || $queryrow['id'] == 259 || $queryrow['id'] == 24 || $queryrow['id'] == 525 || $queryrow['id'] == 526){ 
                                                                    ?>
                                                                    <a id="<?php echo $queryrow['id']; ?>" href="subProgram?<?php echo base64_encode(md5(time())); ?>&gid=<?php echo base64_encode(md5(time())); ?>&fid=<?php echo base64_encode(md5(time())); ?>&eid=<?php echo base64_encode(md5(time())); ?>&pid=<?php echo base64_encode($queryrow['id']); ?>&cid=<?php echo base64_encode(md5(time())); ?>&bid=<?php echo base64_encode(md5(time())); ?>&aid=<?php echo base64_encode(md5(time())); ?>&parent_id=<?php echo base64_encode($queryrow['parent_id']); ?>&zid=<?php echo base64_encode(md5(time())); ?>&yid=<?php echo base64_encode(md5(time())); ?>&wid=<?php echo base64_encode($wid); ?>&xid=<?php echo base64_encode(md5(time())); ?>">    
                                                                        <?php  echo trim($queryrow['program_name']); ?> &nbsp;
                                                                    </a>
                                                                <?php } 
                                                                else { ?>    
                                                                    <span class="signoffmodal" id="<?php echo $queryrow['id']; ?>"><?php  echo trim($queryrow['program_name']); ?> &nbsp;</span>
                                                                <?php }
                                                            ?>
                                                        </a> <?php
                                                    
                                                        // $prearedResult = $con->query("select id,user_id,prepare_signoff_date where workspace_id = '$wid' and prog_id = '$prog_id'")->fetch_all();
                                                        // foreach($prearedResult as $key => $value)
                                                        if ($queryrow['status']) { ?>
                                                            <i class="fas fa-check-circle"
                                                                style="color:green !important;">
                                                            </i>
                                                            
                                                            <?php
                                                            
                                                            $prepareSignoff = $con->query("select count(signoff_prepare_log.id) total from signoff_prepare_log inner join user on signoff_prepare_log.user_id=user.id where workspace_id=".$wid." and prog_id=".$queryrow['id'])->fetch_assoc();
                                                            if($prepareSignoff['total']){
                                                            ?>
                                                            <button class="btn btn-outline-primary fetchPrepare" id="<?php echo $queryrow['id']; ?>">Preparer Log</button>
                                                            <?php
                                                            }
                                                            $reviewSignoff = $con->query("select count(signoff_review_log.id) total from signoff_review_log inner join user on signoff_review_log.user_id=user.id where workspace_id=".$wid." and prog_id=".$queryrow['id'])->fetch_assoc();
                                                            if($reviewSignoff['total']){
                                                            ?>
                                                            <button class="btn btn-outline-success fetchReview" id="<?php echo $queryrow['id']; ?>">Reviewer Log</button>
                                                            <?php
                                                            }
                                                        } else { ?>
                                                            <i class="fas fa-times-circle"
                                                                style="color:red !important;">
                                                            </i> <?php
                                                        }

                                                        if($prog_id == 1){
                                                            echo "<label class=' mt-2'><span class='helpDesign help_3'>3</span></label>";
                                                        }
                                                        elseif($prog_id == 254 || $prog_id == 255 || $prog_id == 256 || $prog_id == 257 || $prog_id == 262 || $prog_id == 266 || $prog_id == 19){
                                                            echo "<label class=' mt-2'><span class='helpDesign help_2'>2</span></label>";
                                                        }
                                                        if(performanceChildCheck($prog_parentId) == 1){
                                                        // if($prog_parentId == 2){
                                                            ?>
                                                            &ensp;&ensp;
                                                            <?php
                                                            $id = $queryrow['id'];
                                                            $assertionResult = $con->query("select * from assertion where workspace_id = '$wid' and program_id = $id");
                                                            while($assertionRow = $assertionResult->fetch_assoc()){
                                                                if($assertionRow['assertion_value'] == 'E/O'){
                                                                    ?>
                                                                        <label class="badge badge-primary form-check-label" for="inlineCheckbox1">E/O</label>  
                                                                    <?php
                                                                }
                                                                if($assertionRow['assertion_value'] == 'M/V'){
                                                                    ?>
                                                                        <label class="badge badge-danger form-check-label" for="inlineCheckbox1">M/V</label>  
                                                                    <?php
                                                                }
                                                                if($assertionRow['assertion_value'] == 'R&O'){
                                                                    ?>
                                                                        <label class="badge badge-warning form-check-label" for="inlineCheckbox1">R&O</label>  
                                                                    <?php
                                                                }
                                                                if($assertionRow['assertion_value'] == 'P&D'){
                                                                    ?>
                                                                        <label class="badge badge-info form-check-label" for="inlineCheckbox1">P&D</label>  
                                                                    <?php
                                                                }
                                                            }
                                                        } 
                                                        ?>
                                                        <a href="#" id="<?php echo $queryrow['id']; ?>"
                                                            class="buttonActive">
                                                            <!-- <i class="fa fa-thumbs-up float-right"
                                                                aria-hidden="true"
                                                                style="color:blue !important;">
                                                            </i> -->
                                                            <img class="float-right" src="Icons/thumbs-up.svg" />
                                                        </a> <?php
                                                        if($prog_id == 254 || $prog_id == 255 || $prog_id == 256 || $prog_id == 257 || $prog_id == 262 || $prog_id == 266 || $prog_id == 19){
                                                            echo "<label class='float-right'><span class='helpDesign help_3'>3</span></label>";
                                                        }
                                                        elseif($prog_id == 1){
                                                            echo "<label class='float-right'><span class='helpDesign help_9'>9</span></label>";
                                                        }
                                                    } else { ?>
                                                        <span class="text-muted pl-2"><?php echo trim($queryrow['program_name']); ?> &nbsp;</span>
                                                        <a href="#" id="<?php echo $queryrow['id']; ?>"
                                                            class="buttonActive">
                                                            <img class="float-right" src="Icons/Icon feather-plus.svg" />
                                                            <!-- <i class="fa fa-ban float-right" aria-hidden="true" style="color:orange !important;"></i> -->
                                                        </a> 
                                                        <?php
                                                    }
                                                ?>
                                            </div>
                                        </div> <?php
                                    }
                                }
                            }
                        }
                        ?>
                    </div>
                </div>
                
                <?php
                if(isset($_SESSION['role']) && !empty($_SESSION['role']) && $_SESSION['role'] != 5)
                {
                    if($prog_id == 1 || $prog_id == 2 || $prog_id == 12 || $prog_id == 19 || $prog_id == 230 || $prog_id == 239 || $prog_id == 240 || $prog_id == 245 || $prog_id == 247 || $prog_id == 254 || $prog_id == 255 || $prog_id == 256 || $prog_id == 257 || $prog_id == 262 || $prog_id == 266){
                    ?>
                        <div id = "helpDescriptionTop" class="d-flex justify-content-center">
                            <div id="helpDescription" class="col-md-11">
                                <div class="card" style="border: 4px solid rgb(134, 189, 255, 0.65) !important;box-shadow: 0px 0px 20px 1px rgba(0,0,0,0.5);">
                                    <div class="card-body">
                                        <button type="button" class="close" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    <?php
                                        if($prog_id == 1){
                                            ?>
                                            <div id="help_1">
                                                <p>1. Quicklinks – You can access any audit pillar any time using the quick link options. It helps you easily navigate between different section of the program.</p>
                                            </div>
                                            <div id="help_2">
                                                <p>2. Work Programs – All the work programs are workstep folders that can be used to include multiple individual steps.</p>
                                                <p>All Workporgrams. You can use “Add program” icon on the top right corner to add more programs to  Pillar like Planning,Risk assessment, Performance or reporting and conclusion you can add program based on your requirements.</p>
                                                <p><b>An audit program once created cannot be deleted.</b></p>
                                            </div>
                                            <div id="help_3">
                                                <p>3. Work steps :A Step is an individual step that should be used to add task under a program or to create a task as a standalone individual step.</p>
                                                <p>All Workporgrams. You can use “Add program” icon on the top right corner to add more worksteps to  Pillar like Planning, Risk assessment, Performance or reporting and conclusion you can add program based on your requirements.</p>
                                            </div>
                                            <div id="help_4">
                                                <p>4. Add program:<p> 
                                                <p><b>Add a Programs:</b></p>
                                                <p>Click on the “Add program” icon on the top right corner. Now you can use Add program as main task folder and add sub steps within a program. Now you can add Name of the program that you wish to add and select “Add as program” from the program type dropdown. Then click Done.</p>
                                                <p>Note audit program will be added in the respective section you currently working on. Example. If you are in planning section of audit and you select “Add a program”, a new program will be added on Planning section only. <b>An audit program once created cannot be deleted.</b></p>
                                                <p><b>Add a Step:</b></p>
                                                <p>Click on the icon “Add program” on the top right corner and add Name of the step for your individual work step and select “Add as Step” from the program type drop-down. This will add a step in the respective section No changes will be saved unless you click Done.</p>
                                                <p>Note Step will be added in the respective section you currently working on. Example. If you are in planning section of audit and you select “Add as Step”, a new step will be added on Planning section only.</p>
                                                <p>You can make a disabled work step to unable it by clicking on “X” and it will change to "Like/Thumbs up"which reflect all active work steps.</p>
                                            </div>
                                            <div id="help_5">
                                                <p>5. Profile: User profile reflects brief details about the user and can be edits by firm administrator.</p> 
                                            </div>
                                            <div id="help_6">
                                                <p>6. Settings – Your Settings are personalized based on your role in your firm and can be always accessed for chat, email and reaching out to a specialist for any help.</p>
                                            </div>
                                            <div id="help_7">
                                                <p>7. Log out- Simply use this button to log out of your firm workspace and client list.</p>
                                            </div>
                                            <div id="help_8">
                                                <p>8. You can use the threads to go back to the previous screen or any screen within the thread, this helps you save a lot of time and help you navigate through different screens.</p>
                                            </div>
                                            <div id="help_9">
                                                <p>9. Thumbs up: You can choose not to work on an suggestive work step by clicking on “Thumbs up” icon located on right hand side on each work step.</p>
                                                <p>Once you click  on “Thumbs up” The icon will change to “X” which means the work step has been disables or marked not applicable. All not applicable work steps will be reflected in the diagnostic report.</p>
                                                <p>You can make a disabled work step to unable it by clicking on “X” and it will change to “Thumbs up” which reflect all active work steps.</p>
                                            </div>
                                            <?php
                                        }
                                        elseif($prog_id == 254 || $prog_id == 255 || $prog_id == 256 || $prog_id == 257 || $prog_id == 266 || $prog_id == 19){
                                            ?>
                                            <div id="help_1">
                                                <p> 1. Steps :A Step is an individual step that should be used to add task under a program or to create a task as a standalone individual step. All Workporgrams. You can use “Add program” icon on the top right corner to add more worksteps to  Pillar like Planning, Risk assessment, Performance or reporting and conclusion you can add program based on your requirements.</p>
                                            </div>
                                            <div id="help_2">
                                                <p>2. Red icons indicate that there is no saved data within this work steps and no sign offs have been done yet. Once a user signs off the step the icons disappear.</p>
                                            </div>
                                            <div id="help_3">
                                                <p>3. Thumbs up: You can choose not to work on an suggestive work step by clicking on “Thumbs up” icon located on right hand side on each work step.</p>
                                                <p>Once you click  on “Thumbs up” The icon will change to “X” which means the work step has been disables or marked not applicable. All not applicable work steps will be reflected in the diagnostic report.</p>
                                                <p>You can make a disabled work step to unable it by clicking on “X” and it will change to “Thumbs up” which reflect all active work steps.</p>
                                            </div>
                                            <div id="help_4">
                                                <p>4. Add program:<p> 
                                                <p><b>Add a Programs:</b></p>
                                                <p>Click on the “Add program” icon on the top right corner. Now you can use Add program as main task folder and add sub steps within a program. Now you can add Name of the program that you wish to add and select “Add as program” from the program type dropdown. Then click Done.</p>
                                                <p>Note audit program will be added in the respective section you currently working on. Example. If you are in planning section of audit and you select “Add a program”, a new program will be added on Planning section only. <b>An audit program once created cannot be deleted.</b></p>
                                                <p><b>Add a Step:</b></p>
                                                <p>Click on the icon “Add program” on the top right corner and add Name of the step for your individual work step and select “Add as Step” from the program type drop-down. This will add a step in the respective section No changes will be saved unless you click Done.</p>
                                                <p>Note Step will be added in the respective section you currently working on. Example. If you are in planning section of audit and you select “Add as Step”, a new step will be added on Planning section only.</p>
                                                <p>You can make a disabled work step to unable it by clicking on “X” and it will change to "Like/Thumbs up"which reflect all active work steps.</p>
                                            </div>
                                            <div id="help_5">
                                                <p>5. Profile: User profile reflects brief details about the user and can be edits by firm administrator.</p> 
                                            </div>
                                            <div id="help_6">
                                                <p>6. Quicklinks – You can access any audit pillar any time using the quick link options. It helps you easily navigate between different section of the program.</p>
                                            </div>
                                            <div id="help_7">
                                                <p>7. You can use the threads to go back to the previous screen or any screen within the thread, this helps you save a lot of time and help you navigate through different screens.</p>
                                            </div>
                                            <div id="help_8">
                                                <p>8. Settings – Your Settings are personalized based on your role in your firm and can be always accessed for chat, email and reaching out to a specialist for any help.</p>
                                            </div>
                                            <div id="help_9">
                                                <p>9. Log out- Simply use this button to log out of your firm workspace and client list.</p>
                                            </div>
                                            <?php
                                        }
                                        elseif($prog_id == 245){
                                            ?>
                                            <div id="help_1">
                                                <p>1. If you wish to upload a new trial balance and overwrite the information on existing trial balance you can use upload excel feature. You would have to follow the same instruction as you would have while upload the first trial balance.</p>
                                            </div>
                                            <div id="help_2">
                                                <p>2. You can download the instruction template later on for future upload using download template feature.</p>
                                            </div>
                                            <div id="help_3">
                                                <p>3. Add program:<p> 
                                                <p><b>Add a Programs:</b></p>
                                                <p>Click on the “Add program” icon on the top right corner. Now you can use Add program as main task folder and add sub steps within a program. Now you can add Name of the program that you wish to add and select “Add as program” from the program type dropdown. Then click Done.</p>
                                                <p>Note audit program will be added in the respective section you currently working on. Example. If you are in planning section of audit and you select “Add a program”, a new program will be added on Planning section only. <b>An audit program once created cannot be deleted.</b></p>
                                                <p><b>Add a Step:</b></p>
                                                <p>Click on the icon “Add program” on the top right corner and add Name of the step for your individual work step and select “Add as Step” from the program type drop-down. This will add a step in the respective section No changes will be saved unless you click Done.</p>
                                                <p>Note Step will be added in the respective section you currently working on. Example. If you are in planning section of audit and you select “Add as Step”, a new step will be added on Planning section only.</p>
                                                <p>You can make a disabled work step to unable it by clicking on “X” and it will change to "Like/Thumbs up"which reflect all active work steps.</p>
                                            </div>
                                            <div id="help_4">
                                                <p>4. Profile: User profile reflects brief details about the user and can be edits by firm administrator.</p> 
                                            </div>
                                            <div id="help_5">
                                                <p>5. Quicklinks – You can access any audit pillar any time using the quick link options. It helps you easily navigate between different section of the program.</p>
                                            </div>
                                            <div id="help_6">
                                                <p>6. You can use the threads to go back to the previous screen or any screen within the thread, this helps you save a lot of time and help you navigate through different screens.</p>
                                            </div>
                                            <div id="help_7">
                                                <p>7. Settings – Your Settings are personalized based on your role in your firm and can be always accessed for chat, email and reaching out to a specialist for any help.</p>
                                            </div>
                                            <div id="help_8">
                                                <p>8. Log out- Simply use this button to log out of your firm workspace and client list.</p>
                                            </div>
                                            <?php
                                        }
                                        elseif($prog_id == 247){
                                            ?>
                                                <div id="help_1">
                                                <p> 1. Click on “ADD Request” icon Select the Account from the drop down for which you would like to add a request.</p>
                                                <p>If you do not find the appropriate account listed in the drop down use “Others” and click Done.</p>
                                                <p>This will add a new request line in the schedule. You can now add Request description for the client and assign it to a particular client contact and add Requested date by which you would expect the client to respond.</p>
                                            </div>
                                            <div id="help_2">
                                                <p>2. Use EXPORT button on the rightft hand side corner of the request list to download the client assistance schedule in excel.</p>
                                            </div>
                                            <div id="help_3">
                                                <p>3. Save:Once you have added your client requests click save at the bottom of the page.</p>
                                                <p>Clicking save will only save the client list for your reference and no notification will be sent to the client.</p>
                                                <p>If you wish to notify the client, you can use Send request button and this will send an automated email to the client contacts notifying them with auditor request.</p>
                                            </div>
                                            <div id="help_4">
                                                <p>4. Send Request: Once you click on send request a notification email will be sent to all client contacts containing information about yor new requested items.</p> 
                                            </div>
                                            <div id="help_5">
                                                <p>5. Profile: User profile reflects brief details about the user and can be edits by firm administrator.</p> 
                                            </div>
                                            <div id="help_6">
                                                <p>6. Quicklinks – You can access any audit pillar any time using the quick link options. It helps you easily navigate between different section of the program.</p>
                                            </div>
                                            <div id="help_7">
                                                <p>7. You can use the threads to go back to the previous screen or any screen within the thread, this helps you save a lot of time and help you navigate through different screens.</p>
                                            </div>
                                            <div id="help_8">
                                                <p>8. Settings – Your Settings are personalized based on your role in your firm and can be always accessed for chat, email and reaching out to a specialist for any help.</p>
                                            </div>
                                            <div id="help_9">
                                                <p>9. Log out- Simply use this button to log out of your firm workspace and client list.</p>
                                            </div>
                                            <?php
                                        }
                                        elseif($prog_id == 12){
                                            ?>
                                            <div id="help_1">
                                                <p> 1. Steps :A Step is an individual step that should be used to add task under a program or to create a task as a standalone individual step. All Workporgrams. You can use “Add program” icon on the top right corner to add more worksteps to  Pillar like Planning, Risk assessment, Performance or reporting and conclusion you can add program based on your requirements.</p>
                                            </div>
                                            <div id="help_2">
                                                <p>2. Profile: User profile reflects brief details about the user and can be edits by firm administrator.</p>
                                            </div>
                                            <div id="help_3">
                                                <p>3. Quicklinks – You can access any audit pillar any time using the quick link options. It helps you easily navigate between different section of the program.</p>
                                            </div>
                                            <div id="help_4">
                                                <p>4.  You can use the threads to go back to the previous screen or any screen within the thread, this helps you save a lot of time and help you navigate through different screens.<p> 
                                            </div>
                                            <div id="help_5">
                                                <p>5. Settings – Your Settings are personalized based on your role in your firm and can be always accessed for chat, email and reaching out to a specialist for any help.</p> 
                                            </div>
                                            <div id="help_6">
                                                <p>6. Log out- Simply use this button to log out of your firm workspace and client list.</p>
                                            </div>
                                            <?php
                                        }
                                        elseif($prog_id == 230){
                                            ?>
                                            <div id="help_1">
                                                <p> 1. Every workspace comes with a list of materiality basis that you can chose from.</p>
                                                <p>You can add your own material basis by using “ADD NEW” icon on center top section of the page.</p>
                                                <p>You can update materiality based on your firm guidance and add rational for using particular bases.</p>
                                                <p>You can also allocate materiality to Balance sheet assets  and liabilities and Profit and loss Income and expenses.</p>
                                                <p>You can also added your own calculation to the workstep. Click on Save to ensure all changes are saved within the workspace.</p>
                                            </div>
                                            <div id="help_2">
                                                <p>2. You can remove any preexisting materially basis or basis created during the engagement not used can be simply removed during this feature.</p>
                                            </div>
                                            <div id="help_3">
                                                <p>3. You can add your rational for choosing materially basis based on your firm guidance in this section.</p>
                                            </div>
                                            <div id="help_4">
                                                <p>4. If you have different materiality or testing threshold basis for assets, liabilities, income and expenses , those can be entered in these sections.<p> 
                                            </div>
                                            <div id="help_5">
                                                <p>5. You can upload your own calculation or any relevant files to this work step</p> 
                                            </div>
                                            <div id="help_6">
                                                <p>6. Profile: User profile reflects brief details about the user and can be edits by firm administrator.</p>
                                            </div>
                                            <div id="help_7">
                                                <p>7. Quicklinks – You can access any audit pillar any time using the quick link options. It helps you easily navigate between different section of the program.</p>
                                            </div>
                                            <div id="help_8">
                                                <p>8. You can use the threads to go back to the previous screen or any screen within the thread, this helps you save a lot of time and help you navigate through different screens.</p>
                                            </div>
                                            <div id="help_9">
                                                <p>9. Settings – Your Settings are personalized based on your role in your firm and can be always accessed for chat, email and reaching out to a specialist for any help.</p>
                                            </div>
                                            <div id="help_10">
                                                <p>10. Log out- Simply use this button to log out of your firm workspace and client list.</p>
                                            </div>

                                            <?php
                                        }
                                        elseif($prog_id == 239 || $prog_id == 240){
                                            ?>
                                            <div id="help_1">
                                                <p> 1. Check your firm’s guidance on how to identify significance account and update significant account to your workspace.Once you are in the identify significant accounts screen balance sheet or profit and loss you can now updated amounts based on trial balance and select account type “Significant” and “non-Significant” from the drop down.</p>
                                            </div>
                                            <div id="help_2">
                                                <p>2. Check your firm’s guidance on how to identify significance account and update significant account to your workspace. Once you are in the identify significant accounts screen balance sheet or profit and loss you can now updated amounts based on trial balance and set risk level of the account to high, medium and low from the risk dropdown section.</p>
                                            </div>
                                            <div id="help_3">
                                                <p>3. Check your firm’s guidance on how to identify significance account and update significant account to your workspace.Once you are in the identify significant accounts screen balance sheet or profit and loss you can now updated amounts based on trial balance and Based on account type and risk level you can decide to import audit work steps in performance step accordingly. </p>
                                                <p>Your workspace package comes with a prelisted suggestive program for few significant accounts. You can import these audit programs while you are under Identify significant accounts screen. Impot feature is listed within both Balance sheet and Profit and loss sections. Refer to the Import column on the right side of the table and you can choose “Yes” or “No” from the drop-down menu. After you have made your selection for “YES” or “No” each account you can click Save and your Programs will be added within the performance section of the audit workspace. </p>
                                                <p>Prelisted suggestive programs are a suggestive methodology, and you can opt to not import them also. If you selected “No” for any account in the impot column those accounts will not appear in the performance section of the audit workspace. </p>
                                                <p>Prelisted suggestive audit programs are only available for selective account, and you may have to add your own steps for each account listed or not listed in the accounts table.</p>
                                                <p><b>How to remove Prelisted suggestive audit program for Significant accounts.</b></p>
                                                <p>Under Identify significant accounts screen use Impot feature listed within both Balance sheet and Profit and loss sections. Refer to the Import column on the right side of the table and you can choose “NO” from the drop-down menu for the accounts you do not want to import prelisted audit work steps. After you have made your selection for “No” you can click Save and the prelisted suggestive audit programs will be removed from Performance section of the workspace. </p>
                                                <p>Currently prelisted suggestive audit programs are available for below accounts. </p>
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th>Balance Sheet</th>
                                                            <th>Profit & Loss</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>Cash and Bank</td>
                                                            <td>Income/Sales</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Accounts Receivable</td>
                                                            <td>Cost of goods Sold</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Prepaid Expenses</td>
                                                            <td>Selling and General Expenses</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Inventory</td>
                                                            <td>Interest Expenses</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Fixed Assets</td>
                                                            <td>Legal Expenses</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Accounts Payable</td>
                                                            <td>Payroll Expenses</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Payroll Expenses</td>
                                                            <td>Depreciation and Amortization</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Debt and Borrowing </td>
                                                            <td>Taxes</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div id="help_4">
                                                <p>4. The scopes for balance sheet account will be automatically updated based on your materiality screen information.<p> 
                                            </div>
                                            <div id="help_5">
                                                <p>5. You can export significant account list and information from this worksetp into excel using the export button.</p> 
                                            </div>
                                            <div id="help_6">
                                                <p>6. You can add Balance sheet accounts to your workspace from Balance sheet tab found within Identify significant accounts screen. Once you are within the Balance sheet section you an find “Add Account” option on the top right corner of the page. After clicking on the “Add Account” button you can put in account name that you wish to add and type of account Asset or liability and click save. Now your new Balance sheet account has been added.</p>
                                                <p>Please note assets less liabilities should equal zero or assets should be equal to liabilities to save the workdone in the screen.</p>
                                            </div>
                                            <div id="help_7">
                                                <p>7. Profile: User profile reflects brief details about the user and can be edits by firm administrator.</p>
                                            </div>
                                            <div id="help_8">
                                                <p>8. Quicklinks – You can access any audit pillar any time using the quick link options. It helps you easily navigate between different section of the program.</p>
                                            </div>
                                            <div id="help_9">
                                                <p>9. You can use the threads to go back to the previous screen or any screen within the thread, this helps you save a lot of time and help you navigate through different screens.</p>
                                            </div>
                                            <div id="help_10">
                                                <p>10. Settings – Your Settings are personalized based on your role in your firm and can be always accessed for chat, email and reaching out to a specialist for any help.</p>
                                            </div>
                                            <div id="help_11">
                                                <p>11. Log out- Simply use this button to log out of your firm workspace and client list.</p>
                                            </div>

                                            <?php
                                        }
                                        elseif($prog_id == 262){
                                            ?>
                                            <div id="help_1">
                                                <p> 1. Steps :A Step is an individual step that should be used to add task under a program or to create a task as a standalone individual step. All Workporgrams. You can use “Add program” icon on the top right corner to add more worksteps to  Pillar like Planning, Risk assessment, Performance or reporting and conclusion you can add program based on your requirements.</p>
                                            </div>
                                            <div id="help_2">
                                                <p>2. Red icons indicate that there is no saved data within this work steps and no sign offs have been done yet. Once a user signs off the step the icons disappear.</p>
                                            </div>
                                            <div id="help_3">
                                                <p>3. Thumbs up: You can choose not to work on an suggestive work step by clicking on “Thumbs up” icon located on right hand side on each work step.</p>
                                                <p>Once you click  on “Thumbs up” The icon will change to “X” which means the work step has been disables or marked not applicable. All not applicable work steps will be reflected in the diagnostic report.</p>
                                                <p>You can make a disabled work step to unable it by clicking on “X” and it will change to “Thumbs up” which reflect all active work steps.</p>
                                            </div>
                                            <div id="help_4">
                                            <p>4. Add program:<p> 
                                                <p><b>Add a Programs:</b></p>
                                                <p>Click on the “Add program” icon on the top right corner. Now you can use Add program as main task folder and add sub steps within a program. Now you can add Name of the program that you wish to add and select “Add as program” from the program type dropdown. Then click Done.</p>
                                                <p>Note audit program will be added in the respective section you currently working on. Example. If you are in planning section of audit and you select “Add a program”, a new program will be added on Planning section only. <b>An audit program once created cannot be deleted.</b></p>
                                                <p><b>Add a Step:</b></p>
                                                <p>Click on the icon “Add program” on the top right corner and add Name of the step for your individual work step and select “Add as Step” from the program type drop-down. This will add a step in the respective section No changes will be saved unless you click Done.</p>
                                                <p>Note Step will be added in the respective section you currently working on. Example. If you are in planning section of audit and you select “Add as Step”, a new step will be added on Planning section only.</p>
                                                <p>You can make a disabled work step to unable it by clicking on “X” and it will change to "Like/Thumbs up"which reflect all active work steps.</p>
                                            </div>
                                            <div id="help_5">
                                                <p>5. Profile: User profile reflects brief details about the user and can be edits by firm administrator.</p> 
                                            </div>
                                            <div id="help_6">
                                                <p>6. Quicklinks – You can access any audit pillar any time using the quick link options. It helps you easily navigate between different section of the program.</p>
                                            </div>
                                            <div id="help_7">
                                                <p>7. You can use the threads to go back to the previous screen or any screen within the thread, this helps you save a lot of time and help you navigate through different screens.</p>
                                            </div>
                                            <div id="help_8">
                                                <p>8. Settings – Your Settings are personalized based on your role in your firm and can be always accessed for chat, email and reaching out to a specialist for any help.</p>
                                            </div>
                                            <div id="help_9">
                                                <p>9. Log out- Simply use this button to log out of your firm workspace and client list.</p>
                                            </div>
                                            <div id="help_10">
                                                <p>10. Work Programs – All the work programs are workstep folders that can be used to include multiple individual steps. All Workporgrams. You can use “Add program” icon on the top right corner to add more programs to  Pillar like Planning,Risk assessment, Performance or reporting and conclusion you can add program based on your requirements.</p>
                                                <p><b>An audit program once created cannot be deleted.</b></p>
                                            </div>
                                            <?php
                                        }
                                        elseif($prog_id == 2){
                                            ?>
                                            <div id="help_1">
                                                <p> 1. List of accounts are automictically updated based on the information provided in Identify significant account screen. If you wish add any account to the list please add the account in “Identify significant account screen” under “Materiality and identify significant risk and accounts ” and select “YES” in the import. Any account marked as “No” in the import section will not appear in the account list.</p>
                                            </div>
                                            <div id="help_2">
                                                <p>2. Profile: User profile reflects brief details about the user and can be edits by firm administrator.</p>
                                            </div>
                                            <div id="help_3">
                                                <p>3. Quicklinks – You can access any audit pillar any time using the quick link options. It helps you easily navigate between different section of the program.</p>
                                            </div>
                                            <div id="help_4">
                                                <p>4. You can use the threads to go back to the previous screen or any screen within the thread, this helps you save a lot of time and help you navigate through different screens.<p> 
                                            </div>
                                            <div id="help_5">
                                                <p>5. Settings – Your Settings are personalized based on your role in your firm and can be always accessed for chat, email and reaching out to a specialist for any help.</p> 
                                            </div>
                                            <div id="help_6">
                                                <p>6. Log out- Simply use this button to log out of your firm workspace and client list.</p>
                                            </div>
                                            <?php
                                        }
                                        
                                    ?>
                                        <i id="left-arrow" class="fas fa-arrow-left"></i>
                                        <i id="right-arrow" class="fas fa-arrow-right"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php
                    }
                }
                ?>

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
        <?php 
            if(isset($_SESSION['role']) && !empty($_SESSION['role']) && $_SESSION['role'] != 5)
            {
        ?>
        <!--Add Programme Modal -->
        <div class="modal fade" id="addProgModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-size" role="document">
                <div class="modal-content">
                    <form>
                        <div class="modal-body">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel"> Add Programme Step </h5>
                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div><br>
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
                        <div class="modal-footer d-flex align-items-center justify-content-center">
                            <!-- <button class="btn btn-danger" type="button" data-dismiss="modal">Cancel</button> -->
                            <input class="btn btn-primary" type="submit" id="addProgSubmit" value="Done">
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!--Add BS and PL Accounts Modal -->
        <div class="modal fade" id="addbsplModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-size" role="document">
                <div class="modal-content">
                    <form>
                        <div class="modal-body">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Add Account</h5>
                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div><br>
                            <div class="form-group">
                                <label for="name">Account Name</label>
                                <input type="text" class="form-control" name="bspl_name" id="bspl_name" required>
                            </div>
                            <!-- <div class="form-group ">
                                <label for="name">Account</label>
                                <select name="addbspl" id="bspl_type" class="form-control" required>
                                    <option value="">Select Account</option>
                                    <option value="33">Balance Sheet</option>
                                    <option value="34">Profit & Loss</option>
                                </select>
                            </div> -->
                            <?php
                            if($prog_id == 239){
                            ?>
                            <div class="form-group ">
                                <label for="name">Header Account</label>
                                <select name="addbspl" id="bspl_header_type" class="form-control" required>
                                    <option value="">Select Header Account</option>
                                    <option value="0">Asset Accounts</option>
                                    <option value="1">Liability Accounts</option>
                                </select>
                            </div>
                            <?php
                            }
                            ?>
                        </div>
                        <div class="modal-footer  d-flex align-items-center justify-content-center">
                            <!-- <button class="btn btn-danger" type="button" data-dismiss="modal">Cancel</button> -->
                            <input class="btn btn-primary" type="submit" id="addbsplSubmit" value="Done">
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Excel Upload Modal -->
        <div class="modal fade" id="addExcelModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-size" role="document">
                <div class="modal-content">
                    <form id="trialBalanceForm">
                        <div class="modal-body">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Upload Excel Form<h5>
                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div><br>
                            <div class="form-group">
                                <label for="name">Choose excel file for upload</label>
                                <input type="file" class="btn btn-upload" name="file" accept=".xls, .xlsx" required>
                                <input type="text" class="form-control" name="parent_id" value="<?php echo $prog_parentId; ?>" hidden>
                                <input type="text" class="form-control" name="pid" value="<?php echo $prog_id; ?>" hidden>
                                <input type="text" class="form-control" name="wid" value="<?php echo $wid; ?>" hidden>
                            </div>
                        </div>
                        <div class="modal-footer d-flex align-items-center justify-content-center">
                            <!-- <button class="btn btn-danger" type="button" data-dismiss="modal">Cancel</button> -->
                            <input class="btn btn-primary" type="submit" value="Upload">
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Trial Balance Response Modal -->
        <div class="modal fade" id="trialBalanceResponseModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Hey <?php echo $_SESSION['name']; ?> !</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="trialBalanceResponseText"></div>
                    </div>
                    <div class="modal-footer">
                        <a class="btn btn-primary" href="subProgram?uid=<?php echo base64_encode(md5($wid));?>&zid=<?php echo base64_encode(md5(time()));?>&aid=<?php echo base64_encode(md5($wid));?>&pid=<?php echo base64_encode($prog_id); ?>&parent_id=<?php echo base64_encode($prog_parentId); ?>&wid=<?php echo base64_encode($wid); ?>&uuid=<?php echo base64_encode(md5(date('Y')));?>&zuid=<?php echo base64_encode(md5(date('m-d-Y')));?>">OK</a>
                    </div>
                </div>
            </div>
        </div>

        <!--Add Method Modal -->
        <div class="modal fade" id="addMethod" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add New Method
                            <h5>
                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                    </div>
                    <form>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="name">Method Name</label>
                                <input type="text" class="form-control" name="name" id="method_name" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-danger" type="button" data-dismiss="modal">Cancel</button>
                            <input class="btn btn-primary" type="submit" id="addMethodSubmit" value="Done">
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!--Add Account Modal -->
        <div class="modal fade" id="addAccount" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-size" role="document">
                <div class="modal-content">
                    <form>
                        <div class="modal-body">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Add New Request </h5>
                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <div class="form-group">
                                <label for="name">Account Name</label>
                                <select class="form-control" name="account" id="account" required>
                                    <option>Select Account !</option>
                                        <?php
                                            $accQuery = $con->query("select * from accounts order by account ASC");
                                            while ($accResult = $accQuery->fetch_assoc()) {
                                        ?>
                                            <option value="<?php echo $accResult['id']; ?>">
                                                <?php echo $accResult['account']; ?>
                                            </option>
                                        <?php
                                            }
                                        ?>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer d-flex align-items-center justify-content-center">
                            <!-- <button class="btn btn-danger" type="button" data-dismiss="modal">Cancel</button> -->
                            <input class="btn btn-primary" type="submit" id="addAccountSubmit" value="Done">
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Signoff Modal-->
        <div class="modal fade" id="signoffModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog  modal-dialog-centered modal-size">
                <div class="modal-content">
                    <form name="signoff" id="trialform" action="signoff?wid=<?php echo $wid; ?>" method="POST" target="_blank" enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="modal-header" id="programname">
                            </div>
                            <br>
                            <div class="container card">
                            <?php
                            if(performanceChildCheck($prog_parentId) == 1){
                            ?>
                                <div class="row d-flex justify-content-center">
                                    <div class="form-check form-check-inline">
                                        <input name="assertion[]" class="form-check-input" type="checkbox" id="inlineCheckbox1" value="E/O">
                                        <label class="badge badge-primary form-check-label" for="inlineCheckbox1">E/O</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input name="assertion[]" class="form-check-input" type="checkbox" id="inlineCheckbox2" value="M/V">
                                        <label class="badge badge-danger form-check-label" for="inlineCheckbox2">M/V</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input name="assertion[]" class="form-check-input" type="checkbox" id="inlineCheckbox3" value="R&O">
                                        <label class="badge badge-warning form-check-label" for="inlineCheckbox3">R&O</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input name="assertion[]" class="form-check-input" type="checkbox" id="inlineCheckbox3" value="P&D">
                                        <label class="badge badge-info form-check-label" for="inlineCheckbox3">P&D</label>
                                    </div>
                                </div>
                            <?php  } ?>
                                <div class="row d-flex justify-content-between">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="files">Upload Documents</label>
                                            <div class="form-group">
                                                <input class="btn btn-upload" type="file" name="file[]" id="uploadedFile" multiple accept="application/msword, application/pdf, .doc, .docx, .pdf, .txt, .rtf, .xls, .xlxs">
                                            </div>
                                        </div>
                                        <div class="form-group"><label for="exfiles">Uploaded Files</label>
                                        <ul class="upload-list" id="filenames">
                                        </ul>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <input type="hidden" name="id" id="id">
                                        <input type="hidden" name="prog_id" id="prog_id">
                                        <label class="formg">Add Your Comment</label>
                                        <textarea name="newComment" id="newComment" class="form-control"
                                                    style="height:50px;"></textarea>
                                        <label class="formg">Comments</label>
                                        <table class="table comments-table" name="comments" id="comments"></table>
                                    </div>
                                    <div class="col-md-12 d-flex align-items-center justify-content-center">
                                        <i class="fas fa-info-circle" style="color:orange !important;"></i>
                                        <strong>Click the save button to save respective files/comments before signing off</strong>
                                    </div>
                                </div>
                                <div class="modal-footer d-flex align-items-center justify-content-center">
                                    <input name="reviewSubmit" class="btn btn-info" type="submit" id="reviewSubmit"
                                            value="Review Sign-Off">
                                    <input name="prepareSubmit" class="btn btn-primary" type="submit"
                                            id="prepareSubmit" value="Prepare Sign-Off">
                                    <input name="done" class="btn btn-success" type="submit" id="done"
                                            value="Save">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Prepare Signoff Log Modal -->
        <div class="modal fade" id="prepareLogModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-size" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Preparer Log</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div><br>
                        <table class="table" id="prepareLogTable">
                            <thead>
                                <tr>
                                    <th>
                                        Initials
                                    </th>
                                    <th>
                                        Prepare Signoff Date
                                    </th>
                                    <th>
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer  d-flex align-items-center justify-content-center">
                        <button class="btn btn-success" type="button" data-dismiss="modal">Ok</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Review Signoff Log Modal -->
        <div class="modal fade" id="reviewLogModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-size" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel"> Reviewer Log </h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div><br>
                        <table class="table" id="reviewLogTable">
                            <thead>
                                <tr>
                                    <th>
                                        Initials
                                    </th>
                                    <th>
                                        Reviewer Signoff Date
                                    </th>
                                    <th>
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer d-flex align-items-center justify-content-center">
                        <button class="btn btn-success" type="button" data-dismiss="modal">Ok</button>
                    </div>
                </div>
            </div>
        </div>

        <!--Add Question Modal -->
        <div class="modal fade" id="addQuestionModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-size" role="document">
                <div class="modal-content">
                    <form>
                        <div class="modal-body">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel"> Add Question Dialog Box </h5>
                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div><br>
                            <div class="form-group">
                                <label for="name">Question Name</label>
                                <input type="text" class="form-control" name="name" id="question_name" required>
                            </div>
                        </div>
                        <div class="modal-footer d-flex align-items-center justify-content-center">
                            <!-- <button class="btn btn-danger" type="button" data-dismiss="modal">Cancel</button> -->
                            <input class="btn btn-primary" type="submit" id="addQuestionSubmit" value="Done">
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!--Add Procedure Part A Modal -->
        <div class="modal fade" id="addProcedureABModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-size" role="document">
                <div class="modal-content">
                    <form>
                        <div class="modal-body">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel"> Add Procedure Dialog Box </h5>
                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div><br>
                            <div class="form-group">
                                <label for="name">Procedures Name</label>
                                <textarea class="form-control" name="name" id="procedure_a_name" cols="30" rows="5" required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer d-flex align-items-center justify-content-center">
                            <!-- <button class="btn btn-danger" type="button" data-dismiss="modal">Cancel</button> -->
                            <input class="btn btn-primary" type="submit" id="addProcedureASubmit" value="Done">
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!--Add Procedure Part B Modal -->
        <div class="modal fade" id="addProcedureBBModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-size" role="document">
                <div class="modal-content">
                    <form>
                        <div class="modal-body">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel"> Add Procedure Dialog Box </h5>
                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div><br>
                            <div class="form-group">
                                <label for="name">Procedures Name</label>
                                <textarea class="form-control" name="name" id="procedure_b_name" cols="30" rows="5" required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer d-flex align-items-center justify-content-center">
                            <!-- <button class="btn btn-danger" type="button" data-dismiss="modal">Cancel</button> -->
                            <input class="btn btn-primary" type="submit" id="addProcedureBSubmit" value="Done">
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!--Edit Procedure Part A Modal -->
        <div class="modal fade" id="editProcedureModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-size" role="document">
                <div class="modal-content">
                    <form method="post" action="editProcedure">
                        <div class="modal-body">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel"> Edit Procedure Dialog Box </h5>
                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div><br>
                            <div class="form-group">
                                <label for="name">Procedures Name</label>
                                <input type="hidden" name="prodecureId" id="prodecureId">
                                <textarea class="form-control" name="name" id="procedure_name" cols="30" rows="5" required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer d-flex align-items-center justify-content-center">
                            <!-- <button class="btn btn-danger" type="button" data-dismiss="modal">Cancel</button> -->
                            <input class="btn btn-primary" type="submit" id="editProcedureASubmit" value="Done">
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!--Edit Inquiring Management Part A Modal -->
        <div class="modal fade" id="editInquiringManagementModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-size" role="document">
                <div class="modal-content">
                    <form method="post" action="editInquiringManagement">
                        <div class="modal-body">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel"> Edit Question Dialog Box </h5>
                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div><br>
                            <div class="form-group">
                                <label for="name">Question Name</label>
                                <input type="hidden" name="inquiringManagementId" id="inquiringManagementId">
                                <input type="text" class="form-control" name="name" id="inquiringManagement_name" required>
                            </div>
                        </div>
                        <div class="modal-footer d-flex align-items-center justify-content-center">
                            <!-- <button class="btn btn-danger" type="button" data-dismiss="modal">Cancel</button> -->
                            <input class="btn btn-primary" type="submit" id="editInquiringManagementSubmit" value="Done">
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!--Edit Conclusion Textarea Modal -->
        <div class="modal fade" id="editTextareaModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-size" role="document">
                <div class="modal-content">
                    <form method="post" action="editConclusion">
                        <div class="modal-body">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel"> Edit Textarea Dialog Box </h5>
                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div><br>
                            <div class="form-group">
                                <label for="name">Textarea Name</label>
                                <input type="hidden" name="id" id="id">
                                <textarea class="form-control" name="name" id="textarea_name" cols="30" rows="5" required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer d-flex align-items-center justify-content-center">
                            <!-- <button class="btn btn-danger" type="button" data-dismiss="modal">Cancel</button> -->
                            <input class="btn btn-primary" type="submit" id="editTextareaSubmit" value="Done">
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!--Edit Conclusion Text Modal -->
        <div class="modal fade" id="editConclusionTextmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-size" role="document">
                <div class="modal-content">
                    <form method="post" action="editConclusionText">
                        <div class="modal-body">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel"> Edit Conclusion Text Dialog Box </h5>
                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div><br>
                            <div class="form-group">
                                <label for="name">Conclusion Text Name</label>
                                <input type="hidden" name="id" id="id">
                                <input type="hidden" name="wid" value="<?php echo $wid; ?>">
                                <input class="form-control" name="name" id="editConclusionText_name" required />
                            </div>
                        </div>
                        <div class="modal-footer d-flex align-items-center justify-content-center">
                            <!-- <button class="btn btn-danger" type="button" data-dismiss="modal">Cancel</button> -->
                            <input class="btn btn-primary" type="submit" id="editTextareaSubmit" value="Done">
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Add Audit Summery Modal-->
        <div class="modal fade" id="audit_summery_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form name="audit_summery_form" id="audit_summery" method="post" action="auditSummerySubmit?wid=<?php echo $wid; ?>">
                        <div class="modal-body">
                            <div class="modal-header">
                                <div class="form-group">
                                    <label for="">Add New Adjusment</label>
                                </div>
                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <br>
                            <div class="container card">
                                <div class="row d-flex justify-content-between">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="adjust_number">Adjustment Number</label>
                                            <div class="form-group">
                                                <input class="" type="text" name="adjustment_number" value="<?php echo $con->query("select count(id) total from summery_of_misstatements where workspace_id=$wid")->fetch_assoc()["total"] == 0 ? "AJ-01" : ++$con->query("SELECT adjust_number from summery_of_misstatements where workspace_id=$wid order by id DESC LIMIT 1")->fetch_assoc()["adjust_number"]; ?>" readonly>
                                            </div>
                                        </div>
                                        <div class="row d-flex col-md-12 p-0">
                                            <div class="form-group col-md-6">
                                                <label for="">Type</label>
                                                <select class="form-control col-md-12" name="type" id="type" required>
                                                    <option value="">Choose any One!</option>
                                                    <option value="Factual">Factual</option>
                                                    <option value="Judgmental">Judgmental</option>
                                                    <option value="Projected">Projected</option>
                                                    <option value="Reclassification">Reclassification</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6"> 
                                                <label for="">Add Misstatement</label>
                                                <select class="form-control col-md-12" name="misstatement" id="misstatement" required>
                                                    <option value="">Choose any One!</option>
                                                    <option value="Uncorrected">Uncorrected</option>
                                                    <option value="Corrected">Corrected</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="missstatements_description">Description</label>
                                            <div class="form-group">
                                                <textarea name= "missstatements_description" class="form-control"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="missstatements_details">Details</label>
                                            <div class="form-group">
                                                <table class="table table-borderless" id="tablogic_miss">
                                                    <tbody>
                                                        <tr id='addr0'>                                                            
                                                            <td>
                                                                <select class="form-control" name="misstatements_account[]" id="misstatements_account" required>
                                                                    <option>Select Account !</option>
                                                                        <?php
                                                                            $accQuery = $con->query("select * from tb_performance_map where workspace_id='$wid'");
                                                                            while ($accResult = $accQuery->fetch_assoc()) {
                                                                        ?>
                                                                            <option value="<?php echo $accResult['accounts_name']; ?>">
                                                                                <?php echo $accResult['accounts_name']; ?>
                                                                            </option>
                                                                        <?php
                                                                            }
                                                                        ?>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <input name="misstatements_amount[]" class="form-control input-lg sum" type="number" placeholder="Enter Amount">
                                                            </td>
                                                        </tr>
                                                        <tr id='addr1'></tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="row">
                                                <div class="col d-flex justify-content-between">
                                                    <a href="#" id="add_row_miss" class="btn btn-outline-primary pull-left">Add</a>
                                                    <a href="#" id='delete_row_miss' class="btn btn-outline-danger">Delete</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer d-flex align-items-center justify-content-center">
                                    <input class="btn btn-primary" type="submit" id="submit_misstatements" value="Save">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Audit Summery Modal-->
        <div class="modal fade" id="edit_audit_summery_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form name="audit_summery_form" id="edit_audit_summery" method="post" action="editAuditSummerySubmit?wid=<?php echo $wid; ?>">
                        <div class="modal-body">
                            <div class="modal-header">
                                <div class="form-group">
                                    <label for="">Add New Adjusment</label>
                                </div>
                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <br>
                            <div class="container card">
                                <div class="row d-flex justify-content-between">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="adjust_number">Adjustment Number</label>
                                            <div class="form-group">
                                                <input class="form_group" type="text" name="editAdjustment_number" id="editAdjustment_number" readonly>
                                            </div>
                                        </div>
                                        <div class="row d-flex col-md-12 p-0">
                                            <div class="form-group col-md-6">
                                                <label for="">Type</label>
                                                <select class="form-control col-md-12" name="editType" id="editType" required>
                                                    <option value="">Choose any One!</option>
                                                    <option value="Factual">Factual</option>
                                                    <option value="Judgmental">Judgmental</option>
                                                    <option value="Projected">Projected</option>
                                                    <option value="Reclassification">Reclassification</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6"> 
                                                <label for="">Add Misstatement</label>
                                                <select class="form-control col-md-12" name="editMisstatement" id="editMisstatement" required>
                                                    <option value="">Choose any One!</option>
                                                    <option value="Uncorrected">Uncorrected</option>
                                                    <option value="Corrected">Corrected</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="missstatements_description">Description</label>
                                            <div class="form-group">
                                                <textarea name= "editMissstatements_description" class="form-control" id="editMissstatements_description" ></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="missstatements_details">Details</label>
                                            <div class="form-group">
                                                <table class="table table-borderless" id="editTablogic_miss">
                                                    <tbody>
                                                        <tr id='addr0'>                                                            
                                                            <td>
                                                                <select class="form-control" name="misstatements_account[]" id="misstatements_account0" required>
                                                                    <option>Select Account !</option>
                                                                        <?php
                                                                            $accQuery = $con->query("select program.id id, program.program_name, workspace_log.amount, workspace_log.type, workspace_log.risk, workspace_log.import from program inner join workspace_log on program.id=workspace_log.program_id where program.parent_id=2 and workspace_log.workspace_id='$wid'");
                                                                            while ($accResult = $accQuery->fetch_assoc()) {
                                                                        ?>
                                                                            <option value="<?php  echo $accResult['program_name']; ?>">
                                                                                <?php echo $accResult['program_name']; ?>
                                                                            </option>
                                                                        <?php
                                                                            }
                                                                        ?>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <input name="misstatements_amount[]" id="misstatements_amount0" class="form-control input-lg sum" type="number" placeholder="Enter Amount">
                                                            </td>
                                                        </tr>
                                                        
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="row">
                                                <div class="col d-flex justify-content-between">
                                                    <a href="#" id="edit_add_row_miss" class="btn btn-outline-primary pull-left">Add</a>
                                                    <a href="#" id='edit_delete_row_miss' class="btn btn-outline-danger">Delete</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer d-flex align-items-center justify-content-center">
                                <input type="hidden" name="logId" id="logId">
                                    <input class="btn btn-primary" type="submit" id="editsubmit_misstatements" value="Save">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Change Financial Statement Sequence Modal-->
        <div class="modal fade" id="financialStatementChangeSequenceModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-size" role="document">
                <div class="modal-content">
                    <form id="financialStatementChangeSequenceForm">
                        <div class="modal-body">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Change Sequence Ordering</h5>
                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div><br>
                            <div class="form-group">
                                <center><label for="adjust_number">Unaudited Balance Sheet</label></center>
                                <?php
                                    $accountTypeResult = $con->query("SELECT DISTINCT account_type, accountTypeSeqNumber from trial_balance where workspace_id='$wid' and ( account_type not like '%Expense%' and account_type not like '%Revenue%' ) order by accountTypeSeqNumber");
                                    if($accountTypeResult->num_rows > 0){
                                        $maxCount = $accountTypeResult->num_rows;
                                        $i = 0;
                                        while($row = $accountTypeResult->fetch_assoc()){
                                            ?>
                                                <div class="form-group d-flex justify-content-around align-items-center">
                                                    <div class="col-md-6" style="text-align: center"><label><?php echo $row['account_type']; ?></label></div>
                                                        <div class="col-md-6" style="text-align: center">
                                                            <input type="hidden" name="accountTypeSeqBS[<?php echo $i; ?>][0]" id="accountTypeSeqBS[<?php echo $i; ?>][0]" value="<?php echo $row['account_type']; ?>">
                                                            <input class="form_group w-50 p-2 d-flex" type="number" min="1" max="<?php echo $maxCount;?>" step="1" name="accountTypeSeqBS[<?php echo $i; ?>][1]" id="accountTypeSeqBS[<?php echo $i++; ?>][1]" value="<?php echo $row['accountTypeSeqNumber']; ?>">
                                                        </div>
                                                </div>
                                            <?php
                                        }
                                    }
                                ?>
                            </div>
                            <hr>
                            <div class="form-group">
                                <center><label for="adjust_number">Unaudited Profit and Loss</label></center>
                                <?php
                                    $accountTypeResult = $con->query("SELECT DISTINCT account_type, accountTypeSeqNumber from trial_balance where workspace_id='$wid' and ( account_type like '%Expense%' or account_type like '%Revenue%' ) order by accountTypeSeqNumber");
                                    if($accountTypeResult->num_rows > 0){
                                        $maxCount = $accountTypeResult->num_rows;
                                        $i = 0;
                                        while($row = $accountTypeResult->fetch_assoc()){
                                            ?>
                                                <div class="form-group d-flex justify-content-around align-items-center">
                                                    <div class="col-md-6" style="text-align: center"><label><?php echo $row['account_type']; ?></label></div>
                                                    <div class="col-md-6" style="text-align: center">
                                                        <input type="hidden" name="accountTypeSeqPL[<?php echo $i; ?>][0]" id="accountTypeSeqPL[<?php echo $i; ?>][0]" value="<?php echo $row['account_type']; ?>">
                                                        <input class="form_group w-50 p-2 d-flex" type="number" min="1" max="<?php echo $maxCount;?>" step="1" name="accountTypeSeqPL[<?php echo $i; ?>][1]" id="accountTypeSeqPL[<?php echo $i++; ?>][1]" value="<?php echo $row['accountTypeSeqNumber']; ?>">
                                                    </div>
                                                </div>
                                            <?php
                                        }
                                    }
                                ?>
                            </div>
                        </div>
                        <div class="modal-footer d-flex align-items-center justify-content-center">
                            <input class="btn btn-primary" type="submit" value="Update">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php 
        }
    ?>
        <!-- Profile Photo Modal -->
        <div class="modal fade" id="photoModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-size" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Update Profile Photo </h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                    </div>
                    <form action="updatePhoto" method="POST" enctype="multipart/form-data" autocomplete="off">
                        <div class="modal-body">
                            <div class="form-group">
                                <input type="hidden" name="uid" value="<?php echo $_SESSION['id']; ?>">
                            </div>
                            <div class="form-group ">
                                <label for="name">Upload Photo</label>
                                <input type="file" class="form-control" name="image" accept="image/x-png,image/gif,image/jpeg,image/jpg" required>
                            </div>
                        </div> 
                        <div class="modal-footer justify-content-center">
                            <button class="btn btn-danger" type="button" data-dismiss="modal">Cancel</button>
                            <input class="btn btn-primary" type="submit" id="registerSubmit" value="Update">
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Show Edit Download Modal -->
        <div class="modal fade" id="showEditDownloadModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-size" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Choose a activity.</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                    </div>
                    <div class="modal-body">
                        <div class="d-flex justify-content-around align-items-center">
                            <div>
                                <div id="hasEditButtonText"></div>
                                <div id="hasEditButton"></div>
                            </div>
                            <div id="fileDownloadButton"></div>
                        </div>
                    </div> 
                    <div class="modal-footer justify-content-center">
                        <button class="btn btn-danger" type="button" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
    
    <div id="loader">
        <div class="load">
            <hr/><hr/><hr/><hr/>
        </div>
    </div>

    <!-- Custom scripts for all pages-->
    <script src="vendor/Export-Html-To-Word-Document-With-Images-Using-jQuery-Word-Export-Plugin/FileSaver.js"></script>
    <script src="vendor/Export-Html-To-Word-Document-With-Images-Using-jQuery-Word-Export-Plugin/jquery.wordexport.js"></script>
    <script type="text/javascript" src="node_modules/froala-editor/js/froala_editor.pkgd.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
    <script src="js/custom.js"></script>
    
    <?php
        if(isset($_SESSION['role']) && !empty($_SESSION['role']) && $_SESSION['role'] != 5)
        { 
    ?>
    <script>
        $(document).ready(function () {
            document.getElementsByTagName("html")[0].style.visibility = "visible";
            //Add row script
            var i = 1;
            var b = i - 1;
            var mid = this.value;

            setInterval(() => {
                let uploaded = localStorage.getItem('uploaded');
                if(uploaded){
                    document.getElementsByClassName('refreshmodal')[0].click();
                    localStorage.removeItem('uploaded');
                }
            }, 1000);

            $("#add_row").on('change', function () {
                alert(mid);
                $('#abody').append('<tr id="R' + (i + 1) +
                    '"><td class="row-index text-center"></td></tr>');
            });

            var i = 1;
            b = i - 1;
            $("#add_table").click(function() {
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
            //Add Row in Summery of Misstatements
            var j = 1;
            a = j - 1;
            $("#add_row_miss").click(function() {
                $('#addr' + j).html($('#addr' + a).html()).find('td:first-child');
                $('#tablogic_miss').append('<tr id="addr' + (j + 1) + '"></tr>');
                j++;
            });
            //Delete Row Function for Summery of Misstatements
            $("#delete_row_miss").click(function() {
                if (j > 1) {
                    $("#addr" + (j - 1)).html('');
                    j--;
                }
            });
            //Add Row in Accounting Estimates
            var x = 1;
            y = x - 1;
            $("#addEstimateRow").click(function() {
                // $('#addRowEstimate' + x).html($('#addRowEstimate' + y).html()).find('td:first-child');
                // $('#addEstimate').append('<tr id="addRowEstimate' + (x + 1) + '"></tr>');
                // x++;
                $("#accounting_estimates").append(
                    '<tr>'+'<td>'+'<select name="submitEstimate[type][]" class="form-control" required>'+'        <option value="Quantitative">Quantitative</option>'+'        <option value="Qualitative">Qualitative</option>'+'    </select>'+'</td>'+'<td><input name="submitEstimate[name][]" type="text"></td>'+'<td><input name="submitEstimate[account][]" type="text"></td>'+'<td> <input name="submitEstimate[py][]" type="number"> </td>'+'<td> <input name="submitEstimate[cy][]" type="number"> </td>'+'<td>'+'<select name="submitEstimate[c][]" class="form-control"required>'+'    <option value="Low">Low</option>'+'    <option value="Moderate">Moderate</option>'+'    <option value="High">High</option>'+'    <option value="NA">NA</option>'+'</select>'+'</td>'+'<td>'+'<select name="submitEstimate[eo][]" class="form-control"required>'+'    <option value="Low">Low</option>'+'    <option value="Moderate">Moderate</option>'+'    <option value="High">High</option>'+'    <option value="NA">NA</option>'+'</select>'+'</td>'+'<td>'+'<select name="submitEstimate[mv][]" class="form-control"required>'+'    <option value="Low">Low</option>'+'    <option value="Moderate">Moderate</option>'+'    <option value="High">High</option>'+'    <option value="NA">NA</option>'+'</select>'+'</td>'+'<td>'+'<select name="submitEstimate[ro][]" class="form-control"required>'+'    <option value="Low">Low</option>'+'    <option value="Moderate">Moderate</option>'+'    <option value="High">High</option>'+'    <option value="NA">NA</option>'+'</select>'+'</td>'+'<td>'+'<select name="submitEstimate[pd][]" class="form-control"required>'+'    <option value="Low">Low</option>'+'    <option value="Moderate">Moderate</option>'+'    <option value="High">High</option>'+'    <option value="NA">NA</option>'+'</select>'+'</td>'+'<td>'+'<select name="submitEstimate[risk][]" class="form-control"required>'+'    <option value="Low Risk">Low Risk</option>'+'    <option value="Significant Risk">Significant Risk</option>'+'    <option value="High Risk">High Risk</option>'+'    <option value="NA">NA</option>'+'</select>'+'</td>'+'</tr>'
                )
            });

            // //Delete Row Function for Summery of Misstatements
            // $("#delete_row_miss").click(function() {
            //     if (x > 1) {
            //         $("#addr" + (x - 1)).html('');
            //         x--;
            //     }
            // });

            $(document).on('click', '#submit_misstatements',function(e){
                let sumMisstatements = 0;
                $('#audit_summery_modal .sum').each(function(){
                    sumMisstatements += parseFloat(this.value);            
                });
                if(sumMisstatements != 0){
                    e.preventDefault();
                    swal({
                        icon: "error",
                        text: "Sum should be ZERO!",
                    })
                }
            });

            $("#editor > div.fr-wrapper > div:nth-child(1) > a").css("display","none");
            
            $(document).on('click', '#editsubmit_misstatements',function(e){
                let sumMisstatements = 0;
                $('#edit_audit_summery_modal .sum').each(function(){
                    sumMisstatements += parseFloat(this.value);            
                });
                // console.log(sumMisstatements)
                if(sumMisstatements != 0){
                    e.preventDefault();
                    swal({
                        icon: "error",
                        text: "Sum should be ZERO!",
                    })
                }
            });

            $(document).on('click', '.buttonActive', function () {
                var id = $(this).attr('id');
                $.ajax({
                    url: "updateActive.php",
                    type: "POST",
                    data: {
                        prog_id: id,
                        wid: <?php echo $wid; ?>
                    },
                    success: function (response) {
                        var obj = JSON.parse(response);
                        if (obj.status) {
                            swal({
                                icon: "success",
                                text: obj.text,
                            }).then(function (isConfirm) {
                                if (isConfirm) {
                                    window.location.href = window.location
                                            .pathname +
                                        "?<?php echo base64_encode(md5(time())); ?>&gid=<?php echo base64_encode(md5(time())); ?>&fid=<?php echo base64_encode(md5(time())); ?>&eid=<?php echo base64_encode(md5(time())); ?>&pid=<?php echo base64_encode($prog_id); ?>&cid=<?php echo base64_encode(md5(time())); ?>&bid=<?php echo base64_encode(md5(time())); ?>&aid=<?php echo base64_encode(md5(time())); ?>&parent_id=<?php echo base64_encode($prog_parentId); ?>&zid=<?php echo base64_encode(md5(time())); ?>&yid=<?php echo base64_encode(md5(time())); ?>&wid=<?php echo base64_encode($wid); ?>&xid=<?php echo base64_encode(md5(time())); ?>";
                                }
                            });
                        } else {
                            swal({
                                icon: "error",
                                text: obj.text,
                            }).then(function (isConfirm) {
                                if (isConfirm) {
                                    window.location.href = window.location
                                            .pathname +
                                        "?<?php echo base64_encode(md5(time())); ?>&gid=<?php echo base64_encode(md5(time())); ?>&fid=<?php echo base64_encode(md5(time())); ?>&eid=<?php echo base64_encode(md5(time())); ?>&pid=<?php echo base64_encode($prog_id); ?>&cid=<?php echo base64_encode(md5(time())); ?>&bid=<?php echo base64_encode(md5(time())); ?>&aid=<?php echo base64_encode(md5(time())); ?>&parent_id=<?php echo base64_encode($prog_parentId); ?>&zid=<?php echo base64_encode(md5(time())); ?>&yid=<?php echo base64_encode(md5(time())); ?>&wid=<?php echo base64_encode($wid); ?>&xid=<?php echo base64_encode(md5(time())); ?>";
                                }
                            });
                        }
                    }
                });
            });

            $('#addProgSubmit').on('click', function (e) {
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
                    success: function (response) {
                        if (response) {
                            swal({
                                icon: "success",
                                text: prog_name + " Added",
                            }).then(function (isConfirm) {
                                if (isConfirm) {
                                    window.location.href = window.location
                                            .pathname +
                                        "?<?php echo base64_encode(md5(time())); ?>&gid=<?php echo base64_encode(md5(time())); ?>&fid=<?php echo base64_encode(md5(time())); ?>&eid=<?php echo base64_encode(md5(time())); ?>&pid=<?php echo base64_encode($prog_id); ?>&cid=<?php echo base64_encode(md5(time())); ?>&bid=<?php echo base64_encode(md5(time())); ?>&aid=<?php echo base64_encode(md5(time())); ?>&parent_id=<?php echo base64_encode($prog_parentId); ?>&zid=<?php echo base64_encode(md5(time())); ?>&yid=<?php echo base64_encode(md5(time())); ?>&wid=<?php echo base64_encode($wid); ?>&xid=<?php echo base64_encode(md5(time())); ?>";
                                }
                            });
                        } else {
                            swal({
                                icon: "error",
                                text: "Failed!",
                            }).then(function (isConfirm) {
                                if (isConfirm) {
                                    window.location.href = window.location
                                            .pathname +
                                        "?<?php echo base64_encode(md5(time())); ?>&gid=<?php echo base64_encode(md5(time())); ?>&fid=<?php echo base64_encode(md5(time())); ?>&eid=<?php echo base64_encode(md5(time())); ?>&pid=<?php echo base64_encode($prog_id); ?>&cid=<?php echo base64_encode(md5(time())); ?>&bid=<?php echo base64_encode(md5(time())); ?>&aid=<?php echo base64_encode(md5(time())); ?>&parent_id=<?php echo base64_encode($prog_parentId); ?>&zid=<?php echo base64_encode(md5(time())); ?>&yid=<?php echo base64_encode(md5(time())); ?>&wid=<?php echo base64_encode($wid); ?>&xid=<?php echo base64_encode(md5(time())); ?>";
                                }
                            });
                        }
                    }
                });
            });

            $('#addbsplSubmit').on('click', function (e) {
                e.preventDefault();
                var bspl_name = $("#bspl_name").val();
                // var bspl_type = $("#bspl_type").val();
                let bspl_header_type = $("#bspl_header_type").val();
                $.ajax({
                    url: "addbspl.php",
                    type: "POST",
                    data: {
                        prog_id: "2",
                        wid: <?php echo $wid; ?>,
                        bspl_name: bspl_name,
                        bspl_header_type: bspl_header_type
                    },
                    success: function (response) {
                        if (response) {
                            swal({
                                icon: "success",
                                text: bspl_name + " Added",
                            }).then(function (isConfirm) {
                                if (isConfirm) {
                                    window.location.href = window.location
                                            .pathname +
                                        "?<?php echo base64_encode(md5(time())); ?>&gid=<?php echo base64_encode(md5(time())); ?>&fid=<?php echo base64_encode(md5(time())); ?>&eid=<?php echo base64_encode(md5(time())); ?>&pid=<?php echo base64_encode($prog_id); ?>&cid=<?php echo base64_encode(md5(time())); ?>&bid=<?php echo base64_encode(md5(time())); ?>&aid=<?php echo base64_encode(md5(time())); ?>&parent_id=<?php echo base64_encode($prog_parentId); ?>&zid=<?php echo base64_encode(md5(time())); ?>&yid=<?php echo base64_encode(md5(time())); ?>&wid=<?php echo base64_encode($wid); ?>&xid=<?php echo base64_encode(md5(time())); ?>";
                                }
                            });
                        } else {
                            swal({
                                icon: "error",
                                text: "Failed!",
                            }).then(function (isConfirm) {
                                if (isConfirm) {
                                    window.location.href = window.location
                                            .pathname +
                                        "?<?php echo base64_encode(md5(time())); ?>&gid=<?php echo base64_encode(md5(time())); ?>&fid=<?php echo base64_encode(md5(time())); ?>&eid=<?php echo base64_encode(md5(time())); ?>&pid=<?php echo base64_encode($prog_id); ?>&cid=<?php echo base64_encode(md5(time())); ?>&bid=<?php echo base64_encode(md5(time())); ?>&aid=<?php echo base64_encode(md5(time())); ?>&parent_id=<?php echo base64_encode($prog_parentId); ?>&zid=<?php echo base64_encode(md5(time())); ?>&yid=<?php echo base64_encode(md5(time())); ?>&wid=<?php echo base64_encode($wid); ?>&xid=<?php echo base64_encode(md5(time())); ?>";
                                }
                            });
                        }
                    }
                });
            });
            
            $('#addMethodSubmit').on('click', function (e) {
                e.preventDefault();
                var method_name = $("#method_name").val();
                $.ajax({
                    url: "addMethod.php",
                    type: "POST",
                    data: {
                        prog_id: <?php echo $prog_id; ?>,
                        wid: <?php echo $wid; ?>,
                        name: method_name
                    },
                    success: function (response) {
                        if (response) {
                            swal({
                                icon: "success",
                                text: method_name + " Added",
                            }).then(function (isConfirm) {
                                if (isConfirm) {
                                    window.location.href = window.location
                                            .pathname +
                                        "?<?php echo base64_encode(md5(time())); ?>&gid=<?php echo base64_encode(md5(time())); ?>&fid=<?php echo base64_encode(md5(time())); ?>&eid=<?php echo base64_encode(md5(time())); ?>&pid=<?php echo base64_encode($prog_id); ?>&cid=<?php echo base64_encode(md5(time())); ?>&bid=<?php echo base64_encode(md5(time())); ?>&aid=<?php echo base64_encode(md5(time())); ?>&parent_id=<?php echo base64_encode($prog_parentId); ?>&zid=<?php echo base64_encode(md5(time())); ?>&yid=<?php echo base64_encode(md5(time())); ?>&wid=<?php echo base64_encode($wid); ?>&xid=<?php echo base64_encode(md5(time())); ?>";
                                }
                            });
                        } else {
                            swal({
                                icon: "error",
                                text: "Failed!",
                            }).then(function (isConfirm) {
                                if (isConfirm) {
                                    window.location.href = window.location
                                            .pathname +
                                        "?<?php echo base64_encode(md5(time())); ?>&gid=<?php echo base64_encode(md5(time())); ?>&fid=<?php echo base64_encode(md5(time())); ?>&eid=<?php echo base64_encode(md5(time())); ?>&pid=<?php echo base64_encode($prog_id); ?>&cid=<?php echo base64_encode(md5(time())); ?>&bid=<?php echo base64_encode(md5(time())); ?>&aid=<?php echo base64_encode(md5(time())); ?>&parent_id=<?php echo base64_encode($prog_parentId); ?>&zid=<?php echo base64_encode(md5(time())); ?>&yid=<?php echo base64_encode(md5(time())); ?>&wid=<?php echo base64_encode($wid); ?>&xid=<?php echo base64_encode(md5(time())); ?>";
                                }
                            });
                        }
                    }
                });
            });

            $('#addAccountSubmit').on('click', function (e) {
                e.preventDefault();
                var account_id = $("#account").val();
                $.ajax({
                    url: "addAccount.php",
                    type: "POST",
                    data: {
                        wid: <?php echo $wid; ?>,
                        account_id: account_id
                    },
                    success: function (response) {
                        if (response) {
                            swal({
                                icon: "success",
                                text: "New Request" + " Added",
                            }).then(function (isConfirm) {
                                if (isConfirm) {
                                    window.location.href = window.location.pathname + "?<?php echo base64_encode(md5(time())); ?>&gid=<?php echo base64_encode(md5(time())); ?>&fid=<?php echo base64_encode(md5(time())); ?>&eid=<?php echo base64_encode(md5(time())); ?>&pid=<?php echo base64_encode($prog_id); ?>&cid=<?php echo base64_encode(md5(time())); ?>&bid=<?php echo base64_encode(md5(time())); ?>&aid=<?php echo base64_encode(md5(time())); ?>&parent_id=<?php echo base64_encode($prog_parentId); ?>&zid=<?php echo base64_encode(md5(time())); ?>&yid=<?php echo base64_encode(md5(time())); ?>&wid=<?php echo base64_encode($wid); ?>&xid=<?php echo base64_encode(md5(time())); ?>";
                                }
                            });
                        } else {
                            swal({
                                icon: "error",
                                text: "Failed!",
                            }).then(function (isConfirm) {
                                if (isConfirm) {
                                    window.location.href = window.location
                                            .pathname +
                                        "?<?php echo base64_encode(md5(time())); ?>&gid=<?php echo base64_encode(md5(time())); ?>&fid=<?php echo base64_encode(md5(time())); ?>&eid=<?php echo base64_encode(md5(time())); ?>&pid=<?php echo base64_encode($prog_id); ?>&cid=<?php echo base64_encode(md5(time())); ?>&bid=<?php echo base64_encode(md5(time())); ?>&aid=<?php echo base64_encode(md5(time())); ?>&parent_id=<?php echo base64_encode($prog_parentId); ?>&zid=<?php echo base64_encode(md5(time())); ?>&yid=<?php echo base64_encode(md5(time())); ?>&wid=<?php echo base64_encode($wid); ?>&xid=<?php echo base64_encode(md5(time())); ?>";
                                }
                            });
                        }
                    }
                });
            });

            $(document).on('click', '.deleteMat', function () {
                var id = $(this).attr('id');
                $.ajax({
                    url: "deleteMat.php",
                    type: "POST",
                    data: {
                        mat_id: id,
                        wid: <?php echo $wid; ?>
                    },
                    success: function (response) {
                        var obj = JSON.parse(response);
                        if (obj.status) {
                            swal({
                                icon: "success",
                                text: obj.text,
                            }).then(function (isConfirm) {
                                if (isConfirm) {
                                    window.location.href = window.location
                                            .pathname +
                                        "?<?php echo base64_encode(md5(time())); ?>&gid=<?php echo base64_encode(md5(time())); ?>&fid=<?php echo base64_encode(md5(time())); ?>&eid=<?php echo base64_encode(md5(time())); ?>&pid=<?php echo base64_encode($prog_id); ?>&cid=<?php echo base64_encode(md5(time())); ?>&bid=<?php echo base64_encode(md5(time())); ?>&aid=<?php echo base64_encode(md5(time())); ?>&parent_id=<?php echo base64_encode($prog_parentId); ?>&zid=<?php echo base64_encode(md5(time())); ?>&yid=<?php echo base64_encode(md5(time())); ?>&wid=<?php echo base64_encode($wid); ?>&xid=<?php echo base64_encode(md5(time())); ?>";
                                }
                            });
                        } else {
                            swal({
                                icon: "error",
                                text: obj.text,
                            }).then(function (isConfirm) {
                                if (isConfirm) {
                                    window.location.href = window.location
                                            .pathname +
                                        "?<?php echo base64_encode(md5(time())); ?>&gid=<?php echo base64_encode(md5(time())); ?>&fid=<?php echo base64_encode(md5(time())); ?>&eid=<?php echo base64_encode(md5(time())); ?>&pid=<?php echo base64_encode($prog_id); ?>&cid=<?php echo base64_encode(md5(time())); ?>&bid=<?php echo base64_encode(md5(time())); ?>&aid=<?php echo base64_encode(md5(time())); ?>&parent_id=<?php echo base64_encode($prog_parentId); ?>&zid=<?php echo base64_encode(md5(time())); ?>&yid=<?php echo base64_encode(md5(time())); ?>&wid=<?php echo base64_encode($wid); ?>&xid=<?php echo base64_encode(md5(time())); ?>";
                                }
                            });
                        }
                    }
                });
            });

            $(document).on('click', '.deleteAcc', function () {
                var id = $(this).attr('id');
                $.ajax({
                    url: "deleteAcc.php",
                    type: "POST",
                    data: {
                        acc_id: id,
                        wid: <?php echo $wid; ?>
                    },
                    success: function (response) {
                        var obj = JSON.parse(response);
                        if (obj.status) {
                            swal({
                                icon: "success",
                                text: obj.text,
                            }).then(function (isConfirm) {
                                if (isConfirm) {
                                    window.location.href = window.location
                                            .pathname +
                                        "?<?php echo base64_encode(md5(time())); ?>&gid=<?php echo base64_encode(md5(time())); ?>&fid=<?php echo base64_encode(md5(time())); ?>&eid=<?php echo base64_encode(md5(time())); ?>&pid=<?php echo base64_encode($prog_id); ?>&cid=<?php echo base64_encode(md5(time())); ?>&bid=<?php echo base64_encode(md5(time())); ?>&aid=<?php echo base64_encode(md5(time())); ?>&parent_id=<?php echo base64_encode($prog_parentId); ?>&zid=<?php echo base64_encode(md5(time())); ?>&yid=<?php echo base64_encode(md5(time())); ?>&wid=<?php echo base64_encode($wid); ?>&xid=<?php echo base64_encode(md5(time())); ?>";
                                }
                            });
                        } else {
                            swal({
                                icon: "error",
                                text: obj.text,
                            }).then(function (isConfirm) {
                                if (isConfirm) {
                                    window.location.href = window.location
                                            .pathname +
                                        "?<?php echo base64_encode(md5(time())); ?>&gid=<?php echo base64_encode(md5(time())); ?>&fid=<?php echo base64_encode(md5(time())); ?>&eid=<?php echo base64_encode(md5(time())); ?>&pid=<?php echo base64_encode($prog_id); ?>&cid=<?php echo base64_encode(md5(time())); ?>&bid=<?php echo base64_encode(md5(time())); ?>&aid=<?php echo base64_encode(md5(time())); ?>&parent_id=<?php echo base64_encode($prog_parentId); ?>&zid=<?php echo base64_encode(md5(time())); ?>&yid=<?php echo base64_encode(md5(time())); ?>&wid=<?php echo base64_encode($wid); ?>&xid=<?php echo base64_encode(md5(time())); ?>";
                                }
                            });
                        }
                    }
                });
            });
            
            $(document).on('click', '#done', function(e){
                let newComment = $("#signoffModal #newComment").val().trim();
                let fileCount = $("#signoffModal #uploadedFile").val();
                if(newComment == '' && fileCount == ''){
                    e.preventDefault();
                    swal({
                        icon: 'success',
                        text: "No changes to be updated!",
                    });
                }
            });

            $(document).on('click', '#sendInvitation', function () {
                $.ajax({
                    url: "sendMailAjax.php",
                    type: "POST",
                    data: {
                        wid: <?php echo trim($wid); ?>
                    },
                    success: function(data){
                        let obj = JSON.parse(data)
                        if(obj.status == 1){
                            text = 'Email successfully send for '+obj.successEmailList;
                            if(obj.unsuccessEmailList != ''){
                                text += ' and email sending was unsuccessfull for '+obj.unsuccessEmailList+' email id\'s';
                            }
                        }
                        else{
                            text = 'Kindly save before sending invitation.';
                        }
                        swal({
                            icon: obj.status == 1 ? 'success':'error',
                            text: text,
                        }).then(function (isConfirm) {
                            if (isConfirm) {
                                window.location.href = window.location
                                        .pathname +
                                    "?<?php echo base64_encode(md5(time())); ?>&gid=<?php echo base64_encode(md5(time())); ?>&fid=<?php echo base64_encode(md5(time())); ?>&eid=<?php echo base64_encode(md5(time())); ?>&pid=<?php echo base64_encode($prog_id); ?>&cid=<?php echo base64_encode(md5(time())); ?>&bid=<?php echo base64_encode(md5(time())); ?>&aid=<?php echo base64_encode(md5(time())); ?>&parent_id=<?php echo base64_encode($prog_parentId); ?>&zid=<?php echo base64_encode(md5(time())); ?>&yid=<?php echo base64_encode(md5(time())); ?>&wid=<?php echo base64_encode($wid); ?>&xid=<?php echo base64_encode(md5(time())); ?>";
                            }
                        }); 
                    }
                });
            });

            $(document).on('click', '.fetchPrepare', function () {
                let id = $(this).attr("id");
                $.ajax({
                    url: "fetchPrepareAjax.php",
                    type: "POST",
                    data: {
                        pid: id,
                        wid: <?php echo $wid; ?>
                    },
                    success: function(data){
                        let obj = JSON.parse(data);
                        $("#prepareLogTable > tbody").empty()
                        for(let i in obj){
                            $("#prepareLogTable > tbody").append("<tr><td>"+obj[i][0]+"</td><td>"+obj[i][1]+"</td><td><a href='#' id='"+obj[i][2]+"' class='deletePrepare'>Delete</a</td></tr>")
                        }
                        $("#prepareLogModal").modal("show")
                    }
                });
            });

            $(document).on('click', '.deletePrepare', function () {
                let id = $(this).attr("id");
                $.ajax({
                    url: "deletePrepareAjax.php",
                    type: "POST",
                    data: {
                        id: id
                    },
                    success: function(data){
                        let responseText = data == 1?'Prepare Sign Off is deleted':'Prepare Sign Off not deleted'
                        data = data == 1?'success':'error'
                        swal({
                                icon: data,
                                text: responseText,
                            }).then(function (isConfirm) {
                                if (isConfirm) {
                                    window.location.href = window.location
                                            .pathname +
                                        "?<?php echo base64_encode(md5(time())); ?>&gid=<?php echo base64_encode(md5(time())); ?>&fid=<?php echo base64_encode(md5(time())); ?>&eid=<?php echo base64_encode(md5(time())); ?>&pid=<?php echo base64_encode($prog_id); ?>&cid=<?php echo base64_encode(md5(time())); ?>&bid=<?php echo base64_encode(md5(time())); ?>&aid=<?php echo base64_encode(md5(time())); ?>&parent_id=<?php echo base64_encode($prog_parentId); ?>&zid=<?php echo base64_encode(md5(time())); ?>&yid=<?php echo base64_encode(md5(time())); ?>&wid=<?php echo base64_encode($wid); ?>&xid=<?php echo base64_encode(md5(time())); ?>";
                                }
                            });
                    }
                });
            });

            $(document).on('click', '.fetchReview', function () {
                let id = $(this).attr("id"); 
                $.ajax({
                    url: "fetchReviewAjax.php",
                    type: "POST",
                    data: {
                        pid: id,
                        wid: <?php echo $wid; ?>
                    },
                    success: function(data){
                        let obj = JSON.parse(data);
                        $("#reviewLogTable > tbody").empty()
                        for(let i in obj){
                            $("#reviewLogTable > tbody").append("<tr><td>"+obj[i][0]+"</td><td>"+obj[i][1]+"</td><td><a href='#' id='"+obj[i][2]+"' class='deleteReview'>Delete</a</td></tr>")
                        }
                        $("#reviewLogModal").modal("show")
                    }
                });
            });

            $(document).on('click', '.deleteReview', function () {
                let id = $(this).attr("id");
                $.ajax({
                    url: "deleteReviewAjax.php",
                    type: "POST",
                    data: {
                        id: id
                    },
                    success: function(data){
                        let responseText = data == 1?'Review Sign Off is deleted':'Review Sign Off not deleted'
                        data = data == 1?'success':'error'
                        swal({
                                icon: data,
                                text: responseText,
                            }).then(function (isConfirm) {
                                if (isConfirm) {
                                    window.location.href = window.location
                                            .pathname +
                                        "?<?php echo base64_encode(md5(time())); ?>&gid=<?php echo base64_encode(md5(time())); ?>&fid=<?php echo base64_encode(md5(time())); ?>&eid=<?php echo base64_encode(md5(time())); ?>&pid=<?php echo base64_encode($prog_id); ?>&cid=<?php echo base64_encode(md5(time())); ?>&bid=<?php echo base64_encode(md5(time())); ?>&aid=<?php echo base64_encode(md5(time())); ?>&parent_id=<?php echo base64_encode($prog_parentId); ?>&zid=<?php echo base64_encode(md5(time())); ?>&yid=<?php echo base64_encode(md5(time())); ?>&wid=<?php echo base64_encode($wid); ?>&xid=<?php echo base64_encode(md5(time())); ?>";
                                }
                            });
                    }
                });
            });

            $(document).on('click', '.deleteComment', function () {
                let id = $(this).attr("id");
                $.ajax({
                    url: "deleteCommentAjax.php",
                    type: "POST",
                    data: {
                        id: id
                    },
                    success: function(data){
                        let responseText = data == 1?'Comment is deleted':'Comment not deleted'
                        data = data == 1?'success':'error'
                        swal({
                            icon: data,
                            text: responseText,
                        }).then((value) => {
                            document.getElementsByClassName('refreshmodal')[0].click();
                        });
                    }
                });
            });

            $(document).on('click', '.deleteFile', function () {
                let id = $(this).attr("id");
                $.ajax({
                    url: "deleteFileAjax.php",
                    type: "POST",
                    data: {
                        id: id,
                        type: '1'
                    },
                    success: function(data){
                        let responseText = data == 1?'File is deleted':'File not deleted'
                        data = data == 1?'success':'error'
                        swal({
                                icon: data,
                                text: responseText,
                            }).then(function (isConfirm) {
                                if (isConfirm) {
                                    document.getElementsByClassName('refreshmodal')[0].click();
                                }
                            });
                    }
                });
            });

            $(document).on('click', '.signoffmodal', function () {
                var id = $(this).attr("id");
                $("#signoffModal #active1 > option:selected").removeAttr('selected');
                $("#signoffModal #filenames").empty();
                $("#signoffModal #programname").empty();
                $("#signoffModal #newComment").val("");
                $("#signoffModal #comments").val("");
                $("#signoffModal #uploadedFile").val("");
                $("#signoffModal #id").val("");
                $("#signoffModal #prog_id").val("");
                $.ajax({
                    url: "admin/signoffFetchAjax.php",
                    type: "POST",
                    data: {
                        id: id,
                        wid: <?php echo $wid; ?>
                    },
                    success: function (data) {
                        obj = JSON.parse(data);
                        {
                            $("#reviewSubmit, #prepareSubmit").hide()
                            if(obj.comment.length != 0 || obj.file.length != 0){
                                $("#prepareSubmit").show()
                                if(obj.prepareSignOff.length != 0){
                                    $("#reviewSubmit").show()
                                }
                            }
                        }
                        

                        obj.file.forEach(function (value) {
                            $('#signoffModal #filenames').append(
                                '<li class="custom-list-items custom-list-items-action" id="' +value[0] + '"><a href="#" class="fileEditDownload" id="'+value[1]+'">' +value[1] + '</a>&nbsp;<a href="#"><i id="'+value[0]+'" class="fas fa-times-circle deleteFile" style="color:red !important;"></a></li>');
                        });
                        if (obj.comment.length != 0) {
                            $('#signoffModal #comments').empty().append('<thead><tr><th>Comments</th><th>Action</th></tr></thead><tbody>');
                            obj.comment.forEach(function (value) {
                                $('#signoffModal #comments').append('<tr><td>'+value[1]+'</td><td><a href="#" id="'+value[0]+'" class="deleteComment">Delete</a></td></tr>');
                            });
                            $('#signoffModal #comments').append('<tbody>');
                        }
                        if(obj.comment.length == 0){
                            $('#signoffModal #comments').empty().append('<thead><tr><th>Comments</th><th>Action</th></tr></thead><tbody><tr><td>No</td><td>Comment</td></tr></tbody>');
                        }
                        $("#signoffModal #programname").append('<h5 class="modal-title">' +
                            obj.pname['program_name'] +
                            '</h5><button class="close refreshmodal" type="button" id="'+id+'" hidden><span aria-hidden="true"><i class="fas fa-redo" style="font-size: 1.3rem !important;"></i></span></button><button class="close" type="button" data-dismiss="modal" aria-label="Close" style="margin-left: 0 !important;"><span aria-hidden="true">×</span></button>'
                        );
                    }
                });
                $("#signoffModal #prog_id").val(id);
                $("#signoffModal").modal('show');
            });

            $(document).on('click', '.refreshmodal', function () {
                var id = $(this).attr("id");
                $("#signoffModal #active1 > option:selected").removeAttr('selected');
                $("#signoffModal #filenames").empty();
                $("#signoffModal #programname").empty();
                $("#signoffModal #newComment").val("");
                $("#signoffModal #comments").val("");
                $("#signoffModal #uploadedFile").val("");
                $("#signoffModal #id").val("");
                $("#signoffModal #prog_id").val("");
                $.ajax({
                    url: "admin/signoffFetchAjax.php",
                    type: "POST",
                    data: {
                        id: id,
                        wid: <?php echo $wid; ?>
                    },
                    success: function (data) {
                        obj = JSON.parse(data);
                        {
                            $("#reviewSubmit, #prepareSubmit").hide()
                            if(obj.comment.length != 0 || obj.file.length != 0){
                                $("#prepareSubmit").show()
                                if(obj.prepareSignOff.length != 0){
                                    $("#reviewSubmit").show()
                                }
                            }
                        }
                        

                        obj.file.forEach(function (value) {
                            $('#signoffModal #filenames').append(
                                '<li class="custom-list-items custom-list-items-action" id="' + value[0] + '"><a href="#" class="fileEditDownload" id="'+value[1]+'">' +value[1] + '</a>&nbsp;<a href="#"><i id="'+value[0]+'" class="fas fa-times-circle deleteFile" style="color:red !important;"></a></li>');
                        });
                        if (obj.comment.length != 0) {
                            $('#signoffModal #comments').empty().append('<thead><tr><th>Comments</th><th>Action</th></tr></thead><tbody>');
                            obj.comment.forEach(function (value) {
                                $('#signoffModal #comments').append('<tr><td>'+value[1]+'</td><td><a href="#" id="'+value[0]+'" class="deleteComment">Delete</a></td></tr>');
                            });
                            $('#signoffModal #comments').append('<tbody>');
                        }
                        if(obj.comment.length == 0){
                            $('#signoffModal #comments').empty().append('<thead><tr><th>Comments</th><th>Action</th></tr></thead><tbody><tr><td>No</td><td>Comment</td></tr></tbody>');
                        }
                        $("#signoffModal #programname").append('<h5 class="modal-title">' +
                            obj.pname['program_name'] +
                            '</h5><button class="close refreshmodal" type="button" id="'+id+'" hidden><span aria-hidden="true"><i class="fas fa-redo" style="style="font-size: 1.3rem !important;""></i></span></button><button class="close" type="button" data-dismiss="modal" aria-label="Close" style="margin-left: 0 !important;"><span aria-hidden="true">×</span></button>'
                        );
                    }
                });
                $("#signoffModal #prog_id").val(id);
                $("#signoffModal").modal('show');
            });

            $('#signoffModal').on('hidden.bs.modal', function () {
                location.reload();
            });

            $("#draft_report_show_div").hide()

            $('#addQuestionSubmit').on('click', function (e) {
                e.preventDefault();
                var question_name = $("#question_name").val();
                $.ajax({
                    url: "addInquiringManagementQuestionAjax.php",
                    type: "POST",
                    data: {
                        wid: <?php echo $wid; ?>,
                        name: question_name,
                    },
                    success: function (response) {
                        if (response) {
                            swal({
                                icon: "success",
                                text: "Successfully Added",
                            }).then(function (isConfirm) {
                                if (isConfirm) {
                                    location.reload();
                                }
                            });
                        } else {
                            swal({
                                icon: "error",
                                text: "Failed!",
                            }).then(function (isConfirm) {
                                if (isConfirm) {
                                    location.reload();
                                }
                            });
                        }
                    }
                });
            });

            //Validate Asset=Liability
            
            $(document).on('click','#validateSubmit', function(e){
                let assetSum = liabilitySum = 0;
                for(j in $("#balanceSheetForm > table > tbody > tr")){
                    i = parseInt(j)+1;
                    try {
                        if($("#balanceSheetForm > table > tbody > tr:nth-child("+i+")")){
                            if($("#balanceSheetForm > table > tbody > tr:nth-child("+i+")").attr('class') != 'table-secondary'){
                                let header_type = $("#balanceSheetForm > table > tbody > tr:nth-child("+i+") > td:nth-child(2) > input").val();
                                if(header_type == '1'){
                                    if(parseInt($("#balanceSheetForm > table > tbody > tr:nth-child("+i+") > td:nth-child(4) > input").val())){
                                        liabilitySum += parseFloat($("#balanceSheetForm > table > tbody > tr:nth-child("+i+") > td:nth-child(4) > input").val(), 10)
                                    }
                                }
                                else{
                                    if(parseInt($("#balanceSheetForm > table > tbody > tr:nth-child("+i+") > td:nth-child(4) > input").val())){
                                        assetSum += parseFloat($("#balanceSheetForm > table > tbody > tr:nth-child("+i+") > td:nth-child(4) > input").val(), 10)
                                    }
                                }
                            }
                        }
                    }
                    catch (error) {
                        
                    }
                }
                if(assetSum != liabilitySum){
                    e.preventDefault()
                    swal({
                        icon: "error",
                        text: "Assets and Liabilities are not matching",
                    });
                }
            })

            $(document).ready(function() {
                var dataTable = $('#trialBalanceTable').DataTable({
                    "destroy": true,
                    "processing": true,
                    "serverSide": true,
                    "searching": true,
                    "order": [],
                    "bInfo": false,
                    "drawCallback": function(settings) {
                        var pagination = $(this).closest('.dataTables_wrapper').find('.dataTables_paginate');
                        pagination.toggle(this.api().page.info().pages > 1);
                        $(".helpDesign, #helpDescription").hide();
                    },
                    "ajax": $.fn.dataTable.pipeline({
                        url: "trialBalanceFetchAjax.php",
                        type: "POST",
                        data: {wid: <?php echo $wid; ?>},
                        pages: 2 // number of pages to cache
                    })
                });
            });

            $.fn.dataTable.pipeline = function ( opts ) {
                // Configuration options
                var conf = $.extend( {
                    pages: 2,     // number of pages to cache
                    url: '',      // script url
                    data: null,   // function or object with parameters to send to the server
                                // matching how `ajax.data` works in DataTables
                    method: 'POST' // Ajax HTTP method
                }, opts );
            
                // Private variables for storing the cache
                var cacheLower = -1;
                var cacheUpper = null;
                var cacheLastRequest = null;
                var cacheLastJson = null;
            
                return function ( request, drawCallback, settings ) {
                    var ajax          = false;
                    var requestStart  = request.start;
                    var drawStart     = request.start;
                    var requestLength = request.length;
                    var requestEnd    = requestStart + requestLength;
                    
                    if ( settings.clearCache ) {
                        // API requested that the cache be cleared
                        ajax = true;
                        settings.clearCache = false;
                    }
                    else if ( cacheLower < 0 || requestStart < cacheLower || requestEnd > cacheUpper ) {
                        // outside cached data - need to make a request
                        ajax = true;
                    }
                    else if ( JSON.stringify( request.order )   !== JSON.stringify( cacheLastRequest.order ) ||
                            JSON.stringify( request.columns ) !== JSON.stringify( cacheLastRequest.columns ) ||
                            JSON.stringify( request.search )  !== JSON.stringify( cacheLastRequest.search )
                    ) {
                        // properties changed (ordering, columns, searching)
                        ajax = true;
                    }
                    
                    // Store the request for checking next time around
                    cacheLastRequest = $.extend( true, {}, request );
            
                    if ( ajax ) {
                        // Need data from the server
                        if ( requestStart < cacheLower ) {
                            requestStart = requestStart - (requestLength*(conf.pages-1));
            
                            if ( requestStart < 0 ) {
                                requestStart = 0;
                            }
                        }
                        
                        cacheLower = requestStart;
                        cacheUpper = requestStart + (requestLength * conf.pages);
            
                        request.start = requestStart;
                        request.length = requestLength*conf.pages;
            
                        // Provide the same `data` options as DataTables.
                        if ( typeof conf.data === 'function' ) {
                            // As a function it is executed with the data object as an arg
                            // for manipulation. If an object is returned, it is used as the
                            // data object to submit
                            var d = conf.data( request );
                            if ( d ) {
                                $.extend( request, d );
                            }
                        }
                        else if ( $.isPlainObject( conf.data ) ) {
                            // As an object, the data given extends the default
                            $.extend( request, conf.data );
                        }
            
                        return $.ajax( {
                            "type":     conf.method,
                            "url":      conf.url,
                            "data":     request,
                            "dataType": "json",
                            "cache":    false,
                            "success":  function ( json ) {
                                cacheLastJson = $.extend(true, {}, json);
            
                                if ( cacheLower != drawStart ) {
                                    json.data.splice( 0, drawStart-cacheLower );
                                }
                                if ( requestLength >= -1 ) {
                                    json.data.splice( requestLength, json.data.length );
                                }
                                
                                drawCallback( json );
                            }
                        } );
                    }
                    else {
                        json = $.extend( true, {}, cacheLastJson );
                        json.draw = request.draw; // Update the echo for each response
                        json.data.splice( 0, requestStart-cacheLower );
                        json.data.splice( requestLength, json.data.length );
            
                        drawCallback(json);
                    }
                }
            };
            
            $.fn.dataTable.Api.register( 'clearPipeline()', function () {
                return this.iterator( 'table', function ( settings ) {
                    settings.clearCache = true;
                } );
            });

            let darkmode = <?php echo $_SESSION['darkmode']; ?>;

            if(darkmode)
            {
                document.documentElement.classList.toggle('dark-mode');
                
            }
            
            else if(!darkmode){
                document.documentElement.classList.remove('dark-mode');
            }

            $(document).on('click', '#exportDOC',function(e){
                $.ajax({
                    url: "export2DocAjax.php",
                    type: "POST",
                    data: {
                        wid: <?php echo $wid; ?>
                    },
                    success: function (response) {
                        if(response){
                            // Creating a MS Doc file
                            var preHtml = "<html xmlns:o='urn:schemas-microsoft-com:office:office' xmlns:w='urn:schemas-microsoft-com:office:word' xmlns='http://www.w3.org/TR/REC-html40'><head><meta charset='utf-8'><title>Export HTML To Doc</title></head><body>";
                            var postHtml = "</body></html>";
                            var element = document.getElementById('DraftReportHtml').innerHTML;
                            var html = preHtml+element+postHtml;
                            var blob = new Blob(['\ufeff', html], {
                                type: 'application/msword'
                            });
                            var url = 'data:application/vnd.ms-word;charset=utf-8,' + encodeURIComponent(html);
                            <?php $draftResult = $con->query("select type_of_audit_report from draft_report where workspace_id = $wid");
                                if($draftResult->num_rows > 0){
                                    $draftResult = $draftResult->fetch_assoc();
                                    ?>
                                        filename = 'Draft Report <?php echo $draftResult['type_of_audit_report'] == 1 ? "Qualified":"Unqualified"; ?> .doc';
                                    <?php
                                }
                            ?>
                            var downloadLink = document.createElement("a");
                            document.body.appendChild(downloadLink);
                            if(navigator.msSaveOrOpenBlob ){
                                navigator.msSaveOrOpenBlob(blob, filename);
                            }else{
                                downloadLink.href = url;
                                downloadLink.download = filename;   
                                downloadLink.click();
                            }
                            document.body.removeChild(downloadLink);
                        }
                    }
                });
            });
        });

        let audit_report_data;
        let audit_report;
        let other_matter;
        let emphasis_of_matters;
        let editor = new FroalaEditor('#editor')
        new FroalaEditor('#procedure_a_name',{attribution:false,autofocus:true})
        new FroalaEditor('#procedure_b_name',{attribution:false,autofocus:true})

        function type_audit_report(e){
            audit_report = $("#audit_report").val();
            if(audit_report == 1){
                $("#emphasis_of_matters").attr("required",false)
                $("#other_matters").attr("required",false)
                $("#emphasis_of_matters_div").hide();
                $("#other_matters_div").hide();
            }
            else{
                $("#emphasis_of_matters").attr("required",true)
                $("#other_matters").attr("required",true)
                $("#emphasis_of_matters_div").show();
                $("#other_matters_div").show();
            }
        }

        $("#draft_report_form").submit(function(e){
            e.preventDefault();
            $("#draft_report_show_div").show()
            audit_report = $("#audit_report").val();
            other_matter = $("#other_matters").val();
            emphasis_of_matters = $("#emphasis_of_matters").val();
            
            if(audit_report == 0){
                $("#unqualified_opinion").show();
                $("#qualified_opinion").hide();
                emphasis_of_matters == 0 ? $("#unqualified_opinion_emphasis_of_matters_body").hide():$("#unqualified_opinion_emphasis_of_matters_body").show()
                other_matter == 0 ? $("#unqualified_opinion_other_matters_body").hide():$("#unqualified_opinion_other_matters_body").show()
                audit_report_data = $('#unqualified_opinion').html()
            }
            else{
                $("#unqualified_opinion").hide();
                $("#qualified_opinion").show()
                audit_report_data = $('#qualified_opinion').html();
                emphasis_of_matters = 0;
                other_matter = 0;
            }
        });

        $(document).on('click','#save_audit_report', function(e){
            $.ajax({
                url: "draftReportAjax.php",
                type: "POST",
                data: {
                    wid: <?php echo $wid; ?>,
                    type_report_audit_report:audit_report,
                    audit_report_data:audit_report_data,
                    emphasis_of_matters:emphasis_of_matters,
                    other_matter:other_matter,
                    status:"0"
                },
                success: function (response) {
                    let text = response == 1 ? "Report Saved Successfully" : "Report Did not Saved";
                    let icon = response == 1 ? "success" : "error";
                    swal({
                        icon: icon,
                        text: text,
                    }).then(function (isConfirm) {
                        if (isConfirm) {
                            window.location.href = window.location.pathname + "?<?php echo base64_encode(md5(time())); ?>&gid=<?php echo base64_encode(md5(time())); ?>&fid=<?php echo base64_encode(md5(time())); ?>&eid=<?php echo base64_encode(md5(time())); ?>&pid=<?php echo base64_encode($prog_id); ?>&cid=<?php echo base64_encode(md5(time())); ?>&bid=<?php echo base64_encode(md5(time())); ?>&aid=<?php echo base64_encode(md5(time())); ?>&parent_id=<?php echo base64_encode($prog_parentId); ?>&zid=<?php echo base64_encode(md5(time())); ?>&yid=<?php echo base64_encode(md5(time())); ?>&wid=<?php echo base64_encode($wid); ?>&xid=<?php echo base64_encode(md5(time())); ?>";
                        }
                    });
                }
            });
        });

        $(document).on('click', '#save_audit_report_update', function(e){
            $.ajax({
                url:"draftReportAjax.php",
                type:"POST",
                data:{
                    wid: <?php echo $wid; ?>,
                    audit_report_data:$(".fr-view>").html(),
                    status:"1"
                },
                success: function(response){
                    let text = response == 1 ? "Report Saved Successfully" : "Report Did not Saved";
                    let icon = response == 1 ? "success" : "error";
                    swal({
                        icon: icon,
                        text: text,
                    }).then(function (isConfirm) {
                        if (isConfirm) {
                            window.location.href = window.location.pathname + "?<?php echo base64_encode(md5(time())); ?>&gid=<?php echo base64_encode(md5(time())); ?>&fid=<?php echo base64_encode(md5(time())); ?>&eid=<?php echo base64_encode(md5(time())); ?>&pid=<?php echo base64_encode($prog_id); ?>&cid=<?php echo base64_encode(md5(time())); ?>&bid=<?php echo base64_encode(md5(time())); ?>&aid=<?php echo base64_encode(md5(time())); ?>&parent_id=<?php echo base64_encode($prog_parentId); ?>&zid=<?php echo base64_encode(md5(time())); ?>&yid=<?php echo base64_encode(md5(time())); ?>&wid=<?php echo base64_encode($wid); ?>&xid=<?php echo base64_encode(md5(time())); ?>";
                        }
                    });
                }
            });
        });

        $(document).on('click', '#prepareSubmitDraft', function(e){
            $.ajax({
                url:"draftReportAjax.php",
                type:"POST",
                data:{
                    wid: <?php echo $wid; ?>,
                    audit_report_data:$(".fr-view>").html(),
                    status:"2"
                },
                success: function(response){
                    let text = response == 1 ? "Updated Successfully" : "Did not Updated";
                    let icon = response == 1 ? "success" : "error";
                    swal({
                        icon: icon,
                        text: text,
                    }).then(function (isConfirm) {
                        if (isConfirm) {
                            window.location.href = window.location.pathname + "?<?php echo base64_encode(md5(time())); ?>&gid=<?php echo base64_encode(md5(time())); ?>&fid=<?php echo base64_encode(md5(time())); ?>&eid=<?php echo base64_encode(md5(time())); ?>&pid=<?php echo base64_encode($prog_id); ?>&cid=<?php echo base64_encode(md5(time())); ?>&bid=<?php echo base64_encode(md5(time())); ?>&aid=<?php echo base64_encode(md5(time())); ?>&parent_id=<?php echo base64_encode($prog_parentId); ?>&zid=<?php echo base64_encode(md5(time())); ?>&yid=<?php echo base64_encode(md5(time())); ?>&wid=<?php echo base64_encode($wid); ?>&xid=<?php echo base64_encode(md5(time())); ?>";
                        }
                    });
                }
            });
        });

        $(document).on('click', '#reviewSubmitDraft', function(e){
            $.ajax({
                url:"draftReportAjax.php",
                type:"POST",
                data:{
                    wid: <?php echo $wid; ?>,
                    audit_report_data:$(".fr-view>").html(),
                    status:"3"
                },
                success: function(response){
                    let text = response == 1 ? "Updated Successfully" : "Did not Update";
                    let icon = response == 1 ? "success" : "error";
                    swal({
                        icon: icon,
                        text: text,
                    }).then(function (isConfirm) {
                        if (isConfirm) {
                            window.location.href = window.location.pathname + "?<?php echo base64_encode(md5(time())); ?>&gid=<?php echo base64_encode(md5(time())); ?>&fid=<?php echo base64_encode(md5(time())); ?>&eid=<?php echo base64_encode(md5(time())); ?>&pid=<?php echo base64_encode($prog_id); ?>&cid=<?php echo base64_encode(md5(time())); ?>&bid=<?php echo base64_encode(md5(time())); ?>&aid=<?php echo base64_encode(md5(time())); ?>&parent_id=<?php echo base64_encode($prog_parentId); ?>&zid=<?php echo base64_encode(md5(time())); ?>&yid=<?php echo base64_encode(md5(time())); ?>&wid=<?php echo base64_encode($wid); ?>&xid=<?php echo base64_encode(md5(time())); ?>";
                        }
                    });
                }
            });
        });

        $(document).on('click', '#prepareSubmitInquiryManagement', function(e){
            $.ajax({
                url:"prepareReviewAjax.php",
                type:"POST",
                data:{
                    wid: <?php echo $wid; ?>,
                    prog_id:"258",
                    status:"0"
                },
                success: function(response){
                    let text = response == 1 ? "Updated Successfully" : "Did not Update";
                    let icon = response == 1 ? "success" : "error";
                    swal({
                        icon: icon,
                        text: text,
                    }).then(function (isConfirm) {
                        if (isConfirm) {
                            window.location.href = window.location.pathname + "?<?php echo base64_encode(md5(time())); ?>&gid=<?php echo base64_encode(md5(time())); ?>&fid=<?php echo base64_encode(md5(time())); ?>&eid=<?php echo base64_encode(md5(time())); ?>&pid=<?php echo base64_encode($prog_id); ?>&cid=<?php echo base64_encode(md5(time())); ?>&bid=<?php echo base64_encode(md5(time())); ?>&aid=<?php echo base64_encode(md5(time())); ?>&parent_id=<?php echo base64_encode($prog_parentId); ?>&zid=<?php echo base64_encode(md5(time())); ?>&yid=<?php echo base64_encode(md5(time())); ?>&wid=<?php echo base64_encode($wid); ?>&xid=<?php echo base64_encode(md5(time())); ?>";
                        }
                    });
                }
            });
        });

        $(document).on('click', '#reviewSubmitInquiryManagement', function(e){
            $.ajax({
                url:"prepareReviewAjax.php",
                type:"POST",
                data:{
                    wid: <?php echo $wid; ?>,
                    prog_id:"258",
                    status:"1"
                },
                success: function(response){
                    let text = response == 1 ? "Updated Successfully" : "Did not Update";
                    let icon = response == 1 ? "success" : "error";
                    swal({
                        icon: icon,
                        text: text,
                    }).then(function (isConfirm) {
                        if (isConfirm) {
                            window.location.href = window.location.pathname + "?<?php echo base64_encode(md5(time())); ?>&gid=<?php echo base64_encode(md5(time())); ?>&fid=<?php echo base64_encode(md5(time())); ?>&eid=<?php echo base64_encode(md5(time())); ?>&pid=<?php echo base64_encode($prog_id); ?>&cid=<?php echo base64_encode(md5(time())); ?>&bid=<?php echo base64_encode(md5(time())); ?>&aid=<?php echo base64_encode(md5(time())); ?>&parent_id=<?php echo base64_encode($prog_parentId); ?>&zid=<?php echo base64_encode(md5(time())); ?>&yid=<?php echo base64_encode(md5(time())); ?>&wid=<?php echo base64_encode($wid); ?>&xid=<?php echo base64_encode(md5(time())); ?>";
                        }
                    });
                }
            });
        });

        $(document).on('click', '#prepareSubmitGoingConcern', function(e){
            $.ajax({
                url:"prepareReviewAjax.php",
                type:"POST",
                data:{
                    wid: <?php echo $wid; ?>,
                    prog_id:"8",
                    status:"0"
                },
                success: function(response){
                    let text = response == 1 ? "Updated Successfully" : "Did not Update";
                    let icon = response == 1 ? "success" : "error";
                    swal({
                        icon: icon,
                        text: text,
                    }).then(function (isConfirm) {
                        if (isConfirm) {
                            window.location.href = window.location.pathname + "?<?php echo base64_encode(md5(time())); ?>&gid=<?php echo base64_encode(md5(time())); ?>&fid=<?php echo base64_encode(md5(time())); ?>&eid=<?php echo base64_encode(md5(time())); ?>&pid=<?php echo base64_encode($prog_id); ?>&cid=<?php echo base64_encode(md5(time())); ?>&bid=<?php echo base64_encode(md5(time())); ?>&aid=<?php echo base64_encode(md5(time())); ?>&parent_id=<?php echo base64_encode($prog_parentId); ?>&zid=<?php echo base64_encode(md5(time())); ?>&yid=<?php echo base64_encode(md5(time())); ?>&wid=<?php echo base64_encode($wid); ?>&xid=<?php echo base64_encode(md5(time())); ?>";
                        }
                    });
                }
            });
        });

        $(document).on('click', '#reviewSubmitGoingConcern', function(e){
            $.ajax({
                url:"prepareReviewAjax.php",
                type:"POST",
                data:{
                    wid: <?php echo $wid; ?>,
                    prog_id:"8",
                    status:"1"
                },
                success: function(response){
                    let text = response == 1 ? "Updated Successfully" : "Did not Update";
                    let icon = response == 1 ? "success" : "error";
                    swal({
                        icon: icon,
                        text: text,
                    }).then(function (isConfirm) {
                        if (isConfirm) {
                            window.location.href = window.location.pathname + "?<?php echo base64_encode(md5(time())); ?>&gid=<?php echo base64_encode(md5(time())); ?>&fid=<?php echo base64_encode(md5(time())); ?>&eid=<?php echo base64_encode(md5(time())); ?>&pid=<?php echo base64_encode($prog_id); ?>&cid=<?php echo base64_encode(md5(time())); ?>&bid=<?php echo base64_encode(md5(time())); ?>&aid=<?php echo base64_encode(md5(time())); ?>&parent_id=<?php echo base64_encode($prog_parentId); ?>&zid=<?php echo base64_encode(md5(time())); ?>&yid=<?php echo base64_encode(md5(time())); ?>&wid=<?php echo base64_encode($wid); ?>&xid=<?php echo base64_encode(md5(time())); ?>";
                        }
                    });
                }
            });
        });
        
        function exportGoingConcern(){
            $("#addPartAARow, #addPartABRow, #addPartBARow, #addPartBBRow").hide();
            $('#goingConcernDiv').find('input[type=text]').each(function() {
                $(this).replaceWith("<span class='inputLabel'>" + this.value + "</span>");
            });
            $('#goingConcernDiv').find('input[type=date]').each(function() {
                $(this).replaceWith("<span class='inputLabel inputDate'>" + this.value + "</span>");
            });
            $('#goingConcernDiv').find('input[type=radio]').each(function() {
                if($(this).attr('checked') == null){
                    $('label[for="'+ $(this).val() +'"]').css("font-weight","normal");
                    $('label[for="'+ $(this).val() +'"]').hide();
                    $(this).hide()
                }
                else{
                    $('label[for="'+ $(this).val() +'"]').css("font-weight","bold");
                    data = $('label[for="'+ $(this).val() +'"]').html();
                    $('label[for="'+ $(this).val() +'"]').html('[ OPTION SELECTED ]'+ data + '[ OPTION SELECTED ]');
                }
            });

            // Creating a MS Doc file
            var preHtml = "<html xmlns:o='urn:schemas-microsoft-com:office:office' xmlns:w='urn:schemas-microsoft-com:office:word' xmlns='http://www.w3.org/TR/REC-html40'><head><meta charset='utf-8'><title>Export HTML To Doc</title></head><body>";
            var postHtml = "</body></html>";
            var html = preHtml+document.getElementById("goingConcernDiv").innerHTML+postHtml;
            var blob = new Blob(['\ufeff', html], {
                type: 'application/msword'
            });
            var url = 'data:application/vnd.ms-word;charset=utf-8,' + encodeURIComponent(html);
            filename = 'Going Concern.doc';
            var downloadLink = document.createElement("a");
            document.body.appendChild(downloadLink);
            if(navigator.msSaveOrOpenBlob ){
                navigator.msSaveOrOpenBlob(blob, filename);
            }
            else{
                downloadLink.href = url;
                downloadLink.download = filename;   
                downloadLink.click();
            }
            document.body.removeChild(downloadLink);

            $('#goingConcernDiv').find('.inputLabel').each(function() {
                $(this).replaceWith("<input type='text' class='form-group' value='" + $(this).html() + "'/>");
            });
            $('#goingConcernDiv').find('.inputDate').each(function() {
                $(this).replaceWith("<input type='date' class='form-group' value='" + $(this).html() + "'/>");
            });

            $('#goingConcernDiv').find('input[type=radio]').each(function() {
                $('label[for="'+ $(this).val() +'"]').css("font-weight","bold");
                if($(this).attr('checked') == null){
                    $('label[for="'+ $(this).val() +'"]').show();
                    $(this).show()
                }
                else{
                    $('label[for="'+ $(this).val() +'"]').css("font-weight","bold");
                    $('label[for="'+ $(this).val() +'"]').html(data);
                }
            });
            $("#addPartAARow, #addPartABRow, #addPartBARow, #addPartBBRow").show();
        }

        $('#addProcedureASubmit').on('click', function (e) {
            e.preventDefault();
            var procedure_name = $("#procedure_a_name").val();
            $.ajax({
                url: "goingConcernAjax.php",
                type: "POST",
                data: {
                    wid: <?php echo $wid; ?>,
                    name: procedure_name,
                    part_name: 'A',
                    status: '0'
                },
                success: function (response) {
                    if (response) {
                        swal({
                            icon: "success",
                            text: "Successfully Added",
                        }).then(function (isConfirm) {
                            if (isConfirm) {
                                location.reload();
                            }
                        });
                    } else {
                        swal({
                            icon: "error",
                            text: "Failed!",
                        }).then(function (isConfirm) {
                            if (isConfirm) {
                                location.reload();
                            }
                        });
                    }
                }
            });
        });

        $('#addProcedureBSubmit').on('click', function (e) {
            e.preventDefault();
            var procedure_name = $("#procedure_b_name").val();
            $.ajax({
                url: "goingConcernAjax.php",
                type: "POST",
                data: {
                    wid: <?php echo $wid; ?>,
                    name: procedure_name,
                    part_name: 'B',
                    status: '0'
                },
                success: function (response) {
                    if (response) {
                        swal({
                            icon: "success",
                            text: "Successfully Added",
                        }).then(function (isConfirm) {
                            if (isConfirm) {
                                location.reload();
                            }
                        });
                    } else {
                        swal({
                            icon: "error",
                            text: "Failed!",
                        }).then(function (isConfirm) {
                            if (isConfirm) {
                                location.reload();
                            }
                        });
                    }
                }
            });
        });

        $("#addPartABRow, #addPartBBRow").click(function(e){
            e.preventDefault()
        })

        $(document).on('click', '#addPartAARow', function(e){
            e.preventDefault()
            let trID = $("#addPartAATable > tbody > tr:nth-last-child(1)").attr('id');
            $("#addPartAATable > tbody").append('<tr id = "'+ ++trID+'"><td><input type="text" name="going_concern_name_title_date_a['+ trID +'][0]" class="form-group col-md-12 col-lg-12 col-sm-12"></td><td><input type="text" name="going_concern_name_title_date_a['+ trID +'][1]" class="form-group col-md-12 col-lg-12 col-sm-12"></td><td><input type="date" class="form-control" name="going_concern_name_title_date_a['+ trID +'][2]" class="form-group col-md-12 col-lg-12 col-sm-12"></td></tr>');
        });

        $(document).on('click', '#addPartBARow', function(e){
            e.preventDefault()
            let trID = $("#addPartAATable > tbody > tr:nth-last-child(1)").attr('id');
            $("#addPartBATable > tbody").append('<tr id = "'+ ++trID+'"><td><input type="text" name="going_concern_name_title_date_b['+ trID +'][0]" class="form-group col-md-12 col-lg-12 col-sm-12"></td><td><input type="text" name="going_concern_name_title_date_b['+ trID +'][1]" class="form-group col-md-12 col-lg-12 col-sm-12"></td><td><input type="date" class="form-control" name="going_concern_name_title_date_b['+ trID +'][2]" class="form-group col-md-12 col-lg-12 col-sm-12"></td></tr>');
        });

        $(document).on('click', '.editProcedure', function(e){
            let id = $(this).attr('id')
            $.ajax({
                url: 'getProcedure.php',
                type: 'POST',
                data: {
                    id: id
                },
                success: function(response){
                    $("#editProcedureModal #procedure_name").html(response);
                    $("#editProcedureModal #prodecureId").val(id)
                    new FroalaEditor('#procedure_name',{attribution:false,autofocus:true})
                    $("#editProcedureModal").modal('show')
                }
            });
        });

        $(document).on('click', '.editMisstatement', function(e){
            let id = $(this).attr('id')
            $.ajax({
                url: 'getMisstatement.php',
                type: 'POST',
                data: {
                    id: id,
                    wid: <?php echo $wid; ?>
                },
                success: function(response){
                    response = JSON.parse(response)
                    $("#edit_audit_summery_modal #editAdjustment_number").val(response['adjust_number']);
                    $("#edit_audit_summery_modal #editMissstatements_description").html(response['description']);
                    $('#edit_audit_summery_modal #editMisstatement option[value='+response['misstatements']+']').attr('selected','selected');
                    $('#edit_audit_summery_modal #editType option[value='+response['type']+']').attr('selected','selected');
                    $("#edit_audit_summery_modal #logId").val(id);

                    var x = 1;
                    
                    for(i in response['log']){
                        if( i > 0){
                            $('#editTablogic_miss').append('<tr id="addr' + x + '"></tr>');
                            $('#editTablogic_miss #addr' + x).html($('#editTablogic_miss #addr' + (i-1)).html()).find('td:first-child');
                            x++;
                        }
                        $('#editTablogic_miss tr:nth-last-child(1) #misstatements_account0 option[value="'+response['log'][i][0]+'"]').attr('selected','selected');
                        $('#editTablogic_miss tr:nth-last-child(1) #misstatements_amount0').val(response['log'][i][1]);
                    }

                    $("#edit_audit_summery_modal").modal("show");
                }
            });
        });

        //Add Row in Summery of Misstatements Edit
        $("#edit_add_row_miss").click(function() {
            // $('#editTablogic_miss #addr' + x).html($('#editTablogic_miss #addr' + a).html()).find('td:first-child');
            
            var x = $('#editTablogic_miss').find('tr').length
            
            $('#editTablogic_miss').append('<tr id="addr' + x + '"></tr>');
            $('#editTablogic_miss #addr' + x).html($('#tablogic_miss #addr0').html()).find('td:first-child');
            
        });

        //Delete Row Function for Summery of Misstatements Edit
        $("#edit_delete_row_miss").click(function() {
            var x = $('#editTablogic_miss tr:nth-last-child(1)').attr('id')
            var y = $('#editTablogic_miss tr:first-child').attr('id')
            if(x != y){
                $("#editTablogic_miss #" + x).remove();
            }
        });

        $('#edit_audit_summery_modal').on('hidden.bs.modal', function () {
            window.location.reload();
        });

        $(document).on('click', '.editInquiringManagement', function(e){
            let id = $(this).attr('id')
            $.ajax({
                url: 'getInquiringManagement.php',
                type: 'POST',
                data: {
                    id: id
                },
                success: function(response){
                    $("#editInquiringManagementModal #inquiringManagement_name").val(response);
                    $("#editInquiringManagementModal #inquiringManagementId").val(id)
                    $("#editInquiringManagementModal").modal('show')
                }
            });
        });

        $(document).on('click', '.editTextarea', function(e){
            let id = $(this).attr('id')
            $.ajax({
                url: 'getTextarea.php',
                type: 'POST',
                data: {
                    id: id
                },
                success: function(response){
                    $("#editTextareaModal #textarea_name").html(response);
                    $("#editTextareaModal #id").val(id);
                    new FroalaEditor('#textarea_name',{attribution:false,autofocus:true})
                    $("#editTextareaModal").modal('show')
                }
            });
        });

        $(document).on('click', '.editConclusion', function(e){
            let id = $(this).attr('id')
            console.log(id)
            $.ajax({
                url: 'getConclusionText.php',
                type: 'POST',
                data: {
                    id: id
                },
                success: function(response){
                    $("#editConclusionTextmodal #editConclusionText_name").val(response);
                    $("#editConclusionTextmodal #id").val(id);
                    $("#editConclusionTextmodal").modal('show')
                }
            });
        });

        $(document).on('click', '.deleteMisstatementModal', function(e){
            var id = $(this).attr('id');
            $.ajax({
                url:"misstatementDelete.php",
                type:"POST",
                data:{
                    wid: <?php echo $wid; ?>,
                    id:id
                },
                success: function(response){
                    let text = response == 1 ? "Deleted Successfully" : "Did not Delete";
                    let icon = response == 1 ? "success" : "error";
                    swal({
                        icon: icon,
                        text: text,
                    }).then(function (isConfirm) {
                        if (isConfirm) {
                            window.location.href = window.location.pathname + "?<?php echo base64_encode(md5(time())); ?>&gid=<?php echo base64_encode(md5(time())); ?>&fid=<?php echo base64_encode(md5(time())); ?>&eid=<?php echo base64_encode(md5(time())); ?>&pid=<?php echo base64_encode($prog_id); ?>&cid=<?php echo base64_encode(md5(time())); ?>&bid=<?php echo base64_encode(md5(time())); ?>&aid=<?php echo base64_encode(md5(time())); ?>&parent_id=<?php echo base64_encode($prog_parentId); ?>&zid=<?php echo base64_encode(md5(time())); ?>&yid=<?php echo base64_encode(md5(time())); ?>&wid=<?php echo base64_encode($wid); ?>&xid=<?php echo base64_encode(md5(time())); ?>";
                        }
                    });
                }
            });
        });

        $(document).on('click', '.deleteProcedureModal', function(e){
            var id = $(this).attr('id');
            $.ajax({
                url:"procedureDelete.php",
                type:"POST",
                data:{
                    wid: <?php echo $wid; ?>,
                    id:id
                },
                success: function(response){
                    let text = response == 1 ? "Deleted Successfully" : "Did not Delete";
                    let icon = response == 1 ? "success" : "error";
                    swal({
                        icon: icon,
                        text: text,
                    }).then(function (isConfirm) {
                        if (isConfirm) {
                            window.location.href = window.location.pathname + "?<?php echo base64_encode(md5(time())); ?>&gid=<?php echo base64_encode(md5(time())); ?>&fid=<?php echo base64_encode(md5(time())); ?>&eid=<?php echo base64_encode(md5(time())); ?>&pid=<?php echo base64_encode($prog_id); ?>&cid=<?php echo base64_encode(md5(time())); ?>&bid=<?php echo base64_encode(md5(time())); ?>&aid=<?php echo base64_encode(md5(time())); ?>&parent_id=<?php echo base64_encode($prog_parentId); ?>&zid=<?php echo base64_encode(md5(time())); ?>&yid=<?php echo base64_encode(md5(time())); ?>&wid=<?php echo base64_encode($wid); ?>&xid=<?php echo base64_encode(md5(time())); ?>";
                        }
                    });
                }
            });
        });

        $(document).on('click', '.deleteInquiringManagementModal', function(e){
            var id = $(this).attr('id');
            $.ajax({
                url:"inquireingManagementDelete.php",
                type:"POST",
                data:{
                    id:id,
                    wid:<?php echo $wid; ?>
                },
                success: function(response){
                    let text = response == 1 ? "Deleted Successfully" : "Did not Delete";
                    let icon = response == 1 ? "success" : "error";
                    swal({
                        icon: icon,
                        text: text,
                    }).then(function (isConfirm) {
                        if (isConfirm) {
                            window.location.href = window.location.pathname + "?<?php echo base64_encode(md5(time())); ?>&gid=<?php echo base64_encode(md5(time())); ?>&fid=<?php echo base64_encode(md5(time())); ?>&eid=<?php echo base64_encode(md5(time())); ?>&pid=<?php echo base64_encode($prog_id); ?>&cid=<?php echo base64_encode(md5(time())); ?>&bid=<?php echo base64_encode(md5(time())); ?>&aid=<?php echo base64_encode(md5(time())); ?>&parent_id=<?php echo base64_encode($prog_parentId); ?>&zid=<?php echo base64_encode(md5(time())); ?>&yid=<?php echo base64_encode(md5(time())); ?>&wid=<?php echo base64_encode($wid); ?>&xid=<?php echo base64_encode(md5(time())); ?>";
                        }
                    });
                }
            });
        });

        $(document).on('click', '#prepareSubmitAccountEstimate', function(e){
            $.ajax({
                url:"prepareReviewAjax.php",
                type:"POST",
                data:{
                    wid: <?php echo $wid; ?>,
                    prog_id:"259",
                    status:"0"
                },
                success: function(response){
                    let text = response == 1 ? "Updated Successfully" : "Did not Update";
                    let icon = response == 1 ? "success" : "error";
                    swal({
                        icon: icon,
                        text: text,
                    }).then(function (isConfirm) {
                        if (isConfirm) {
                            window.location.href = window.location.pathname + "?<?php echo base64_encode(md5(time())); ?>&gid=<?php echo base64_encode(md5(time())); ?>&fid=<?php echo base64_encode(md5(time())); ?>&eid=<?php echo base64_encode(md5(time())); ?>&pid=<?php echo base64_encode($prog_id); ?>&cid=<?php echo base64_encode(md5(time())); ?>&bid=<?php echo base64_encode(md5(time())); ?>&aid=<?php echo base64_encode(md5(time())); ?>&parent_id=<?php echo base64_encode($prog_parentId); ?>&zid=<?php echo base64_encode(md5(time())); ?>&yid=<?php echo base64_encode(md5(time())); ?>&wid=<?php echo base64_encode($wid); ?>&xid=<?php echo base64_encode(md5(time())); ?>";
                        }
                    });
                }
            });
        });
        
        $(document).on('click', '#reviewSubmitAccountEstimate', function(e){
            $.ajax({
                url:"prepareReviewAjax.php",
                type:"POST",
                data:{
                    wid: <?php echo $wid; ?>,
                    prog_id:"259",
                    status:"1"
                },
                success: function(response){
                    let text = response == 1 ? "Updated Successfully" : "Did not Update";
                    let icon = response == 1 ? "success" : "error";
                    swal({
                        icon: icon,
                        text: text,
                    }).then(function (isConfirm) {
                        if (isConfirm) {
                            window.location.href = window.location.pathname + "?<?php echo base64_encode(md5(time())); ?>&gid=<?php echo base64_encode(md5(time())); ?>&fid=<?php echo base64_encode(md5(time())); ?>&eid=<?php echo base64_encode(md5(time())); ?>&pid=<?php echo base64_encode($prog_id); ?>&cid=<?php echo base64_encode(md5(time())); ?>&bid=<?php echo base64_encode(md5(time())); ?>&aid=<?php echo base64_encode(md5(time())); ?>&parent_id=<?php echo base64_encode($prog_parentId); ?>&zid=<?php echo base64_encode(md5(time())); ?>&yid=<?php echo base64_encode(md5(time())); ?>&wid=<?php echo base64_encode($wid); ?>&xid=<?php echo base64_encode(md5(time())); ?>";
                        }
                    });
                }
            });
        });

        $(document).on('click', '#prepareSubmitAuditSummery', function(e){
            $.ajax({
                url:"prepareReviewAjax.php",
                type:"POST",
                data:{
                    wid: <?php echo $wid; ?>,
                    prog_id:"24",
                    status:"0"
                },
                success: function(response){
                    let text = response == "1" ? "Updated Successfully" : "Did not Update";
                    let icon = response == "1" ? "success" : "error";
                    swal({
                        icon: icon,
                        text: text,
                    }).then(function (isConfirm) {
                        if (isConfirm) {
                            window.location.href = window.location.pathname + "?<?php echo base64_encode(md5(time())); ?>&gid=<?php echo base64_encode(md5(time())); ?>&fid=<?php echo base64_encode(md5(time())); ?>&eid=<?php echo base64_encode(md5(time())); ?>&pid=<?php echo base64_encode($prog_id); ?>&cid=<?php echo base64_encode(md5(time())); ?>&bid=<?php echo base64_encode(md5(time())); ?>&aid=<?php echo base64_encode(md5(time())); ?>&parent_id=<?php echo base64_encode($prog_parentId); ?>&zid=<?php echo base64_encode(md5(time())); ?>&yid=<?php echo base64_encode(md5(time())); ?>&wid=<?php echo base64_encode($wid); ?>&xid=<?php echo base64_encode(md5(time())); ?>";
                        }
                    });
                }
            });
        });

        $(document).on('click', '#reviewSubmitAuditSummery', function(e){
            $.ajax({
                url:"prepareReviewAjax.php",
                type:"POST",
                data:{
                    wid: <?php echo $wid; ?>,
                    prog_id:"24",
                    status:"1"
                },
                success: function(response){
                    let text = response == 1 ? "Updated Successfully" : "Did not Update";
                    let icon = response == 1 ? "success" : "error";
                    swal({
                        icon: icon,
                        text: text,
                    }).then(function (isConfirm) {
                        if (isConfirm) {
                            window.location.href = window.location.pathname + "?<?php echo base64_encode(md5(time())); ?>&gid=<?php echo base64_encode(md5(time())); ?>&fid=<?php echo base64_encode(md5(time())); ?>&eid=<?php echo base64_encode(md5(time())); ?>&pid=<?php echo base64_encode($prog_id); ?>&cid=<?php echo base64_encode(md5(time())); ?>&bid=<?php echo base64_encode(md5(time())); ?>&aid=<?php echo base64_encode(md5(time())); ?>&parent_id=<?php echo base64_encode($prog_parentId); ?>&zid=<?php echo base64_encode(md5(time())); ?>&yid=<?php echo base64_encode(md5(time())); ?>&wid=<?php echo base64_encode($wid); ?>&xid=<?php echo base64_encode(md5(time())); ?>";
                        }
                    });
                }
            });
        });

        $("#export2excel").click(function(e) {   
            window.open('data:application/vnd.ms-excel,' + encodeURIComponent($('#export_misstatements').html())); // content is the id of the DIV element  
            e.preventDefault();   
        }); 

        $("#exportEstimate").click(function(e) {   
            window.open('data:application/vnd.ms-excel,' + encodeURIComponent($('#export_Estimate_page').html())); // content is the id of the DIV element  
            e.preventDefault();   
        });

        $(document).on('click', '.deleteAccountingEstimate', function(e){
            var id = $(this).attr('id');
            $.ajax({
                url:"accountingEstimateDelete.php",
                type:"POST",
                data:{
                    id:id,
                    wid:<?php echo $wid; ?>
                },
                success: function(response){
                    let text = response == 1 ? "Deleted Successfully" : "Did not Delete";
                    let icon = response == 1 ? "success" : "error";
                    swal({
                        icon: icon,
                        text: text,
                    }).then(function (isConfirm) {
                        if (isConfirm) {
                            window.location.href = window.location.pathname + "?<?php echo base64_encode(md5(time())); ?>&gid=<?php echo base64_encode(md5(time())); ?>&fid=<?php echo base64_encode(md5(time())); ?>&eid=<?php echo base64_encode(md5(time())); ?>&pid=<?php echo base64_encode($prog_id); ?>&cid=<?php echo base64_encode(md5(time())); ?>&bid=<?php echo base64_encode(md5(time())); ?>&aid=<?php echo base64_encode(md5(time())); ?>&parent_id=<?php echo base64_encode($prog_parentId); ?>&zid=<?php echo base64_encode(md5(time())); ?>&yid=<?php echo base64_encode(md5(time())); ?>&wid=<?php echo base64_encode($wid); ?>&xid=<?php echo base64_encode(md5(time())); ?>";
                        }
                    });
                }
            });
        });

        $(document).on('click', '#reviewSubmitEstimate', function(e){
            $.ajax({
                url:"prepareReviewAjax.php",
                type:"POST",
                data:{
                    wid: <?php echo $wid; ?>,
                    prog_id:"259",
                    status:"1"
                },
                success: function(response){
                    let text = response == 1 ? "Updated Successfully" : "Did not Update";
                    let icon = response == 1 ? "success" : "error";
                    swal({
                        icon: icon,
                        text: text,
                    }).then(function (isConfirm) {
                        if (isConfirm) {
                            window.location.href = window.location.pathname + "?<?php echo base64_encode(md5(time())); ?>&gid=<?php echo base64_encode(md5(time())); ?>&fid=<?php echo base64_encode(md5(time())); ?>&eid=<?php echo base64_encode(md5(time())); ?>&pid=<?php echo base64_encode($prog_id); ?>&cid=<?php echo base64_encode(md5(time())); ?>&bid=<?php echo base64_encode(md5(time())); ?>&aid=<?php echo base64_encode(md5(time())); ?>&parent_id=<?php echo base64_encode($prog_parentId); ?>&zid=<?php echo base64_encode(md5(time())); ?>&yid=<?php echo base64_encode(md5(time())); ?>&wid=<?php echo base64_encode($wid); ?>&xid=<?php echo base64_encode(md5(time())); ?>";
                        }
                    });
                }
            });
        });

        $(document).on('click', '#prepareSubmitEstimate', function(e){
            $.ajax({
                url:"prepareReviewAjax.php",
                type:"POST",
                data:{
                    wid: <?php echo $wid; ?>,
                    prog_id:"259",
                    status:"0"
                },
                success: function(response){
                    let text = response == "1" ? "Updated Successfully" : "Did not Update";
                    let icon = response == "1" ? "success" : "error";
                    swal({
                        icon: icon,
                        text: text,
                    }).then(function (isConfirm) {
                        if (isConfirm) {
                            window.location.href = window.location.pathname + "?<?php echo base64_encode(md5(time())); ?>&gid=<?php echo base64_encode(md5(time())); ?>&fid=<?php echo base64_encode(md5(time())); ?>&eid=<?php echo base64_encode(md5(time())); ?>&pid=<?php echo base64_encode($prog_id); ?>&cid=<?php echo base64_encode(md5(time())); ?>&bid=<?php echo base64_encode(md5(time())); ?>&aid=<?php echo base64_encode(md5(time())); ?>&parent_id=<?php echo base64_encode($prog_parentId); ?>&zid=<?php echo base64_encode(md5(time())); ?>&yid=<?php echo base64_encode(md5(time())); ?>&wid=<?php echo base64_encode($wid); ?>&xid=<?php echo base64_encode(md5(time())); ?>";
                        }
                    });
                }
            });
        });

        $("#trialBalanceForm").submit(function(e){
            e.preventDefault();
            let form = $('#trialBalanceForm')[0];
            let data = new FormData(form);
            $.ajax({
                url: "excelUploadAjax.php",
                enctype: 'multipart/form-data',
                type: "POST",
                processData: false,
                contentType: false,
                cache: false,
                data: data,
                success: function(response){
                    $("#addExcelModal").modal("hide");
                    $("#trialBalanceResponseText").html(response);
                    $("#trialBalanceResponseModal").modal("show");
                }
            });
        });

        $('#financialStatementChangeSequenceForm').submit(function(e){
            e.preventDefault();
            let form = $('#financialStatementChangeSequenceForm')[0];
            let data = new FormData(form);
            $.ajax({
                url: 'updateAccountTypeSequenceAjax.php',
                type: 'POST',
                processData: false,
                contentType: false,
                cache: false,
                data: data,
                success: function(response){
                    $("#financialStatementChangeSequenceModal").modal("hide");
                    response = JSON.parse(response);
                    swal({
                        icon: response['status'] == 1? 'success':'error',
                        text: response['text'],
                    }).then(function (isConfirm) {
                        if (isConfirm) {
                            window.location.href = window.location.pathname + "?<?php echo base64_encode(md5(time())); ?>&gid=<?php echo base64_encode(md5(time())); ?>&fid=<?php echo base64_encode(md5(time())); ?>&eid=<?php echo base64_encode(md5(time())); ?>&pid=<?php echo base64_encode($prog_id); ?>&cid=<?php echo base64_encode(md5(time())); ?>&bid=<?php echo base64_encode(md5(time())); ?>&aid=<?php echo base64_encode(md5(time())); ?>&parent_id=<?php echo base64_encode($prog_parentId); ?>&zid=<?php echo base64_encode(md5(time())); ?>&yid=<?php echo base64_encode(md5(time())); ?>&wid=<?php echo base64_encode($wid); ?>&xid=<?php echo base64_encode(md5(time())); ?>";
                        }
                    });
                }
            });
        });

    </script>

    <!-- Help Section -->
    <script type="text/javascript">
        $(document).ready(function() {

            document.getElementsByTagName("html")[0].style.visibility = "visible";

            $(".helpDesign, #helpDescription").hide();
        
            $("#helpDescription > div > div > .close").click(function(e){
                $(".helpDesign, #helpDescription").toggle();
            });
            <?php
                if($prog_id == 245){
                    ?>
                        $("#helpButton").click(function(e){
                            $(".helpDesign, #helpDescription").toggle();
                            $("#help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8").hide();
                            $("#help_1").show();
                            $(".help_1").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                            $(".help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                        });
                        $(".help_1").click(function(e){
                            $(".help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                            $(".help_1").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                            $("#help_1").show();
                            $("#help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8").hide();
                        });

                        $(".help_2").click(function(e){
                            $(".help_1, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                            $(".help_2").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                            $("#help_2").show();
                            $("#help_1, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8").hide();
                        });

                        $(".help_3").click(function(e){
                            $(".help_1, .help_2, .help_4, .help_5, .help_6, .help_7, .help_8").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                            $(".help_3").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                            $("#help_3").show();
                            $("#help_1, #help_2, #help_4, #help_5, #help_6, #help_7, #help_8").hide();
                        });

                        $(".help_4").click(function(e){
                            $(".help_1, .help_2, .help_3, .help_5, .help_6, .help_7, .help_8").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                            $(".help_4").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                            $("#help_4").show();
                            $("#help_1, #help_2, #help_3, #help_5, #help_6, #help_7, #help_8").hide();
                        });

                        $(".help_5").click(function(e){
                            $(".help_1, .help_2, .help_3, .help_4, .help_6, .help_7, .help_8").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                            $(".help_5").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                            $("#help_5").show();
                            $("#help_1, #help_2, #help_3, #help_4, #help_6, #help_7, #help_8").hide();
                        });
                        
                        $(".help_6").click(function(e){
                            $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_7, .help_8").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                            $(".help_6").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                            $("#help_6").show();
                            $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_7, #help_8").hide();
                        });

                        $(".help_7").click(function(e){
                            $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6, .help_8").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                            $(".help_7").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                            $("#help_7").show();
                            $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6, #help_8").hide();
                        });

                        $(".help_8").click(function(e){
                            $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6, .help_7").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                            $(".help_8").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                            $("#help_8").show();
                            $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6, #help_7").hide();
                        });

                        $("#right-arrow").click(function(e){
                            if($(".help_1").hasClass("helpDesignSelected")){
                                $(".help_1, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8, .help_9").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                                $(".help_2").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                                $("#help_2").show();
                                $("#help_1, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8, #help_9").hide();
                            }
                            else if($(".help_2").hasClass("helpDesignSelected")){
                                $(".help_1, .help_2, .help_4, .help_5, .help_6, .help_7, .help_8, .help_9").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                                $(".help_3").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                                $("#help_3").show();
                                $("#help_1, #help_2, #help_4, #help_5, #help_6, #help_7, #help_8, #help_9").hide();
                            }
                            else if($(".help_3").hasClass("helpDesignSelected")){
                                $(".help_1, .help_2, .help_3, .help_5, .help_6, .help_7, .help_8, .help_9").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                                $(".help_4").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                                $("#help_4").show();
                                $("#help_1, #help_2, #help_3, #help_5, #help_6, #help_7, #help_8, #help_9").hide();
                            }
                            else if($(".help_4").hasClass("helpDesignSelected")){
                                $(".help_1, .help_2, .help_3, .help_4, .help_6, .help_7, .help_8, .help_9").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                                $(".help_5").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                                $("#help_5").show();
                                $("#help_1, #help_2, #help_3, #help_4, #help_6, #help_7, #help_8, #help_9").hide();
                            }
                            else if($(".help_5").hasClass("helpDesignSelected")){
                                $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_7, .help_8, .help_9").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                                $(".help_6").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                                $("#help_6").show();
                                $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_7, #help_8, #help_9").hide();
                            }
                            else if($(".help_6").hasClass("helpDesignSelected")){
                                $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6, .help_8, .help_9").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                                $(".help_7").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                                $("#help_7").show();
                                $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6, #help_8, #help_9").hide();
                            }
                            else if($(".help_7").hasClass("helpDesignSelected")){
                                $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_9").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                                $(".help_8").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                                $("#help_8").show();
                                $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_9").hide();
                            }
                            else if($(".help_8").hasClass("helpDesignSelected")){
                                $(".help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                                $(".help_1").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                                $("#help_1").show();
                                $("#help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8").hide();
                            }
                        });

                        $("#left-arrow").click(function(e){
                            if($(".help_1").hasClass("helpDesignSelected")){
                                $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6, .help_7").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                                $(".help_8").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                                $("#help_8").show();
                                $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6, #help_7").hide();
                            }
                            else if($(".help_2").hasClass("helpDesignSelected")){
                                $(".help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                                $(".help_1").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                                $("#help_1").show();
                                $("#help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8").hide();
                            }
                            else if($(".help_3").hasClass("helpDesignSelected")){
                                $(".help_1, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                                $(".help_2").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                                $("#help_2").show();
                                $("#help_1, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8").hide();
                            }
                            else if($(".help_4").hasClass("helpDesignSelected")){
                                $(".help_1, .help_2, .help_4, .help_5, .help_6, .help_7, .help_8").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                                $(".help_3").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                                $("#help_3").show();
                                $("#help_1, #help_2, #help_4, #help_5, #help_6, #help_7, #help_8").hide();
                            }
                            else if($(".help_5").hasClass("helpDesignSelected")){
                                $(".help_1, .help_2, .help_3, .help_5, .help_6, .help_7, .help_8").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                                $(".help_4").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                                $("#help_4").show();
                                $("#help_1, #help_2, #help_3, #help_5, #help_6, #help_7, #help_8").hide();
                            }
                            else if($(".help_6").hasClass("helpDesignSelected")){
                                $(".help_1, .help_2, .help_3, .help_4, .help_6, .help_7, .help_8").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                                $(".help_5").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                                $("#help_5").show();
                                $("#help_1, #help_2, #help_3, #help_4, #help_6, #help_7, #help_8").hide();
                            }
                            else if($(".help_7").hasClass("helpDesignSelected")){
                                $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_7, .help_8").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                                $(".help_6").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                                $("#help_6").show();
                                $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_7, #help_8").hide();
                            }
                            else if($(".help_8").hasClass("helpDesignSelected")){
                                $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6, .help_8").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                                $(".help_7").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                                $("#help_7").show();
                                $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6, #help_8").hide();
                            }
                        });
                    <?php
                }
                elseif($prog_id == 1 || $prog_id == 254 || $prog_id == 255 || $prog_id == 256 || $prog_id == 247 || $prog_id == 266 || $prog_id == 19){
                    ?>
                    $("#helpButton").click(function(e){
                        $(".helpDesign, #helpDescription").toggle();
                        $("#help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8, #help_9").hide();
                        $("#help_1").show();
                        $(".help_1").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                        $(".help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8, .help_9").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                    });
                    $(".help_1").click(function(e){
                        $(".help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8, .help_9").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                        $(".help_1").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                        $("#help_1").show();
                        $("#help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8, #help_9").hide();
                    });

                    $(".help_2").click(function(e){
                        $(".help_1, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8, .help_9").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                        $(".help_2").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                        $("#help_2").show();
                        $("#help_1, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8, #help_9").hide();
                    });

                    $(".help_3").click(function(e){
                        $(".help_1, .help_2, .help_4, .help_5, .help_6, .help_7, .help_8, .help_9").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                        $(".help_3").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                        $("#help_3").show();
                        $("#help_1, #help_2, #help_4, #help_5, #help_6, #help_7, #help_8, #help_9").hide();
                    });

                    $(".help_4").click(function(e){
                        $(".help_1, .help_2, .help_3, .help_5, .help_6, .help_7, .help_8, .help_9").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                        $(".help_4").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                        $("#help_4").show();
                        $("#help_1, #help_2, #help_3, #help_5, #help_6, #help_7, #help_8, #help_9").hide();
                    });

                    $(".help_5").click(function(e){
                        $(".help_1, .help_2, .help_3, .help_4, .help_6, .help_7, .help_8, .help_9").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                        $(".help_5").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                        $("#help_5").show();
                        $("#help_1, #help_2, #help_3, #help_4, #help_6, #help_7, #help_8, #help_9").hide();
                    });
                    
                    $(".help_6").click(function(e){
                        $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_7, .help_8, .help_9").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                        $(".help_6").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                        $("#help_6").show();
                        $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_7, #help_8, #help_9").hide();
                    });

                    $(".help_7").click(function(e){
                        $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6, .help_8, .help_9").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                        $(".help_7").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                        $("#help_7").show();
                        $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6, #help_8, #help_9").hide();
                    });

                    $(".help_8").click(function(e){
                        $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_9").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                        $(".help_8").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                        $("#help_8").show();
                        $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_9").hide();
                    });

                    $(".help_9").click(function(e){
                        $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                        $(".help_9").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                        $("#help_9").show();
                        $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8").hide();
                    });

                    $("#right-arrow").click(function(e){
                        if($(".help_1").hasClass("helpDesignSelected")){
                            $(".help_1, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8, .help_9").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                            $(".help_2").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                            $("#help_2").show();
                            $("#help_1, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8, #help_9").hide();
                        }
                        else if($(".help_2").hasClass("helpDesignSelected")){
                            $(".help_1, .help_2, .help_4, .help_5, .help_6, .help_7, .help_8, .help_9").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                            $(".help_3").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                            $("#help_3").show();
                            $("#help_1, #help_2, #help_4, #help_5, #help_6, #help_7, #help_8, #help_9").hide();
                        }
                        else if($(".help_3").hasClass("helpDesignSelected")){
                            $(".help_1, .help_2, .help_3, .help_5, .help_6, .help_7, .help_8, .help_9").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                            $(".help_4").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                            $("#help_4").show();
                            $("#help_1, #help_2, #help_3, #help_5, #help_6, #help_7, #help_8, #help_9").hide();
                        }
                        else if($(".help_4").hasClass("helpDesignSelected")){
                            $(".help_1, .help_2, .help_3, .help_4, .help_6, .help_7, .help_8, .help_9").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                            $(".help_5").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                            $("#help_5").show();
                            $("#help_1, #help_2, #help_3, #help_4, #help_6, #help_7, #help_8, #help_9").hide();
                        }
                        else if($(".help_5").hasClass("helpDesignSelected")){
                            $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_7, .help_8, .help_9").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                            $(".help_6").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                            $("#help_6").show();
                            $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_7, #help_8, #help_9").hide();
                        }
                        else if($(".help_6").hasClass("helpDesignSelected")){
                            $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6, .help_8, .help_9").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                            $(".help_7").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                            $("#help_7").show();
                            $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6, #help_8, #help_9").hide();
                        }
                        else if($(".help_7").hasClass("helpDesignSelected")){
                            $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_9").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                            $(".help_8").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                            $("#help_8").show();
                            $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_9").hide();
                        }
                        else if($(".help_8").hasClass("helpDesignSelected")){
                            $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                            $(".help_9").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                            $("#help_9").show();
                            $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8").hide();
                        }
                        else if($(".help_9").hasClass("helpDesignSelected")){
                            $(".help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8, .help_9").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                            $(".help_1").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                            $("#help_1").show();
                            $("#help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8, #help_9").hide();
                        }

                    });

                    $("#left-arrow").click(function(e){
                        if($(".help_1").hasClass("helpDesignSelected")){
                            $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                            $(".help_9").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                            $("#help_9").show();
                            $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8").hide();
                        }
                        else if($(".help_2").hasClass("helpDesignSelected")){
                            $(".help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8, .help_9").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                            $(".help_1").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                            $("#help_1").show();
                            $("#help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8, #help_9").hide();
                        }
                        else if($(".help_3").hasClass("helpDesignSelected")){
                            $(".help_1, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8, .help_9").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                            $(".help_2").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                            $("#help_2").show();
                            $("#help_1, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8, #help_9").hide();
                        }
                        else if($(".help_4").hasClass("helpDesignSelected")){
                            $(".help_1, .help_2, .help_4, .help_5, .help_6, .help_7, .help_8, .help_9").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                            $(".help_3").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                            $("#help_3").show();
                            $("#help_1, #help_2, #help_4, #help_5, #help_6, #help_7, #help_8, #help_9").hide();
                        }
                        else if($(".help_5").hasClass("helpDesignSelected")){
                            $(".help_1, .help_2, .help_3, .help_5, .help_6, .help_7, .help_8, .help_9").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                            $(".help_4").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                            $("#help_4").show();
                            $("#help_1, #help_2, #help_3, #help_5, #help_6, #help_7, #help_8, #help_9").hide();
                        }
                        else if($(".help_6").hasClass("helpDesignSelected")){
                            $(".help_1, .help_2, .help_3, .help_4, .help_6, .help_7, .help_8, .help_9").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                            $(".help_5").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                            $("#help_5").show();
                            $("#help_1, #help_2, #help_3, #help_4, #help_6, #help_7, #help_8, #help_9").hide();
                        }
                        else if($(".help_7").hasClass("helpDesignSelected")){
                            $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_7, .help_8, .help_9").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                            $(".help_6").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                            $("#help_6").show();
                            $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_7, #help_8, #help_9").hide();
                        }
                        else if($(".help_8").hasClass("helpDesignSelected")){
                            $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6, .help_8, .help_9").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                            $(".help_7").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                            $("#help_7").show();
                            $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6, #help_8, #help_9").hide();
                        }
                        else if($(".help_9").hasClass("helpDesignSelected")){
                            $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_9").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                            $(".help_8").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                            $("#help_8").show();
                            $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_9").hide();
                        }
                    });

                    <?php
                }
                elseif($prog_id == 12 || $prog_id == 2){
                    ?>
                    $("#helpButton").click(function(e){
                        $(".helpDesign, #helpDescription").toggle();
                        $("#help_2, #help_3, #help_4, #help_5, #help_6").hide();
                        $("#help_1").show();
                        $(".help_1").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                        $(".help_2, .help_3, .help_4, .help_5, .help_6").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                    });

                    $(".help_1").click(function(e){
                        $(".help_2, .help_3, .help_4, .help_5, .help_6").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                        $(".help_1").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                        $("#help_1").show();
                        $("#help_2, #help_3, #help_4, #help_5, #help_6").hide();
                    });

                    $(".help_2").click(function(e){
                        $(".help_1, .help_3, .help_4, .help_5, .help_6").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                        $(".help_2").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                        $("#help_2").show();
                        $("#help_1, #help_3, #help_4, #help_5, #help_6").hide();
                    });

                    $(".help_3").click(function(e){
                        $(".help_1, .help_2, .help_4, .help_5, .help_6").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                        $(".help_3").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                        $("#help_3").show();
                        $("#help_1, #help_2, #help_4, #help_5, #help_6").hide();
                    });

                    $(".help_4").click(function(e){
                        $(".help_1, .help_2, .help_3, .help_5, .help_6").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                        $(".help_4").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                        $("#help_4").show();
                        $("#help_1, #help_2, #help_3, #help_5, #help_6").hide();
                    });

                    $(".help_5").click(function(e){
                        $(".help_1, .help_2, .help_3, .help_4, .help_6").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                        $(".help_5").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                        $("#help_5").show();
                        $("#help_1, #help_2, #help_3, #help_4, #help_6").hide();
                    });
                    
                    $(".help_6").click(function(e){
                        $(".help_1, .help_2, .help_3, .help_4, .help_5").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                        $(".help_6").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                        $("#help_6").show();
                        $("#help_1, #help_2, #help_3, #help_4, #help_5").hide();
                    });

                    $("#right-arrow").click(function(e){
                        if($(".help_1").hasClass("helpDesignSelected")){
                            $(".help_1, .help_3, .help_4, .help_5, .help_6").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                            $(".help_2").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                            $("#help_1, #help_3, #help_4, #help_5, #help_6").hide();
                            $("#help_2").show();
                        }
                        else if($(".help_2").hasClass("helpDesignSelected")){
                            $(".help_1, .help_2, .help_4, .help_5, .help_6").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                            $(".help_3").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                            $("#help_1, #help_2, #help_4, #help_5, #help_6").hide();
                            $("#help_3").show();
                        }
                        else if($(".help_3").hasClass("helpDesignSelected")){
                            $(".help_1, .help_2, .help_3, .help_5, .help_6").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                            $(".help_4").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                            $("#help_4").show();
                            $("#help_1, #help_2, #help_3, #help_5, #help_6").hide();
                        }
                        else if($(".help_4").hasClass("helpDesignSelected")){
                            $(".help_1, .help_2, .help_3, .help_4, .help_6").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                            $(".help_5").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                            $("#help_5").show();
                            $("#help_1, #help_2, #help_3, #help_4, #help_6").hide();
                        }
                        else if($(".help_5").hasClass("helpDesignSelected")){
                            $(".help_1, .help_2, .help_3, .help_4, .help_5").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                            $(".help_6").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                            $("#help_6").show();
                            $("#help_1, #help_2, #help_3, #help_4, #help_5").hide();
                        }
                        else if($(".help_6").hasClass("helpDesignSelected")){
                            $(".help_2, .help_3, .help_4, .help_5, .help_6").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                            $(".help_1").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                            $("#help_1").show();
                            $("#help_2, #help_3, #help_4, #help_5, #help_6").hide();
                        }
                    });

                    $("#left-arrow").click(function(e){
                        if($(".help_1").hasClass("helpDesignSelected")){
                            $(".help_1, .help_2, .help_3, .help_4, .help_5").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                            $(".help_6").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                            $("#help_1, #help_2, #help_3, #help_4, #help_5").hide();
                            $("#help_6").show();
                        }
                        else if($(".help_2").hasClass("helpDesignSelected")){
                            $(".help_2, .help_3, .help_4, .help_5, .help_6").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                            $(".help_1").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                            $("#help_1").show();
                            $("#help_2, #help_3, #help_4, #help_5, #help_6").hide();
                        }
                        else if($(".help_3").hasClass("helpDesignSelected")){
                            $(".help_1, .help_3, .help_4, .help_5, .help_6").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                            $(".help_2").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                            $("#help_2").show();
                            $("#help_1, #help_3, #help_4, #help_5, #help_6").hide();
                        }
                        else if($(".help_4").hasClass("helpDesignSelected")){
                            $(".help_1, .help_2, .help_4, .help_5, .help_6").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                            $(".help_3").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                            $("#help_3").show();
                            $("#help_1, #help_2, #help_4, #help_5, #help_6").hide();
                        }
                        else if($(".help_5").hasClass("helpDesignSelected")){
                            $(".help_1, .help_2, .help_3, .help_5, .help_6").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                            $(".help_4").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                            $("#help_4").show();
                            $("#help_1, #help_2, #help_3, #help_5, #help_6").hide();
                        }
                        else if($(".help_6").hasClass("helpDesignSelected")){
                            $(".help_1, .help_2, .help_3, .help_4, .help_6").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                            $(".help_5").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                            $("#help_5").show();
                            $("#help_1, #help_2, #help_3, #help_4, #help_6").hide();
                        }
                    });

                    <?php
                }
                elseif($prog_id == 230){
                    ?>
                    $("#helpButton").click(function(e){
                        $(".helpDesign, #helpDescription").toggle();
                        $("#help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8, #help_9, #help_10").hide();
                        $("#help_1").show();
                        $(".help_1").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                        $(".help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8, .help_9, .help_10").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                    });
                    $(".help_1").click(function(e){
                        $(".help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8, .help_9, .help_10").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                        $(".help_1").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                        $("#help_1").show();
                        $("#help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8, #help_9, #help_10").hide();
                    });

                    $(".help_2").click(function(e){
                        $(".help_1, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8, .help_9, .help_10").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                        $(".help_2").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                        $("#help_2").show();
                        $("#help_1, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8, #help_9, #help_10").hide();
                    });

                    $(".help_3").click(function(e){
                        $(".help_1, .help_2, .help_4, .help_5, .help_6, .help_7, .help_8, .help_9, .help_10").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                        $(".help_3").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                        $("#help_3").show();
                        $("#help_1, #help_2, #help_4, #help_5, #help_6, #help_7, #help_8, #help_9, #help_10").hide();
                    });

                    $(".help_4").click(function(e){
                        $(".help_1, .help_2, .help_3, .help_5, .help_6, .help_7, .help_8, .help_9, .help_10").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                        $(".help_4").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                        $("#help_4").show();
                        $("#help_1, #help_2, #help_3, #help_5, #help_6, #help_7, #help_8, #help_9, #help_10").hide();
                    });

                    $(".help_5").click(function(e){
                        $(".help_1, .help_2, .help_3, .help_4, .help_6, .help_7, .help_8, .help_9, .help_10").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                        $(".help_5").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                        $("#help_5").show();
                        $("#help_1, #help_2, #help_3, #help_4, #help_6, #help_7, #help_8, #help_9, #help_10").hide();
                    });
                    
                    $(".help_6").click(function(e){
                        $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_7, .help_8, .help_9, .help_10").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                        $(".help_6").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                        $("#help_6").show();
                        $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_7, #help_8, #help_9, #help_10").hide();
                    });

                    $(".help_7").click(function(e){
                        $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6, .help_8, .help_9, .help_10").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                        $(".help_7").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                        $("#help_7").show();
                        $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6, #help_8, #help_9, #help_10").hide();
                    });

                    $(".help_8").click(function(e){
                        $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_9, .help_10").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                        $(".help_8").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                        $("#help_8").show();
                        $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_9, #help_10").hide();
                    });

                    $(".help_9").click(function(e){
                        $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8, .help_10").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                        $(".help_9").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                        $("#help_9").show();
                        $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8, #help_10").hide();
                    });

                    $(".help_10").click(function(e){
                        $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8, .help_9").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                        $(".help_10").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                        $("#help_10").show();
                        $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8, #help_9").hide();
                    });

                    $("#right-arrow").click(function(e){
                        if($(".help_1").hasClass("helpDesignSelected")){
                            $(".help_1, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8, .help_9, .help_10").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                            $(".help_2").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                            $("#help_2").show();
                            $("#help_1, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8, #help_9, #help_10").hide();
                        }
                        else if($(".help_2").hasClass("helpDesignSelected")){
                            $(".help_1, .help_2, .help_4, .help_5, .help_6, .help_7, .help_8, .help_9, .help_10").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                            $(".help_3").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                            $("#help_3").show();
                            $("#help_1, #help_2, #help_4, #help_5, #help_6, #help_7, #help_8, #help_9, #help_10").hide();
                        }
                        else if($(".help_3").hasClass("helpDesignSelected")){
                            $(".help_1, .help_2, .help_3, .help_5, .help_6, .help_7, .help_8, .help_9, .help_10").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                            $(".help_4").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                            $("#help_4").show();
                            $("#help_1, #help_2, #help_3, #help_5, #help_6, #help_7, #help_8, #help_9, #help_10").hide();
                        }
                        else if($(".help_4").hasClass("helpDesignSelected")){
                            $(".help_1, .help_2, .help_3, .help_4, .help_6, .help_7, .help_8, .help_9, .help_10").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                            $(".help_5").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                            $("#help_5").show();
                            $("#help_1, #help_2, #help_3, #help_4, #help_6, #help_7, #help_8, #help_9, #help_10").hide();
                        }
                        else if($(".help_5").hasClass("helpDesignSelected")){
                            $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_7, .help_8, .help_9, .help_10").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                            $(".help_6").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                            $("#help_6").show();
                            $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_7, #help_8, #help_9, #help_10").hide();
                        }
                        else if($(".help_6").hasClass("helpDesignSelected")){
                            $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6, .help_8, .help_9, .help_10").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                            $(".help_7").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                            $("#help_7").show();
                            $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6, #help_8, #help_9, #help_10").hide();
                        }
                        else if($(".help_7").hasClass("helpDesignSelected")){
                            $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_9, .help_10").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                            $(".help_8").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                            $("#help_8").show();
                            $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_9, #help_10").hide();
                        }
                        else if($(".help_8").hasClass("helpDesignSelected")){
                            $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8, .help_10").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                            $(".help_9").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                            $("#help_9").show();
                            $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8, #help_10").hide();
                        }
                        else if($(".help_9").hasClass("helpDesignSelected")){
                            $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8, .help_9").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                            $(".help_10").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                            $("#help_10").show();
                            $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8, #help_9").hide();
                        }
                        else if($(".help_10").hasClass("helpDesignSelected")){
                            $(".help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8, .help_9, .help_10").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                            $(".help_1").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                            $("#help_1").show();
                            $("#help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8, #help_9, #help_10").hide();
                        }

                    });

                    $("#left-arrow").click(function(e){
                        if($(".help_1").hasClass("helpDesignSelected")){
                            $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8, .help_9").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                            $(".help_10").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                            $("#help_10").show();
                            $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8, #help_9").hide();
                        }
                        else if($(".help_2").hasClass("helpDesignSelected")){
                            $(".help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8, .help_9, .help_10").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                            $(".help_1").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                            $("#help_1").show();
                            $("#help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8, #help_9, #help_10").hide();
                        }
                        else if($(".help_3").hasClass("helpDesignSelected")){
                            $(".help_1, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8, .help_9, .help_10").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                            $(".help_2").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                            $("#help_2").show();
                            $("#help_1, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8, #help_9, #help_10").hide();
                        }
                        else if($(".help_4").hasClass("helpDesignSelected")){
                            $(".help_1, .help_2, .help_4, .help_5, .help_6, .help_7, .help_8, .help_9, .help_10").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                            $(".help_3").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                            $("#help_3").show();
                            $("#help_1, #help_2, #help_4, #help_5, #help_6, #help_7, #help_8, #help_9, #help_10").hide();
                        }
                        else if($(".help_5").hasClass("helpDesignSelected")){
                            $(".help_1, .help_2, .help_3, .help_5, .help_6, .help_7, .help_8, .help_9, .help_10").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                            $(".help_4").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                            $("#help_4").show();
                            $("#help_1, #help_2, #help_3, #help_5, #help_6, #help_7, #help_8, #help_9, #help_10").hide();
                        }
                        else if($(".help_6").hasClass("helpDesignSelected")){
                            $(".help_1, .help_2, .help_3, .help_4, .help_6, .help_7, .help_8, .help_9, .help_10").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                            $(".help_5").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                            $("#help_5").show();
                            $("#help_1, #help_2, #help_3, #help_4, #help_6, #help_7, #help_8, #help_9, #help_10").hide();
                        }
                        else if($(".help_7").hasClass("helpDesignSelected")){
                            $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_7, .help_8, .help_9, .help_10").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                            $(".help_6").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                            $("#help_6").show();
                            $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_7, #help_8, #help_9, #help_10").hide();
                        }
                        else if($(".help_8").hasClass("helpDesignSelected")){
                            $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6, .help_8, .help_9, .help_10").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                            $(".help_7").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                            $("#help_7").show();
                            $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6, #help_8, #help_9, #help_10").hide();
                        }
                        else if($(".help_9").hasClass("helpDesignSelected")){
                            $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_9, .help_10").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                            $(".help_8").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                            $("#help_8").show();
                            $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_9, #help_10").hide();
                        }
                        else if($(".help_10").hasClass("helpDesignSelected")){
                            $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8, .help_10").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                            $(".help_9").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                            $("#help_9").show();
                            $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8, #help_10").hide();
                        }
                    });

                    <?php
                }
                elseif($prog_id == 239 || $prog_id == 240){
                    ?>
                        $("#helpButton").click(function(e){
                            $(".helpDesign, #helpDescription").toggle();
                            $("#help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8, #help_9, #help_10, #help_11").hide();
                            $("#help_1").show();
                            $(".help_1").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                            $(".help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8, .help_9, .help_10, .help_11").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                        });
                        $(".help_1").click(function(e){
                            $(".help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8, .help_9, .help_10, .help_11").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                            $(".help_1").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                            $("#help_1").show();
                            $("#help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8, #help_9, #help_10, #help_11").hide();
                        });

                        $(".help_2").click(function(e){
                            $(".help_1, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8, .help_9, .help_10, .help_11").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                            $(".help_2").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                            $("#help_2").show();
                            $("#help_1, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8, #help_9, #help_10, #help_11").hide();
                        });

                        $(".help_3").click(function(e){
                            $(".help_1, .help_2, .help_4, .help_5, .help_6, .help_7, .help_8, .help_9, .help_10, .help_11").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                            $(".help_3").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                            $("#help_3").show();
                            $("#help_1, #help_2, #help_4, #help_5, #help_6, #help_7, #help_8, #help_9, #help_10, #help_11").hide();
                        });

                        $(".help_4").click(function(e){
                            $(".help_1, .help_2, .help_3, .help_5, .help_6, .help_7, .help_8, .help_9, .help_10, .help_11").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                            $(".help_4").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                            $("#help_4").show();
                            $("#help_1, #help_2, #help_3, #help_5, #help_6, #help_7, #help_8, #help_9, #help_10, #help_11").hide();
                        });

                        $(".help_5").click(function(e){
                            $(".help_1, .help_2, .help_3, .help_4, .help_6, .help_7, .help_8, .help_9, .help_10, .help_11").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                            $(".help_5").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                            $("#help_5").show();
                            $("#help_1, #help_2, #help_3, #help_4, #help_6, #help_7, #help_8, #help_9, #help_10, #help_11").hide();
                        });
                        
                        $(".help_6").click(function(e){
                            $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_7, .help_8, .help_9, .help_10, .help_11").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                            $(".help_6").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                            $("#help_6").show();
                            $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_7, #help_8, #help_9, #help_10, #help_11").hide();
                        });

                        $(".help_7").click(function(e){
                            $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6, .help_8, .help_9, .help_10, .help_11").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                            $(".help_7").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                            $("#help_7").show();
                            $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6, #help_8, #help_9, #help_10, #help_11").hide();
                        });

                        $(".help_8").click(function(e){
                            $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_9, .help_10, .help_11").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                            $(".help_8").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                            $("#help_8").show();
                            $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_9, #help_10, #help_11").hide();
                        });

                        $(".help_9").click(function(e){
                            $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8, .help_10, .help_11").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                            $(".help_9").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                            $("#help_9").show();
                            $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8, #help_10, #help_11").hide();
                        });

                        $(".help_10").click(function(e){
                            $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8, .help_9, .help_11").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                            $(".help_10").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                            $("#help_10").show();
                            $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8, #help_9, #help_11").hide();
                        });

                        $(".help_11").click(function(e){
                            $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8, .help_9, .help_10").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                            $(".help_11").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                            $("#help_11").show();
                            $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8, #help_9, #help_10").hide();
                        });

                        $("#right-arrow").click(function(e){
                            if($(".help_1").hasClass("helpDesignSelected")){
                                $(".help_1, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8, .help_9, .help_10, .help_11").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                                $(".help_2").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                                $("#help_2").show();
                                $("#help_1, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8, #help_9, #help_10, #help_11").hide();
                            }
                            else if($(".help_2").hasClass("helpDesignSelected")){
                                $(".help_1, .help_2, .help_4, .help_5, .help_6, .help_7, .help_8, .help_9, .help_10, .help_11").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                                $(".help_3").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                                $("#help_3").show();
                                $("#help_1, #help_2, #help_4, #help_5, #help_6, #help_7, #help_8, #help_9, #help_10, #help_11").hide();
                            }
                            else if($(".help_3").hasClass("helpDesignSelected")){
                                $(".help_1, .help_2, .help_3, .help_5, .help_6, .help_7, .help_8, .help_9, .help_10, .help_11").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                                $(".help_4").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                                $("#help_4").show();
                                $("#help_1, #help_2, #help_3, #help_5, #help_6, #help_7, #help_8, #help_9, #help_10, #help_11").hide();
                            }
                            else if($(".help_4").hasClass("helpDesignSelected")){
                                $(".help_1, .help_2, .help_3, .help_4, .help_6, .help_7, .help_8, .help_9, .help_10, .help_11").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                                $(".help_5").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                                $("#help_5").show();
                                $("#help_1, #help_2, #help_3, #help_4, #help_6, #help_7, #help_8, #help_9, #help_10, #help_11").hide();
                            }
                            else if($(".help_5").hasClass("helpDesignSelected")){
                                $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_7, .help_8, .help_9, .help_10, .help_11").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                                $(".help_6").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                                $("#help_6").show();
                                $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_7, #help_8, #help_9, #help_10, #help_11").hide();
                            }
                            else if($(".help_6").hasClass("helpDesignSelected")){
                                $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6, .help_8, .help_9, .help_10, .help_11").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                                $(".help_7").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                                $("#help_7").show();
                                $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6, #help_8, #help_9, #help_10, #help_11").hide();
                            }
                            else if($(".help_7").hasClass("helpDesignSelected")){
                                $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_9, .help_10, .help_11").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                                $(".help_8").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                                $("#help_8").show();
                                $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_9, #help_10, #help_11").hide();
                            }
                            else if($(".help_8").hasClass("helpDesignSelected")){
                                $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8, .help_10, .help_11").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                                $(".help_9").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                                $("#help_9").show();
                                $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8, #help_10, #help_11").hide();
                            }
                            else if($(".help_9").hasClass("helpDesignSelected")){
                                $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8, .help_9, .help_11").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                                $(".help_10").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                                $("#help_10").show();
                                $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8, #help_9, #help_11").hide();
                            }
                            else if($(".help_10").hasClass("helpDesignSelected")){
                                $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8, .help_9, .help_10").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                                $(".help_11").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                                $("#help_11").show();
                                $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8, #help_9, #help_10").hide();
                            }
                            else if($(".help_11").hasClass("helpDesignSelected")){
                                $(".help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8, .help_9, .help_10, .help_11").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                                $(".help_1").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                                $("#help_1").show();
                                $("#help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8, #help_9, #help_10, #help_11").hide();
                            }
                        });

                        $("#left-arrow").click(function(e){
                            if($(".help_1").hasClass("helpDesignSelected")){
                                $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8, .help_9, .help_10").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                                $(".help_11").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                                $("#help_11").show();
                                $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8, #help_9, #help_10").hide();
                            }
                            else if($(".help_2").hasClass("helpDesignSelected")){
                                $(".help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8, .help_9, .help_10, .help_11").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                                $(".help_1").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                                $("#help_1").show();
                                $("#help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8, #help_9, #help_10, #help_11").hide();
                            }
                            else if($(".help_3").hasClass("helpDesignSelected")){
                                $(".help_1, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8, .help_9, .help_10, .help_11").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                                $(".help_2").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                                $("#help_2").show();
                                $("#help_1, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8, #help_9, #help_10, #help_11").hide();
                            }
                            else if($(".help_4").hasClass("helpDesignSelected")){
                                $(".help_1, .help_2, .help_4, .help_5, .help_6, .help_7, .help_8, .help_9, .help_10, .help_11").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                                $(".help_3").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                                $("#help_3").show();
                                $("#help_1, #help_2, #help_4, #help_5, #help_6, #help_7, #help_8, #help_9, #help_10, #help_11").hide();
                            }
                            else if($(".help_5").hasClass("helpDesignSelected")){
                                $(".help_1, .help_2, .help_3, .help_5, .help_6, .help_7, .help_8, .help_9, .help_10, .help_11").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                                $(".help_4").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                                $("#help_4").show();
                                $("#help_1, #help_2, #help_3, #help_5, #help_6, #help_7, #help_8, #help_9, #help_10, #help_11").hide();
                            }
                            else if($(".help_6").hasClass("helpDesignSelected")){
                                $(".help_1, .help_2, .help_3, .help_4, .help_6, .help_7, .help_8, .help_9, .help_10, .help_11").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                                $(".help_5").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                                $("#help_5").show();
                                $("#help_1, #help_2, #help_3, #help_4, #help_6, #help_7, #help_8, #help_9, #help_10, #help_11").hide();
                            }
                            else if($(".help_7").hasClass("helpDesignSelected")){
                                $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_7, .help_8, .help_9, .help_10, .help_11").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                                $(".help_6").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                                $("#help_6").show();
                                $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_7, #help_8, #help_9, #help_10, #help_11").hide();
                            }
                            else if($(".help_8").hasClass("helpDesignSelected")){
                                $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6, .help_8, .help_9, .help_10, .help_11").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                                $(".help_7").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                                $("#help_7").show();
                                $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6, #help_8, #help_9, #help_10, #help_11").hide();
                            }
                            else if($(".help_9").hasClass("helpDesignSelected")){
                                $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_9, .help_10, .help_11").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                                $(".help_8").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                                $("#help_8").show();
                                $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_9, #help_10, #help_11").hide();
                            }
                            else if($(".help_10").hasClass("helpDesignSelected")){
                                $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8, .help_10, .help_11").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                                $(".help_9").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                                $("#help_9").show();
                                $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8, #help_10, #help_11").hide();
                            }
                            else if($(".help_11").hasClass("helpDesignSelected")){
                                $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8, .help_9, .help_11").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                                $(".help_10").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                                $("#help_10").show();
                                $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8, #help_9, #help_11").hide();
                            }
                        });
                    <?php
                }
                elseif($prog_id == 262){
                    ?>
                        $("#helpButton").click(function(e){
                            $(".helpDesign, #helpDescription").toggle();
                            $("#help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8, #help_9, #help_10").hide();
                            $("#help_1").show();
                            $(".help_1").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                            $(".help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8, .help_9, .help_10").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                        });
                        $(".help_1").click(function(e){
                            $(".help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8, .help_9, .help_10").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                            $(".help_1").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                            $("#help_1").show();
                            $("#help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8, #help_9, #help_10").hide();
                        });

                        $(".help_2").click(function(e){
                            $(".help_1, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8, .help_9, .help_10").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                            $(".help_2").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                            $("#help_2").show();
                            $("#help_1, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8, #help_9, #help_10").hide();
                        });

                        $(".help_3").click(function(e){
                            $(".help_1, .help_2, .help_4, .help_5, .help_6, .help_7, .help_8, .help_9, .help_10").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                            $(".help_3").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                            $("#help_3").show();
                            $("#help_1, #help_2, #help_4, #help_5, #help_6, #help_7, #help_8, #help_9, #help_10").hide();
                        });

                        $(".help_4").click(function(e){
                            $(".help_1, .help_2, .help_3, .help_5, .help_6, .help_7, .help_8, .help_9, .help_10").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                            $(".help_4").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                            $("#help_4").show();
                            $("#help_1, #help_2, #help_3, #help_5, #help_6, #help_7, #help_8, #help_9, #help_10").hide();
                        });

                        $(".help_5").click(function(e){
                            $(".help_1, .help_2, .help_3, .help_4, .help_6, .help_7, .help_8, .help_9, .help_10").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                            $(".help_5").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                            $("#help_5").show();
                            $("#help_1, #help_2, #help_3, #help_4, #help_6, #help_7, #help_8, #help_9, #help_10").hide();
                        });
                        
                        $(".help_6").click(function(e){
                            $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_7, .help_8, .help_9, .help_10").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                            $(".help_6").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                            $("#help_6").show();
                            $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_7, #help_8, #help_9, #help_10").hide();
                        });

                        $(".help_7").click(function(e){
                            $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6, .help_8, .help_9, .help_10").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                            $(".help_7").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                            $("#help_7").show();
                            $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6, #help_8, #help_9, #help_10").hide();
                        });

                        $(".help_8").click(function(e){
                            $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_9, .help_10").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                            $(".help_8").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                            $("#help_8").show();
                            $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_9, #help_10").hide();
                        });

                        $(".help_9").click(function(e){
                            $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8, .help_10").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                            $(".help_9").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                            $("#help_9").show();
                            $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8, #help_10").hide();
                        });

                        $(".help_10").click(function(e){
                            $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8, .help_9").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                            $(".help_10").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                            $("#help_10").show();
                            $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8, #help_9").hide();
                        });

                        $("#right-arrow").click(function(e){
                            if($(".help_1").hasClass("helpDesignSelected")){
                                $(".help_1, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8, .help_9, .help_10").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                                $(".help_2").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                                $("#help_2").show();
                                $("#help_1, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8, #help_9, #help_10").hide();
                            }
                            else if($(".help_2").hasClass("helpDesignSelected")){
                                $(".help_1, .help_2, .help_4, .help_5, .help_6, .help_7, .help_8, .help_9, .help_10").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                                $(".help_3").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                                $("#help_3").show();
                                $("#help_1, #help_2, #help_4, #help_5, #help_6, #help_7, #help_8, #help_9, #help_10").hide();
                            }
                            else if($(".help_3").hasClass("helpDesignSelected")){
                                $(".help_1, .help_2, .help_3, .help_5, .help_6, .help_7, .help_8, .help_9, .help_10").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                                $(".help_4").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                                $("#help_4").show();
                                $("#help_1, #help_2, #help_3, #help_5, #help_6, #help_7, #help_8, #help_9, #help_10").hide();
                            }
                            else if($(".help_4").hasClass("helpDesignSelected")){
                                $(".help_1, .help_2, .help_3, .help_4, .help_6, .help_7, .help_8, .help_9, .help_10").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                                $(".help_5").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                                $("#help_5").show();
                                $("#help_1, #help_2, #help_3, #help_4, #help_6, #help_7, #help_8, #help_9, #help_10").hide();
                            }
                            else if($(".help_5").hasClass("helpDesignSelected")){
                                $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_7, .help_8, .help_9, .help_10").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                                $(".help_6").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                                $("#help_6").show();
                                $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_7, #help_8, #help_9, #help_10").hide();
                            }
                            else if($(".help_6").hasClass("helpDesignSelected")){
                                $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6, .help_8, .help_9, .help_10").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                                $(".help_7").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                                $("#help_7").show();
                                $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6, #help_8, #help_9, #help_10").hide();
                            }
                            else if($(".help_7").hasClass("helpDesignSelected")){
                                $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_9, .help_10").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                                $(".help_8").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                                $("#help_8").show();
                                $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_9, #help_10").hide();
                            }
                            else if($(".help_8").hasClass("helpDesignSelected")){
                                $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8, .help_10").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                                $(".help_9").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                                $("#help_9").show();
                                $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8, #help_10").hide();
                            }
                            else if($(".help_9").hasClass("helpDesignSelected")){
                                $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8, .help_9").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                                $(".help_10").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                                $("#help_10").show();
                                $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8, #help_9").hide();
                            }
                            else if($(".help_10").hasClass("helpDesignSelected")){
                                $(".help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8, .help_9, .help_10").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                                $(".help_1").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                                $("#help_1").show();
                                $("#help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8, #help_9, #help_10").hide();
                            }
                        });

                        $("#left-arrow").click(function(e){
                            if($(".help_1").hasClass("helpDesignSelected")){
                                $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8, .help_9").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                                $(".help_10").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                                $("#help_10").show();
                                $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8, #help_9").hide();
                            }
                            else if($(".help_2").hasClass("helpDesignSelected")){
                                $(".help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8, .help_9, .help_10").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                                $(".help_1").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                                $("#help_1").show();
                                $("#help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8, #help_9, #help_10").hide();
                            }
                            else if($(".help_3").hasClass("helpDesignSelected")){
                                $(".help_1, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8, .help_9, .help_10").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                                $(".help_2").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                                $("#help_2").show();
                                $("#help_1, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8, #help_9, #help_10").hide();
                            }
                            else if($(".help_4").hasClass("helpDesignSelected")){
                                $(".help_1, .help_2, .help_4, .help_5, .help_6, .help_7, .help_8, .help_9, .help_10").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                                $(".help_3").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                                $("#help_3").show();
                                $("#help_1, #help_2, #help_4, #help_5, #help_6, #help_7, #help_8, #help_9, #help_10").hide();
                            }
                            else if($(".help_5").hasClass("helpDesignSelected")){
                                $(".help_1, .help_2, .help_3, .help_5, .help_6, .help_7, .help_8, .help_9, .help_10").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                                $(".help_4").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                                $("#help_4").show();
                                $("#help_1, #help_2, #help_3, #help_5, #help_6, #help_7, #help_8, #help_9, #help_10").hide();
                            }
                            else if($(".help_6").hasClass("helpDesignSelected")){
                                $(".help_1, .help_2, .help_3, .help_4, .help_6, .help_7, .help_8, .help_9, .help_10").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                                $(".help_5").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                                $("#help_5").show();
                                $("#help_1, #help_2, #help_3, #help_4, #help_6, #help_7, #help_8, #help_9, #help_10").hide();
                            }
                            else if($(".help_7").hasClass("helpDesignSelected")){
                                $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_7, .help_8, .help_9, .help_10").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                                $(".help_6").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                                $("#help_6").show();
                                $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_7, #help_8, #help_9, #help_10").hide();
                            }
                            else if($(".help_8").hasClass("helpDesignSelected")){
                                $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6, .help_8, .help_9, .help_10").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                                $(".help_7").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                                $("#help_7").show();
                                $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6, #help_8, #help_9, #help_10").hide();
                            }
                            else if($(".help_9").hasClass("helpDesignSelected")){
                                $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_9, .help_10").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                                $(".help_8").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                                $("#help_8").show();
                                $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_9, #help_10").hide();
                            }
                            else if($(".help_10").hasClass("helpDesignSelected")){
                                $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8, .help_10").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                                $(".help_9").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                                $("#help_9").show();
                                $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8, #help_10").hide();
                            }
                        });
                    <?php
                }
                else{
                    

                    ?>
            // Default Help Jquery
            // Default Help Jquery
            $("#helpButton").click(function(e){
                $(".helpDesign, #helpDescription").toggle();
                $("#help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8, #help_9, #help_10, #help_11, #help_12").hide();
                $("#help_1").show();
                $(".help_1").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                $(".help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8, .help_9, .help_10, .help_11, .help_12").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
            });
            $(".help_1").click(function(e){
                $(".help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8, .help_9, .help_10, .help_11, .help_12").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                $(".help_1").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                $("#help_1").show();
                $("#help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8, #help_9, #help_10, #help_11, #help_12").hide();
            });

            $(".help_2").click(function(e){
                $(".help_1, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8, .help_9, .help_10, .help_11, .help_12").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                $(".help_2").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                $("#help_2").show();
                $("#help_1, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8, #help_9, #help_10, #help_11, #help_12").hide();
            });

            $(".help_3").click(function(e){
                $(".help_1, .help_2, .help_4, .help_5, .help_6, .help_7, .help_8, .help_9, .help_10, .help_11, .help_12").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                $(".help_3").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                $("#help_3").show();
                $("#help_1, #help_2, #help_4, #help_5, #help_6, #help_7, #help_8, #help_9, #help_10, #help_11, #help_12").hide();
            });

            $(".help_4").click(function(e){
                $(".help_1, .help_2, .help_3, .help_5, .help_6, .help_7, .help_8, .help_9, .help_10, .help_11, .help_12").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                $(".help_4").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                $("#help_4").show();
                $("#help_1, #help_2, #help_3, #help_5, #help_6, #help_7, #help_8, #help_9, #help_10, #help_11, #help_12").hide();
            });

            $(".help_5").click(function(e){
                $(".help_1, .help_2, .help_3, .help_4, .help_6, .help_7, .help_8, .help_9, .help_10, .help_11, .help_12").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                $(".help_5").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                $("#help_5").show();
                $("#help_1, #help_2, #help_3, #help_4, #help_6, #help_7, #help_8, #help_9, #help_10, #help_11, #help_12").hide();
            });
            
            $(".help_6").click(function(e){
                $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_7, .help_8, .help_9, .help_10, .help_11, .help_12").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                $(".help_6").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                $("#help_6").show();
                $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_7, #help_8, #help_9, #help_10, #help_11, #help_12").hide();
            });

            $(".help_7").click(function(e){
                $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6, .help_8, .help_9, .help_10, .help_11, .help_12").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                $(".help_7").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                $("#help_7").show();
                $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6, #help_8, #help_9, #help_10, #help_11, #help_12").hide();
            });

            $(".help_8").click(function(e){
                $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_9, .help_10, .help_11, .help_12").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                $(".help_8").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                $("#help_8").show();
                $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_9, #help_10, #help_11, #help_12").hide();
            });

            $(".help_9").click(function(e){
                $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8, .help_10, .help_11, .help_12").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                $(".help_9").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                $("#help_9").show();
                $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8, #help_10, #help_11, #help_12").hide();
            });

            $(".help_10").click(function(e){
                $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8, .help_9, .help_11, .help_12").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                $(".help_10").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                $("#help_10").show();
                $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8, #help_9, #help_11, #help_12").hide();
            });

            $(".help_11").click(function(e){
                $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8, .help_9, .help_10, .help_12").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                $(".help_11").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                $("#help_11").show();
                $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8, #help_9, #help_10, #help_12").hide();
            });

            $(".help_12").click(function(e){
                $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8, .help_9, .help_10, .help_11").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                $(".help_12").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                $("#help_12").show();
                $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8, #help_9, #help_10, #help_11").hide();
            });

            $("#right-arrow").click(function(e){
                if($(".help_1").hasClass("helpDesignSelected")){
                    $(".help_1, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8, .help_9, .help_10, .help_11, .help_12").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                    $(".help_2").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                    $("#help_2").show();
                    $("#help_1, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8, #help_9, #help_10, #help_11, #help_12").hide();
                }
                else if($(".help_2").hasClass("helpDesignSelected")){
                    $(".help_1, .help_2, .help_4, .help_5, .help_6, .help_7, .help_8, .help_9, .help_10, .help_11, .help_12").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                    $(".help_3").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                    $("#help_3").show();
                    $("#help_1, #help_2, #help_4, #help_5, #help_6, #help_7, #help_8, #help_9, #help_10, #help_11, #help_12").hide();
                }
                else if($(".help_3").hasClass("helpDesignSelected")){
                    $(".help_1, .help_2, .help_3, .help_5, .help_6, .help_7, .help_8, .help_9, .help_10, .help_11, .help_12").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                    $(".help_4").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                    $("#help_4").show();
                    $("#help_1, #help_2, #help_3, #help_5, #help_6, #help_7, #help_8, #help_9, #help_10, #help_11, #help_12").hide();
                }
                else if($(".help_4").hasClass("helpDesignSelected")){
                    $(".help_1, .help_2, .help_3, .help_4, .help_6, .help_7, .help_8, .help_9, .help_10, .help_11, .help_12").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                    $(".help_5").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                    $("#help_5").show();
                    $("#help_1, #help_2, #help_3, #help_4, #help_6, #help_7, #help_8, #help_9, #help_10, #help_11, #help_12").hide();
                }
                else if($(".help_5").hasClass("helpDesignSelected")){
                    $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_7, .help_8, .help_9, .help_10, .help_11, .help_12").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                    $(".help_6").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                    $("#help_6").show();
                    $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_7, #help_8, #help_9, #help_10, #help_11, #help_12").hide();
                }
                else if($(".help_6").hasClass("helpDesignSelected")){
                    $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6, .help_8, .help_9, .help_10, .help_11, .help_12").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                    $(".help_7").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                    $("#help_7").show();
                    $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6, #help_8, #help_9, #help_10, #help_11, #help_12").hide();
                }
                else if($(".help_7").hasClass("helpDesignSelected")){
                    $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_9, .help_10, .help_11, .help_12").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                    $(".help_8").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                    $("#help_8").show();
                    $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_9, #help_10, #help_11, #help_12").hide();
                }
                else if($(".help_8").hasClass("helpDesignSelected")){
                    $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8, .help_10, .help_11, .help_12").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                    $(".help_9").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                    $("#help_9").show();
                    $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8, #help_10, #help_11, #help_12").hide();
                }
                else if($(".help_9").hasClass("helpDesignSelected")){
                    $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8, .help_9, .help_11, .help_12").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                    $(".help_10").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                    $("#help_10").show();
                    $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8, #help_9, #help_11, #help_12").hide();
                }
                else if($(".help_10").hasClass("helpDesignSelected")){
                    $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8, .help_9, .help_10, .help_12").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                    $(".help_11").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                    $("#help_11").show();
                    $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8, #help_9, #help_10, #help_12").hide();
                }
                else if($(".help_11").hasClass("helpDesignSelected")){
                    $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8, .help_9, .help_10, .help_11").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                    $(".help_12").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                    $("#help_12").show();
                    $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8, #help_9, #help_10, #help_11").hide();
                }
                else if($(".help_12").hasClass("helpDesignSelected")){
                    $(".help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8, .help_9, .help_10, .help_11, .help_12").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                    $(".help_1").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                    $("#help_1").show();
                    $("#help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8, #help_9, #help_10, #help_11, #help_12").hide();
                }
            });

            $("#left-arrow").click(function(e){
                if($(".help_1").hasClass("helpDesignSelected")){
                    $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8, .help_9, .help_10, .help_11").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                    $(".help_12").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                    $("#help_12").show();
                    $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8, #help_9, #help_10, #help_11").hide();
                }
                else if($(".help_2").hasClass("helpDesignSelected")){
                    $(".help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8, .help_9, .help_10, .help_11, .help_12").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                    $(".help_1").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                    $("#help_1").show();
                    $("#help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8, #help_9, #help_10, #help_11, #help_12").hide();
                }
                else if($(".help_3").hasClass("helpDesignSelected")){
                    $(".help_1, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8, .help_9, .help_10, .help_11, .help_12").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                    $(".help_2").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                    $("#help_2").show();
                    $("#help_1, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8, #help_9, #help_10, #help_11, #help_12").hide();
                }
                else if($(".help_4").hasClass("helpDesignSelected")){
                    $(".help_1, .help_2, .help_4, .help_5, .help_6, .help_7, .help_8, .help_9, .help_10, .help_11, .help_12").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                    $(".help_3").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                    $("#help_3").show();
                    $("#help_1, #help_2, #help_4, #help_5, #help_6, #help_7, #help_8, #help_9, #help_10, #help_11, #help_12").hide();
                }
                else if($(".help_5").hasClass("helpDesignSelected")){
                    $(".help_1, .help_2, .help_3, .help_5, .help_6, .help_7, .help_8, .help_9, .help_10, .help_11, .help_12").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                    $(".help_4").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                    $("#help_4").show();
                    $("#help_1, #help_2, #help_3, #help_5, #help_6, #help_7, #help_8, #help_9, #help_10, #help_11, #help_12").hide();
                }
                else if($(".help_6").hasClass("helpDesignSelected")){
                    $(".help_1, .help_2, .help_3, .help_4, .help_6, .help_7, .help_8, .help_9, .help_10, .help_11, .help_12").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                    $(".help_5").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                    $("#help_5").show();
                    $("#help_1, #help_2, #help_3, #help_4, #help_6, #help_7, #help_8, #help_9, #help_10, #help_11, #help_12").hide();
                }
                else if($(".help_7").hasClass("helpDesignSelected")){
                    $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_7, .help_8, .help_9, .help_10, .help_11, .help_12").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                    $(".help_6").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                    $("#help_6").show();
                    $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_7, #help_8, #help_9, #help_10, #help_11, #help_12").hide();
                }
                else if($(".help_8").hasClass("helpDesignSelected")){
                    $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6, .help_8, .help_9, .help_10, .help_11, .help_12").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                    $(".help_7").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                    $("#help_7").show();
                    $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6, #help_8, #help_9, #help_10, #help_11, #help_12").hide();
                }
                else if($(".help_9").hasClass("helpDesignSelected")){
                    $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_9, .help_10, .help_11, .help_12").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                    $(".help_8").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                    $("#help_8").show();
                    $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_9, #help_10, #help_11, #help_12").hide();
                }
                else if($(".help_10").hasClass("helpDesignSelected")){
                    $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8, .help_10, .help_11, .help_12").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                    $(".help_9").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                    $("#help_9").show();
                    $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8, #help_10, #help_11, #help_12").hide();
                }
                else if($(".help_11").hasClass("helpDesignSelected")){
                    $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8, .help_9, .help_11, .help_12").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                    $(".help_10").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                    $("#help_10").show();
                    $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8, #help_9, #help_11, #help_12").hide();
                }
                else if($(".help_12").hasClass("helpDesignSelected")){
                    $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6, .help_7, .help_8, .help_9, .help_10, .help_12").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                    $(".help_11").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                    $("#help_11").show();
                    $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6, #help_7, #help_8, #help_9, #help_10, #help_12").hide();
                }
            });

            <?php
                                
                }
                ?>
        
                    });
    </script>
    <?php
        }
    ?>

    <!-- File fetch for temp -->
    <script>
        $(document).on('click','.fileFetch', function(e){
            e.preventDefault();
            fileName = $(this)[0].id;
            $.ajax({
                url: "fileFetchAjax.php",
                type: "POST",
                data:{
                    file: fileName
                },
                success: function(data){
                    data = JSON.parse(data);
                    if(data['status'] == true){
                        window.location.href = data['file_location'];
                    }
                    else{
                        swal({
                            icon: 'error',
                            text: 'File not found',
                        }).then(function (isConfirm) {
                            if (isConfirm) {
                                location.reload();
                            }
                        });
                    }
                }
            });
        })
    </script>

    <script>
        var filename;
        $(document).on('click', '.fileEditDownload', function(e){
            e.preventDefault();
            fileName = $(this).attr('id');
            filename = fileName;
            $.ajax({
                url: 'checkBeforeShowingEditButtonAjax.php',
                type: 'POST',
                data: {
                    wid:'<?php echo base64_encode($wid); ?>',
                    cid:'<?php echo base64_encode($clientId); ?>',
                    file : fileName
                },
                success: function(data){
                    if(data == 1){
                        url = "qid=<?php echo base64_encode(md5($clientId)); ?>&zid=<?php echo base64_encode(md5($clientId+1)); ?>&qqid=<?php echo base64_encode(md5($clientId+2)); ?>&sid=<?php echo base64_encode(md5($clientId+3)); ?>&mid=<?php echo base64_encode(md5($clientId+4)); ?>&wid=<?php echo base64_encode($wid) ; ?>&cid=<?php echo base64_encode($clientId); ?>&file=" + fileName + "&pid=<?php echo base64_encode(md5($clientId+5)); ?>&location=<?php echo base64_encode($_SESSION['upload_file_location']); ?>&lid=<?php echo base64_encode(md5($clientId+6)); ?>&tid=<?php echo base64_encode(md5($clientId+7)); ?>"
                        $("#hasEditButtonText").empty();
                        // $("#hasEditButton").empty().append("<a class='editButtonClicked' href='editFile?" + url + "' target = '_blanc'><button class='btn btn-outline-primary'>Edit File</button></a>");
                        $("#hasEditButton").empty().append('<h5>Live Editing coming soon!</h5>');
                    }
                    else{
                        $("#hasEditButton").empty();
                        $("#hasEditButtonText").empty().append('<h5>Someone is editing please be patient.</h5>');
                    }
                    $("#fileDownloadButton").empty().append("<a href='#' class='fileFetch' id='" + fileName + "' target = '_blanc' download><button class ='btn btn-outline-success'>Download File</button></a>")
                    $("#showEditDownloadModal").modal('show');
                }
            });
        });

        $(document).on('click', '.editButtonClicked', function(e){
            e.preventDefault();
            $("#showEditDownloadModal").modal('hide');
            window.open("http://localhost/AuditSoft/editExcelLive/excel?file="+filename);
        });
    </script>

    <script>
        $(document).ajaxStart(function(){
            $('body').addClass('stop-scrolling');
            $('#loader').show();
        }).ajaxSuccess(function() {
            $('body').removeClass('stop-scrolling');
            $('#loader').hide();
        });
    </script>
</body>