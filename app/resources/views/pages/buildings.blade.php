@extends('layouts.default')
@section('content')

	<div class="title m-b-md">
		<h2>Building Search Results for {{ $building_tag->name }}</h2>
	</div>
	
	@foreach ( $buildings as $key => $value )

		<div class="building">
			<a href="/building-report/{{ $value->id }}">
			{{ $value->name }}
			</a>
		</div>
		
	@endforeach

	
@stop