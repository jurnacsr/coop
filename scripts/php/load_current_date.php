<?php
	
	$current_month = "NULL";
	
	// connect to database
	include("CONNECTION.php");
	include("ENVIRONMENT.php");
	
	// check for wishlist under that name
	$check_query = "SELECT CURRENT_ORDER_MONTH, ORDER_DAY, ORDER_DATE FROM COOP_CONFIG;";
	$result = mysql_query($check_query, $con);
	if (mysql_num_rows($result) == 0)
	{
		die("Something illegal happened.");
	}
	if (!$result) die(mysql_error());
	while ($row = mysql_fetch_array($result))
	{
		$date = $row['ORDER_DATE'];
		
		$date = explode("-", $date);
		$finalDate = "";
		switch ($date[1])
		{
			case '01': {
			$month="January";
			break;
			}
			case '02': {
			$month="February";
			break;
			}
			case '03': {
			$month="March";
			break;
			}
			case '04': {
			$month="April";
			break;
			}
			case '05': {
			$month="May";
			break;
			}
			case '06': {
			$month="June";
			break;
			}
			case '07': {
			$month="July";
			break;
			}
			case '08': {
			$month="August";
			break;
			}
			case '09': {
			$month="September";
			break;
			}
			case '10': {
			$month="October";
			break;
			}
			case '11': {
			$month="November";
			break;
			}
			case '12': {
			$month="December";
			break;
			}
		}
		echo ($month . " " .$date[2] . " " . $date[0]);
	}
?>