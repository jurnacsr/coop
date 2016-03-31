<?php
	
	// connect to database

	include("CONNECTION.php");
	include("ENVIRONMENT.php");
	
	
	$itemsList = "";
	
	// get the current order
	$check_query = "SELECT * FROM item ORDER BY ITEM_NAME ASC;";
	$result = mysql_query($check_query, $con);
	if (!$result) die(mysql_error());
	if (mysql_num_rows($result) == 0)
	{
		echo("no rows");
		exit(0);
	}
	while ($row = mysql_fetch_array($result))
	{
		$itemName = $row['ITEM_NAME'];
		$itemsList = $itemsList."".$itemName.",";
	}
	$itemsList = substr($itemsList, 0, strlen($itemsList)-1);
	echo($itemsList);
?>