<script>
	$(document).ready(function()
	{
		$(".delete_wishlist_button").click(deleteWishlistClick);
	});
	
	function deleteWishlistClick()
	{
		if (confirm("Are you sure you want to delete this wishlist?"))
		{
			var wishlistKeyVar = $(this).attr("id");
			$.post('scripts/php/delete_wishlist.php',
				{wishlistKey: wishlistKeyVar},
				function (result)
				{
					if (result == "y")
					{
						author_view_click();
					}
				}
			);
		}
	}
</script>
<?php
	
	// connect to database
	include("CONNECTION.php");
	include("ENVIRONMENT.php");
	
	$current_month = "NULL";
	
	$author_list = array();
	$username_count = 0;
	
	// get the author view
	$productQuery = "SELECT DISTINCT USER_NAME FROM wishlist_item INNER JOIN item ON item.ITEM_ID = wishlist_item.WISHLIST_ITEM_ITEM_ID INNER JOIN user ON user.USER_ID = wishlist_item.WISHLIST_ITEM_USER_ID ORDER BY USER_NAME ASC";
	$result = mysql_query($productQuery, $con);
	if (!$result) die(mysql_error());
	while ($row = mysql_fetch_array($result))
	{
		$username = $row['USER_NAME'];
		$username_count++;
	?>
		<h3>Wishlist for <?php echo($username);?></h3>
		<table cellpadding="4" class="author_view_table" width="90%">
			<th class="author_view_header">Item Name</th>
			<th class="author_view_header">Qty.</th>
			<th class="author_view_header">Unit</th>
			<th class="author_view_header">Price</th>
			<th class="author_view_header">Item Total</th>
			<th class="author_view_header">Delete Wishlist</th>
		<?php
		// get list of distinct items
		$first = true;
		$itemsQuery = "SELECT * FROM wishlist_item INNER JOIN item ON item.ITEM_ID = wishlist_item.WISHLIST_ITEM_ITEM_ID INNER JOIN user ON user.USER_ID = wishlist_item.WISHLIST_ITEM_USER_ID WHERE user.USER_NAME='".$username."'";
		$r = mysql_query($itemsQuery, $con);
		if (!$r) die(mysql_error());
		while ($c = mysql_fetch_array($r))
		{
		array_push($author_list, $username."*".$c['ITEM_NAME']."*".$c['WISHLIST_ITEM_QUANTITY']."*".$c['ITEM_QUANTITY_UNIT']."*".$c['ITEM_PRICE']."*");
			?>
			<tr class="author_view_row">
				<td class="author_view_data"><?php echo($c['ITEM_NAME']);?></td>
				<td class="author_view_data" align="center"><?php echo($c['WISHLIST_ITEM_QUANTITY']);?></td>
				<td class="author_view_data"><?php echo($c['ITEM_QUANTITY_UNIT']);?></td>
				<td class="author_view_data" align="center"><?php echo($c['ITEM_PRICE']);?></td>
				<?php
					$itemTotal = $c['WISHLIST_ITEM_QUANTITY'] * $c['ITEM_PRICE'];
					if ($ENV != 'dev') {
						$itemTotal = money_format('%i', $itemTotal);
					}
					$wishlist_item_id = $c['WISHLIST_ITEM_USER_ID'];
					
				?>
				<td class="author_view_data" align="center"><?php echo($itemTotal);?></td>
				<?php
				if ($first)
				{
					?>
					<td class="author_view_data" align="center"><input id='<?php echo($wishlist_item_id); ?>' class="delete_wishlist_button" type="button" value="Delete?" /></td>
					<?php
				}
				else
				{
					?>
					<td class="author_view_data" />
					<?php
				}
				?>
			</tr>
			<?php
			$first = false;
		}
		?>
		</table>
		<?php
	}
?>