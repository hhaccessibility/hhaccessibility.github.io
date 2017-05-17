	<div class="spreadsheet sort-by-{{ $order_by }}">
	<div class="spreadsheet form-container">
			<form class="form-inline">
			<input type="hidden" id="_token" value="{{ csrf_token() }}">
			<div class="form-group disform">
				<label class="sr-only" for="distance">Radius Distance</label>
				<div class="input-group">
					<div class="input-group-addon">Radius</div>
					<input type="text" class="form-control" id="distance" placeholder="distance" value="{{ $search_radius }}">
					<div class="input-group-addon">km</div>
				</div>
			</div>
			<button type="button" id="updateRadius" class="btn btn-primary">Update</button>
		</form>
	</div>
	@if (count($locations) === 0)
		No location found matching the specified keywords
	@else
		<div class="row column-titles">
			<div class="col-xs-6 name">
				<h3><a href="{{ $url_factory->createURLForOrderByField('name') }}">Name</a></h3>
			</div>
			<div class="col-xs-3 accessibility-rating">
				<h3><a href="{{ $url_factory->createURLForOrderByField('rating') }}">Accessibility Rating (%)</a></h3>
			</div>
			<div class="col-xs-3 distance">
				<h3><a href="{{ $url_factory->createURLForOrderByField('distance') }}">Distance (km)</a></h3>
			</div>
		</div>
		<div class="data">
		@foreach ( $locations as $key => $value )
			<div class="location">
				<a href="/location-report/{{ $value->id }}">
					<div class="row">
						<div class="col-xs-6">
							{{ $value->name }}
						</div>
						<div class="col-xs-3">
							{{ round($value->rating) }}
						</div>
						<div class="col-xs-3">
							{{ number_format((float)$value->distance, 2, '.', '') }}
						</div>
					</div>
				</a>
			</div>
		@endforeach
		</div>
	@endif
	</div>
