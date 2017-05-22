$(document).ready(function() {
	$("#resetbtn").click(resetbtn);
	$("#profileForm").change(formChange);
	var DefaultFormValue = $("#profileForm").serialize();

	function form_change() {
			$("#resetbtn").prop("disabled",true);
			$("#submitbtn").prop("disabled",true);
		if ( $("#profileForm").serialize() === DefaultFormValue) {
		} else {
			$("#resetbtn").prop("disabled",false);
			$("#submitbtn").prop("disabled",false);
		}
	}
	function resetbtn() {
		$("#resetbtn").prop("disabled",true);
		$("#submitbtn").prop("disabled",true);
		$("#profileForm").trigger("reset");
	}
});
