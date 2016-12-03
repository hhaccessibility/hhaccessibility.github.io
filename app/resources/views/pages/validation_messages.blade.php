@if ($errors->has(NULL))
<div class="alert alert-danger">
	@foreach ($errors->all() as $error)
		{{ $error }}<br>        
	@endforeach
</div>
@endif
