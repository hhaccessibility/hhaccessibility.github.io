function initMap() {
	var userPoint = {lat: user_latitude, lng: user_longitude };
	var map = new google.maps.Map(document.getElementById('map'), {
	  zoom: 12,
	  center: userPoint,
	  draggable: false
	});
	locations.forEach(function(location) {
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
	google.maps.event.addDomListener(window, 'resize', function() {
		map.setCenter(userPoint);
	});
}


