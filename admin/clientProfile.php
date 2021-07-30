<?php
    include '../dbconnection.php';
    session_start();

    if (isset($_SESSION['external']) && !empty($_SESSION['external']) && $_SESSION['external'] == 1){
        $checkAccess = $con->query("select id from accounts_log where client_contact_id = ".$_SESSION['id'])->num_rows;
        if($checkAccess){
            $clientName = 1;
            $location =  base64_encode(md5($clientName)).'&gid='. base64_encode(md5($clientName)).'&fid='. base64_encode(md5($clientName)).'&eid='.base64_encode(md5($clientName)).'&cid='.base64_encode($_SESSION['external_client_id']);
            header('Location: ../workspace?vid='.$location);
        }
        else{
            header("Location: ../logout");
        }
    }

    if (!isset($_SESSION['email']) && empty($_SESSION['email'])) {
        header("Location: ../login");
    }
    if (isset($_SESSION['role']) && !empty($_SESSION['role']) && $_SESSION['role'] == '3') {
        header('Location: ../login');
    }
    $clientID = $_GET['cid'];
    $clientName = $con->query("select name from client where id = $clientID ")->fetch_assoc()["name"];
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

    <!-- Custom fonts for this template-->
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../css/custom.css" rel="stylesheet">
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/uiux.css" rel="stylesheet" type="text/css">

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
            <li class="nav-item d-flex">
                <a class="nav-link d-flex align-items-center" href="clientList">
                    <img class="nav-icon" src="../Icons/Group 3.svg"/>&nbsp;&nbsp;
                    <span>Clients List</span>
                </a>
            </li>
            <li class="nav-item d-flex" style="background-color: rgba(232,240,255,1); border-radius: 15px;">
                <span class="nav-icon d-flex align-items-center" style="padding: 0 0 0 10px !important;">
                    <?php
                        $img_query = $con->query("SELECT * FROM user WHERE id = ".$_SESSION['id']." and img != ''");
                        $row = $img_query->fetch_assoc();
                    ?>
                    <img class = "profilePhoto" src="../images/<?php echo $row['img']; ?>">
                </span>
                <a class="nav-link d-flex align-items-center" href="#" id="userDropdown"
                    role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span>
                        <?php echo $_SESSION['name']; ?>
                        <img class="nav-icon" src="../Icons/Group 6.svg" style="width:15px !important;"/>
                    </span>
                </a>
                <!-- Dropdown - User Information -->
                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                    <!-- <a class="dropdown-item" href="#" data-toggle="modal" data-target="#changePasswordModal">
                            <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                            Change Password
                        </a>
                        <div class="dropdown-divider"></div> -->
                </div>
            </li>
        </ul>
    </nav>

    <!-- SideBar -->
    <div class="sidenav">
        <div class="side-header">
            <img class="sidenav-icon" src="../Icons/Group-1.png"/> &nbsp;
           
        </div>

        <div class="side-body">
            <div class="dash">
                <img class="sidenav-icon" src="../Icons/pie-chart.svg" style="width:24px !important; height:24px !important;"/> &nbsp;
                Profile
			</svg>
            </div>
            <div class="settings">
                <span class="settings-items">
                    <a href="../settings" class="text-decoration-none">
                        <img class="sidenav-icon" src="Icons/settings.svg" style="width:24px !important; height:24px !important;"/> &nbsp;Settings
                    </a>
                </span>
                <br>
                <span class="settings-items">
                    <img class="sidenav-icon" src="../Icons/help-circle.svg" style="width:24px !important; height:24px !important;"/> &nbsp;
                    Help
                </span>
            </div>
        </div>

        <div class="side-footer">
            <button type="button" class="btn btn-primary"><i class="fas fa-sign-out-alt"></i> Logout</button>
        </div>
    </div>

    <div class="mar">
        <!-- HEADER -->
        <div id="header">
            <div class="container-fluid shadow border-bottom" stickylevel="0" style="z-index:1200;">


                <div class="row pt-1">
                    <div class="col-md-4">
                        <!-- <img class="float-left" src="../vendor/img/audit-edge-logo.svg" style="height:45px;"> -->
                        <div class="ml-2 font-1 h3 py-1 d-inline-block float-left"></div>
                    </div>
                    <div class="col-md-4 text-center font-2 getContent" href="clientList">
                        <h3><?php echo strtoupper($clientName . " - PROFILE"); ?></h3>
                    </div>
                </div>
            </div>
        </div><br>

        <div class="row justify-content-md-center" style="margin: 0 !important;">

            <!-- Cards -->
            <!-- MEMBERS -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <a class="nav-link" href="clientMember?cid=<?php echo $clientID;?>">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        <span>MEMBERS</span>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-user-tie fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <!-- EDIT CLIENT PROFILE -->
            <div class="col-xl-3 col-md-6 mb-4">    
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <a class="nav-link" href="#" data-toggle="modal" data-target="#editClientModal">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        <span>EDIT PROFILE</span>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-user-tie fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <!-- DATATABLE -->
            <div class="container pt-4 justify-content-md-center" style="margin: 0 !important;">
                <div class="row">
                    <div class="card-body" style="width:10px;">
                        <div class="table-responsive" style="background-color: white !important; border-radius: 15px !important;">
                            <div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <table id="clientsTable" class="table display table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Sl</th>
                                                    <th scope="col">Name</th>
                                                    <th scope="col">Nickname</th>
                                                    <th scope="col">Date of Incorporation</th>
                                                    <th scope="col">Constitution</th>
                                                    <th scope="col">Industry</th>
                                                    <th scope="col">Address</th>
                                                    <th scope="col">Country</th>
                                                    <th scope="col">State</th>
                                                    <th scope="col">City</th>
                                                    <th scope="col">Pincode</th>
                                                    <th scope="col">PAN Number</th>
                                                    <th scope="col">GST Number</th>
                                                    <th scope="col">TAN Number</th>
                                                    <th scope="col">CIN Number</th>
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

        <!--Edit Client Modal -->
        <div class="modal fade" id="editClientModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <?php 
                    $query = "select * from client where id = '$clientID'";
                ?>
                    <form action="editClientProfile" method="post">
                    <?php
                        $result = $con->query($query);
                        while($row = $result->fetch_assoc()){
                    ?>
                        <div class="modal-body">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Basic Details<h5>
                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <input type="hidden" class="form-control" name="date" value= "<?php echo $row['added_by_date'] ?>" required>
                            <input type="hidden" class="form-control" name="id" value= "<?php echo $row['added_by_id'] ?>" required>
                            <input type="hidden" class="form-control" name="cid" value= "<?php echo $clientID ?>" required>
                            <input type="hidden" class="form-control" name="active" value= "<?php echo $row['active'] ?>" required>
                            <div class="form-group ">
                                <label for="name">Client Name</label>
                                <input type="text" class="form-control" name="clientname" value= "<?php echo $row['name'] ?>" required>
                            </div>
                            <div class="form-group ">
                                <label for="name">Nick Name</label>
                                <input type="text" class="form-control" name="nickname" value= "<?php echo $row['nickname'] ?>">
                            </div>
                            <div class="form-group ">
                                <label for="name">Date of Incorporation/ Birth</label>
                                <input type="date" class="form-control" name="dob" value= "<?php echo $row['incorp_date'] ?>" required>
                            </div>
                            <div class="form-group ">
                                <label for="country">Constitution</label>
                                <select class="form-control" name="constitution" required>
                                    <option>Select Constitution !</option>
                                        <?php
                                            $consQuery = $con->query("select * from constitution");
                                            while ($consResult = $consQuery->fetch_assoc()) {
                                        ?>
                                    <option value="<?php echo $consResult['id']; ?>" <?php if($row['const_id'] == $consResult['id']) echo "selected"; ?>><?php echo $consResult['const']; ?></option>
                                        <?php
                                            }
                                        ?>
                                </select>
                            </div>
                            <div class="form-group ">
                                <label for="country">Industry</label>
                                <select class="form-control" name="industry" required>
                                    <option>Select Industry !</option>
                                    <?php
                                                    $indusQuery = $con->query("select * from industry");
                                                    while ($indusResult = $indusQuery->fetch_assoc()) {
                                                ?>
                                    <option value="<?php echo $indusResult['id']; ?>" <?php if($row['industry_id'] == $indusResult['id']) echo "selected"; ?>><?php echo $indusResult['industry']; ?></option>
                                    <?php
                                                }
                                                ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="name">Address</label>
                                <input type="text" class="form-control" name="add" value= "<?php echo $row['address'] ?>">
                            </div>
                            <div class="form-group">
                                <label for="country">Country</label>
                                <input type="text" class="form-control" name="country" value= "<?php echo $row['country'] ?>">
                            </div>
                            <div class="form-group">
                                <label for="state">State</label>
                                <input type="text" class="form-control" name="state" value= "<?php echo $row['state'] ?>">
                            </div>
                            <div class="form-group">
                                <label for="city">City</label>
                                <input type="text" class="form-control" name="cistatestatety" value= "<?php echo $row['city'] ?>">
                            </div>
                            <div class="form-group ">
                                <label for="name">Pincode</label>
                                <input type="text" class="form-control" name="pincode" value= "<?php echo $row['pincode'] ?>" required>
                            </div>
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Satuatory Information<h5>
                            </div>
                            <div class="form-group ">
                                <label for="name">Pan No.</label>
                                <input type="text" class="form-control" name="pan" value= "<?php echo $row['pan'] ?>" required>
                            </div>
                            <div class="form-group ">
                                <label for="name">GST No.</label>
                                <input type="text" class="form-control" name="gst" value= "<?php echo $row['gst'] ?>" required>
                            </div>
                            <div class="form-group ">
                                <label for="name">TAN No.</label>
                                <input type="text" class="form-control" name="tan" value= "<?php echo $row['tan'] ?>" required>
                            </div>
                            <div class="form-group ">
                                <label for="name">CIN No.</label>
                                <input type="text" class="form-control" name="cin" value= "<?php echo $row['cin'] ?>" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-danger" type="button" data-dismiss="modal">Cancel</button>
                            <input class="btn btn-warning" type="reset" value="Reset">
                            <input class="btn btn-primary" type="submit" value="Done">
                        </div>
                    <?php } ?>
                    </form>
                </div>
            </div>
        </div> 
    </div>


    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Core plugin JavaScript-->
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
    <!-- Custom scripts for all pages-->
    <script src="../js/sb-admin-2.min.js"></script>
    <!-- Page level custom scripts -->
    <script src="../js/custom.js"></script>
    <!-- MULTISELECT JS -->
    <script src="../js/multiselect-master/dist/js/multiselect.js"></script>
    <script>
    $(document).ready(function() {
        get_data();

        let darkmode = <?php echo $_SESSION['darkmode']; ?>;
        if(darkmode)
        {
            document.documentElement.classList.toggle('dark-mode');
            
        }
        else if(!darkmode){
            document.documentElement.classList.remove('dark-mode');
        }

        // $('#lstview').multiselect();
        // $('#lstview_to').multiselect();

    //     $(document).on('click','#submit2',function(e){
    //         e.preventDefault();
    //         var selectedValues = []; 
    //         $("#lstview_to option").each(function(){
    //             selectedValues.push($(this).val()); 
    //         });
    //         var id = $("#memberId").val();
    //         var name = $("#name2").val(); 
            
    //         $("#allocate").modal('hide');
    //         $.ajax({
    //             url: "clientAllocate.php",
    //             type: "POST",
    //             data: {
    //                 memberId:id,
    //                 name:name,
    //                 selectedValues:selectedValues
    //             },
    //             success: function(data){    
    //                 if (data) {
    //                 swal({
    //                     icon: "success",
    //                     text: "Updated",
    //                 }).then(function(isConfirm) {
    //                     if (isConfirm) {
    //                         location.reload();
    //                     }
    //                 });
    //             } else {
    //                 swal({
    //                     icon: "error",
    //                     text: "Failed!",
    //                 }).then(function(isConfirm) {
    //                     if (isConfirm) {
    //                         location.reload();
    //                     }
    //                 });
    //             }
    //             }
    //         });    
    //     });
    });

    // $(document).on('click', '.editMember', function() {
    //     var id = $(this).attr("id");
    //     $("#editModal #active1 > option:selected").removeAttr('selected');
    //     $.ajax({
    //         url: "editMemberFetchAjax.php",
    //         type: "POST",
    //         data: {
    //             id: id
    //         },
    //         success: function(data) {
    //             obj = JSON.parse(data);
    //             id = obj.id;
    //             $("#editModal #name1").val(obj.name);
    //             $("#editModal #email1").val(obj.email);
    //             $("#editModal #role1").val(obj.accessLevel);
    //             $("#editModal #active1 option[value=" + obj.active + "]").attr(
    //                 'selected', 'selected');
    //             $("#editModal #signoff1").val(obj.signoff_init);
    //             $("#editModal").modal('show');
    //         }
    //     });
    // });


    function get_data() {
        var dataTable = $('#clientsTable').DataTable({
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
            "ajax": {
                url: "clientFetchAjax.php",
                type: "POST",
                data: {
                    cid: <?php echo $clientID; ?>
                }
            }
        });
    }

    // $(document).on('click', '#submit1', function(e) {
    //     e.preventDefault();
    //     var email = $("#email1").val();
    //     var role = $("#role1").val();
    //     var active = $("#active1").val();
    //     var signoff = $("#signoff1").val();
    //     $("#editModal").modal('hide');

    //     $.ajax({
    //         url: "editAMember.php",
    //         type: "POST",
    //         data: {
    //             email: email,
    //             role: role,
    //             active: active,
    //             signoff: signoff
    //         },
    //         success: function(data) {
    //             if (data) {
    //                 swal({
    //                     icon: "success",
    //                     text: "Updated",
    //                 }).then(function(isConfirm) {
    //                     if (isConfirm) {
    //                         location.reload();
    //                     }
    //                 });
    //             } else {
    //                 swal({
    //                     icon: "error",
    //                     text: "Failed!",
    //                 }).then(function(isConfirm) {
    //                     if (isConfirm) {
    //                         location.reload();
    //                     }
    //                 });
    //             }
    //         }
    //     });


    // });

    // $(document).on('click', '.allocate', function() {
    //     var id = $(this).attr("id");
    //     $.ajax({
    //         url: "clientMemberFetchAjax.php",
    //         type: "POST",
    //         data: {
    //             id: id
    //         },
    //         success: function(data) {
    //             obj = JSON.parse(data);
    //             id = obj.id;
    //             $("#allocate #name2").val(obj.name);
    //             $("#allocate #memberId").val(obj.id);
    //             fromSelect(id);
    //             toSelect(id);
    //             $("#allocate").modal('show');
    //         }
    //     });

    //     function fromSelect(id) {
    //         $("#allocate #lstview").empty();
    //         $.ajax({
    //             url: "fromClientAjax.php",
    //             type: "POST",
    //             data: {
    //                 id: id
    //             },
    //             success: function(data) {
    //                 //console.log(data);
    //                 cObj = JSON.parse(data);
    //                 for (var i = 0; i < cObj.length; i++) {
    //                     $("#allocate #lstview").append('<option value="' + cObj[i].id + '">' + cObj[i].name + '</option>');
    //                 }
    //             }
    //         });
    //     }

    //     function toSelect(id) {
    //         $("#allocate #lstview_to").empty();
    //         $.ajax({
    //             url: "toClientAjax.php",
    //             type: "POST",
    //             data: {
    //                 id: id
    //             },
    //             success: function(data) {
    //                 cObj = JSON.parse(data);
    //                 for (var i = 0; i < cObj.length; i++) {
    //                     $("#allocate #lstview_to").append('<option value="' + cObj[i].id + '">' + cObj[i].name + '</option>');
    //                 }
    //             }
    //         });
    //     }

    // });

    // $('#registerSubmit').on('click', function(e) {
    //     e.preventDefault();
    //     var name = $("#name").val();
    //     var email = $("#email").val();
    //     var password = $("#password").val();
    //     var role = $("#role").val();
    //     var signoff = $("#signoff").val();
    //     $.ajax({
    //         url: "addMember.php",
    //         type: "POST",
    //         data: {
    //             name: name,
    //             email: email,
    //             password: password,
    //             role: role,
    //             signoff: signoff
    //         },
    //         success: function(response) {
    //             if (response) {
    //                 swal({
    //                     icon: "success",
    //                     text: name + " Added",
    //                 }).then(function(isConfirm) {
    //                     if (isConfirm) {
    //                         location.reload();
    //                     }
    //                 });
    //             } else {
    //                 swal({
    //                     icon: "error",
    //                     text: "Already Exists!",
    //                 }).then(function(isConfirm) {
    //                     if (isConfirm) {
    //                         location.reload();
    //                     }
    //                 });
    //             }
    //         }
    //     });
    // });
    </script>
</body>

</html>