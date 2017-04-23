			<div class="rate-report-toggle">
				<a class="{{ !$is_reporting ? 'selected' : '' }}" href="/location-rating/{{ $location_id }}/{{ $question_category_id }}">Rate</a>
				<a class="{{ $is_reporting ? 'selected' : '' }}" href="/location-reporting/{{ $location_id }}/{{ $question_category_id }}">Report</a>
			</div>
