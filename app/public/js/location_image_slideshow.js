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
		if (images[current_image_index].can_be_deleted) {
			$('.slideshow').addClass('can-be-deleted');
		}
		else {
			$('.slideshow').removeClass('can-be-deleted');
		}
	}

	function nextClicked(event) {
		if (!event) {
			event = window.event;
		}
		current_image_index = (current_image_index + 1) % images.length;
		updateBackgroundImage();
		event.stopImmediatePropagation();
	}

	function processArrowKey(event) {
		if (!event) {
			event = window.event;
		}
		if (event.keyCode === 39) {
			// right arrow key
			nextClicked(event);
		}
		else if (event.keyCode === 37) {
			// left arrow key
			previousClicked(event);
		}
		else if (event.keyCode === 46) {
			deleteClicked(event);
		}
	}

	function previousClicked(event) {
		if (!event) {
			event = window.event;
		}
		current_image_index = (current_image_index + images.length - 1) % images.length;
		updateBackgroundImage();
		event.stopImmediatePropagation();
	}

	function deleteClicked(event) {
		if (!event) {
			event = window.event;
		}
		event.stopImmediatePropagation();
		if (!images[current_image_index].can_be_deleted) {
			return; // Do nothing because we're not allowed to.
		}
		var image_id = images[current_image_index].id;
		deleteImage(image_id).then(function() {
			// Remove the deleted image from the images Array.
			images = images.filter(function(image) {
				return image.id !== image_id;
			});
			// If no images remain, hide the slideshow and the image button.
			if (images.length === 0) {
				hideSlideShowButton();
				hideSlideshow();
			}
			else {
				// Make sure current_image_index stays in a valid Array index.
				if (current_image_index >= images.length) {
					current_image_index = 0;
				}
				updateBackgroundImage();
			}
		});
	}

	function showSlideshow() {
		var $slideshow = $('<div class="slideshow"></div>');
		$slideshow.click(hideSlideshow);
		var $dialog = $('<div class="slideshow-dialog"></div>');
		var $image = $('<div class="image"></div>');
		var $next = $('<a class="next" title="Next"></a>').click(nextClicked);
		$dialog.append($next);
		var $previous = $('<a class="previous" title="Previous"></a>').click(previousClicked);
		$dialog.append($previous);
		var $delete = $('<a class="delete" title="Delete image"><em class="fa fa-trash"></em></a>').click(deleteClicked);
		$dialog.append($image);
		$dialog.append($delete);
		$slideshow.append($dialog);
		$('body').append($slideshow);
		updateBackgroundImage($image);
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

	function hideSlideShowButton() {
		var $images_button = $('.images-button');
		$images_button.removeClass('has-images');
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
	
	function deleteImage(image_id) {
		return $.ajax({
				'url': '/api/location/image/' + image_id,
				'method': 'DELETE',
				'data': {
					'_token': csrfToken,
				},
				'error': function( jqXHR, textStatus, errorThrown) {
					console.error('Failed to delete image ' + image_id + ' textStatus = ' + textStatus + ', errorThrown = ' + errorThrown + ', jqXHR = ', jqXHR);
				}
		});
	}

	var location_id = getLocationId();
	downloadImages(location_id).then(addSlideShowButton).then(preloadImages);
});