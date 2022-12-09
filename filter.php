<html>
<head>
	<meta  charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="filter-body">

<form action="" method="POST" onsubmit=""

	<table>
		<tr>
			<td>Language: </td>

			<td>
				<select name="language" >
					<option value="">All</option>
					<option value="english">English</option>
					<option value="sinhala">Sinhala</option>
					<option value="tamil">Tamil</option>
				</select>
				<input type="hidden" name="hidden_language" value="language">	
			<td>Sort by: </td>
			<td>
				<select name="sort-by">
					<option value="p_name">Alphabetically</option>
					<option value="p_enrtydate">Recently Added</option>
					<option value="p_price">Low Price</option>
				</select>
				<input type="hidden" name="hidden_sorting" value="sort-by">	
			</td>						
		</tr>
	
	</table>	
</form>

</div>

<?php  

?>

</body>	
</html>