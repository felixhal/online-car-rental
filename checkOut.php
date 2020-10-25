<?php 
//Starts session
session_start();
//Gets the total amount due
$total = $_REQUEST['total'];
//Get the contact details of the user from the forms
$email = $_REQUEST['email'];
$fname = $_REQUEST['fname'];
$lname = $_REQUEST['lname'];
$address1 = $_REQUEST['address1'];
$address2 = $_REQUEST['address2'];
$city = $_REQUEST['city'];
$state = $_REQUEST['state'];
$postcode = $_REQUEST['postcode'];
$payment = $_REQUEST['payment'];
//variable to store an error messages
$error = "";
$valid = "";

//Checks if there there is a submission
if(isset($_REQUEST['submit']))
{
	//php function thats validate if a string is an email address
	if (filter_var($email, FILTER_VALIDATE_EMAIL) == false)
	{
		$error .= "email is not valid email address<br>";
	}
	
	if($error != "")
	{
		$error = "<p>There are the following errors</p><p>$error</p>";
		print "<script>alert(\"Email address is invalid\")</script>";
	}
	else
	{
		//Email fields
		$emailTo = $email;
		$subject = "Order Conformation";
		$content = "Thanks for ordering from Hertz-UTS, the total cost is ".$total;
		$headers = "From: Hertz-UTS";
		
		mail($emailTo, $subject, $content, $headers);
		print "<script>window.location=\"index2.php\"</script>";
		
	}
}
?>
<html>
	<head>
		<title>Home</title>
	</head>
		<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500&display=swap" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed&display=swap" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="home_style.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<style>
		
		.form-container{
		text-align: center;
		width: 100%;
		}
		
		#checkOut{
		font-size:20px;
		font-family:'Poppins', sans-serif;
		}
		
		.form{
		border: none;
		padding: 8px;
		margin-left: auto;
		margin-right: auto;
		font-family:'Poppins', sans-serif;
		font-size: 15px;
		widht: 100%;
		}
		
		input[type=text], select {
		font-family:'Poppins', sans-serif;
		width: 100%;
		padding: 3px 6px;
		margin: 8px 0;
		display: inline-block;
		border: 1px solid #ccc;
		border-radius: 4px;
		box-sizing: border-box;
		}
	</style>
	<body>
	<div class="topnav">
		<a id="logo">Hertz-UTS</a>
	</div>
	<div class="form-container" id="form-container">
		<h3 id="checkOut" style="text-align:center">Check Out</h3>
		<form method="GET">
			<table class="form">
				<tr>
					<td>First Name<span style="color:red">*</span></td>
					<td><input type="text" name="fname" id="fname" onchange="" required></td>
				</tr>
				<tr>
					<td>Last Name<span style="color:red">*</span></td>
					<td><input type="text" name="lname" id="lname" onchange="" required></td>
				</tr>
				<tr>
					<td>Email<span style="color:red">*</span></td>
					<td><input type="text" name="email" id="email" onchange="" required></td>
				</tr>
				<tr>
					<td>Address Line 1<span style="color:red">*</span></td>
					<td><input type="text" name="address1" id="address1" onchange=""required></td>
				</tr>
				<tr>
					<td>Address Line 2<span style="color:red">*</span></td>
					<td><input type="text" name="address2" id="address2" onchange="" required></td>
				</tr>
				<tr>
					<td>City<span style="color:red">*</span></td>
					<td><input type="text" name="city" id="city" onchange="" required></td>
				</tr>
				<tr>
					<td>State<span style="color:red">*</span></td>
					<td>
						<select name="state" id="state" required>
							<option value="New South wales">NSW</option>
							<option value="Australia Capital Terriroty">ACT</option>
							<option value="Victoria">VIC</option>
							<option value="Northern Territory">NT</option>
							<option value="Western Australia">WA</option>
							<option value="South Australia">SA</option>
							<option value="Tasmania">TAS</option>
							<option value="Queensland">QL</option>
						</select>
					</td>
				</tr>
				<tr>
					<td>Post Code<span style="color:red">*</span></td>
					<td><input type="text" name="" id="" onchange="" required></td>
				</tr>
				<tr>
					<td>Payment Type<span style="color:red">*</span></td>
					<td>
						<select name="payment" id="payment" required>
							<option value="Visa">Visa</option>
							<option value="Master Card">Master Card</option>
							<option value="PayPal">PayPal</option>
							<option value="American Experess">Amex</option>
						</select>
					</td>
				</tr>
				<tr>
					<td>Grand Total</td>
					<td id="grand_total">$<?php echo $total; ?></td>
				</tr>
				<tr>
					<input type="hidden" value="" name="grand_total" id="final_total">
					<td colspan="2"><input type="submit" value="submit" id="Reserve" name="submit" onsubmit="getTotal()"></td>
				</tr>				
			</table>
		</form>
	</div>
	<script>
	function getTotal(){
		var grand_total = document.getElementById("grand_total").innerHTML;
		document.getElementById("final_total").value = grand_total; 
	}
	</script>
	</body>
</html>