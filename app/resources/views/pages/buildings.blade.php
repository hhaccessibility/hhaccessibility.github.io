@extends('layouts.default')
@section('content')

	<div class="title m-b-md">
		<h2>Building Search Results for {{ $building_tag->name }}</h2>
	</div>
	@if (count($buildings) === 0)
		No building found matching the specified tag
	@endif
	
	@foreach ( $buildings as $key => $value )

		<div class="building">
			<a href="/building-report/{{ $value->id }}">
			{{ $value->name }}
			</a>
		</div>
		
	@endforeach

	
@stop