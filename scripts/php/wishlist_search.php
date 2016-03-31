<?php

	include("CONNECTION.php");
	include("ENVIRONMENT.php");
	
	$user = $_POST['user'];
	
	// get the user's name and ID
	$userId;
	$userQuery = "SELECT USER_ID FROM user WHERE USER_NAME='".$user."';";
	$result = mysql_query($userQuery, $con);	
	if (!$result) die(mysql_error());
	while ($row = mysql_fetch_array($result))
	{
		$userId = $row['USER_ID'];
	}
	
	$joinQuery = "SELECT ITEM_NAME AS NAME, ITEM_QUANTITY_UNIT AS UNIT, ITEM_PRICE AS PRICE, WISHLIST_ITEM_QUANTITY AS QUANTITY FROM item INNER JOIN wishlist_item ON item.ITEM_ID = wishlist_item.WISHLIST_ITEM_ITEM_ID WHERE wishlist_item.WISHLIST_ITEM_USER_ID = ".$userId;
	$result = mysql_query($joinQuery, $con);	
	if (!$result) die(mysql_error());

	$grandTotal = 0;
	
	?>
<html>
<head>
	<script>
		function new_wishlist_search()
		{
			$("#wishlist_search_div").html("<div id='search_invalid_div'></div>Enter your name:<input type='text' id='name_wishlist_search' /><br /><input type='button' class='submit_button' id='browse_wishlist_button' value='Search For Wishlist' onclick='browse_wishlist_click()' />");
		}
	</script>
</head>
<body>
	<h2>Wishlist for <?php echo($user);?></h2>
	<table class="wishlist_view_table">
		<tr>
			<th class="wishlist_view_header">Item name</th>
			<th class="wishlist_view_header">Quantity</th>
			<th class="wishlist_view_header">Unit</th>
			<th class="wishlist_view_header">Unit Price</th>
			<th class="wishlist_view_header">Total Price</th>
		</tr>
	<?php
	while ($row = mysql_fetch_assoc($result))
	{
		?>
		<tr class="wishlist_view_row">
			<td class="wishlist_view_data"><?php echo($row['NAME']);?></td>
			<td class="wishlist_view_data"><?php echo($row['QUANTITY']);?></td>
			<td class="wishlist_view_data"><?php echo($row['UNIT']);?></td>
			<td class="wishlist_view_data"><?php echo($row['PRICE']);?></td>
			<?php
				$totalPrice = $row['PRICE'] * $row['QUANTITY'];
				$grandTotal = $grandTotal + $totalPrice;
				$totalPrice = $totalPrice . "";
				if ($ENV != 'dev') {
					$totalPrice = money_format('%i', $totalPrice);
				}
			?>
			<td class="wishlist_view_data"><?php echo($totalPrice);?></td>
		</tr>
		<?php
	}
	
	if ($ENV != 'dev') {
		$grandTotal = money_format('%i', $grandTotal);
	}
?>
	<tr class="wishlist_view_row">
		<td class="empty_wishlist_view_data" />
		<td class="empty_wishlist_view_data" />
		<td class="empty_wishlist_view_data" />
		<td class="wishlist_view_data">Grand Total:</td>
		<td class="wishlist_view_data"><?php echo($grandTotal);?></td>
	</tr>
	</table>
	<br />
	<input type="button" id="new_search_button" onclick="new_wishlist_search()" value="New Wishlist Search"/>
</body>
</html>