<?php
    include 'dbconnection.php';
    if(!isset($_SESSION)){
       session_start();
    }
?>

<head>
    <link rel="icon" href="https://ksacademy.co.in/images/chartered_accountants/ca.png">
 
    <title> <?php echo strtoupper($_SESSION['name'] . " Dashboard"); ?> </title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

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
             ?>
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
                <img class="sidenav-icon" src="Icons/Group-1.jpeg"/> &nbsp;
               
            </div>
        </div>
        <div class="side-footer">
            <div class="side-body">
                
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
                        <!-- <img class="float-left" src="vendor/img/Auditors Deske-logo.svg" style="height:45px;"> -->
                        <div class="ml-2 font-1 h3 py-1 d-inline-block float-left"></div>
                    </div>
                    <div class="col-md-4 text-center font-2 getContent" href="#">
                        <h3>Payment History</h3>
                    </div>
                </div>
            </div>
        </div><br>

        <!-- Body -->        
        <div class="col-md-12">
            <div class="d-flex col-md-12">
               <table class="table table-striped">
                  <thead>
                     <tr>
                        <th>SL</th>
                        <th>Order Id</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Date</th>
                     </tr>
                  </thead>
                  <tbody>
                  <?php
                     $i = 1;
                     $result = $con->query("SELECT * from payment_details where firm_id =". $_SESSION['firm_id']);
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
                            <td><?php echo $row['order_id']; ?></td>
                            <td><?php echo $row['amount']; ?></td>
                            <td><?php echo $row['status']; ?></td>
                            <td><?php echo $row['date']; ?></td>
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
                    <span><strong><span style="color: #02519c;">Luthra & Luthra LLP</span>&nbsp;&copy;
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
</body>