/**
Makes image slideshow available for location report
*/
$(document).ready(function() {
	var images;
	var current_image_index = 0;
	
	function getImageURL(image_id) {
		return '/location/image/' + image_id;
	}

	function preloadImage(image) {
		image.imgElement = new Image();
		image.imgElement.src = getImageURL(image.id);
	}

	function getLocationId() {
		var url = window.location.href;
		var index;
		var token = '/location/report/';
		if (url.indexOf(token) !== -1) {
			url = url.substring(url.indexOf(token) + token.length);
			if( url.indexOf('/') !== -1 ) {
				url = url.substring(0, url.indexOf('/'));
			}
			return url;
		}
		throw new Error('Unable to get location id.');
	}
	
	function hideSlideshow() {
		$('.slideshow').remove();
		$(document).off('keydown', processArrowKey);
	}
	
	function updateBackgroundImage($image) {
		if ($image === undefined) {
			$image = $('.image');
		}
		$image.css({
			backgroundImage: 'url("' + getImageURL(images[current_image_index].id) + '")'
		});
	}
	
	function nextClicked() {
		current_image_index = (current_image_index + 1) % images.length;
		updateBackgroundImage();
		event.stopImmediatePropagation();
	}

	function processArrowKey(event) {
		if (event.keyCode === 39) {
			// right key
			nextClicked();
		}
		else if (event.keyCode === 37) {
			previousClicked();
		}
	}

	function previousClicked() {
		current_image_index = (current_image_index + images.length - 1) % images.length;
		updateBackgroundImage();
		event.stopImmediatePropagation();
	}

	function showSlideshow() {
		var $slideshow = $('<div class="slideshow"></div>');
		$slideshow.click(hideSlideshow);
		var $dialog = $('<div class="slideshow-dialog"></div>');
		var $image = $('<div class="image"></div>');
		updateBackgroundImage($image);
		var $next = $('<a class="next" title="Next"></a>').click(nextClicked);
		$dialog.append($next);
		var $previous = $('<a class="previous" title="Previous"></a>').click(previousClicked);
		$dialog.append($previous);
		$dialog.append($image);
		$slideshow.append($dialog);
		$('body').append($slideshow);
		$(document).on('keydown', processArrowKey);
	}

	function addSlideShowButton() {
		// If there are any images to view, add a button for viewing them.
		if (images.length > 0) {
			var $images_button = $('.images-button');
			$images_button.addClass('has-images');
			$images_button.find('a').click(showSlideshow);
		}
	}

	function preloadImages() {
		images.forEach(preloadImage);
	}

	function downloadImages(location_id) {
		return $.ajax({
			'url': '/api/location/image/' + location_id,
			'success': function(response) {
				images = response;
			},
			'error': function() {
				console.error('Failed to get images.');
			}
		});
	}

	var location_id = getLocationId();
	downloadImages(location_id).then(addSlideShowButton).then(preloadImages);
});