<?php

//make connection with database
include("db_conn.php");

?>

<html>
<head><link rel="stylesheet" href="style.css"></head>
<body>


<!--Navigation Bar-->
		<nav>
<!--show cart link if user click on add to cart buton and Calculate no of added books-->
		<?php
		if (!empty($_SESSION["shopping_cart"])) {
			$cart_count = count(array_keys($_SESSION["shopping_cart"]));
		?>
		<div class="cart-section">
			<a href="shopping-cart.php"><span>  <?php echo $cart_count; ?> </span>My Cart</a>
		</div>
		<?php	
		}
		?>


		<!--Create dropdown menu to shop by category -->


		<div class="dropdown">
			
			<a class="dropdown-btn">Shop by category</a>
				<div class="mydropdown-links">
				<?php
					$query1 = "SELECT s_code, s_name FROM subject;";
					$result1 = mysqli_query($conn, $query1);
					if (mysqli_num_rows($result1)>0) {
						while($row = mysqli_fetch_array($result1)){
					?>			
						<a name="category" href="search.php?category=<?php echo $row["s_code"];?>"><?php echo $row["s_name"] ?></a>

					<?php
						}
					}else{
						echo "No categories.";
					}	
					?>						
				</div>

		</div>





			<a href="home-page.php">Home</a>
			<a href="gift-vouchers.php">Gift Vouchers</a>
			<a href="about_us/ab1.html">About Us</a>
			<a href="contact_list/cc.html">Contact Us</a>			
	</nav>

</body>
</html>			