<?php

	include("CONNECTION.php");
	include("ENVIRONMENT.php");
	
	$user = $_POST['user'];
	
	// get the user's name and ID
	$userId = '-1';
	$userQuery = "SELECT USER_ID FROM user WHERE USER_NAME='".$user."';";
	$result = mysql_query($userQuery, $con);	
	if (!$result) die(mysql_error());
	while ($row = mysql_fetch_array($result))
	{
		$userId = $row['USER_ID'];
	}
	echo($userId);
?>