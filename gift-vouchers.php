<?php

//session started
session_start();

//make connection between database
include("db_conn.php");

//check if form was submitted
 	if (isset($_POST['add'])) {
 	  	if (isset($_SESSION['shopping_cart'])) {
 	  		$array_item_no = array_column($_SESSION["shopping_cart"],"p_code");
 	  		if(!in_array($_GET["p_code"],$array_item_no)){
 	  			$count = count($_SESSION["shopping_cart"]);
 	  			$item_array = array(
                    'p_code' => $_GET["p_code"],
                    'p_name' => $_POST["hidden_name"],
                    'p_image' => $_POST["hidden_image"],
                    'p_price' => $_POST["hidden_price"],
                    'quantity' => $_POST["quantity"], //defualt book quantity = 1
                );
                $_SESSION["shopping_cart"][$count] = $item_array;
                echo '<script>window.location="gift-vouchers.php"</script>';
 	  		}else{
 	  			echo '<script>alert("Product is already in  the cart")</script>';
 	  			echo '<script>window.location="gift-vouchers.php"</script>';
 	  		}	
 	  	}else{
 	  		$item_array = array(
                'p_code' => $_GET["p_code"],
                'p_name' => $_POST["hidden_name"],
                'p_image' => $_POST["hidden_image"],
                'p_price' => $_POST["hidden_price"],
                'quantity' => $_POST["quantity"],
                );
 	  		$_SESSION["shopping_cart"][0] = $item_array;
 	  	}
 	  }
?>
<!DOCTYPE html>
<html lang="en">	
<head>
<title>Gift Vouchers - Samudra Bookshop</title>	
<meta  charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="style.css">
</head>
<body>


	<header>
		<h1>SAMUDRA<span>BOOKSHOP</span></h1>
		<!--Search bar-->
		<form method="GET" action="search.php" class="search-bar-form">
		<div class="search-bar-form">	
			<div class="search">
				<input class="search-bar" type="text" name= "word" placeholder="Search books here...">
				<button type="submit" class="search-bar-btn">Search</i></button>
			</div>
		</div>	
		</form>
			<!--make connection with search bar and navigation-->
			<?php include("search-nav.php"); ?>
		</header>


<div class="container">

<!--New Books Section-->	
		<div class="books-block">
			<h3>Gift Vouchers</h3>
		<?php

		//display books in home-page

		$query1 = "SELECT * FROM gifts ORDER BY p_price";
		$result1 = mysqli_query($conn, $query1);
		if (mysqli_num_rows($result1)>0) {
			while($row = mysqli_fetch_array($result1)){
		?>	
			<div class="product-box">
				<form method="post" action="gift-vouchers.php?action=add&p_code=<?php echo $row["p_code"];?>">
					
					<div class="book">						
						<img src="<?php echo $row["p_image"];?>" width="100px" height="130px" class="book-image">
						<h5 class="book-name"><b><?php echo $row["p_name"];?><b></h5>
						<h5 class="book-price"><b><?php echo "Rs: ".$row["p_price"];?><b></h5>
						<input type="number" name="quantity" min="1" max="5" value="1" class="quantity-control">	
						<input type="hidden" name="hidden_name" value="<?php echo $row["p_name"];?>">
						<input type="hidden" name="hidden_image" value="<?php echo $row["p_image"];?>">	
						<input type="hidden" name="hidden_price" value="<?php echo $row["p_price"];?>">
						<input type="submit" name="add"  value="ADD TO CART" class="cart-btn">
					</div>						
				</form>				
			</div>
		<?php			
			}
		}else{
			echo "No Books";
		}
		?>
		</div>
</div>
<?php 
//add footer
include("footer.php");
?>		
</body>
</html>