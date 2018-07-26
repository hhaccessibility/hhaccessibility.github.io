@extends('layouts.default')
@section('content')
<div class="locations-added-by-me">
	<div class="text-center">
		<h1>Added Locations ({{ count($locations) }})</h1>
	</div>
	@if ( count($locations) === 0 )
		<p class="text-center">No locations have been added yet.  Add a location by clicking '+' below.</p>
		<p class="add-create"><a href="/location/management/add" title="Add New Location"><em class="fa fa-plus-circle"></em></a></p>
	@else
		<p class="add-create"><a href="/location/management/add" title="Add New Location"><em class="fa fa-plus-circle"></em></a></p>
	<div class="row column-titles">
		<div class="col-xs-3">
			<h3>Name</h3>
		</div>
		<div class="col-md-5 col-xs-3">
			<h3>Address</h3>
		</div>
		<div class="col-md-3 col-xs-3">
			<h3>Suggestions</h3>
		</div>
		
	</div>
	<div class="data">
		@foreach ($locations as $location)
			<div class="row">
				<div class="col-xs-3">
					<a href="/location-rating/{{ $location->id }}">{{ $location->name }}</a>
				</div>
				<div class="col-md-5 col-xs-3">
					<a href="/location-rating/{{ $location->id }}">{{ $location->address }}</a>
				</div>
				<div class="col-md-2 col-xs-3">
					<a href="/suggestion-list/{{ $location->id }}">{{ $location->suggestions }}</a>
				</div>
				<div class="col-md-2 col-xs-3">

					<div class="col-md-6 col-xs-6">
					<a href="/location/management/edit/{{ $location->id }}">Edit</a>
					</div>
					<div class="col-md-6 col-xs-6">
					@if ( $location->is_safe_to_delete )
						<a href="/location/management/delete/{{ $location->id }}">Delete</a>
					@else
						Unsafe
					@endif
					</div>

				</div>

			</div>
		@endforeach
	</div>
	@endif
</div>

@stop