<?php
	include("scripts/php/CONNECTION.php");
	include("scripts/php/ENVIRONMENT.php");
	
	if (!$_POST['user']) {
		header( 'Location: <?php echo($ROOT); ?>' ) ;
	}
	$name = $_POST['user'];
	
	
	// create a user for that name
	$createQuery = "INSERT INTO user (USER_NAME) VALUES ('".$name."')";
	if (!mysql_query($createQuery, $con))
	{
		echo("<b>Insert error:</b><br />".mysql_errno()." <br /> ".mysql_error());
		exit(0);
	}
	
	// get the ref id
	$refQuery = "SELECT USER_ID FROM user WHERE USER_NAME='".$name."'";
	$result = mysql_query($refQuery, $con);
	while ($row = mysql_fetch_array($result))
	{
		$refId = $row['USER_ID'];
	}
	
	// get the delivery date for the order
	$dateQuery = "SELECT ORDER_DATE FROM COOP_CONFIG";
	$result = mysql_query($dateQuery, $con);
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
	
	// get and populate a list of all the items
	$allItemsList = "";
	$allItemsQuery = "SELECT * FROM item;";
	if (!$allItemsResult = mysql_query($allItemsQuery, $con))
	{
		echo("<b>Item select error:</b><br />".mysql_errno()." <br /> ".mysql_error());
		exit(0);
	}
	while ($row = mysql_fetch_array($allItemsResult))
	{
		$itemName = $row['ITEM_NAME'];
		$unit = $row['ITEM_QUANTITY_UNIT'];
		$price = $row['ITEM_PRICE'];
		$case = $row['ITEM_CASE_SIZE'];
		
		$allItemsList = $allItemsList."".$itemName."*".$unit."*".$price."*".$case."@";
	}
