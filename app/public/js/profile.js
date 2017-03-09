/*
profile.js is used in the profile.blade.php view.
*/

/**
When select-all checkboxes are checked, check every other associated checkbox.
*/
function selectAllChanged() {
	if ( $(this).is(':checked') ) {
		var tab = $(this).closest('[role="tabpanel"]');
		var questions = tab.find('.questions input[type="checkbox"]');
		questions.prop('checked', true);
	}
}

function updateSelectAll() {
	var tab = $(this).closest('[role="tabpanel"]');
	var selectAll = tab.find('input.select-all');
	var checkboxes = tab.find('.questions input[type="checkbox"]');
	var allChecked = true;
	checkboxes.each(function() {
		if ( !$(this).is(':checked') )
			allChecked = false;
	});
	selectAll.prop('checked', allChecked);
}

function initSelectAllBindings() {
	var interests_box = $('.accessibility-interests');
	var select_all_elements = interests_box.find('input.select-all');
	var checkboxes = interests_box.find('.questions input');
	var question_categories = interests_box.find('.category > .checkbox');
	select_all_elements.change(selectAllChanged);
	checkboxes.change(updateSelectAll);
	question_categories.each(function() {
		updateSelectAll.call(this);
	});
}

// uploads the profile photo by submitting the image upload form
// Called after selecting a photo and hitting "Open"
function upload()
{
    $("#photo-upload").submit();
}

// Opens a dialog for the user to select an image
function selectImageFile()
{
    $(".hidden-uploader").click();
}


$( function() {
	$( "#accordion" ).accordion();
	initSelectAllBindings();
} );
