# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [0.1.1] - 2020-06-24
### Fixed
- Fixed symlink error when there already exists a .git/hooks symlink, but its target does not exist.

## [0.1.0] - 2020-06-24
### Added
- The package will now not only check the composer working directory for a .git folder, but also any parent directories.

### Changed
- Added check for existence of custom docksal commands to standard-hooks phpcs and phpstan
- Permissions are now set correctly (0755) on symlinks placed in .git/hooks. 

### Fixed
- When the installer places symlinks in the .git/hooks folder, existing hooks should be ignored. This check failed for non-symlinks and has now been fixed.
- ShellCheck fixes for all shell scripts

## [0.0.3] - 2020-05-08
### Removed
- Removed bash installer script. Manual installation is not necessary (since the package is a composer plugin) and if necessary you can add a "scripts" section in the composer.json with a call to the installer. Added this to the README.

## [0.0.2] - 2020-05-05
### Fixed
- Fixed error in README

## [0.0.1] - 2020-04-28
### Added
- Initial version of the project