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
        .red-color{
            color: red;
            /* color: white; */
            /* background-color: red; */
        }
        .green-color{
            color: green;
            /* color: white; */
            /* background-color: green; */
        }
    </style>
</head>

<body style="overflow-y: scroll; height:100% !important;" oncontextmenu="return false">

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
                    <a href="clientDashboard?qid=<?php echo base64_encode(md5($clientName)); ?>&gid=<?php echo base64_encode(md5($clientName)); ?>&fid=<?php echo base64_encode(md5($clientName)); ?>&eid=<?php echo base64_encode(md5($clientName)); ?>&cid=<?php echo base64_encode($_SESSION['client_id']); ?>&yid=<?php echo base64_encode(md5($clientName)); ?>&bid=<?php echo base64_encode(md5($clientName)); ?>&aid=<?php echo base64_encode(md5($clientName)); ?>&zid=<?php echo base64_encode(md5($clientName)); ?>&jid=<?php echo base64_encode(md5($clientName)); ?>&wid=<?php echo base64_encode($wid); ?>&xid=<?php echo base64_encode(md5($clientName)); ?>"><img class="sidenav-icon" src="Icons/pie-chart.svg" style="width:24px !important; height:24px !important;"/> &nbsp;
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
                                            <a href="subProgram?pid=<?php echo $queryrow['id']; ?>&parent_id=<?php echo $queryrow['parent_id']; ?>&wid=<?php echo $wid; ?>">
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
                <div class="sub-dash" style="margin-top: 1rem !important;">
                    <a href="subProgram?pid=245&parent_id=255&wid=<?php echo $wid; ?>"><img class="sidenav-icon" src="Icons/Group 6.svg" style="width:1rem !important; height:1rem !important; transform: rotate(90deg);"/> &nbsp;
                        Back
                    </a>
                </div>
            </div>
            <div class="settings">
                <div class="settings-items-top-div">
                    <div class="settings-items">
                        <a href="../settings" class="text-decoration-none">
                            <img class="sidenav-icon" src="Icons/settings.svg" style="width:24px !important; height:24px !important;"/> &nbsp;Settings
                        </a>
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
                if ($prog_id != '2' && $prog_id != '20' && $prog_id != '230' && $prog_id != '229' && $prog_id != '12' && $prog_id != '239' && $prog_id != '240' && $prog_id != '247' && $prog_id != '') {
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
                    <?php
                        $img_query = $con->query("SELECT * FROM user WHERE id = ".$_SESSION['id']." and img != ''");
                        if($img_query->num_rows == 1){
                            $row = $img_query->fetch_assoc();
                            ?>
                            <img class = "profilePhoto" src="../images/<?php echo $row['img']; ?>">
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
    
    <div class="mar">
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
            <!-- <a href="subProgram.php?pid=245&parent_id=255&wid=<?php // echo $wid; ?>"><button class="btn btn-primary">Back</button></a> -->
            <!-- Subprogram Body -->
            <div class="row">
                <div class="col-md-12">
                    <div class="container">
                        <div class="row">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <table id="financialBalanceTable" class="table display table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">Sl</th>
                                                            <th scope="col">Account Type</th>
                                                            <th scope="col">Account Class</th>
                                                            <th scope="col">Financial Statement</th>
                                                            <th scope="col">CY Final Balance</th>
                                                            <th scope="col">CY Beg Balance</th>
                                                            <th scope="col">Difference</th>
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

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>
    <script src="js/custom.js"></script>

    <script>
        $(document).ready(function() {
            var dataTable = $('#financialBalanceTable').DataTable({
                "destroy": true,
                "processing": true,
                "serverSide": true,
                "searching": true,
                "order": [],
                "bInfo": false,
                "fnRowCallback": function(nRow, aData, iDisplayIndex) {
                    $("td:first", nRow).html(iDisplayIndex + 1);
                    return nRow;
                },
                "drawCallback": function(settings) {
                        $(".helpDesign, #helpDescription").hide();
                    },
                "ajax":
                $.fn.dataTable.pipeline({
                    url: "financialBalanceFetchAjax.php",
                    type: "POST",
                    data: {wid: <?php echo $wid; ?>},
                    pages: 2 // number of pages to cache
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
            });
        });
    </script>
</body>