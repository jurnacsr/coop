<?php
	session_start();
	if ($_SESSION['admin'] != 'YES') {
		echo ("Unauthorized for this action.");
		exit(0);
	}
	
	// connect to database
	include("CONNECTION.php");
	include("ENVIRONMENT.php");
	
	$current_month = "NULL";
	
	// get the current order
	$check_query = "SELECT CURRENT_ORDER_MONTH FROM COOP_CONFIG;";
	$result = mysql_query($check_query, $con);
	if (!$result) die(mysql_error());
	while ($row = mysql_fetch_array($result))
	{
		$currentMonth = $row['CURRENT_ORDER_MONTH'];
	}
	
	// parse lists and make a separate query for each
	$nameList = $_POST['items'];
	$quantityList = $_POST['quantities'];
	
	$nameArray = explode(",",$nameList);
	$quantityArray = explode(",",$quantityList);
	
	for ($i = 0; $i < count($nameArray); $i++)
	{
		$query = "DELETE FROM item WHERE ITEM_NAME='".$nameArray[$i]."' AND ITEM_QUANTITY_UNIT='".$quantityArray[$i]."'";
		if (!mysql_query($query, $con))
		{
			echo("Error in sql: ".mysql_error());
			exit(0);
		}
	}
	echo ('good');
?>