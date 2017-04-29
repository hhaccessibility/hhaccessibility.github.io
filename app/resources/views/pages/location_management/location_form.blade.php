		<div class="row">
			<div class="col-lg-1 col-md-1 col-xs-3">
				<label for="address">Address</label>
			</div>
			<div class="col-lg-3 col-md-5 col-xs-9">
				<input id="address" name="address" type="text"
					value="{{ $location->address }}">
			</div>
			<div class="col-lg-1 col-md-1 col-xs-3">
				<label for="phone_number">Phone</label>
			</div>
			<div class="col-lg-3 col-md-5 col-xs-9">
				<input id="phone_number" name="phone_number" type="text"
					value="{{ $location->phone_number }}">
			</div>
			<div class="col-lg-1 col-md-1 col-xs-3">
				<label for="external_web_url">URL</label>
			</div>
			<div class="col-lg-3 col-md-5 col-xs-9">
				<input id="external_web_url" name="external_web_url" type="text"
					value="{{ $location->external_web_url }}">
			</div>
			<div class="col-lg-1 col-md-1 col-xs-3">
				<label for="name">Name</label>
			</div>
			<div class="col-lg-3 col-md-5 col-xs-9">
				<input id="name" name="name" type="text"
					value="{{ $location->name }}">
			</div>
			<div class="col-lg-1 col-md-1 col-xs-3">
				<label for="group">Group</label>
			</div>
			<div class="col-lg-3 col-md-5 col-xs-9">
				<select id="group" name="group">
				@foreach ( $location_groups as $location_group )
					<option @if ( $location->location_group_id === $location_group->id )
							selected="selected"
						@endif
						value="{{ $location_group->id }}">{{ $location_group->name }}</option>
				@endforeach
				</select>
			</div>
			<div class="col-lg-1 col-md-1 col-xs-3">
				<label for="data_source_id">Source</label>
			</div>
			<div class="col-lg-3 col-md-5 col-xs-9">
				<select id="data_source_id" name="data_source_id">
					@foreach ($data_sources as $data_source)
					<option
						@if ( $location->data_source_id === $data_source->id )
							selected="selected"
						@endif
						value="{{ $data_source->id }}">{{ $data_source->name }}</option>
					@endforeach
				</select>
			</div>
			<div class="col-lg-1 col-md-1 col-xs-3">
				<label>Categories</label>
			</div>
			<div class="col-lg-3 col-md-5 col-xs-9 categories">
				@foreach ( $location_tags as $location_tag )
					<div>
						<input type="checkbox"
							@if ( in_array($location_tag->id, $associated_location_tag_ids) )
								checked="checked"
							@endif
							id="location_tag_{{ $location_tag->id }}"
							name="categories[]">
						<label
							for="location_tag_{{ $location_tag->id }}"
							>
						{{ $location_tag->name }}
						</label>
					</div>
				@endforeach
			</div>
		</div>
		<button type="submit" class="pull-right btn btn-lg btn-primary save-button">Save</button>