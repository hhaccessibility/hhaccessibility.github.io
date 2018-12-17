$(document).ready(function() {
	function tagToggleClicked() {
		var $this = $(this);
		var location_tag_id = parseInt($this.data('tag-id'));
		var location_id = $this.closest('.row').data('location-id');
		var data = {
			'_token': csrf_token,
			'location_id': location_id,
			'location_tag_id': location_tag_id
		};
		var headers = {
			'X-CSRF-TOKEN': csrf_token
		};
		var toggle_css_class = 'selected';
		var method;
		if ($this.hasClass(toggle_css_class)) {
			method = 'DELETE';
		}
		else {
			method = 'POST';
		}
		$.ajax({
			'url': '/location/tag',
			'method': method,
			'data': data,
			'headers': headers,
			'success': function() {
				// I'm not using toggleClass because that would risk making the UI show 
				// a different state than what the database has if someone clicks the same 
				// button before old requests complete.
				if (method === 'DELETE') {
					$this.removeClass(toggle_css_class);
				}
				else {
					$this.addClass(toggle_css_class);
				}
			}
		});
	}

	function intializeLocationBindings(index, location_element) {
		$(location_element).find('button').each(function(index, e) {
			$(e).on('click', tagToggleClicked);
		});
	}
	
	function refreshPage() {
		location.reload();
	}

	function initializeAllBindings() {
		$('.data .row').each(intializeLocationBindings);
		$('#refresh-page').click(refreshPage);
	}

	initializeAllBindings();
});