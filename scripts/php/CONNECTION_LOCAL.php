<?php
	// user:
	//	Web user
	// connect to database
	$con = mysql_connect("localhost","localUser");
	if (!$con) {
		die("Error - could not connect to database.");
	}
	mysql_select_db("jurnac5_store", $con);