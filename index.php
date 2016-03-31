<?php 
	session_start();
	include("scripts/php/CONNECTION.php");
	include("scripts/php/ENVIRONMENT.php");
?>
<html>
<head>
	<title>Jurnack's, Naturally! Co-Op</title>
	<script type="text/javascript" src="scripts/js/jquery.js"></script>
	<script type="text/javascript" src="scripts/js/coop_common.js"></script>
	<link rel="stylesheet" type="text/css" href="style/style.css" />
	<script>
		$(document).ready(function() {
			$("#dateSpan").text(getYear());
			
			checkCutoffDate();
			
			$("#searchWishlistForm").submit(searchWishlistSubmit);
			$("#administrationForm").submit(adminSubmit);
		});
	</script>
</head>
<body>
<div class="container">
	<h1 class="title">Welcome to the Jurnack's, Naturally!<p>Albert's Organics Buying Club Portal.</h1>
	<div class="entry help-div" id="help-div" >
		<p>Welcome to the Jurnack's, Naturally Albert's Organics Buying Club Portal.  </p>
		<p>To create a wishlist, enter your name below then press 'Create Wishlist.'  If your name has been already taken, try a combination of your first and last name, or add a number
		to the end (for example, if Bob is already taken, one could try Bob1, or Bob Jones).  
		<p>You can also review lists you have created for the current order period by using the Browse Wishlist entry system.  To browse a wishlist, enter the wishlist name (tthe name you used to create your wishlist) 
		and press 'Search For Wishlist'.  The wishlist will then be displayed. </p>
	</div>
	<div class="actionsDiv centered">
		<center>
		<div class="actionItem" id="createWishlistDiv">
			<div class="centered" id="loadingCreateWishlistDiv">
				<i>Checking Date...</i>
				<p><img src="loading.gif" id="loadingImage"/></p>
			</div>
			<div class="centered" id="createWishistItemCheckDiv" style="display: none;">
				<i>Checking for Items...</i>
				<p><img src="loading.gif" id="loadingImage"/></p>
			</div>
			<div class="centered" id="createWishlistNoItemsDiv" style="display: none;"> 
				There are no items for this order, or an order has not been created.  Please try again later.
			</div>
			<div id="createWishlistFormDiv" style="display: none">
			<form id="createWishlistForm" action="createWishlist.php" method="POST" >
				<b>Create Wishlist </b>
				<p>
					<input type="text" placeholder="Wishlist Name" id="createWishlistUsernameInput" name="user"/>
				</p>
				<p>
					<input type="submit" id="createWishlistSubmitButton" value="Create Wishlist" />
				</p>
				<div id="createWishlistUserExists" style="display: none;">There already is a wishlist with that name.  Try using something similar.</div>
				<div id="createWishlistBlankUsername" style="display: none;">You must enter a name to create a wishlist.</div>
				<div id="createWishlistCheckingUsername" style="display: none;"><img src="loading.gif" id="loadingImage"/></div>
				<div id="createWishlistDatabaseError" style="display: none;">There was a database error creating your wishlist.  Please try again.</div>
			</form>
			</div>
		</div>
		<div class="actionItem">
			<form id="searchWishlistForm" action="searchWishlists.php" method="POST">
				<b>Search for a Wishlist </b>
				<p>
					<input type="text" placeholder="Wishlist Name" id="searchWishlistNameInput" name="user"/>
				</p>
				<p>
					<input type="submit" value="Search for Wishlist" id="searchWishlistSubmitButton" />
				</p>
				<div id="searchWishlistLoading" style="display: none;"><img src="loading.gif" id="loadingImage"/></div>
				<div id="searchWishlistBlankUsername" style="display: none;">You must enter a name to search for a wishlist.</div>
				<div id="searchWishlistNoneFound" style="display: none;">No wishlist was found with that name.</div>
			</form>
		</div>
		<div class="actionItem">
			<form id="administrationForm" action="administration.php" method="POST">
				<b>Administration</b>
				<p>
					<input type="password" id="adminPasswordInput"/>
				</p>
				<p>
					<input type="submit" value="Log In" />
				</p>
			</form>
			<div id="loginFailedDiv" style="color: red; font-weight: bold; display: none"> 
				Login failed.
			</div>
		</div>
		<br style="clear: left;" />
		</center>
	</div>
</div>
<div class="footer centered">
	&copy;<span id="dateSpan"></span> jurnacks.com
</div>
</body>
</html>