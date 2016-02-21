'use strict';

var path = {
    lothus: '../',
    build: '../app/webroot',
    src: 'admin-src',
}

var env          = require('minimist')(process.argv.slice(2))
  , gulp         = require('gulp')
  , stylus       = require('gulp-stylus')
  , plumber      = require('gulp-plumber')
  , browserify   = require('gulp-browserify')
  , browserSync  = require('browser-sync')
  , uglify       = require('gulp-uglify')
  , imagemin     = require('gulp-imagemin')
  , concat       = require('gulp-concat')
  , gulpif       = require('gulp-if')
  , jeet         = require('jeet')
  , bootstrap    = require('bootstrap-styl')
  , autoprefixer = require('autoprefixer-stylus')
  , koutoSwiss   = require('kouto-swiss')
  , rupture      = require('rupture')
  , livereload   = require('gulp-livereload')
  , rsync        = require('rsyncwrapper').rsync;


gulp.task('stylus', function() {
    gulp.src('css/main.styl')
    .pipe(plumber())
    .pipe(stylus({
        use:[jeet(), autoprefixer(), koutoSwiss(), rupture(), bootstrap()],
        compress: env.p
    }))
    .pipe(gulp.dest(path.build+'/css/adm'))
    .pipe(livereload());
});


gulp.task('jhint', function () {
    return gulp.src([path.src+'/js/*.js'])
    .pipe(jshint('.jshintrc'))
    .pipe(jshint.reporter('jshint-stylish'));
});


gulp.task('js', function() {
    return gulp.src(path.src+'/js/*.js')
    .pipe(plumber())
    .pipe(concat('admin.js'))
    .pipe(gulpif(env.p, uglify()))
    .pipe(gulp.dest(path.build+'/js'))
});


gulp.task('browserify', function(){
    return gulp.src(path.src+'/js/main.js')
        .pipe(plumber())
        .pipe(browserify({debug: !env.p }))
        .pipe(gulpif(env.p, uglify()))
        .pipe(gulp.dest(path.build+'/js'));
});


gulp.task('imagemin', function() {
    return gulp.src(path.src+'/img/**/*')
    .pipe(plumber())
    .pipe(imagemin({
        optimizationLevel: 3, progressive: true, interlaced: true
    }))
    .pipe(gulp.dest(path.build+'/img'));
});


gulp.task('watch', function() {
    livereload.listen();
    gulp.watch('css/*.styl', ['stylus']);
    gulp.watch('js/*.js', [ (env.fy) ? 'browserify' : 'js']);
    gulp.watch(path.src+'/img/**/*.{jpg, png, gif}', ['imagemin']);
});


gulp.task('browser-sync', function () {
   var files = [
      path.lothus+'/app/**/*.phtml',
      path.lothus+'/app/**/*.php',
      path.build+'/css/*.css',
      path.build+'/img/**/*',
      path.build+'/js/*.js'
   ];

   browserSync.init(files, {
      server: false,
      open: false
   });
});

gulp.task('deploy', function(){
    rsync({
        ssh: true,
        src: './'+path.build+'/',
        dest: 'user@hostname:/path/to/www',
        recursive: true,
        syncDest: true,
        args: ['--verbose']
    },
        function (erro, stdout, stderr, cmd) {
            gutil.log(stdout);
    });
});




// For development => gulp
// For production  => gulp --p
// For use browserify  => gulp --fy

// Task
gulp.task('default', ['js','stylus', 'imagemin', 'watch', 'browser-sync']);

// Build and Deploy
gulp.task('build', [(env.fy) ? 'browserify' : 'js', 'stylus', 'imagemin', 'deploy']);
