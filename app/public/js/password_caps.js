document.addEventListener( 'keydown', function( event ) {
	var caps = event.getModifierState && event.getModifierState( 'CapsLock' );
	if(caps) {
  		$("#capsLock").show();
  	} else {
  		$("#capsLock").hide();
  	}
});