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
	return setSearchRadius(distNumber).fail(function() {
		onUpdateRadiusError();
	});
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
