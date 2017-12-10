@extends('layouts.default')
@section('head-content')
<script src="/js/jquery-3.1.1.js">
</script>
<script>
	var location_id = {!! $location->id !!};
	var csrf_token = '{{ csrf_token() }}';
</script>
<script src="/js/duplicate_location_finder.js">
</script>
@stop
@section('content')
<div class="duplicate-location-finder">
	<h1>Duplicate Location Finder for {{ $location->name }}</h1>
	<form>
		<input type="hidden" name="location_id" value="{{ $location->id }}">
		<div>
			<label for="radius_meters">Maximum distance in meters</label>
			<input name="radius_meters" id="radius_meters" type="number" step="1" value="{{ $radius_meters }}">
		</div>
		<div>
			<input type="submit" value="Search">
		</div>
	</form>
	<h2>Possible Duplicates:</h2>
	<div class="spreadsheet">
		<div class="row column-titles">
			<div class="col-xs-5"><h4>Name</h4></div>
			<div class="col-xs-4"><h4>Address</h4></div>
			<div class="col-xs-2"><h4>Distance</h4></div>
			<div class="col-xs-1"><h4>Duplicate</h4></div>
		</div>
		@foreach ( $search_results as $search_result )
		<div class="row" data-location_id="{{ $search_result->id }}">
			<div class="col-xs-5">
				<a href="/location-report/{{ $search_result->id }}">{{ $search_result->name }}</a>
			</div>
			<div class="col-xs-4">{{ $search_result->address }}</div>
			<div class="col-xs-2">{{ round($search_result->distance * 1000) }}</div>
			<div class="col-xs-1"><button>Duplicate</button></div>
		</div>
		@endforeach
	</div>
</div>

@stop