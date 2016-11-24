@extends('layouts.default')
@section('content')

	<div class="row">
		<div class="col-lg-6">
			<div class="title m-b-md">
				<h1>Access Locator</h1>
			</div>

			<div>
				<p>Find locations with accessibility ratings by real users
				and rate buildings yourself!</p>
			</div>
		</div>
		<div class="col-lg-6">
			<div class="building-tags">
				<div class="row">
				@foreach ( $building_tags as $key => $value )
				
					<div class="building-tag col-md-3 col-sm-4 col-xs-6">
					<a href="/search-by-tag/{{ $value->id }}" title="{{ $value->description }}">
					{{ $value->name }}
					</a>
					</div>
					
				@endforeach
				</div>
			</div>
		</div>
	</div>
	<div class="links">
		<a href="http://www.jmccentre.ca/">JMCC</a>
		<a href="https://github.com/hhaccessibility/hhaccessibility.github.io">GitHub</a>
	</div>
@stop