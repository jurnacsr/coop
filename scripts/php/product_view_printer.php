<?php
	
	// connect to database
	include("CONNECTION.php");
	include("ENVIRONMENT.php");
	
	$current_month = "NULL";
	
	// get the product view
	$productQuery = "SELECT DISTINCT ITEM_NAME, ITEM_ID, ITEM_CASE_SIZE, ITEM_QUANTITY_UNIT FROM item ORDER BY ITEM_NAME ASC";
	$result = mysql_query($productQuery, $con);
	if (!$result) die(mysql_error());
	?>
	<table class="product_view_table" width="80%" cellpadding="3" >
		<th class="product_view_header">Item Name</th>
		<th class="product_view_header">Quantity On Wishlists</th>
		<th class="product_view_header">Quantity Unit</th>
		<th class="product_view_header">Case Size</th>
		<th class="product_view_header">Cases to Order</th>
		<th class="product_view_header">Leftover</th>
	<?php
	while ($row = mysql_fetch_array($result))
	{
		// we have a list of distinct item names
		$itemName = $row['ITEM_NAME'];
		$itemID = $row['ITEM_ID'];
		$caseSize = $row['ITEM_CASE_SIZE'];
		$unit = $row['ITEM_QUANTITY_UNIT'];
		
		// query for the total lists
		$itemNumberQuery = "SELECT SUM(WISHLIST_ITEM_QUANTITY) AS COUNT FROM wishlist_item WHERE WISHLIST_ITEM_ITEM_ID='".$itemID."'";
		$r = mysql_query($itemNumberQuery, $con);
		while ($col = mysql_fetch_array($r))
		{
			$cases = "";
			$extra = "";
			if ($col['COUNT'] != 0) {
				$cases = ceil($col['COUNT'] / $caseSize);
				echo("<!-- TEST ". $col['COUNT'] / $caseSize ." -->");
				$extra =  $caseSize - ($col['COUNT'] % $caseSize);
				if ($extra == $caseSize) {
					$extra = "";
				}
				if ($cases == 0) {
					$cases = "";
				}
			}
			?>
			<tr class="product_view_row">
				<td class="product_view_data"><?php echo($itemName); if ($extra != 0 || $extra != "") echo("***");?></td>
				<td class="product_view_data" align="center"><?php echo($col['COUNT']);?></td>
				<td class="product_view_data"><?php echo($unit);?></td>
				<td class="product_view_data" align="center"><?php echo($caseSize);?></td>
				<td class="product_view_data" align="center"><?php echo($cases);?></td>
				<td class="product_view_data" align="center"><?php echo($extra);?></td>
			</tr>
			<?php
		}
	}
?>

		</table>