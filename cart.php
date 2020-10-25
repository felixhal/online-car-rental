<?php 
session_start();

if(isset($_REQUEST["checkOut"])){
	if(!isset($_SESSION["shopping_cart"])){
	}
	else{
		print "<script>alert(\"You have no reservations currently\")</script>";
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
		.Cancel{
		background: #F6DB2D;
		border: 4px solid #F6DB2D;
		float: center;
		text-align: center;
		padding:8px 9px;
		font-family: 'Poppins', sans-serif;
		font-size:15x;
		margin:10px;
		transition-duration:0.4s;
		cursor:pointer;
		}

		.Cancel:hover{
		background-color: black;
		border: 4px solid #F6DB2D;
		color:#F6DB2D
		}
		
		.CheckOut{
		background: #F6DB2D;
		border: 4px solid #F6DB2D;
		float: center;
		text-align: center;
		padding:8px 9px;
		font-family: 'Poppins', sans-serif;
		font-size:15x;
		margin:10px;
		transition-duration:0.4s;
		cursor:pointer;
		}

		.CheckOut:hover{
		background-color: black;
		border: 4px solid #F6DB2D;
		color:#F6DB2D
		}
		
		.container{
		font-family: 'Poppins', sans-serif;
		margin: auto 0;
		}
		
		.cart{
		background: transparent;
		text-align: center;
		margin-left: auto;
		margin-right: auto;
		width: 50%;
		padding: 2px;
		}
		
		.cart table{
		}
		
		.cart table td, .cart table th{
		border: none;
		padding: 8px;
		text-align: center;
		}
		
		.cart table tr:nth-child(even){
		background-color: #f2f2f2;
		}
		
		.cart table tr:hover{
		background-color: #ddd;
		}
		</style> 
	<body>
	<div class="topnav">
		<a id="logo">Hertz-UTS</a>
		<a href="index.php"><button id="Reserve">Catalogue</button><a>
	</div>
	
	<div class="container">
		<div class="cart">
		<?php
		//Check if there is any item in the shopping cart
		//if there is than display the items in it
		if(!(empty($_SESSION["shopping_cart"]))){
		?>
		<h3>Reservation Details <h3>
		<table class="cart-items">
		<tr>
			<th>Image</th>
			<th>Vehicle</th>
			<th>Price Per Day</th>
			<th>Rental Days</th>
			<th>Action</th>
		</tr>
		<?php
			//for each item in the shopping car print it in a table
			foreach($_SESSION["shopping_cart"] as $keys => $values){
		?>
				<tr class="cart-row">
					<td><img src=./images/<?php print $values["model"];?>.jpg width=225 height=150></td>
					<td><?php print $values["brand"];?><?php print " "?><?print $values["model"];?><?php print " "?><?php print $values["year"]?></td>
					<td><?php print $values["ppd"];?></td>
					<!--When there is a change in value in the input field execute updateCartTotal(); function-->
					<td><input type="number" class="numOfDays" value="2" onchange="updateCartTotal();"></td>
					<!--A link to for a GET method that passes the delete action and hidden id to shopping_cart.php (this page) -->
					<td><a href="index.php?action=delete&getId=<?php print $values["id"];?>"><button class="Cancel">Cancel</button></a></td>
				</tr>
		<?php
			}
		?>
				</table>
				<form method=POST action="checkOut.php">
				<input type="hidden" value="0" id="total" name="total">
				<!-- everytime the form is submitted update the cart total-->
				<input type="submit" method="GET" value="checkOut" action="checkOut.php" class="CheckOut" name="checkOut" onsubmit="updateCartTotal();">
				</form>
		<?php
		}
		else
		{
			print "You currently have no reservations";
			print "
					
				<form method=\"POST\">
				<input type=\"submit\" method=\"GET\" value=\"checkOut\" action=\"checkOut.php\" class=\"CheckOut\" name=\"checkOut\"></form>";
		}
		?>
		</div>
	</div>
	<script async>
	//Wait until the page is done loading
	if(document.readyState == 'loading'){
		//Event listener for the document that listens for the page to finish loading
		//'DOMContentLoaded' is listens until the content is loaded and once it's done than execute updateCartTotal
		document.addEventListener('DOMContentLoaded', updateCartTotal)
	} else {
		//If the document is done loading just execute updateCartTotal
		updateCartTotal();
	}
	//updateCarttotal() updates the total amount that needs to pay by iterating through the table to calculate it based on the rental period and ppd value
	function updateCartTotal(){
		//returns a collection of rows in the table
		var row = document.getElementsByClassName("cart-items")[0].rows;
		//variable total intialised as 0 to store the total amount due by customer
		var total = 0;
		//Error message variable to keep track of errors
		var error = "";
		//iterate through the table collection using a for loop
		for(var i=1; i<row.length; i++){
			//Access the variable at the third cell of the current row(contains the ppd value)
			var ppd = document.getElementsByClassName("cart-items")[0].rows[i].cells.item(2).innerHTML;
			//Access the value of the input field in the fourth cell of the current row contains the rental period
			var rentalPeriod = document.getElementsByClassName("cart-items")[0].rows[i].cells.item(3).firstChild.value;
			//Check if the rental period is valid (integer>0)
			if(rentalPeriod > 0){
				//add the total of the row to the current total
				total += ppd*rentalPeriod;
			}
			//If it's not a valid rentalPeriod notify user and concatanate the error message
			else{
				//concatanate the error message
				error += "Invalid input";
				//set the input value for fourth column in the row to 2 (as default)
				document.getElementsByClassName("cart-items")[0].rows[i].cells.item(3).firstChild.value = 2;
			}
		}
		//If error variable is not empty it indicates there is an error
		if(error != ""){
			//Notify user of the error
			alert("You need to at least rent the car for 1 day");
		}
		//Update the total amount due that will be posted
		else{
			alert(total);
			document.getElementById("total").value = total;
		}
	}
	</script>
	</body>
</html>