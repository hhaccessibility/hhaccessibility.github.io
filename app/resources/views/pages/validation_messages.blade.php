@if ($errors->has(NULL))
<div class="alert alert-danger">
	@if (isset($show_only_first) && $show_only_first)
		{{ $errors->all()[0] }}
	@else
		@foreach ($errors->all() as $error)
			{{ $error }}<br>
		@endforeach
	@endif
</div>
@endif
