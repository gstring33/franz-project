const { src, dest } = require('gulp');
const babel = require('gulp-babel');
const uglify = require('gulp-uglify');
const rename = require('gulp-rename');

exports.default = function() {
    return src('site/templates/dist/js/*.js')
        .pipe(babel())
        .pipe(dest('site/templates/public/js/'))
        .pipe(uglify())
        .pipe(rename({ extname: '.min.js' }))
        .pipe(dest('site/templates/public/js/'));
}