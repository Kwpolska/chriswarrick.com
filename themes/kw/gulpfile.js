const { src, dest } = require('gulp');
const minifyCSS = require('gulp-csso');
const sass = require('gulp-sass');
const postcss = require('gulp-postcss');
//const sourcemaps = require('gulp-sourcemaps');
const autoprefixer = require('autoprefixer');
const rename = require("gulp-rename");
sass.compiler = require('node-sass');


function css() {
    return src('*.scss')
        .pipe(sass.sync().on('error', sass.logError))
        .pipe(postcss([autoprefixer()]))
        .pipe(rename(function (path) {
            path.basename = path.basename.replace("-kw", "")
        }))
        .pipe(dest('assets/css'))
        .pipe(minifyCSS())
        .pipe(rename(function (path) {
            path.basename += ".min"
        }))
        .pipe(dest('assets/css'));
}

exports.default = css;
