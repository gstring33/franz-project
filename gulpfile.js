const { src, dest, parallel, series, task, watch } = require('gulp');
const uglify = require('gulp-uglify');
const rename = require('gulp-rename');
const del = require('del');
const autoprefixer = require('gulp-autoprefixer');
const concat = require('gulp-concat');
const sass = require('gulp-sass');
const cleanCSS = require('gulp-clean-css');

// ----- CONFIG ----- //

const paths = {
    src: 'site/templates/dist/',
    dest: 'site/templates/public/',
    node_modules: 'node_modules/',
}

const cssVendorsPaths = [
    paths.src + 'vendors/fancybox/jquery.fancybox.css',
    paths.src + 'vendors/icon-etlinefont/style.css"',
    paths.node_modules + 'fontawesome-4.7/css/font-awesome.css'
]

const autoprefixerConf = [
    "last 1 major version",
    ">= 1%",
    "Chrome >= 45",
    "Firefox >= 38",
    "Edge >= 12",
    "Explorer >= 10",
    "iOS >= 9",
    "Safari >= 9",
    "Android >= 4.4",
    "Opera >= 30"
]

// ----- TASKS: COMMON ----- //

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
    return src(cssVendorsPaths)
        .pipe(cleanCSS({compatibility: 'ie8'}))
        .pipe(rename({ extname: '.min.css' }))
        .pipe(dest(paths.dest + 'css/'));
})

task('build:scss:vendor', function (cb) {
    return src(paths.src + 'scss/vendors.scss')
        .pipe(sass({ outputStyle: 'compressed' }))
        .on('error', sass.logError)
        .pipe(autoprefixer(autoprefixerConf, { cascade: true }))
        .pipe(rename({ extname: '.min.css' }))
        .pipe(dest(paths.dest + 'css/'));
})

// ----- TASKS: DEV ----- //

task('build:js', function(cb) {
    return src(paths.src + 'js/**/*.js')
        .pipe(rename({ extname: '.js' }))
        .pipe(dest(paths.dest + 'js'));
})

task('build:scss', function(cb) {
    return src([paths.src + 'scss/**/*.scss', '!' + paths.src + 'scss/vendors.scss'])
        .pipe(sass({ outputStyle: 'expanded' }))
        .pipe(sass().on('error', sass.logError))
        .pipe(autoprefixer(autoprefixerConf))
        .pipe(dest(paths.dest + 'css'))
})

task('watch', function () {
    watch(paths.src + 'js/*.js', series('build:js'))
    watch(paths.src + 'scss/*.scss', series('build:scss'))
})

exports.default = series(
    'clean:public',
    parallel(
        'build:js:vendor',
        'build:js',
        'build:scss:vendor',
        'build:css:vendor',
        'build:scss'
    )
)

// ----- TASKS: PROD ----- //

task('build:js:prod', function(cb) {
    return src(paths.src + 'js/**/*.js')
        .pipe(uglify())
        .pipe(rename({ extname: '.min.js' }))
        .pipe(dest(paths.dest + 'js/'));
})

task('build:scss:prod', function(cb) {
    return src(paths.src + 'scss/**/*.scss')
        .pipe(sass({ outputStyle: 'compressed' }))
        .on('error', sass.logError)
        .pipe(autoprefixer(autoprefixerConf, { cascade: true }))
        .pipe(rename({ extname: '.min.css' }))
        .pipe(dest(paths.dest + 'css/'));
})

exports.prod = series(
    'clean:public',
    parallel(
        'build:js:vendor',
        'build:js:prod',
        'build:scss:vendor',
        'build:css:vendor',
        'build:scss:prod'
    )
);