<?php
	session_start();
	$admin_okay = $_SESSION['admin'];
	if ($admin_okay != 'YES')
	{
		echo("You do not have administrator access.");
		exit(0);
	}
?>
<html>
<head>
	<script>
		$(document).ready(function(){
			$("#new_order_header").click(order_header_click);
			$("#viewOrderDiv").load("scripts/php/view_current_order.php");
			$("#newOrderDiv").hide();
			$("#continueNewOrderButton").click(new_order_click);
			$("#author_view_button").click(author_view_click);
			$("#product_view_button").click(product_view_click);
			$("#product_wish_view_button").click(product_wish_view_click);
			
			// load and parse the current order
			$("#current_order_span").load("scripts/php/load_current_date.php");	
			$("#current_cutoff_span").load("scripts/php/load_cutoff_date.php");	
		});
		
		function product_view_click()
		{
			$("#view_wishlist_div").load("scripts/php/product_view.php");
		}
		
		function product_wish_view_click()
		{
			$("#view_wishlist_div").load("scripts/php/product_wish_view.php");
		}
		
		function author_view_click()
		{
			$("#view_wishlist_div").load("scripts/php/author_view.php");		
		}
		
		function order_header_click()
		{
			$("#newOrderDiv").slideToggle(500);
		}
		
		function new_order_click()
		{
			if (confirm("Are you sure you want to create a new order?  This will purge ALL information for the current order - that includes wishlists and product lists."))
				if (confirm("This action cannot be undone.  Are you 100% sure?"))
				{
					$("div.alert_div").css("border", "0px solid black");
					var month = $("#monthSelect option:selected").val();
					var year = $("#yearSelect option:selected").val();
					var day = $("#daySelect option:selected").val();
					var date = month+"20"+year;
					var monthCutoff = $("#monthSelectCutoff option:selected").val();
					var yearCutoff = $("#yearSelectCutoff option:selected").val();
					var dayCutoff = $("#daySelectCutoff option:selected").val();
					var dateCutoff = monthCutoff+"20"+yearCutoff;
					
					// make post request to clear the old data.
					$.post('scripts/php/new_order.php',
					{date: date,
					 day:day,
					 dateCutoff: dateCutoff,
					 dayCutoff: dayCutoff},
					function(result)
					{
						if (result == 'good')
						{
							$("#viewOrderDiv").load("scripts/php/view_current_order.php");
							$("#current_order_span").load("scripts/php/load_current_date.php");	
							$("#current_cutoff_span").load("scripts/php/load_cutoff_date.php");	
							$("#newOrderDiv").slideToggle(500);					
							
						}
					});
				}
		}
	</script>
