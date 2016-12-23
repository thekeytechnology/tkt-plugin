var gulp = require('gulp');
var less = require('gulp-less');
var concat = require('gulp-concat');
var watch = require('gulp-watch');
var plumber = require('gulp-plumber');
var gutil = require('gulp-util');

const themeFolder = "../../../themes/betheme_child/";
const assetsFolder = themeFolder + "assets/";

const lessInput = assetsFolder + "less/tk.less";
const cssOutput = themeFolder + "css/";

var jsFolder = assetsFolder + "js/";
const jsInput = require(jsFolder + "input.json").map(function (item) {
    return jsFolder + item
});
const jsOutput = themeFolder + "/js/";

gulp.task("less", function () {
    return gulp.src(lessInput)
        .pipe(plumber(function (error) {
            gutil.log(error.message);
            this.emit('end');
        }))
        .pipe(less())
        .pipe(gulp.dest(cssOutput))
});

gulp.task("js", function () {
    return gulp.src(jsInput)
        .pipe(plumber(function (error) {
            gutil.log(error.message);
            this.emit('end');
        }))
        .pipe(concat("tk.js"))
        .pipe(gulp.dest(jsOutput))
});

gulp.task('start-watching', function () {
    gulp.watch(assetsFolder + "**/*.*", ['less', 'js']);
});

gulp.task('default', ['less', 'js']);

gulp.task('watch', ['default', 'start-watching']);