var user_zoom_detection_active = false;

function updateMapPositionAndSize(map, bounds, zoomOffset) {
	user_zoom_detection_active = false;
	map.fitBounds(bounds);
	map.setZoom(map.getZoom() + zoomOffset);
	user_zoom_detection_active = true;
}

function initMap() {
	//current user LatLng
	var userPoint = {lat: user_latitude, lng: user_longitude };
	
	//Map div
	var mapDiv = document.getElementById('map');

    //Map options
	var options = {
	  zoom: 19,
	  center: userPoint,
	  draggable: false
	};
	//creating the map
	var map = new google.maps.Map(mapDiv, options);

	locations.forEach(function(location) {

		var myLatLng = new google.maps.LatLng(location.latitude,location.longitude);

		var locationMarker = new google.maps.Marker({
		  position: {lat: location.latitude, lng: location.longitude},
		  map: map,
		  title: location.name
		});

		google.maps.event.addListener(locationMarker, 'click', function() {
			window.location.href = '/location-report/' + location.id;
		});
	});

	var centreMarker = new google.maps.Marker({
	  position: userPoint,
	  map: map,
	  icon: 'http://maps.google.com/mapfiles/ms/icons/blue-dot.png'
	});

    //convert kilometers to meters
	var circleRadius =search_radius*1000;

	var circle = new google.maps.Circle({
					    center: userPoint,
					    radius: circleRadius,
					    strokeColor: "#555759",
					    strokeOpacity: 0.6,
					    strokeWeight: 1,
					    fillColor: "#929599",
					    fillOpacity: 0.3
					  });
	var bounds = new google.maps.LatLngBounds();

	bounds.union(circle.getBounds());
	map.fitBounds(bounds);
	circle.setMap(map);
	var prev_zoom_level = map.getZoom();
	var zoomOffset = 0;

	google.maps.event.addListenerOnce(map, 'idle', function(){
		zoomOffset = 0;
		user_zoom_detection_active = true;
	});
	
	google.maps.event.addDomListener(map, 'zoom_changed', function() {
		zoomLevel = map.getZoom();
		if( user_zoom_detection_active ) {
			zoomOffset += zoomLevel - prev_zoom_level;
			updateMapPositionAndSize(map, bounds, zoomOffset);
		}
		prev_zoom_level = zoomLevel;
	});

	window.addEventListener('resize', function(event){
		google.maps.event.trigger(map, "resize");
	});
	google.maps.event.addDomListener(window, 'resize', function() {
		updateMapPositionAndSize(map, bounds, zoomOffset);
	});
}
