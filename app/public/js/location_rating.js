function setComment(comment)
{
	$.ajax({
		'method': 'PUT',
		'url': '/location-rating/comment',
		'data': {
			'location_id': location_id,
			'question_category_id': question_category_id,
			'comment': comment,
			'_token': csrf_token
		}
	});	
}

function removeAnswer(question_id)
{
	$.ajax({
		'method': 'DELETE',
		'url': '/location-rating/answer',
		'data': {
			'location_id': location_id,
			'question_id': question_id,
			'_token': csrf_token
		}
	});
}

function saveAnswerChange(question_id, answer_text)
{
	// Turn the answer text into a number 0, 1, 2.
	answer_text = answer_text.toLowerCase().trim();
	var answer_value_map = {"no": 0, "yes": 1, "not applicable": 2};
	if ( answer_value_map[answer_text] !== undefined )
	{
		answer_text = answer_value_map[answer_text];
	}
	$.ajax({
		'method': 'PUT',
		'url': '/location-rating/answer',
		'data': {
			'location_id': location_id,
			'question_id': question_id,
			'answer': answer_text,
			'_token': csrf_token
		}
	});
}

function answerClicked()
{
	var $this = $(this);
	var $question_element = $this.closest('.questions > div.row');
	var question_id = parseInt($question_element.data('question-id'));
	if ( $this.hasClass('selected') )
	{
		$this.removeClass('selected');
		removeAnswer(question_id);
		return;
	}

	saveAnswerChange(question_id, $this.text());	
	$this.addClass('selected');
	var $sibling_answers = $question_element.find('.answers > div');
	$.each($sibling_answers, function(index, sibling_answer) {
		if ( sibling_answer !== $this[0] )
		{
			$(sibling_answer).removeClass('selected');
		}
	});
}

function getCommentElement()
{
	return $('.rate textarea.clean');
}

function saveCommentFromUI()
{
	setComment(getCommentElement().val());
}

function initAnswerBindings()
{
	var $answer_buttons = $('.questions .answers > div');
	var $comment = getCommentElement();

	$answer_buttons.bind('click', answerClicked);
	$comment[0].setComment = setComment;
	$comment[0].getCommentElement = getCommentElement;
	$comment.bind('keyup blur', saveCommentFromUI);
}

$(document).ready(initAnswerBindings);

/*
When the header menu and icon are scrolled off the top of the viewport,
give the body element a fixed-menu class so the left menu gets fixed
to the top of the viewport. 
*/
window.addEventListener('scroll', function(event)
{
	var element = event.target;
	var scroll_top = window.scrollY;
	if ( scroll_top === undefined )
	{
		scroll_top = document.documentElement.scrollTop;
	}
	var header = document.getElementsByTagName('header')[0];
	var headerHeight = header.clientHeight;
	if ( headerHeight === undefined ) {
		headerHeight = 40;
	}
	if ( headerHeight < scroll_top ) {
		document.body.className = 'fixed-menu';
	}
	else {
		document.body.className = '';
	}
});
