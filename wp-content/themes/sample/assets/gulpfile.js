/**
* http://macr.ae/article/gulp-and-babel.html
* http://egorsmirnov.me/2015/05/25/browserify-babelify-and-es6.html
* http://jpsierens.com/tutorial-javascript-es6-babelv6/
* http://hazmi.id/building-with-gulp-1-compile-less-watch-changes-and-minify-css/
*
* Packgages:
* https://www.npmjs.com/package/pump
* https://www.npmjs.com/package/gulp-uglify
*/

var gulp = require('gulp')
var sourcemaps = require('gulp-sourcemaps')
var livereload = require('gulp-livereload')
var watch = require('gulp-watch')
var batch = require('gulp-batch')

// JavaScript development.
var browserify = require('browserify')
var babelify = require('babelify')
var source = require('vinyl-source-stream')
var buffer = require('vinyl-buffer')
var uglify = require('gulp-uglify')

// Less compilation.
var less = require('gulp-less')

// CSS compilation.
var concat = require('gulp-concat')
var cleanCSS = require('gulp-clean-css')
var concatCss = require('gulp-concat-css') // optional

// HTML compilation.
var htmlmin = require('gulp-htmlmin')
var path = require('path')
var foreach = require('gulp-foreach')

// Task to compile js.
// https://gist.github.com/alkrauss48/a3581391f120ec1c3e03
// http://blog.revathskumar.com/2016/02/browserify-with-gulp.html
gulp.task('compile-js', function () {
    // app.js is your main JS file with all your module inclusions
  return browserify({
    extensions: ['.js', '.jsx'],

    // To fix Babel 6 'regeneratorRuntime is not defined'.
    // https://babeljs.io/docs/usage/polyfill
    // http://esausilva.com/2017/07/11/uncaught-referenceerror-regeneratorruntime-is-not-defined-two-solutions/
    // https://stackoverflow.com/questions/33527653/babel-6-regeneratorruntime-is-not-defined
    entries:  ['./javascripts/app.js'],
    // entries:  ["babel-polyfill", './javascripts/app.js'],
    debug: true
  })
  .transform('babelify', {
    presets: ['es2015', 'es2017', 'react'],
    plugins: [

      // Turn async functions into ES2015 generators
      // https://babeljs.io/docs/plugins/transform-async-to-generator/
      "transform-async-to-generator"

      // https://gist.github.com/EduardoRFS/4c3daa7f7c42cc53d047cc782e43f98e
      // 'syntax-async-functions',
      // 'transform-regenerator'
    ]
  })
  .bundle()
  .pipe(source('bundle.min.js'))
  .pipe(buffer())
  .pipe(sourcemaps.init())
  .pipe(uglify())
  .pipe(sourcemaps.write('./maps'))
  .pipe(gulp.dest('dist'))
  .pipe(livereload())
})

// Task to compile less.
gulp.task('compile-less', function () {
  return gulp.src([
    'stylesheets/master.less'
  ])
  .pipe(sourcemaps.init())
  .pipe(less())
  .pipe(sourcemaps.write('./maps'))
  .pipe(gulp.dest('stylesheets/css'))
})

// Task to minify css.
gulp.task('minify-css', function () {
  return gulp.src([
    'stylesheets/css/master.css'
  ])
  .pipe(sourcemaps.init())
  .pipe(cleanCSS({debug: true}))
  .pipe(concat('bundle.min.css'))
  .pipe(sourcemaps.write('./maps'))
  .pipe(gulp.dest('dist'))
  .pipe(livereload())
})

// Task to minify html.
// https://www.npmjs.com/package/gulp-htmlmin
// https://github.com/kangax/html-minifier
// gulp.task('minify-html', function() {
//   return gulp.src('index.html')
//   .pipe(htmlmin({
//     collapseWhitespace: true,
//     removeComments: true
//   }))
//   .pipe(concat('index.min.html'))
//   .pipe(gulp.dest(''))
// })

