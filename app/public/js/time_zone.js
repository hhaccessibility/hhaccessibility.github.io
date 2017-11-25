function sendTimeZoneOffsetToServerIfNeeded()
{
	var time_zone_offset = new Date().getTimezoneOffset();
	if ( time_zone_offset_from_server === time_zone_offset )
	{
		return; // no need to reload.
	}
	
	$.ajax({
		'method': 'POST',
		'url': '/time-zone',
		'data': {
			'time_zone_offset': time_zone_offset,
			'_token': csrf_token
		},
		'success': function() {
			// reload page.
			location.reload();
		}
	});
}

$(document).ready(sendTimeZoneOffsetToServerIfNeeded);