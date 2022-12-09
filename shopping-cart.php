<?php

//session started
session_start();

//make connection between database
include("db_conn.php");

//removing books from shopping cart when user clicked on "Remove Item"
    if(isset($_GET["action"])){
        if($_GET["action"] == "delete"){
            foreach($_SESSION["shopping_cart"] as $keys => $value){
                if($value["p_code"] == $_GET["p_code"]){
                    unset($_SESSION["shopping_cart"][$keys]);
                    echo '<script>window.location="shopping-cart.php"</script>';
                }
            }
        }
    }    

//delete cart
	if(array_key_exists('delete_cart', $_POST)) {
		unset($_SESSION["shopping_cart"]);		
	} 


	//Adding fixed delivery fee to the bill
	$delivery_fee = 250;     
?>
<!DOCTYPE html>
<html lang="en">	
<head>
<title>Shopping Cart - Samudra Bookshop</title>	
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
	<!--Navigate user to continue shopping or delete all items in cart-->     
    <div class="return-shopping">
    	<button class="continue-btn" onclick="window.location.href='home-page.php'">TO SHOPPING</button>
    	<form method="post">
    		<button name="delete_cart" class="delete-btn" onclick="return confirm('Proceed to delete cart?')">DELETE</button>
    	</form>
    </div>
    <div class="summary">
    	<h3 class="title">Summary</h3>
        <div class="cart-table">
            <table class="price-summary">
            <tr>
                <th width="30%">Product Description</th>
                <th width="10%">Quantity</th>
                <th width="13%">Price Per Unit (Rs:)</th>
                <th width="10%">Total Price (Rs:)</th>
                <th width="17%">Remove Item</th>
            </tr>






			<?php
                if(!empty($_SESSION["shopping_cart"])){
                    $total=0;
                    foreach($_SESSION["shopping_cart"] as $key => $item){
            ?>
            <tr>	
                <td><?php echo $item["p_name"] ;?></td>
                <td><?php echo $item["quantity"] ;?></td>
                <td><?php echo number_format($item["p_price"],2);?></td>
                <td><?php echo number_format($item["quantity"]*$item["p_price"],2);?></td>
                <td><a href="shopping-cart.php?action=delete&p_code=<?php echo $item["p_code"]; ?>"><span class="remove-item">Remove Item</span></a></td>  

            </tr>

<!--Calculate subtotal value-->           
                <?php
                    $total = $total + ($item["quantity"]*$item["p_price"]); 
                    }
                    $total = $total + $delivery_fee; 
                ?> 
            <tr>
            	<td colspan="3" style="padding-right: 5px;"><b>Delivery Fee</b></td>
            	<td ><b><?php echo number_format($delivery_fee,2);?></b></td>   
            </tr>    
            <tr>
                <td colspan="3" align="right">Total</td>
                <td align="right"><?php echo "Rs: ".number_format($total,2);?></td>              
            </tr>                           	
            </table>
        
    </div>

<!--Proceed shipping details to database-->
        <form method="POST" action="place-order.php">

<!--Getting shipping information-->  
    <div class="shipping">
		
			<table class="shipping-info">
				<tr>
					<th class="left-section" width="70%"><h3>Shipping Information</h3>  </th>
					<th class="right-section" width="30%"><h3>About Delivery</h3>  </th>
				</tr>	
				<tr>
			<td class="left-section">	
					<table class="shipping-table">
				<tr>
					<td width="50%" class="left-info"><b>Recipient Name: </b><span style="color: red;">*</span></td>
					<td width="50%"><input type="text" name="r_name" placeholder="Amal Fernando" required></td>
				</tr>
				<tr>
					<td class="left-info"><b>Recipient Address: </b><span style="color: red;">*</span></td>
					<td><textarea rows="5" cols="22" name="r_address" placeholder="No:1, Beach Road, Matara" required></textarea></td>
				</tr>
				<tr>
					<td class="left-info"><b>Postal Code: </b><span style="color: red;">*</span></td>
					<td><input type="text" name="r_postalcode" placeholder="81000"  pattern="\d{5,5}" required></td>
				</tr>
				<tr>
					<td class="left-info"><b>Payment Method: </b><span style="color: red;">*</span></td>
					<td>
							<input type="radio" name="paymethod" value="debit-credit" checked required>DEBIT/CREDIT CARD<br>
							<input type="radio" name="paymethod" value="cod" required>CASH ON DELIVERY								
						
					</td>
				</tr>
				<tr>
					<td class="left-info"><b>Recipient Phone No: </b><span style="color: red;">*</span></td>
					<td><input type="text" name="r_phone" placeholder="07********" pattern="\d{10,10}" required></td>
				</tr>
				<tr>
					<td class="left-info"><b>Recipient email: </b></td>
					<td><input type="text" name="r_email" placeholder="amal@email.com"></td>
				</tr>
				<tr>
					<td class="left-info"><b>Any messages: <b></td>
					<td><textarea rows="5" cols="22" placeholder="messages" name="r_message"></textarea></td>
				</tr>

				</table>				
			</td>

			<!--Giving instructions -->  
			<td class="right-section">	 				
					<img class="rider-image" src="bookstore/rider.png">
						<ul>
							<li>Two payment methods</li>
							<ol>
								<li>Credit/Debit Card</li>
								<li>Cash On Delivery</li>
							</ol>
							<li>To cancel the order, call us via phone with order details.</li>
							<li>Only in Sri Lanka</li>	
							<li><b>Standarded Delivary before, <br><?php echo date("Y-m-d", strtotime('+5 days')); ?></b></li>
						</ul>
						
			</td>
		</tr>
		<tr>
				<td colspan="1" align="left">	
				<input type="hidden" name="hidden_total_price" value="<?php echo $total;?>">
				<input type="hidden" name="hidden_curdate" value="<?php echo date("Y-m-d"); ?>">
				<input type="submit" value="PLACE ORDER" name="order" onclick="return confirm('Proceed to place order?')" class="order-btn">
				<input type="reset" value="X">
				</td>
		</tr>
			</table>	
		   	
    
                <?php
                }else{
                ?>
                </form>         	
                	<tr>
                		<td colspan="2">Empty cart</td>
                	</tr>
                
                <?php	
                }
                ?>
       </div>
</div>
</form>
</table>
</div>
</div>
</div>
<?php 
//add footer
include("footer.php");
?>
</body>