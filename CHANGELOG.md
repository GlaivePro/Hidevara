# Changelog

## 1.1.0

### Added

- Laravel 7 compatibility.

## 1.0.5

### Fixed

- The package crashing PHPUnit in some cases.

## 1.0.4

### Added

- Laravel 5.8 compatibility.

## 1.0.2

### Fixed
- Made HidingHandler implement ExceptionHandler i-face.

## 1.0.1

### Added
- Option to manually bind the hider earlier in lifecycle.

### Changed
- Changed extending method to use Laravel Service Container's extend method instead of the PHP native extending. This allows to use this regardless of the app namespace.
- Moved config merging from provider to class because a crash could happen before provider is called.
- Some namings and internal procedures.

### Updated
- Readme according to changes.

## Initial commit

### Added
- This package.

### Deprecated
- Nothing, I guess.

### Fixed
- Whoops dumping your database credentials and other data.

### Removed
- Aforementioned data from the whoops output.

### Security
- It's implemented.
