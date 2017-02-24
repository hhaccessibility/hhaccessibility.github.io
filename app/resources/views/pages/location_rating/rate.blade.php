@extends('layouts.default')
@section('head-content')
	<script type="text/javascript" src="/js/jquery-3.1.1.js"></script>
	<script type="text/javascript" language="JavaScript" src="/js/location_rating.js">
    </script>
	<script type="text/javascript" language="JavaScript">
	// values used in location_rating.js
	var location_id = {{ $location->id }};
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
			<div class="question-categories">
			@foreach ( $question_categories as $category )
				<a href="/location-rating/{{ $location->id }}/{{ $category->id }}"
				@if ( $category->id === $question_category->id )
					class="selected"
				@endif
				>
					{{ $category->name }}
				</a>
			@endforeach
			</div>
		</div>
		<div class="rate">
			<h1>{{ $location->name }}</h1>
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
						'answer_repository' => $answer_repository
					))
			@endif
		</div>
	@endif
	</div>

@stop