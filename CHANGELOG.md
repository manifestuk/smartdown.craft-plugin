# Change Log #
All notable changes to this project will be documented in this file. This
project adheres to [Semantic Versioning](http://semver.org/).

## [2.0.0] - 2016-01-01 ##
### Added ###
- Added change log.
- Added unit tests for all "utility" classes.
- Implemented rudimentary build process.

### Changed ###
- Renamed the plugin from SmartDown to Smartdown.
- Renamed `SmartDownService` to `SmartdownService` (breaking change).
- Removed previously-deprecated `markdown` and `smartypants` Twig filter options.
- Updated `michelf/php-markdown` dependency to version 1.6.
- Updated `michelf/php-smartypants` dependency to version 1.6 (beta).

### Fixed ###
- Fixed [issue 2][issue-2] by updating SmartyPants dependency.

[issue-2]: https://github.com/monooso/smartdown.craft-plugin/issues/2

## [1.0.0] - 2015-07-30 ##
### Added ###
- Added `SmartDownService`, accessible via `craft()->smartDown`, so third-parties can share the love.
- Added `modifySmartdownMarkupInput` hook.
- Added `modifySmartdownMarkupOutput` hook.
- Added `modifySmartdownTypographyInput` hook.
- Added `modifySmartdownTypographyOutput` hook.

### Changed ###
- Deprecated `markdown` Twig filter option; use `markup` instead.
- Deprecated `smartypants` Twig filter option; use `typography` instead.

## 0.1.0 - 2013-08-21 ##
Initial release.

### Added ###
- Added `smartdown` Twig filter, which runs a string through "Multi-Markdown" and "SmartyPants" parsers.

[Unreleased]: https://github.com/monooso/smartdown.craft-plugin/compare/1.0.0...HEAD
[2.0.0]: https://github.com/monooso/smartdown.craft-plugin/compare/1.0.0...2.0.0
[1.0.0]: https://github.com/monooso/smartdown.craft-plugin/compare/0.1.0...1.0.0
