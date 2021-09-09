<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include 'dbconnection.php';
include 'getProgramStatus.php';

session_start();

if (isset($_SESSION['external']) && !empty($_SESSION['external']) && $_SESSION['external'] == 1){
    $checkAccess = $con->query("select id from accounts_log where client_contact_id = ".$_SESSION['id'])->num_rows;
    if($checkAccess){
        $clientName = 1;
        $location =  base64_encode(md5($clientName)).'&gid='. base64_encode(md5($clientName)).'&fid='. base64_encode(md5($clientName)).'&eid='.base64_encode(md5($clientName)).'&cid='.base64_encode($_SESSION['external_client_id']);
        header('Location: workspace?vid='.$location);
    }
    else{
        header("Location: logout");
    }
}

if (!isset($_SESSION['email']) && empty($_SESSION['email'])) {
    header("Location: ./");
}

if (isset($_SESSION['logged_in_date']) && !empty($_SESSION['logged_in_date'])){
    $currentDate = date_create(date("Y-m-d H:i:s",strtotime(date_format(date_create("now", new DateTimeZone('Asia/Kolkata')), "Y-m-d H:i:s"))));
    $loggedInDate = date_create(date("Y-m-d H:i:s",strtotime($_SESSION['logged_in_date'])));
    $diff=date_diff($currentDate,$loggedInDate);
    if($diff->format("%a") > 1 || $diff->format("%m") > 1 || $diff->format("%y") > 1){
        header('Location: logout');
    }
}

$wid = base64_decode($_GET['wid']);
$clientName = $_SESSION['cname'];

$clientId = $_SESSION['client_id'];

if($con->query("select * from workspace where id = $wid and client_id = $clientId")->num_rows == 0){
    header('Location: login');
}
$_SESSION['workspace_id'] = $wid;
$_SESSION['upload_file_location'] = $_SESSION['file_location'].'/'.$_SESSION['workspace_id'].'/';   



$folderStatus = $con->query("select * from client_temp_folder where workspace_id = $wid");
if($folderStatus->num_rows == 0){

    function generateRandomFolderName($length = 20) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    
    // $_SESSION['tempFolderName'] = generateRandomFolderName();
    
    // Hasing technique Blow-Fish to stop Brute Force
    $salt = substr(md5(uniqid(rand(), true)), 0, 22);
    $_SESSION['tempFolderName'] = substr(str_replace("/", rand(date('Y'), time()), hash('sha256',crypt(generateRandomFolderName(), $salt))), 0, 25);

    $con->query("insert into client_temp_folder(workspace_id, folder_name) values('$wid','".$_SESSION['tempFolderName']."')");
    shell_exec('mkdir view/'.$_SESSION['tempFolderName'].'/');
    shell_exec('chmod -R 777 view/'.$_SESSION['tempFolderName'].'/');
}
else{
    $_SESSION['tempFolderName'] = $folderStatus->fetch_assoc()['folder_name'];
}

if(isset($_SESSION['external']) && $_SESSION['external'] == 1){
    header("Location: subProgram?aid=". base64_encode(md5($clientName))."&gid=".base64_encode(md5($clientName))."&fid=".base64_encode(md5($clientName))."&eid=".base64_encode(md5($clientName))."&pid=".base64_encode('247')."&cid=".base64_encode(md5($clientName))."&bid=".base64_encode(md5($clientName))."&aid=". base64_encode(md5($clientName))."&zid=". base64_encode(md5($clientName))."&yid=". base64_encode(md5($clientName))."&wid=". base64_encode($wid)."&xid=". base64_encode(md5($clientName)));
}

