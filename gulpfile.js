const { src, dest, parallel, task } = require('gulp');
const babel = require('gulp-babel');
const uglify = require('gulp-uglify');
const rename = require('gulp-rename');
const autoprefixer = require('gulp-autoprefixer')
cleanCSS = require('gulp-clean-css')
purgecss = require('gulp-purgecss')

const paths = {
    src: 'site/templates/dist/',
    dest: 'site/templates/public/'
}

task('build-js', function(cb) {
    return src(paths.src + 'js/*.js')
        .pipe(babel())
        .pipe(dest(paths.dest + 'js/'))
        .pipe(uglify())
        .pipe(rename({ extname: '.min.js' }))
        .pipe(dest(paths.dest + 'js/'));
})

task('build-css', function(cb) {
    return src(paths.src + 'css/*.css')
        .pipe(autoprefixer())
        .pipe(cleanCSS({compatibility: 'ie8'}))
        .pipe(rename( { extname: '.min.css'}))
        .pipe(dest(paths.dest + 'css/'));
})

exports.default = parallel('build-js', 'build-css')