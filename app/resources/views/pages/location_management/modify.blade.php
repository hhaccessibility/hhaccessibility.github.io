@extends('layouts.default')
@section('content')
<div class="location-management">
	<h1>Modify Location - <a href="/location-report/{{ $location->id }}">{{ $location->name }}</a></h1>

	<form method="post" action="/location-modify">
		<input type="hidden" name="location_id" value="{{ $location->id }}">
		@include('pages.location_management.location_form',
					array(
						'location' => $location,
						'data_sources' => $data_sources,
						'location_tags' => $location_tags
					))
	</form>

</div>
@stop