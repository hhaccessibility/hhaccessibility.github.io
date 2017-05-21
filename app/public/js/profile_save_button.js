$(document).ready(function() {
	$("#profile_form").change(form_change);
	$("#resetbtn").click(resetbtn);
	var DefaultFormValue = $("#profile_form").serialize();

	function form_change() {
		if ( $("#profile_form").serialize() === DefaultFormValue) {
			$("#resetbtn").prop("disabled",true);
			$("#submitbtn").prop("disabled",true);
		} else {
			$("#resetbtn").prop("disabled",false);
			$("#submitbtn").prop("disabled",false);
		}
	}
	function resetbtn() {
		$("#profile_form").trigger("reset");
		$("#resetbtn").prop("disabled",true);
		$("#submitbtn").prop("disabled",true);
	}
});
