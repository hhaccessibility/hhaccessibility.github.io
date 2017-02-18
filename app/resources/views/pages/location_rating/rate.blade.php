@extends('layouts.default')
@section('content')
	<div class="location-rating">
	@if ( $location === null )
		<p>The specified location couldn't be found</p>
	@else
		<div class="menu">
			<h1>Rate Location</h1>
			<div class="question-categories">
			@foreach ( $question_categories as $category )
				<a href="/location-rating/{{ $location->id }}/{{ $category->id }}">
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
						'location' => $location
					))
			@endif
		</div>
	@endif
	</div>

@stop