<?php

//session started
session_start();

//make connection with database
include("db_conn.php");

extract($_POST);
	
?>
<!DOCTYPE html>
<html lang="en">	
<head>
<title>Place Order - Samudra Bookshop</title>	
<meta  charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="style.css">
</head>
<body>


	<header>

		<h1>SAMUDRA<span>BOOKSHOP</span></h1>

			<!--make connection with search bar and navigation-->
			<?php include("search-nav.php"); ?>
			
	</header>


	
<?php


	if(!empty($_SESSION["shopping_cart"])){

		//intsert customer data to recipients table in database
		$query1 = "insert into recipients (r_name, r_address, r_postalcode, r_paymentmethod, r_phone, r_email, r_message, r_date) values
('$r_name', '$r_address', $r_postalcode, '$paymethod', '$r_phone', '$r_email', '$r_message', '$hidden_curdate');";

		$result1 = mysqli_query($conn, $query1);

		//retrive r_code to insert into order foriegn key
		$getstock = "select r_code from recipients where r_phone = '$r_phone';";

		$ref_code = mysqli_fetch_assoc(mysqli_query($conn, $getstock));

		if(isset($ref_code)){
			$ref = $ref_code["r_code"];
		}

		//store details of order 
		foreach( $_SESSION["shopping_cart"] as $key => $item ){

			$query2 = "insert into orders (o_r_code, o_p_code, o_quantity) values ('$ref', '$item[p_code]', '$item[quantity]');";

			$result2 = mysqli_query($conn, $query2);

			//update book stock
			$query3 = "update book set p_stock = p_stock-'$item[quantity]' where p_code = '$item[p_code]';";					
			$result3 = mysqli_query($conn, $query3);
		}		

	  	}	


	if(!$result1){
?>
<div class="placeorder">
    <h1>Something went wrong.</h1>
    <p>Please try again.</p>
    
</div>
<?php
	die();		
	}else{
?>
<div class="placeorder">
    <h2>Your Order Has Been Placed.</h2>
    <p>Thank you <b><?php echo $r_name; ?></b> for ordering, we'll contact you by phone with your order details.</p>

    <?php
    	if($paymethod == "cod"){
    ?>		
    		<p>You can complete your payment to our delivery rider.<br><a href="home-page.php">Go to home >>>></a></p>
    <?php		
    	}else{
    ?>
    <p>Your payment has recived us.<br><a href="home-page.php">Go to home >>>></a></p>
    <?php } ?>

</div>
<?php } mysqli_close($conn); ?>

<?php 
//destroy the session
session_destroy();

//add footer
include("footer.php");
?>
</body>
</html>