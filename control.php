<?php

//make connection between database
include("db_conn.php");


?>
<!DOCTYPE html>
<html lang="en">	
<head>
<title>Admin - Samudra Bookshop</title>	
<meta  charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="style.css">
</head>
<body>


	<header>
		<h1>SAMUDRA<span>BOOKSHOP</span></h1>
		<h4 style="color: red;">!!!Admin Only!!!</h4>
	</header>


<div class="container">



		<div class="books-block">
			<h3>Recently Recived Orders</h3>

			<table class="price-summary" width="100%">
				<tr>
					<th width="5%">Code</th>
					<th width="15%">Date</th>
					<th width="15%">Customer</th>
					<th width="30%">Contacts</th>
					<th width="10%">Payment</th>
					<th width="10%">Messages</th>
					<th width="15%">Order</th>
				</tr>			
		<?php

		//display books in home-page

		$query1 = "select distinct r_code, r_date, r_name, r_address, r_postalcode, r_phone, r_email, r_paymentmethod, r_message from recipients, orders where r_code = o_r_code order by r_code desc;";		

		$result1 = mysqli_query($conn, $query1);
		if (mysqli_num_rows($result1)>0) {
			while($row = mysqli_fetch_array($result1)){
				
		$query2 = "select p_name, o_quantity from book, orders, recipients where p_code = o_p_code AND r_code = o_r_code and r_code = '$row[r_code]';";

		$query3 = "select p_name, o_quantity from gifts, orders, recipients where p_code = o_p_code AND r_code = o_r_code and r_code = '$row[r_code]';";			
	

		?>
				<tr>
					<td><?php echo $row["r_code"];?></td>
					<td><?php echo $row["r_date"];?></td>
					<td><b><?php echo $row["r_name"];?></b></td>
					<td>
						<b><?php echo $row["r_address"];?></b><br>
						<?php echo $row["r_postalcode"];?><br>
						<?php echo $row["r_phone"];?><br>
						<?php echo $row["r_email"];?>
					</td>
					<td><?php echo $row["r_paymentmethod"];?></td>
					<td><?php echo $row["r_message"];?></td>
					
					<td>
						<table>
							<tr>
								<th width="70%">Item</th>
								<th width="30%">Quantity</th>
							</tr>
								<?php
								$result2 = mysqli_query($conn, $query2);
								while($row2 = mysqli_fetch_array($result2)){
								?>
								<tr>	
									<td><?php echo $row2["p_name"];?></td>
									<td><?php echo $row2["o_quantity"];?></td>
								</tr>									
								<?php
								}
								$result3 = mysqli_query($conn, $query3);
								while($row3 = mysqli_fetch_array($result3)){								
								?>

							<tr>	
								<td><?php echo $row3["p_name"];?></td>
								<td><?php echo $row3["o_quantity"];?></td>
							</tr>

							<?php } ?>	
						</table>
					</td>
						
				</tr>
				
				
		<?php			
			}
		}else{
			echo "No Orders";
		}
		?>
		
	</table>	
	</div>
</div>
</div>

</body>

</html>