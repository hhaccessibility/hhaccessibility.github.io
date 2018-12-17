@extends('layouts.default')
@section('footer-content')
	<script src="/js/jquery-3.1.1.js" type="text/javascript"></script>
	<script type="text/javascript" language="JavaScript">
	var csrf_token = '{{ csrf_token() }}';
    </script>
	<script src="/js/location_tagging.js" type="text/javascript"></script>
@stop
@section('content')
<div class="location-tagging">
	<div class="title-and-navigation">
		<div class="title-and-total">
			<h1>
				<a class="internal-dashboard-link" href="/dashboard">
					<em class="fa fa-gears"></em>
				</a>
				- Location Tagging</h1>
			<p>{{ $total_num_locations_matched }} total locations matched</p>
		</div>
		<div class="navigation">
			@if (0 > $page_size - $offset)
				<a href="/location-tagging?page_size={{ $page_size }}&amp;offset={{ $offset - $page_size }}" title="Previous Page">&lt;</a>
			@else if ($offset !== 0)
				<a href="/location-tagging?page_size={{ $page_size }}" title="Previous Page">&lt;</a>
			@endif
			<a id="refresh-page"><em class="fa fa-refresh"></em></a>
			@if ($total_num_locations_matched > $page_size + $offset)
				<a href="/location-tagging?page_size={{ $page_size }}&amp;offset={{ $offset + $page_size }}" title="Next Page">&gt;</a>
			@endif
		</div>
	</div>
	<div class="data">
		<div class="row headings">
			<div class="col-md-4 col-xs-6">Name</div>
			<div class="col-md-8 col-xs-6">Tags</div>
		</div>
		@foreach ($locations as $location)
		<div class="row" data-location-id="{{ $location->id }}">
			<div class="col-md-4 col-xs-6"><a href="/location/report/{{ $location->id }}">{{ $location->name }}</a></div>
			<div class="col-md-8 col-xs-6">
				@foreach ($location_tags as $location_tag)
					<button title="{{ $location_tag->name }}" data-tag-id="{{ $location_tag->id }}"><em class="{{ $location_tag->icon_selector }}"></em></button>
				@endforeach
			</div>
		</div>
		@endforeach
	</div>
</div>
@stop