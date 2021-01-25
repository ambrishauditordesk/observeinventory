<?php
    include 'dbconnection.php';
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
    <link
            href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
            rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="css/custom.css" rel="stylesheet">

    <!-- bootstrap cdn -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/jstree.min.js"></script>

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
    </style>
</head>

<body style="overflow-y: scroll" oncontextmenu="return false">

<div id="wrapper">

    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

        <!-- Sidebar - Brand -->
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index">
            <div class="sidebar-brand-icon">
                <a class="navbar-brand navbar-logo" href="<?php if(isset($_SESSION['external_client_id']) && $_SESSION['external_client_id'] == '') echo "admin/clientList"; else echo "workspace?cid=".$_SESSION['client_id']; ?>">Audit-EDG</a>
            </div>
        </a>

        <!-- Divider -->
        <hr class="sidebar-divider d-none d-md-block">
        <!-- Nav Item - Pages Collapse Menu -->

        <?php
        if($_SESSION['external'] == 0){
            $query = "select program.* from program inner join workspace_log on program.id=workspace_log.program_id where program.parent_id='$prog_parentId' and workspace_log.workspace_id='$wid' order by _seq";
            $exquery = $con->query($query);
            if ($exquery->num_rows != 0) {
                while ($queryrow = $exquery->fetch_assoc()) {
                    if ($queryrow['hasChild'] == 1) {
                        ?>
                        <li id="employees" class="nav-item  <?php if ($queryrow['id'] == $prog_id) echo 'active'; ?>">
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
                <!-- Topbar Navbar -->
                <ul class="navbar-nav ml-auto">
                    <?php
                        if ($prog_id != '2' && $prog_id != '20' && $prog_id != '230' && $prog_id != '229' && $prog_id != '12' && $prog_id != '239' && $prog_id != '240' && $prog_id != '247') {
                            ?>
                            <li class="nav-item d-flex">
                                <a class="nav-link d-flex align-items-center" href="#" data-toggle="modal"
                                   data-target="#addProgModal">
                                    <span>Add Programme</span>&nbsp;&nbsp;
                                    <i class="fas fa-user-plus fa-1x"></i>
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
                                    <span>Add Account</span>&nbsp;&nbsp;
                                    <i class="fas fa-user-plus fa-1x"></i>
                                </a>
                            </li>
                    <?php } 
                    ?>
                    <li class="nav-item dropdown no-arrow ">
                        <a class="nav-link dropdown-toggle d-flex justify-contents-center" href="#"
                           id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true"
                           aria-expanded="false">
                            <div class="d-flex align-items-center">
                                <span><?php echo $_SESSION['name']; ?>&nbsp;</span>
                                <span class="rounded-circle d-flex justify-contents-center">
                                        <i class="fas fa-user-circle fa-2x" aria-hidden="true"></i>
                                    </span>
                            </div>
                        </a>
                        <!-- Dropdown - User Information -->
                        <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                             aria-labelledby="userDropdown">
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
            <!-- HEADER -->
            <div id="header">
                <div class="container-fluid shadow border border-bottom" stickylevel="0" style="z-index:1200;">
                    <div class="row pt-1">
                        <div class="row text-center cdrow" href="#">
                            <h2><?php echo strtoupper($clientName . " - Dashboard"); ?></h2>
                        </div>
                    </div>
                </div>
                <br>
                <!-- Body Starts -->
                <div class="container-fluid">
                    <!-- Breadcrumbs -->

                    <div class="row">
                        <div class="col-md-12">
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
                                $query = "select program.id id, _seq,assets_liabilities_check.program_name, assets_liabilities_check.header_type,workspace_log.amount, workspace_log.type, workspace_log.risk, workspace_log.import from program inner join workspace_log on program.id=workspace_log.program_id inner join assets_liabilities_check on assets_liabilities_check.id=program.id where program.parent_id=33 and workspace_log.workspace_id='$wid' order by _seq,id asc";
                                $row1 = ($con->query("select balance_asset, balance_liability from sub_materiality where workspace_id = '$wid'"))->fetch_assoc();
                                ?>
                                <div class="form-row">
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
                                <form id="balanceSheetForm" action="accountsSubmit.php?&wid=<?php echo $wid; ?>" method="post">
                                    <table class="table table-hover">
                                        <thead>
                                        <tr class="table-secondary">
                                            <th scope="col" hidden>Id</th>
                                            <th scope="col">Asset Accounts</th>
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
                                                ?>
                                                
                                                <?php
                                                    if ($row['header_type'] == 0) { ?>
                                                        <tr id="<?php echo ++$i; ?>">
                                                            <td scope="row" hidden>
                                                                <input type="hidden" name="submitData[id][]"
                                                                       value="<?php echo $row['id']; ?>">
                                                            </td>
                                                            <td scope="row" hidden>
                                                                <input type="hidden" name="submitData[header_type][]"
                                                                       value="<?php echo $row['header_type']; ?>">
                                                            </td>
                                                            <td scope="row"><?php echo $row['program_name']; ?></td>
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
                                                            <th scope="col" hidden>Id</th>
                                                            <th scope="col">Liability Accounts</th>
                                                            <th scope="col">Amount</th>
                                                            <th scope="col">Type</th>
                                                            <th scope="col">Risk</th>
                                                            <th scope="col">Import</th>
                                                        </tr>
                                                        <?php
                                                        $result = $con->query($query);
                                                    while ($row = $result->fetch_assoc()) {
                                                        if ($row['header_type'] == 1) {?>
                                                        <tr id="<?php echo ++$i; ?>">
                                                            <td scope="row" hidden>
                                                                <input type="hidden" name="submitData[id][]"
                                                                       value="<?php echo $row['id']; ?>">
                                                            </td>
                                                            <td scope="row" hidden>
                                                                <input type="hidden" name="submitData[header_type][]"
                                                                       value="<?php echo $row['header_type']; ?>">
                                                            </td>
                                                            <td scope="row"><?php echo $row['program_name']; ?></td>
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
                                    <div class="row d-flex justify-content-center">
                                        <input type="submit" id="validateSubmit" class="btn btn-primary align-middle" value="Submit">
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
                                    <div class="col-md-12 text-center">
                                        <button class="btn btn-primary" data-target="#addAccount" data-toggle="modal"
                                                id="add_acc">ADD REQUEST
                                        </button>
                                    </div>
                                    <div class="col-md-12">
                                        <a target="_blank" href="exportRequestClient?wid=<?php echo $wid; ?>"><button class="btn btn-primary">Export</button></a>
                                    </div>
                                    <?php } ?>
                                </div><br>
                                <div class="row">    
                                    <div class="tableFixHead">
                                        <form style="overflow-x:auto;" action="clientAssistSubmit.php?&wid=<?php echo $wid; ?>" method="post" enctype="multipart/form-data">
                                            <div class="row">    
                                                <div>
                                                    <table>
                                                        <thead class="text-center">
                                                        <tr>
                                                            <th scope="col" hidden>Id</th>
                                                            <th scope="col">Account Name</th>
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
                                                                        <td scope="row" hidden>
                                                                            <input type="hidden" name="account[id][]"
                                                                                value="<?php echo $query1['id']; ?>">
                                                                        </td>
                                                                        <td><label><?php echo $row['account']; ?></label></td>
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
                                                        <strong>Click the Save button to save respective changes before clicking on Sending Invitation.</strong>
                                                    </div>
                                                    <hr>
                                                    <?php } ?>
                                            <div class="row d-flex justify-content-center">
                                                <input type="submit" class="btn btn-primary align-middle" value="Save"> &nbsp;
                                                <?php 
                                                if(isset($_SESSION['external']) && $_SESSION['external'] != 1){
                                                    ?>
                                                <input id="sendInvitation" type="button" class="btn btn-primary align-middle" value="Send Invitation">
                                                <?php } ?>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            <?php 
                            }
                            elseif ($prog_id == 240) {
                                $query = "select program.id id, program.program_name, workspace_log.amount, workspace_log.type, workspace_log.risk, workspace_log.import from program inner join workspace_log on program.id=workspace_log.program_id where program.parent_id=34 and workspace_log.workspace_id='$wid' order by _seq,id asc";
                                $row1 = ($con->query("select pl_income, pl_expense from sub_materiality where workspace_id = '$wid'"))->fetch_assoc();
                                ?>
                                <div class="form-row">
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
                                <form action="accountsSubmit.php?&wid=<?php echo $wid; ?>" method="post">
                                    <table class="table table-hover">
                                        <thead>
                                        <tr class="table-secondary">
                                            <th scope="col" hidden>Id</th>
                                            <th scope="col">Asset Accounts</th>
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
                                                    <td scope="row" hidden>
                                                        <input type="hidden" name="submitData[id][]"
                                                               value="<?php echo $row['id']; ?>">
                                                    </td>
                                                    <td scope="row"><?php echo $row['program_name']; ?></td>
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
                                    <div class="row d-flex justify-content-center">
                                        <input type="submit" class="btn btn-primary align-middle" value="Submit">
                                    </div>
                                </form>
                                <?php
                            } elseif ($prog_id == 230) {
                                $query = "select * from materiality where workspace_id='$wid' and prog_id='$prog_id'";
                                $result = $con->query($query); ?>
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <button class="btn btn-primary" data-target="#addMethod" data-toggle="modal"
                                                id="add_new">ADD NEW
                                        </button>
                                    </div>
                                </div><br>
                                <form action="materialitySubmit.php?&wid=<?php echo $wid; ?>" method="post"
                                      enctype="multipart/form-data">
                                    <table class="table table-hover" id="tab_logic">
                                        <thead class="text-center">
                                        <tr>
                                            <th scope="col" hidden>Id</th>
                                            <th scope="col">Methods</th>
                                            <th scope="col" colspan="2">Standard %</th>
                                            <th scope="col" colspan="2">Custom %</th>
                                            <th scope="col">Amount</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                        <tr>
                                            <th hidden></th>
                                            <th></th>
                                            <th>High</th>
                                            <th>Low</th>
                                            <th>High</th>
                                            <th>Low</th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody id="abody">
                                        <?php
                                            while ($row = $result->fetch_assoc()) {
                                                ?>
                                                <tr>
                                                    <td scope="row" hidden>
                                                        <input type="hidden" name="materialityData[id][]"
                                                               value="<?php echo $row['id']; ?>">
                                                    </td>
                                                    <td><label><?php echo $row['name']; ?></label></td>
                                                    <td><input type="text" size="10" name="materialityData[sLow][]"
                                                               value="<?php echo $row['standard_low']; ?>"></td>
                                                    <td><input type="text" size="10" name="materialityData[sHigh][]"
                                                               value="<?php echo $row['standard_high']; ?>"></td>
                                                    <td><input type="text" size="10" name="materialityData[cLow][]"
                                                               value="<?php echo $row['custom_low']; ?>"></td>
                                                    <td><input type="text" size="10" name="materialityData[cHigh][]"
                                                               value="<?php echo $row['custom_high']; ?>"></td>
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
                                    <div class="row d-flex justify-content-center">
                                        <input class="btn btn-primary" type="file" name="file"
                                        accept=".pdf, .xls, .xlsx, .txt, .csv, .doc, .docx, .rtf, .xlmb">
                                        &nbsp;
                                        <input type="submit" class="btn btn-primary align-middle" value="Submit"> &nbsp;
                                    </div>
                                </form><br>
                                <div class="row d-flex justify-content-center">
                                    <div class="col-md-5 col-sm-12">
                                        <?php
                                            $query = "select * from materiality_files where workspace_id='$wid'";
                                            $result = $con->query($query);
                                            while ($row = $result->fetch_assoc()) {
                                                ?>
                                                <ul class="list-group">
                                                    <li class="list-group-item list-group-item-action"><a
                                                                target="_blank"
                                                                href="<?php echo "uploads/materiality/" . $row['fname']; ?>"><?php echo $row['fname']; ?></a>
                                                    </li>
                                                </ul>
                                                <?php
                                            } ?>
                                    </div>
                                </div> <?php 
                            } elseif ($prog_id == 12)  {
                                    // $query = "select program.*, signoff_log.Prepare_SignOff, signoff_log.prepare_date, signoff_log.Review_SignOff, signoff_log.review_date, workspace_log.status status, workspace_log.active active from program inner join workspace_log on program.id = workspace_log.program_id left join signoff_log on program.id = signoff_log.prog_id and signoff_log.workspace_id = workspace_log.workspace_id where program.parent_id = '$prog_id' and workspace_log.workspace_id = '$wid' and workspace_log.import = 1 order by _seq";
                                    $query = "select program.*, workspace_log.status status, workspace_log.active active from program inner join workspace_log on program.id = workspace_log.program_id where program.parent_id = '$prog_id' and workspace_log.workspace_id = '$wid' and workspace_log.import = 1 order by _seq";
                                    $exquery = $con->query($query);
                                    if ($exquery->num_rows != 0)
                                    {
                                    while ($queryrow = $exquery->fetch_assoc())
                                    {
                                    if ($queryrow['hasChild'] == 1)
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
                                        <?php echo trim($queryrow['program_name']); ?>
                                        <?php }
                                            }
                                            }
                            } else {
                                    $query = "select program.*, workspace_log.status status, workspace_log.active active from program inner join workspace_log on program.id = workspace_log.program_id where program.parent_id = '$prog_id' and workspace_log.workspace_id = '$wid' and workspace_log.import = 1";
                                    $exquery = $con->query($query);
                                    if ($exquery->num_rows != 0) {
                                        while ($queryrow = $exquery->fetch_assoc()) {
                                            if ($queryrow['hasChild'] == 1) { ?>
                                                <div class="list-group">
                                                    <a href="subProgram.php?pid=<?php echo $queryrow['id']; ?>&parent_id=<?php echo $queryrow['parent_id']; ?>&wid=<?php echo $wid; ?>"
                                                        class="list-group-item list-group-item-action"><b><?php echo trim($queryrow['program_name']); ?></b></a>
                                                </div> <?php
                                            } else { ?>
                                                <div class="list-group">
                                                    <div class="list-group-item list-group-item-action">
                                                        <?php echo trim($queryrow['program_name']); ?> &nbsp;&nbsp;
                                                        <?php
                                                            if ($queryrow['active']) { ?>
                                                                <a href="#">
                                                                    <?php
                                                                        if($queryrow['id'] == 247){ ?>
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
                                                                    <button class="btn btn-outline-primary fetchPrepare" id="<?php echo $queryrow['id']; ?>">Prepare Sign Off Log</button>
                                                                    <?php
                                                                    }
                                                                    $reviewSignoff = $con->query("select count(signoff_review_log.id) total from signoff_review_log inner join user on signoff_review_log.user_id=user.id where workspace_id=".$wid." and prog_id=".$queryrow['id'])->fetch_assoc();
                                                                    if($reviewSignoff['total']){
                                                                    ?>
                                                                    <button class="btn btn-outline-success fetchReview" id="<?php echo $queryrow['id']; ?>">Review Sign Off Log</button>
                                                                    <?php
                                                                    }
                                                                } else { ?>
                                                                    <i class="fas fa-times-circle"
                                                                        style="color:red !important;">
                                                                    </i> <?php
                                                                } ?>
                                                                <a href="#" id="<?php echo $queryrow['id']; ?>"
                                                                    class="buttonActive">
                                                                    <i class="fa fa-thumbs-up float-right"
                                                                        aria-hidden="true"
                                                                        style="color:blue !important;">
                                                                    </i>
                                                                </a> <?php
                                                            } else { ?>
                                                                <a href="#" id="<?php echo $queryrow['id']; ?>"
                                                                    class="buttonActive">
                                                                    <i class="fa fa-ban float-right" aria-hidden="true" style="color:orange !important;"></i>
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
        <!--Add Programme Modal -->
        <div class="modal fade" id="addProgModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add Programme/Step
                            <h5>
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

        <!--Add BS and PL Accounts Modal -->
        <div class="modal fade" id="addbsplModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add Account
                            <h5>
                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                    </div>
                    <form>
                        <div class="modal-body">
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
                        <div class="modal-footer">
                            <button class="btn btn-danger" type="button" data-dismiss="modal">Cancel</button>
                            <input class="btn btn-primary" type="submit" id="addbsplSubmit" value="Done">
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
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add New Request
                            <h5>
                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                    </div>
                    <form>
                        <div class="modal-body">
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
                        <div class="modal-footer">
                            <button class="btn btn-danger" type="button" data-dismiss="modal">Cancel</button>
                            <input class="btn btn-primary" type="submit" id="addAccountSubmit" value="Done">
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Signoff Modal-->
        <div class="modal fade" id="signoffModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header" id="programname">
                    </div>
                    <form name="signoff" action="signoff.php?wid=<?php echo $wid; ?>" method="POST" enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="container card bg-light font-2 py-2">
                                <div class="row d-flex justify-content-between">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="form-group">
                                                <label for="country">Upload Documents</label>
                                                <div class="form-group">
                                                    <input class="btn btn-primary" type="file" name="file[]" id="uploadedFile" multiple accept="application/msword, application/pdf, .doc, .docx, .pdf, .txt, .rtf">
                                                </div>
                                            </div>
                                        </div>
                                        <ul class="list-group" id="filenames">
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="hidden" name="id" id="id">
                                        <input type="hidden" name="prog_id" id="prog_id">
                                        <label>Add Your Comment</label>
                                        <textarea name="newComment" id="newComment" class="form-control"
                                                  style="height:50px;"></textarea>
                                        <label>Comments</label>
                                        <table class="table" name="comments" id="comments"></table>
                                    </div>
                                    <div class="col-md-12">
                                        <hr>

                                        <i class="fas fa-info-circle" style="color:orange !important;"></i>
                                        <strong>Click the save button to save respective files/comments before signing off</strong>
                                    </div>
                                </div>
                                <div class="modal-footer">
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
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Prepare Sign Off Logs
                            <h5>
                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                    </div>
                    <div class="modal-body">
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
                    <div class="modal-footer">
                        <button class="btn btn-danger" type="button" data-dismiss="modal">Ok</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Review Signoff Log Modal -->
        <div class="modal fade" id="reviewLogModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Review Sign Off Logs
                            <h5>
                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                    </div>
                    <div class="modal-body">
                        <table class="table" id="reviewLogTable">
                            <thead>
                                <tr>
                                    <th>
                                        Initials
                                    </th>
                                    <th>
                                        Review Signoff Date
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
                    <div class="modal-footer">
                        <button class="btn btn-danger" type="button" data-dismiss="modal">Ok</button>
                    </div>
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
                        prog_id: <?php echo $prog_id == 240? 34:33; ?>,
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
                    $("#signoffModal").modal("hide") 
                    $("#errorModal").modal("show") 
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
                                    window.location.href = window.location
                                            .pathname +
                                        "?pid=<?php echo $prog_id; ?>&parent_id=<?php echo $prog_parentId; ?>&wid=<?php echo $wid; ?>";
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
                $("#signoffModal #comments").val("");
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
                                '<li class="list-group-item list-group-item-action" id="' +
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
                            '</h5><button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>'
                        );
                    }
                });
                $("#signoffModal #prog_id").val(id);
                $("#signoffModal").modal('show');
            });

            $(document).on('click','#validateSubmit', function(e){
                let assetSum = liabilitySum = 0;
                for(i in $("#balanceSheetForm > table > tbody > tr")){
                    try {
                        if($("#balanceSheetForm > table > tbody > tr:nth-child("+i+")")){
                            if($("#balanceSheetForm > table > tbody > tr:nth-child("+i+")").attr('class') != 'table-secondary'){
                                // console.log($("#balanceSheetForm > table > tbody > tr:nth-child("+i+")").attr('class'))
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
                // console.log(liabilitySum)
                // console.log(assetSum)
                if(assetSum != liabilitySum){
                    e.preventDefault()
                    swal({
                                icon: "error",
                                text: "Assets and Liabilities are not matching",
                            }).then(function (isConfirm) {
                                if (isConfirm) {
                                    window.location.href = window.location
                                            .pathname +
                                        "?pid=<?php echo $prog_id; ?>&parent_id=<?php echo $prog_parentId; ?>&wid=<?php echo $wid; ?>";
                                }
                            });
                }
            })
        });
    </script>
</body>