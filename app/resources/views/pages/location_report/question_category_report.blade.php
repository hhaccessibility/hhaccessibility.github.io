@extends('layouts.default')
@section('head-content')
	<script type="text/javascript" language="JavaScript" src="/js/smart_menu.js">
    </script>
@stop
@section('content')
	<div class="location-reporting">
	@if ( $location === null )
		<p>The specified location couldn't be found</p>
	@else
		<div class="menu">
			<h1>Location Ratings</h1>
			@include('includes.question_categories',
				array(
					'location_id' => $location->id,
					'question_categories' => $question_categories,
					'question_category' => $question_category,
					'base_url' => '/location-reporting/'
				))
		</div>
		<div class="ratings">
			<h1><a href="/location-report/{{ $location->id }}">{{ $location->name }}</a></h1>
			@include('includes.rate_report_toggle',
				array(
					'location_id' => $location->id,
					'question_category_id' => $question_category->id,
					'is_reporting' => true
				))
			@include('pages.location_report.questions',
				array(
					'question_category' => $question_category,
					'location_id' => $location->id
				))
		</div>
	@endif
	</div>

@stop