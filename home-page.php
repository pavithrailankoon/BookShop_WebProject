<?php

//session started
session_start();

//make connection with database
include("db_conn.php");

//check if form was submitted and store order details in an array
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
                echo '<script>window.location="home-page.php"</script>';
 	  		}else{
 	  			echo '<script>alert("Product is already in  the cart")</script>';
 	  			echo '<script>window.location="home-page.php"</script>';
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
<title>Home - Samudra Bookshop</title>	
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
		<div class="cover-image">
		
			<img src="bookstore/samudra-img.jpg">			

		</div>	
			
	</header>

<div class="container">

<!--New Books Section-->	
		<div class="books-block">
			<h3>Recently added books</h3>
		<?php

		//display books in home-page

		$query1 = "SELECT DISTINCT p_code, p_image, p_name, p_price, a_name, p_stock FROM book, author WHERE p_a_code = a_code AND p_entrydate >= DATE(now() - INTERVAL 2 MONTH);";


		$result1 = mysqli_query($conn, $query1);
		if (mysqli_num_rows($result1)>0) {
			while($row = mysqli_fetch_array($result1)){
		?>	


			<div class="product-box">

				<!--Show newly added books-->
				<button class="new-items">NEW</button>

				<!--Show out-of-stock books-->
				<?php if($row["p_stock"]==0){?> <h3 class="out-of-stock">Out of stock</h3> <?php } ?>
				
				<form method="post" action="home-page.php?action=add&p_code=<?php echo $row["p_code"];?>">
					
					<div class="book">
						<a href="book.php?p_code=<?php echo $row["p_code"];?>"><img src="<?php echo $row["p_image"];?>" class="book-image" name="p_image"></a>
						<h5 class="book-name"><b><?php echo $row["p_name"];?><b></h5>
						<h5 class="book-author"><i><?php echo "by ".$row["a_name"];?></i></h5>
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
		<a class="more-books" href="search.php?word=">more>></a>
		</div>

<!--Featured books section-->
		
		<div class="books-block">
			<h3>Author`s Choice</h3>
				<?php

					$query1 = "SELECT p_code, p_name, p_image, a_name, p_price, p_stock FROM book, author where p_a_code = a_code AND p_status = 'featured'";


					$result1 = mysqli_query($conn, $query1);
					if (mysqli_num_rows($result1)>0) {
					while($row = mysqli_fetch_array($result1)){
				?>	


				<div class="product-box">
					<form method="post" action="home-page.php?action=add&p_code=<?php echo $row["p_code"];?>">
					
						<div class="book">
						<?php if($row["p_stock"]==0){?> <h3>Out of stock</h3> <?php } ?>					
							<a href="book.php?p_code=<?php echo $row["p_code"];?>"><img href="" src="<?php echo $row["p_image"];?>" width="100px" height="130px" class="book-image"></a>
							<h5 class="book-name"><b><?php echo $row["p_name"];?><b></h5>
							<h5 class="book-author"><i><?php echo "by ".$row["a_name"];?></i></h5>
							<h5 class="book-price"><b><?php echo "Rs: ".$row["p_price"];?><b></h5>
							<input type="number" name="quantity" min="1" max="5" value="1" class="quantity-control">	
							<input type="hidden" name="hidden_name" value="<?php echo $row["p_name"];?>">
							<input type="hidden" name="hidden_image" value="<?php echo $row["p_image"];?>">	
							<input type="hidden" name="hidden_price" value="<?php echo $row["p_price"];?>">
							<input type="submit" name="add" value="ADD TO CART" class="cart-btn">
						</div>						
					</form>	

				</div>
			<?php				
				}
			}else{
				echo "No Books";
			}
			?>
			<a class="more-books" href="search.php?word=">more>></a>
		</div>

<!--Movie Based books section-->
		
		<div class="books-block">
			<h3>Movies Based Books</h3>
				<?php

					$query1 = "SELECT p_code, p_name, p_image, a_name, p_price, p_stock FROM book, author where p_a_code = a_code AND p_status = 'movie based'";


					$result1 = mysqli_query($conn, $query1);
					if (mysqli_num_rows($result1)>0) {
					while($row = mysqli_fetch_array($result1)){
				?>	

				
				<div class="product-box">
					<form method="post" action="home-page.php?action=add&p_code=<?php echo $row["p_code"];?>">
					
						<div class="book">
						<?php if($row["p_stock"]==0){?> <h3>Out of stock</h3> <?php } ?>					
							<a href="book.php?p_code=<?php echo $row["p_code"];?>"><img href="" src="<?php echo $row["p_image"];?>" width="100px" height="130px" class="book-image"></a>
							<h5 class="book-name"><b><?php echo $row["p_name"];?><b></h5>
							<h5 class="book-author"><i><?php echo "by ".$row["a_name"];?></i></h5>
							<h5 class="book-price"><b><?php echo "Rs: ".$row["p_price"];?><b></h5>
							<input type="number" name="quantity" min="1" max="5" value="1" class="quantity-control">	
							<input type="hidden" name="hidden_name" value="<?php echo $row["p_name"];?>">
							<input type="hidden" name="hidden_image" value="<?php echo $row["p_image"];?>">	
							<input type="hidden" name="hidden_price" value="<?php echo $row["p_price"];?>">
							<input type="submit" name="add" value="ADD TO CART" class="cart-btn">
						</div>						
					</form>	

				</div>
			<?php				
				}
			}else{
				echo "No Books";
			}
			?>
			<a class="more-books" href="search.php?word=">more>></a>
		</div>			
</div>		



<?php 
//add footer
include("footer.php");
?>			
</body>
</html>