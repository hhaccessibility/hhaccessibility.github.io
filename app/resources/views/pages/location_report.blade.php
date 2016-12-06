@extends('layouts.default')
@section('content')

<div class="location-report">
	<h1>{{ $location->name }}</h1>
	<div class="row">
		<div class="col-xs-6">
			<address>{{ $location->address }}</address>
		</div>
		<div class="col-xs-6 text-right">
			@foreach ( $location->tags()->orderBy('name')->get() as $location_tag )
				<a class="location-tag" href="/search-by-tag/{{ $location_tag->id }}">
					{{ $location_tag->name }}
				</a>
			@endforeach
		</div>
	</div>
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