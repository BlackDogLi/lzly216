const {mix} = require('laravel-mix');

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

/**
 * Home
 */
//mix.js('resources/assets/home/js/app.js', 'public/js/app.js').sass('resources/assets/home/sass/style.css');

/**
 * Admin 后台
 */

mix.js('resources/assets/admin/js/app.js', 'public/admin/js/app.js')
   .sass('resources/assets/admin/sass/app.scss', 'public/admin/css/app.css');

if (mix.config.inProduction) {
	mix.version();
}
