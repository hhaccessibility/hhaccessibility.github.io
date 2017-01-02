@extends('layouts.default')
@section('content')
<div class="profile-photo-upload">
	<h1>Profile Photo Upload</h1>
	
	<div class="profile-photo-upload-form">
		<form method="post" action="/profile-photo-upload" enctype="multipart/form-data">
			{!! csrf_field() !!}
			@include('pages.validation_messages', array('errors'=>$errors))					
			<div class="row">
				<div class="col-xs-12">
					<input class="clean" type="file" name="profile_photo">
				</div>
				<div class="col-xs-12">
					<input type="submit" class="clean" value="Upload Photo">
				</div>
			</div>
		</form>
	</div>
</div>
@stop