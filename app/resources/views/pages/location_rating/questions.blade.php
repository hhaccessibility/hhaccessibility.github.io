<h2>{{ $question_category->name }}</h2>

<form method="post" action="/location-rating">
	<input type="hidden" name="location_id" value="{{ $location->id }}">
	<div class="questions">
		@foreach ( $question_category->questions()->get() as $question )
			<div class="row">
				<div class="col-xs-6">
					{!! $question->question_html !!}
				</div>
				<div class="col-xs-6">
					<div class="row answers">
						<div class="col-xs-3">
							<div>
							Yes
							</div>
						</div>
						<div class="col-xs-3">
							<div>
							No
							</div>
						</div>
						<div class="col-xs-6">
							<div>
							Not Applicable
							</div>
						</div>
					</div>
				</div>
			</div>
		@endforeach
	</div>
	<textarea class="clean" placeholder="Comment on {{ $question_category->name }} at {{ $location->name }}"></textarea>
	<input class="clean pull-right" type="submit" value="Next">
</form>
