let mix = require('laravel-mix');

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

mix.webpackConfig({
    module: {
        rules: [{
            test: /\.ya?ml$/,
            use: [
                {
                    loader:path.resolve(__dirname, 'yaml-loader.js')
                }
            ]
        }]
    }
});

// The application
mix.js('resources/assets/js/app.js', 'public/js')
   .sass('resources/assets/sass/app.scss', 'public/css');

// The home page
mix.js('resources/assets/js/home.js', 'public/js')
    .sass('resources/assets/sass/home.scss','public/css');

mix.copyDirectory('resources/assets/images/koornbase', 'public/images/koornbase');

mix.browserSync('https://homestead.test/');