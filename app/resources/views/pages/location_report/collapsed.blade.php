@extends('layouts.default')
@section('head-content')
	@if (!$turn_off_maps)
	<script>
		  function initMap() {
			var uluru = {lat: {{ $location->latitude }}, lng: {{ $location->longitude }} };
			var map = new google.maps.Map(document.getElementById('map'), {
			  zoom: 13,
			  center: uluru
			});
			var marker = new google.maps.Marker({
			  position: uluru,
			  map: map
			});
		  }
    </script>
	@endif
@stop
@section('footer-content')
	@if (!$turn_off_maps)
	<script async defer
		src="//maps.googleapis.com/maps/api/js?key={{ $google_map_api_key }}&callback=initMap">
    </script>
	@endif
@stop
@section('content')

<div class="location-report">
	@include('pages.location_report.top', array(
		'rating_system' => $rating_system,
		'location' => $location))
	<div class="row">
		<div class="col-xs-5">
			<address>{{ $location->address }}</address>
			<a href="{{ $location->getExternalWebURL() }}">{{ $location->getExternalWebURL() }}</a>
		</div>
		<div class="col-xs-7 text-right location-tags">
			@foreach ( $location->tags()->orderBy('name')->get() as $location_tag )
				<a class="location-tag" href="/search-by-tag/{{ $location_tag->id }}">
					{{ $location_tag->name }}
				</a>
			@endforeach
		</div>
	</div>
	<div class="map-and-box">
		<div id="map">
		</div>
		<div class="questions-box">
			@if ($rating_system === 'personal' && !$personal_rating_is_available)
				@include('pages.location_report.personal_not_available')
			@else
				<div class="title-bar">
					<h3>{{ $location->name }}</h3>
					<span>( {{ $num_ratings }} ratings )</span>
				</div>
				<div class="questions">
					@foreach ( $question_categories as $category )
						<div class="question-category">
							<h4><a href="/location-report/{{ $location->id }}/{{ $rating_system }}/{{ $category->id }}">{{ $category->name }}</a></h4>
						</div>
					@endforeach
				</div>
			@endif
		</div>
	</div>
</div>

@stop