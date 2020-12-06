const mix = require('laravel-mix');

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

mix.scripts([
    'resources/js/pilot-tools.js',
    'resources/js/policies.js',
    'resources/js/maps.js',
    'resources/js/myczqo.js',
    'resources/js/preferences.js',
    'resources/js/custom-pages.js',
    'resources/js/instructing.js',
    'resources/js/misc.js'
], 'public/js/czqo.js');

