<?php
    include 'dbconnection.php';
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
        header("Location: login");
    }
    if($_SESSION['role'] == 5){
      header("Location: login");
    }
   $wid = $_SESSION['workspace_id'];
   if(!isset($_SESSION['workspace_id']) && empty($_SESSION['workspace_id'])){
       header('location: ./');
   }
   $clientName = 1;

    if (isset($_SESSION['logged_in_date']) && !empty($_SESSION['logged_in_date'])){
        $currentDate = date_create(date("Y-m-d H:i:s",strtotime(date_format(date_create("now", new DateTimeZone('Asia/Kolkata')), "Y-m-d H:i:s"))));
        $loggedInDate = date_create(date("Y-m-d H:i:s",strtotime($_SESSION['logged_in_date'])));
        $diff=date_diff($currentDate,$loggedInDate);
		if($diff->format("%a") > 1 || $diff->format("%m") > 1 || $diff->format("%y") > 1){
			header('Location: logout');
		}
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
    <meta name="google" content="notranslate" />

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="css/custom.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/uiux.css" rel="stylesheet" type="text/css">
    <link href="css/chat.css" rel="stylesheet" type="text/css">

    <!-- JQuery CDN -->
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.min.js"
        integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>

    <!-- Datatable CDN -->
    <link href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css" rel="stylesheet">
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
    <!-- SweetAlert -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"
        integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA=="
        crossorigin="anonymous"></script>

        <style>
        .card-body{
            margin: 0 13.5%;
        }
        .custom-control{
            padding-left: 2.5rem;
        }
        </style>
</head>

<body style="overflow-y: scroll">

    <!-- Navbar -->
    <nav class="navbar sticky-top navbar-expand-lg navbar-mainbg border-bottom">
        <!-- Topbar Navbar -->
        <ul class="navbar-nav ml-auto">
            <!-- <li class="nav-item d-flex">
                <a class="nav-link d-flex align-items-center" href="clientList">
                    <span>List Clients</span>&nbsp;&nbsp;
                    <i class="fas fa-list fa-1x"></i>
                </a>
            </li> -->
            <?php 
            if(!empty($wid))
            {
            ?>
            <li class="nav-item d-flex">
                <a class="nav-link d-flex align-items-center" href="clientDashboard?qid=<?php echo base64_encode(md5($clientName)); ?>&gid=<?php echo base64_encode(md5($clientName)); ?>&fid=<?php echo base64_encode(md5($clientName)); ?>&eid=<?php echo base64_encode(md5($clientName)); ?>&cid=<?php echo base64_encode($_SESSION['client_id']); ?>&yid=<?php echo base64_encode(md5($clientName)); ?>&bid=<?php echo base64_encode(md5($clientName)); ?>&aid=<?php echo base64_encode(md5($clientName)); ?>&zid=<?php echo base64_encode(md5($clientName)); ?>&jid=<?php echo base64_encode(md5($clientName)); ?>&wid=<?php echo base64_encode($wid); ?>&xid=<?php echo base64_encode(md5($clientName)); ?>">
                    <img class="nav-icon" src="Icons/Group 3.svg"/>&nbsp;&nbsp;
                    <span>Dashboard</span>
                </a>
            </li>
            <?php }
            else{ ?>
            <li class="nav-item d-flex">
                <a class="nav-link d-flex align-items-center" href="admin/clientList">
                    <img class="nav-icon" src="Icons/Group 3.svg"/>&nbsp;&nbsp;
                    <span>Client List</span>
                </a>
            </li>
            <?php } ?>
            <li class="nav-item d-flex" style="background-color: rgba(232,240,255,1); border-radius: 15px;">
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
                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown" style="font-size: small";>
                    <!-- <a class="dropdown-item" href="#" data-toggle="modal" data-target="#changePasswordModal">
                            <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                            Change Password
                        </a>
                        <div class="dropdown-divider"></div> -->
                        <?php 
                        if($_SESSION['role'] == '-1' || $_SESSION['role'] == '1'){
                        ?>
                            <a class="dropdown-item" href="admin/activityLog"><i class="fas fa-list"></i>Activity Log</a>
                            <a class="dropdown-item" href="#"><i class="fas fa-user-tie hue" style="color:blue;"></i><?php echo $_SESSION['name']; ?></a>
                            <a class="dropdown-item" href="#"><i class="fas fa-signature hue" style="color:blue;"></i><?php echo $_SESSION['signoff']; ?></a>
                            <a class="dropdown-item" href="#"><i class="fas fa-at hue" style="color:blue;"></i><?php echo $_SESSION['email']; ?></a>
                        <?php
                        }   
                        else{
                            ?>
                            <a class="dropdown-item" href="admin/activityLog"><i class="fas fa-list"></i>Activity Log</a>
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

    <!-- SideBar -->
    <div class="sidenav">
        <div class="side-header">
            <!-- <div style="border-bottom:1px solid;"> -->
            <div>
                <img class="sidenav-icon" src="Icons/Group -1.svg"/> &nbsp;
                Audit Edg
            </div>
        </div>
        <div class="side-footer">
            <div class="side-body">
                <div class="dash">
                    <img class="sidenav-icon" src="Icons/pie-chart.svg" style="width:24px !important; height:24px !important;"/> &nbsp;
                    Contacts
                    </svg>
                </div>
            </div>
            <div class="settings">
                <div class="settings-items-top-div">
                    <div class="settings-items">
                        <a href="settings" class="text-decoration-none">
                            <img class="sidenav-icon" src="Icons/settings.svg" style="width:24px !important; height:24px !important;"/> &nbsp;Settings
                        </a>
                    </div>
                    <!-- <div class="settings-items">
                        <img class="sidenav-icon" src="Icons/help-circle.svg" style="width:24px !important; height:24px !important;"/> &nbsp;
                        Help
                    </div> -->
                </div>
                <a href="logout"><button type="button" class="btn btn-primary"><i class="fas fa-sign-out-alt"></i> Logout</button></a>
            </div>
        </div>
    </div>

    <div class="mar">

        <!-- HEADER -->
        <div id="header">
            <div class="container-fluid shadow" stickylevel="0" style="z-index:1200;">
                <div class="row pt-1">
                    <div class="col-md-4">
                        <!-- <img class="float-left" src="vendor/img/audit-edge-logo.svg" style="height:45px;"> -->
                        <div class="ml-2 font-1 h3 py-1 d-inline-block float-left"></div>
                    </div>
                    <div class="col-md-4 text-center font-2 getContent" href="#">
                        <h3>Deleted File Log</h3>
                    </div>
                </div>
            </div>
        </div><br>

        <!-- Body -->
        <center><h4><b>Below are all deleted files</b></h4>
        
        <center><h4><i class="fas fa-exclamation-triangle" style="color: red;"></i>To <b>Freeze Workspace</b> all deleted files need to be either deleted permanently or it must be recovered. <i class="fas fa-exclamation-triangle" style="color: red;"></i></h4></center>
        <div class="col-md-12">
            <div class="d-flex col-md-12">
               <table class="table table-striped">
                  <thead>
                     <tr>
                        <th>SL</th>
                        <th>File Name</th>
                        <th>Deleted Date</th>
                        <th>Action</th>
                     </tr>
                  </thead>
                  <tbody>
                  <?php
                     $i = 1;
                     $result = $con->query("SELECT signoff_files_log.id id, deletedDate, file, program.program_name FROM signoff_files_log inner join workspace on workspace.id = workspace_id inner join program on program.id = prog_id where status = 1 and workspace_id = $wid");
                     if($result->num_rows < 1){
                        ?>
                        <tr>
                           <td colspan="4">No record found</td>
                        </tr>
                        <?php
                    }
                    else{
                        while($row = $result->fetch_assoc()){
                        ?>
                        <tr>
                            <td><?php echo $i++; ?></td>
                            <td><?php echo $row['file']; ?></td>
                            <td><?php echo $row['deletedDate']; ?></td>
                            <td><a href="#" class="recoverFileSignOff" id="<?php echo $row['id']; ?>"><i class="fas fa-fw fa-redo"></i></a><a href="#" class="deleteFileSignOff" id="<?php echo $row['id']; ?>"><i class="fas fa-fw fa-trash"></i></a></td>
                        </tr>
                        <?php
                        }
                    }
                  ?>
                  </tbody>
               </table>
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

    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>
    <!-- Page level custom scripts -->
    <script src="js/custom.js"></script>
    <!-- MULTISELECT JS -->
    <script src="js/multiselect-master/dist/js/multiselect.js"></script>
    <script>
    $(document).ready(function() {

        document.getElementsByTagName("html")[0].style.visibility = "visible";

        let darkmode = <?php echo $_SESSION['darkmode']; ?>;
        if(darkmode)
        {
            document.documentElement.classList.toggle('dark-mode');
            $("#customSwitch1").attr("checked","checked");
        }
        else{
            document.documentElement.classList.remove('dark-mode');
            $("#customSwitch1").removeAttr('checked');
        }

        $(document).on('click', '.recoverFileSignOff', function () {
            let id = $(this).attr("id");
            $.ajax({
                url: "recoverDeleteFileAjax.php",
                type: "POST",
                data: {
                    id: id,
                    type: '1'
                },
                success: function(data){
                    let responseText = data == 1?'File is recovered':'File is not recovered'
                    data = data == 1?'success':'error'
                    swal({
                        icon: data,
                        text: responseText,
                    }).then(function (isConfirm) {
                        if (isConfirm) {
                        window.location.reload();
                        }
                    });
                }
            });
        });

        $(document).on('click', '.deleteFileSignOff', function () {
            let id = $(this).attr("id");
            $.ajax({
                url: "recoverDeleteFileAjax.php",
                type: "POST",
                data: {
                    id: id,
                    type: '0'
                },
                success: function(data){
                    let responseText = data == 1?'File is deleted permanently':'File is not deleted'
                    data = data == 1?'success':'error'
                    swal({
                        icon: data,
                        text: responseText,
                    }).then(function (isConfirm) {
                        if (isConfirm) {
                        window.location.reload();
                        }
                    });
                }
            });
        });

        // $(document).on('click', '.recoverFileAccountingEstimates', function () {
        //     let id = $(this).attr("id");
        //     $.ajax({
        //         url: "recoverFileAjax.php",
        //         type: "POST",
        //         data: {
        //             id: id,
        //             type: '2'
        //         },
        //         success: function(data){
        //             let responseText = data == 1?'File is recovered':'File is not recovered'
        //             data = data == 1?'success':'error'
        //             swal({
        //                 icon: data,
        //                 text: responseText,
        //             }).then(function (isConfirm) {
        //                 if (isConfirm) {
        //                 window.location.reload();
        //                 }
        //             });
        //         }
        //     });
        // });

        // $(document).on('click', '.recoverFileInsignificant', function () {
        //     let id = $(this).attr("id");
        //     $.ajax({
        //         url: "recoverFileAjax.php",
        //         type: "POST",
        //         data: {
        //             id: id,
        //             type: '3'
        //         },
        //         success: function(data){
        //             let responseText = data == 1?'File is recovered':'File is not recovered'
        //             data = data == 1?'success':'error'
        //             swal({
        //                 icon: data,
        //                 text: responseText,
        //             }).then(function (isConfirm) {
        //                 if (isConfirm) {
        //                 window.location.reload();
        //                 }
        //             });
        //         }
        //     });
        // });

        // $(document).on('click', '.recoverFileMateriality', function () {
        //     let id = $(this).attr("id");
        //     $.ajax({
        //         url: "recoverFileAjax.php",
        //         type: "POST",
        //         data: {
        //             id: id,
        //             type: '4'
        //         },
        //         success: function(data){
        //             let responseText = data == 1?'File is recovered':'File is not recovered'
        //             data = data == 1?'success':'error'
        //             swal({
        //                 icon: data,
        //                 text: responseText,
        //             }).then(function (isConfirm) {
        //                 if (isConfirm) {
        //                 window.location.reload();
        //                 }
        //             });
        //         }
        //     });
        // });
    });
    </script>
</body>

</html>