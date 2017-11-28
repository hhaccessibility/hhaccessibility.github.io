/*
location_report.js is used in the report on a single location, 
pages/location_report/collapsed.blade.php.
*/

function hideLocationTagIcons() {
	$('.location-tag').attr('title', '');
	$('.location-report').removeClass('show-location-tag-icons');
}

function showLocationTagIcons() {
	$('.location-report').addClass('show-location-tag-icons');
	$('.location-tag').each(function(index, e) {
		var $e = $(e);
		$e.attr('title', $e.find('.name').text());
	});
}

var location_tag_width_when_expanded = undefined;

function updateShowLocationTagIcons() {
	if( location_tag_width_when_expanded === undefined && $('.show-location-tag-icons').length === 0 ) {
		location_tag_width_when_expanded = 0;
		var gap = 5;
		$('.location-tag').each(function(index, e) {
			location_tag_width_when_expanded += $(e).outerWidth() + gap;
		});
		// If the width is actually really small, we still want to treat it like it is at least 100 pixels
		// because it looks inconsistent to have the icons used on some location 
		// reports(with only 1 tag) but not others(with many like Devonshire Mall).
		location_tag_width_when_expanded = Math.max(200, location_tag_width_when_expanded);
	}
	var $container = $('.location-tags');
	if( location_tag_width_when_expanded !== undefined && location_tag_width_when_expanded > $container.outerWidth() ) {
		showLocationTagIcons();
	}
	else {
		hideLocationTagIcons();
	}
}

function updateHeightOfMap() {
  var $map_parent = $('.map-and-box');
  var $map = $('#map');
  var $copyright = $('#copyright');
  var height = window.innerHeight - $(map).offset().top - $copyright.height();
  if (height < 100)
	height = 100;

  $map.height(height);
  $map_parent.height(height);
}
$(window).resize(function() {
	updateHeightOfMap();
	updateShowLocationTagIcons();
});
document.addEventListener("DOMContentLoaded", function(event) {
	updateHeightOfMap();
	updateShowLocationTagIcons();
});