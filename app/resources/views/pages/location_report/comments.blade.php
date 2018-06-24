@extends('layouts.default')

@section('content')
	<div class="location-comments">
		<h1><a href="/location-report/{{ $location->id }}">{{ $location->name }}</a></h1>
			<div class="comments">
				<h2>Comments</h2>
				@if ( count($comments) === 0 )
					<p>There are no comments for this category.</p>
				@else
					<?php $current_category_name = ""; ?>
					@foreach ( $comments as $comment )

						<div class="category-subheader">
							@if ($current_category_name != $comment->getQuestionCategory()->name)
								@if ($current_category_name != "")
									<hr>
								@endif
								{{ $comment->getQuestionCategory()->name }}
								<?php $current_category_name = $comment->getQuestionCategory()->name; ?>
							@endif
						</div>
						<div class="comment">

							<div class="comment-header">
								{{ $comment->getAnsweredByUser()->first_name }}
								{{ $comment->getAnsweredByUser()->last_name }}
								said,

								<span class="pull-right">
								{{ $comment->getWhenSubmitted() }}
								</span>
							</div>
							<div class="comment-content">
								{{ $comment->content }}
							</div>
						</div>
					@endforeach
				@endif
			</div>
	</div>
@stop