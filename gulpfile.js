const { src, dest, parallel, series, task, watch } = require('gulp');
const uglify = require('gulp-uglify');
const rename = require('gulp-rename');
const postcss = require('gulp-postcss');
const del = require('del');
const cssnano = require('cssnano');
const autoprefixer = require('autoprefixer');
const concat = require('gulp-concat')

const paths = {
    src: 'site/templates/dist/',
    dest: 'site/templates/public/',
    node_modules: 'node_modules/'
}

task('clean:public', function() {
    return del([
        paths.dest + 'js/*',
        paths.dest + 'css/*'
    ])
})

task('build:js:vendor', function(cb) {
    let vendors = [
        paths.node_modules + 'jquery/dist/jquery.min.js',
        paths.node_modules + 'jquery-migrate/dist/jquery-migrate.min.js',
        paths.node_modules + '@popperjs/core/dist/umd/popper.min.js',
        paths.node_modules + 'bootstrap/dist/js/bootstrap.min.js'
    ];
    return src(vendors)
        .pipe(concat('vendors.min.js'))
        .pipe(dest(paths.dest + 'js'))
})

task('build:css:vendor', function(cb) {
    let vendors = [
        paths.node_modules + 'bootstrap/dist/css/bootstrap.min.css'
    ]

    return src(vendors)
        .pipe(concat('vendors.min.css'))
        .pipe(dest(paths.dest + 'css'))
})

// ----- BUILD FOR DEV ----- //

task('build:js', function(cb) {
    return src(paths.src + 'js/**/*.js')
        .pipe(rename({ extname: '.js' }))
        .pipe(dest(paths.dest + 'js'));
})

task('build:css', function(cb) {
    return src(paths.src + 'css/*.css')
        .pipe(dest(paths.dest + 'css/'))
})

task('watch', function () {
    watch(paths.src + 'js/*.js', series('build:js'))
    watch(paths.src + 'css/*.css', series('build:css'))
})

exports.default = series(
    'clean:public',
    parallel(
        'build:js:vendor',
        'build:css:vendor',
        'build:js',
        'build:css'
    )
)

// ----- BUILD FOR PROD ----- //

task('build:js:prod', function(cb) {
    return src(paths.src + 'js/**/*.js')
        .pipe(uglify())
        .pipe(rename({ extname: '.min.js' }))
        .pipe(dest(paths.dest + 'js/'));
})

task('build:css:prod', function(cb) {
    const plugins = [
        autoprefixer(),
        cssnano()
    ]

    return src(paths.src + 'css/*.css')
        .pipe(postcss(plugins))
        .pipe(rename({ extname: '.min.css' }))
        .pipe(dest(paths.dest + 'css/'));
})

exports.prod = series(
    'clean:public',
    parallel(
        'build:js:vendor',
        'build:css:vendor',
        'build:js:prod',
        'build:css:prod'
    )
);