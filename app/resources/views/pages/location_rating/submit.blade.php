<form method="post" action="/location-rating-submit">
	{!! csrf_field() !!}
	<input type="hidden" name="location_id" value="{{ $location_id }}">
	<input type="submit" class="clean" value="Submit">
</form>
