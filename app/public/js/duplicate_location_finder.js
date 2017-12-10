function confirmDuplicate() {
	var $row = $(this).closest('.row');
	var location2_id = $row.data('location_id');
	$.ajax({
		'url': '/duplicates-confirmed',
		'method': 'PUT',
		'data': {
			'location_ids': [location_id, location2_id],
			'_token': csrf_token
		},
		'error': function() {
			alert('duplicates-confirmed failed.');
		},
		'success': function() {
			window.location.reload(true);
		}
	});
}

function init() {
	$('button').click(confirmDuplicate);
}

$(document).ready(init);

