<?php
include 'dbconnection.php';
include 'moneyFormatter.php';
session_start();

$account = base64_decode($_GET['account']);
$wid = base64_decode($_GET['wid']);

?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <title> <?php echo strtoupper($_SESSION['name'] . " Financial Statement"); ?> </title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">

        <!-- Custom fonts for this template-->
        <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i">
        <link rel="stylesheet" href="https://cdn.datatables.net/plug-ins/1.10.24/features/searchHighlight/dataTables.searchHighlight.css">

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
        <script src="https://cdn.datatables.net/plug-ins/1.10.24/features/searchHighlight/dataTables.searchHighlight.min.js"></script>
        <script src="https://bartaz.github.io/sandbox.js/jquery.highlight.js"></script>
        <!-- SweetAlert -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous"></script>
    </head>

    <body style="overflow-y: scroll" oncontextmenu="return true">

        <!-- Navbar -->
        <nav class="navbar sticky-top navbar-expand-lg navbar-mainbg border-bottom">
            <!-- Topbar Navbar -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item d-flex" style="background-color: rgba(232,240,255,1); border-radius: 15px;">
                    <!-- <label class="d-flex justify-content-center align-items-center mt-2"><span class="helpDesign help_3">3</span></label> -->
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
                    <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown" style="font-size:small;">
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
                    <img class="sidenav-icon" src="Icons/Group-1.png"/> &nbsp;
                   
                </div>
            </div>
            <div class="side-footer">
                <div class="side-body">
                    <div class="dash">
                        <img class="sidenav-icon" src="Icons/pie-chart.svg" style="width:24px !important; height:24px !important;"/> &nbsp;
                        Dashboard
                        </svg>
                    </div>
                </div>
                <div class="settings">
                    <div class="settings-items-top-div">
                        <div class="settings-items d-flex justify-content-between align-items-center">
                            <a href="settings" class="text-decoration-none">
                                <img class="sidenav-icon" src="Icons/settings.svg" style="width:24px !important; height:24px !important;"/> &nbsp;Settings
                            </a>
                            <!-- <label class="d-flex justify-content-center align-items-center mt-2"><span class="helpDesign help_7">7</span></label> -->
                        </div>
                        <!-- <div id="helpButton" class="settings-items">
                            <a href="#" class="text-decoration-none"><img class="sidenav-icon" src="Icons/help-circle.svg" style="width:24px !important; height:24px !important;"/> &nbsp;
                            Help</a>
                        </div> -->
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="logout"><button type="button" class="btn btn-primary"><i class="fas fa-sign-out-alt"></i> Logout</button></a>
                        <!-- <label class="d-flex justify-content-center align-items-center mt-2"><span class="helpDesign help_12">12</span></label> -->
                    </div>
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
                            <h3>Financial Statement </h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 text-center p-top">
                <button class="btn bg-violet" id="export2excel">Export</button><br><br>
            </div>
            <div id="financial_statement">
                <div>
                    <center><h4><b><u><?php echo $account; ?></u></b></h4>
                </div>
                <!-- Body -->
                <div class="col-md-12">
                    <div class="d-flex col-md-12">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col shadow-remove">Account Number</th>
                                    <th scope="col shadow-remove">Account Name</th>
                                    <th scope="col shadow-remove">CY Opening Balance</th>
                                    <th scope="col shadow-remove">CY Closing Balance</th>
                                    <th scope="col shadow-remove">Variance(&#8377;)</th>
                                    <th scope="col shadow-remove">Variance(%)</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                $totalCyBegBal = 0;
                                $totalCyFinalBal = 0;
                                $result = $con->query("SELECT account_number, account_name, cy_beg_bal,cy_final_bal FROM trial_balance WHERE financial_statement = '$account' and workspace_id = $wid");
                                if($result->num_rows == 0){
                                    ?>
                                        <tr>
                                            <td colspan="7">No record found</td>
                                        </tr>
                                    <?php
                                }
                                else{
                                    while($row = $result->fetch_assoc()){
                                        $cyBegBal = numberToCurrency($row['cy_beg_bal']);
                                        $cyFinalBal = numberToCurrency($row['cy_final_bal']);
                                        $totalCyBegBal += $row['cy_beg_bal'];
                                        $totalCyFinalBal += $row['cy_final_bal'];
                                    ?>
                                    <tr>
                                        <td><?php echo $row['account_number']; ?></td>
                                        <td style="text-align: left"><?php echo $row['account_name']; ?></td>
                                        <td><?php echo $cyBegBal; ?></td>
                                        <td><?php echo $cyFinalBal; ?></td>
                                        <td><?php echo numberToCurrency($row['cy_final_bal'] - $row['cy_beg_bal']); ?></td>
                                        <td>
                                            <?php
                                                $diffPercentage = 0.00;
                                                if($row['cy_beg_bal'] != 0)
                                                    $diffPercentage = number_format((float)(($row['cy_final_bal']-$row['cy_beg_bal'])/$row['cy_beg_bal'])*100, 2, '.', '');
                                                echo $diffPercentage.'%';
                                            ?>
                                        </td>
                                    </tr>
                                    <?php
                                    }
                                }
                            ?>
                                <tr colspan="4"><td></td></tr>
                                    <tr>
                                        <td colspan="1"></td>
                                        <td style="text-align: center"><h5 style="border-bottom: 1px solid;border-top: 1px solid;">Total</h5></td>
                                        <td style="text-align: center"><h5 style="border-bottom: 1px solid;border-top: 1px solid;"><?php echo numberToCurrency($totalCyBegBal); ?></h5></td>
                                        <td style="text-align: center"><h5 style="border-bottom: 1px solid;border-top: 1px solid;"><?php echo numberToCurrency($totalCyFinalBal); ?></h5></td>
                                    </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <footer class="sticky-footer">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span><strong><span style="color: #8E1C1C;">Auditors Desk </span>&copy;
                        <?php echo date("Y"); ?></strong></span>
                    </div>
                </div>
            </footer>
        </div>
        
        <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
        
        <!-- Core plugin JavaScript-->
        <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous"></script>
        <!-- Custom scripts for all pages-->
        <script src="js/sb-admin-2.min.js"></script>
        <!-- Page level custom scripts -->
        <script src="js/custom.js"></script>
        <!-- For export -->
        <script src="http://cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js"></script> 
        <script>
            $(document).ready(function(){

                document.getElementsByTagName("html")[0].style.visibility = "visible";

                $("#export2excel").click(function(e) { 
                    $("table").table2excel({ 
                        filename: "<?php echo $account;?>.xls"
                    });
                });
            });
        </script>
    </body>
</html>


