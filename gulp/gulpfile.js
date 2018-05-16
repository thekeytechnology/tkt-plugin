let gulp = require('gulp');
let less = require('gulp-less');
let concat = require('gulp-concat');
let watch = require('gulp-watch');
let plumber = require('gulp-plumber');
let gutil = require('gulp-util');

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

gulp.task("less", function () {
    return gulp.src(lessInput)
        .pipe(plumber(function (error) {
            gutil.log(error.message);
            this.emit('end');
        }))
        .pipe(less())
        .pipe(gulp.dest(cssOutput))
});

gulp.task("less-wpadmin", function () {
    return gulp.src(lessWPAdminInput)
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
    gulp.watch(assetsFolder + "**/*.*", ['less', 'less-wpadmin', 'js']);
});

gulp.task('default', ['less', 'less-wpadmin', 'js']);

gulp.task('watch', ['default', 'start-watching']);
