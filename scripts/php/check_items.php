<?php

	include("CONNECTION.php");
	include("ENVIRONMENT.php");
	
	// check for wishlist under that name
	$check_query = "SELECT * FROM item;";
	$result = mysql_query($check_query, $con);
	
	if (mysql_num_rows($result) == 0)
	{
		die("Something illegal happened.");
	}
	
	if (mysql_num_rows($result) == 0)
	{
		echo ('none');
	}else
	{
		echo ('good');
	}
?>