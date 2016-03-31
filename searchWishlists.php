<?php

	include("scripts/php/CONNECTION.php");
	include("scripts/php/ENVIRONMENT.php");
	
	if (!$_POST['user']) {
		header( 'Location: <?php echo($ROOT); ?>' ) ;
	}
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
	<title>Jurnack's, Naturally! Co-Op</title>
	<script type="text/javascript" src="scripts/js/jquery.js"></script>
	<script type="text/javascript" src="scripts/js/coop_common.js"></script>
	<link rel="stylesheet" type="text/css" href="style/style.css" />
	<script>
		$(document).ready(function() {
			$("#dateSpan").text(getYear());
		});
	</script>
</head>
<body>
<div class="container">
	<h1 class="title">Viewing wishlist for <?php echo($user); ?></h1>
	<table class="wishlist_view_table" border="0" width="100%">
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
			<td class="wishlist_view_data" align="center"><?php echo($row['NAME']);?></td>
			<td class="wishlist_view_data" align="center"><?php echo($row['QUANTITY']);?></td>
			<td class="wishlist_view_data" align="center"><?php echo($row['UNIT']);?></td>
			<td class="wishlist_view_data" align="center"><?php echo($row['PRICE']);?></td>
			<?php
				$totalPrice = $row['PRICE'] * $row['QUANTITY'];
				$grandTotal = $grandTotal + $totalPrice;
				$totalPrice = $totalPrice . "";
				if ($ENV != 'dev') {
					$totalPrice = money_format('%i', $totalPrice);
				}
			?>
			<td class="wishlist_view_data" align="center"><?php echo($totalPrice);?></td>
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
		<td class="wishlist_view_data" align="center"><b>Grand Total:</b></td>
		<td class="wishlist_view_data" align="center"><?php echo($grandTotal);?></td>
	</tr>
	</table>
	<br />
	<h2 class="title"><a href="<?php echo($ROOT); ?>">Click here to go back to the main site.</a></h2>
</div>
<div class="footer centered">
	&copy;<span id="dateSpan"></span> Stephen Jurnack
</div>
</body>
</html>