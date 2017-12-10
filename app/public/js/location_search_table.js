function onUpdateRadiusError()
{
	$("div.disform").addClass("has-error");
	$("#distance").val("");
}

function getDistanceElement()
{
	return $("#distance");
}

function updateRadiusClicked()
{
	var distNumber = parseFloat(getDistanceElement().val());
	if (!distNumber || distNumber<=0 ) {
		onUpdateRadiusError();
		return;
	}
	$.ajax(
		{
			url: "/api/set-search-radius",
			type: 'post',
			headers: {
				'X-CSRF-Token': $("#_token").val()
			},
			data: {
				'distance': distNumber,
				'_token': $("#_token").val()
			},
			fail: function() {
				console.error('Something failed in ajax request');
				onUpdateRadiusError();
			},
			success: refreshPage
		}
	);
}

function refreshPage() {
	location.reload();
}

function bindUpdateRadius() {
	$("#updateRadius").click(updateRadiusClicked);
	getDistanceElement().keypress(function(event) {
		if ( event.which === 13 ) // ENTER keypress
		{
			updateRadiusClicked();
		}
	});
}

$(document).ready(function() {
	bindUpdateRadius();
});
