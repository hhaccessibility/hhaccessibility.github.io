@extends('layouts.default')
@section('content')

<div class="location-report">

	@include('pages.location_report.top', array(
		'rating_system' => $rating_system,
		'location' => $location,
		'question_category_id' => $question_category_id))

	@if ($rating_system === 'personal' && !$personal_rating_is_available)
		@include('pages.location_report.personal_not_available')
	@else
		<div class="questions">
			@foreach ( $question_categories as $category )
				<div class="question-category">
					<h4><a href="/location-report/{{ $location->id }}/{{ $rating_system }}/{{ $category->id }}">{{ $category->name }}</a></h4>
					@if ($category->id === $question_category_id)
						<ol>
						@foreach ( $category->questions as $question )
							<li>
							{!! $question->question_html !!}
							</li>
						@endforeach
						</ol>
					@endif
				</div>
			@endforeach
		</div>
	@endif
	<h2>Comments</h2>
	<p>There are a total of {{ count($comments) }} comments for this location.</p>
	<div class="comments">
	@foreach ( $comments as $comment )
		<div>
		{{ $comment->content }}
		</div>
	@endforeach
	</div>
</div>

@stop