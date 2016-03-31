<?php
	session_start();
	$adminAuth = $_SESSION['admin'];
	if ($adminAuth != 'YES' && $adminAuth != 'NO') 
		$_SESSION['admin'] = 'NO';
?>
<html>
<head>
	<title>Jurnack's, Naturally! Co-Op</title>
	<script type="text/javascript" src="scripts/js/jquery.js"></script>
	<link rel="stylesheet" type="text/css" href="styles/main_style.css" />
	<script>
		$(document).ready(function() {
			$(document).keypress(enter_check);
			$("#create_wishlist_button").click(wishlistClick);
			$("#browse_wishlist_button").click(browse_wishlist_click);
			$("#admin_submit").click(adminSubmitClick);
			
			// check the cutoff date
			check_cutoff_date();
		});
		
		function check_cutoff_date() {
			$.post('scripts/php/check_cutoff_date.php',
				{user: name},
				function(result)
				{
					if (result.indexOf("late") != -1)
					{
						$("#wishlist_entry_div").text("Sorry, it is too late to create a wishlist for this order.");					
					}
					else {
						$("#wishlist_entry_div").html("Enter your name:<input type=\"text\" id=\"name_wishlist\" style=\"margin-bottom: 5px; margin-left: 5px\"/><br /><input type=\"button\" class=\"submit_button\" id=\"create_wishlist_button\" value=\"Create Wishlist\" />");						
					}
				});
		}
		
		function browse_wishlist_click()
		{
			var name = $("#name_wishlist_search").val();
			$.post('scripts/php/wishlist_search.php',
			{user: name},
			function(result)
			{
				if (result.indexOf("**error**") != -1)
				{
					$("#search_invalid_div").text("Database error.  Please contact administrator.");					
				}
				if (result == 'fail')
				{
					$("#search_invalid_div").text("Could not find a wishlist under that name.");
				}
				else
				{
					$("#wishlist_search_div").html(result);
				}
			});
		}
		
		function enter_check(event)
		{
			if (event.which == 13)
			{
				if ($("#name_wishlist").is(":focus"))
				{
					wishlistClick();
				}
				if ($("#admin_password").is(":focus"))
				{
					adminSubmitClick();
				}
				if ($("#name_wishlist_search").is(":focus"))
				{
					browse_wishlist_click();
				}
			}
		}
		
		function expanderClick()
		{
			$(this).next("div.entry").slideToggle(500);
		}
		
		function wishlistClick()
		{
			$("#wishlist_create_error_div").text("");
			
			var createUser = $("#name_wishlist").val();
			var good = "no";
			if (createUser === undefined || createUser == "") 
			{
				alert("You must enter a name for the wishlist.");
				return false;
			}
			
			$("#wishlist_create_error_div").html("<img src='loading.gif' />Creating wishlist...");
			
			// verify that there is no other wishlist under that name
			$.post('scripts/php/check_wishlist_name.php',
			{user: createUser},
			function (result)
			{
				$("#wishlist_create_error_div").html("");	
				if (result == "good" && result.indexOf("**error**") == -1)
				{
					// the wishlist has a valid name - procede!
					$.post('scripts/php/create_wishlist.php',
					{user: createUser},
					function (result2)
					{
						$("#wishlist_entry_div").html(result2);
					});
				}
				else if (result.indexOf("**error**") != -1)
				{
					$("#wishlist_create_error_div").text("Database error.  Please contact administrator.");					
				}
				else
				{
					$("#wishlist_create_error_div").text("There already is a wishlist with that name.  Try using something similar.");
				}
			});
			
		}
		
		function adminSubmitClick()
		{
			$("div.admin_login").text("");
		
			// if admin password correct, set YES in sesssion
			var password = $("#admin_password").val();
			$.post('scripts/php/login_admin.php',
			{password: password},
			function(result)
			{
				if (result == 'fail')
				{
					$("div.admin_login").text("Incorrect password.");
				}
				else
				{
					$("div.admin_div_main").load("scripts/php/admin_options.php");
				}
			});
		}
	</script>
</head>
<body>
<div class="main">
	<h1>Welcome to the Jurnack's, Naturally!  
	<p>Albert's Organics Buying Club Portal.</h1>
	<div class="entry help-div" id="help-div" >
		<p>Welcome to the Jurnack's, Naturally Albert's Organics Buying Club Portal.  Here you can create and view wishlists of products that can be ordered through Jurnack's, Naturally!</p>
		<p>To create a wishlist, enter your name below then press 'Create Wishlist.'  If your name has been already taken, try a combination of your first and last name, or add a number
		to the end (for example, if Bob is already taken, one could try Bob1, or Bob Jones).  When you have created a wishlist, you will then be able to add items to the list.</p>
		</p>To add items, first click the 'Add Row' button.  This button will add a row to your wishlist, which contains the desired item, the desired quantity of that item, and 
		the price of that item.  A few new items will appear - a drop-down menu under Item Name and a text box under Quantity.  First, select an item from the Item Name drop-down
		box.  The drop-down box will contain the items that are available through the co-op for the current order period.  When you select an item, the unit name (like LB, EACH, etc.)
		will appear, along with a price per unit.  At the bottom of your wishlist will also be a Grand Total, 
		containing the cumulative price of all the goods you have requested.</p>
		<p>To add another item to your wishlist, press the 'Add Row' button again.  You can add as many items to your wishlist in this way.  If you add one too many rows, you can press the 
		'Remove Last Row' button, but be careful!  If you remove a row with an item and quantity specified, it will be deleted and cannot be recovered!</p>
		<p>To save the wishlist, press the 'Save Wishlist' button.  Once you have saved the wishlist, it cannot be edited again.  If you need to add items to your list, you'll need to 
		create a new wishlist.</p>
		<p>You can also review previous lists you have created by using the Browse Wishlist entry system.  To browse a wishlist, enter the wishlist name (the same as you typed above) 
		and press 'Search For Wishlist'.  The wishlist will then be displayed. </p>
	</div>
	<p class="expander">Start a Wishlist</p>
	<div class="entry" id="wishlistEntryDiv" >
		<div class="error_div" id="wishlist_create_error_div" style="margin-bottom: 5px;">
		</div>
		<div id="wishlist_entry_div">
		</div>
	</div>
	<p class="expander">Browse a Wishlist</p>
	<div class="entry" id="wishlist_search_div" >
		<div id="search_invalid_div"></div>
		Enter your name:<input type="text" id="name_wishlist_search" style="margin-bottom: 5px; margin-left: 5px" /><br />
		<input type="button" class="submit_button" id="browse_wishlist_button" value="Search For Wishlist" />
	</div>
	<p class="expander">Administrator</p>
	<div class="entry" id="adminAccessDiv">
		<div class="error_div admin_login"></div>
		<div class="admin_div_main">
			Admin Password:<input type="password" id="admin_password" style="margin-bottom: 5px; margin-left: 5px" /><br />
			<input type="button" class="submit_button" id="admin_submit" value="Login As Admin" />
		</div>
	</div>
</div>
</body>
</html>