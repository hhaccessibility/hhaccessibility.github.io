@extends('layouts.default')
@section('content')
<div class="suggestion-list">
	<div class="text-center">
		<h1>
		@if ($name)
		<a href="/location/management/my-locations">{{ count($suggestions) }} Suggestions for {{ $name }}</a>
		@else
		Suggestions ({{ count($suggestions) }})
		@endif
		</h1>
	</div>
	@if ( count($suggestions) === 0 )
		<p class="text-center">There are no suggestions
		@if ($name)
			about this location
		@endif</p>
	@else
	<div class="list-group">
		@foreach($suggestions as $suggestion)
		<a href="/suggestion-detail/{{ $suggestion->id }}" class="list-group-item" title="Click to check details">
			<span class="username"><span class="submitted-by">Submitted by:</span> {{ $suggestion->user_name }}</span>
			<span class="location-name">
			@if ($name)
				{{ $name }}
			@else
				{{ $suggestion->location_name }}
			@endif
			</span>
			<span class="when-generated">{{ $suggestion->when_generated }}</span>
		</a>
		@endforeach
	</div>
	@endif
	
</div>
@stop