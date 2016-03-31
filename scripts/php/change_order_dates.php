<?php
	session_start();
	if ($_SESSION['admin'] != 'YES') {
		echo ("Unauthorized for this action.");
		exit(0);
	}
	
	// connect to database
	include("CONNECTION.php");
	include("ENVIRONMENT.php");
	
	$newOrderDate = $_POST['newDate'];
	$newCutoffDate = $_POST['newCutoff'];
	$updateDate = "ORDER_DATE = '" . $newOrderDate . "'";
	$cutoffDate = "ORDER_CUTOFF_DATE = '" . $newCutoffDate . "'";
	
	$updateQuery = "UPDATE COOP_CONFIG SET " . $updateDate . ", " . $cutoffDate;
	
	if (!mysql_query($updateQuery, $con))
	{
		echo("database error: ".mysql_error(). " For query: " . $updateQuery);
		exit(0);
	}
	echo('good');
?>