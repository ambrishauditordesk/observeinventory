<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="refresh" content="5;url=login" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="">
<meta name="author" content="">

<!-- Custom fonts for this template-->
<link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
<link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

<!-- Custom styles for this template-->
<link href="css/sb-admin-2.min.css" rel="stylesheet">
<link href="css/pace-theme.css" rel="stylesheet">
<link href="css/custom.css" rel="stylesheet">

<!-- JQuery CDN -->
<script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>

<!-- Datatable CDN -->
<link href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css" rel="stylesheet">
<script type="text/javascript" src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>

</head>
<body style="background-image: url('Icons/bgwall.jpg');" oncontextmenu="return false">

<!-- bootstrap js -->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- Core plugin JavaScript-->
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>
<!-- Custom scripts for all pages-->
<script src="js/sb-admin-2.min.js"></script>
<!-- Page level plugins -->
<script src="vendor/chart.js/Chart.min.js"></script>
<!-- Page level custom scripts -->
<script src="js/demo/chart-area-demo.js"></script>
<script src="js/demo/chart-pie-demo.js"></script>
<script src="js/demo/datatables-demo.js"></script>
<script src="js/pace.min.js"></script>
<script src="js/custom.js"></script>

<!-- sweetalert cdn -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"
    integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA=="
    crossorigin="anonymous"></script>

<?php 
    include 'dbconnection.php';
    session_start();
    $ser = $_SERVER['HTTP_REFERER'];

    if ($_POST["vercode"] != $_SESSION["vercode"] OR $_SESSION["vercode"]=='')  {
        echo "<script>
                swal({
                    icon: 'error',
                    text: 'Please Enter a Valid Verification Code!',
                }).then(function(isConfirm) {
                    if (isConfirm) {
                        window.location.href = '$ser';
                    }
                });
            </script>";
    }
    else{
        if (isset($_SESSION['email']) && !empty($_SESSION['email'])){
            if($_SESSION['accessLevel'] == '-1'){
                header("Location: admin/clientList");    
            }
            else{
                header("Location: admin/clientList");
            }
        }
        if(!isset($_POST['email']) && empty($_POST['email']) && !isset($_POST['password']) && empty($_POST['password'])){
            header('Location: index');
        }

        $location = json_decode(file_get_contents("https://geolocation-db.com/json/"),true);
        $ip = $location['IPv4'];
        $json = json_decode(file_get_contents("http://ipinfo.io/$ip/geo"), true);
        $browser = $_SERVER['HTTP_USER_AGENT'];
        $dateTime = date_format(date_create("now", new DateTimeZone('Asia/Kolkata')), "Y-m-d H:i:s");
        $location = $json['city'].', '.$json['region'].', '.$json['country'].' ( '.$json['loc'].' )';
    
        $email = trim($_POST["email"]);
        $con->query("insert into loginlog(email,ip,dateTime,location,browser,status) values('$email','$ip','$dateTime','$location','$browser','')");
        $loginLogId = $con->insert_id;
        echo $loginLogId;
        $pass = trim($_POST["password"]);
        $pass = md5($pass);
        $users = $con->query("SELECT * FROM user WHERE email= '$email' and password= '$pass'");
        
        
        if ($users->num_rows != 0) {
            $usersrow = $users->fetch_assoc();
            if($usersrow['active'] == 1){
                $con->query("update loginlog set status = 'Success' where id = $loginLogId");
                $_SESSION['id'] = $usersrow['id'];
                $_SESSION['name'] = $usersrow['name'];
                $_SESSION['email'] = $usersrow['email'];
                $_SESSION['role'] = $usersrow['accessLevel'];
                $_SESSION['reg_date'] = $usersrow['reg_date'];
                $_SESSION['signoff'] = $usersrow['signoff_init'];
                $_SESSION['darkmode'] = $usersrow['darkmode'];
                $_SESSION['external'] = 0;
                if($usersrow['client_id'] != ''){
                    $_SESSION['external'] = 1;
                    $_SESSION['external_client_id'] = $usersrow['client_id'];
                    $checkAccess = $con->query("select id from accounts_log where client_contact_id = ".$usersrow['id'])->num_rows;
                    if($checkAccess){
                        header('Location: workspace?cid='.$usersrow['client_id']);
                    }
                    else{
                        session_unset();
                        session_destroy();
                        echo "<script>
                                swal({
                                    icon: 'error',
                                    text: 'Access Denied!',
                                }).then(function(isConfirm) {
                                    if (isConfirm) {
                                        window.location.href = '$ser';
                                    }
                                });
                            </script>";
                    }
                }
                else{
                    header('Location: admin/clientList');
                }
            }
            else{
                $con->query("update loginlog set status = 'Access Denied' where id = $loginLogId");
                echo "<script>
                        swal({
                            icon: 'error',
                            text: 'Access Denied!',
                        }).then(function(isConfirm) {
                            if (isConfirm) {
                                window.location.href = '$ser';
                            }
                        });
                    </script>";
            }
        }
        else{
            $con->query("update loginlog set status = 'Failed' where id = $loginLogId");
            echo "<script>
                    swal({
                        icon: 'error',
                        text: 'Wrong UserName or Password!',
                    }).then(function(isConfirm) {
                        if (isConfirm) {
                            window.location.href = '$ser';
                        }
                    });
                </script>";
        }
    } 
?>