// Loop each html.
// https://www.npmjs.com/package/gulp-foreach
gulp.task('minify-html', function () {
  return gulp.src('*.html')
    .pipe(foreach(function(stream, file){
      // Get the filename.
      // https://github.com/mariusGundersen/gulp-flatMap/issues/4
      // https://nodejs.org/api/path.html#path_path_basename_p_ext
      var name = path.basename(file.path)
      return stream
        .pipe(htmlmin({
          collapseWhitespace: true,
          removeComments: true
        }))
        .pipe(concat('min.' + name))
    }))
    .pipe(gulp.dest(''))
})

// Task to copy fonts to dist.
gulp.task('copy-fonts', function() {
  return gulp.src([
    'fonts/*.{eot,svg,ttf,woff,woff2}',
    'fonts/**/*.{eot,svg,ttf,woff,woff2}',
    'node_modules/material-design-icons/iconfont/MaterialIcons-Regular.*',
    'node_modules/foundation-icon-fonts/foundation-icons.*',
  ])
  .pipe(gulp.dest('dist/fonts/'))
})

// Task to copy images to dist.
gulp.task('copy-images', function() {
  return gulp.src([
    'images/*.{jpg,png,gif}',
    'images/**/*.{jpg,png,gif}',
    'node_modules/jquery-ui-bundle/images/*',
  ])
  .pipe(gulp.dest('dist/images/'))
})

// // Task to watch.
// // Notes:
// // Use this watch for js dev only.
// gulp.task('watch', function () {

//   // Watch all js files recursively.
//   watch([
//       'javascripts/**',
//       'javascripts/**/*.js'
//     ], batch(function (events, done) {
//       gulp.start('compile-js', done)
//   }))

//   // Watch all less files recursively.
//   // Some bug here as it only occurs once.
//   watch([
//       'stylesheets/**',
//       'stylesheets/**/*.less'
//     ], batch(function (events, done) {
//       gulp.start('compile-less', done)
//   }))

//   // Watch all css files recursively.
//   watch([
//       'stylesheets/**',
//       'stylesheets/**/*.css'
//     ], batch(function (events, done) {
//       gulp.start('minify-css', done)
//   }))

//   // Watch all image files recursively.
//   watch([
//       'images/**',
//       'images/**/*.{jpg,png,gif}'
//     ], batch(function (events, done) {
//       gulp.start('copy-images', done)
//   }))

//   // Watch all fonts files recursively.
//   watch([
//       'fonts/**',
//       'fonts/**/*.{eot,svg,ttf,woff,woff2}'
//     ], batch(function (events, done) {
//       gulp.start('copy-fonts', done)
//   }))
// })

// Task to watch.
// Notes:
// Use this watch for less/ css dev but watch out for gulp crashes after renaming or deleting folders/ files, see question below,
// https://stackoverflow.com/questions/50551062/gulp-crashes-after-renaming-or-deleting-folders
gulp.task('watch', function () {

  // Watch all js files recursively.
  gulp.watch([
    // 'javascripts/**/*.js', // does not work when new folders added.
    'javascripts/**',
  ],[
    'compile-js'
  ])

  // Watch all the .less files recursively.
  gulp.watch([
    'stylesheets/**/*.less'
  ], [
    'compile-less'
  ])

  // Watch all css files recursively.
  gulp.watch([
    'stylesheets/**/*.css'
  ], [
    'minify-css'
  ])

  // Watch all fonts files recursively.
  gulp.watch([
    // 'fonts/**/*.{eot,svg,ttf,woff,woff2}', // does not work for some reason when new folders added.
    'fonts/**',
  ], [
    'copy-fonts',
  ])

  // Watch all image files recursively.
  gulp.watch([
    // 'images/**/*.{jpg,png,gif}', // does not work for some reason when new folders added.
    'images/**'
  ], [
    'copy-images'
  ])
})

// Development:
// Task when running `gulp` from terminal.
gulp.task('default', ['watch'])

// Production:
// Task when running `gulp build` from terminal.
gulp.task('build', [
  'minify-css',
  'copy-fonts',
  'copy-images',
  'compile-js',
  'minify-html'
])
