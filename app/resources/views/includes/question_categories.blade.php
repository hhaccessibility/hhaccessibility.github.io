			<div class="question-categories">
				@foreach ( $question_categories as $category )
					<a href="{{ $base_url }}{{ $location_id }}/{{ $category->id }}"
					@if ( $category->id === $question_category->id )
						class="selected"
					@endif
					>
						{{ $category->name }}
					</a>
				@endforeach
			</div>
