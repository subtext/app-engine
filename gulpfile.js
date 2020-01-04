const {src, dest} = require('gulp');
const concat = require('gulp-concat');
const babel = require('gulp-babel');
const uglify = require('gulp-uglify');

function defaultTask() {
    return src('src/js/**/*.js')
        .pipe(concat('index.min.js'))
        .pipe(babel({presets: ['@babel/preset-env']}))
        .pipe(uglify())
        .pipe(dest('public/js/'));
}

exports.default = defaultTask;
