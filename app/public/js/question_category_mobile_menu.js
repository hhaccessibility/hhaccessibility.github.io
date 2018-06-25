// Used for the View and Rating features on a specific question category and location.
// Manages the mobile menu for listing question categories

$(document).ready(function() {

	function getHamburgerMenuElement() {
		return $('.collapse-toggle-button');
	}

	function getRateViewToggleElement() {
		return $('.collapsible-menu');
	}

	function showPopup() {
		getRateViewToggleElement().addClass('show-popup');
	}

	function hidePopup() {
		getRateViewToggleElement().removeClass('show-popup');
	}

	function togglePopup() {
		getRateViewToggleElement().toggleClass('show-popup');
		console.log('togglePopup called.  show-popup is: ' + getRateViewToggleElement().hasClass('show-popup'));
	}

	var previous_display = undefined;
	function viewportWidthUpdated() {
		var $hamburger_menu = getHamburgerMenuElement();
		var display = $hamburger_menu.css('display');
		if( display !== previous_display && (display !== 'none' || previous_display !== undefined) ) {
			console.log('viewportWidthUpdated called. display = ' + display);
			if( display !== 'none' ) {
				$hamburger_menu.click(togglePopup);
				$hamburger_menu.mouseover(showPopup);
			}
			else {
				$hamburger_menu.unbind();
				hidePopup();
			}
			previous_display = display;
		}
	}

	$(window).resize(viewportWidthUpdated);
	viewportWidthUpdated();
});