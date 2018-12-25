@extends('layouts.default')
@section('head-content')
    <script language="JavaScript" src="/js/jquery-3.1.1.js"></script>
    <script>
		var suggestion_id = {{ $suggestion->id }};
	</script>
    <script src="/js/suggestion_detail.js"></script>
@stop
@section('content')
<div class="suggestion-detail">
	<h1 class="text-center">{{ $user_name }}'s <a href="/suggestion-list/{{ $suggestion->location_id }}">Suggestion for {{ $location_name }}</a></h1>
	<form class="form">
        {!! csrf_field() !!}
        <input type="hidden" id="suggestion_id" value="{{ $suggestion->id }}">
        <input type="hidden" id="location_id" value="{{ $suggestion->location_id }}">
        <div class="row">
            <div class="form-group col-md-12">
                <div class="col-md-6">
                    <label for="original_name">Name:</label>
                    <input class="form-control" type="text" id="original_name" value="{{ $original_location->name }}" readonly>
                    <input class="form-control" type="text" id="suggestion_name" value="{{ $suggestion->location_name }}" readonly>
                    <input type="button" class="btn btn-primary" id="accept_name" value="Accept">
                    <input type="button" class="btn btn-warning resolve" value="Mark as Resolved">
                </div>
                <div class="col-md-6">
                    <label for="original_phone_number">Phone Number:</label>
                    <input class="form-control" type="text" id="original_phone_number" value="{{ $original_location->phone_number }}" readonly>
                    <input class="form-control" type="text" id="suggestion_phone_number" value="{{ $suggestion->location_phone_number }}" readonly>
                    <input type="button" class="btn btn-primary" id="accept_phone_number" value="Accept">
                    <input type="button" class="btn btn-warning resolve" value="Mark as Resolved">
                </div>
            </div>
        </div>
        
        <div class="form-group">
            <label for="original_address">Address:</label>
            <input class="form-control" type="text" id="original_address" value="{{ $original_location->address }}" readonly>
            <input class="form-control" type="text" id="suggestion_address" value="{{ $suggestion->location_address }}" readonly>
            <input type="button" class="btn btn-primary" id="accept_address" value="Accept">
            <input type="button" class="btn btn-warning resolve" value="Mark as Resolved">
        </div>
        
        <div class="form-group">
            <label for="original_external_web_url">External Web Url:</label>
            <input class="form-control" type="text" id="original_external_web_url" value="{{ $original_location->external_web_url }}" readonly>
            <input class="form-control" type="text" id="suggestion_external_web_url" value="{{ $suggestion->location_external_web_url }}" readonly>
            <input type="button" class="btn btn-primary" id="accept_external_web_url" value="Accept">
            <input type="button" class="btn btn-warning resolve" value="Mark as Resolved">
        </div>

        <div>
            <input type="button" class="btn btn-primary" id="accept_all" value="Accept All">
            <input type="button" class="btn btn-warning resolve" id="resolve_all" value="Mark as Resolved">
        </div>
    </form>
	
</div>
@stop