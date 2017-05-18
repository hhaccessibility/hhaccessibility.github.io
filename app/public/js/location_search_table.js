function bindUpdateRadius() {
	$("#updateRadius").click(onclickUpdateButtonEvent);
	function onclickUpdateButtonEvent(event){
		var distNumber = parseFloat($("#distance").val());
		if (!distNumber || distNumber<=0 ) {
			$("dev.disform").addClass("has-error");
			$("#distance").val("");
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
				success: refreshPage
			}
		);
		event.preventDefault();
	}
	function refreshPage() {
		location.reload();
	}
}

$().ready(function() {
	bindeUpdateRadius();
});
