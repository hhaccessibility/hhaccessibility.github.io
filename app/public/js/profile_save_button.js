$(document).ready(function() {
	$("#profileForm").change(formChange);
	$("#resetButton").click(resetButton);
	var DefaultFormValue = $("#profileForm").serialize();

	function formChange() {
		if ( $("#profileForm").serialize() === DefaultFormValue) {
			$("#resetButton").prop("disabled", true);
			$("#submitButton").prop("disabled", true);
		} else {
			$("#resetButton").prop("disabled", false);
			$("#submitButton").prop("disabled", false);
		}
	}
	function resetButton() {
		$("#profileForm").trigger("reset");
		$("#resetButton").prop("disabled", true);
		$("#submitButton").prop("disabled", true);
	}
});
