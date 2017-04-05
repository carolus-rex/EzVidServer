const { mix } = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/assets/js/app.js', 'public/js');

mix.styles(['resources/assets/sass/app.scss',
			'resources/assets/sass/custom.scss',
			'resources/assets/sass/bootstrap_popover.scss',
			'resources/assets/sass/vids_show.scss'], 'resources/assets/sass/joined.scss' );

mix.sass('resources/assets/sass/joined.scss', 'public/css/app.css');