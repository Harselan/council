let mix = require('laravel-mix');
let tailwindcss = require('tailwindcss');

mix.js('resources/js/app.js', 'js');

mix.sass('resources/sass/app.scss', 'public/css')
.options({
    processCssUrls: false,
    postCss: [ tailwindcss('./tailwind.js') ],
})
.browserSync('council.test')