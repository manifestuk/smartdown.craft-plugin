var gulp = require('gulp')
  , bump = require('gulp-bump')
  , del = require('del')
  , git = require('gulp-git')
  , zip = require('gulp-zip');

/**
 * Bump the plugin config version.
 *
 * @param string type The bump type (major|minor|patch).
 */
function bumpVersion(type) {
  return gulp.src('./src/smartdown/config.json')
    .pipe(bump({type: type}))
    .pipe(gulp.dest('./src/smartdown/'));
}

// Clean up build directory.
gulp.task('clean-build', function () {
  return del(['build/**', '!build']);
});

// Copy "release" files to the build directory.
gulp.task('copy-to-build', ['clean-build'], function () {
  gulp.src(['CHANGELOG.md', 'LICENSE.txt', 'README.md']).pipe(gulp.dest('build'));
  return gulp.src('src/smartdown/**/*', {base: 'src'}).pipe(gulp.dest('build'));
});

// Build a zip file for distribution.
gulp.task('build-zip', ['copy-to-build'], function () {
  var config = require('./build/smartdown/config.json')
    , filename = 'smartdown-' + config.version + '.zip';

  return gulp.src('build/**/*')
    .pipe(zip(filename))
    .pipe(gulp.dest('dist'));
});

// Commits any outstanding changes, and tags the release.
gulp.task('tag-release', function() {
  var config = require('./build/smartdown/config.json')
    , version = config.version
    , message = 'Release ' + version;

  return gulp.src('./*')
    .pipe(git.add())
    .pipe(git.commit(message))
    .pipe(git.tag(version, message))
    .pipe(gulp.dest('./'));
});

// Pushes any commits and tags.
gulp.task('push-release', function () {
  return git.push('origin', 'master', '--tags');
});

// Bump the plugin "patch" version.
gulp.task('bump-patch', function () {
  return bumpVersion('patch');
});

// Bump the plugin "minor" version.
gulp.task('bump-minor', function () {
  return bumpVersion('minor');
});

// Bump the plugin "major" version.
gulp.task('bump-major', function () {
  return bumpVersion('major');
});

// Create a release.
gulp.task('release-patch', ['bump-patch', 'copy-to-build', 'build-zip', 'tag-release']);
