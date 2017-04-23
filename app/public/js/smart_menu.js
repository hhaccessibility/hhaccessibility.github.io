
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
