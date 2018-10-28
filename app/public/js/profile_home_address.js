
var regions = [];

function getCountryElement()
{
	return $('#home_country_id');
}

function updateRegionOptions()
{
	var country_id = parseInt(getCountryElement().val());
	var $home_region = $('#home_region');
	$home_region.empty();
	//Below statement enables the default region to be empty or no region
	$home_region.append($('<option/>').text("-- Select Region --").val(''));
	regions.forEach(function(region) {
		if ( region.country_id === country_id )
		{
			if($home_region.data('value') === region.name)
			{
				$home_region.append($('<option />').val(region.name).attr('selected', 'selected').text(region.name));
			}
			else
			{
				$home_region.append($('<option/>').val(region.name).text(region.name));
			}
		}
	});
}

// Used for the State/Province datalist
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
	getCountryElement().change(updateRegionOptions);
	downloadRegions().then(updateRegionOptions);
});