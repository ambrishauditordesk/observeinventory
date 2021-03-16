<?php
    include 'dbconnection.php';
    include 'moneyFormatter.php';
    session_start();
    if (!isset($_SESSION['email']) && empty($_SESSION['email'])) {
        header("Location: login");
    }
    
    $clientId = $_SESSION['client_id'];
    $clientName = $con->query("select name from client where id = ".$clientId)->fetch_assoc()['name'];
    $prog_id = $_GET['pid'];
    if(isset($_GET['parent_id']))
        $prog_parentId = $_GET['parent_id'];
    $wid = $_GET['wid'];
    if(isset($_SESSION['breadcrumb']))
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"
            integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA=="
            crossorigin="anonymous"></script>
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
</head>

<body style="overflow-y: scroll;" oncontextmenu="return false">

    <!-- SideBar -->
    <div class="sidenav">
        <div class="side-header">
            <!-- <div style="border-bottom:1px solid;"> -->
            <div>
                <a href="<?php if(isset($_SESSION['external_client_id']) && $_SESSION['external_client_id'] == '') echo "admin/clientList"; else echo "workspace?cid=".$_SESSION['client_id']; ?>">
                    <img class="sidenav-icon" src="Icons/Group -1.svg"/> &nbsp;
                    Audit Edg
                </a>
            </div>
        </div>
        <div class="side-footer">
            <div class="side-body">
                <div class="dash">
                    <a href="clientDashboard?wid=<?php echo $wid;?>"><img class="sidenav-icon" src="Icons/pie-chart.svg" style="width:24px !important; height:24px !important;"/> &nbsp;
                    Workspace
                    </a>
                </div>
                <?php
                    if($_SESSION['external'] == 0){
                        $query = "select program.* from program inner join workspace_log on program.id=workspace_log.program_id where program.parent_id='0' and workspace_log.workspace_id='$wid' order by _seq";
                        $exquery = $con->query($query);
                        if ($exquery->num_rows != 0) {
                            while ($queryrow = $exquery->fetch_assoc()) {
                                if ($queryrow['hasChild'] == 1) {
                                    ?>
                                        <div class="sub-dash" id="employees" style="margin-top: 1rem !important;">
                                            <a href="subProgram.php?pid=<?php echo $queryrow['id']; ?>&parent_id=<?php echo $queryrow['parent_id']; ?>&wid=<?php echo $wid; ?>">
                                                <img class="sidenav-icon" src="Icons/Group 6.svg" style="width:1rem !important; height:1rem !important;"/> &nbsp;
                                                <?php echo trim($queryrow['program_name']); ?>
                                            </a>
                                        </div>
                                    <?php
                                }
                            }
                        }
                    }
                ?>
            </div>
            <div class="settings">
                <div class="settings-items-top-div">
                    <div class="settings-items settingsmodal">
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

    <!-- Navbar -->
    <nav class="navbar sticky-top navbar-expand-lg navbar-mainbg border-bottom">
        <!-- Topbar Navbar -->
        <ul class="navbar-nav ml-auto">
            <?php
                if ($prog_id != '2' && $prog_id != '20' && $prog_id != '230' && $prog_id != '229' && $prog_id != '12' && $prog_id != '239' && $prog_id != '240' && $prog_id != '247') {
                    ?>
                    <li class="nav-item d-flex">
                        <a class="nav-link d-flex align-items-center" href="#" data-toggle="modal"
                            data-target="#addProgModal">
                            <img class="nav-icon" src="Icons/plus-circle-1.svg" style="height:35px; width:35px;"/>&nbsp;&nbsp;
                            <span>Add Programme</span>
                        </a>
                    </li>
                <?php }
            ?>
            <?php
                if($prog_id == '239' || $prog_id == '240'){
            ?>
                <li class="nav-item d-flex">
                        <a class="nav-link d-flex align-items-center" href="#" data-toggle="modal"
                            data-target="#addbsplModal">
                            <img class="nav-icon" src="Icons/Group 5.svg"/>&nbsp;&nbsp;
                            <span>Add Account</span>
                        </a>
                    </li>
            <?php } 
            ?>
            <!-- Dropdown -->
            <li class="nav-item d-flex" style="background-color: rgba(232,240,255,1); border-radius: 15px; padding: 8px !important;">
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
                    <?php 
                        if($_SESSION['role'] == '-1'){
                    ?>
                        <a class="dropdown-item" href="admin/loginLog"><i class="fas fa-list"></i>Login Log</a>
                    <?php
                    } 
                    ?>
                </div>
            </li>
        </ul>
    </nav>
    
    <div class="mar" <?php if($prog_id == 255 || $prog_id==230 || $prog_id==239 || $prog_id==240|| $prog_id==2|| $prog_id==19){ echo "style='height: auto !important;'"; } ?> >
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
                                ?>
                                <li class="breadcrumb-item"><a
                                            href="clientDashboard.php?wid=<?php echo $wid; ?>">Dashboard</a>
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
                                            <li class="breadcrumb-item"><a
                                                        href="subProgram.php?pid=<?php echo $bread[$i]['pid']; ?>&parent_id=<?php echo $bread[$i]['parent_id']; ?>&wid=<?php echo $wid; ?>"><?php echo $bread[$i]['name']; ?></a>
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
                            // $query = "select program.id id, program.program_name, workspace_log.amount, workspace_log.type, workspace_log.risk, workspace_log.import from program inner join workspace_log on program.id=workspace_log.program_id where program.parent_id=33 and workspace_log.workspace_id='$wid' order by _seq,id asc";
                            $query = "select program.id id, _seq,assets_liabilities_check.program_name, assets_liabilities_check.header_type,workspace_log.amount, workspace_log.type, workspace_log.risk, workspace_log.import from program inner join workspace_log on program.id=workspace_log.program_id inner join assets_liabilities_check on assets_liabilities_check.id=program.id where program.parent_id=2 and workspace_log.workspace_id='$wid' order by _seq,id asc";
                            $row1 = ($con->query("select balance_asset, balance_liability from sub_materiality where workspace_id = '$wid'"))->fetch_assoc();
                            ?>
                             <div class="col-md-12 text-center p-top">
                                    <a target="_blank" href="exportAccounts?wid=<?php echo $wid; ?>&pid=239"><button class="btn bg-violet">Export</button></a>
                            </div> <hr>
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
                            <form id="balanceSheetForm" action="accountsSubmit.php?&wid=<?php echo $wid; ?>&pid=<?php echo $prog_id; ?>" method="post" enctype="multipart/form-data">
                                <table class="table table-hover">
                                    <thead>
                                    <tr class="table-secondary">
                                        <th scope="col">Asset Accounts</th>
                                        <th scope="col" hidden>Id</th>
                                        <th scope="col" hidden></th>
                                        <th scope="col">Amount</th>
                                        <th scope="col">Type</th>
                                        <th scope="col">Risk</th>
                                        <th scope="col">Import</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                        $result = $con->query($query);
                                        $i = 0;
                                        while ($row = $result->fetch_assoc()) {
                                            // if($row['amount'] != '' || $row['type'] != '' || $row['risk'] != '' || $row['import'] != ''){
                                            //     $balanceSheet0 = 1;
                                            // }
                                            ?>
                                            
                                            <?php
                                                if ($row['header_type'] == 0) { ?>
                                                    <tr id="<?php echo ++$i; ?>">
                                                        <td scope="row" style="height: 4rem !important; display: flex; align-items: center; justify-content: center"><?php echo $row['program_name']; ?></td>
                                                        <td scope="row" hidden>
                                                            <input type="hidden" name="submitData[header_type][]"
                                                                    value="<?php echo $row['header_type']; ?>">
                                                        </td>
                                                        <td scope="row" hidden>
                                                            <input type="hidden" name="submitData[id][]"
                                                                    value="<?php echo $row['id']; ?>">
                                                        </td>
                                                        <td scope="row">
                                                            <input type="number" name="submitData[amount][]"
                                                                    value="<?php echo $row['amount']; ?>" size="10" step="0.01">
                                                        </td>
                                                        <td scope="row">
                                                            <select name="submitData[type][]" class="form-control"
                                                                    required>
                                                                <option <?php if ($row['type'] == 0) echo "selected"; ?>
                                                                        value="0">Significant
                                                                </option>
                                                                <option <?php if ($row['type'] == 1) echo "selected"; ?>
                                                                        value="1">Non-Significant
                                                                </option>
                                                            </select>
                                                        </td>
                                                        <td scope="row">
                                                            <select name="submitData[risk][]" class="form-control"required>
                                                                <option <?php if ($row['risk'] == 0) echo "selected"; ?> value="0">Low</option>
                                                                <option <?php if ($row['risk'] == 1) echo "selected"; ?> value="1">Moderate</option>
                                                                <option <?php if ($row['risk'] == 2) echo "selected"; ?> value="2">High
                                                                </option>
                                                            </select>
                                                        </td>
                                                        <td scope="row">
                                                            <select name="submitData[import][]" class="form-control"
                                                                    required>
                                                                <option <?php if ($row['import'] == 0) echo "selected"; ?> value="0">No</option>
                                                                <option <?php if ($row['import'] == 1) echo "selected"; ?> value="1">Yes</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                <?php } 
                                                }
                                                ?>
                                                    <tr class="table-secondary">
                                                        <th scope="col">Liability Accounts</th>
                                                        <th scope="col" hidden>Id</th>
                                                        <th scope="col" hidden></th>
                                                        <th scope="col">Amount</th>
                                                        <th scope="col">Type</th>
                                                        <th scope="col">Risk</th>
                                                        <th scope="col">Import</th>
                                                    </tr>
                                                    <?php
                                                    $result = $con->query($query);
                                                while ($row = $result->fetch_assoc()) {
                                                    // if($row['amount'] != '' || $row['type'] != '' || $row['risk'] != '' || $row['import'] != ''){
                                                    //     $balanceSheet1 = 1;
                                                    // }
                                                    if ($row['header_type'] == 1) {
                                                        ?>
                                                    <tr id="<?php echo ++$i; ?>">
                                                        <td scope="row" style="height: 4rem !important; display: flex; align-items: center; justify-content: center"><?php echo $row['program_name']; ?></td>
                                                        <td scope="row" hidden>
                                                            <input type="hidden" name="submitData[id][]"
                                                                    value="<?php echo $row['id']; ?>">
                                                        </td>
                                                        <td scope="row" hidden>
                                                            <input type="hidden" name="submitData[header_type][]"
                                                                    value="<?php echo $row['header_type']; ?>">
                                                        </td>
                                                        <td scope="row">
                                                            <input type="number" name="submitData[amount][]"
                                                                    value="<?php echo $row['amount']; ?>" size="10" step="0.01">
                                                        </td>
                                                        <td scope="row">
                                                            <select name="submitData[type][]" class="form-control"
                                                                    required>
                                                                <option <?php if ($row['type'] == 0) echo "selected"; ?>
                                                                        value="0">Significant
                                                                </option>
                                                                <option <?php if ($row['type'] == 1) echo "selected"; ?>
                                                                        value="1">Non-Significant
                                                                </option>
                                                            </select>
                                                        </td>
                                                        <td scope="row">
                                                            <select name="submitData[risk][]" class="form-control"
                                                                    required>
                                                                <option <?php if ($row['risk'] == 0) echo "selected"; ?>
                                                                        value="0">
                                                                    Low
                                                                </option>
                                                                <option <?php if ($row['risk'] == 1) echo "selected"; ?>
                                                                        value="1">
                                                                    Moderate
                                                                </option>
                                                                <option <?php if ($row['risk'] == 2) echo "selected"; ?>
                                                                        value="2">
                                                                    High
                                                                </option>
                                                            </select>
                                                        </td>
                                                        <td scope="row">
                                                            <select name="submitData[import][]" class="form-control"
                                                                    required>
                                                                <option <?php if ($row['import'] == 0) echo "selected"; ?>
                                                                        value="0">No
                                                                </option>
                                                                <option <?php if ($row['import'] == 1) echo "selected"; ?>
                                                                        value="1">Yes
                                                                </option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            
                                            <?php
                                        }
                                    ?>
                                    </tbody>
                                </table>
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
                                                <li class="custom-list-items custom-list-items-action"><a
                                                            target="_blank"
                                                            href="<?php echo "uploads/insignificant_files/" . $row['fname']; ?>"><?php echo $row['fname']; ?></a>
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
                            <div class="row d-flex justify-content-center">
                            <?php
                                // $reviewSignoff = $con->query("select count(signoff_review_log.id) total from signoff_review_log inner join user on signoff_review_log.user_id=user.id where workspace_id=".$wid." and prog_id=239")->fetch_assoc();
                                // if($reviewSignoff['total']){
                                ?>
                                <!-- <button class="btn btn-outline-success fetchReview" id="239">Review Sign Off Log</button>&nbsp; -->
                                <?php
                                // }
                                // $prepareSignoff = $con->query("select count(signoff_prepare_log.id) total from signoff_prepare_log inner join user on signoff_prepare_log.user_id=user.id where workspace_id=".$wid." and prog_id=239")->fetch_assoc();
                                // if($prepareSignoff['total']){
                                ?>
                                <!-- <button class="btn btn-outline-success fetchPrepare" id="239">Prepare Sign Off Log</button> -->
                                <?php
                                // }
                                ?>
                            </div>
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
                                    <button class="btn btn-info" data-target="#addAccount" data-toggle="modal"
                                            id="add_acc">Add Request
                                    </button>
                                    <a target="_blank" href="exportRequestClient?wid=<?php echo $wid; ?>"><button class="btn btn-success">Export</button></a>
                                </div>
                                <?php } ?>
                            </div><br>
                            <div class="row">    
                                <div class="tableFixHead">
                                    <form style="overflow-x:auto;" action="clientAssistSubmit.php?&wid=<?php echo $wid; ?>" method="post" enctype="multipart/form-data">
                                        <div class="row" style="margin: 0 !important;">    
                                            <div>
                                                <table>
                                                    <thead class="text-center">
                                                    <tr>
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
                                                                $query1 = $con->query("select id,client_contact_id from accounts_log where workspace_id = '$wid' and id = '".$row['id']."'")->fetch_assoc();
                                                                ?>
                                                                <tr>
                                                                    <td><label><?php echo $row['account']; ?></label></td>
                                                                    <td scope="row" hidden>
                                                                        <input type="hidden" name="account[id][]"
                                                                            value="<?php echo $query1['id']; ?>">
                                                                    </td>
                                                                    <td><textarea rows="3" class="form-control mb-3" style="width: 500px !important;" <?php if($_SESSION['external'] == 1) echo "readonly"; ?> name="account[des][]"><?php echo $row['description']; ?></textarea></td>
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
                                                                                echo "<label style='white-space:nowrap;'>".$count++.":- <a target='_blank' href='uploads/clientrequest/".$documentResultRow['documents']."'>".$documentResultRow['documents']."</a></label><br>";
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
                                                <div class="col-md-12">
                                                    <i class="fas fa-info-circle" style="color:orange !important;"></i>
                                                    <strong>Click save button to save respective changes before clicking send request.</strong>
                                                </div>
                                                <hr>
                                                <?php } ?>
                                        <div class="row d-flex justify-content-center p-bottom">
                                            <input type="submit" class="btn btn-success align-middle" value="Save"> &nbsp;
                                            <?php 
                                            if(isset($_SESSION['external']) && $_SESSION['external'] != 1){
                                                ?>
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
                                        <button class="btn btn-success" data-target="#addExcelModal" data-toggle="modal">Upload Excel</button>
                                        <a href="assets/TB_Template.xlsx"><button class="btn bg-violet" download="Trial Balance Template.xlsx">Download Template</button></a>
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
                                                window.location = 'http://atlats.in/audit/assets/TB_Template.xlsx', {
                                                icon: 'success',
                                                }
                                            }
                                        });
                                    </script>
                                <?php
                            }
                            else{
                            ?>
                                <div class="col-md-12 text-center p-top d-flex justify-content-center">
                                    <!-- <button class="btn btn-success" >Download Trial Balance Template</button> -->
                                    <button class="btn btn-success" data-target="#addExcelModal" data-toggle="modal">Upload Excel</button>&nbsp;
                                    <!-- <a href="assets/TB_Template.xlsx"><button class="btn bg-violet" download="Trial Balance Template.xlsx">Download Template</button></a> -->
                                    <form method="get" action="assets/TB_Template.xlsx">
                                        <button type="submit" class="btn bg-violet">Download Template</button>
                                    </form>
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
                            $query = "select program.id id, program.program_name, workspace_log.amount, workspace_log.type, workspace_log.risk, workspace_log.import from program inner join workspace_log on program.id=workspace_log.program_id where program.parent_id=2 and workspace_log.workspace_id='$wid' and _seq >= 10 order by _seq,id asc";
                            $row1 = ($con->query("select pl_income, pl_expense from sub_materiality where workspace_id = '$wid'"))->fetch_assoc();
                            ?>
                            <div class="col-md-12 text-center p-top">
                                    <a target="_blank" href="exportAccounts?wid=<?php echo $wid; ?>&pid=240"><button class="btn bg-violet">Export</button></a>
                            </div> <hr>
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
                            <form id="balanceSheetForm" action="accountsSubmit.php?&wid=<?php echo $wid; ?>&pid=<?php echo $prog_id; ?>" method="post" enctype="multipart/form-data">
                                <table class="table table-hover">
                                    <thead>
                                    <tr class="table-secondary">
                                        <th scope="col">Asset Accounts</th>
                                        <th scope="col" hidden>Id</th>
                                        <th scope="col">Amount</th>
                                        <th scope="col">Type</th>
                                        <th scope="col">Risk</th>
                                        <th scope="col">Import</th>
                                    </tr>
                                    </thead>
                                    <?php
                                        $result = $con->query($query);
                                        $i = 0;
                                        while ($row = $result->fetch_assoc()) {
                                            ?>
                                            <tbody>
                                            <tr>
                                                <td scope="row" style="height: 4rem !important; display: flex; align-items: center; justify-content: center"><?php echo $row['program_name']; ?></td>
                                                <td scope="row" hidden>
                                                    <input type="hidden" name="submitData[id][]"
                                                            value="<?php echo $row['id']; ?>">
                                                </td>
                                                <td scope="row">
                                                    <input type="text" name="submitData[amount][]"
                                                            value="<?php echo $row['amount']; ?>" size="10">
                                                </td>
                                                <td scope="row">
                                                    <select name="submitData[type][]" class="form-control" required>
                                                        <option <?php if ($row['type'] == 0) echo "selected"; ?>
                                                                value="0">Significant
                                                        </option>
                                                        <option <?php if ($row['type'] == 1) echo "selected"; ?>
                                                                value="1">Non-Significant
                                                        </option>
                                                    </select>
                                                </td>
                                                <td scope="row">
                                                    <select name="submitData[risk][]" class="form-control" required>
                                                        <option <?php if ($row['risk'] == 0) echo "selected"; ?>
                                                                value="0">
                                                            Low
                                                        </option>
                                                        <option <?php if ($row['risk'] == 1) echo "selected"; ?>
                                                                value="1">
                                                            Moderate
                                                        </option>
                                                        <option <?php if ($row['risk'] == 2) echo "selected"; ?>
                                                                value="2">
                                                            High
                                                        </option>
                                                    </select>
                                                </td>
                                                <td scope="row">
                                                    <select name="submitData[import][]" class="form-control"
                                                            required>
                                                        <option <?php if ($row['import'] == 0) echo "selected"; ?>
                                                                value="0">No
                                                        </option>
                                                        <option <?php if ($row['import'] == 1) echo "selected"; ?>
                                                                value="1">Yes
                                                        </option>
                                                    </select>
                                                </td>
                                            </tr>
                                            </tbody>
                                            <?php
                                        }
                                    ?>
                                </table>
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
                                            <li class="custom-list-items custom-list-items-action"><a
                                                        target="_blank"
                                                        href="<?php echo "uploads/insignificant_files/" . $row['fname']; ?>"><?php echo $row['fname']; ?></a>
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
                                    <button class="btn bg-violet" data-target="#addMethod" data-toggle="modal"
                                            id="add_new">ADD NEW
                                    </button>
                                </div>
                            </div><br>
                            <form action="materialitySubmit.php?&wid=<?php echo $wid; ?>" method="post"
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
                                    <textarea class="form-control" id="textarea" rows="5"
                                                name="comment"><?php echo $row['comments']; ?></textarea>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="input1">Balance Assets Scope</label>
                                        <input type="text" class="form-control" name="aScope"
                                                value="<?php echo $row['balance_asset']; ?>">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="input2">Balance Liability Scope</label>
                                        <input type="text" class="form-control" name="lScope"
                                                value="<?php echo $row['balance_liability']; ?>">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="input3">PL- Income Scope</label>
                                        <input type="text" class="form-control" name="pliScope"
                                                value="<?php echo $row['pl_income']; ?>">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="input4">PL- Expenses Scope</label>
                                        <input type="text" class="form-control" name="pleScope"
                                                value="<?php echo $row['pl_expense']; ?>">
                                    </div>
                                </div>
                                <div class="row d-flex justify-content-center align-items-center">
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
                                                <a target="_blank" href="<?php echo "uploads/materiality/" . $row['fname']; ?>"><?php echo $row['fname']; ?></a>
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
                                <button class="btn btn-outline-success fetchReview" id="230">Reviewer Sign Off</button>&nbsp;
                                <?php
                                }
                                $prepareSignoff = $con->query("select count(signoff_prepare_log.id) total from signoff_prepare_log inner join user on signoff_prepare_log.user_id=user.id where workspace_id=".$wid." and prog_id=230")->fetch_assoc();
                                if($prepareSignoff['total']){
                                ?>
                                <button class="btn btn-outline-success fetchPrepare" id="230">Preparer Sign Off</button>
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
                                        <a href="subProgram.php?pid=<?php echo $queryrow['id']; ?>&parent_id=<?php echo $queryrow['parent_id']; ?>&wid=<?php echo $wid; ?>"
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
                                            <h2><span class="badge badge-primary" >Profit & Loss.</span></h2>
                                            <br/>
                                            <?php
                                        }
                                        ?>
                                        <div class="custom-list">
                                            <a href="subProgram.php?pid=<?php echo $queryrow['id']; ?>&parent_id=<?php echo $queryrow['parent_id']; ?>&wid=<?php echo $wid; ?>"
                                                class="custom-list-items custom-list-items-action"><b><?php echo trim($queryrow['program_name']); ?></b></a>
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
                                                                    <a href="subProgram.php?pid=<?php echo $queryrow['id']; ?>&parent_id=<?php echo $queryrow['parent_id']; ?>&wid=<?php echo $wid; ?>">    
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
                            </style>
                            <?php
                            $accountTypeResult = $con->query("SELECT account_type from trial_balance where workspace_id='".$wid."' group by account_type");
                            ?>
                            <br>
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
                            while($accountTypeRow = $accountTypeResult->fetch_assoc()){
                                ?>
                                <tr>
                                    <td colspan=5 style="text-align: left">
                                        <b>
                                            <h3><?php echo $accountTypeRow['account_type']; ?></h3>
                                        </b>
                                    </td>
                                </tr>
                                <?php
                                $accountClassResult = $con->query("SELECT account_class from trial_balance where account_type ='".$accountTypeRow['account_type']."' and workspace_id='".$wid."' group by account_class");
                                while($accountClassRow = $accountClassResult->fetch_assoc()){
                                    $cyFinalBalTotal = $cyBegBalTotal = 0;
                                    ?>
                                    <tr>
                                        <td colspan=5 style="text-align: left">
                                            <h4><?php echo $accountClassRow['account_class']; ?></h4>
                                        </td>
                                    </tr>
                                    <?php
                                    $financialStatementResult = $con->query("SELECT max(financial_statement) financial_statement, sum(cy_beg_bal) cy_beg_bal, sum(cy_final_bal) cy_final_bal from trial_balance where account_type ='".$accountTypeRow['account_type']."' and account_class ='".$accountClassRow['account_class']."' and workspace_id='".$wid."' group by account_class,account_class,financial_statement order by financial_statement");
                                    while($financialStatementRow = $financialStatementResult->fetch_assoc()){
                                        $cyFinalBalTotal += $financialStatementRow['cy_final_bal'];
                                        $cyBegBalTotal += $financialStatementRow['cy_beg_bal'];
                                        ?>
                                        <tr>
                                            <td style="text-align: left"><?php echo $financialStatementRow['financial_statement'];?></td>
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
                                        <tr>
                                            <td style="text-align: left"><h5 style="border-bottom: 1px solid;border-top: 1px solid;">Total <?php echo $accountClassRow['account_class']; ?></h5></td>
                                            <td style="text-align: left"><h5 style="border-bottom: 1px solid;border-top: 1px solid;"><?php echo numberToCurrency($cyFinalBalTotal); ?></h5></td>
                                            <td style="text-align: left"><h5 style="border-bottom: 1px solid;border-top: 1px solid;"><?php echo numberToCurrency($cyBegBalTotal); ?></h5></td>
                                            <td colspan=2></td>
                                        </tr>
                                    <?php
                                }
                                ?>
                                <tr><td colspan=5>&nbsp;</td></tr>
                                <?php
                            }
                            ?>
                                </tbody>
                            </table>
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
                                                <a href="subProgram.php?pid=<?php echo $queryrow['id']; ?>&parent_id=<?php echo $queryrow['parent_id']; ?>&wid=<?php echo $wid; ?>"
                                                    class="custom-list-items custom-list-items-action"><b><?php echo trim($queryrow['program_name']); ?></b></a>
                                            </div>
                                        <?php
                                    } else { ?>
                                        <div class="custom-list">
                                            <div class="custom-list-items custom-list-items-action">
                                                <?php echo trim($queryrow['program_name']); ?> &nbsp;&nbsp;
                                                <?php
                                                    if ($queryrow['active']) { ?>
                                                        <a href="#">
                                                            <?php
                                                                if($queryrow['id'] == 395){
                                                                    $trialBalanceResult = $con->query("select count(id) total from trial_balance where workspace_id = '".$wid."'");
                                                                    if($trialBalanceResult->fetch_assoc()['total'] == 0){
                                                                        $con->query("UPDATE workspace_log SET import = 0 WHERE workspace_id = '".$wid."' and program_id = 395");
                                                                    }
                                                                }
                                                                if($queryrow['id'] == 247 || $queryrow['id'] == 245 || $queryrow['id'] == 395){ ?>
                                                                    <a href="subProgram.php?pid=<?php echo $queryrow['id']; ?>&parent_id=<?php echo $queryrow['parent_id']; ?>&wid=<?php echo $wid; ?>">    
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
                        ?>
                    </div>
                </div>

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
                    <form action="excelUpload" enctype="multipart/form-data" method="post">
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
                    <form name="signoff" id="trialform" action="signoff.php?wid=<?php echo $wid; ?>" method="POST" target="_blank" enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="modal-header" id="programname">
                            </div>
                            <br>
                            <div class="container card">
                                <div class="row d-flex justify-content-between">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="files">Upload Documents</label>
                                            <div class="form-group">
                                                <input class="btn btn-upload" type="file" name="file[]" id="uploadedFile" multiple accept="application/msword, application/pdf, .doc, .docx, .pdf, .txt, .rtf">
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
                                    <input name="prepareSubmit" class="btn btn-success" type="submit"
                                            id="prepareSubmit" value="Prepare Sign-Off">
                                    <input name="done" class="btn btn-primary" type="submit" id="done"
                                            value="Save">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Error Modal -->
        <div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Files or Comment Both Can't be empty
                            <h5>
                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Files or Comment Both Can't be empty</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-danger" type="button" data-dismiss="modal">Ok</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Prepare Signoff Log Modal -->
        <div class="modal fade" id="prepareLogModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-size" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Preparer Sign Off</h5>
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
                            <h5 class="modal-title" id="exampleModalLabel"> Reviewer Sign Off </h5>
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

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>
    <script src="js/custom.js"></script>

    <script>
        $(document).ready(function () {
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
                                        "?pid=<?php echo $prog_id; ?>&parent_id=<?php echo $prog_parentId; ?>&wid=<?php echo $wid; ?>";
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
                                        "?pid=<?php echo $prog_id; ?>&parent_id=<?php echo $prog_parentId; ?>&wid=<?php echo $wid; ?>";
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
                                        "?pid=<?php echo $prog_id; ?>&parent_id=<?php echo $prog_parentId; ?>&wid=<?php echo $wid; ?>";
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
                                        "?pid=<?php echo $prog_id; ?>&parent_id=<?php echo $prog_parentId; ?>&wid=<?php echo $wid; ?>";
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
                                        "?pid=<?php echo $prog_id; ?>&parent_id=<?php echo $prog_parentId; ?>&wid=<?php echo $wid; ?>";
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
                                        "?pid=<?php echo $prog_id; ?>&parent_id=<?php echo $prog_parentId; ?>&wid=<?php echo $wid; ?>";
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
                                        "?pid=<?php echo $prog_id; ?>&parent_id=<?php echo $prog_parentId; ?>&wid=<?php echo $wid; ?>";
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
                                        "?pid=<?php echo $prog_id; ?>&parent_id=<?php echo $prog_parentId; ?>&wid=<?php echo $wid; ?>";
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
                                    window.location.href = window.location.pathname + "?pid=<?php echo $prog_id; ?>&parent_id=<?php echo $prog_parentId; ?>&wid=<?php echo $wid; ?>";
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
                                        "?pid=<?php echo $prog_id; ?>&parent_id=<?php echo $prog_parentId; ?>&wid=<?php echo $wid; ?>";
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
                                        "?pid=<?php echo $prog_id; ?>&parent_id=<?php echo $prog_parentId; ?>&wid=<?php echo $wid; ?>";
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
                                        "?pid=<?php echo $prog_id; ?>&parent_id=<?php echo $prog_parentId; ?>&wid=<?php echo $wid; ?>";
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
                                        "?pid=<?php echo $prog_id; ?>&parent_id=<?php echo $prog_parentId; ?>&wid=<?php echo $wid; ?>";
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
                                        "?pid=<?php echo $prog_id; ?>&parent_id=<?php echo $prog_parentId; ?>&wid=<?php echo $wid; ?>";
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
                        icon: 'error',
                        text: "Files or Comment Both Can't be empty",
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
                        // let obj = JSON.parse(data)
                        console.log(data);
                        // if(obj.status){
                        //     swal({
                        //         icon: "success",
                        //         text: obj.response,
                        //     }).then(function (isConfirm) {
                        //         if (isConfirm) {
                        //             window.location.href = window.location
                        //                     .pathname +
                        //                 "?pid=<?php echo $prog_id; ?>&parent_id=<?php echo $prog_parentId; ?>&wid=<?php echo $wid; ?>";
                        //         }
                        //     });
                        // }
                        // else{
                        //     swal({
                        //         icon: "error",
                        //         text: obj.response,
                        //     }).then(function (isConfirm) {
                        //         if (isConfirm) {
                        //             window.location.href = window.location
                        //                     .pathname +
                        //                 "?pid=<?php echo $prog_id; ?>&parent_id=<?php echo $prog_parentId; ?>&wid=<?php echo $wid; ?>";
                        //         }
                        //     });
                        // }
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
                                        "?pid=<?php echo $prog_id; ?>&parent_id=<?php echo $prog_parentId; ?>&wid=<?php echo $wid; ?>";
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
                                        "?pid=<?php echo $prog_id; ?>&parent_id=<?php echo $prog_parentId; ?>&wid=<?php echo $wid; ?>";
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
                        id: id
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
                                '<li class="custom-list-items custom-list-items-action" id="' +
                                value[0] + '"><a target="_blank" href="https://docs.google.com/gview?url=http://<?php echo $_SERVER['SERVER_NAME']; ?>/audit/uploads/program_files/'+value[1]+'">' +
                                value[1] + '</a>&nbsp;<a href="#"><i id="'+value[0]+'" class="fas fa-times-circle deleteFile" style="color:red !important;"></a></li>');
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
                                '<li class="custom-list-items custom-list-items-action" id="' +
                                value[0] + '"><a target="_blank" href="https://docs.google.com/gview?url=http://<?php echo $_SERVER['SERVER_NAME']; ?>/audit/uploads/program_files/'+value[1]+'">' +
                                value[1] + '</a>&nbsp;<a href="#"><i id="'+value[0]+'" class="fas fa-times-circle deleteFile" style="color:red !important;"></a></li>');
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

            //Validate Asset=Liability
            
            // $(document).on('click','#validateSubmit', function(e){
            //     let assetSum = liabilitySum = 0;
            //     for(i in $("#balanceSheetForm > table > tbody > tr")){
            //         try {
            //             if($("#balanceSheetForm > table > tbody > tr:nth-child("+i+")")){
            //                 if($("#balanceSheetForm > table > tbody > tr:nth-child("+i+")").attr('class') != 'table-secondary'){
            //                     // console.log($("#balanceSheetForm > table > tbody > tr:nth-child("+i+")").attr('class'))
            //                     let header_type = $("#balanceSheetForm > table > tbody > tr:nth-child("+i+") > td:nth-child(2) > input").val();
            //                     if(header_type == '1'){
            //                         if(parseInt($("#balanceSheetForm > table > tbody > tr:nth-child("+i+") > td:nth-child(4) > input").val())){
            //                             liabilitySum += parseFloat($("#balanceSheetForm > table > tbody > tr:nth-child("+i+") > td:nth-child(4) > input").val(), 10)
            //                         }
            //                     }
            //                     else{
            //                         if(parseInt($("#balanceSheetForm > table > tbody > tr:nth-child("+i+") > td:nth-child(4) > input").val())){
            //                             assetSum += parseFloat($("#balanceSheetForm > table > tbody > tr:nth-child("+i+") > td:nth-child(4) > input").val(), 10)
            //                         }
            //                     }
            //                 }
            //             }
            //         }
            //         catch (error) {
                        
            //         }
            //     }
            //     // console.log(liabilitySum)
            //     // console.log(assetSum)
            //     if(assetSum != liabilitySum){
            //         e.preventDefault()
            //         swal({
            //                     icon: "error",
            //                     text: "Assets and Liabilities are not matching",
            //                 }).then(function (isConfirm) {
            //                     if (isConfirm) {
            //                         window.location.href = window.location
            //                                 .pathname +
            //                             "?pid=<?php echo $prog_id; ?>&parent_id=<?php echo $prog_parentId; ?>&wid=<?php echo $wid; ?>";
            //                     }
            //                 });
            //     }
            // })

            $(document).ready(function() {
                var dataTable = $('#trialBalanceTable').DataTable({
                    "destroy": true,
                    "processing": true,
                    "serverSide": true,
                    "searching": true,
                    "order": [],
                    "fnRowCallback": function(nRow, aData, iDisplayIndex) {
                        $("td:first", nRow).html(iDisplayIndex + 1);
                        return nRow;
                    },
                    "drawCallback": function(settings) {
                        var pagination = $(this).closest('.dataTables_wrapper').find('.dataTables_paginate');
                        pagination.toggle(this.api().page.info().pages > 1);
                    },
                    "ajax":
                    $.fn.dataTable.pipeline({
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
            
            // Register an API method that will empty the pipelined data, forcing an Ajax
            // fetch on the next draw (i.e. `table.clearPipeline().draw()`)
            $.fn.dataTable.Api.register( 'clearPipeline()', function () {
                return this.iterator( 'table', function ( settings ) {
                    settings.clearCache = true;
                } );
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
                url: "darkmode.php",
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