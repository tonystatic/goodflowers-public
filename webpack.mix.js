const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 */

const paths = {
    node : './node_modules/',
    vendor: './resources/assets/vendor/',
    front : {
        src  : './resources/assets/front/',
        dest : './public/assets/front/'
    }
};

//--------------------------------------------------------------------------
// Front Assets

const postCssProcessors = [
    require('precss'),
    require('cssnano') ({
        convertValues: {
            length: false
        }
    }),
    require('postcss-discard-comments') ({
        removeAll: true
    })
];

mix.sass(paths.front.src + 'scss/app.scss', paths.front.dest + 'css')
    .options({
        postCss: postCssProcessors,
        processCssUrls: false,
    })
    .sourceMaps();

mix.scripts(
    [
        paths.node      + 'jquery/dist/jquery.js',
        paths.node      + 'bootstrap/dist/js/bootstrap.bundle.min.js',
        paths.node      + 'feather-icons/dist/feather.js',
        paths.node      + 'inputmask/dist/jquery.inputmask.js',
        paths.node      + 'svg-pan-zoom/dist/svg-pan-zoom.js',
        paths.node      + 'hammerjs/hammer.js',
        paths.node      + 'moment/min/moment.min.js',
        paths.node      + 'moment/locale/ru.js',
        paths.node      + 'js-cookie/src/js.cookie.js',
        paths.vendor    + 'js/copy2clipboard.js',
        paths.front.src + 'js/app.js',
    ],
    paths.front.dest + 'js/app.js'
);

mix.scripts(
    [
        paths.front.src + 'js/garden.js',
    ],
    paths.front.dest + 'js/garden.js'
);

mix.scripts(
    [
        paths.front.src + 'js/donate.js',
    ],
    paths.front.dest + 'js/donate.js'
);

if (mix.inProduction()) {
    mix.version();
}
