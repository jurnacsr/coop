<?php

	include("CONNECTION.php");
	include("ENVIRONMENT.php");
	
	$user = $_POST['user'];
	
	// get the current order
	$check_query = "SELECT * FROM user WHERE USER_NAME='".$user."'";
	
	$result = mysql_query($check_query, $con);
	if (!$result) echo("**error**:".mysql_error());
	if (mysql_num_rows($result) > 0)
	{
		echo(mysql_num_rows($result));
		exit(0);
	}
	echo ("good");
?>