# Change Log
All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).

## [Unreleased]
### Added

## 0.0.4 - 2016-05-11
### Added
- XMLSerializer that allows to serialize objacets and array to a plain XML structure
- Paperworks loader
- Paperflows loader
- support for multilanguage

## 0.0.3 - 2016-01-29
### Added
- New function on string utils in order to truncate strings that are too long
- Added function getOneField to base Dao class it allows user to get one field from a table
### Fixed
- Modifying index.php in order to delete everitingh after the ? in the url.

## 0.0.2 - 2016-01-29
### Added
- Adding method setRequest to PrivateAggregator and to PublicAggregator in order to have the possibility
of getting back to previous page and in order to know where I am now
- Adding method setControllerPath to PrivateAggregator and to PublicAggregator in order to save
family, subfamily and aggregator in current controller implementation
- Adding method redirectToPreviousPage to PrivateAggregator so it is possible to get to the previous
page after calling a controller passing automatically a set of flash messages [info, warning, error, success]
- Adding method redirectToSecondPreviousPage to PrivateAggregator so it is possible to get to the previous previous
page after calling a controller passing automatically a set of flash messages [info, warning, error, success]
- Adding method redirectToPage to PrivateAggregator so it is possible to redirect to a page passing automatically
a set of flash messages [info, warning, error, success]
- Adding methods countByFieldList and countByFields to BasicDao in order to enlarge the dao objects possibilities

## 0.0.1 - 2016-01-29
### Added
- This CHANGELOG file to hopefully have notes about what is changing version by version.
You can find description about how to keep this changelog here [keepachangelog.com](http://keepachangelog.com/).
