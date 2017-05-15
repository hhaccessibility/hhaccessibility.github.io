/*
profile.js is used in the profile.blade.php view.
*/

var regions = [];

function initSelectAllText() {
	var isAllChecked = true;
	$("div.category").each( iterateCategory );
	selectAllToggle();
	bindCheckboxes();

	function iterateCategory(index,category) {
		$(category).find("input:checkbox").each( iternateCategoryCheckboxes );
		if(isAllChecked) {
			$(category).find("button.select-all").text("Unselect All");
		}
		else $(category).find("button.select-all").text("Select All");
		isAllChecked = true;
	}
	function iternateCategoryCheckboxes(index,checkbox) {
		if($(checkbox).prop("checked") == false) {
			isAllChecked = false;
		}
	}
}

function bindCheckboxes() {
	$("div.questions input:checkbox").change(uncheckLastBox);

	function uncheckLastBox() {
		var SelectAllBtn = $(this).closest("div.category").find("button.select-all");
		var NumberOfCheckboxes = $(this).closest("div.questions").find("input:checkbox").length;
		var NumberOfcheckedCheckboxes = $(this).closest("div.questions").find("input:checked").length;
		var isAllChecked = (NumberOfCheckboxes == NumberOfcheckedCheckboxes);
		var isSelectAllShowed = ( $(SelectAllBtn).text() === "Select All" );
		if( isAllChecked && isSelectAllShowed )
			$(SelectAllBtn).text("Unselect All");
		else
			$(SelectAllBtn).text("Select All");
	}
}

function selectAllToggle() {
	$('button.select-all').click(function(){
		if($(this).text() === "Select All") {
			$(this).closest("div.category").find("input:checkbox").prop('checked',true);
			$(this).text('Unselect All');
		} else {
			$(this).closest("div.category").find("input:checkbox").prop('checked',false);
			$(this).text('Select All');
		}
	})
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
	initSelectAllText();
	randomizePhotoURL();
	getCountryElement().change(updateRegionOptions);
	downloadRegions().then(updateRegionOptions);
} );
