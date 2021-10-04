<?php

require('config.php');
require('razorpay-php/Razorpay.php');
if(!isset($_SESSION)){
       session_start();
    }

// Create the Razorpay Order

use Razorpay\Api\Api;

$api = new Api($keyId, $keySecret);

//
// We create an razorpay order using orders api
// Docs: https://docs.razorpay.com/docs/orders
//

$id = $_POST['id'];
$name = $_POST['name'];
$email = $_POST['email'];
$orderId = 'AD-'.rand();
$amountCheck = $_POST['amountCheck'];

if($amountCheck == 1){
  $noOfWorkspace = $_POST['subscription'];
	$amount = $_POST['amount'];
}
else{
  $firmStorage = $_POST['firmStorage'];
  $amount = $_POST['storageAmount'];
}


$orderData = [
    'receipt'         => 3456,
    'amount'          => $amount * 100, // 2000 rupees in paise
    'currency'        => 'INR',
    'payment_capture' => 1 // auto capture
];

$razorpayOrder = $api->order->create($orderData);

$razorpayOrderId = $razorpayOrder['id'];

$_SESSION['razorpay_order_id'] = $razorpayOrderId;
$displayAmount = $amount = $orderData['amount'];

if ($displayCurrency !== 'INR')
{
    $url = "https://api.fixer.io/latest?symbols=$displayCurrency&base=INR";
    $exchange = json_decode(file_get_contents($url), true);

    $displayAmount = $exchange['rates'][$displayCurrency] * $amount / 100;
}

$data = [
    "key"               => $keyId,
    "amount"            => $amount,
    "name"              => "Auditor's Desk",
    "description"       => "The Digital Audit Workspace",
    "image"             => "https://s29.postimg.org/r6dj1g85z/daft_punk.jpg",
    "prefill"           => [
    "name"              => $name,
    "email"             => $email,
    "contact"           => "",
    ],
    "notes"             => [
    "address"           => "Hello World",
    "merchant_order_id" => $orderId,
    ],
    "theme"             => [
    "color"             => "#254eda"
    ],
    "order_id"          => $razorpayOrderId,
];

if ($displayCurrency !== 'INR')
{
    $data['display_currency']  = $displayCurrency;
    $data['display_amount']    = $displayAmount;
}

$json = json_encode($data);

?>

<form action="verify.php" method="POST">
  <script
    src="https://checkout.razorpay.com/v1/checkout.js"
    data-key="<?php echo $data['key']?>"
    data-amount="<?php echo $data['amount']?>"
    data-currency="INR"
    data-name="<?php echo $data['name']?>"
    data-image="<?php echo $data['image']?>"
    data-description="<?php echo $data['description']?>"
    data-prefill.name="<?php echo $data['prefill']['name']?>"
    data-prefill.email="<?php echo $data['prefill']['email']?>" 
    data-prefill.contact="<?php echo $data['prefill']['contact']?>"
    data-notes.shopping_order_id="<?php echo 'AD-'.rand(); ?>"
    data-order_id="<?php echo $data['order_id']?>"
    <?php if ($displayCurrency !== 'INR') { ?> data-display_amount="<?php echo $data['display_amount']?>" <?php } ?>
    <?php if ($displayCurrency !== 'INR') { ?> data-display_currency="<?php echo $data['display_currency']?>" <?php } ?>
  >
  </script>
  <!-- Any extra fields to be submitted with the form but not sent to Razorpay -->
  <input type="hidden" name="firmId" value="<?php echo $id; ?>">
  <input type="hidden" name="firmName" value="<?php echo $name; ?>">
  <input type="hidden" name="firmEmail" value="<?php echo $email; ?>">
  <input type="hidden" name="orderID" value="<?php echo $orderId; ?>">
  <input type="hidden" name="amountCheck" value="<?php echo $amountCheck; ?>">
  <input type="hidden" name="noOfWorkspace" value="<?php echo $noOfWorkspace; ?>">
  <input type="hidden" name="firmStorage" value="<?php echo $firmStorage; ?>">
  <input type="hidden" name="amount" value="<?php echo $amount; ?>">

</form>

<script>  
  let razorpay_button = document.querySelector('.razorpay-payment-button');
  razorpay_button.style.visibility = 'hidden';
  razorpay_button.click();
</script>
