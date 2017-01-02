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
	var interests_box = $('.accesibility-interests');
	var select_all_elements = interests_box.find('input.select-all');
	var checkboxes = interests_box.find('.questions input');
	select_all_elements.change(selectAllChanged);
	checkboxes.change(updateSelectAll);
}

$( function() {
	$( "#accordion" ).accordion();
	initSelectAllBindings();
} );
