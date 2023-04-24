# [4.5.0](https://git.customd.com/composer/Laravel-Helpers/compare/v4.4.1...v4.5.0) (2023-04-24)


### Features

* trait to help with recording ([21a1fd1](https://git.customd.com/composer/Laravel-Helpers/commit/21a1fd12ee885d0de6415f130c7aa441b958313c))

## [4.4.1](https://git.customd.com/composer/Laravel-Helpers/compare/v4.4.0...v4.4.1) (2023-03-27)


### Bug Fixes

* added orIWhere for the or statment ([8b4b06c](https://git.customd.com/composer/Laravel-Helpers/commit/8b4b06cbb386fbdbe4aaddec56fba95eca82bb71))

# [4.4.0](https://git.customd.com/composer/Laravel-Helpers/compare/v4.3.0...v4.4.0) (2023-03-27)


### Features

* case insensitive where statement iWhere ([8aaccd4](https://git.customd.com/composer/Laravel-Helpers/commit/8aaccd4e802932d73c015756338091d5ca65fcf9))

# [4.3.0](https://git.customd.com/composer/Laravel-Helpers/compare/v4.2.0...v4.3.0) (2023-02-28)


### Features

* unit testing helper: randomTestingId() ([d14082d](https://git.customd.com/composer/Laravel-Helpers/commit/d14082dcd6643884e6bdb0797901df238b0f635f))

# [4.2.0](https://git.customd.com/composer/Laravel-Helpers/compare/v4.1.1...v4.2.0) (2023-01-15)


### Features

* carbon extended ([8f10c6f](https://git.customd.com/composer/Laravel-Helpers/commit/8f10c6f602d347907158e59307f774f279360c44))
* datetime wiht timezones handling ([bb8c26d](https://git.customd.com/composer/Laravel-Helpers/commit/bb8c26d57f7a77bc82b84ac04e09ac17eab3d3a9))

## [4.1.1](https://git.customd.com/composer/Laravel-Helpers/compare/v4.1.0...v4.1.1) (2022-09-09)


### Bug Fixes

* update whereNullOrValue method ([5c33df4](https://git.customd.com/composer/Laravel-Helpers/commit/5c33df408ba620809ae02d50e8b88ee753bfef77))

# [4.1.0](https://git.customd.com/composer/Laravel-Helpers/compare/v4.0.0...v4.1.0) (2022-09-09)


### Features

* whereNullOrValue database mixin ([023551e](https://git.customd.com/composer/Laravel-Helpers/commit/023551eba8e5d2c4a8d4763824d5ed52c090870e))

# [4.0.0](https://git.customd.com/composer/Laravel-Helpers/compare/v3.2.0...v4.0.0) (2022-08-15)


### Features

* Fixes policy parser to use correct name  ([92679f8](https://git.customd.com/composer/Laravel-Helpers/commit/92679f801c94cbc17c7b22f5b180102a2f106662))


### BREAKING CHANGES

* could break names using the existing broken behaviour

# [3.2.0](https://git.customd.com/composer/Laravel-Helpers/compare/v3.1.0...v3.2.0) (2022-07-26)


### Features

* ddh and dump helpers to do debugging  ([8fe681a](https://git.customd.com/composer/Laravel-Helpers/commit/8fe681a079eae2a8f91ebf2f8f73ff98f3fabd6b))

# [3.1.0](https://git.customd.com/composer/Laravel-Helpers/compare/v3.0.0...v3.1.0) (2022-04-11)


### Bug Fixes

* this instead of request ([c69fb57](https://git.customd.com/composer/Laravel-Helpers/commit/c69fb572479acc8af31f99d0125d88b712f9035a))


### Features

* set config based on users current timezone ([4a6e0f4](https://git.customd.com/composer/Laravel-Helpers/commit/4a6e0f45f65d59d8f0c5daf7b4657c8191f759a5))

# [3.0.0](https://git.customd.com/composer/Laravel-Helpers/compare/v2.0.0...v3.0.0) (2022-03-07)


### Bug Fixes

* incorrect policy permission name ([9f000da](https://git.customd.com/composer/Laravel-Helpers/commit/9f000da3b4af432e81abd3c2a392e62f830f3930))
* orwhereNotNullorEmpty returning false positives ([152d7af](https://git.customd.com/composer/Laravel-Helpers/commit/152d7afe820e4661188bde3dfe2f841e1364c7d8))


### Features

* Db Relationship orFail method and tests ([7330da6](https://git.customd.com/composer/Laravel-Helpers/commit/7330da6e7d063fe6447cb491d2b392882b257cbf))
* removal of listener event and docs ([6fc3a73](https://git.customd.com/composer/Laravel-Helpers/commit/6fc3a7334731004a9faf78fd25bd31242fe35fd3))
* Remove NotificationSendingListner in favour of core laravel ([bd2dcb6](https://git.customd.com/composer/Laravel-Helpers/commit/bd2dcb6fb910c3e63b18612ab13f35176fe549d5))


### BREAKING CHANGES

* permission should be viewAny and not list
* https://laravel.com/docs/8.x/notifications#determining-if-the-queued-notification-should-be-sent in place of `blockSending` method on notification.

# [2.0.0](https://github.com/customd/Laravel-Helpers/compare/v1.4.1...v2.0.0) (2021-10-08)


### Features

* execute helper no longer manipulates data ([a786a68](https://github.com/customd/Laravel-Helpers/commit/a786a6874d9be25b57621360d007f2ee85eade77))


### BREAKING CHANGES

* - arrays are no longer flattened

## [1.4.1](https://github.com/customd/Laravel-Helpers/compare/v1.4.0...v1.4.1) (2021-07-21)


### Bug Fixes

* Crud permissions for non-model based check. (ie create) ([49138db](https://github.com/customd/Laravel-Helpers/commit/49138dba1fa9af2c426918a0e068a39b2dfa3887))

# [1.4.0](https://github.com/customd/Laravel-Helpers/compare/v1.3.0...v1.4.0) (2021-07-12)


### Features

* string reverse helper ([06b03ef](https://github.com/customd/Laravel-Helpers/commit/06b03ef5105411404c25919d82560b7306962d9f))

# [1.3.0](https://github.com/customd/Laravel-Helpers/compare/v1.2.2...v1.3.0) (2021-07-12)


### Features

* **DB:** Macros for null or empty dealings ([15c3e8e](https://github.com/customd/Laravel-Helpers/commit/15c3e8ef5f4099b981c6def3f41a0ab5e5df1ec6))

## [1.2.2](https://github.com/customd/Laravel-Helpers/compare/v1.2.1...v1.2.2) (2021-07-01)


### Bug Fixes

* allow php 8 ([0739d39](https://github.com/customd/Laravel-Helpers/commit/0739d39d675032770b1e6793600064fca2e07e5c))

## [1.2.1](https://github.com/customd/Laravel-Helpers/compare/v1.2.0...v1.2.1) (2021-07-01)


### Bug Fixes

* updated method name to suite original work ([65b668b](https://github.com/customd/Laravel-Helpers/commit/65b668b70d67ea0b3834fc48aebb5a8e0c3750ef))

# [1.2.0](https://github.com/customd/Laravel-Helpers/compare/v1.1.0...v1.2.0) (2021-07-01)


### Features

* Notification delayed blocking after the affect ([37321e6](https://github.com/customd/Laravel-Helpers/commit/37321e6eeb6d0ae3c316e170be6e6b4e418059d5))

# [1.1.0](https://github.com/customd/Laravel-Helpers/compare/v1.0.1...v1.1.0) (2021-06-25)


### Features

* **policy:** CRUD trait added to handle basic crud permissions with policy ([ab3c2ea](https://github.com/customd/Laravel-Helpers/commit/ab3c2ea23eded37a962675907967c37670a7a718))

## [1.0.1](https://github.com/customd/Laravel-Helpers/compare/v1.0.0...v1.0.1) (2021-06-24)


### Bug Fixes

* **helper:** allow array based args to be passed to execute method ([5c264a9](https://github.com/customd/Laravel-Helpers/commit/5c264a9ef4553af6ebde392924f5130ac6dc2de8))

# 1.0.0 (2021-06-24)


### Features

* **helpers:** add execute helper ([f3f568b](https://github.com/customd/Laravel-Helpers/commit/f3f568b41c259930ec21076bac88429fb71ed53d))
