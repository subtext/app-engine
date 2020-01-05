const {src, dest, series, parallel} = require('gulp');
const concat = require('gulp-concat');
const babel = require('gulp-babel');
const uglify = require('gulp-uglify');
const sass = require('gulp-sass');

function buildJavaScript() {
    return src('src/js/**/*.js')
        .pipe(concat('index.min.js'))
        .pipe(babel({presets: ['@babel/preset-env']}))
        .pipe(uglify())
        .pipe(src([
            'node_modules/bootstrap/dist/js/bootstrap.min.js',
            'node_modules/jquery/dist/jquery.min.js',
            'node_modules/popper.js/dist/popper.min.js'
        ]))
        .pipe(dest('public/js/'));
}

function buildStyle() {
    return src('src/scss/**/*.scss')
        .pipe(sass())
        .pipe(dest('public/css/'));
}

exports.default = parallel(buildStyle, buildJavaScript);
exports.style = buildStyle;

