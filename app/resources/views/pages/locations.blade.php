@extends('layouts.default')
@section('content')

	<div class="title m-b-md">
		<h2>Location Search Results for {{ $location_tag->name }}</h2>
	</div>
	@if (count($locations) === 0)
		No location found matching the specified tag
	@endif
	
	@foreach ( $locations as $key => $value )

		<div class="location">
			<a href="/location-report/{{ $value->id }}">
			{{ $value->name }}
			</a>
		</div>
		
	@endforeach

	
@stop