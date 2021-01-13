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

    $description = array();
    $client = array();
    $request = array();
    $date = array();
    $id = array();
    $fn = array();
    $wid = $_GET['wid'];
    $ser = $_SERVER['HTTP_REFERER'];

    // foreach ($_FILES['file'] as $data) {
    //     $fn[] = $data;
    // }
    // var_dump($fn);
    // return;
    //File Upload
    $filePresent = 0;
    $uploadOk = 1;
    //var_dump($_FILES);
    if(!empty($_FILES['file']['name'])){
        $filePresent = 1;
        // File size should be less the 2MB
        if ($_FILES["file"]["size"] > 2000000) {
            $error.= "<p>File Size is greater than 2MB.</p><br>";
            $uploadOk = 0;
        }
        $name = explode(".", $_FILES['file']['name'])[0]."_$id.".explode(".", $_FILES['file']['name'])[1];
        $tmp_name = $_FILES['file']['tmp_name'];
        $path = 'uploads/clientrequest/';
    }

    if($uploadOk) {
        foreach ($_POST['account']['des'] as $data) {
            $description[] = $data;
        }
        foreach ($_POST['account']['client'] as $data) {
            $client[] = $data;
        }
        foreach ($_POST['account']['request'] as $data) {
            $request[] = $data;
        }
        foreach ($_POST['account']['date'] as $data) {
            $date[] = $data;
        }
        foreach ($_POST['account']['id'] as $data) {
            $id[] = $data;
        }
        $j = sizeof($description);

        for ($i = 0; $i < $j; $i++) 
        {
            if($con->query("update accounts_log set description = '$description[$i]', client_contact_id='$client[$i]', request='$request[$i]',documents='$name',date='$date[$i]' where workspace_id='$wid' and accounts_id = '$id[$i]'") === TRUE)
            {
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
            }
            else
            {
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
        }
    }
?>
</body>

</html>