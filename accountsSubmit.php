<!DOCTYPE html>
<html lang="en">

<head>
    <title>Audit-EDG</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- sweetalert cdn -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"
        integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA=="
        crossorigin="anonymous"></script>
</head>

<body>
    <?php
    include 'dbconnection.php';
    session_start();
    if (!isset($_SESSION['email']) && empty($_SESSION['email'])) {
        header("Location: ../login");
    }
    $type = array();
    $amount = array();
    $risk = array();
    $import = array();
    $id = array();
    $wid=$_GET['wid'];
    $ser = $_SERVER['HTTP_REFERER'];

    foreach ($_POST['submitData']['type'] as $data) {
        $type[] = $data;
    }
    foreach ($_POST['submitData']['amount'] as $data) {
        $amount[] = $data;
    }
    foreach ($_POST['submitData']['risk'] as $data) {
        $risk[] = $data;
    }
    foreach ($_POST['submitData']['import'] as $data) {
        $import[] = $data;
    }
    foreach ($_POST['submitData']['id'] as $data) {
        $id[] = $data;
    }
    $j = sizeof($type);
    $flag = 0;

    for ($i = 0; $i < $j; $i++) {
        if($con->query("update workspace_log set amount = '$amount[$i]', type='$type[$i]', risk='$risk[$i]', import='$import[$i]' where program_id='$id[$i]' and workspace_id='$wid'") === TRUE)
        {
            $flag=1;
        }
        else
        {
            $flag=0;
        }
    }

    if ($flag) {
        echo "<script>
                swal({
                    icon: 'success',
                    text: 'Updated!',
                }).then(function(isConfirm) {
                    if (isConfirm) {
                        window.location.href = '$ser';
                    }
                });
            </script>";
    } else {
        echo "<script>
                swal({
                    icon: 'error',
                    text: 'Error!',
                }).then(function(isConfirm) {
                    if (isConfirm) {
                        window.location.href = '$ser';
                    }
                });
             </script>";
    }
?>
</body>

</html>