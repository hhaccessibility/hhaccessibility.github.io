var defaultFormValue;

// submitted is true if the user profile form is being submitted.
var submitted = false;

function isProfileChanged()
{
	return $("#profileForm").serialize() !== defaultFormValue;
}

$(document).ready(function() {
	$("#profileForm").change(formChange).submit(function() {
		submitted = true;
	});
	defaultFormValue = $("#profileForm").serialize();

	function formChange() {
		$("#submitButton").prop("disabled", !isProfileChanged());
	}
});
