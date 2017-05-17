function bindeUpdateRadius() {
	preventSubmitForm();
	$("#updateRadius").click(onclickUpdateButtonEvent);
	function onclickUpdateButtonEvent(event){
		var updateRadiusApi = "/api/set-search-radius"
		var distNumber = parseFloat($("#distance").val());
		if (!distNumber || distNumber > 200  || distNumber<=0 ) {
			$("dev.disform").addClass("has-error");
			$("#distance").val("");
			return;
		}
		$.ajax(
			{
				url: updateRadiusApi,
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
	function refreshPage(data, status, xhr) {
		location.reload();
	}
	function preventSubmitForm(){
		$("form").submit(function(e){
			e.preventDefault(e);
		});
	}
}

$().ready(function() {
	bindeUpdateRadius();
});
