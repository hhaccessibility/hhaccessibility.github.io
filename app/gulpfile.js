const elixir = require('laravel-elixir');

require('laravel-elixir-vue-2');

/*
|--------------------------------------------------------------------------
| Elixir Asset Management
|--------------------------------------------------------------------------
|
| Elixir provides a clean, fluent API for defining some basic Gulp tasks
| for your Laravel application. By default, we are compiling the Sass
| file for your application as well as publishing vendor resources.
|
 */

var bootstrap_sass = './node_modules/bootstrap-sass/';
elixir((mix) => {
	// copy bootstrap fonts to public folder
	mix.copy(bootstrap_sass + "assets/fonts/bootstrap", 'public/fonts/bootstrap');
	mix.sass('app.scss')
		.webpack('app.js');
});
