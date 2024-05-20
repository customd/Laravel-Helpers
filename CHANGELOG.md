## [5.4.4](https://git.customd.com/composer/Laravel-Helpers/compare/v5.4.3...v5.4.4) (2024-05-20)


### Bug Fixes

* issue with gates on global scope ([c26eb30](https://git.customd.com/composer/Laravel-Helpers/commit/c26eb30965565494e4daab5b9f2996be8473e288))

## [5.4.3](https://git.customd.com/composer/Laravel-Helpers/compare/v5.4.2...v5.4.3) (2024-05-20)


### Bug Fixes

* minor updates to policy automation ([45b1656](https://git.customd.com/composer/Laravel-Helpers/commit/45b1656da97cad2f9dbbfde2218bcb9e228fd76c))

## [5.4.2](https://git.customd.com/composer/Laravel-Helpers/compare/v5.4.1...v5.4.2) (2024-05-20)


### Bug Fixes

* crud access query scopes ([cc60345](https://git.customd.com/composer/Laravel-Helpers/commit/cc60345f355c7e178fa003730af8b8fc65a2c419))
* phpstan ([86f54bf](https://git.customd.com/composer/Laravel-Helpers/commit/86f54bfe36d50373af2872ce7cf3b41413196464))

## [5.4.1](https://git.customd.com/composer/Laravel-Helpers/compare/v5.4.0...v5.4.1) (2024-05-20)


### Bug Fixes

* crud permissions on policy to make use of same permission from trait on model. ([b52f7be](https://git.customd.com/composer/Laravel-Helpers/commit/b52f7be85830316f021d3f1de06a02be82884a80))

# [5.4.0](https://git.customd.com/composer/Laravel-Helpers/compare/v5.3.2...v5.4.0) (2024-05-19)


### Features

* Permission Scope / Trait ([bd57248](https://git.customd.com/composer/Laravel-Helpers/commit/bd5724887bf8945be4167fc58d5f2e22f6ffd1d5))

## [5.3.2](https://git.customd.com/composer/Laravel-Helpers/compare/v5.3.1...v5.3.2) (2024-05-16)


### Bug Fixes

* **valueObjects:** mapping only if array is indexed ([6c7b119](https://git.customd.com/composer/Laravel-Helpers/commit/6c7b11971460a2d7615d447b5221d7e1387396d8))

## [5.3.1](https://git.customd.com/composer/Laravel-Helpers/compare/v5.3.0...v5.3.1) (2024-04-09)


### Bug Fixes

* **docs:** Add prelim HTTP Recording section to mention requirement of `tests/stubs/` dir ([#3](https://git.customd.com/composer/Laravel-Helpers/issues/3)) ([67dfa17](https://git.customd.com/composer/Laravel-Helpers/commit/67dfa1793fa91fb02bb158d7b0806c803256637d))

# [5.3.0](https://git.customd.com/composer/Laravel-Helpers/compare/v5.2.2...v5.3.0) (2024-04-09)


### Features

* add isEmail helper to Str / Stringable ([28463ee](https://git.customd.com/composer/Laravel-Helpers/commit/28463ee47be321b8e15740ec2de8675eabc458b7))

## [5.2.2](https://git.customd.com/composer/Laravel-Helpers/compare/v5.2.1...v5.2.2) (2024-03-26)


### Bug Fixes

* fixes to use only crud methods by default ([5eb47ff](https://git.customd.com/composer/Laravel-Helpers/commit/5eb47ff372ff0c90642d8b4b39e905ea80506cb1))

## [5.2.1](https://git.customd.com/composer/Laravel-Helpers/compare/v5.2.0...v5.2.1) (2024-03-22)


### Bug Fixes

* **value objects:** small optimisation of key case mapping ([f469892](https://git.customd.com/composer/Laravel-Helpers/commit/f4698924e1c5c083f3c292fb067f2d00a2fa652b))

# [5.2.0](https://git.customd.com/composer/Laravel-Helpers/compare/v5.1.0...v5.2.0) (2024-03-19)


### Features

* added maptocase attribute for fromrequest method on valueobject ([2cd2516](https://git.customd.com/composer/Laravel-Helpers/commit/2cd2516f47aa0843620c5703585aaa6212d49a50))
* allow repository to run calls without global scopes ([0935044](https://git.customd.com/composer/Laravel-Helpers/commit/0935044a9dcb65e261daf8d3a63c3bc03506fe62))

# [5.1.0](https://git.customd.com/composer/Laravel-Helpers/compare/v5.0.0...v5.1.0) (2024-03-18)


### Features

* trait to allow tests to call protectd methods / properties ([1a12672](https://git.customd.com/composer/Laravel-Helpers/commit/1a12672dd4273f2b64de4d1944c57178fb309354))

## [4.13.2](https://git.customd.com/composer/Laravel-Helpers/compare/v4.13.1...v4.13.2) (2024-03-13)


### Bug Fixes

* carbon mixin sets immutable timezone and has helper hasISOFormat(date) ([b58f040](https://git.customd.com/composer/Laravel-Helpers/commit/b58f040b890dc8475988a0dc0fad724dc51ef5ce))
* deprecated ovservable trait - laravel has observable attribute now ([e3b0e3e](https://git.customd.com/composer/Laravel-Helpers/commit/e3b0e3efaea5ce65bda6be415714077f527ac2a1))
* enanced crud permissions to be more flexable by using callable ([299515c](https://git.customd.com/composer/Laravel-Helpers/commit/299515cc5b7dcc76fd137867eca338c5f5dca704))

## [4.13.1](https://git.customd.com/composer/Laravel-Helpers/compare/v4.13.0...v4.13.1) (2024-03-04)


### Bug Fixes

* adds toJsonResource to valueObjects ([7e8af8d](https://git.customd.com/composer/Laravel-Helpers/commit/7e8af8df7ceabac2ecd987e68ff1642c89fcc2de))

# [4.13.0](https://git.customd.com/composer/Laravel-Helpers/compare/v4.12.0...v4.13.0) (2024-02-28)


### Features

* advanced attriburtes on value objects ([6e5130d](https://git.customd.com/composer/Laravel-Helpers/commit/6e5130d01fa1519fd070c822a11b4ed3d8c5b7ab))

# [4.12.0](https://git.customd.com/composer/Laravel-Helpers/compare/v4.11.0...v4.12.0) (2024-02-11)


### Features

* Filament Timezone Control on input ([7327a27](https://git.customd.com/composer/Laravel-Helpers/commit/7327a27c6965de79360507534992bd116912daba))

# [4.11.0](https://git.customd.com/composer/Laravel-Helpers/compare/v4.10.0...v4.11.0) (2024-02-06)


### Features

* value objects for stricter object types ([7a6f0c3](https://git.customd.com/composer/Laravel-Helpers/commit/7a6f0c38876d9137bcf60296c1e68844b5e0a78e))

# [4.10.0](https://git.customd.com/composer/Laravel-Helpers/compare/v4.9.1...v4.10.0) (2024-01-24)


### Features

* added callable to the base repository to forward calls directly to the model ([60817d3](https://git.customd.com/composer/Laravel-Helpers/commit/60817d3d804ae4072b2e77b68acc75923b25ce6c))

## [4.9.1](https://git.customd.com/composer/Laravel-Helpers/compare/v4.9.0...v4.9.1) (2024-01-23)


### Bug Fixes

* larastan rule for blank / filled ([d8560d4](https://git.customd.com/composer/Laravel-Helpers/commit/d8560d4c39f520ca1b878b7d1530c8717b6de61b))

# [4.9.0](https://git.customd.com/composer/Laravel-Helpers/compare/v4.8.0...v4.9.0) (2023-11-02)


### Features

* base repository pattern ([05957a8](https://git.customd.com/composer/Laravel-Helpers/commit/05957a8844354aadfc9ebe1395208870d55bac7f))

# [4.8.0](https://git.customd.com/composer/Laravel-Helpers/compare/v4.7.0...v4.8.0) (2023-07-13)


### Bug Fixes

* added missing userFormat method ([70b3c1d](https://git.customd.com/composer/Laravel-Helpers/commit/70b3c1df7b156af32da2f6dd26a95fcd72e3f973))
* stan ([631d09d](https://git.customd.com/composer/Laravel-Helpers/commit/631d09d65f7dd4fce7858498b4654ccd16bcf3dd))


### Features

* add from_key_or_model helper for laoding models from instances or primary key ([d561f03](https://git.customd.com/composer/Laravel-Helpers/commit/d561f03b798ca633d3f6c78d63e24dc634d6aa30))
* enhanced date functionality ([cef9812](https://git.customd.com/composer/Laravel-Helpers/commit/cef981295ee19c9078ccbebbf4170e00bde15ba1))
* **exception:** update extra type to string|array|null ([4fdd28c](https://git.customd.com/composer/Laravel-Helpers/commit/4fdd28c92e69811a0bfb80f0a67ab79046fa03da))

# [4.7.0](https://git.customd.com/composer/Laravel-Helpers/compare/v4.6.1...v4.7.0) (2023-07-04)


### Features

* added Observerable trait to allow registration of observers in models based on naming convensions ([6080d43](https://git.customd.com/composer/Laravel-Helpers/commit/6080d433ae43559d2450f42c01591c2def09e870))

## [4.6.1](https://git.customd.com/composer/Laravel-Helpers/compare/v4.6.0...v4.6.1) (2023-05-31)


### Bug Fixes

* compatability with recordings for laravel prior to 9.12 ([b1a6f65](https://git.customd.com/composer/Laravel-Helpers/commit/b1a6f650d34c36e67daea43717d0e7987c2016d6))

# [4.6.0](https://git.customd.com/composer/Laravel-Helpers/compare/v4.5.0...v4.6.0) (2023-05-01)


### Features

* **http-recording:** BREAKING: update recording to store http status and headers along side the body in a json file. Eliminates the need to pass a `type` but is not ([9f6e308](https://git.customd.com/composer/Laravel-Helpers/commit/9f6e30833f060438d62ae7a68a32dd4769423ce8))

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
