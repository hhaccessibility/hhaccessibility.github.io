@extends('layouts.default', ['body_class' => 'nav-faq'])
@section('content')
<div class="faq">
	<h1>Frequently Asked Questions</h1>
	
	<div class="faq-items">
	@foreach ($faq_items as $faq_item)
	
		<div>
			<p>{{ $faq_item->question }}</p>
			<div class="answer">
				{!! $faq_item->answer_html !!}
			</div>
		</div>
	
	@endforeach
	</div>
</div>
@stop