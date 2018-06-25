function initMap() {
	//current user LatLng
	var userPoint = {lat: 42.3174246, lng: -83.0374028 };

	//Map div
	var mapDiv = document.getElementById('map');


	//Map options
	var options = {
		zoom: 14,
		center: userPoint,
		draggable: true,
		streetViewControl: false
	};
	//creating the map
	var map = new google.maps.Map(mapDiv, options);

	locations.filter(function(location) {
		return !location.address || location.address.trim() === '';
	}).forEach(function(location) {

		var myLatLng = new google.maps.LatLng(location.latitude,location.longitude);

		var locationMarker = new google.maps.Marker({
			position: {lat: location.latitude, lng: location.longitude},
			map: map,
			title: location.name + '(' + location.id + ')'
		});

		google.maps.event.addListener(locationMarker, 'click', function() {
			var textarea = document.getElementsByTagName('textarea')[0];
			textarea.value += "\n" + location.latitude + ', ' + location.longitude;
			locationMarker.setMap(null);
		});
	});
}