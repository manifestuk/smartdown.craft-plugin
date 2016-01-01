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
  gulp.src(['CHANGELOG.md', 'LICENSE.txt', 'README.md']).pipe(gulp.dest('tmp'));
  return gulp.src('src/smartdown/**/*', {base: 'src'}).pipe(gulp.dest('tmp'));
});

// Build a zip file for distribution.
gulp.task('build', ['copy-to-tmp'], function () {
  var config = require('./src/smartdown/config.json')
    , filename = 'smartdown-' + config.version + '.zip';

  return gulp.src('tmp/**/*')
    .pipe(zip(filename))
    .pipe(gulp.dest('releases'));
});

gulp.task('tag', ['build'], function () {
  var config = require('./src/smartdown/config.json')
    , version = config.version
    , message = 'Release ' + version;

  return git.tag(version, message);
});

// Pushes any commits and tags.
gulp.task('release', ['tag'], function () {
  return git.push('origin', 'master', '--tags');
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

// Create a release.
// Won't work, because annoying async.
//gulp.task('release-patch', ['bump-patch', 'tag-release']);
