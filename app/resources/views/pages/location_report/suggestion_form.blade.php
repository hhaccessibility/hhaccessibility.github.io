<div class="suggestion-form">
	<form class="form" id="suggestionForm">
		{!! csrf_field() !!}
		<div class="message-display">
		</div>
		<input type="hidden" name="location-id" id="location-id" value="{{ $location->id }}" readonly="readonly">
		<div class="row">
			<div class="col-sm-6 col-xs-12">
				<div class="input-group">
					<label for="location-name">Location name:</label>
					<input type="text" class="form-control"  name="location-name" id="location-name" data-value="{{ $location->name }}" value="{{ $location->name }}" required>
				</div>
			</div>
			<div class="col-sm-6 col-xs-12">
				<div class="input-group">
					<label for="phone-number">Phone number:</label>
					<input type="tel" class="form-control" name="phone-number" id="phone-number" data-value="{{ $location->phone_number }}" value="{{ $location->phone_number }}">
				</div>
			</div>
			<div class="col-xs-12">
				<div class="input-group">
					<label for="address">Address:</label>
					<input type="text" class="form-control" name="address" id="address" data-value="{{ $location->address }}" value="{{ $location->address }}" required>
				</div>
			</div>
			<div class="col-xs-12">
				<div class="input-group">
					<label for="url">External website:</label>
					<input type="url" class="form-control" name="url" id="url" data-value="{{ $location->external_web_url }}" value="{{ $location->external_web_url }}">
				</div>
			</div>
		</div>
	</form>
</div>