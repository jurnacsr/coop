<?php
	
	$itemList = $_POST['itemList'];
	$quantityList = $_POST['quantityList'];
	$unitList = $_POST['unitList'];
	$priceList = $_POST['priceList'];
	$wishlistId = $_POST['id'];
	$user = $_POST['user'];
	$totalPriceList = $_POST['totalPriceList'];
	$saveEmail = $_POST['sendEmail'];
	
	include("CONNECTION.php");
	include("ENVIRONMENT.php");
	
	$itemArray = explode(",",$itemList);
	$quantityArray = explode(",",$quantityList);
	$unitArray = explode(",",$unitList);
	$priceArray = explode(",",$priceList);
	$totalPriceArray = explode(",",$totalPriceList);
	for ($i = 0; $i < count($itemArray); $i++)
	{
		$item = $itemArray[$i];
		$quantity = $quantityArray[$i];
		$price = $priceArray[$i];
		$unit = $unitArray[$i];
		$totalPrice = $totalPriceArray[$i];
		
		// get item id by name
		$itemId = "";
		$itemIdQuery = "SELECT ITEM_ID from item WHERE ITEM_NAME='".$item."'";
		$result = mysql_query($itemIdQuery, $con);
		if (!$result) die(mysql_error());
		while ($row = mysql_fetch_array($result))
		{
			$itemId = $row['ITEM_ID'];
		}
		
		
		$query = "INSERT INTO wishlist_item (WISHLIST_ITEM_USER_ID, WISHLIST_ITEM_ITEM_ID, WISHLIST_ITEM_QUANTITY) VALUES (".$wishlistId.",'".$itemId."',".$quantity.")";
		if (!mysql_query($query, $con))
		{
			echo("Error in sql: ".mysql_error());
			exit(0);
		}
	}
	if ($saveEmail == '1' && $ENV != 'dev') {
	
		// get the order date
		$orderDate = "";
		$dateQuery = "SELECT ORDER_DATE from COOP_CONFIG";
		$result = mysql_query($dateQuery, $con);
		if (!$result) die(mysql_error());
		while ($row = mysql_fetch_array($result))
		{
			$orderDate = $row['ORDER_DATE'];
		}
		// format the date
		$orderDate = explode("-", $orderDate);
		$finalDate = "";
		$month = "";
		switch ($orderDate[1])
		{
			case '01': {
			$month="January";
			break;
			}
			case '02': {
			$month="February";
			break;
			}
			case '03': {
			$month="March";
			break;
			}
			case '04': {
			$month="April";
			break;
			}
			case '05': {
			$month="May";
			break;
			}
			case '06': {
			$month="June";
			break;
			}
			case '07': {
			$month="July";
			break;
			}
			case '08': {
			$month="August";
			break;
			}
			case '09': {
			$month="September";
			break;
			}
			case '10': {
			$month="October";
			break;
			}
			case '11': {
			$month="November";
			break;
			}
			case '12': {
			$month="December";
			break;
			}
		}
		$finalDate = $month . " " .$orderDate[2] . " " . $orderDate[0];

		$to = $_POST['email'];
		$subject = "Co-Op Wishlist Email Confirmation";
		
		$grandTotal = 0;
		
		$items = "";
		
		$tdOpen = "<td style='padding: 5px;'>";
		$tdEnd = "</td>";
		
		$trOpen = "<tr>";
		$trEnd = "</tr>";
				
		for ($i = 0; $i < count($itemArray); $i++)
		{
			$item = $itemArray[$i];
			$quantity = $quantityArray[$i];
			$price = $priceArray[$i];
			$unit = $unitArray[$i];
			$totalPrice = $totalPriceArray[$i];
			
			$items = $items . "" . $trOpen;
			$items = $items . "" . $tdOpen . "" . $item . "" . $tdEnd;
			$items = $items . "" . $tdOpen . "" . $quantity . "" . $tdEnd;
			$items = $items . "" . $tdOpen . "" . $price . "" . $tdEnd;
			$items = $items . "" . $tdOpen . "" . $unit . "" . $tdEnd;
			$items = $items . "" . $tdOpen . "" . $totalPrice . "" . $tdEnd;
			$items = $items . "" . $trEnd;
			
			$grandTotal += $totalPrice;
		}
		
		if ($ENV != 'dev') {
			setlocale(LC_MONETARY, 'en_US');
			$grandTotal = money_format('%i', $grandTotal);
		}
		
		$grandTotal = str_replace("USD", "", $grandTotal);

		$headers = 'Content-type: text/html; charset=iso-8859-1' . "\n";
		$headers .= 'From: Coop-Confirmation@jurnacks.com' . "\n";
		$headers .= 'MIME-Version: 1.0' . "\n";

		$body = "
		<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3.org/TR/html4/loose.dtd\">
		   <html>
		   <head>
			<title></title>
		   </head>
		   <body>
		   <div id='content' align='center' style='width: 70%;  border: 2px solid black; border-radius: 10px; background-color: #D6E0F5; margin-left: auto; margin-right: auto;'>
		   <center><h2>Wishlist Confirmation Email</h2></center>
		   <div id='info' style='margin-bottom: 15px;'>
			<table>
				<tr>
					<td style='padding: 0px 20px 0px'><h3>Name: $user</h3></td>
				</tr>
				<tr>
					<td style='padding: 0px 20px 0px'>Email: $to</td>
				</tr>
				<tr>
					<td style='padding: 0px 20px 0px'><h3>Delivery Date: $finalDate</h3></td>
				</tr>
			</table>
		   </div>
		   <div id='mainData' align='center'>
			<table id='dataTable'>
				<tr>
					<th>Item name</th>
					<th>Quantity</th>
					<th>Unit</th>
					<th>Unit Price</th>
					<th>Total Price</th>
				</tr>
				<tr>
				$items
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td>Grand Total:</td>
					<td style='padding: 5px;'>$grandTotal</td>
			</table>
			</div>
			</div>
			</body>
			</html>";

		mail($to, $subject, $body, $headers);
	}
	echo("good");
?>