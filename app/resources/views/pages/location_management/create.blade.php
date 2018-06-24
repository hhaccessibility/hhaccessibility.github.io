@extends('layouts.default')
@section('content')
<div class="location-management">
	<h1>Create New Location</h1>

	<form method="post" action="/location-modify">
		@include('pages.location_management.location_form',
					array(
						'location' => $location,
						'data_sources' => $data_sources,
						'location_tags' => $location_tags
					))
	</form>

</div>
@stop