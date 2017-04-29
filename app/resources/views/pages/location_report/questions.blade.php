<h2>{{ $question_category->name }}</h2>

<div>
	<div class="questions">
		@foreach ( $question_category->questions()->get() as $question )
			<div>
				{!! $question->question_html !!}
				<span class="percentage">
				{!! $question->getAccessibilityRating($location_id, 'universal') !!}%
				</span>
			</div>
		@endforeach
	</div>
</div>