?>
<html>
<head>
	<title>Jurnack's, Naturally! Co-Op - Create Wishlist</title>
	<script type="text/javascript" src="scripts/js/jquery.js"></script>
	<script type="text/javascript" src="scripts/js/coop_common.js"></script>
	<link rel="stylesheet" type="text/css" href="style/style.css" />
	<script>
		var count = 0;
		var row_count = 0;
		var refId = '<?php echo($refId);?>';
		var user = '<?php echo($name);?>';
		var total = 0;
		
		var masterItemList = '<?php echo($allItemsList); ?>';
		
		var itemNameList = [];
		var itemUnitList = {};
		var itemPriceList = {};
		var itemCaseList = {};
		
		$(document).ready(function () {
			$("#addRowButton").click(add_row_click);
			$("#removeRowButton").click(remove_row_click);
			$("#saveWishlistButton").click(save_wishlist_click);
			
			$('#sendWishlistEmail').change(wishlist_email_click);
			$("#dateSpan").text(getYear());
			
			populateItemList();
			
			add_row_click();
		});
		
		function populateItemList() {
			var rawItemList = masterItemList.split("@");
			
			for (var i = 0; i < rawItemList.length; i++) {
				var rawItem = rawItemList[i];
				
				var list = rawItem.split("*");
				
				var iName = list[0];
				if (iName == '') {
					break;
				}
				
				var iUnit = list[1];
				var iPrice = list[2];
				var iCase = list[3];
				
				itemNameList[i] = iName;
				itemUnitList[iName] = iUnit;
				itemPriceList[iName] = iPrice;
				itemCaseList[iName] = iCase;
			}
		}
		
		function wishlist_email_click() {
			var b = $('#sendWishlistEmail');
			var i = $('#emailEntryDiv');
			
			if (b.is(':checked')) {
				i.show();
			} else {
				i.hide();
			}
		}
		
		function restore_wishlist_div()
		{
			$("#wishlist_entry_div").html("Enter your name:<input type='text' id='name_wishlist' /><br /><input type='button' class='submit_button' id='create_wishlist_button' value='Create Wishlist' onclick='wishlistClick()' />");
		}
		
		function add_row_click()
		{
			row_count++;
			count++;
			
			var selectElement = "<tr class='wishlist_row' align='center'>"+
				"<td class='wishlist_data'><select class='selector item_select"+
				count+"'><option value='default' default='default'>--Choose a Product--</option>";
			
			var append = "";
			// get the product list
			for (var i = 0; i < itemNameList.length; i++) {
				append = append + "<option value='"+itemNameList[i]+"'>"+itemNameList[i]+"</option>";
			}
			selectElement = selectElement + "" + append + "</select></td><td class='item_unit"+count+"' /><td class='quantity"+count+"' align='center'><input type='text' size='4' id='quantity"+count+"' onchange='item_price_change($(this))' /></td><td class='price"+count+"' /><td class='total_price"+count+"' align='center'/></tr>";
			$(".wishlist_table tr:last").after(selectElement);
			$(".wishlist_totals_table").css("display", "block");
			$(".selector").bind('change', item_select_get_unit_price);
			
			$("#removeRowButton").removeAttr("disabled");
		}
		
		function item_select_get_unit_price()
		{			
			var unit_element = $($(this).parent().parent().children().get(1));
			var price_element = $($(this).parent().parent().children().get(3));
			var this_class = $(this).attr('class');
			this_class = this_class.split(" ")[1];
			var itemName =  $("."+this_class).val();
			var itemUnit = itemUnitList[itemName];
			var itemPrice = itemPriceList[itemName];
			unit_element.text(itemUnit);
			price_element.text(itemPrice);
		}
		
		function item_price_change(object)
		{
			var price_element = $(object.parent().parent().children().get(3));
			var total_element = $(object.parent().parent().children().get(4));
			var price = price_element.text();
			var quantity = $("#"+object.attr('id')).val();
			total_element.text(CurrencyFormatted(price*quantity));
			update_total_box();
		}
		
		function update_total_box()
		{
			var total = 0;
			// get list of price_elements
			var list = $('td[class^="total_price"]');
			for (var i = 0; i < list.length; i++)
			{
				total = total + parseFloat($(list[i]).text());
			}
			$("#total_amount").text(CurrencyFormatted(total));
		}
		
		function CurrencyFormatted(amount)
		{
			var i = parseFloat(amount);
			if(isNaN(i)) { i = 0.00; }
			var minus = '';
			if(i < 0) { minus = '-'; }
			i = Math.abs(i);
			i = parseInt((i + .005) * 100);
			i = i / 100;
			s = new String(i);
			if(s.indexOf('.') < 0) { s += '.00'; }
			if(s.indexOf('.') == (s.length - 2)) { s += '0'; }
			s = minus + s;
			return s;
		}
		
		function remove_row_click()
		{
			if (row_count > 0)
			{
				row_count--;
				$(".wishlist_table tr:last").remove();
			}
			if (row_count == 0)
			{
				$("#removeRowButton").attr("disabled", "disabled");				
			}
			update_total_box();			
		}
		
		function isNumber(n) {
			return !isNaN(parseFloat(n)) && isFinite(n);
		}
		
		function save_wishlist_click()
		{
			// check for email send
			var b = $('#sendWishlistEmail');
			var i = $('#wishlistEmailInput');
			var e = $('#emailErrorDiv');
			var sendEmail = '0';
			var email = '';
			
			if (b.is(':checked')) {
				email = i.val();
				if (email == '') {
					e.text("Please enter an email, or uncheck 'Send Email Confirmation' checkbox.");
					return;
				}
				
				if (email.indexOf("@") == -1 || email.indexOf(".") == -1) {
					e.text("Please make sure your email is formatted correctly.");		
					return;			
				}
			} 
			sendEmail = '1';
			
			console.log(sendEmail + ": "+email);
						
			// package data
			var nameData = "";
			var quantityData = "";
			var unitData = "";
			var priceData = "";
			var totalPriceData = "";
			
			// get all the product names
			var list = $('select[class^="selector"]');
			for (var i =0; i < list.length; i++)
			{
				var nameSelectElement = $(list[i]);
				nameSelectElement.removeClass("error_border");
				console.log(nameSelectElement.val());
				if (nameSelectElement.val() == "default")
				{
					alert("Please make sure you have selected an item in all rows, or removed the blank rows.");
					nameSelectElement.addClass("error_border");
					return false;
				}
				nameData = nameData + "" + nameSelectElement.val() + ",";
			}
			nameData = nameData.substring(0, nameData.length-1);
			
			// get the quantities
			list = $('input[id^="quantity"]');
			for (var i =0; i < list.length; i++)
			{
				var nameSelectElement = $(list[i]);
				nameSelectElement.removeClass("error_border");
				if (nameSelectElement.val() == "")
				{
					alert("Please make sure all quantity fields are entered properly.");
					nameSelectElement.addClass("error_border");
					$("#submitSpan").html("<input type='button' id='saveWishlistButton' value='Save Wishlist' />");
					$("#saveWishlistButton").click(save_wishlist_click);
					return false;
				}
				if (!isNumber(nameSelectElement.val()))
				{
					alert("Please make sure all quantity fields are numbers.");
					nameSelectElement.addClass("error_border");
					$("#submitSpan").html("<input type='button' id='saveWishlistButton' value='Save Wishlist' />");
					$("#saveWishlistButton").click(save_wishlist_click);
					return false;
				}
				quantityData = quantityData + "" + nameSelectElement.val() + ",";
			}
			quantityData = quantityData.substring(0, quantityData.length-1);
			
			// get the units
			list = $('td[class^="item_unit"]');
			for (var i =0; i < list.length; i++)
			{
				var nameSelectElement = $(list[i]);
				unitData = unitData + "" + nameSelectElement.text() + ",";
			}
			unitData = unitData.substring(0, unitData.length-1);
			//alert(unitData);
			
			// get the prices
			list = $('td[class^="price"]');
			for (var i =0; i < list.length; i++)
			{
				var nameSelectElement = $(list[i]);
				priceData = priceData + "" + nameSelectElement.text() + ",";
			}
			priceData = priceData.substring(0, priceData.length-1);
			
			// get the total prices
			list = $('td[class^="total_price"]');
			for (var i =0; i < list.length; i++)
			{
				var nameSelectElement = $(list[i]);
				totalPriceData = totalPriceData + "" + nameSelectElement.text() + ",";
			}
			totalPriceData = totalPriceData.substring(0, totalPriceData.length-1);
						
			// disable the save button
			$("#saveWishlistButton").prop("disabled", true);
			$("#mainCreateWishlistDiv").hide();
			$("#savingDiv").show();
			
			// make post to script to save the wishlist
			$.post('scripts/php/save_wishlist.php',
			{itemList: nameData,
			 quantityList: quantityData,
			 unitList: unitData,
			 priceList: priceData,
			 totalPriceList: totalPriceData,
			 id: refId,
			 user: user,
			 sendEmail: sendEmail,
			 email: email},
				function (result)
				{
					console.log(result);
					if (result=="good")
					{
						// wishlist save was successful
						$("#saveSuccessDiv").show();
						$("#savingDiv").hide();						
					}
				}
			);
		}
	</script>
