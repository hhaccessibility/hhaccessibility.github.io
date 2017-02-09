	<div class="spreadsheet sort-by-{{ $order_by }}">
	@if (count($locations) === 0)
		No location found matching the specified keywords
	@else
		<div class="row">
			<div class="col-xs-7">
				<h3><a href="{{ $url_factory->createURLForOrderByField('name') }}">Name</a></h3>
			</div>
			<div class="col-xs-2">
				<h3><a href="{{ $url_factory->createURLForOrderByField('rating') }}">Rating</a></h3>
			</div>
			<div class="col-xs-3">
				<h3><a href="{{ $url_factory->createURLForOrderByField('distance') }}">Distance (km)</a></h3>
			</div>
		</div>
		<div class="data">
		@foreach ( $locations as $key => $value )
			<div class="location">
				<a href="/location-report/{{ $value->id }}">
					<div class="row">
						<div class="col-xs-7">
							{{ $value->name }}
						</div>
						<div class="col-xs-2">
							{{ $value->rating }}
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