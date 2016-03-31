<html><body>
<?php
	// connect to database
	include("CONNECTION.php");
	include("ENVIRONMENT.php");
	
	$current_month = "NULL";
	
	// get the product view
	$productQuery = "SELECT ITEM_NAME, ITEM_ID FROM item ORDER BY ITEM_NAME ASC";
	$result = mysql_query($productQuery, $con);
	if (!$result) die(mysql_error());
	
	while ($row = mysql_fetch_array($result))
	{
		// we have a list of distinct item names
		$itemName = $row['ITEM_NAME'];
		$itemID = $row['ITEM_ID'];
		// query for the total lists
		$itemNumberQuery = "SELECT user.user_name AS NAME, wishlist_item.wishlist_item_quantity AS QTY FROM wishlist_item INNER JOIN user ON wishlist_item.wishlist_item_user_id=user.user_id WHERE wishlist_item_item_id = ".$itemID;
		$r = mysql_query($itemNumberQuery, $con);
		$rows = mysql_num_rows($r);
		if ($rows != 0) 
		{
		?>
		<table class="product_view_table" style="width: 75%"  cellpadding="3" >
		<th class="product_view_header" width="34%"><?php echo($row['ITEM_NAME']); ?></th>
		<th class="product_view_header" align="left">Qty</th>
		<?php
		
		$people = "";
		$quantity = "";
		while ($col = mysql_fetch_array($r))
		{
			$quantity = $quantity + $col['QTY'];
			?>
			<tr class="product_view_row">
				<td class="product_view_data" align="center"><?php echo($col['NAME']);?></td>
				<td class="product_view_data" align="left" colspan="2"><?php echo($col['QTY']);?></td>
			</tr>
			<?php
		}
		?>		
		<tr class="product_view_row">
			<td class="product_view_data" align="right">Total Ordered:</td>
			<td class="product_view_data" align="left"><?php echo($quantity);?></td>
		</tr>
		</table>
		<br /><br />
		<?php
		}
		}
	?>
	</body></html>