</head>
<body>
<div class="container">
	<div id="saveSuccessDiv" style="display: none">
		<h1 class="title">Wishlist for <?php echo($name);?> saved successfully!</h1>
		<h2 class="title"><a href="<?php echo($ROOT); ?>">Click here to go back to the main site</a></h2>
	</div>
	<div id="saveErrorDiv" style="display: none">
		<h1 class="title">There was an error when saving the wishlist for <?php echo($name);?>.</h1>
		<h2 class="title"><a href="<?php echo($ROOT); ?>">Click here to go back to the main site and try again</a></h2>
	</div>
	<div id="savingDiv" style="display: none">
		<h1 class="title">Saving wishlist for <?php echo($name);?>...</h1>
		<h2 class="title"><img src="loading.gif" id="loadingImage" /></h2>
	</div>
	
	<div id="mainCreateWishlistDiv">
	<h1 class="title">Create Wishlist for <?php echo($name);?></h1>
	</p>To add items, first click the 'Add Row' button.  This button will add a row to your wishlist, which contains the desired item, the desired quantity of that item, and 
	the price of that item.  A few new items will appear - a drop-down menu under Item Name and a text box under Quantity.  First, select an item from the Item Name drop-down
	box.  The drop-down box will contain the items that are available through the co-op for the current order period.  When you select an item, the unit name (like LB, EACH, etc.)
	will appear, along with a price per unit.  At the bottom of your wishlist will also be a Grand Total, 
	containing the cumulative price of all the goods you have requested.</p>
	<p>To add another item to your wishlist, press the 'Add Row' button again.  You can add as many items to your wishlist in this way.  If you add one too many rows, you can press the 
	'Remove Last Row' button, but be careful!  If you remove a row with an item and quantity specified, it will be deleted and cannot be recovered!</p>
	<p>To save the wishlist, press the 'Save Wishlist' button.  Once you have saved the wishlist, it cannot be edited again.  If you need to add items to your list, you'll need to 
	create a new wishlist.</p>
	<div class="actionsDiv centered">
	<center><h3>Delivery Date: <?php echo($finalDate); ?></h3>
	<input type="checkbox" id="sendWishlistEmail" /> Send confirmation email of wishlist?
	<div id="emailEntryDiv" style="display: none; padding-top: 10px;">
		<form action="javascript:function heyMom(){return false;}">
			Email: <input type="text" id="wishlistEmailInput" />
		</form>
	</div>
	<div id="emailErrorDiv" style="font-weight: bold;"></div>
	</center>
	<div id="no_rows_div" ></div>
	<div id="">
	<table class="wishlist_table" border="0" width="100%" style=" margin-top: 20px;">
		<th class="wishlist_item_header" width="30%"> Item Name </th>
		<th class="wishlist_item_header" width="25%"> Item Unit </th>
		<th class="wishlist_item_header" width="10%"> Quantity </th>
		<th class="wishlist_item_header" width="20%"> Price Per Unit </th>
		<th class="wishlist_item_header" width="15%" align="right"> Total Price</th>
		<tr class="empty_wishlist_row" />
	</table>
	<table class="wishlist_totals_table" style="display: none; margin-top: 20px;">
		<tr>
			<td class="empty_table_data" width="30%" />
			<td class="empty_table_data" width="25%"/>
			<td class="empty_table_data" width="10%" />
			<td class="empty_table_data" width="20%"/>
			<td class="table_data" width="15%" ><h4>Grand Total: <span id="total_amount"> -----</span></h4></td>
		</tr>
	</table>
	<table class="wishlist_button_table">
		<tr>
			<td class="empty_table_data" />
			<td class="table_data"><input type="button" id="addRowButton" value="Add Row" /></td>
			<td class="table_data"><input type="button" id="removeRowButton" value="Remove Last Row" disabled="disabled" /></td>
			<td class="table_data"><span id="submitSpan"><input type="button" id="saveWishlistButton" value="Save Wishlist" /></span></td>
		</tr>
	</table>
	</div>
	</div>
	</div>
</div>

<div class="footer centered">
	&copy;<span id="dateSpan"></span> jurnacks.com
</div>
</body>
</html>