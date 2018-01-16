@extends('layouts.default')
@section('head-content')
	<script language="JavaScript" src="/js/jquery-3.1.1.js"></script>	
	<script language="JavaScript" src="/js/pie_graph.js"></script>
@stop
@section('content')

<div class="location-report ratings-only">
	@include('pages.location_report.top', array(
		'rating_system' => $rating_system,
		'location' => $location))
	<div class="row">
		<div class="col-xs-5">
			<address>{{ $location->address }}</address>
			<a href="{{ $location->getExternalWebURL() }}">{{ $location->getExternalWebURL() }}</a>
		</div>
		<div class="col-xs-7 text-right">
			<div class="location-tags">
			@foreach ( $location->tags()->orderBy('name')->get() as $location_tag )
				<a class="location-tag" title="{{ $location_tag->name }}" href="/location-search?location_tag_id={{ $location_tag->id }}">
					<span class="name">{{ $location_tag->name }}</span>
					<span class="icon {{ $location_tag->icon_selector }}"></span>
				</a>
			@endforeach
			</div>
		</div>
	</div>
	<div class="questions-box">
		@if ($rating_system === 'personal' && !$personal_rating_is_available)
			@include('pages.location_report.personal_not_available')
		@else
			<div class="title-bar">
				<h3>{{ $location->name }}</h3>
				<div class="location-rating">
					@include('pages.components.pie_graph',
						array(
							'percent' => $location->getAccessibilityRating($rating_system),
							'size' => 'big'))
					<span class="percentage">{{ round($location->getAccessibilityRating($rating_system)) }}%</span>
					<div class="foreground">
						<div class="accessible-label">Accessible</div>
						<div class="num-ratings">( {{ $num_ratings }} ratings )</div>
					</div>
				</div>
			</div>
			<div class="questions">
				@foreach ( $question_categories as $category )
					<div class="question-category">
						<a href="/location-reporting/{{ $location->id }}/{{ $category->id }}">
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

@stop