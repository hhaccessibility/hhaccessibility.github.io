@extends('layouts.default')
@section('head-content')
	@if (!$turn_off_maps)
	<script>
		  function initMap() {
			var locationPoint = {lat: {{ $location->latitude }}, lng: {{ $location->longitude }} };
			var map = new google.maps.Map(document.getElementById('map'), {
			  zoom: 15,
			  center: locationPoint,
			  draggable: false,
			  streetViewControl: false
			});
			var marker = new google.maps.Marker({
			  position: locationPoint,
			  map: map
			});
			
			function centreLocation() {
				map.setCenter(locationPoint);
			}

			google.maps.event.addDomListener(window, 'resize', centreLocation);
			$(window).resize(function() {
				google.maps.event.trigger(map, "resize");
			});
		  }
		  
    </script>
	@endif
	<script language="JavaScript" src="/js/jquery-3.1.1.js"></script>	
	<script src="/js/location_report.js"></script>
@stop
@section('footer-content')
	@if (!$turn_off_maps)
	<script async defer
		src="//maps.googleapis.com/maps/api/js?key={{ $google_map_api_key }}&callback=initMap">
    </script>
	@endif
@stop
@section('content')

<div class="location-report">
	@include('pages.location_report.top', array(
		'rating_system' => $rating_system,
		'location' => $location))
	<div class="row">
		<div class="col-xs-5">
			<address>{{ $location->address }}</address>
			<a href="{{ $location->getExternalWebURL() }}">{{ $location->getExternalWebURL() }}</a>
		</div>
		<div class="col-xs-7 text-right">
			<div class="location-tags">
			@foreach ( $location->tags()->orderBy('name')->get() as $location_tag )
				<a class="location-tag" title="{{ $location_tag->name }}" href="/location-search?location_tag_id={{ $location_tag->id }}">
					<span class="name">{{ $location_tag->name }}</span>
					<span class="icon {{ $location_tag->icon_selector }}"></span>
				</a>
			@endforeach
			</div>
		</div>
	</div>
	<div class="map-and-box">
		<div id="map">
		</div>
	</div>
</div>

@stop