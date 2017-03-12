@extends('layouts.default')
@section('content')
<div class="reviewed-locations">
	<div>
		<h1>Reviewed Locations</h1>
	</div>
	@if ( count($locations) === 0 )
		<p>No locations have been reviewed yet.</p>
	@else
	<div>
		@foreach ($locations as $location)
			<div class="row">
				<div class="col-xs-8">
					<a href="/location-rating/{{ $location->id }}">{{ $location->name }} - {{ $location->address }}</a>
				</div>
				<div class="col-xs-4">
					<form method="post" action="/location-rating-commit">
						{!! csrf_field() !!}
						<input type="hidden" name="location_id" value="{{ $location->id }}">
						<input type="submit" value="Commit Answers">
					</form>
				</div>
			</div>
		@endforeach
	</div>
	@endif
</div>

@stop