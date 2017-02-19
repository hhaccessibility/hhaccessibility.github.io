@extends('layouts.default')
@section('head-content')
  @if ( $view === 'map' && !$turn_off_maps  )
  <script>
    var locations      = {!! json_encode($locations) !!};
	var user_longitude = {{ $base_user->getLongitude() }};
	var user_latitude  = {{ $base_user->getLatitude() }};
	var search_radius  = parseFloat({{ $search_radius }});

  </script>
  <script src="/js/location_search_map.js">
  </script>
  @endif
@stop
@section('footer-content')
	@if ( $view === 'map' && !$turn_off_maps )
	<script async defer
		src="//maps.googleapis.com/maps/api/js?key={{ $google_map_api_key }}&callback=initMap">
    </script>
	@endif
@stop
@section('content')

<div class="location-search">
	<div class="title-map-table-bar">
		@if ( !empty($location_tag_name) )
		<h1>Location Search Results for {{ $location_tag_name }}</h1>
		@else
		<h1>Location Search Results for {{ $keywords }}</h1>
		@endif
		<div class="text-right">
			@if ( $view === 'table' )
				<a class="selected" href="{{ $url_factory->createURLForView('table') }}">Table</a>
				<a href="{{ $url_factory->createURLForView('map') }}">Map</a>
			@else
				<a href="{{ $url_factory->createURLForView('table') }}">Table</a>
				<a class="selected" href="{{ $url_factory->createURLForView('map') }}">Map</a>
			@endif
		</div>
	</div>
	
	@if ( $view === 'table' )
		@include('pages.location_search.spreadsheet', array('locations' => $locations, 'url_factory' => $url_factory))
	@else
		@include('pages.location_search.map', array('locations' => $locations))
	@endif
	
</div>
	
@stop