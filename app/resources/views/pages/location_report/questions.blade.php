<h2>{{ $question_category->name }}</h2>

<div>
	<div class="questions">
		@foreach ( $question_category->questions()->get() as $question )
			<div class="row" data-question-id="{{ $question->id }}">
				<div class="col-xs-6">
					{!! $question->question_html !!}
					{!! $question->getAccessibilityRating($location_id, 'universal') !!}%
					
				</div>
				<div class="col-xs-6">
				</div>
			</div>
		@endforeach
	</div>
</div>