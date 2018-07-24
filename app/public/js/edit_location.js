function initMap() {
	// initial map location
	var initPoint = {lat: parseFloat($('#latitude').val()), lng: parseFloat($('#longitude').val()) };
	var mapDiv = document.getElementById('map');
	var options = {
	  zoom: 17,
	  center: initPoint,
	  draggable: false,
	  streetViewControl: false,
	  clickableIcons: false
	};
	//creating the map
	map = new google.maps.Map(mapDiv, options);

	user_input_marker = new google.maps.Marker({
			map: map,
			position: initPoint
		});

	var name_timer = new DelayedNonEmptyTimer(300, getName, processName);
	getNameElement().on('keydown keyup change blur', name_timer.delayedProcess);

	// sanitize values on blur.
	getNameElement().on('blur', singleSpaceAndTrim('name'));
	$('#address').on('blur', singleSpaceAndTrim('address'));
	$('#phone_number').on('blur', singleSpaceAndTrim('phone_number'));
	$('#external_web_url').on('blur', sanitizeExternalWebURL);

	updateNearbyLocationMarkers();
}
