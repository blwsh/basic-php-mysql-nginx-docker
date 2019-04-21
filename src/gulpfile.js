const gulp         =  require('gulp');
const browserSync  =  require('browser-sync').create();
const sass         =  require('gulp-sass');
const rename       =  require("gulp-rename");
const sourceMaps   =  require('gulp-sourcemaps');
const autoPrefix   =  require('gulp-autoprefixer');

// Static Server + watching scss/html files
gulp.task('serve', ['sass'], () => {
    browserSync.init();
    gulp.watch("resources/scss/**/*.scss", ['sass']);
    gulp.watch("resources/js/*.js").on('change', browserSync.reload);
});

// Compile sass into CSS & auto-inject into browsers
gulp.task('sass', () => {
    return gulp.src("resources/scss/*.scss")
        .pipe(sourceMaps.init())
        .pipe(sass({outputStyle: 'compressed'}).on('error', sass.logError))
        .pipe(autoPrefix({ browsers: ['last 2 versions'], cascadee: false }))
        .pipe(sourceMaps.write('.'))
        .pipe(gulp.dest("public/assets/css"))
        .pipe(browserSync.stream());
});

gulp.task('default', ['serve']);
