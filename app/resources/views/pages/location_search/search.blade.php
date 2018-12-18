@extends('layouts.default')
@section('head-content')
  <script src="/js/jquery-3.1.1.js"></script>
  <script src="/js/utils.js" type="text/javascript"></script>
  <script>
	var search_radius  = parseFloat({{ $search_radius }});

	function setSearchRadius(new_radius_km)
	{
		var csrf_token = $("#_token").val();
		return $.ajax(
			{
				url: "/api/set-search-radius",
				type: 'post',
				headers: {
					'X-CSRF-Token': csrf_token
				},
				data: {
					'distance': new_radius_km,
					'_token': csrf_token
				},
				success: refreshPage,
				fail: function() {
					console.error('Something failed in ajax request');
				}
			}
		);
	}
  </script>
  @if ( $view === 'map' && !$turn_off_maps  )
  <script>
    var locations      = {!! json_encode($locations) !!};
	var user_longitude = {{ $base_user->getLongitude() }};
	var user_latitude  = {{ $base_user->getLatitude() }};
  </script>
  <script src="/js/location_search_map.js">
  </script>
  @endif
  @if ( $view === 'table' )
	<link href="/css/jquery/jquery-ui.css" rel="stylesheet" type="text/css">
	<script src="/css/jquery/jquery-ui.js"></script>
	<script src="/js/location_search_table.js"></script>
  @endif
@stop
@section('footer-content')
	@if ( $view === 'map' && !$turn_off_maps )
	<script async defer
		src="//maps.googleapis.com/maps/api/js?key={{ $google_map_api_key }}&amp;callback=initMap">
    </script>
	<script
		src="/js/marker_clusterer.js">
    </script>
	@endif
@stop
@section('content')

<div class="location-search {{ $max_reached ? 'warned' : '' }}">
	<input type="hidden" id="_token" value="{{ csrf_token() }}">
	<div class="title-map-table-bar">
		<div class="title">
			@if ( !empty($location_tag_name) )
			<h1><span class="location-search-results-for">Location Search Results for</span> {{ $location_tag_name }}</h1>
				@if (empty(trim($keywords)))
					<p class="keyword-filter">Not filtered by keywords</p>
				@else
					<p class="keyword-filter">Also filtered by keywords: {{ $keywords }}</p>
				@endif
			@else
			<h1><span class="location-search-results-for">Location Search Results for</span> {{ $keywords }}</h1>
			@endif
		</div>
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
