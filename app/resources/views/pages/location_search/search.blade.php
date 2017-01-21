@extends('layouts.default')
@section('content')

<div class="location-search">
	<div class="title-map-table-bar">
		@if ( !empty($location_tag_name) )
		<h1>Location Search Results for {{ $location_tag_name }}</h1>
		@else
		<h1>Location Search Results for {{ $keywords }}</h1>
		@endif
		<div class="pull-right text-right">
			@if ( $view === 'table' )
				<a class="selected" href="{{ $url }}&amp;view=table">Table</a>
				<a href="{{ $url }}&amp;view=map">Map</a>
			@else
				<a href="{{ $url }}&amp;view=table">Table</a>
				<a class="selected" href="{{ $url }}&amp;view=map">Map</a>
			@endif
		</div>
	</div>
	
	@if ( $view === 'table' )
		@include('pages.location_search.spreadsheet', array('locations' => $locations))
	@else
		@include('pages.location_search.map', array('locations' => $locations))
	@endif
	
</div>
	
@stop