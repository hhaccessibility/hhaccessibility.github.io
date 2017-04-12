/*
home.js provides various functions used in the home page.

This includes defining how the Google Map behaves and processes addresses entered by the user.
*/

//basic variables that I declared outside the function blocks to use them inside any of the two functions.
var map;
var marker;

/*
Returns the location search text entered by the user or its placeholder text if the text is otherwise empty.
*/
function getAddress()
{
	var address = $('#address').val().trim();
	if ( address === '' )
	{
		address = $('#address').attr('placeholder');
	}
	return address;
}

var  lastProcessedAddress = '';
function conditionalProcessAddress() {
	if( lastProcessedAddress.trim() !== getAddress().trim()  ) {
		lastProcessedAddress = getAddress();
		processAddress();
	}
}

var timer = undefined;
function delayedProcessAddress()
{
	if ( timer !== undefined )
	{
		clearInterval(timer);
		timer = undefined;
	}
	
	timer = setTimeout(conditionalProcessAddress, 1000);
}

/*
This function is where I initialized the map and then called the geocodeAddress function
perform the geocoding and display the address.
I had to set the zoom to 17 because that is the only point where I carousel-indicators
see the names of location around the users current location.
*/
function initMap()
{
     map = new google.maps.Map(document.getElementById('map'), {
		zoom: 17,
		draggable: false
	});

	google.maps.event.addDomListener(window, "resize", function() {
		var center = map.getCenter();
		google.maps.event.trigger(map, "resize");
		map.setCenter(center); 
	});
	
	conditionalProcessAddress();
	
	$('#address').bind('keyup change', delayedProcessAddress);
}

/*
Processes the user-provided address by retrieving corresponding longitude, latitude and saving the
results through an asynchronous request to the server.
*/
function processAddress()
{
	var address = getAddress();

	//creating a geocoder object
	var geocoder = new google.maps.Geocoder();

	geocoder.geocode({'address': address}, function(results, status)
	{
		//If this was successful, then...
		if (status === 'OK')
		{
			//get the latitude and longitude: Note this is part of what I would store to the database or session
			var latitude = results[0].geometry.location.lat();
			var longitude = results[0].geometry.location.lng();
			//center the map at that location
			map.setCenter(results[0].geometry.location);

			//place the marker on that location
			if ( marker === undefined )
			{
				marker = new google.maps.Marker({
					map: map
				});
			}
			marker.setPosition(results[0].geometry.location);
			saveUserLocation(address, latitude, longitude);
		} else {
			throw new Error('Geocode was not successful for the following reason: ' + status);
		}
	});
}

/*
Sends HTTP request to save the location information inside the
database or session
*/
function saveUserLocation(user_address, latitude, longitude)
{
	$.ajax({
		type: 'post',
		url: '/save-user-location',
		data: {
			'_token': $('input[name=_token]').val(),
			'latitude' : latitude,
			'longitude' : longitude,
			'address':   user_address
		},
		error: function(xhr, reason, ex ){
			throw new Error('there was an error in your code.'+ JSON.stringify(xhr));
		},
	});
}

function getLocationCategoryIdFromURL(url)
{
	var index = url.indexOf('location_tag_id=');
	if ( index !== -1 )
	{
		var choppedUrl = url.substring(index + 'location_tag_id='.length);
		
		// Remove parameters after the location category/tag id
		index = choppedUrl.indexOf('&');
		if ( index !== -1 )
		{
			choppedUrl = choppedUrl.substring(0, index);
		}

		return choppedUrl;
	}
	else
	{
		throw new Error('Unable to find location_tag_id in URL: ' + url);
	}
}

function getKeywordsInputElement()
{
	return $('[name="keywords"]');
}

function updateCategoryLinksOffKeywords()
{
	// We want the category links on the home page 
	// to filter also by keyword.
	// To accomplish this, we're adding the keywords as an encoded parameter
	// in the location search URL's of each location category.

	var keywords = getKeywordsInputElement().val().trim();
	keywords = encodeURIComponent(keywords); // encode keywords to safely use in URL.
	$('.location-tags a').each(function() {
		var $this = $(this);
		var oldUrl = $this.attr('href');
		var location_category_id = getLocationCategoryIdFromURL(oldUrl);
		var newUrl = '/location-search?location_tag_id=' + location_category_id;
		if ( keywords )
		{
			newUrl = newUrl + '&keywords=' + keywords;
		}
		$this.attr('href', newUrl);
	});
}

function bindCategoryLinksToKeywordInput()
{
	getKeywordsInputElement().keyup(updateCategoryLinksOffKeywords);
	updateCategoryLinksOffKeywords();
}

$(document).ready(bindCategoryLinksToKeywordInput);