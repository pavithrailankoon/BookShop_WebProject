<?php

//session started
session_start();

//make connection between database
include("db_conn.php");

//Get sent choosed categories from other pages
if(isset($_GET["category"])){
	$category = $_GET["category"];
}else if (isset($_GET["word"])){
	$word = $_GET["word"];
}else if (isset($_GET["choice"])) {
	$choice = $_GET["choice"];
}
	

//check if book was submitted
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
                echo '<script>window.location="search.php?word="</script>';

 	  		}else{
 	  			echo '<script> alert("Product is already in  the cart")</script>';
 	  			echo '<script>window.location="search.php?word="</script>';


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
<title>Search - Samudra Bookshop</title>	
<meta  charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="style.css">
</head>
<body>
	<header>
		<h1>SAMUDRA<span>BOOKSHOP</span></h1>

<!--Search bar-->
		<form method="GET" action="<?php  echo $_SERVER['PHP_SELF'] ?>">
		<div class="search-bar-form">	
			<div class="search">
				<input class="search-bar" type="text" name= "word" placeholder="Search books here...">
				<button type="submit" class="search-bar-btn">Search</button>
			</div>
		</div>
		
		</form>		

<!--Navigation Bar-->
		
			<!--make connection with search bar and navigation-->
			<?php include("search-nav.php"); ?>
			

	</header>





<div class="container">
<?php if(isset($word)){ ?>
		<div class="books-block">

			<p class="in-search-page-word">Search Results For: "<?php echo $word; ?>"</p>
		
			<?php
				if (!empty($word)) {
				

					$query1 = "SELECT p_code, p_name, p_image, a_name, p_price, p_stock FROM book, author where p_a_code = a_code AND ((p_name LIKE '%".$word."%') OR (a_name LIKE '%".$word."%') OR (p_language LIKE '%".$word."%'));";
					$result1 = mysqli_query($conn, $query1);
					if (mysqli_num_rows($result1)>0) {
					while($row = mysqli_fetch_array($result1)){
					?>	
				<div class="product-box">


					<form method="post" action="search.php?action=add&p_code=<?php echo $row["p_code"];?>">
					
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
		}else{
			$query2 = "SELECT p_code, p_name, p_image, a_name, p_price, p_stock FROM book, author WHERE a_code = p_a_code ORDER BY p_name ASC";
			$result2 = mysqli_query($conn, $query2);
					if (mysqli_num_rows($result2)>0) {
					while($row = mysqli_fetch_array($result2)){
				?>	
				<div class="product-box">


					<form method="post" action="search.php?action=add&p_code=<?php echo $row["p_code"];?>">
					
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
			}
			

			?>
		</div>	
<?php } else if (isset($category)){ ?>

		<div class="books-block">
			<?php
				$sql = "SELECT s_name FROM subject WHERE s_code = '$category';";
				$subjectName = mysqli_query($conn, $sql);
				$s_name = mysqli_fetch_assoc($subjectName);
			?>	
				<p class="in-search-page-word">Selected Category: "<?php echo $s_name["s_name"]; ?>"</p>				
			<?php	

			$query3 = "SELECT p_code, p_name, p_image, a_name, p_price, p_stock, s_name FROM book, author, subject WHERE a_code = p_a_code AND s_code = p_s_code AND s_code = '$category';";
				$result3 = mysqli_query($conn, $query3);
					if (mysqli_num_rows($result3)>0) {
					while($row = mysqli_fetch_array($result3)){
				?>	
				<div class="product-box">


					<form method="post" action="search.php?action=add&p_code=<?php echo $row["p_code"];?>">
					
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
						echo "No books for choosed category!".$category;
					}
			?>
		
			<?php		
		}			

?>
		</div>
	<div>
</div>
</div>
</div>		
<?php 
//add footer
include("footer.php");
?>	
</div>
</body>
</html>