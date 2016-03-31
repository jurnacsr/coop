<?php
	
	// connect to database
	include("CONNECTION.php");
	include("ENVIRONMENT.php");
	
	// parse lists and make a separate query for each
	$nameList = $_POST['items'];
	$quantityList = $_POST['quantities'];
	$priceList = $_POST['prices'];
	$caseSizeList = $_POST['caseSizes'];
	
	$nameArray = explode(",",$nameList);
	$quantityArray = explode(",",$quantityList);
	$priceArray = explode(",",$priceList);
	$caseSizeArray = explode(",",$caseSizeList);
	
	if (count($nameArray) != count($quantityArray) || 
		count($nameArray) != count($priceArray) || 
		count($quantityArray) != count($priceArray))
	{
		echo ("Array values are inequal.  fill in the proper values and resubmit.");
		exit(0);
	}
	
	for ($i = 0; $i < count($nameArray)-1; $i++)
	{
		$query = "INSERT INTO item (ITEM_NAME, ITEM_QUANTITY_UNIT, ITEM_PRICE, ITEM_CASE_SIZE) VALUES ('".$nameArray[$i]."','".$quantityArray[$i]."',".$priceArray[$i].",".$caseSizeArray[$i].")";
		if (!mysql_query($query, $con))
		{
			echo($query."<br />");
			echo("Error in sql: ".mysql_error());
			exit(0);
		}
	}
	echo("good");
?>