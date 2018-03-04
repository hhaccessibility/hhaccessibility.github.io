/*
utils.js contains a few functions that may be useful for a few different JavaScript files in AccessLocator.
*/

/*
DelayedNonEmptyTimer is an object to filter very frequent events down to a frequency that efficiently uses bandwidth and server-resources.  For example, you may want to call the delayedProcess method with every key stroke but limit the frequency of HTTP requests to at most 1 request per second.

@param time_limit is a time limit in miliseconds.
@param getValue is a function used to retrieve a value.  This could be for getting a value from an input element.
@param updateCallback is a function called when the time limit was reached and getValue() returns a non-empty string.
	updateCallback would typically involve sending an HTTP request.
*/
function DelayedNonEmptyTimer(time_limit, getValue, updateCallback) {
	var self = this;
	var timer = undefined;

	function delayedProcess()
	{
		if ( timer !== undefined )
		{
			clearInterval(timer);
			timer = undefined;
		}

		timer = setTimeout(function() {
			if ( getValue().trim() !== '' ) {
				updateCallback(getValue());
			}
		}, time_limit);
	}

	self.delayedProcess = delayedProcess;
}

//To calculate the radian value of a point
function degreesToRadians(degrees) {
  return degrees * Math.PI / 180;
}

// To calculate the distance between two points on a map
function calculateDistance(pt1, pt2) {
  var R = 6378137; // Earth's mean radius in meter
  var dLat = degreesToRadians(pt2.lat() - pt1.lat());
  var dLong = degreesToRadians(pt2.lng() - pt1.lng());
  var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
    Math.cos(degreesToRadians(pt1.lat())) * Math.cos(degreesToRadians(pt2.lat())) *
    Math.sin(dLong / 2) * Math.sin(dLong / 2);
  var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
  var d = R * c;
  return d; // returns the distance in meter
}

/*
Sends HTTP request to save the location information inside the
database or session
*/
function saveSearchLocation(address, latitude, longitude)
{
	// Some validation to help developers who might pass the wrong order of parameters in.
	if ( typeof address !== 'string' || address.trim() === '' ) {
		throw new Error('saveSearchLocation requires address to be a non-empty string.');
	}
	if ( typeof latitude !== 'number' || typeof longitude !== 'number' ) {
		throw new Error('latitude and longitude must be numbers.');
	}

	var token = $('input[name="_token"], #_token').val();
	if ( !token ) {
		throw new Error('_token must be set in document for saveSearchLocation to work.');
	}

	return $.ajax({
		type: 'post',
		url: '/save-user-location',
		data: {
			'_token': token,
			'latitude' : latitude,
			'longitude' : longitude,
			'address':   address
		},
		error: function(xhr, reason, ex ){
			throw new Error('there was an error in your code.'+ JSON.stringify(xhr));
		},
	});
}

function getAddressFromLatLng(lat_lng) {
	sanitizeLatLng(lat_lng);
	var deferred = $.Deferred();
	var geocoder = new google.maps.Geocoder();
	geocoder.geocode({'location': {'lat': lat_lng.lat(), 'lng': lat_lng.lng()}}, function(results, status)
	{
		var address;
		if (status === 'OK')
		{
			if (results[0])
			{
				// less than 100 meters is close enough to use.
				if (calculateDistance(lat_lng, results[0].geometry.location) <= 100 )
				{
					address = results[0].formatted_address;
					lat_lng = results[0].geometry.location;
				} else {
					// use the coordinates to be exact since
					// nearest address is too inaccurate.
					address = "("+lat_lng.lat().toFixed(5)+","+lat_lng.lng().toFixed(5)+")";
				}
				deferred.resolve({
					'address': address,
					'lat_lng': lat_lng
				});
			} else {
				deferred.reject('No Results');
			}
		} else {
			deferred.reject('Geocode failed for the following reason: ' + status);
		}
	});
	return deferred.promise();
}

function sanitizeLatLng(lat_lng) {
	function sanitizeMethod(method_name) {
		if ( typeof lat_lng[method_name] === 'function' ) {
			return; // No problem to sanitize.
		}
		if ( typeof lat_lng[method_name] === 'number' ) {
			lat_lng['_' + method_name] = lat_lng[method_name];
			lat_lng[method_name] = function() {
				return lat_lng['_' + method_name];
			};
		}
		else {
			throw new Error('lat_lng.' + method_name + ' must either be a function or a number.');
		}
	}
	// In case lat_lng is an object like {'lat': 45, 'lng': -81}, convert to methods.
	sanitizeMethod('lat');
	sanitizeMethod('lng');
}

function saveSearchLocationWithOptionalAddress(lat_lng, address) {
	sanitizeLatLng(lat_lng);

	function saveNewSearchLocation() {
		return saveSearchLocation(address, lat_lng.lat(), lat_lng.lng());
	}

	// Look up address if it wasn't specified.
	if ( typeof address !== 'string' || address.trim() === '' ) {
		return getAddressFromLatLng(lat_lng).then(function(address_info) {
			lat_lng = address_info.lat_lng;
			address = address_info.address;
			return saveNewSearchLocation();
		});
	}
	else {
		return saveNewSearchLocation();
	}
}

function isMobile() {
	// copied from:
	// https://stackoverflow.com/questions/7995752/detect-desktop-browser-not-mobile-with-javascript
	return navigator.userAgent.match(/(iPad)|(iPhone)|(iPod)|(android)|(webOS)/i);
}

function isDesktop() {
	return !isMobile();
}

function refreshPage() {
	location.reload();
}