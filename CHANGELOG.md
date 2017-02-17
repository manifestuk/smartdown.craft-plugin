# Change Log #
All notable changes to this project will be documented in this file. This
project adheres to [Semantic Versioning](http://semver.org/).

## [2.1.0] - 2017-02-17 ##
### Added ###
- Add Travis CI configuration.
- Add documentation URL.
- Add schema version.
- Add releases feed.

### Changed ###
- Reorganise plugin according to current best practices.
- Use present tense in CHANGELOG.

### Fixed ###
- Update parser to accept objects with a `__toString` method.

## [2.0.1] - 2016-01-02 ##
### Fixed ###
- Fix copying of README in build process.

## [2.0.0] - 2016-01-01 ##
### Added ###
- Add change log.
- Add unit tests for all "utility" classes.
- Implement rudimentary build process.

### Changed ###
- Rename the plugin from SmartDown to Smartdown.
- Rename `SmartDownService` to `SmartdownService` (breaking change).
- Remove previously-deprecated `markdown` and `smartypants` Twig filter options.
- Update `michelf/php-markdown` dependency to version 1.6.
- Update `michelf/php-smartypants` dependency to version 1.6 (beta).

### Fixed ###
- Fix [issue 2][issue-2] by updating SmartyPants dependency.

[issue-2]: https://github.com/monooso/smartdown.craft-plugin/issues/2

## [1.0.0] - 2015-07-30 ##
### Added ###
- Add `SmartDownService`, accessible via `craft()->smartDown`, so third-parties can share the love.
- Add `modifySmartdownMarkupInput` hook.
- Add `modifySmartdownMarkupOutput` hook.
- Add `modifySmartdownTypographyInput` hook.
- Add `modifySmartdownTypographyOutput` hook.

### Changed ###
- Deprecate `markdown` Twig filter option; use `markup` instead.
- Deprecate `smartypants` Twig filter option; use `typography` instead.

## 0.1.0 - 2013-08-21 ##
Initial release.

### Added ###
- Add `smartdown` Twig filter, which runs a string through "Multi-Markdown" and "SmartyPants" parsers.

[Unreleased]: https://github.com/monooso/smartdown.craft-plugin/compare/2.1.0...HEAD
[2.1.0]: https://github.com/monooso/smartdown.craft-plugin/compare/2.1.0...2.0.1
[2.0.1]: https://github.com/monooso/smartdown.craft-plugin/compare/2.0.0...2.0.1
[2.0.0]: https://github.com/monooso/smartdown.craft-plugin/compare/1.0.0...2.0.0
[1.0.0]: https://github.com/monooso/smartdown.craft-plugin/compare/0.1.0...1.0.0