$_SESSION['breadcrumb'] = array();
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
    <link rel="icon" href="Icons/fav.png" type="image/gif" sizes="16x16">

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css">
    <link rel="stylesheet" href="https://cdn.lineicons.com/1.0.1/LineIcons.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:100,300,400,600&amp;display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.2/animate.min.css">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="css/custom.css" rel="stylesheet">
    <link href="css/uiux.css" rel="stylesheet" type="text/css">
    <style>
        /* html {
        	font-size: 14px;
        }
        body {
            background: #f6f9fc;
            font-family: "Open Sans", sans-serif;
            color: #525f7f;
        } */
        h2 {
            text-align: center;
            font-size: 2rem;
            font-weight: 100;
        }
        .timeline {
            display: flex;
            flex-direction: column;
            width: 50vw;
            margin: 5% auto;
            margin-left: 29%;
        }
        .timeline__event {
            margin-bottom: 20px;
            position: relative;
            display: flex;
            margin: 20px 0;
            border-radius: 8px;
            height: 60px;
        }
        .timeline__event__title {
            font-size: 1.2rem;
            line-height: 1.4;
            text-transform: uppercase;
            font-weight: 600;
            color: #9251ac;
            letter-spacing: 1.5px;
        }
        .timeline__event__content {
            padding: 20px;
        }
        .timeline__event__date {
            color: #fff;
            font-size: 1.5rem;
            font-weight: 600;
            white-space: nowrap;
        }
        .timeline__event__icon {
            background: #4eb92b;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-basis: 40%;
            font-size: 2rem;
            color: #66FF66;
            padding: 20px;
        }
        .first {
            position: absolute;
            top: 50%;
            left: -70px;
            font-size: 2.5rem;
            transform: translateY(-50%);
        }
        .second {
            position: absolute;
            top: 50%;
            left: -70px;
            font-size: 2.5rem;
            transform: translateY(-50%);
        }
        .third {
            position: absolute;
            top: 50%;
            left: -79px;
            font-size: 2.5rem;
            transform: translateY(-50%);
        }
        .fourth {
            position: absolute;
            top: 50%;
            left: -75px;
            font-size: 2.5rem;
            transform: translateY(-50%);
        }
        .fifth {
            position: absolute;
            top: 50%;
            left: -80px;
            font-size: 2.5rem;
            transform: translateY(-50%);
        }
        .sixth {
            position: absolute;
            top: 50%;
            left: -73px;
            font-size: 2.5rem;
            transform: translateY(-50%);
        }
        .timeline__event__description {
            flex-basis: 60%;
        }
        .timeline__event:after {
            content: "";
            width: 2px;
            height: 100%;
            background: #66FF66;
            position: absolute;
            top: 52%;
            left: -3.5rem;
            z-index: -1;
        }
        .timeline__event:before {
            content: "";
            width: 5rem;
            height: 5rem;
            position: absolute;
            background: #4eb92b;
            border-radius: 100%;
            left: -6rem;
            top: 50%;
            transform: translateY(-50%);
            border: 2px solid #66FF66;
        }
        .timeline__event--type2:before {
            background: #254eda;
            border-color: #50BFE6;
        }
        .timeline__event--type2:after {
            background: #50BFE6;
        }
        .timeline__event--type2 .timeline__event__icon {
            background: #254eda;
            color: #50BFE6;
        }
        .timeline__event:last-child:after {
            content: none;
        }
        .workflowLink{
            font-size:15px !important;
            color: #fff;
        }
        .workflowLink:hover{
            font-size:20px !important;
            color:#fff !important;
        }
        .workflowModalContent{
            background-color: transparent !important;
            border: 1px solid #fff !important;
        }
        .workflowModalHeader{
            background-color:#fff !important;
        }
        @media (max-width: 786px) {
            .timeline__event {
                flex-direction: column;
            }
            .timeline__event__icon {
                border-radius: 4px 4px 0 0;
            }
        }
        
    </style>

</head>

