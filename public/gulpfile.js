'use strict';

var config = require('./config.json');
var browserSync = require('browser-sync').create();
var gulp = require('gulp');
var glob = require('gulp-sass-glob');
var notify = require('gulp-notify');
var sourcemaps = require('gulp-sourcemaps');
var plumber = require('gulp-plumber');
var sass = require('gulp-sass');

// CSS.
gulp.task('css', function () {
  return gulp.src(config.css.src)
    .pipe(glob())
    .pipe(plumber({
      errorHandler: function (error) {
        notify.onError({
          title: "Gulp",
          subtitle: "Failure!",
          message: "Error: <%= error.message %>",
          sound: "Beep"
        })(error);
        this.emit('end');
      }
    }))
    .pipe(sourcemaps.init())
    .pipe(sass({
      style: 'compressed',
      errLogToConsole: true,
      includePaths: config.css.includePaths
    }))
    .pipe(sourcemaps.write())
    .pipe(gulp.dest(config.css.dest))
    .pipe(browserSync.reload({stream: true, match: '**/*.css'}))
});

// Watch task.
gulp.task('watch', function () {
  gulp.watch(config.css.src, gulp.series(['css']));
});