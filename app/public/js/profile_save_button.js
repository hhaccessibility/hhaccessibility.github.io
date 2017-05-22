$(document).ready(function() {
	$("#profileForm").change(formChange);
	$("#resetButton").click(resetButton);
	var DefaultFormValue = $("#profileForm").serialize();

	function form_change() {
			$("#submitbtn").prop("disabled",true);
		if ( $("#profileForm").serialize() === DefaultFormValue) {
			$("#resetButton").prop("disabled", true);
		} else {
			$("#submitbtn").prop("disabled",false);
			$("#resetButton").prop("disabled", false);
		}
	}
		$("#submitbtn").prop("disabled",true);
	function resetButton() {
		$("#profileForm").trigger("reset");
		$("#resetButton").prop("disabled", true);
	}
});
