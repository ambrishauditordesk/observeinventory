<!DOCTYPE html>
<html lang="en">
<head>
    <title>Auditors Desk</title>
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

include '../dbconnection.php';
require('config.php');

session_start();

require('razorpay-php/Razorpay.php');
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;

$success = true;

$error = "Payment Failed";

if (empty($_POST['razorpay_payment_id']) === false)
{
    $api = new Api($keyId, $keySecret);

    try
    {
        // Please note that the razorpay order ID must
        // come from a trusted source (session here, but
        // could be database or something else)
        $attributes = array(
            'razorpay_order_id' => $_SESSION['razorpay_order_id'],
            'razorpay_payment_id' => $_POST['razorpay_payment_id'],
            'razorpay_signature' => $_POST['razorpay_signature']
        );

        $api->utility->verifyPaymentSignature($attributes);
    }
    catch(SignatureVerificationError $e)
    {
        $success = false;
        $error = 'Razorpay Error : ' . $e->getMessage();
    }
}

if ($success === true)
{
    $razorpay_order_id = $_SESSION['razorpay_order_id'];
    $razorpay_payment_id = $_POST['razorpay_payment_id'];
    $firmName = $_POST['firmName'];
    $firmEmail = $_POST['firmEmail'];
    $firmID = $_POST['firmId'];
    $orderID = $_POST['orderID'];
    $amountCheck = $_POST['amountCheck'];
    $amount = $_POST['amount'];
    $date = date_format(date_create("now", new DateTimeZone('Asia/Kolkata')), "d-m-Y H:m:s");
    $ser = "../settings";

    $con->query("INSERT INTO payment_details(firm_id, name, email, amount ,order_id, razorpay_order_id, payment_id, status, date) VALUES ('$firmID','$firmName','$firmEmail','$amount','$orderID','$razorpay_order_id','$razorpay_payment_id','success','$date')");  
    
    if($amountCheck == 1){
        $noOfWorkspace = $_POST['noOfWorkspace'];
        $previous_subs = $con->query("select subscribed_workspace from firm_details where id =".$firmID)->fetch_assoc()['subscribed_workspace'];
        $query = $con->query("update firm_details set subscribed_workspace = ($previous_subs + $noOfWorkspace) where id = $firmID");
        
    }
    else{
        $firmStorage = $_POST['firmStorage'];
        $previous_storage = $con->query("select storage from firm_details where id = $firmID")->fetch_assoc()['storage'];
        $query = $con->query("update firm_details set storage = ($previous_storage + $firmStorage) where id = $firmID");
    }

    $html = "<script>
                swal({
            closeOnClickOutside: false,
                    title: 'Payment Successful!',
                    icon: 'success',
                    text: 'Payment ID : {$_POST['razorpay_payment_id']}',
                }).then(function(isConfirm) {
                    if (isConfirm) {
                        window.location.href = '$ser';
                    }
                });
            </script>";
}
else
{
    $html = "<script>
                swal({
            closeOnClickOutside: false,
                    title: 'Payment Failed!',
                    icon: 'error',
                    text: '$error',
                }).then(function(isConfirm) {
                    if (isConfirm) {
                        window.location.href = '$ser';
                    }
                });
            </script>";
}

echo $html;
?>
</body>