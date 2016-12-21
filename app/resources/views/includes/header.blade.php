<div class="navbar-inner">
	<a id="logo" href="/">AccessLocator</a>
	<ul class="nav pull-right">
		<li><a href="/">Home</a></li>
		<li><a href="/our-vision">Our Vision</a></li>
		<li><a href="/profile">Profile</a></li>
		@if ( $base_user->isSignedIn() )
		<li><a href="/signout">Sign Out</a></li>
		@endif
		<li><a href="/contact">Contact</a></li>
	</ul>
</div>
