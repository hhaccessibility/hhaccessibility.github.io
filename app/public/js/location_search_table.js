function bindeUpdateRadius() {
	$("#updateRadius").click(onclickUpdateButtonEvent);
	function onclickUpdateButtonEvent(event){
		var updateRadiusApi = "/api/set-search-radius"
		var distNumber = parseInt($("#distance").val());
		if (!distNumber) {
			$("#distance").val("");
			return;
		}
		/*
		$.post( updateRadiusApi,
			{
				distantce: distNumber,
				_token: $("#_token").val()
			}, function(data) {
				$("div.data").html(data);
			}
		);
		*/
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
				success: refreshDiv
			}
		);
		event.preventDefault();
	}
	function refreshDiv(data, status, xhr) {
		$("div.data").html(data);
		/*
		console.log(xhr.getAllResponseHeaders());
		var x = xhr.getResponseHeader("X-CSRF-Token");
		console.log(x);
		*/
	}
}

function bindDistanceNumberOnly() {
	$("#distance ").on("input", function(evt) {
		var self = $(this);
		self.val(self.val().replace(/[^\d].+/, ""));
		if ((evt.which < 48 || evt.which > 57)) 
		{
			evt.preventDefault();
		}
	});
}
$().ready(function() {
	bindeUpdateRadius();
	bindDistanceNumberOnly();
});
