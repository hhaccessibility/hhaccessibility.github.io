@extends('layouts.default')
@section('content')

<div class="locations-by-keywords">
	<h1>Location Search Results for {{ $keywords }}</h1>
	@include('pages.location_search.spreadsheet', array('locations' => $locations))

</div>
	
@stop