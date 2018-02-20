@extends('layouts.default', ['body_class' => 'error'])
@section('content')
<div class="missing">
	<h1>Missing</h1>
	<p class="alert alert-warning"><strong>{{ $message }}</strong></p>
</div>
@stop