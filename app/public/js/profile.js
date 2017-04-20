/*
profile.js is used in the profile.blade.php view.
*/

var regions = [];

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
	// indicate that the uploading is starting.
	$element = $('.photo-display .progress-element').addClass('uploading');
	
    $("#photo-upload").submit();
}

// Opens a dialog for the user to select an image
function selectImageFile()
{
    $(".hidden-uploader").click();
}

function randomizePhotoURL()
{
	$element = $('.photo-display .uploaded-photo');
	// If a photo was uploaded for the current user
	if ( $element.length !== 0 )
	{
		// Set a new URL so cache won't interfere with refreshing newly uploaded photos.
		$element.css({
			'background-image': "url(\'/profile-photo?t=" + (new Date().getTime()) + "\')"
		});
	}
}

function getCountryElement()
{
	return $('#home_country_id');
}

function updateRegionOptions()
{
	var country_id = parseInt(getCountryElement().val());
	var $datalist = $('#regions');
	$datalist.empty();
	regions.forEach(function(region) {
		if ( region.country_id === country_id )
			$datalist.append($('<option />').text(region.name));
	});
}

function downloadRegions()
{
	return $.ajax({
		'method': 'GET',
		'url': '/api/regions',
		'success': function(response) {
			regions = response;
		}
	});
}

$( function() {
	$( "#accordion" ).accordion();
	initSelectAllBindings();
	randomizePhotoURL();
	getCountryElement().change(updateRegionOptions);
	downloadRegions().then(updateRegionOptions);
} );
