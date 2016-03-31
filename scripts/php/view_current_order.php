<?php
	session_start();
	if ($_SESSION['admin'] != 'YES') {
		echo ("Unauthorized for this action.");
		exit(0);
	}
	
	$current_month = "NULL";
	
	// connect to database
	include("CONNECTION.php");
	include("ENVIRONMENT.php");
	
	// get the current order
	$currentOrder = "NULL";
	$orderQuery = "SELECT CURRENT_ORDER_MONTH FROM COOP_CONFIG";
	$result = mysql_query($orderQuery, $con);
	if (!$result) die("Query for current order identifier had an error: ".mysql_error());
	while ($row = mysql_fetch_array($result))
	{
		$currentOrder = $row['CURRENT_ORDER_MONTH'];
	}
?>
<html>
<head>
	<script>
		var count = 0;
	
		$(document).ready(function (){
			$(".item_edit_row").hide();
			$("#addNewItemButton").click(add_item_click);
			$("#addRowButton").click(add_row_click);	
			$("#removeRowButton").click(remove_row_click);
			$("#removeItemButton").click(remove_item_click);
			$(".edit_row_click").click(edit_item_click);
			$("#saveChangesButton").click(save_edited_items_click);
			
			//add_row_click();
		});
		
		function save_edited_items_click()
		{
			var oldItemList = "";
			var newItemList = "";
			var newPriceList = "";
			var newUnitList = "";
			var newCaseSizeList = "";
			var list = $('tr.displaying');
			for (var i = 0; i < list.length; i++)
			{
				var oldItemName, newItemName, newPrice, newUnit, newCaseSize, e;
				e = $(list[i]).children()[2];
				e = $(e).children()[0];
				
				// old item
				oldItemName = $(e).attr("class").split(":")[1];
				oldItemList = oldItemList + "" + oldItemName + ",";
				
				// new item
				newItemName = $(e).val();
				newItemList = newItemList + "" + newItemName + ",";
				
				// new price
				e = $(list[i]).children()[4];
				e = $(e).children()[0];
				newItemPrice = $(e).val();
				newPriceList = newPriceList + "" + newItemPrice + ",";
				
				// new unit
				e = $(list[i]).children()[3];
				e = $(e).children()[0];
				newUnit = $(e).val();
				newUnitList = newUnitList + "" + newUnit + ",";
				
				// new case size
				e = $(list[i]).children()[5];
				e = $(e).children()[0];
				newCaseSize = $(e).val();
				newCaseSizeList = newCaseSizeList + "" + newCaseSize + ",";
			}
			
			newCaseSizeList = newCaseSizeList.substring(0, newCaseSizeList.length-1);
			newUnitList = newUnitList.substring(0, newUnitList.length-1);
			newPriceList = newPriceList.substring(0, newPriceList.length-1);
			newItemList = newItemList.substring(0, newItemList.length-1);
			oldItemList = oldItemList.substring(0, oldItemList.length-1);
			
			$("#loadingOrderDiv").show();
			$("#viewOrderDiv").hide();
			$.post('scripts/php/edit_items.php',
				{oldItems: oldItemList,
				 newItems: newItemList,
				 newPrices: newPriceList,
				 newUnits: newUnitList,
				 newCaseSizes: newCaseSizeList
				},
				function(result)
				{
					result = $.trim(result);
					if (result == 'good')
					{
						//$("#viewOrderDiv").load("scripts/php/view_current_order.php");
						loadOrder();						
					}
			});
		}
		
		function edit_item_click()
		{
			var parent_row = $(this).parent().parent();
			var detail_row = parent_row.next(".item_edit_row");
			var detail_display = detail_row.css("display");
			if (detail_display == 'none')
			{
				$(this).attr("src", "minus.gif");
				detail_row.css("display", "table-row");
				detail_row.addClass("displaying");
			}
			else
			{
				$(this).attr("src", "plus.gif");
				detail_row.css("display", "none");
				detail_row.removeClass("displaying");
			}
		}
		
		function add_row_click()
		{
			$("#items_table tr:last").after("<tr>  "+
			"<td class='empty_table_data' width='5%'/><td class='empty_table_data' width='15%' />"+
			"<td class='table_data' width='25%'><input type='text' class='new_item_name' id='new_item_n- ame' /> </td>"+
			"<td class='table_data' width='25%'><input type='text' class='new_item_quantity' id='new_item_quantity' /> </td>"+
			"<td class='table_data' width='15%'><input type='text' size='6' class='new_item_price' id='new_item_price' /> </td>"+
			"<td class='table_data' width='20%'><input type='text' size='6' class='new_item_case_size' id='new_item_case_size' /> </td>"+
			"</tr>");
			count=count+1;
			$("#removeRowButton").removeAttr("disabled");
		}
		
		function remove_row_click()
		{
			if (count > 0)
			{
				count--;
				$("#items_table tr:last").remove();
			}
			if (count == 0)
			{
				$("#removeRowButton").attr("disabled", "disabled");				
			}
		}
		
		function add_item_click()
		{
			var itemNames = $("input.new_item_name").get();
			var itemQuantities = $("input.new_item_quantity").get();
			var itemPrices = $("input.new_item_price").get();
			var itemCaseSizes = $("input.new_item_case_size").get();
			
			var itemNameArray ="", itemQuantityArray="", itemPriceArray=""; var itemCaseSizeArray = "";
			
			// iterate through names, package as a nice array
			for (var i =0; i < itemNames.length; i++)
			{
				itemNameArray = itemNameArray +""+itemNames[i].value+",";
			}
			
			// iterate through quantities, package as nice array
			for (var i =0; i < itemQuantities.length; i++)
			{
				itemQuantityArray = itemQuantityArray +""+itemQuantities[i].value+",";
			}
			
			// iterate through the case sizes
			for (var i =0; i < itemCaseSizes.length; i++)
			{
				itemCaseSizeArray = itemCaseSizeArray +""+itemCaseSizes[i].value+",";
			}
			
			// iterate through prices, verifying the integrity, then package as nice array
			for (var i =0; i < itemPrices.length; i++)
			{
				var price = itemPrices[i].value;
				var dollars=price.split(".")[0], cents=price.split(".")[1];
				if (isNaN(dollars) || isNaN(cents))
				{
					alert("dollars and cents must be a number.");
					itemPrices[i].focus();
					$(itemPrices[i]).css("border", "solid 3px red");
				}
				else if (cents.length > 2)
				{
					alert("cents too long - try again.");
					itemPrices[i].focus();
					$(itemPrices[i]).css("border", "solid 3px red");
					
				}
				else
				{
					itemPriceArray = itemPriceArray +""+itemPrices[i].value+",";
					// make post request to add items
					$("#loadingOrderDiv").show();
					$("#viewOrderDiv").hide();
					$.post('scripts/php/add_items.php',
					{items: itemNameArray,
					 quantities: itemQuantityArray,
					 prices: itemPriceArray,
					 caseSizes: itemCaseSizeArray
					},
					function(result)
					{
						if (result == 'good')
						{
							//$("#viewOrderDiv").load("scripts/php/view_current_order.php");
							loadOrder();							
						}
					});
				}
			}
		}
		
		function remove_item_click()
		{
			if (!confirm("Are you sure you want to delete the checked items?  This cannot be undone.")) return false;
			var checkedItemNames = "";
			var checkedItemQuantities = "";
			$("#items_table_div input:checked").each(function()
			{
				var name = $(this).attr('name');
				var array = name.split(",");
				checkedItemNames = checkedItemNames + "" + array[0] +",";
				checkedItemQuantities = checkedItemQuantities + "" + array[1]+",";
			});
			checkedItemNames = checkedItemNames.substring(0, checkedItemNames.length-1);
			checkedItemQuantities = checkedItemQuantities.substring(0, checkedItemQuantities.length-1);
			
			$("#loadingOrderDiv").show();
			$("#viewOrderDiv").hide();
			
			// make post request to remove the checked items
			$.post('scripts/php/remove_items.php',
				{items: checkedItemNames,
				 quantities: checkedItemQuantities,
				},
				function(result)
				{
					if (result == 'good')
					{
						//$("#viewOrderDiv").load("scripts/php/view_current_order.php");
						loadOrder();					
					}
			});
		}
	</script>
