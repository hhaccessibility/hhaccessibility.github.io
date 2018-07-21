	<div class="title">
		<div class="search-and-title">
			@if ($location_search_path)
				<a href="{{ $location_search_path }}" title="Back to last location search results" class="fa fa-search"></a>
			@endif
			@if (isset($question_category_id))
				<h1><a href="/location/report/{{ $location->id }}/{{ $rating_system }}">{{ $location->name }}</a></h1>
			@else
				<h1>{{ $location->name }}</h1>
			@endif
		</div>
		<div class="universal-personal">
			<a class="{{ $rating_system === 'universal' ? 'selected' : '' }}"
				href="/location/report/{{ $location->id }}/universal{{
					isset($question_category_id) 
					? '/'.$question_category_id
					: ''
				}}">Universal</a>
			<a class="{{ $rating_system === 'personal' ? 'selected' : '' }}"
			href="/location/report/{{ $location->id }}/personal{{
					isset($question_category_id) 
					? '/'.$question_category_id
					: ''
				}}">Personal</a>
		</div>
	</div>
