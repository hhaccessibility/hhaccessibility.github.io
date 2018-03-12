@extends('layouts.default')
@section('content')
	<div class="question-explanation">
		<h1 class="title">Question Explanation</h1>
		@include('pages.questions.question_explanation_dialog_content',
			array(
				'question_html' => $question->question_html,
				'explanation' => $question->explanation
				)
			)
		@if ($redirect_url)
			<div class="close-row">
				<a class="btn btn-primary" href="{{ $redirect_url }}">Close</a>
			</div>
		@endif
	</div>
@stop