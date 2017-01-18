@extends('layouts.default')
@section('content')

<div class="locations-by-tag">
	<h1>Location Search Results for {{ $location_tag->name }}</h1>
	@include('pages.location_search.spreadsheet', array('locations' => $locations))

</div>
	
@stop