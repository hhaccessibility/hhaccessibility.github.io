document.addEventListener("DOMContentLoaded", function(event) {
	var fields = ['name', 'phone_number', 'address', 'external_web_url'];
	var resolved = {};

	function isSuggestionDoingNothing(field) {
		var original_val = $('#original_' + field).val();
		var suggested_val = $('#suggestion_' + field).val();
		return original_val === suggested_val;
	}

	function hide(field) {
		field = 'accept_' + field;
		var $parent = $('#' + field).closest('div');
		$parent.addClass('does-nothing');
		var $form_rows = $parent.closest('form .row');

		// If any row contains more than 
		if ($form_rows.find('.does-nothing').length > 1) {
			$form_rows.addClass('does-nothing');
		}
	}

	function hideSuggestionsThatDoNothing() {
		fields.filter(isSuggestionDoingNothing).forEach(hide);
	}

	function bindField(tag) {
		var $tag = $("#accept_" + tag);
		var $div = $tag.closest('div');
		$tag.click(function() {
			sendRequest(tag);
		});
		$div.find('.resolve').click(function() {
			markResolved(tag);
		});
	}

	function getData(fieldname) {
		var data = {};
		data._token = $('[name="_token"]').val();
		return data;
	}

	/**
	Removes the 'accept_' prefix for the specified string.
	For example, removePrefix('accept_all') === 'all'.
	*/
	function removePrefix(tag) {
		if (tag.indexOf('accept_') === 0) {
			return tag.substring('accept_'.length);
		}
		else {
			return tag;
		}
	}

	function getCSRFToken() {
		return $('input[name="_token"]').val();
	}

	function sendRequest(tag) {
		var method = 'PUT';
		if (tag === 'resolve_all') {
			method = 'DELETE';
			url = '/api/suggestion/' + suggestion_id;
		}
		else {
			url = '/api/suggestion/merge/' + suggestion_id + '/' + removePrefix(tag);
		}
		$.ajax({
			url: url,
			method: method,
			data: {
				'_token': getCSRFToken()
			},
			success: function() {
				if (tag === 'accept_all' || tag === 'resolve_all') {
					// navigate to suggestion list.
					location.href = '/suggestion-list';
				}
				else {
					location.reload();
				}
			},
			error: function(result) {
				$("#" + tag).parent().prepend("<div class='alert alert-danger'>"
												+ "Something went wrong."
												+ "</div>");
			}
		});
	}

	function markResolved(fieldname) {
		resolved[fieldname] = true;
		hide(fieldname);
	}

	fields.forEach(bindField);
	hideSuggestionsThatDoNothing();
	['resolve_all', 'accept_all'].forEach(function(tag) {
		$('#' + tag).click(function() {
			sendRequest(tag);
		});
	});
});
