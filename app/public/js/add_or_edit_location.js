var user_input_marker;
var map;

function sanitizeExternalWebURL() {
	var field_id = 'external_web_url';
	var $element = $('#' + field_id);
	singleSpaceAndTrim(field_id)();
	var val = $element.val();
	if ( val !== '' ) {
		var index = val.indexOf(':/');
		// Fix missing extra slash in protocol.  
		// For example, 'http:/www.google.com' instead of 'http://www.google.com'.
		if ( index < 8 && val.indexOf('://') !== index ) {
			val = val.substring(0, index) + '://' + val.substring(index + 2);
		}
		var protocol = val.split('://');
		// If no protocol specified, use http.
		if ( protocol.length === 1 ) {
			val = 'http://' + val;
		}
		else {
			protocol = protocol[0].toLowerCase();
			// Use one of the HTTP protocols instead of anything else like ftp.
			if ( ['http', 'https'].indexOf(protocol) === -1 ) {
				protocol = 'http';
			}
			val = protocol + '://' + val.substring(val.indexOf('://') + 3);
		}
		// Replace with sanitized value.
		$element.val(val);
	}
}

function singleSpaceAndTrim(field_id) {
	return function() {
		var $element = $('#' + field_id);
		$element.val($element.val().trim().replace(/\s+/g, ' '));
	};
}

function updateNearbyLocationMarkers() {
	nearby_locations.filter(function(location) {
		return location.shape === undefined; 
		// filter to locations that don't yet have corresponding shapes.
	}).forEach(function(location) {
		var default_options = {
			strokeColor: '#FF0000',
			strokeOpacity: 0.7,
			strokeWeight: 1,
			fillColor: '#FF0000',
			fillOpacity: 0.25,
			map: map,
			center: {lat: location.latitude, lng: location.longitud
			},
			radius: 5,
			title: location.name
          };
		location.shape = new google.maps.Circle(default_options);

		// Show location name while hovering.
		google.maps.event.addListener(location.shape, 'mouseover', function() {
             this.getMap().getDiv().setAttribute('title', location.name);
			 this.setOptions({
				strokeOpacity: 1.0,
				fillOpacity: 0.5
			 });
		});
		google.maps.event.addListener(location.shape, 'mouseout', function() {
			 this.setOptions(default_options);
		});
	});
}

function getNameElement() {
	return $('#name');
}

function getName() {
	return getNameElement().val();
}

function processName(new_name) {
	// Avoid route match problems from having slashes in the name.
	new_name = new_name.replace(/[\/\\]/g, ' ');
	var api_route = '/location-suggestions-for-name/';
	$.ajax({
		'method': 'GET',
		'url': api_route + new_name,
		'success': function(response) {
			// update the location tags.
			var selected_a_location_tag = false;
			$('#location_tags option:not(#location-tag-i-do-not-know)').each(function() {
				var $this = $(this);
				$this.prop('selected', response.location_tags[$this.val()].is_matched);
				selected_a_location_tag = true;
			});
			if( selected_a_location_tag ) {
				$('#location-tag-i-do-not-know').prop('selected', false);
			}

			// update the location group.
			if( response.location_group === null ) {
				response.location_group = '-';
			}
			$('#location_group_id').val(response.location_group);
		},
		'error': function() {
			console.error('Failed to get respone from ' + api_route);
		}
	});
}