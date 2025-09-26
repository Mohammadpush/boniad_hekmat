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

mix.js('resources/js/app.js', 'public/js')
   .css('resources/css/app.css', 'public/css')
   .options({
       processCssUrls: false
   })
   .browserSync({
       proxy: '127.0.0.1:8000',
       files: [
           'resources/views/**/*.blade.php',
           'resources/css/**/*.css',
           'resources/js/**/*.js',
           'public/assets/**/*',
           'app/**/*.php',
           'routes/**/*.php'
       ],
       watchOptions: {
           usePolling: true,
           interval: 1000,
           ignored: /node_modules/
       },
       open: false,
       notify: true,
       ghostMode: {
           clicks: true,
           forms: true,
           scroll: true
       }
   });

// Enable versioning in production
if (mix.inProduction()) {
    mix.version();
}
