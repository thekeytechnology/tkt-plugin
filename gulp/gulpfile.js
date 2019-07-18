let gulp = require('gulp');
let less = require('gulp-less');
let concat = require('gulp-concat');
let plumber = require('gulp-plumber');

const themeFolder = "../../../themes/tkt/";

const assetsFolder = themeFolder + "assets/";

const lessInput = assetsFolder + "less/tk.less";
const lessWPAdminInput = assetsFolder + "less/tk-wpadmin.less";
const cssOutput = themeFolder + "css/";

let jsFolder = assetsFolder + "js/";
const jsInput = require(jsFolder + "input.json").map(function (item) {
    return jsFolder + item
});
const jsOutput = themeFolder + "/js/";

function css() {
    return gulp
        .src(lessInput)
        .pipe(plumber())
        .pipe(less())
        .pipe(gulp.dest(cssOutput));
}

function cssWpAdmin() {
    return gulp
        .src(lessWPAdminInput, {allowEmpty: true})
        .pipe(plumber())
        .pipe(less())
        .pipe(gulp.dest(cssOutput));
}

function js() {
    return gulp
        .src(jsInput)
        .pipe(concat("tk.js"))
        .pipe(gulp.dest(jsOutput));
}


function watchFiles() {
    css();
    cssWpAdmin();
    js();
    gulp.watch(assetsFolder + "**/*.less", css, cssWpAdmin);
    gulp.watch(assetsFolder + "**/*.js", js);
}


const build = gulp.series(gulp.parallel(js, css, cssWpAdmin));
const watch = gulp.series(build, gulp.parallel(watchFiles));

exports.default = build;
exports.watch = watch;