</head>
<body>	
<?php	
	// get list of all items in the current order
	$check_query = "SELECT ITEM_NAME, ITEM_QUANTITY_UNIT, ITEM_PRICE, ITEM_CASE_SIZE FROM item ORDER BY ITEM_NAME ASC";
	$result = mysql_query($check_query, $con);
	if (!$result) die("Query for item selection by current order had an error: ".mysql_error());
	?>
	<div id="items_table_div">
	<table class="item_table" id="items_table" border="0" width="100%">
		<th class="empty_header_data" width="5%" align="left" />
		<td class="header_data" width="15%" align="left">Edit</th>
		<th class="header_data" width="25%" align="left">Name</th>
		<th class="header_data" width="25%" align="left">Unit</th>
		<th class="header_data" width="15%" align="left">Price</th>
		<th class="header_data" width="20%" align="left">Case Size</th>
	<?php
	while ($row = mysql_fetch_array($result))
	{
		?>
		<tr>
			<td class="table_data"><input type="checkbox" class="item_checkbox" id='checkbox' name='<?php echo($row['ITEM_NAME'].",".$row['ITEM_QUANTITY_UNIT']); ?>' /> </td>
			<td class="table_data"><img class="edit_row_click" id='<?php echo($row['ITEM_NAME'].",".$row['ITEM_QUANTITY_UNIT']); ?>' src="plus.gif" /></td>
			<td class="table_data"><?php echo($row['ITEM_NAME']); ?></td>
			<td class="table_data"><?php echo($row['ITEM_QUANTITY_UNIT']); ?></td>
			<td class="table_data"><?php echo($row['ITEM_PRICE']); ?></td>
			<td class="table_data"><?php echo($row['ITEM_CASE_SIZE']); ?></td>
		</tr>
		<tr class="item_edit_row">
			<td />
			<td></td>
			<td><input type="text" class="item_edit_name:<?php echo($row['ITEM_NAME']); ?>" value='<?php echo($row['ITEM_NAME']); ?>' size="10"/></td>
			<td><input type="text" class="item_edit_unit" value='<?php echo($row['ITEM_QUANTITY_UNIT']); ?>' size="12" /></td>
			<td><input type="text" class="item_edit_price" value='<?php echo($row['ITEM_PRICE']); ?>' size="6" /></td>
			<td><input type="text" class="item_edit_case_size" value='<?php echo($row['ITEM_CASE_SIZE']); ?>' size="6" /></td>
		</tr>
		<?php
	}
?>
	</table>
	<hr />
	<table class="item_table" id="commit_buttons_table">
	<tr>
		<td class="empty_table_data" />
		<td class="table_data"><input type="button" id="saveChangesButton" value="Save Edited Items" /></td>
		<td class="table_data"><input type="button" id="addRowButton" value="Add Row" /></td>
		<td class="table_data"><input type="button" id="removeRowButton" value="Remove Last Row" disabled="disabled" /></td>
		<td class="table_data"><input type="button" id="removeItemButton" value="Remove Selected Items" /></td>
		<td class="table_data"><input type="button" id="addNewItemButton" value="Save New Items" /></td>
	</tr>
	</table>
	</div>
</body>
</html>