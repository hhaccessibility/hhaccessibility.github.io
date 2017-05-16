	<link href="/css/jquery/jquery-ui.css" rel="stylesheet" type="text/css">
	<script src="/css/jquery/external/jquery/jquery.js"></script>
	<script src="/css/jquery/jquery-ui.js"></script>
	<script src="/js/location_search_table.js"></script>
	<script src="/js/location_search_table.js"></script>
	<div class="spreadsheet sort-by-{{ $order_by }}">
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