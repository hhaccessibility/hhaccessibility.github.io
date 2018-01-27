@extends('layouts.default')
@section('head-content')
	<script type="text/javascript" src="/js/jquery-3.1.1.js"></script>
	<script type="text/javascript" language="JavaScript" src="/js/smart_menu.js">
    </script>
	<script type="text/javascript" language="JavaScript" src="/js/location_rating.js">
    </script>
	<script type="text/javascript" language="JavaScript" src="/js/question_category_mobile_menu.js">
    </script>
	<script type="text/javascript" language="JavaScript">
	// values used in location_rating.js
	var location_id = '{{ $location->id }}';
	var question_category_id = {{ $question_category->id }};
	var csrf_token = '{{ csrf_token() }}';
    </script>
@stop
@section('content')
	<div class="location-rating">
	@if ( $location === null )
		<p>The specified location couldn't be found</p>
	@else
		<div class="menu">
			<h1>Rate Location</h1>
			@include('includes.question_categories',
				array(
					'location_id' => $location->id,
					'question_categories' => $question_categories,
					'base_url' => '/location-rating/'
				))
			<div class="submit">
				@include('pages.location_rating.submit',
					array(
						'location_id' => $location->id
					))
			</div>
		</div>
		<div class="rate">
			<h1><a href="/location-report/{{ $location->id }}">{{ $location->name }}</a></h1>
				@include('includes.rate_report_toggle',
					array(
						'location_id' => $location->id,
						'question_categories' => $question_categories,
						'question_category_id' => $question_category->id,
						'base_url' => '/location-rating/',
						'is_reporting' => false
					))
			@if ( $question_category === null )
				@include('pages.location_rating.introduction',
					array(
						'location' => $location
					))
			@else
				@include('pages.location_rating.questions',
					array(
						'question_category' => $question_category,
						'location' => $location,
						'uses_screen_reader' => $uses_screen_reader,
						'answer_repository' => $answer_repository
					))
			@endif
		</div>
	@endif
	</div>

@stop