<body style="overflow-y: scroll; height: 100% !important;" oncontextmenu="return false">


    <!-- SideBar -->
    <div class="sidenav">
        <div class="side-header">
            <!-- <div style="border-bottom:1px solid;"> -->
            <div>
                <img class="sidenav-icon" src="Icons/Group-1.png"/> &nbsp;
               
            </div>
        </div>
        <div class="side-footer">
            <div class="side-body">
                <div class="dash">
                    <a href="workspace?<?php echo base64_encode(md5($clientName)); ?>&gid=<?php echo base64_encode(md5($clientName)); ?>&fid=<?php echo base64_encode(md5($clientName)); ?>&eid=<?php echo base64_encode(md5($clientName)); ?>&pid=<?php echo base64_encode($clientId); ?>&cid=<?php echo base64_encode($clientId); ?>&bid=<?php echo base64_encode(md5($clientName)); ?>&aid=<?php echo base64_encode(md5($clientName)); ?>&parent_id=<?php echo base64_encode(md5($clientName)); ?>&zid=<?php echo base64_encode(md5($clientName)); ?>&yid=<?php echo base64_encode(md5($clientName)); ?>&wid=<?php echo base64_encode($wid); ?>&xid=<?php echo base64_encode(md5($clientName)); ?>"><img class="sidenav-icon" src="Icons/pie-chart.svg" style="width:24px !important; height:24px !important;"/> &nbsp;
                    Workspace
                    </a>
                </div>
                <div class="dash" style="margin-top: 1rem !important;">
                    <a href="#" data-toggle="modal" data-target="#workflowModal"><img class="sidenav-icon" src="Icons/pie-chart.svg" style="width:24px !important; height:24px !important;"/> &nbsp;
                    Quicklinks
                    </a>
                </div>
            </div>
            <div class="settings">
                <div class="settings-items-top-div">
                    <div class="settings-items d-flex justify-content-between align-items-center">
                        <a href="settings" class="text-decoration-none">
                            <img class="sidenav-icon" src="Icons/settings.svg" style="width:24px !important; height:24px !important;"/> &nbsp;Settings
                        </a>
                        <?php
                            if($_SESSION['firm_details']['plan'] != 1){
                        ?>
                        <label class="d-flex justify-content-center align-items-center mt-2"><span class="helpDesign help_6">6</span></label>
                        <?php
                            }else{
                                ?>
                                <label class="d-flex justify-content-center align-items-center mt-2"><span class="helpDesign help_5">5</span></label>
                                <?php
                            }
                        ?>
                    </div>
                    <div id="helpButton" class="settings-items">
                        <a href="#" class="text-decoration-none"><img class="sidenav-icon" src="Icons/help-circle.svg" style="width:24px !important; height:24px !important;"/> &nbsp;
                        Help</a>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <a href="logout"><button type="button" class="btn btn-primary"><i class="fas fa-sign-out-alt"></i> Logout</button></a>
                    <?php
                        if($_SESSION['firm_details']['plan'] != 1){
                    ?>
                        <label class="d-flex justify-content-center align-items-center mt-2"><span class="helpDesign help_7">7</span></label>
                    <?php
                        }else{
                            ?>
                                <label class="d-flex justify-content-center align-items-center mt-2"><span class="helpDesign help_6">6</span></label>
                            <?php
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Navbar -->
    <nav class="navbar sticky-top navbar-expand-lg navbar-mainbg border-bottom">
        <!-- Topbar Navbar -->
        <ul class="navbar-nav ml-auto">
            <?php
                if($_SESSION['firm_details']['plan'] != 1){
            ?>
            <li class="nav-item d-flex">
                <label class="d-flex justify-content-center align-items-center mt-2"><span class="helpDesign help_5">5</span></label>
                <a class="nav-link d-flex align-items-center" target="_blank" href="diagnosticReport?wid=<?php echo $wid; ?>">
                    <img class="nav-icon" src="Icons/download.jpg" style="height: 40px; width: 40px;" />&nbsp;&nbsp;
                    <span>Diagonistic Report</span>
                </a>
            </li>
            <?php
                }
            ?>
            <li class="nav-item d-flex">
                <label class="d-flex justify-content-center align-items-center mt-2"><span class="helpDesign help_2">2</span></label>
                <a class="nav-link d-flex align-items-center" href="admin/workspaceMembers?sid=<?php echo base64_encode(md5($clientName)); ?>&gid=<?php echo base64_encode(md5($clientName)); ?>&fid=<?php echo base64_encode(md5($clientName)); ?>&eid=<?php echo base64_encode(md5($clientName)); ?>&cid=<?php echo base64_encode($_SESSION['client_id']); ?>&yid=<?php echo base64_encode(md5($clientName)); ?>&bid=<?php echo base64_encode(md5($clientName)); ?>&aid=<?php echo base64_encode(md5($clientName)); ?>&zid=<?php echo base64_encode(md5($clientName)); ?>&jid=<?php echo base64_encode(md5($clientName)); ?>&wid=<?php echo base64_encode($wid); ?>&xid=<?php echo base64_encode(md5($clientName)); ?>">
                <img class="nav-icon" src="Icons/Group 4.svg"/>&nbsp;&nbsp;
                <span>Team Members</span>
                </a>
            </li>
            <li class="nav-item d-flex">
            <label class="d-flex justify-content-center align-items-center mt-2"><span class="helpDesign help_3">3</span></label>
                <a class="nav-link d-flex align-items-center" href="admin/clientList">
                    <img class="nav-icon" src="Icons/Group 3.svg"/>&nbsp;&nbsp;
                    <span>Clients List</span>
                </a>
            </li>
            <!-- Dropdown -->
            <li class="nav-item d-flex" style="background-color: rgba(232,240,255,1); border-radius: 15px;">
                <label class="d-flex justify-content-center align-items-center mt-2"><span class="helpDesign help_4">4</span></label>
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

    <div class="mar">
        <!-- HEADER -->
        <br><div id="header">
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
            <div class="col-md-12 d-flex align-items-center justify-content-center" style="padding-bottom: 2%; border-bottom: 2px solid #e1e2e9;">
                <div class="col-md-4 d-flex align-items-center">
                    <span class="span-heading">Audit Program</span>
                    <?php
                        $query = "select program.id id from program inner join workspace_log on program.id=workspace_log.program_id where program.parent_id='0' and workspace_log.workspace_id='$wid' and workspace_log.active = 1 order by _seq";
                        $exquery = $con->query($query);
                        $totalProgramCount = $statusProgramCount = 0;
                        if ($exquery->num_rows != 0) {
                            while($row = $exquery->fetch_assoc()){
                                $data = getProgramStatus($row['id'],$wid);
                                $totalProgramCount += $data['totalCount'];
                                $statusProgramCount += $data['statusCount'];
                            }  
                        }
                        // $querys1 = $con->query("SELECT count(workspace_log.id) total FROM workspace_log inner join program on workspace_log.program_id = program.id where workspace_id = $wid and program.hasChild = 0 and status = 1 and import=1")->fetch_assoc()['total'];
                        // $querys = $con->query("SELECT count(workspace_log.id) total FROM workspace_log inner join program on workspace_log.program_id = program.id where workspace_id = $wid and program.hasChild = 0 and import=1")->fetch_assoc()['total'];

                        // $totalCount = (int)$con->query("SELECT count(id) total from materiality where workspace_id = $wid")->fetch_assoc()['total'];
                        // $statusCount = (int)$con->query("SELECT count(id) total FROM materiality where workspace_id = $wid and ( standard_low != '' or standard_high != '' or custom != '' or amount != '' )")->fetch_assoc()['total'];

                        // $totalCount += (int)$con->query("SELECT count(id) total FROM tb_performance_map where workspace_id = $wid")->fetch_assoc()['total'];
                        // $statusCount += (int)$con->query("SELECT count(id) total FROM tb_performance_map where amount != '' and workspace_id = $wid")->fetch_assoc()['total'];

                        // $totalCount += (int)$con->query("SELECT count(id) total FROM workspace_log where ( program_id >= 35 and program_id <= 46 ) or ( program_id >= 231 and program_id <= 237 ) and workspace_id = $wid")->fetch_assoc()['total'];
                        // $statusCount += (int)$con->query("SELECT count(id) total FROM workspace_log where ( program_id >= 35 or program_id <= 46 ) and ( program_id >= 231 or program_id <= 237 ) and workspace_id = $wid and amount != ''")->fetch_assoc()['total'];

                        // $querys += $totalCount;
                        // $querys1 += $statusCount;

                        $per = round(number_format((float)0, 2, '.', ''));
                        if($statusProgramCount != 0){
                            $per = round(number_format((float)($statusProgramCount/$totalProgramCount)*100, 2, '.', ''));
                        }
                    ?>
                    <span class="color-block"><?php echo $per."%"; ?></span>
                </div>
                <div class="progress col-md-8 p-0" style="height:25px; margin:0 !important;">
                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" aria-valuenow="<?php echo $per; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $per; ?>%; color:<?php if($per == 0) echo "#000"; else echo "#fff"; ?>;"></div>
                </div>
            </div><br>
            <div class="col-md-12 d-flex" style="flex-direction:column;">
                <?php
                $query = "select program.* from program inner join workspace_log on program.id=workspace_log.program_id where program.parent_id='0' and workspace_log.workspace_id='$wid' order by _seq";
                $exquery = $con->query($query);
                if ($exquery->num_rows != 0) {
                    while ($queryrow = $exquery->fetch_assoc()) {?>
                    <label class="mt-2"><span class="helpDesign help_1">1</span></label>
                <div class="col-md-12 custom-list" style="flex-direction:row; align-items:center;">
                    <div class="col-md-12">
                        <a href="subProgram?did=<?php echo base64_encode(md5($clientName)); ?>&gid=<?php echo base64_encode(md5($clientName)); ?>&fid=<?php echo base64_encode(md5($clientName)); ?>&eid=<?php echo base64_encode(md5($clientName)); ?>&pid=<?php echo base64_encode($queryrow['id']); ?>&cid=<?php echo base64_encode(md5($clientName)); ?>&bid=<?php echo base64_encode(md5($clientName)); ?>&aid=<?php echo base64_encode(md5($clientName)); ?>&parent_id=<?php echo base64_encode($queryrow['parent_id']); ?>&zid=<?php echo base64_encode(md5($clientName)); ?>&yid=<?php echo base64_encode(md5($clientName)); ?>&wid=<?php echo base64_encode($wid); ?>&xid=<?php echo base64_encode(md5($clientName)); ?>"
                        class="custom-list-items"><b><?php echo trim($queryrow['program_name']); ?></b>
                    </a>
                        <?php
                        if($queryrow['id']==1)
                            $color='bg-primary';
                        elseif($queryrow['id']==12)
                            $color='bg-info';
                        elseif($queryrow['id']==262)
                            $color='bg-darkg';
                        elseif($queryrow['id']==2)
                            $color='bg-primary';
                        elseif($queryrow['id']==19)
                            $color='bg-violet';

                        $data = getProgramStatus($queryrow['id'],$wid);
                        
                        $per = round(number_format((float)0, 2, '.', ''));
                        if($data['statusCount'] != 0){
                            $per = round(number_format((float)($data['statusCount']/$data['totalCount'])*100, 2, '.', ''));
                        }
                        // echo "Status= ".$data['statusCount'].", Total=".$data['totalCount'];
                    ?>
                    <span class="completion <?php echo $color; ?>"><?php echo $per."%"; ?></span>
                    <div class="progress p-0">
                        <div class="progress-bar progress-bar-striped progress-bar-animated <?php echo $color; ?>" role="progressbar" style="width: <?php echo $per; ?>%; color: <?php if($per == 0) echo "#000"; else echo "#fff"; ?>;" aria-valuenow="<?php echo $per; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    </div>

                </div>
                <?php }}
                    ?>
                    <br>
            </div>
            <?php
            $status = $con->query("select status from workspace_log where workspace_id=$wid and program_id=248")->fetch_assoc()['status'];
            if($status){
                $deleteLogCount = $con->query("SELECT count(id) total from signoff_files_log where status = 1 and workspace_id = $wid")->fetch_assoc()['total'];
                if($deleteLogCount == 0){
                    ?>
                        <div class="col-md-12 d-flex justify-content-center">
                            <button id="freeze" type="button" class="btn btn-lg btn-custom d-flex align-items-center"><img class="nav-icon" src="Icons/pause-circle.svg"/> &nbsp; Freeze Workspace</button>
                        </div>
                    <?php
                }
                else{
                    ?> 
                        <center><h6>To <b>Freeze Workspace</b> all deleted files need to be either deleted permanently or it must be recovered from <b><a href="deletedFiles">Deleted File Log</a></b>.</h6></center>
                        <div class="col-md-12 d-flex justify-content-center">
                            <button type="button" class="btn btn-lg btn-custom d-flex align-items-center"><img class="nav-icon" src="Icons/pause-circle.svg"/> &nbsp; Freeze Workspace</button>
                        </div>
                    <?php
                }
            }
            ?>
        </div>

        <div id = "helpDescriptionTop" class="d-flex justify-content-center">
            <div id="helpDescription" class="col-md-11">
                <div class="card" style="border: 4px solid rgb(134, 189, 255, 0.65) !important;box-shadow: 0px 0px 20px 1px rgba(0,0,0,0.5);">
                    <div class="card-body">
                        <button type="button" class="close" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <div id="help_1">
                            <p>1. Audit Pillars : We have divided fieldwork in five pillars. You cannot add or remove any pillar .</p>
                        </div>
                        <div id="help_2">
                            <p>2. Audit members : This shows the list of members that currently have access to the particular client file.</p>
                            <p>This only reflects the members in the currently open client file.</p>
                        </div>
                        <div id="help_3">
                            <p>3. Client list: Will take you to the main page where the list of all clients allocated to you is present.</p>
                        </div>
                        <div id="help_4">
                            <p>4. Profile: User profile reflects brief details about the user and can be edited by firm administrator.</p>
                        </div>
                        <?php
                            if($_SESSION['firm_details']['plan'] != 1){
                        ?>
                            <div id="help_5">
                                <p>5. Diagnostics: Diagnostics report gives you a summary of all applicable work steps being signed off by preparer and reviewer.</p>
                                <p>Diagnostics reports give you a quick view of the number of comments and number of files within a particular workplace.</p>
                                <p>You can review this report to ensure accuracy and completeness of audit before Concluding.</p> 
                            </div>
                            <div id="help_6">
                                <p>6. Settings – Your Settings are personalized based on your role in your firm and can be always accessed for chat, email and reaching out to a specialist for any help.</p>
                            </div>
                            <div id="help_7">
                                <p>7. Log out- Simply use this button to log out of your firm workspace and client list.</p>
                            </div>
                        <?php
                            }
                            else{
                                ?>
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
                    
        <!-- Footer -->
        <footer class="sticky-footer">
            <div class="container my-auto">
                <div class="copyright text-center my-auto">
                    <span><strong><span style="color: #4eb92b;">Auditors</span><span style="color: #254eda;">Desk</span>&copy;
                    <?php echo date("Y"); ?></strong></span>
                </div>
            </div>
        </footer>

        <!-- Workflow Modal -->
        <div class="modal fade" id="workflowModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-size" role="document">
                <div class="modal-content workflowModalContent">
                    <div class="modal-header workflowModalHeader">
                        <h5 class="modal-title" id="exampleModalLabel">Workflow</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                    </div>
                    <div class="modal-body">
                        <div class="timeline">
                            <div class="timeline__event  animated fadeInUp timeline__event--type1">
                                <div class="timeline__event__icon ">
                                    <i class="far fa-file-excel first"></i>
                                    <div class="timeline__event__date">
                                        <a class="workflowLink" href="subProgram?did=<?php echo base64_encode(md5($clientName)); ?>&gid=<?php echo base64_encode(md5($clientName)); ?>&fid=<?php echo base64_encode(md5($clientName)); ?>&eid=<?php echo base64_encode(md5($clientName)); ?>&pid=<?php echo base64_encode(245); ?>&cid=<?php echo base64_encode(md5($clientName)); ?>&bid=<?php echo base64_encode(md5($clientName)); ?>&aid=<?php echo base64_encode(md5($clientName)); ?>&parent_id=<?php echo base64_encode(1); ?>&zid=<?php echo base64_encode(md5($clientName)); ?>&yid=<?php echo base64_encode(md5($clientName)); ?>&wid=<?php echo base64_encode($wid); ?>&xid=<?php echo base64_encode(md5($clientName)); ?>" style="text-decoration: none;">Trial Balance</a>
                                    </div>
                                </div>
                            </div>
                            <div class="timeline__event animated fadeInUp delay-1s timeline__event--type2">
                                <div class="timeline__event__icon">
                                    <i class="far fa-copy second"></i>
                                    <div class="timeline__event__date">
                                        <a class="workflowLink" href="subProgram?did=<?php echo base64_encode(md5($clientName)); ?>&gid=<?php echo base64_encode(md5($clientName)); ?>&fid=<?php echo base64_encode(md5($clientName)); ?>&eid=<?php echo base64_encode(md5($clientName)); ?>&pid=<?php echo base64_encode(395); ?>&cid=<?php echo base64_encode(md5($clientName)); ?>&bid=<?php echo base64_encode(md5($clientName)); ?>&aid=<?php echo base64_encode(md5($clientName)); ?>&parent_id=<?php echo base64_encode(1); ?>&zid=<?php echo base64_encode(md5($clientName)); ?>&yid=<?php echo base64_encode(md5($clientName)); ?>&wid=<?php echo base64_encode($wid); ?>&xid=<?php echo base64_encode(md5($clientName)); ?>" style="text-decoration: none;">Unaudited Financial Statement</a>
                                    </div>
                                </div>
                            </div>
                            <div class="timeline__event animated fadeInUp delay-2s timeline__event--type3">
                                <div class="timeline__event__icon">
                                    <i class="fas fa-desktop third"></i>
                                    <div class="timeline__event__date">
                                        <a class="workflowLink" href="subProgram?did=<?php echo base64_encode(md5($clientName)); ?>&gid=<?php echo base64_encode(md5($clientName)); ?>&fid=<?php echo base64_encode(md5($clientName)); ?>&eid=<?php echo base64_encode(md5($clientName)); ?>&pid=<?php echo base64_encode(2); ?>&cid=<?php echo base64_encode(md5($clientName)); ?>&bid=<?php echo base64_encode(md5($clientName)); ?>&aid=<?php echo base64_encode(md5($clientName)); ?>&parent_id=<?php echo base64_encode(0); ?>&zid=<?php echo base64_encode(md5($clientName)); ?>&yid=<?php echo base64_encode(md5($clientName)); ?>&wid=<?php echo base64_encode($wid); ?>&xid=<?php echo base64_encode(md5($clientName)); ?>" style="text-decoration: none;">Performance</a>
                                    </div>
                                </div>
                            </div>
                            <div class="timeline__event animated fadeInUp delay-3s timeline__event--type2">
                                <div class="timeline__event__icon">
                                    <i class="far fa-times-circle fourth"></i>
                                    <div class="timeline__event__date">
                                        <a class="workflowLink" href="subProgram?did=<?php echo base64_encode(md5($clientName)); ?>&gid=<?php echo base64_encode(md5($clientName)); ?>&fid=<?php echo base64_encode(md5($clientName)); ?>&eid=<?php echo base64_encode(md5($clientName)); ?>&pid=<?php echo base64_encode(24); ?>&cid=<?php echo base64_encode(md5($clientName)); ?>&bid=<?php echo base64_encode(md5($clientName)); ?>&aid=<?php echo base64_encode(md5($clientName)); ?>&parent_id=<?php echo base64_encode(19); ?>&zid=<?php echo base64_encode(md5($clientName)); ?>&yid=<?php echo base64_encode(md5($clientName)); ?>&wid=<?php echo base64_encode($wid); ?>&xid=<?php echo base64_encode(md5($clientName)); ?>" style="text-decoration: none;">Summary of Misstatements</a>
                                    </div>
                                </div>
                            </div>
                            <div class="timeline__event animated fadeInUp delay-4s timeline__event--type5">
                                <div class="timeline__event__icon">
                                    <i class="far fa-file-alt sixth"></i>
                                    <div class="timeline__event__date">
                                        <a class="workflowLink" href="subProgram?did=<?php echo base64_encode(md5($clientName)); ?>&gid=<?php echo base64_encode(md5($clientName)); ?>&fid=<?php echo base64_encode(md5($clientName)); ?>&eid=<?php echo base64_encode(md5($clientName)); ?>&pid=<?php echo base64_encode(525); ?>&cid=<?php echo base64_encode(md5($clientName)); ?>&bid=<?php echo base64_encode(md5($clientName)); ?>&aid=<?php echo base64_encode(md5($clientName)); ?>&parent_id=<?php echo base64_encode(19); ?>&zid=<?php echo base64_encode(md5($clientName)); ?>&yid=<?php echo base64_encode(md5($clientName)); ?>&wid=<?php echo base64_encode($wid); ?>&xid=<?php echo base64_encode(md5($clientName)); ?>" style="text-decoration: none;">Audited vs Unaudited</a>
                                    </div>
                                </div>
                            </div>
                            <div class="timeline__event animated fadeInUp delay-5s  timeline__event--type2">
                                <div class="timeline__event__icon">
                                    <i class="fas fa-copy sixth"></i>
                                    <div class="timeline__event__date">
                                        <a class="workflowLink" href="subProgram?did=<?php echo base64_encode(md5($clientName)); ?>&gid=<?php echo base64_encode(md5($clientName)); ?>&fid=<?php echo base64_encode(md5($clientName)); ?>&eid=<?php echo base64_encode(md5($clientName)); ?>&pid=<?php echo base64_encode(526); ?>&cid=<?php echo base64_encode(md5($clientName)); ?>&bid=<?php echo base64_encode(md5($clientName)); ?>&aid=<?php echo base64_encode(md5($clientName)); ?>&parent_id=<?php echo base64_encode(19); ?>&zid=<?php echo base64_encode(md5($clientName)); ?>&yid=<?php echo base64_encode(md5($clientName)); ?>&wid=<?php echo base64_encode($wid); ?>&xid=<?php echo base64_encode(md5($clientName)); ?>" style="text-decoration: none;">Audited Financial Statement</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

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


    </div>

   

    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/jstree.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>
            <!-- sweetalert cdn -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous"></script>

    <script src="js/custom.js"></script>
    <script>
    $(document).ready(function() {

        document.getElementsByTagName("html")[0].style.visibility = "visible";

        $(".helpDesign, #helpDescription").hide();

        $("#helpDescription > div > div > .close").click(function(e){
            $(".helpDesign, #helpDescription").hide();
        });
        
        $("#helpButton").click(function(e){
            $(".helpDesign, #helpDescription").toggle();
            $("#help_2, #help_3, #help_4, #help_5, #help_6, #help_7").hide();
            $("#help_1").show();
            $(".help_1").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
            $(".help_2, .help_3, .help_4, .help_5, .help_6, .help_7").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
        });

        $(".help_1").click(function(e){
            $(".help_2, .help_3, .help_4, .help_5, .help_6, .help_7").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
            $(".help_1").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
            $("#help_1").show();
            $("#help_2, #help_3, #help_4, #help_5, #help_6, #help_7").hide();
        });

        $(".help_2").click(function(e){
            $(".help_1, .help_3, .help_4, .help_5, .help_6, .help_7").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
            $(".help_2").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
            $("#help_2").show();
            $("#help_1, #help_3, #help_4, #help_5, #help_6, #help_7").hide();
        });

        $(".help_3").click(function(e){
            $(".help_1, .help_2, .help_4, .help_5, .help_6, #help_7").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
            $(".help_3").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
            $("#help_3").show();
            $("#help_1, #help_2, #help_4, #help_5, #help_6, #help_7").hide();
        });

        $(".help_4").click(function(e){
            $(".help_1, .help_2, .help_3, .help_5, .help_6, .help_7").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
            $(".help_4").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
            $("#help_4").show();
            $("#help_1, #help_2, #help_3, #help_5, #help_6, #help_7").hide();
        });

        $(".help_5").click(function(e){
            $(".help_1, .help_2, .help_3, .help_4, .help_6, .help_7").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
            $(".help_5").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
            $("#help_5").show();
            $("#help_1, #help_2, #help_3, #help_4, #help_6, #help_7").hide();
        });
        
        $(".help_6").click(function(e){
            $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_7").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
            $(".help_6").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
            $("#help_6").show();
            $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_7").hide();
        });

        <?php
            if($_SESSION['firm_details']['plan'] != 1){
        ?>
        $(".help_7").click(function(e){
            $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
            $(".help_7").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
            $("#help_7").show();
            $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6").hide();
        });
        <?php
            }
        ?>

        $("#right-arrow").click(function(e){
            if($(".help_1").hasClass("helpDesignSelected")){
                $(".help_1, .help_3, .help_4, .help_5, .help_6, .help_7").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                $(".help_2").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                $("#help_2").show();
                $("#help_1, #help_3, #help_4, #help_5, #help_6, #help_7").hide();
            }
            else if($(".help_2").hasClass("helpDesignSelected")){
                $(".help_1, .help_2, .help_4, .help_5, .help_6, #help_7").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                $(".help_3").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                $("#help_3").show();
                $("#help_1, #help_2, #help_4, #help_5, #help_6, #help_7").hide();
            }
            else if($(".help_3").hasClass("helpDesignSelected")){
                $(".help_1, .help_2, .help_3, .help_5, .help_6, .help_7").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                $(".help_4").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                $("#help_4").show();
                $("#help_1, #help_2, #help_3, #help_5, #help_6, #help_7").hide();
            }
            else if($(".help_4").hasClass("helpDesignSelected")){
                $(".help_1, .help_2, .help_3, .help_4, .help_6, .help_7").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                $(".help_5").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                $("#help_5").show();
                $("#help_1, #help_2, #help_3, #help_4, #help_6, #help_7").hide();
            }
            else if($(".help_5").hasClass("helpDesignSelected")){
                $(".help_1, .help_2, .help_3, .help_4, .help_5").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                $(".help_6").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                $("#help_6").show();
                $("#help_1, #help_2, #help_3, #help_4, #help_5").hide();
            }
            <?php
                if($_SESSION['firm_details']['plan'] != 1){
            ?>
            else if($(".help_6").hasClass("helpDesignSelected")){
                $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                $(".help_7").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                $("#help_7").show();
                $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6").hide();
            }
            else if($(".help_7").hasClass("helpDesignSelected")){
                $(".help_2, .help_3, .help_4, .help_5, .help_6, .help_7").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                $(".help_1").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                $("#help_1").show();
                $("#help_2, #help_3, #help_4, #help_5, #help_6, #help_7").hide();
            }
            <?php
                }else{
                    ?>
                    else if($(".help_6").hasClass("helpDesignSelected")){
                        $(".help_2, .help_3, .help_4, .help_5, .help_6").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                        $(".help_1").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                        $("#help_1").show();
                        $("#help_2, #help_3, #help_4, #help_5, #help_6").hide();
                    }
                    <?php
                }
            ?>
        });

        $("#left-arrow").click(function(e){
            <?php
                if($_SESSION['firm_details']['plan'] != 1){                                   
            ?>
                if($(".help_1").hasClass("helpDesignSelected")){
                    $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_6").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                    $(".help_7").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                    $("#help_7").show();
                    $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_6").hide();
                }
            <?php 
                } else {
                    ?>
                    if($(".help_1").hasClass("helpDesignSelected")){
                        $(".help_1, .help_2, .help_3, .help_4, .help_5").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                        $(".help_6").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                        $("#help_6").show();
                        $("#help_1, #help_2, #help_3, #help_4, #help_5").hide();
                    }
                    <?php
                }
            ?>
            else if($(".help_2").hasClass("helpDesignSelected")){
                $(".help_2, .help_3, .help_4, .help_5, .help_6, .help_7").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                $(".help_1").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                $("#help_1").show();
                $("#help_2, #help_3, #help_4, #help_5, #help_6, #help_7").hide();
            }
            else if($(".help_3").hasClass("helpDesignSelected")){
                $(".help_1, .help_3, .help_4, .help_5, .help_6, .help_7").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                $(".help_2").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                $("#help_2").show();
                $("#help_1, #help_3, #help_4, #help_5, #help_6, #help_7").hide();
            }
            else if($(".help_4").hasClass("helpDesignSelected")){
                $(".help_1, .help_2, .help_4, .help_5, .help_6, #help_7").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                $(".help_3").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                $("#help_3").show();
                $("#help_1, #help_2, #help_4, #help_5, #help_6, #help_7").hide();
            }
            else if($(".help_5").hasClass("helpDesignSelected")){
                $(".help_1, .help_2, .help_3, .help_5, .help_6, .help_7").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                $(".help_4").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                $("#help_4").show();
                $("#help_1, #help_2, #help_3, #help_5, #help_6, #help_7").hide();
            }
            else if($(".help_6").hasClass("helpDesignSelected")){
                $(".help_1, .help_2, .help_3, .help_4, .help_6, .help_7").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                $(".help_5").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                $("#help_5").show();
                $("#help_1, #help_2, #help_3, #help_4, #help_6, #help_7").hide();
            }
            <?php
                if($_SESSION['firm_details']['plan'] != 1){               
            ?>
            else if($(".help_7").hasClass("helpDesignSelected")){
                $(".help_1, .help_2, .help_3, .help_4, .help_5, .help_7").removeClass("helpDesignSelected").addClass("helpDesignNotSelected");
                $(".help_6").removeClass("helpDesignNotSelected").addClass("helpDesignSelected");
                $("#help_6").show();
                $("#help_1, #help_2, #help_3, #help_4, #help_5, #help_7").hide();
            }
            <?php 
            } 
            ?>
        });


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

        $(document).on('click','#freeze',function(){
            $.ajax({
                url: 'freeze.php',
                type: 'POST',
                data: {
                    id: <?php echo $wid; ?>,
                    freeze: 1
                },
                success: function(data){
                    if (data) {
                            swal({
                                icon: "success",
                                text: "Thank You for Freezing",
                                closeOnClickOutside: false,
                            }).then(function (isConfirm) {
                                if (isConfirm) {
                                    window.location.href = <?php echo "'workspace?gid=".base64_encode(md5(trim($_SESSION['client_id'])))."&xid=".base64_encode(md5(trim($_SESSION['client_id'])))."&yid=".base64_encode(md5(trim($_SESSION['client_id'])))."&zid=".base64_encode(md5(trim($_SESSION['client_id'])))."&aid=".base64_encode(md5(trim($_SESSION['client_id'])))."&sid=".base64_encode(md5(trim($_SESSION['client_id'])))."&cid=".base64_encode(trim($_SESSION['client_id']))."'"?>;
                                }
                            });
                        }
                }
            })
        });

        let darkmode = <?php echo $_SESSION['darkmode']; ?>;
        if(darkmode)
        {
            document.documentElement.classList.toggle('dark-mode');
            
        }
        else if(!darkmode){
            document.documentElement.classList.remove('dark-mode');
        }
    });

    </script>
</body>