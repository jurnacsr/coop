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
	$newDate = $_POST['date'];
	$newDay = $_POST['day'];
	
	$newDateCutoff = $_POST['dateCutoff'];
	$newDayCutoff = $_POST['dayCutoff'];
	
	// get the current order
	$check_query = "SELECT CURRENT_ORDER_MONTH FROM COOP_CONFIG;";
	$result = mysql_query($check_query, $con);
	if (!$result) die(mysql_error());
	while ($row = mysql_fetch_array($result))
	{
		$currentMonth = $row['CURRENT_ORDER_MONTH'];
	}
	// clear the wishlists for this order
	$wishlistDetailQuery = "TRUNCATE TABLE wishlist_item";
	$result = mysql_query($wishlistDetailQuery, $con);
	if (!$result) die(mysql_error());
	
	// clear the wishlist headers for this order
	$wishlistHeaderQuery = "TRUNCATE TABLE user";
	$result = mysql_query($wishlistHeaderQuery, $con);
	if (!$result) die(mysql_error());
	
	// clear the product lists for this order
	$productListQuery = "TRUNCATE TABLE item";
	if (!mysql_query($productListQuery, $con))
	{
		echo("database error: ".mysql_error());
		exit(0);
	}
	
	$orderDate = 'yyyy-mm-dd';
	$month = substr($newDate, 0, 3);
	$year = substr($newDate, 5, 2);
	switch ($month)
	{
		case 'JAN': {
		$month="01";
		break;
		}
		case 'FEB': {
		$month="02";
		break;
		}
		case 'MAR': {
		$month="03";
		break;
		}
		case 'APR': {
		$month="04";
		break;
		}
		case 'MAY': {
		$month="05";
		break;
		}
		case 'JUN': {
		$month="06";
		break;
		}
		case 'JUL': {
		$month="07";
		break;
		}
		case 'AUG': {
		$month="08";
		break;
		}
		case 'SEP': {
		$month="09";
		break;
		}
		case 'OCT': {
		$month="10";
		break;
		}
		case 'NOV': {
		$month="11";
		break;
		}
		case 'DEC': {
		$month="12";
		break;
		}
	}
	$year = "20".$year;
	if (strlen($newDay) == 1) $newDay = "0".$newDay;
	$orderDate = $year."-".$month."-".$newDay;
	
	$orderDateCutoff = 'yyyy-mm-dd';
	$month = substr($newDateCutoff, 0, 3);
	$year = substr($newDateCutoff, 5, 2);
	switch ($month)
	{
		case 'JAN': {
		$month="01";
		break;
		}
		case 'FEB': {
		$month="02";
		break;
		}
		case 'MAR': {
		$month="03";
		break;
		}
		case 'APR': {
		$month="04";
		break;
		}
		case 'MAY': {
		$month="05";
		break;
		}
		case 'JUN': {
		$month="06";
		break;
		}
		case 'JUL': {
		$month="07";
		break;
		}
		case 'AUG': {
		$month="08";
		break;
		}
		case 'SEP': {
		$month="09";
		break;
		}
		case 'OCT': {
		$month="10";
		break;
		}
		case 'NOV': {
		$month="11";
		break;
		}
		case 'DEC': {
		$month="12";
		break;
		}
	}
	$year = "20".$year;
	if (strlen($newDayCutoff) == 1) $newDayCutoff = "0".$newDayCutoff;
	$orderDateCutoff = $year."-".$month."-".$newDayCutoff;
	
	// update the current order entry in the config table
	$updateQuery = "UPDATE COOP_CONFIG SET CURRENT_ORDER_MONTH='".$newDate."', ORDER_DAY=".$newDay.", ORDER_DATE='".$orderDate."', ORDER_CUTOFF_DATE='".$orderDateCutoff."'";
	if (!mysql_query($updateQuery, $con))
	{
		echo("database error: ".mysql_error());
		exit(0);
	}
	echo('good');
?>