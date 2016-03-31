<?php
	session_start();
	$password = $_POST['password'];
	
	// connect to database
	include("CONNECTION.php");
	include("ENVIRONMENT.php");
	
	// check for wishlist under that name
	$check_query = "SELECT ADMIN_PASSWORD FROM COOP_CONFIG;";
	$result = mysql_query($check_query, $con);
	if (!$result) die(mysql_error());
	while ($row = mysql_fetch_array($result))
	{
		$dbPassword = $row['ADMIN_PASSWORD'];
		if ($dbPassword == $password) 
		{
			echo ('good');
			$_SESSION['admin'] = 'YES';
		}
		else {
			echo ('fail');
			$_SESSION['admin'] = 'NO';
		}
	}
?>