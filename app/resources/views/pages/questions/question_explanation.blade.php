@extends('layouts.default')
@section('content')
	<div class="question-explanation">
		@if ($location)
		<h1 class="bread-crumb"><a href="/location-rating/{{ $location->id }}">{{ $location->name }}</a></h1>
		<h1 class="middle-bread-crumb"><a href="/location-rating/{{ $location->id }}/{{ $question_category->id }}">{{ $question_category->name }}</a></h1>
		@else
		<h1 class="bread-crumb">{{ $question_category->name }}</h1>
		@endif
		<h1 class="title">Question Explanation</h1>
		<div class="question-html">
		{{ $question->question_html }}
		</div>
		<h3>Examples and Explanation</h3>
		<div class="question-explanation">
		{!! $question->explanation !!}
		</div>
	</div>
@stop