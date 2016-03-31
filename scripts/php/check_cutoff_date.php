<?php

	include("CONNECTION.php");
	include("ENVIRONMENT.php");
	
	$current_month = "NULL";
	
	// check for wishlist under that name
	$check_query = "SELECT ORDER_DATE FROM COOP_CONFIG;";
	$result = mysql_query($check_query, $con);
	
	if (mysql_num_rows($result) == 0)
	{
		die("Something illegal happened.");
	}
	if (!$result) die(mysql_error());
	while ($row = mysql_fetch_array($result))
	{
		$date = $row['ORDER_DATE'];
		
		$date = str_replace("-", "", $date);
		
		$currentDate = date("Y-m-d");
		$currentDate = str_replace("-", "", $currentDate);		
		
		$res = "good";
		
		if ($currentDate > $date) 
			$res = "late";
		
		echo ($res);
	}
?>