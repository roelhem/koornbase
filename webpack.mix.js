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
        rules: [
                {
                    test: /\.ya?ml$/,
                    use: [
                        {
                            loader:path.resolve(__dirname, 'yaml-loader.js')
                        }
                    ]
                },
                {
                    test: /\.(graphql|gql)$/,
                    exclude: /node_modules/,
                    loader: 'graphql-tag/loader',
                }
            ]
    }
});

// The dashboard-application
mix.js('resources/assets/dashboard/js/main.js', 'public/js/dashboard.js')
   .sass('resources/assets/dashboard/sass/main.scss', 'public/css/dashboard.css');

// The home page
mix.js('resources/assets/homepage/js/main.js', 'public/js/home.js')
    .sass('resources/assets/homepage/sass/main.scss','public/css/home.css');

mix.copyDirectory('resources/assets/shared/images/koornbase', 'public/images/koornbase');

mix.browserSync('https://koornbase.test/');