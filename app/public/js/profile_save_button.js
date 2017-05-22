$(document).ready(function() {
	$("#profileForm").change(formChange);
	var DefaultFormValue = $("#profileForm").serialize();

	function formChange() {
		if ( $("#profileForm").serialize() === DefaultFormValue) {
			$("#submitButton").prop("disabled", true);
		} else {
			$("#submitButton").prop("disabled", false);
		}
	}
});
