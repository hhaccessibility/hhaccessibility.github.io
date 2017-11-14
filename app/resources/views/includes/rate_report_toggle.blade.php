			<div class="rate-report-toggle">
				<div class="rate-report">
					<a class="{{ !$is_reporting ? 'selected' : '' }}" href="/location-rating/{{ $location_id }}/{{ $question_category_id }}">Rate</a>
					<a class="{{ $is_reporting ? 'selected' : '' }}" href="/location-reporting/{{ $location_id }}/{{ $question_category_id }}">Report</a>
				</div>
				<div class="collapsible-menu">
					<span class="fa fa-bars"></span>
					<ul>
						@foreach ($question_categories as $category)
						<li>
							<a href="{{ $base_url }}{{ $location_id }}/{{ $category->id }}"
							@if ( $category->id === $question_category->id )
								class="selected"
							@endif
							>
								{{ $category->name }}
							</a>
						</li>
						@endforeach
					</ul>
				</div>
			</div>
