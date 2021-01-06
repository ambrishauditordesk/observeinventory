<?php
    include 'dbconnection.php';
    session_start();
    if (!isset($_SESSION['email']) && empty($_SESSION['email'])) {
        header("Location: ../login");
    }
    $clientName = $_SESSION['cname'];
    $prog_id = $_GET['pid'];
    $prog_parentId = $_GET['parent_id'];
    $wid = $_GET['wid'];
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

</head>

<body style="overflow-y: scroll">

<div id="wrapper">

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
                                $query = "select program.id id, program.program_name, workspace_log.amount, workspace_log.type, workspace_log.risk, workspace_log.import from program inner join workspace_log on program.id=workspace_log.program_id where program.parent_id=33 and workspace_log.workspace_id='$wid' order by _seq,id asc";
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
                                                <?php
                                                    if ($row['id'] == 45) { ?>
                                                        <tr class="table-secondary">
                                                            <th scope="col" hidden>Id</th>
                                                            <th scope="col">Liability Accounts</th>
                                                            <th scope="col">Amount</th>
                                                            <th scope="col">Type</th>
                                                            <th scope="col">Risk</th>
                                                            <th scope="col">Import</th>
                                                        </tr>
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
                                                    <?php } else { ?>
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
                            } 
                            elseif ($prog_id == 247){
                                $query = "select * from materiality where workspace_id='$wid' and prog_id='$prog_id'";
                                $result = $con->query($query); ?>
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <button class="btn btn-primary" data-target="#addAccount" data-toggle="modal"
                                                id="add_acc">ADD ACCOUNT
                                        </button>
                                    </div>
                                </div><br>
                                <form action="materialitySubmit.php?&wid=<?php echo $wid; ?>" method="post" enctype="multipart/form-data">
                                    <table class="table table-hover" id="tab_logic">
                                        <thead class="text-center">
                                        <tr>
                                            <th scope="col" hidden>Id</th>
                                            <th scope="col">Account Name</th>
                                            <th scope="col">Description</th>
                                            <th scope="col">Specific Documents requested</th>
                                            <th scope="col">Client Assign</th>
                                            <th scope="col">Requested By</th>
                                            <th scope="col">Date Requested</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                        <tr>
                                            <th hidden></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
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
                                </form>
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
                                               accept="application/msword, application/pdf, .doc, .docx, .pdf, .txt, .rtf">
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
                                    $query = "select program.*, signoff_log.Prepare_SignOff, signoff_log.prepare_date, signoff_log.Review_SignOff, signoff_log.review_date, workspace_log.status status, workspace_log.active active from program inner join workspace_log on program.id = workspace_log.program_id left join signoff_log on program.id = signoff_log.prog_id and signoff_log.workspace_id = workspace_log.workspace_id where program.parent_id = '$prog_id' and workspace_log.workspace_id = '$wid' and workspace_log.import = 1 order by _seq";
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
                                    $query = "select program.*, signoff_log.Prepare_SignOff, signoff_log.prepare_date, signoff_log.Review_SignOff, signoff_log.review_date, workspace_log.status status, workspace_log.active active from program inner join workspace_log on program.id = workspace_log.program_id left join signoff_log on program.id = signoff_log.prog_id and signoff_log.workspace_id = workspace_log.workspace_id where program.parent_id = '$prog_id' and workspace_log.workspace_id = '$wid' and workspace_log.import = 1";
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
                                                                if ($queryrow['status']) { ?>
                                                                    <i class="fas fa-check-circle"
                                                                        style="color:green !important;">
                                                                    </i>
                                                                    <span>Prepared By:
                                                                        <?php echo $queryrow['Prepare_SignOff'] . "(" . $queryrow['prepare_date']; ?>
                                                                        )
                                                                    </span> &nbsp;| &nbsp;
                                                                    <span>Reviewed By:
                                                                        <?php echo $queryrow['Review_SignOff'] . "(" . $queryrow['review_date']; ?>
                                                                        )</span>
                                                                    <?php
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
                            <div class="form-group ">
                                <label for="name">Account</label>
                                <select name="addbspl" id="bspl_type" class="form-control" required>
                                    <option value="">Select Account</option>
                                    <option value="33">Balance Sheet</option>
                                    <option value="34">Profit & Loss</option>
                                </select>
                            </div>
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
        <div class="modal fade" id="addMethod" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
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
        <div class="modal fade" id="addAccount" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add New Account
                            <h5>
                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                    </div>
                    <form>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="name">Account Name</label>
                                <select class="form-control" name="account" required>
                                    <option>Select Account !</option>
                                        <?php
                                            $accQuery = $con->query("select * from accounts");
                                            while ($accResult = $accQuery->fetch_assoc()) {
                                        ?>
                                    <option value="<?php echo $accResult['id']; ?>">
                                        <?php echo $accResult['account']; ?></option>
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
        <div class="modal fade" id="signoffModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header" id="programname">


                    </div>
                    <form name="signoff" action="signoff.php?wid=<?php echo $wid; ?>" method="POST"
                          enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="container card bg-light font-2 py-2">
                                <div class="row d-flex justify-content-between">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="form-group">
                                                <label for="country">Upload Documents</label>
                                                <div class="form-group">
                                                    <input class="btn btn-primary" type="file" name="file[]"
                                                           multiple
                                                           accept="application/msword, application/pdf, .doc, .docx, .pdf, .txt, .rtf">
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
                                        <textarea name="newComment" class="form-control"
                                                  style="height:50px;"></textarea>
                                        <label>Comments</label>
                                        <textarea readonly name="comments" id="comments" class="form-control"
                                                  style="height:200px;"></textarea><br>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <input name="reviewSubmit" class="btn btn-info" type="submit" id="reviewSubmit"
                                           value="Review Sign-Off">
                                    <input name="prepareSubmit" class="btn btn-success" type="submit"
                                           id="prepareSubmit" value="Prepare Sign-Off">
                                    <input name="done" class="btn btn-primary" type="submit" id="done"
                                           value="Submit">
                                </div>
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
                var bspl_type = $("#bspl_type").val();
                $.ajax({
                    url: "addbspl.php",
                    type: "POST",
                    data: {
                        prog_id: bspl_type,
                        wid: <?php echo $wid; ?>,
                        bspl_name: bspl_name,
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
                        obj.file.forEach(function (value) {
                            $('#signoffModal #filenames').append(
                                '<li class="list-group-item list-group-item-action" id="' +
                                value[0] + '"><a target="_blank" href="#">' +
                                value[1] + '</a></li>');
                        });
                        if (obj.comment != null) {
                            $("#signoffModal #comments").val(obj.comment['comment']);
                            $("#signoffModal #id").val(obj.comment['id']);
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
        });
    </script>
</body>