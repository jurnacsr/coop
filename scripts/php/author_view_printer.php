<?php
	
	// connect to database
	include("CONNECTION.php");
	include("ENVIRONMENT.php");
	
	$current_month = "NULL";
	$index =0;
	$counter = 0;
	
	// get the author view
	$productQuery = "SELECT DISTINCT USER_NAME FROM wishlist_item INNER JOIN item ON item.ITEM_ID = wishlist_item.WISHLIST_ITEM_ITEM_ID INNER JOIN user ON user.USER_ID = wishlist_item.WISHLIST_ITEM_USER_ID ORDER BY USER_NAME ASC";
	$result = mysql_query($productQuery, $con);
	if (!$result) die(mysql_error());
	echo("<table><tr>");
	while ($row = mysql_fetch_array($result))
	{
		$username = $row['USER_NAME'];
		$counter++;
	?>
	<td valign="top" style="border: 1px solid black;">
		<h3>Wishlist for <?php echo($username);?></h3>
		<table class="author_view_table">
			<th class="author_view_header">Item Name</th>
			<th class="author_view_header">Qty.</th>
			<th class="author_view_header">Unit</th>
			<th class="author_view_header">Price</th>
			<th class="author_view_header">Item Total</th>
		<?php
		// get list of distinct items
		$itemsQuery = "SELECT * FROM wishlist_item INNER JOIN item ON item.ITEM_ID = wishlist_item.WISHLIST_ITEM_ITEM_ID INNER JOIN user ON user.USER_ID = wishlist_item.WISHLIST_ITEM_USER_ID WHERE user.USER_NAME='".$username."'";
		$r = mysql_query($itemsQuery, $con);
		if (!$r) die(mysql_error());
		$itemPriceSumQuery = "SELECT SUM(ITEM_PRICE) AS ITEM_SUM FROM wishlist_detail WHERE USER_NAME='".$username."'";
		$rr = mysql_query($itemsQuery, $con);
		if (!$rr) die(mysql_error());
		while ($c = mysql_fetch_array($r))
		{
			?>
			<tr class="author_view_row">
				<td class="author_view_data"><?php echo($c['ITEM_NAME']);?></td>
				<td class="author_view_data"><?php echo($c['WISHLIST_ITEM_QUANTITY']);?></td>
				<td class="author_view_data"><?php echo($c['ITEM_QUANTITY_UNIT']);?></td>
				<td class="author_view_data"><?php echo($c['ITEM_PRICE']);?></td>
				<?php
					$itemTotal = $c['WISHLIST_ITEM_QUANTITY'] * $c['ITEM_PRICE'];
					if ($ENV != 'dev') {
						$itemTotal = money_format('%i', $itemTotal);
					}
				?>
				<td class="author_view_data"><?php echo($itemTotal);?></td>
			</tr>
			<?php
		}
		?>
		</table>
		<br />
		<br />
		<br />
		</td>
		<?php
		if ($counter % 2 == 0)
		{
			echo("</tr>");
		}
	}
?>