/*
location_report.js is used in the report on a single location, 
pages/location_report/collapsed.blade.php.
*/

function updateHeightOfMap() {
  var $map_parent = $('.map-and-box');
  var $map = $('#map');
  var $copyright = $('#copyright');
  var height = window.innerHeight - $(map).offset().top - $copyright.height();
  if (height < 100)
	height = 100;

  $map.height(height);
  $map_parent.height(height);
  
  $questionBox = $('.questions-box');
  var questionBoxHeight = $questionBox.height();
  var questionBoxTop = Math.max(0, (height - questionBoxHeight) / 2);
	$questionBox.css('top', Math.round(questionBoxTop) + 'px');
}
$(window).resize(updateHeightOfMap);
document.addEventListener("DOMContentLoaded", function(event) {
	updateHeightOfMap();
});