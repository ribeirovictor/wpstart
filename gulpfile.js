const gulp = require('gulp');
const sass = require('gulp-sass');
const autoprefixer = require('gulp-autoprefixer');
const browserSync = require('browser-sync').create();
const concat = require('gulp-concat');
const babel = require('gulp-babel');
const uglify = require('gulp-uglify');

let proxy = 'http://cannabispromotions:8888/'

// Funçao para compilar o SASS e adicionar os prefixos
function compilaSass() {
  return gulp
  .src('assets/css/**/**/**/*.sass')
  .pipe(sass({
    outputStyle: 'compressed'
  }))
  .pipe(autoprefixer({
    browsers: ['last 2 versions'],
    cascade: false
  }))
  .pipe(gulp.dest('./'))
  .pipe(browserSync.stream());
}

// Tarefa de gulp para a função de SASS
gulp.task('sass', function(done){
  compilaSass();
  done();
});

// Função para juntar o JS
function gulpJS() {
  return gulp
  .src('assets/js/main/*.js')
  .pipe(concat('main.js'))
  .pipe(babel({
    presets: ['env']
  }))
  .pipe(uglify())
  .pipe(gulp.dest('assets/js/'))
  .pipe(browserSync.stream())
}

gulp.task('mainjs', gulpJS);

// JS Plugins
function pluginJS() {
  return gulp
  .src([
    'assets/js/plugins/*.js'
  ])
  .pipe(concat('plugins.js'))
  .pipe(uglify())
  .pipe(gulp.dest('assets/js/'))
  .pipe(browserSync.stream())
}

gulp.task('pluginjs', pluginJS);

// Concact all JS
// function concatJS() {
//   return gulp
//   .src([
//     'assets/js/libs/jquery-3.3.1.js',
//     'assets/js/libs/subjx.js',
//     'assets/js/plugins.js',
//     'assets/js/main.js',
//   ])
//   .pipe(concat('index.js'))
//   .pipe(gulp.dest('./'))
//   .pipe(browserSync.stream())
// }

// gulp.task('concatjs', concatJS);

// Função para iniciar o browser
function browser() {
  browserSync.init({
    proxy: proxy
  });
}

// Tarefa para iniciar o browser-sync
gulp.task('browser-sync', browser);

// Função de watch do Gulp
function watch() {
  gulp.watch('assets/css/**/**/*.sass', compilaSass);
  gulp.watch('assets/js/main/*.js', gulpJS);
  gulp.watch('assets/js/plugins/*.js', pluginJS);
  // gulp.watch('assets/js/*.js', concatJS);
  gulp.watch(['*.php', './**/**/**/*.php']).on('change', browserSync.reload);
}

// Inicia a tarefa de watch
gulp.task('watch', watch);

// Tarefa padrão do Gulp, que inicia o watch e o browser-sync
gulp.task('default', gulp.parallel('watch', 'browser-sync', 'sass', 'mainjs', 'pluginjs'));
