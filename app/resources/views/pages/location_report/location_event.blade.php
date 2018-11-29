@extends('layouts.default')
@section('content')

<div class="location-event">
	<a class="report-link" title="location report" href="/location/report/{{ $location_id }}"><em class="fa fa-map-marker"></em></a>
	<h1>{{ $location_event->name }}</h1>
	<p class="when">{{ $location_event->getFormattedDate() }}</p>
	<p>{{ $location_event->description }}</p>
	@if ($location_event->external_web_url)
		<p>
			<span class="learn-more">Learn more by visiting</span>
			<a href="{{ $location_event->external_web_url }}">{{ $location_event->external_web_url }}</a>
		</p>
	@endif
</div>

@stop