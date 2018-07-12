# Release Notes


## 0.1.9 (2018-07-12)

### Changed

- Changed the way of doing plugins migrations


## 0.1.8 (2018-07-11)

### Added

- Add command `lt-migration:up` to pipeline of command `lt-setup:init`

### Fixed

- Fixed absolute path into a Lortom Commands


## 0.1.7 (2018-07-11)

## Added

- Add command `lt-gitignore:delete`
- Added in pipeline of command `lt-setup:init`:
    - the Artisan call to `lt-gitignore:delete`
    - command exec of `npm-install` into `angular-backend` folder


## 0.1.6 (2018-07-10)

stable version

### Fixed

- Fixed version of angular in `composer.json`
- Fixed bug in `webpack.config.js`
- Fixed in  `lt-setup:init` command, the load of `database` configuration on .env at runtime.