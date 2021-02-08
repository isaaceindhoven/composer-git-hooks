# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]
### Addedd
- PHP 8.0 support

## [1.1.0] - 2020-12-22
### Added
- Composer 2 support

## [1.0.2] - 2020-10-31
### Fixed
- Fixed issues due to PHP 7.4-only syntax

### Changed
- Use docksal-cli PHP 7.3 image instead of PHP 7.4.

## [1.0.1] - 2020-10-30
### Fixed
- Fixed issues due to PHP 7.4-only syntax

### Removed
- Removed PHP 7.2 support

## [1.0.0] - 2020-10-30
### Changed
- Open sourced package

### Removed
- Removed ISAAC company-specifics from README

## [0.2.1] - 2020-10-30
### Changed
- phpcs fixes

## [0.2.0] - 2020-10-30
### Added
- Added LICENSE.md

### Changed
- Changed license to MIT

### Removed
- Removed ISAAC company-specifics from README and composer.json
- Removed the standard-hooks (ISAAC specific) and put them into their own package.
- ISAAC copyright preambles (preparation for open-sourcing)

## [0.1.5] - 2020-08-20
### Changed
- Fallback to host's php when docksal is unavailable for the phpcs and phpstan standard hooks 

### Fixed
- The chain-hook script now properly forwards git's arguments to the actual hook. Before it would 'eat them up'.

## [0.1.4] - 2020-08-19
### Added
- Added standard prepare-commit-msg hook to add the JIRA issue id
- Support PHP 7.4

## [0.1.3] - 2020-08-14
### Fixed
- Depending on composer's installation method for the package (via source, via dist?), the standard-hooks would not get the correct executable permission. Added a check for this such that during composer install/update the correct permissions will be set.  

## [0.1.2] - 2020-06-24
### Fixed
- In some cases, the chain-hook script in the package folder did not have executable permissions. On install we now ensure the chain-hook script has the correct permissions.

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