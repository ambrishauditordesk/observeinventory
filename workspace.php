<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
    include 'dbconnection.php';
    if(!isset($_SESSION)){
       session_start();
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
    
    $clientID = base64_decode($_GET['cid']);
    
    if($con->query("select * from client where id = $clientID")->num_rows == 0){
        header('Location: ./');
    }

    $_SESSION['client_id'] = $clientID;
    
    $clientName = $con->query("select added_by_id, name from client where id = $clientID ")->fetch_assoc();
    
    $_SESSION['cname'] = $clientName['name'];
    $added_by_id = $clientName['added_by_id'];
    $clientName = $clientName['name'];
    
    $name = str_replace(' ', '', $_SESSION['cname']);
    $hasFirmId = false;
    if(isset($_SESSION['external']) && $_SESSION['external'] == 1){
        $_SESSION['firm_id'] = $con->query("select firm_details.id id from firm_details inner join firm_user_log on firm_details.id = firm_user_log.firm_id inner join user on firm_user_log.user_id = user.id inner join user_client_log on user.id = user_client_log.user_id where user.accessLevel = 4 and user_client_log.client_id = $clientID")->fetch_assoc()['id'];
        $hasFirmId = true;
    }
    if(isset($_SESSION['role']) && ( $_SESSION['role'] == 1 || $_SESSION['role'] == -1 )){
        $firm_id = $con->query("select firm_details.id id from firm_details inner join firm_user_log on firm_details.id = firm_user_log.firm_id inner join user on firm_user_log.user_id = user.id inner join user_client_log on user.id = user_client_log.user_id where user.accessLevel = 4 and user_client_log.client_id = $clientID");
        if($firm_id->num_rows > 0){
            $_SESSION['firm_id'] = $firm_id->fetch_assoc()['id'];
            $hasFirmId = true;
        }
    }

    if(isset($_SESSION['role']) && !empty($_SESSION['role']) && $_SESSION['role'] > 1 ){
        $firm_id = $con->query("select firm_details.id id from firm_details inner join firm_user_log on firm_details.id = firm_user_log.firm_id inner join user on firm_user_log.user_id = user.id inner join user_client_log on user.id = user_client_log.user_id where user.accessLevel = 4 and user_client_log.client_id = $clientID");
        if($firm_id->num_rows > 0){
            $_SESSION['firm_id'] = $firm_id->fetch_assoc()['id'];
            $hasFirmId = true;
        }
    }

    if($hasFirmId){
        $_SESSION['file_location'] = 'uploads/'.$_SESSION['firm_id'].'/'.$_SESSION['client_id'].$name;
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="icon" href="https://ksacademy.co.in/images/chartered_accountants/ca.png">

    <title> WORKSPACE </title>
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
    <link href="css/pace-theme.css" rel="stylesheet">
    <link href="css/custom.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/uiux.css" rel="stylesheet" type="text/css">
    
    <!-- JQuery CDN -->
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>

    <!-- Datatable CDN -->
    <link href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css" rel="stylesheet">
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous"></script>

</head>

<body style="overflow-y: scroll" oncontextmenu="return false">

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
            <li class="nav-item d-flex">
            <label class="d-flex justify-content-center align-items-center mt-2"><span class="helpDesign help_2">2</span></label>
                <?php
                if($_SESSION['role'] != 5){
                    ?>
                    <a class="nav-link d-flex align-items-center" href="admin/clientList">
                        <img class="nav-icon" src="Icons/Group 3.svg"/>&nbsp;&nbsp;
                        <span>Clients List</span>
                    </a>
                    <?php
                }
                ?>
            </li>
            <div class="d-flex justify-content-between align-items-center">
                <label class="d-flex justify-content-center align-items-center mt-2"><span class="helpDesign help_4">4</span></label>
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
                    <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                        <?php 
                            if($_SESSION['role'] == '-1' || $_SESSION['role'] == '1'){
                            ?>
                                <a class="dropdown-item" href="#"><i class="fas fa-user-tie hue" style="color:blue;"></i><?php echo $_SESSION['name']; ?></a>
                                <a class="dropdown-item" href="#"><i class="fas fa-signature hue" style="color:blue;"></i><?php echo $_SESSION['signoff']; ?></a>
                                <a class="dropdown-item" href="#"><i class="fas fa-at hue" style="color:blue;"></i><?php echo $_SESSION['email']; ?></a>
                            <?php
                            }   
                            elseif($_SESSION['role'] == 5){
                                ?>
                                <a class="dropdown-item" href="#"><i class="fas fa-user-tie hue" style="color:blue;"></i><?php echo $_SESSION['name']; ?></a>
                                <a class="dropdown-item" href="#"><i class="fas fa-at hue" style="color:blue;"></i><?php echo $_SESSION['email']; ?></a>
                                <a class="dropdown-item" href="#"><i class="fas fa-briefcase hue" style="color:blue;"></i>Firm Name -<?php echo $_SESSION['firm_details']['firm_name']; ?></a>
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
            </div>
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
                <div class="dash">
                    <img class="sidenav-icon" src="Icons/pie-chart.svg" style="width:24px !important; height:24px !important;"/> &nbsp;
                    Workspace
                </div>
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
                            <label class="d-flex justify-content-center align-items-center mt-2"><span class="helpDesign help_5">5</span></label>
                        </div>
                        <div id="helpButton" class="settings-items">
                            <a href="#" class="text-decoration-none"><img class="sidenav-icon" src="Icons/help-circle.svg" style="width:24px !important; height:24px !important;"/> &nbsp;
                            Help</a>
                        </div>
                    </div>
                    <?php
                        }
                    ?>
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="logout"><button type="button" class="btn btn-primary"><i class="fas fa-sign-out-alt"></i> Logout</button></a>
                        <label class="d-flex justify-content-center align-items-center mt-2"><span class="helpDesign help_6">6</span></label>
                    </div>
                </div>
        </div>
    </div>

    <div class="mar">
        <!-- HEADER -->
        <div id="header">
            <div class="container-fluid shadow border border-bottom" stickylevel="0" style="z-index:1200;">
                <div class="row pt-1">
                    <div class="col-md-4">
                        <div class="ml-2 font-1 h3 py-1 d-inline-block float-left"></div>
                    </div>
                    <div class="col-md-4 text-center font-2 getContent" href="admin/clientList">
                        <h3><?php echo strtoupper($clientName . " Workspace"); ?></h3>
                    </div>    
                </div>
            </div>
        </div>
        <br>

        <!-- ADD WORKSPACE BUTTON -->
        <?php 
            if($_SESSION['role'] != 3 && $_SESSION['external'] != 1){
        ?>
        <div class = "row justify-content-md-center" style="width: 100% !important;">   
            <div class="col-xl-3 col-md-6 mb-4 ">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <a class="nav-link" href="#" data-toggle="modal" data-target="#addWorkspaceModal">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        <span>Add Workspace</span>
                                    </div>
                                </div>
                                <div class="col-auto">  
                                    <i class="fas fa-fw fa-user-plus fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </a>
                        <label class="d-flex justify-content-center align-items-center mt-2"><span class="helpDesign help_1">1</span></label>
                    </div>
                </div>
            </div>
        </div>  
        <?php } 
        ?>
        <!-- TABLE -->
            <div class="container pt-4">
                <div class="row">
                    <div class="card-body" style="width:10px;">
                        <div class="table-responsive" style="border-radius: 12px !important;">
                            <div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <table id="workspaceTable" class="table display table-bordered table-striped" style="border-collapse: inherit !important;">
                                            <thead>
                                                <tr>
                                                    <th scope="col shadow-remove">From</th>
                                                    <th scope="col shadow-remove">To</th>
                                                    <th scope="col shadow-remove">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php   
                                                
                                                if($_SESSION['external'] != 1){
                                                    $query = "Select * from workspace where client_id = $clientID";
                                                }
                                                else{
                                                    $query = "Select workspace.* from workspace inner join accounts_log on accounts_log.workspace_id = workspace.id where client_id = $clientID and client_contact_id = ".$_SESSION['id'];
                                                }

                                                $result = $con->query($query);
                                                if($result->num_rows > 0){
                                                    while ($row = $result->fetch_assoc()) {
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $row['datefrom']; ?></td>
                                                            <td><?php echo $row['dateto']; ?></td>
                                                            <td>
                                                            <?php
                                                            if(!$row['freeze']){
                                                            ?>
                                                                <a href="clientDashboard?<?php echo base64_encode(md5($clientName)); ?>&gid=<?php echo base64_encode(md5($clientName)); ?>&fid=<?php echo base64_encode(md5($clientName)); ?>&eid=<?php echo base64_encode(md5($clientName)); ?>&cid=<?php echo base64_encode(md5($clientName)); ?>&bid=<?php echo base64_encode(md5($clientName)); ?>&aid=<?php echo base64_encode(md5($clientName)); ?>&zid=<?php echo base64_encode(md5($clientName)); ?>&yid=<?php echo base64_encode(md5($clientName)); ?>&wid=<?php echo base64_encode($row['id']); ?>&xid=<?php echo base64_encode(md5($clientName)); ?>" class="icon-hide">
                                                                    <img class="hue" src="Icons/edit-1.svg"><img class="hue" src="Icons/edit-2.svg">
                                                                </a>
                                                            <?php
                                                            }
                                                            else{
                                                                if($_SESSION['role'] == '1' || $_SESSION['role'] == '-1' || $_SESSION['role'] == '4'){
                                                                ?>
                                                                    <a id="<?php echo $row['id']; ?>" class="freeze" href="#"><i class="fas fa-unlock"></i></a>
                                                                <?php
                                                                }
                                                                else{
                                                                    ?>
                                                                    <span class="badge badge-danger">Locked!</span>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                                <label class="d-flex justify-content-center align-items-center mt-2"><span class="helpDesign help_3">3</span></label>
                                                                
                                                            </td>
                                                        </tr>
                                                    <?php
                                                    }
                                                }
                                                else{
                                                    ?>
                                                    <tr><td colspan="3">No workspace is available.</td></tr>
                                                    <?php
                                                }  
                                            ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        <div id = "helpDescriptionTop" class="d-flex justify-content-center">
            <div id="helpDescription" class="col-md-11">
                <div class="card" style="border: 4px solid rgb(134, 189, 255, 0.65) !important;box-shadow: 0px 0px 20px 1px rgba(0,0,0,0.5);">
                    <div class="card-body">
                        <button type="button" class="close" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <div id="help_1">
                            <p>1. Add workspace: For every client your can create multiple workspace based on the period of your engagement.</p>
                            <p>Once you click on add workspace and enter the dates you will be able to access the Audit dashboard.</p><br>
                            <p>Once you click done your workspace is now ready to use.</p>
                            <p>You can not delete a workspace once created. It can only be freezed out by using the freeze workspace feature.</p>
                            <p> Only firm administrator can unlock a workspace once it’s been freeze by the audit team.</p>
                        </div>
                        <div id="help_2">
                            <p>2. Client list: Will take you to the main page where the list of all clients allocated to you is present.</p>
                        </div>
                        <div id="help_3">
                            <p>3. Action: You can access the respective workspace using this button and enter the audit dashboard</p>
                        </div>
                        <div id="help_4">
                            <p>4. Profile: User profile reflects brief details about the user and can be edited by firm administrator.</p>
                        </div>
                        <div id="help_5">
                            <p>5. Settings – Your Settings are personalized based on your role in your firm and can be accessed at all times for chat, email and reaching out to a specialist for any help.</p> 
                        </div>
                        <div id="help_6">
                            <p>6. Log out- Simply use this button to log out of your firm workspace and client list.</p>
                        </div>
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
                    <span><strong><span style="color: #02519c;">Luthra & Luthra LLP</span>&nbsp;&copy;
                    <?php echo date("Y"); ?></strong></span>
                </div>
            </div>
        </footer>

        <!--ADD WORKSPACE -->
        <div class="modal fade" id="addWorkspaceModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered  modal-size" role="document">
                <div class="modal-content">
                    
                    <form action="addWorkspace" method="post" id="addWorkspace">
                        <div class="modal-body">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Workspace for Financial Year<h5>
                                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group ">
                                        <label for="name">From</label>
                                        <input type="date" class="form-control" min='1970-01-01' max='2100-12-12' name="from" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group ">
                                        <label for="name">To</label>
                                        <input type="date" class="form-control" min='1970-01-01' max='2100-12-12' name="to" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer  d-flex align-items-center justify-content-center">
                            <input class="btn btn-primary" type="submit" id="" value="Done">
                            <input type= "hidden" name = "clientID" value = "<?php echo $clientID; ?>" >
                        </div>
                    </form>
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


    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous"></script>
    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>
    <!-- Page level custom scripts -->
    <script src="js/custom.js"></script>
    <script>

    $(document).ready(function(){

        document.getElementsByTagName("html")[0].style.visibility = "visible";

        let darkmode = <?php echo $_SESSION['darkmode']; ?>;
        if(darkmode)
        {
            document.documentElement.classList.toggle('dark-mode');
            
        }
        else if(!darkmode){
            document.documentElement.classList.remove('dark-mode');
        }

        $("#helpDescription > div > div > .close").click(function(e){
            $(".helpDesign, #helpDescription").toggle();
        });

        $(".helpDesign, #helpDescription").hide();

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

        $(document).on('click','.freeze',function(e){
            $.ajax({
                url: 'freeze.php',
                type: 'POST',
                data: {
                    id: $(this).attr("id"),
                    freeze: 0
                },
                success: function(data){
                    if (data) {
                    swal({
            closeOnClickOutside: false,
                        icon: "success",
                        text: "Workspace unlocked!",
                    }).then(function (isConfirm) {
                        if (isConfirm) {
                            window.location.href = <?php echo "'workspace?gid=".base64_encode(md5(trim($_SESSION['client_id'])))."&xid=".base64_encode(md5(trim($_SESSION['client_id'])))."&yid=".base64_encode(md5(trim($_SESSION['client_id'])))."&zid=".base64_encode(md5(trim($_SESSION['client_id'])))."&aid=".base64_encode(md5(trim($_SESSION['client_id'])))."&sid=".base64_encode(md5(trim($_SESSION['client_id'])))."&cid=".base64_encode(trim($_SESSION['client_id']))."'"?>;
                        }
                    });
                    }
                }
            });
        });

    });

    $(document).ready(function(){
        var i=1;
        b = i-1;
        $("#add_row").click(function () {
                $('#addr' + i).html($('#addr' + b).html()).find('td:first-child');
                $('#tab_logic').append('<tr id="addr' + (i + 1) + '"></tr>');
            i++;
        });
        //Delete Row Function for sales add form
        $("#delete_row").click(function () {
            if (i > 1) {
                $("#addr" + (i - 1)).html('');
                i--;
            }
        });
    });
    </script>