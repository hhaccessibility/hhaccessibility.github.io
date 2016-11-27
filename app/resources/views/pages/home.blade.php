@extends('layouts.default')
@section('content')

<div class="home-page">
	<div class="row">
		<div class="col-lg-5">
			<div class="intro">
				<div class="title m-b-md">
					<h1>Access Locator</h1>
				</div>

				<div>
					<p>Find locations with accessibility ratings by real users
					and rate buildings yourself!</p>
				</div>
			</div>
		</div>
		<div class="col-lg-7">
			<div class="home-inputs">
				<div class="login-signup">
					<a href="/login">
						<table>
							<tr>
								<td class="user-icon">
									<i class="fa fa-user"></i>
								</td>
								<td class="text-center">
									Login / Signup
								</td>
							</tr>
						</table>
					</a>
				</div>
				<div class="input-group search">
					<form class="navbar-form" role="search">
						<table>
							<tr>
								<td>
									<input type="text" class="form-control">
								</td>
								<td class="search-button">
									<button class="btn btn-default" type="submit">
										<i class="fa fa-search"></i>
									</button>
								</td>
							</tr>
						</table>
					</form>
				</div>
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
	</div>
</div>

@stop