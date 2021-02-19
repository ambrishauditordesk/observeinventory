<?php
include 'dbconnection.php';
session_start();
// if (!isset($_SESSION['email']) && empty($_SESSION['email'])) {
//     header("Location: index");
// }
$clientName = $_SESSION['cname'];
$wid = $_GET['wid'];
$_SESSION['breadcrumb'] = array();
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

    <!-- Custom Fav icon -->
    <!-- <link rel="icon" href="img/atllogo.png" type="image/gif" sizes="16x16"> -->

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="css/custom.css" rel="stylesheet">
    <style>
    *{
        padding: 0;
    }
    body{
        width:100vw;
        height:100vh;
        background-color: #f3f3f3;
    }
    .container-fluid{
        height: 100%;
    }
    #topBarOuterDiv{
        height: 12%;
        border: 1px solid;
    }
    #main{
        height: 100%;
        display: flex;
        justify-content: flex-start;
    }
    #leftSideBar{
        height: 100%;
        border: 1px solid black;
        padding: 10px;
        background-color: #fff;
    }
    #mainBody{
        height: 100%;
        border: 1px solid black;
        background-color: #000;
    }
    </style>

</head>

<body style="overflow-y: scroll" oncontextmenu="return false">

    <div class="container-fluid">
        <div id="topBarOuterDiv" class="row">
            <div id="topBar" class="col-md-12"></div>
        </div>
        <div id="main" class="row">
            <div id="leftSideBar" class="mr-md-n1 col-md-2"></div>
            <div id="mainBody" class="p-0 col-md-10"></div>
        </div>
    </div>

    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/jstree.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>
            <!-- sweetalert cdn -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous"></script>

    <!-- <script src="js/custom.js"></script> -->
    <script>
    $(document).ready(function() {
        var i = 1;
        b = i - 1;
        $("#add_row").click(function() {
            $('#addr' + i).html($('#addr' + b).html()).find('td:first-child');
            $('#tab_logic').append('<tr id="addr' + (i + 1) + '"></tr>');
            i++;
        });
        //Delete Row Function for sales add form
        $("#delete_row").click(function() {
            if (i > 1) {
                $("#addr" + (i - 1)).html('');
                i--;
            }
        });

        // $(document).on('click','#freeze',function(){
        //     $.ajax({
        //         url: 'freeze.php',
        //         type: 'POST',
        //         data: {id: <?php echo $wid; ?>,freeze: 1},
        //         success: function(data){
        //             if (data) {
        //                     swal({
        //                         icon: "success",
        //                         text: "Thank You for Freezing",
        //                     }).then(function (isConfirm) {
        //                         if (isConfirm) {
        //                             window.location.href = "workspace?cid=<?php echo $_SESSION['client_id']; ?>";
        //                         }
        //                     });
        //                 }
        //         }
        //     })
        // })
    });
    </script>
</body>