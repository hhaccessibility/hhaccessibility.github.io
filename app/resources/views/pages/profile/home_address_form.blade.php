@extends('layouts.default', ['body_class' => 'nav-profile'])
@section('head-content')
  <script src="/css/jquery/external/jquery/jquery.js"></script>
  <script src="/js/profile_home_address.js"></script>
@stop
@section('content')
<div class="home-address">
	<h1>Home Region</h1>
	<p>Let us know roughly where you live so we can improve your experience with AccessLocator.</p>
	<form method="post" action="/profile/home-address">
		{!! csrf_field() !!}
		@include('pages.validation_messages', array('errors'=>$errors))
		<div class="box">
			<div class="form-group">
				<div class="row">
					<div class="col-sm-4 col-xs-5">
						<label for="home_country_id">Country</label>
					</div>
					<div class="col-sm-8 col-xs-7">
						<select class="form-control" id="home_country_id" name="home_country_id">
							<option value="">-- Select Country --</option>
							@foreach ($countries as $country)
								@if ( in_array($country->id, $enabled_country_ids) )
								<option value="{{ $country->id }}"
									@if ( $user->home_country_id === $country->id )
									selected
									@endif
								>{{ $country->name }}</option>
								@endif
							@endforeach
						</select>
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="row">
					<div class="col-sm-4 col-xs-5">
						<label for="province">State/Province</label>
					</div>
					<div class="col-sm-8 col-xs-7">
						<select class="form-control" id="home_region" name="home_region" data-value="{{ $user->home_region }}">
						</select>
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="row">
					<div class="col-sm-4 col-xs-5">
						<label for="home_city">City</label>
					</div>
					<div class="col-sm-8 col-xs-7">
						<input class="form-control" id="home_city" name="home_city" value="{{ $user->home_city }}">
					</div>
				</div>
			</div>
		</div>

		<div class="text-right footer">
			<a href="/profile">
				<button type="button" class="btn btn-lg btn-primary save-button">Cancel</button>
			</a>
			<button type="submit" class="btn btn-lg btn-primary save-button">Save</button>
		</div>
	</form>
</div>

@stop