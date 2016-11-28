@extends('layouts.default')
@section('content')

<div class="locations-by-keywords">
	<h1>Location Search Results for {{ $keywords }}</h1>
	@if (count($locations) === 0)
		No location found matching the specified keywords
	@endif
	
	@foreach ( $locations as $key => $value )

		<div class="location">
			<a href="/location-report/{{ $value->id }}">
			{{ $value->name }}
			</a>
		</div>
		
	@endforeach

</div>
	
@stop