</head>
<body>
	<h1>Current Order: <span id="current_order_span"></span></h1>
	<h2>Current Order Cutoff Date: <span id="current_cutoff_span"></span></h2>
	<p class="admin_header" id="view_order_header">
		View/Edit Current Order
	</p>
	<div class="admin_div" id="viewOrderDiv">
	
	</div>
	<p class="admin_header">
		View Wishlists
	</p>
	<div class="admin_div" id="viewWishlistsDiv">
		<table id="">
			<tr>
				<td><input type="button" id="author_view_button" value="View by Author" /><br /></td>
				<td><a href="/scripts/php/author_view_printer.php" target="_blank">Printer-Friendly View</a></td>
			</tr>
			<tr>
				<td><input type="button" id="product_view_button" value="View by Product" /><br /></td>
				<td><a href="/scripts/php/product_view_printer.php" target="_blank">Printer-Friendly View</a></td>
			</tr>
			<tr>
				<td><input type="button" id="product_wish_view_button" value="View by Product Wishes" /><br /></td>
				<td><a href="/scripts/php/product_wish_view_print.php" target="_blank">Printer-Friendly View</a></td>
			</tr>
		</table>
		<div id="view_wishlist_div">
		
		</div>
	</div>
	<p class="admin_header" id="new_order_header">
		New Order
	</p>
	<div class="admin_div" id="newOrderDiv">
		<div class="alert_div">
			<h2>Caution!</h2>
			<p>Creating a new order will remove the old order 
			and everything associated with it.  This includes 
			wishlists and product lists.  This action cannot be undone. 
			Procede with caution.</p>
			<input type="button" id="continueNewOrderButton" value="I understand"/><br/>
			<br />
			<center>
				<table>
					<tr>
						<td>Select New Order Delivery Date:</td>
						<td>
							<select id="monthSelect">
								<option value="JAN">January</option>
								<option value="FEB">February</option>
								<option value="MAR">March</option>
								<option value="APR">April</option>
								<option value="MAY">May</option>
								<option value="JUN">June</option>
								<option value="JUL">July</option>
								<option value="AUG">August</option>
								<option value="SEP">September</option>
								<option value="OCT">October</option>
								<option value="NOV">November</option>
								<option value="DEC">December</option>
							</select>
						</td>
						<td>
							<select id="daySelect">
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
								<option value="4">4</option>
								<option value="5">5</option>
								<option value="6">6</option>
								<option value="7">7</option>
								<option value="8">8</option>
								<option value="9">9</option>
								<option value="10">10</option>
								<option value="11">11</option>
								<option value="12">12</option>
								<option value="13">13</option>
								<option value="14">14</option>
								<option value="15">15</option>
								<option value="16">16</option>
								<option value="17">17</option>
								<option value="18">18</option>
								<option value="19">19</option>
								<option value="20">20</option>
								<option value="21">21</option>
								<option value="22">22</option>
								<option value="23">23</option>
								<option value="24">24</option>
								<option value="25">25</option>
								<option value="26">26</option>
								<option value="27">27</option>
								<option value="28">28</option>
								<option value="29">29</option>
								<option value="30">30</option>
								<option value="31">31</option>
							</select>
						</td>
						<td>
							<select id="yearSelect">
								<option value="14" selected="selected">2014</option>
								<option value="15" >2015</option>
								<option value="16" >2016</option>
								<option value="17" >2017</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>Select New Order Wishlist Cutoff Date:</td>
						<td>
							<select id="monthSelectCutoff">
								<option value="JAN">January</option>
								<option value="FEB">February</option>
								<option value="MAR">March</option>
								<option value="APR">April</option>
								<option value="MAY">May</option>
								<option value="JUN">June</option>
								<option value="JUL">July</option>
								<option value="AUG">August</option>
								<option value="SEP">September</option>
								<option value="OCT">October</option>
								<option value="NOV">November</option>
								<option value="DEC">December</option>
							</select>
						</td>
						<td>
							<select id="daySelectCutoff">
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
								<option value="4">4</option>
								<option value="5">5</option>
								<option value="6">6</option>
								<option value="7">7</option>
								<option value="8">8</option>
								<option value="9">9</option>
								<option value="10">10</option>
								<option value="11">11</option>
								<option value="12">12</option>
								<option value="13">13</option>
								<option value="14">14</option>
								<option value="15">15</option>
								<option value="16">16</option>
								<option value="17">17</option>
								<option value="18">18</option>
								<option value="19">19</option>
								<option value="20">20</option>
								<option value="21">21</option>
								<option value="22">22</option>
								<option value="23">23</option>
								<option value="24">24</option>
								<option value="25">25</option>
								<option value="26">26</option>
								<option value="27">27</option>
								<option value="28">28</option>
								<option value="29">29</option>
								<option value="30">30</option>
								<option value="31">31</option>
							</select>
						</td>
						<td>
							<select id="yearSelectCutoff">
								<option value="14" selected="selected">2014</option>
								<option value="15" >2015</option>
								<option value="16" >2016</option>
								<option value="17" >2017</option>
							</select>
						</td>
					</tr>
				</table>
			</center>
		</div>
		<div class="admin_div">
		</div>
	</div>
</body>
</html>