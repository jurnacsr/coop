<?php
	$createName = $_POST['createUser'];
	
	// connect to database
	include("CONNECTION.php");
	include("ENVIRONMENT.php");
	
	$wishlistKey = $_POST['wishlistKey'];
	
	// delete from wishlist item table
	$deleteItemQuery = "DELETE FROM wishlist_item WHERE WISHLIST_ITEM_USER_ID = ".$wishlistKey;
	// delete from user table
	$deleteUserQuery = "DELETE FROM user WHERE USER_ID=".$wishlistKey;
	
	$result = mysql_query($deleteItemQuery, $con);
	if (!$result) die(mysql_error());
	
	$result = mysql_query($deleteUserQuery, $con);
	if (!$result) die(mysql_error());
	
	echo('y');
?>