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
  @if ( $view === 'table' && $turn_off_maps  )
	<link href="/css/jquery/jquery-ui.css" rel="stylesheet" type="text/css">
	<script src="/css/jquery/external/jquery/jquery.js"></script>
	<script src="/css/jquery/jquery-ui.js"></script>
	<script src="/js/location_search_table.js"></script>
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

<div class="location-search {{ $max_reached ? 'warned' : '' }}">
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
		@if ( $max_reached )
			<span class="warning">Narrow your search to view all matches.  
			{{ count($locations) }} of {{ $unlimited_location_count }} shown.</span>
		@endif
	</div>

	@if ( $view === 'table' )
		@include('pages.location_search.spreadsheet', array('locations' => $locations, 'url_factory' => $url_factory))
	@else
		@include('pages.location_search.map', array('locations' => $locations))
	@endif

</div>

@stop
