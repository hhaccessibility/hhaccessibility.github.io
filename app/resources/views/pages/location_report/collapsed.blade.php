@extends('layouts.default')
@section('head-content')
	@if (!$turn_off_maps)
	<script>
		  function initMap() {
			var locationPoint = {lat: {{ $location->latitude }}, lng: {{ $location->longitude }} };
			var map = new google.maps.Map(document.getElementById('map'), {
			  zoom: 15,
			  center: locationPoint,
			  draggable: false,
			  streetViewControl: false
			});
			var marker = new google.maps.Marker({
			  position: locationPoint,
			  map: map
			});
			
			function centreLocation() {
				map.setCenter(locationPoint);
			}

			google.maps.event.addDomListener(window, 'resize', centreLocation);
			$(window).resize(function() {
				google.maps.event.trigger(map, "resize");
			});
		  }
		  
    </script>
	@endif
	<script language="JavaScript" src="/js/jquery-3.1.1.js"></script>	
	<script>
		  
		  function updateHeightOfMap() {
			  var $map_parent = $('.map-and-box');
			  var $map = $('#map');
			  var $copyright = $('#copyright');
			  var height = window.innerHeight - $(map).offset().top - $copyright.height();
			  if (height < 100)
				height = 100;
			
			  $map.height(height);
			  $map_parent.height(height);
			  
			  $questionBox = $('.questions-box');
			  var questionBoxHeight = $questionBox.height();
			  var questionBoxTop = Math.max(0, (height - questionBoxHeight) / 2);
				$questionBox.css('top', Math.round(questionBoxTop) + 'px');
		  }
		$(window).resize(updateHeightOfMap);
		document.addEventListener("DOMContentLoaded", function(event) {
			updateHeightOfMap();
		});		  
	</script>
	<script language="JavaScript" src="/js/pie_graph.js"></script>
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
				<a class="location-tag" href="/location-search?location_tag_id={{ $location_tag->id }}">
					{{ $location_tag->name }}
				</a>
			@endforeach
		</div>
	</div>
	<div class="map-and-box">
		<div id="map">
		</div>
		<div class="outer-questions-box">
			<div class="questions-box">
				@if ($rating_system === 'personal' && !$personal_rating_is_available)
					@include('pages.location_report.personal_not_available')
				@else
					<div class="title-bar">
						<h3>(Under Development)</h3>
						<div class="location-rating">
							@include('pages.components.pie_graph',
								array(
									'percent' => $location->getAccessibilityRating($rating_system),
									'size' => 'big'))
							<span class="percentage">{{ sprintf("%.1f", $location->getAccessibilityRating($rating_system)) }}%</span>
							<div class="foreground">
								<div class="accessible-label">Accessible</div>
								<div class="num-ratings">( {{ $num_ratings }} ratings )</div>
							</div>
						</div>
					</div>
					<div class="questions">
						@foreach ( $question_categories as $category )
							<div class="question-category">
								<a href="/location-report/{{ $location->id }}/{{ $rating_system }}/{{ $category->id }}">
								@include('pages.components.pie_graph', array('percent' => $category->getAccessibilityRating($location->id, $rating_system)))
								
									<span class="category-name">{{ $category->name }}</span>
									
									<span class="percentage">{{ $category->getAccessibilityRating($location->id, $rating_system).'%' }}</span>
								</a>
							</div>
						@endforeach
					</div>
				@endif
			</div>
		</div>
	</div>
</div>

@stop