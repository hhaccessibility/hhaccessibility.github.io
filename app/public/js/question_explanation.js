/*
This depends on utils.js and jQuery.
*/

function initQuestionExplanationLinks() {
	function showQuestionExplanationDialog(question_id) {
		$.ajax({
			'url': '/api/question-explanation/' + question_id,
			'success': function(response) {
				showMessage(response.html, 'question-explanation-dialog');
			}
		});
	}

	function replaceWithDialog() {
		var question_id = this.href;
		if (question_id.lastIndexOf('/') !== -1) {
			question_id = question_id.substring(question_id.lastIndexOf('/') + 1);
		}
		if (question_id.indexOf('"') !== -1) {
			question_id = question_id.substring(0, question_id.indexOf('"'));
		}
		this.removeAttribute('href');
		$(this).click(function() {
			showQuestionExplanationDialog(question_id);
		});
	}

	return isUsingScreenReader().then(function(response) {
		if (response === false) {
			var $links = $('.question-explanation-link');
			$links.each(replaceWithDialog);
		}
	});
}