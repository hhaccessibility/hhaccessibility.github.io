var user_zoom_detection_active = false;

function updateMapPositionAndSize(map, bounds, zoom_offset) {
	user_zoom_detection_active = false;
	map.fitBounds(bounds);
	map.setZoom(map.getZoom() + zoom_offset);
	user_zoom_detection_active = true;
}

function addCircleToMap(map, user_point) {
    //convert kilometers to meters
	var circle_radius = search_radius * 1000;
	var default_circle_stroke = '#555759';

	var circle = new google.maps.Circle({
		center: user_point,
		radius: circle_radius,
		strokeColor: default_circle_stroke,
		strokeOpacity: 0.6,
		strokeWeight: 1,
		fillColor: "#929599",
		fillOpacity: 0.3,
		editable: true,
		clickable: false
	});

	var bounds = new google.maps.LatLngBounds();

	bounds.union(circle.getBounds());
	map.fitBounds(bounds);
	circle.setMap(map);

	google.maps.event.addListener(circle, 'mouseover', function() {
		circle.setOptions({'strokeColor': '#000'});
	});
	google.maps.event.addListener(circle, 'mouseout', function() {
		circle.setOptions({'strokeColor': default_circle_stroke});
	});

	// when circle is dragged, we calculate search_radius based on new circleRadius
	google.maps.event.addListener(circle, 'radius_changed', function() {
		search_radius = circle.getRadius() / 1000;
		setSearchRadius(search_radius);
	});
	google.maps.event.addListener(circle, 'center_changed', function() {
		saveSearchLocationWithOptionalAddress(circle.getCenter(), undefined).
			fail(console.error).
			then(refreshPage);
	});
	return circle;
}

function isCTRLDown() {
	if (event && event.ctrlKey) {
		return true;
	}
	return false;
}

function initMap() {
	//current user LatLng
	var user_point = {lat: user_latitude, lng: user_longitude };

	//Map div
	var mapDiv = document.getElementById('map');

    //Map options
	var options = {
	  zoom: 19,
	  center: user_point,
	  draggable: true,
	  streetViewControl: false,
	  clickableIcons: false
	};
	//creating the map
	var map = new google.maps.Map(mapDiv, options);
	var markers = [];
	locations.forEach(function(location) {

		var myLatLng = new google.maps.LatLng(location.latitude,location.longitude);
		var markerIcon = '/images/maps/less_recommend.png'; // default for less than 40 rating
		
		if (location.rating>39) {
			if (location.rating >65) {
				markerIcon = '/images/maps/more_recommend.png';
			}
			else {
				markerIcon = '/images/maps/may_be_recommend.png';
			}
		}
		
		var locationMarker = new google.maps.Marker({
		  position: {lat: location.latitude, lng: location.longitude},
		  map: map,
		  title: location.name,
		  icon: markerIcon
		});

		google.maps.event.addListener(locationMarker, 'click', function() {
			window.location.href = '/location/report/' + location.id;
		});
		markers.push(locationMarker);
	});
	var markerCluster = new MarkerClusterer(map, markers,
            {imagePath: '/images/maps/cluster_'});

	var centreImage = new google.maps.MarkerImage('/images/maps/cross.png',
		new google.maps.Size(49, 49), new google.maps.Point(0, 0), new google.maps.Point(25, 24));
	var centreMarker = new google.maps.Marker({
	  position: user_point,
	  map: map,
	  icon: centreImage
	});

    //convert kilometers to meters
	var circle = addCircleToMap(map, user_point);
	var bounds = new google.maps.LatLngBounds();

	bounds.union(circle.getBounds());
	map.fitBounds(bounds);
	var prev_zoom_level = map.getZoom();
	var zoom_offset = 0;

	google.maps.event.addListenerOnce(map, 'idle', function(){
		zoom_offset = 0;
		user_zoom_detection_active = true;
	});

	google.maps.event.addDomListener(map, 'zoom_changed', function() {
		zoomLevel = map.getZoom();
		if( user_zoom_detection_active ) {
			zoom_offset += zoomLevel - prev_zoom_level;
			if (zoom_offset < 0) {
				search_radius *= 1.8;
			}
			else {
				search_radius /= 1.8;
			}
			setSearchRadius(search_radius);
		}
		prev_zoom_level = zoomLevel;
	});

	window.addEventListener('resize', function(event){
		google.maps.event.trigger(map, "resize");
	});
	google.maps.event.addDomListener(window, 'resize', function() {
		updateMapPositionAndSize(map, bounds, zoom_offset);
	});

	map.addListener('dragend', function() {
		saveSearchLocationWithOptionalAddress(map.getCenter()).then(function() {
			circle.setCenter(map.getCenter());
			location.reload();
		});
	});

	map.addListener('dblclick', function() {
		if (isCTRLDown()) {
			new_search_radius *= 2;
		}
		else {
			new_search_radius /= 2;
		}
		setSearchRadius(new_search_radius);
	});
}
