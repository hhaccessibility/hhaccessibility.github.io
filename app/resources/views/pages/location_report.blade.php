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
	<div class="title">
		<h1>{{ $location->name }}</h1>
		<div class="universal-personal">
			<a class="{{ $rating_system === 'universal' ? 'selected' : '' }}"
				href="/location-report/{{ $location->id }}/universal">Universal</a>
			<a class="{{ $rating_system === 'personal' ? 'selected' : '' }}"
			href="/location-report/{{ $location->id }}/personal">Personal</a>
		</div>
	</div>
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
	<div class="row">
		<div class="col-lg-9 col-md-7 col-xs-12">
			<div id="map">
			</div>
		</div>
		<div class="col-lg-3 col-md-5 col-xs-12">
			@if ($rating_system === 'personal' && !$personal_rating_is_available)
				<div class="text-center">
					<p>The personal accessibility ratings are available only after you
						have <a href="/login">signed in</a> and have specified your accessibility needs.</p>
				</div>
			@else
				<div class="questions">
					@foreach ( $question_categories as $category )

						<div class="question-category">
							<h4><a href="/location-report/{{ $location->id }}/{{ $rating_system }}/{{ $category->id }}">{{ $category->name }}</a></h4>
							@if ($category->id === $question_category_id)
								<ol>
								@foreach ( $category->questions as $question )
									<li>
									{!! $question->question_html !!}
									</li>
								@endforeach
								</ol>
							@endif
						</div>
					@endforeach
				</div>
			@endif
		</div>
	</div>
</div>

@stop