var gulp = require('gulp');
var sass = require('gulp-sass')(require('sass'));
var minifyCSS = require('gulp-csso');
var sourcemaps = require('gulp-sourcemaps');
var watch = require('gulp-watch');
var uglify = require('gulp-uglify');
var autoprefixer = require('gulp-autoprefixer');
var rename = require("gulp-rename");

function errorHandler (error) {
  console.log(error.toString())
  this.emit('end')
}



function css(){

 // loop through all the SASS files to build
 // builds 2 times, first standard files, then minified version
  return gulp.src('assets/sass/*.scss')
      .pipe(sass()).on('error', errorHandler)
      .pipe(autoprefixer({
            cascade: false
        }))
      .pipe(gulp.dest('./css'))
      .pipe(minifyCSS()).on('error', errorHandler)
      .pipe(rename({ extname: '.min.css' }))
      .pipe(gulp.dest('./css'))
  ;
};

function js(){
  // destination ./ puts into ./js directory.
  // it writes the file 3 times. normal, minified, minified with sourcemap
  return gulp.src('assets/**/*.js')
    .pipe(gulp.dest('./'))
    .pipe(uglify()).on('error', errorHandler)
      .pipe(rename({ extname: '.min.js' }))
    .pipe(gulp.dest('./'))
    .pipe(sourcemaps.init())
      .pipe(uglify()).on('error', errorHandler)
      .pipe(rename({ extname: '.sourcemap.js' }))
    .pipe(sourcemaps.write())
    .pipe(gulp.dest('./'))
    ;
};


gulp.task('default', gulp.series(css,js));

gulp.task('watch', function(){
  gulp.watch('assets/**/*.scss', gulp.series(css));
  gulp.watch('assets/**/*.js', gulp.series(js));
});

