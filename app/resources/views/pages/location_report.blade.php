@extends('layouts.default')
@section('content')

<div class="location-report">
	<h1>{{ $location->name }}</h1>
	<address>{{ $location->address }}</address>
	<div class="questions">
		@foreach ( $question_categories as $category )

			<div class="question-category">
				<h4>{{ $category->name }}</h4>
				<ol>
				@foreach ( $category->questions as $question )
					<li>
					{!! $question->question_html !!}
					</li>
				@endforeach
				</ol>
			</div>
			
		@endforeach
	</div>
</div>

@stop