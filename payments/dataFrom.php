<?php

include '../dbconnection.php';
session_start();

$id = $_POST['id'];
$name = $_POST['name'];
$add = $_POST['address'];
$email = $_POST['email'];

if($_POST['amountCheck'] == 1)
	$amount = $_POST['amount'];
else
	$amount = $_POST['storageAmount'];

?>

<html>
<head>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css">

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.min.js"></script>
	<script>
		window.onload = function() {
			var d = new Date().getTime();
			document.getElementById("tid").value = d;
		};
	</script>
</head>
<body>
	<form method="post" name="customerData" action="ccavRequestHandler.php">
		<table width="40%" height="100" border='1' align="center"><caption><font size="4" color="blue"><b>Subscription</b></font></caption></table>
			<table width="40%" height="100" align="center">
				<tr>
					<td>Parameter Name:</td><td>Parameter Value:</td>
				</tr>
				<tr>
					<td colspan="2"> Compulsory information</td>
				</tr>
				<tr>
					<td>TID	:</td><td><input class="form-control" type="text" name="tid" id="tid" readonly /></td>
				</tr>
				<tr>
					<td>Merchant Id	:</td><td><input class="form-control" type="text" name="merchant_id" value="436311" readonly/></td>
				</tr>
				<tr>
					<td>Order Id	:</td><td><input class="form-control" type="text" name="order_id" value="12345678" readonly/></td>
				</tr>
				<tr>
					<td>Amount	:</td><td><input class="form-control" type="text" name="amount" value="<?php echo $amount; ?>" readonly/></td>
				</tr>
				<tr>
					<td>Currency	:</td><td><input class="form-control" type="text" name="currency" value="INR"/></td>
				</tr>
				<tr>
					<td>Redirect URL	:</td><td><input class="form-control" type="text" name="redirect_url" value="http://yourfirmaudit.com/AuditSoft/payments/ccavResponseHandler.php"/></td>
				</tr>
			 	<tr>
			 		<td>Cancel URL	:</td><td><input class="form-control" type="text" name="cancel_url" value="http://yourfirmaudit.com/AuditSoft/payments/ccavResponseHandler.php"/></td>
			 	</tr>
			 	<tr>
					<td>Language	:</td><td><input class="form-control" type="text" name="language" value="EN" readonly/></td>
				</tr>
		     	<tr>
		     		<td colspan="2">Billing information(optional):</td>
		     	</tr>
		        <tr>
		        	<td>Billing Name	:</td><td><input class="form-control" type="text" name="billing_name" value="<?php echo $name; ?>"/></td>
		        </tr>
		        <tr>
		        	<td>Billing Address	:</td><td><input class="form-control" type="text" name="billing_address" value="<?php echo $add; ?>"/></td>
		        </tr>
		        <!-- <tr>
		        	<td>Billing City	:</td><td><input type="text" name="billing_city" value="Indore"/></td>
		        </tr>
		        <tr>
		        	<td>Billing State	:</td><td><input type="text" name="billing_state" value="MP"/></td>
		        </tr>
		        <tr>
		        	<td>Billing Zip	:</td><td><input type="text" name="billing_zip" value="425001"/></td>
		        </tr>
		        <tr>
		        	<td>Billing Country	:</td><td><input type="text" name="billing_country" value="India"/></td>
		        </tr>
		        <tr>
		        	<td>Billing Tel	:</td><td><input type="text" name="billing_tel" value="9876543210"/></td>
		        </tr> -->
		        <tr>
		        	<td>Billing Email	:</td><td><input class="form-control" type="text" name="billing_email" value="<?php echo $email; ?>"/></td>
		        </tr>
		        <!-- <tr>
		        	<td colspan="2">Shipping information(optional)</td>
		        </tr>
		        <tr>
		        	<td>Shipping Name	:</td><td><input type="text" name="delivery_name" value="Chaplin"/></td>
		        </tr>
		        <tr>
		        	<td>Shipping Address	:</td><td><input type="text" name="delivery_address" value="room no.701 near bus stand"/></td>
		        </tr>
		        <tr>
		        	<td>shipping City	:</td><td><input type="text" name="delivery_city" value="Hyderabad"/></td>
		        </tr>
		        <tr>
		        	<td>shipping State	:</td><td><input type="text" name="delivery_state" value="Andhra"/></td>
		        </tr>
		        <tr>
		        	<td>shipping Zip	:</td><td><input type="text" name="delivery_zip" value="425001"/></td>
		        </tr>
		        <tr>
		        	<td>shipping Country	:</td><td><input type="text" name="delivery_country" value="India"/></td>
		        </tr>
		        <tr>
		        	<td>Shipping Tel	:</td><td><input type="text" name="delivery_tel" value="9876543210"/></td>
		        </tr>
		        <tr>
		        	<td>Merchant Param1	:</td><td><input type="text" name="merchant_param1" value="additional Info."/></td>
		        </tr>
		        <tr>
		        	<td>Merchant Param2	:</td><td><input type="text" name="merchant_param2" value="additional Info."/></td>
		        </tr>
				<tr>
					<td>Merchant Param3	:</td><td><input type="text" name="merchant_param3" value="additional Info."/></td>
				</tr>
				<tr>
					<td>Merchant Param4	:</td><td><input type="text" name="merchant_param4" value="additional Info."/></td>
				</tr>
				<tr>
					<td>Merchant Param5	:</td><td><input type="text" name="merchant_param5" value="additional Info."/></td>
				</tr> -->
				<!-- <tr>
					<td>Promo Code	:</td><td><input type="text" name="promo_code" value=""/></td>
				</tr>
				<tr>
					<td>Vault Info.	:</td><td><input type="text" name="customer_identifier" value=""/></td>
				</tr> -->
		        <tr>
		        	<td></td><td><INPUT TYPE="submit" class="btn btn-primary" value="Proceed"></td>
		        </tr>
	      	</table>
	      </form>
	</body>
</html>


