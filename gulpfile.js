const { src, dest, parallel, series, task, watch } = require('gulp');
const babel = require('gulp-babel');
const uglify = require('gulp-uglify');
const rename = require('gulp-rename');
const postcss = require('gulp-postcss');
const del = require('del');
const cssnano = require('cssnano');
const autoprefixer = require('autoprefixer');

const paths = {
    src: 'site/templates/dist/',
    dest: 'site/templates/public/'
}

task('clean:public', function() {
    return del([
        paths.dest + 'js/*',
        paths.dest + 'css/*'
    ])
})

// ----- BUILD FOR DEV ----- //

task('build:js', function(cb) {
    return src(paths.src + 'js/*.js')
        .pipe(babel())
        .pipe(dest(paths.dest + 'js/'))
})

task('build:css', function(cb) {
    return src(paths.src + 'css/*.css')
        .pipe(dest(paths.dest + 'css/'))
})

task('watch', function () {
    watch(paths.src + 'js/*.js', series('build:js'))
    watch(paths.src + 'css/*.css', series('build:css'))
})

exports.default = series('clean:public', parallel('build:js', 'build:css'))

// ----- BUILD FOR PROD ----- //

task('build:js:prod', function(cb) {
    return src(paths.src + 'js/*.js')
        .pipe(babel())
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

exports.prod = series(parallel('clean:public', 'build:js:prod', 'build:css:prod'));