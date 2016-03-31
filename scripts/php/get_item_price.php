<?php
	
	$itemName = $_POST['item'];
	

	include("CONNECTION.php");
	include("ENVIRONMENT.php");
	
	
	// get the quantity unit for the selected item name
	$check_query = "SELECT ITEM_PRICE FROM item WHERE ITEM_NAME='".$itemName."';";
	$result = mysql_query($check_query, $con);
	if (!$result) die(mysql_error());
	while ($row = mysql_fetch_array($result))
	{
		echo($row['ITEM_PRICE']);
		//echo($check_query);
	}
?>