
function initMap() {
	//current user LatLng
	var userPoint = {lat: user_latitude, lng: user_longitude };
	var bounds = new google.maps.LatLngBounds();

	//Map div
	var mapDiv = document.getElementById('map');

    //Map options
	var options = {
	  zoom: 12,
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

		bounds.extend(myLatLng);

	});

	map.fitBounds(bounds);

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
					    fillOpacity: 0.4
					  });
					  circle.setMap(map);

	google.maps.event.addDomListener(window, 'resize', function() {
		map.setCenter(userPoint);
	});



}
