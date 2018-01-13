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
	  draggable: false,
	  streetViewControl: false,
	  clickableIcons: false
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
	var default_circle_stroke = '#555759';

	var circle = new google.maps.Circle({
					    center: userPoint,
					    radius: circleRadius,
					    strokeColor: default_circle_stroke,
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


	google.maps.event.addListener(circle,'mouseover',function() {
		circle.setOptions({'strokeColor': '#000'});
	}); 
	google.maps.event.addListener(circle,'mouseout',function() {
		circle.setOptions({'strokeColor': default_circle_stroke});
	}); 
	google.maps.event.addListener(circle,'mousedown',function() {
		circle.setOptions({'editable': true});
	}); 
	google.maps.event.addListener(map,'mouseover',function() {
		circle.setOptions({'editable': true});
	}); 
	google.maps.event.addListener(map,'mouseout',function() {
		circle.setOptions({'editable': false});
	}); 

	// when circle is dragged, we calculate search_radius based on new circleRadius
	google.maps.event.addListener(circle,'radius_changed',function() {
		search_radius = circle.getRadius()/1000;
		setSearchRadius(search_radius);
	});

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
