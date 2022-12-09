<?php

//session started
session_start();

//make connection with database
include("db_conn.php");

if(isset($_GET["p_code"])){
	$p_code = $_GET["p_code"];
}else{
	echo "Book does not exit.";
}

//check if form was submitted and store order details in an array
 	if (isset($_POST['add'])||isset($_POST['buyNow'])) {
 	  	if (isset($_SESSION['shopping_cart'])) {
 	  		$array_item_no = array_column($_SESSION["shopping_cart"],"p_code");
 	  		if(!in_array($_GET["p_code"],$array_item_no)){
 	  			$count = count($_SESSION["shopping_cart"]);
 	  			$item_array = array(
                    'p_code' => $_GET["p_code"],
                    'p_name' => $_POST["hidden_name"],
                    'p_image' => $_POST["hidden_image"],
                    'p_price' => $_POST["hidden_price"],
                    'quantity' => $_POST["quantity"], 
                );
                $_SESSION["shopping_cart"][$count] = $item_array;
                if (isset($_POST['add'])){
                	echo '<script>window.location="book.php?p_code=<?php echo $row2["p_code"];?>"</script>';
                }else{
                	echo '<script>window.location="shopping-cart.php"</script>';
                }
                
 	  		}else{
 	  			if (isset($_POST['add'])){
 	  				echo '<script>alert("Product is already in  the cart")</script>';
 	  				echo '<script>window.location="book.php?p_code=<?php echo $row2["p_code"];?>"</script>';
 	  			}else{
 	  				echo '<script>alert("Product is already in  the cart")</script>';
 	  				echo '<script>window.location="shopping-cart.php"</script>';	
 	  			}	
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
<title>Book - Samudra Bookshop</title>	
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

	

<!--Display book information (Book name, Author name, Price, Stocks, Image)-->

		<?php

			$query1 = "select p_stock, p_name, p_year, p_price, p_image, p_year, a_name, p_language, p_description from book, author where p_code = '$p_code' and a_code = p_a_code;";

			$result1 = mysqli_query($conn, $query1);
			if (mysqli_num_rows($result1)==1) {
				while($row1 = mysqli_fetch_array($result1)){
			
		?>
	<div class="container">
			<table class="book-introduction-1">
				<tr>

					<!--Retrive Book Image-->
					<td width="290px">
						<img src="<?php echo $row1["p_image"];?>" width="275px" height="350px" class="in-page-img">
					</td>

					<!--Retrive book details-->
					<td >
						<table class="in-page-details-table">
							<tr><th class="in-page-book-name"><b><?php echo $row1["p_name"];?></b><th><tr>
							<tr><td class="in-page-book-author"><h5><i><?php echo "by ".$row1["a_name"];?></i></h5></td><tr>	
							<tr><td><h5 class="book-price"><b><?php echo "Rs: ".$row1["p_price"];?><b></h5></td><tr>
							<tr><td><?php if($row1["p_stock"]>0){?> 
									<h4 style="color:green;">In stock</h4> 
								<?php }else{?>
									<h4 style="color:red">Out of stock</h4> 
								<?php } ?>
							</td>
						</tr>
	

				<!--send add to cart data to array-->
						<form method="post" action="book.php?action=add&p_code=<?php echo $p_code;?>">
							<tr>
								<td><input type="number" name="quantity" min="1" max="5" value="1" class="quantity-control">
								</td>
							<tr>									
							<tr>
								<td>
									<input type="hidden" name="hidden_name" value="<?php echo $row1["p_name"];?>">
									<input type="hidden" name="hidden_image" value="<?php echo $row1["p_image"];?>">	
									<input type="hidden" name="hidden_price" value="<?php echo $row1["p_price"];?>">									
									<input type="submit" name="add" value="ADD TO CART" class="in-page-cart-btn">
									<br><br>
								</td>	
							</tr>
							<tr>
								<td>				
									<input type="submit" name="buyNow" value="BUY NOW" class="in-page-buy-now-btn">
								</td>
							</tr>
						</form>
						

						</table>
					</td>	
				</tr>
				</table>

<!--Retrive book category-->				
				<?php
					$query3 = "SELECT s_name FROM book, subject WHERE s_code = p_s_code AND p_code = '$p_code';";
					$result3 = mysqli_query($conn, $query3);
					$row3 = mysqli_fetch_assoc($result3);
				?>

				<table class="book-introduction-2">
					<tr>
						<td style="text-align:right;">Book</td>
						<td><b><?php echo $row1["p_name"];?><b></td>
					</tr>	
					<tr>
						<td style="text-align:right;">Author</td>
						<td><b><?php echo $row1["a_name"];?><b></td>
					</tr>	
					<tr>
						<td style="text-align:right;">Publication</td>
						<td><b><?php echo $row1["p_year"];?><b></td>
					</tr>
					<tr>
						<td style="text-align:right;">Language</td>
						<td><b><?php echo $row1["p_language"];?><b></td>
					</tr>
					<tr>
						<td style="text-align:right;">Category</td>
						<td><b><?php echo $row3['s_name']; ?><b></td>
					</tr>
				</table>
				<table class="book-introduction-3">	
					<tr>
						<td colspan="2" style="text-align:center">Overview<td>
					</tr>
					<tr>
						<td class = "book-intro" colspan="2" style="text-align:justify;"><p><?php echo $row1["p_description"];?></p><td>
					</tr>																							
				</table>

<?php
}
?>				

				<div>				
				<!--Recommend 3 books related subjects-->
				<table class="similar-books">
				<tr>
					<th><h3>Similar Books</h3></th>	
				</tr>	
				<tr>
					<td>
				<?php
				$query2 = "select p_code, p_name, p_image, s_name from book, subject where p_s_code = s_code and p_s_code like (select p_s_code from book, subject where p_code = '$p_code' and p_s_code = s_code) limit 0,3;";

					$result2 = mysqli_query($conn, $query2);
					if (mysqli_num_rows($result2)>0) {
						while($row2 = mysqli_fetch_array($result2)){
				?>
				<div class="similar-book-block">	
					
						<div class="similar-book">
							<a href="book.php?p_code=<?php echo $row2["p_code"];?>"><img href="" src="<?php echo $row2["p_image"];?>" width="190px" height="200px" class="in-page-similar-book-image"></a>
							<h5 class="in-page-similar-book-name"><b><?php echo $row2["p_name"];?><b></h5>
						</div>	
					
				</div>	
				<?php
						}
					}else{
						echo "No similar books found.";
					}	
				?>
				</td>
				</tr>
			</table>
			</div>
			
<?php
}else{
	echo "Something went wrong.";
}
?>
		</div>
<?php 
//add footer
include("footer.php");
?>
</body>
</html>