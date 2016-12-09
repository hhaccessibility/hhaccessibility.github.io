@extends('layouts.default')
@section('head-content')
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
@stop
@section('footer-content')
	<script async defer
		src="//maps.googleapis.com/maps/api/js?key={{ $google_map_api_key }}&callback=initMap">
    </script>
@stop
@section('content')

<div class="location-report">
	<h1>{{ $location->name }}</h1>
	<div class="row">
		<div class="col-xs-6">
			<address>{{ $location->address }}</address>
		</div>
		<div class="col-xs-6 text-right">
			@foreach ( $location->tags()->orderBy('name')->get() as $location_tag )
				<a class="location-tag" href="/search-by-tag/{{ $location_tag->id }}">
					{{ $location_tag->name }}
				</a>
			@endforeach
		</div>
	</div>
	<div id="map">
	</div>
	<div class="questions">
		@foreach ( $question_categories as $category )

			<div class="question-category">
				<h4>{{ $category->name }}</h4>
				<ol>
				@foreach ( $category->questions as $question )
					<li>
					{!! $question->question_html !!}
					</li>
				@endforeach
				</ol>
			</div>
			
		@endforeach
	</div>
</div>

@stop