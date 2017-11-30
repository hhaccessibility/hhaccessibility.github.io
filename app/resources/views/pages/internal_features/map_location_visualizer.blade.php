@extends('layouts.default')
@section('head-content')
<script>
	var locations = {!! $locations !!};
</script>
<script src="/js/map_location_visualizer.js">
</script>
@stop
@section('footer-content')
	<script async defer
		src="//maps.googleapis.com/maps/api/js?key={{ $google_map_api_key }}&callback=initMap">
    </script>
@stop

@section('content')
<div class="map-location-visualizer">
	<h1>Map Location Visualizer</h1>
	<div id="map">
	</div>
</div>
@stop