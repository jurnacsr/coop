<?php
	
	// connect to database
	include("CONNECTION.php");
	include("ENVIRONMENT.php");
	
	// parse lists and make a separate query for each
	$oldNameList = $_POST['oldItems'];
	$newItemList = $_POST['newItems'];
	$newPriceList = $_POST['newPrices'];
	$newUnitList = $_POST['newUnits'];
	$newCaseSizes = $_POST['newCaseSizes'];
	
	$oldNameArray = explode(",",$oldNameList);
	$newNameArray = explode(",",$newItemList);
	$newPriceArray = explode(",",$newPriceList);
	$newUnitArray = explode(",",$newUnitList);
	$newCaseSizeArray = explode(",",$newCaseSizes);
	$query = "NONE";
	
	for ($i = 0; $i < count($oldNameArray); $i++)
	{
		$query = "UPDATE item SET ITEM_NAME='".$newNameArray[$i]."', ITEM_QUANTITY_UNIT='".$newUnitArray[$i]."', ITEM_PRICE=".$newPriceArray[$i].", ITEM_CASE_SIZE=".$newCaseSizeArray[$i]." WHERE ITEM_NAME='".$oldNameArray[$i]."'";
		if (!mysql_query($query, $con))
		{
			echo($query."<br />");
			echo("Error in sql: ".mysql_error());
			exit(0);
		}
	}
	echo("good");
?>