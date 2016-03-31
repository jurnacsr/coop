// diable all cachine for AJAX requests
$.ajaxSetup({
	cache: false
});

// variables for new chunks of text
var LATE_WISHLIST_HTML = "<div class='centered' id='loadingCreateWishlistDiv'><b>Sorry, it is too late to create a wishlist for this order.</b></div>";

// Gets the current year for the page footer
function getYear() {
	return (new Date()).getFullYear();
}

// checks the cutoff date for this current order
function checkCutoffDate() {
	var createWishlistDivHtml = $("#createWishlistDiv").html();
	$.post('scripts/php/check_cutoff_date.php',
		{user: name},
		function(result)
		{
			console.log("Cutoff Date: "+result);
			if (result.indexOf("late") != -1)
			{
				$("#createWishlistDiv").html(LATE_WISHLIST_HTML);
			}
			else {
				$("#loadingCreateWishlistDiv").hide();
				$("#createWishistItemCheckDiv").show();				
				$("#createWishlistForm").live('submit', createWishlistFormSubmit);
				checkItems();
			}
	});
}

function checkItems() {
	$.post('scripts/php/check_items.php',
		function(result) {
			console.log("Items check: "+result);
			if (result == 'good') {
				$("#createWishistItemCheckDiv").hide();	
				$("#createWishlistFormDiv").show();	
			
			} else {
				$("#createWishistItemCheckDiv").hide();		
				$("#createWishlistNoItemsDiv").show();			
			}
	});
}

// check username validity when the create wishlist form is submitted
function createWishlistFormSubmit() { 
	$("#createWishlistBlankUsername").hide();
	$("#createWishlistUserExists").hide();
	$("#createWishlistBlankUsername").hide();
	$("#createWishlistCheckingUsername").hide();
	$("#createWishlistDatabaseError").hide();
	$("#createWishlistSubmitButton").prop("disabled", true);
	var createUser = $("#createWishlistUsernameInput").val();
	var good = false;
	
	// make sure the user entered something
	if (createUser === undefined || createUser == "") 
	{
		$("#createWishlistBlankUsername").show();
		$("#createWishlistSubmitButton").prop("disabled", false);
		return false;
	}
	$("#createWishlistCheckingUsername").show();
	
	// verify that there is no other wishlist under that name
	$.ajax(
	{
		url: 'scripts/php/check_wishlist_name.php',
		data: {user: createUser},
		type: 'POST',
		async: false,
		success: function (result)
		{
			console.log(result);
			$("#createWishlistCheckingUsername").hide();
			console.log(result);
			if (result == "good" && result.indexOf("**error**") == -1)
			{	
				good = true;
			}
			else if (result.indexOf("**error**") != -1)
			{
				$("#createWishlistDatabaseError").show();					
			}
			else
			{
				$("#createWishlistUserExists").show();	
				$("#createWishlistSubmitButton").prop("disabled", false);			
			}
		}
	});
	
	return good;
}

// check if that username exists when browsing for a wishlist
function searchWishlistSubmit() {
	$("#searchWishlistBlankUsername").hide();
	$("#searchWishlistNoneFound").hide();
	$("#searchWishlistSubmitButton").prop("disabled", true);
	var name = $("#searchWishlistNameInput").val();
	
	// make sure the user entered something
	if (name === undefined || name == "") 
	{
		$("#searchWishlistBlankUsername").show();
		$("#searchWishlistSubmitButton").prop("disabled", false);
		return false;
	}
	$("#searchWishlistLoading").show();
	
	var good = false;
	// verify that a wishlist exists under that name
	$.ajax(
	{
		url: 'scripts/php/wishlist_exists_search.php',
		data: {user: name},
		type: 'POST',
		async: false,
		success: function (result)
		{
			if (result != -1) {
				good = true;
			}
			else {
				$("#searchWishlistNoneFound").show();
			}
		}
	});
	
	$("#searchWishlistLoading").hide();
	$("#searchWishlistSubmitButton").prop("disabled", false);
	return good;

}

function adminSubmit() {
	var password = $("#adminPasswordInput").val();
	$("#loginFailedDiv").hide();
	
	var good = false;
	
	// make post to verify password
	$.ajax(
	{
		url: 'scripts/php/login_admin.php',
		data: {password: password},
		type: 'POST',
		async: false,
		success: function (result)
		{
			console.log(result);
			if (result == 'fail') {
				console.log("--"+result+"--");
				$("#loginFailedDiv").show();
			} else {
				good = true;
			}
		}
	});
	
	return good;
}