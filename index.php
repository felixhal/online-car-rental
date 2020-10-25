<?php 
//Starts sessions global variable
session_start();
//Implementation of array_column function (since array_column is not supported in php 5.3.21)
/*function array_column($array, $column_name){
	//array maps sends each value of an array to the function
	//Using the "use" keyword to pass in the column name (item_id)
	return array_map(function($element) use($column_name)
	{
		//returns the item
		return $element[$column_name];
		//Scope of the array (in this case the $_SESSION["shopping_cart"] associative array)
		}, $array);
    }*/
//Check if there are any reservations request
if(isset($_REQUEST["reserve"])){
	//Check if there are any reservations made previously
	if(isset($_SESSION["shopping_cart"])){
		//array columns returns the value of a single column of the input (identified using the column key in this case item_id) in the array
		$item_array_id = array_column($_SESSION["shopping_cart"], "id");
			//If the item id passed is not in the array than append the id into the array
			if(!in_array($_REQUEST["getId"], $item_array_id)){
				//counts the size of the array
				$count = count($_SESSION["shopping_cart"]);
				$item_array = array(
				"id" => $_REQUEST["getId"],
				"category" => $_REQUEST["getCategory"],
				"isFree" => $_REQUEST["isAvailable"],
				"brand" => $_REQUEST["getBrand"],
				"model" => $_REQUEST["getModel"],
				"year" => $_REQUEST["getYear"],
				"mileage" => $_REQUEST["getMileage"],
				"fuel" => $_REQUEST["getFuel"],
				"seats" => $_REQUEST["getSeat"],
				"ppd" => $_REQUEST["getPpd"],
				"desc" => $_REQUEST["getDesc"]
 				);
				//Assigns the new item in the highest index ($count)
				$_SESSION["shopping_cart"][$count] = $item_array;
			}
			//If the item exist already than it cannot be added
			else{
				print "<script>alert(\"You already reserved this car\")</script>";
			}
		}
		//if the shopping cart is empty add the item from index 0
		else{
			$item_array = array(
			"id" => $_REQUEST["getId"],
			"category" => $_REQUEST["getCategory"],
			"isFree" => $_REQUEST["isAvailable"],
			"brand" => $_REQUEST["getBrand"],
			"model" => $_REQUEST["getModel"],
			"year" => $_REQUEST["getYear"],
			"mileage" => $_REQUEST["getMileage"],
			"fuel" => $_REQUEST["getFuel"],
			"seats" => $_REQUEST["getSeats"],
			"ppd" => $_REQUEST["getPpd"],
			"desc" => $_REQUEST["getDesc"]
			);
			//Assigns new item in the first index
			$_SESSION["shopping_cart"][0] = $item_array;
		}
}
//Checks to see if there is $_REQUEST with name "action" (GET method)
if(isset($_REQUEST["action"])){
	//if the action value is delete
	if($_REQUEST["action"] == "delete"){
		//For each item in the shopping cart
		foreach($_SESSION["shopping_cart"] as $keys => $values){
			//Deletes the item with the passed id with the get method
			if($values["id"] == $_GET["getId"]){
				//Delete everything with the identifier key
				unset($_SESSION["shopping_cart"][$keys]);
				//Alert message to the user that the item has been removed
				print "<script>alert(\"Item Removed\")</script>";
				//Redirect user to the shopping cart page
				print "<script>window.location=\"cart.php\"</script>";
			}
		}
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
		</style> 
	<body>
	<div class="topnav">
		<a id="logo">Hertz-UTS</a>
		<a href="cart.php"><button id="Reserve">Car Reservations</button></a>
	</div>
	
	<div class="wrapper" id="wrapper">
	</div>
	
	<div class="cart" id="cart">
		<?php
		?>
	</div>

	<script>
	
	//On document ready load the XML using loadDoc() function
	$(document).ready(function(){
		loadDoc();
	});
	
	//Loads the cars.xml content file 
	function loadDoc(){
		//xhttp request
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function(){
			//When cars.xml is done loaded (sucessfull)
			if(this.readyState == 4 && this.status == 200){
				//Call getCars to iterate through xml file and store it in an array format
				//Display the results in the web page
				getCars(this);
			}
		};
		xhttp.open("GET", "cars.xml", true);
		xhttp.send();
	}
	//getCars iterate through cars.xml
	function getCars(xml){
		var xmlDoc = xml.responseXML;
		//variable to display
		var grid="";
		//get the car tags and store it in an array within cars.xml
		var x = xmlDoc.getElementsByTagName("car");
		//Itertate through the cars.xml and display it in the webpage
		for(var i=0; i<x.length; i++){
			//get the id attribute
			var id = x[i].getAttribute("id");
			//get the category as the child of the car tag
			var category = x[i].getElementsByTagName("category")[0].childNodes[0].nodeValue;
			//get the availabilityas the child of the car tag
			var isAvailable = x[i].getElementsByTagName("availability")[0].childNodes[0].nodeValue;
			//get the brand as the child of the car tag
			var brand = x[i].getElementsByTagName("brand")[0].childNodes[0].nodeValue;
			//get the model as the child of the car tag
			var model = x[i].getElementsByTagName("model")[0].childNodes[0].nodeValue;
			//get the year attribute of the model as the child of the car tag
			var year = x[i].getElementsByTagName("model")[0].getAttribute("year");
			//get the mileage as the child of the car tag
			var mileage = x[i].getElementsByTagName("mileage")[0].childNodes[0].nodeValue;
			//get the fuel_type as the child of the car tag
			var fuel = x[i].getElementsByTagName("fuel_type")[0].childNodes[0].nodeValue;
			//get the seats as the child of the car tag
			var seats = x[i].getElementsByTagName("seats")[0].childNodes[0].nodeValue;
			//get the price_per_day as the child of the car tag
			var ppd = x[i].getElementsByTagName("price_per_day")[0].childNodes[0].nodeValue;
			//get the description as the child of the car tag
			var desc = x[i].getElementsByTagName("description")[0].childNodes[0].nodeValue;
			//Display the title of the car consisting of model, year, brand and model
			var title = year + " " + brand + " " + model;
			//Display the picture of each car
			var picture = "./images/" + model + ".jpg";
			
			//Displays the XML format in the for of an html stored in grid variable
			//In each iteration it will keep appending
			grid += "<div><h3>" + title + "</h3><form method=\"GET\"><table>" +
			"<tr><td colspan=\"2\" style=\"text-align:center\"><img src=" + picture + " width=225 height=150></td></tr>" +
			"<tr><td>Availability</td><td>" + isAvailable + "</td></tr>" +
			"<tr><td>Category</td><td>" + category + "</td></tr>" +
			"<tr><td>Brand</td><td>" + brand + "</td></tr>" +
			"<tr><td>model</td><td>" + model + "</td></tr>" +
			"<tr><td>Year</td><td>" + year + "</td></tr>" +
			"<tr><td>Mileage</td><td>" + mileage + "</td></tr>" +
			"<tr><td>Fuel Type</td><td>" + fuel + "</td></tr>" +
			"<tr><td>Seats</td><td>" + seats + "</td></tr>" +
			"<tr><td>Price Per Day</td><td>" + ppd + "</td></tr>" +
			"<tr><td colspan=\"2\" style=\"text-align:center\">" + desc + "</td></tr>" +
			
			//Input values that will be posted to the shopping cart
			"<tr><td colspan=2 style=\"text-align:center\">" +
			"<input type=\"hidden\" name=\"getDesc\" value=" + desc + ">" +
			"<input type=\"hidden\" name=\"getPpd\" value=" + ppd + ">" +
			"<input type=\"hidden\" name=\"getSeats\" value=" + seats+ ">" +
			"<input type=\"hidden\" name=\"getFuel\" value=" + fuel + ">" +
			"<input type=\"hidden\" name=\"getMileage\" value=" + mileage + ">" +
			"<input type=\"hidden\" name=\"getModel\" value=" + model + ">" +
			"<input type=\"hidden\" name=\"getBrand\" value=" + brand + ">" +
			"<input type=\"hidden\" name=\"getYear\" value=" + year + ">" +
			"<input type=\"hidden\" name=\"getCategory\" value=" + category + ">" +
			"<input type=\"hidden\" name=\"getId\" value=" + id + ">" +
			"<input type=\"hidden\" class=\"isAvailable\" value=" + isAvailable +">" +
			"<input type=\"submit\" name=\"reserve\" id=\"Reserve\" value=\"reserve\">" +
			"</td></tr>" +
			"</table></form></div>";
			
			//Setting the html element with id "wrapper" innerHTML vavlue as grid
			//Display the grid value in html
			document.getElementById("wrapper").innerHTML = grid;
		}
		
		//Checks the avaialability of the car using Ajax
		//Determines if the car is available booking
		$(document).on("submit", "form", function(){
			//Refers to the current instance of the form
			var $form = $(this);
			//isFree variable finds the class within the form
			//isAvaialble stores with it the 
			isFree = $form.find(".isAvailable").val();
			//message to notify customer
			var valid = "Car Added to Reservations";
			//error variable to keep track of the issue
			var error = "";
			//Check value of the isFree from the xml file
			if(isFree == "false"){
				//Append the string to the error message
				error += "Car is unavailable, please choose another car";
			}
			//If the error message is not empty than it will cause an issue
			if(error != ""){
				//notify notify the error to the user
				alert(error);
				//Do not submit
				return false;
			}
			else{
				//notify the user with the success message
				alert(valid);
				//submit the form
				return true;
			}
		});
	}
	</script>
	</body>
</html>