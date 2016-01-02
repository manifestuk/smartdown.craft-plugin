var gulp = require('gulp')
  , bump = require('gulp-bump')
  , del = require('del')
  , zip = require('gulp-zip');

/**
 * Bump the plugin config version.
 *
 * @param string type The bump type (major|minor|patch).
 */
var bumpVersion = function(type) {
  return gulp.src('./src/smartdown/config.json')
    .pipe(bump({type: type}))
    .pipe(gulp.dest('./src/smartdown/'));
}

// Clean up tmp directory.
gulp.task('clean-tmp', function () {
  return del(['tmp/**']);
});

// Copy "release" files to the tmp directory.
gulp.task('copy-to-tmp', ['clean-tmp'], function () {
  gulp.src(['CHANGELOG.md', 'LICENSE.txt']).pipe(gulp.dest('tmp'));

  return gulp.src(['src/README.md', 'src/smartdown/**/*'], {base: 'src'})
    .pipe(gulp.dest('tmp'));
});

// Build a zip file for distribution.
gulp.task('build', ['copy-to-tmp'], function () {
  var config = require('./src/smartdown/config.json')
    , filename = 'smartdown-' + config.version + '.zip';

  return gulp.src('tmp/**/*')
    .pipe(zip(filename))
    .pipe(gulp.dest('releases'));
});

// Bump the plugin "major" version.
gulp.task('bump-major', function () {
  return bumpVersion('major');
});

// Bump the plugin "minor" version.
gulp.task('bump-minor', function () {
  return bumpVersion('minor');
});

// Bump the plugin "patch" version.
gulp.task('bump-patch', function () {
  return bumpVersion('patch');
});
