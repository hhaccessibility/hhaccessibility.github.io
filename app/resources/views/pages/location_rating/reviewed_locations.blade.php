@extends('layouts.default')
@section('content')
<div class="reviewed-locations">
	<div>
		<h1>Reviewed Locations ({{ count($locations) }})</h1>
	</div>
	@if ( count($locations) === 0 )
		<p>No locations have been reviewed yet.</p>
	@else
	<div class="row column-titles">
		<div class="col-xs-4">
			<h3>Name</h3>
		</div>
		<div class="col-xs-4">
			<h3>Address</h3>
		</div>
		<div class="col-xs-4">
			<h3 class="text-right">Status</h3>
		</div>
	</div>
	<div class="data">
		@foreach ($locations as $location)
			<div class="row">
				<div class="col-xs-4">
					<a href="/location/rating/{{ $location->id }}">{{ $location->name }}</a>
				</div>
				<div class="col-xs-4">
					<a href="/location/rating/{{ $location->id }}">{{ $location->address }}</a>
				</div>
				<div class="col-xs-4">
					<div class="pull-right">
						@if ( in_array($location->id, $locations_unsubmitted) )
						<form method="post" action="/location-rating-commit">
							{!! csrf_field() !!}
							<input type="hidden" name="location_id" value="{{ $location->id }}">
							<input type="submit" value="Submit Answers">
						</form>
						@else
							Submitted
						@endif
					</div>
				</div>
			</div>
		@endforeach
	</div>
	@endif
</div>

@stop