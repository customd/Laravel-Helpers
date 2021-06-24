# Laravel Helpers

Collection of helpers for re-use accross a few of our projects

## Installation

Install via composer

```bash
composer require custom-d/laravel-helpers
```

## Helpers

**execute** - this helper runs an execute action on an action file with dependancy injection on the contructor

```
execute(Action::class, $param, $param2);
execute(Action::class);

//resolves as
$app->make(Action::class)->execute(...)
```

If you discover any security related issues, please email
instead of using the issue tracker.

## Credits

- [](https://github.com/custom-d/laravel-helpers)
- [All contributors](https://github.com/custom-d/laravel-helpers/graphs/contributors)

This package is bootstrapped with the help of
[melihovv/laravel-package-generator](https://github.com/melihovv/laravel-package-generator).
