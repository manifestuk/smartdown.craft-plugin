# Changelog

## 3.0.1 - 2018-08-31

### Added
- Add license to `composer.json` (fixes [#7])

[#7]: https://github.com/experience/smartdown.craft-plugin/issues/7

### Changed
- Remove PHP version requirement (fixes [#11])

[#11]: https://github.com/experience/smartdown.craft-plugin/issues/11

## 3.0.0 - 2018-03-31

### Added
- Update for Craft 3.

## 2.1.0 - 2017-02-17

### Added
- Add Travis CI configuration.
- Add documentation URL.
- Add schema version.
- Add releases feed.

### Changed
- Reorganise plugin according to current best practices.
- Use present tense in CHANGELOG.

### Fixed
- Update parser to accept objects with a `__toString` method.

## 2.0.1 - 2016-01-01

### Fixed
- Fix copying of README in build process.

## 2.0.0 - 2015-12-31

### Added
- Add CHANGELOG
- Implement unit tests for 'utility' classes.
- Implement build process.

### Changed
- Rename plugin from SmartDown to Smartdown. Beware case-insensitive version control systems.
- Rename 'SmartDownService' to 'SmartdownService'.
- Remove deprecated 'markdown' and 'smartypants' Twig filter options.
- Update MarkdownExtra to version 1.6.
- Update SmartyPants to version 1.6 beta

### Fixed
- Fix issue with SmartyPants dependency containing errant '.git' folder

## 1.0.0 - 2015-07-29

### Added
- Add 'SmartDownService'.
- Add 'modifySmartdownMarkupInput' hook.
- Add 'modifySmartdownMarkupOutput' hook.
- Add 'modifySmartdownTypographyInput' hook.
- Add 'modifySmartdownTypographyOutput' hook.

### Changed
- Deprecate 'markdown' Twig filter option. Use 'markup' instead.
- Deprecate 'smartypants' Twig filter option. Use 'typography' instead.

## 0.1.0 - 2013-08-20

### Added
- Add 'smartdown' Twig filter, which runs a string through MarkdownExtra and SmartyPants.
