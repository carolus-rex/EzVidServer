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

mix.js('app/Http/resources/assets/js/app.js', 'public/js');

mix.styles(['app/Http/resources/assets/sass/app.scss',
			'app/Http/resources/assets/sass/custom.scss',
			'components/Vids/resources/assets/sass/bootstrap_popover.scss',
			'components/Vids/resources/assets/sass/vids_show.scss'], 'app/Http/resources/assets/sass/joined.scss' );

mix.sass('app/Http/resources/assets/sass/joined.scss', 'public/css/app.css');
