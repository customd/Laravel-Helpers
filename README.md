[![Latest Release](https://git.customd.com/composer/Laravel-Helpers/-/badges/release.svg)](https://git.customd.com/composer/Laravel-Helpers/-/releases)
[![coverage report](https://git.customd.com/composer/Laravel-Helpers/badges/master/coverage.svg)](https://git.customd.com/composer/Laravel-Helpers/-/commits/master)
[![Github Issues](https://img.shields.io/github/issues/customd/laravel-helpers)](https://github.com/customd/Laravel-Helpers/issue)
[![For Laravel 5](https://img.shields.io/badge/Laravel-8%20/%209-red.svg)](https://github.com/phpsa/laravel-api-controller/issues)
<a href="https://packagist.org/packages/custom-d/laravel-helpers"><img src="https://img.shields.io/packagist/dt/custom-d/laravel-helpers" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/custom-d/laravel-helpers"><img src="https://img.shields.io/packagist/v/custom-d/laravel-helpers" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/custom-d/laravel-helpers"><img src="https://img.shields.io/packagist/l/custom-d/laravel-helpers" alt="License"></a>

# Laravel Helpers <!-- no toc -->

Collection of helpers for re-use accross a few of our projects

  - [Installation](#installation)
  - [Crud Policy Trait](#crud-policy-trait)
  - [Helpers](#helpers)
  - [Delayed notifications blocking:](#delayed-notifications-blocking)
  - [DB Macros](#db-macros)
  - [String Macros](#string-macros)
  - [Credits](#credits)

## Installation

Install via composer

```bash
composer require custom-d/laravel-helpers
```

## Upgrade V2 to V3

 - ViewAny plicy now checks for a viewAny permission and not list for the viewAny permission check
 - Dropped support for php 7.3 & 7.2
 - Dropped support for Laravel 6 & 7
 - marked execute helper as deprecated
 - fixed Model::orWhereNotNullOrEmpty method to do correct query
## Crud Policy Trait

by using the `CustomD\LaravelHelpers\Models\Policies\CrudPermissions` trait in your model policy along side Spatie role permissions using wildcard permissions
you can have your policy look like:

```php

namespace App\Models\Policies;

use App\Models\Policies\Traits\CrudPermissions;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;
    use CrudPermissions;
}
```

and it will check for the following permissions:

- user.viewAny (list is v2 and earlier)
- user.view
- user.create
- user.update
- user.delete
- user.restore

for user locked based policy permissions you can add the following method to your model:
`userHasPermission(User $user): bool`

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


## DB Macros

### Null Or Empty
when dealing with some of our legacy databases we have some columns where the entry is either null or empty and these macros allow you to query this without the double entries:

```php
Model::whereNullOrEmpty('column_name'); //generates select * where 1=1 and (column_name is null or column_name = '')
Model::orWhereNullOrEmpty('column_name'); //generates select * where 1=1 or (column_name is null or column_name = '')
Model::whereNotNullOrEmpty('column_name'); //generates select * where 1=1 and (column_name is not null and column_name != '')
Model::orWhereNotNullOrEmpty('column_name'); //generates select * where 1=1 or (column_name is not null and column_name != '')
Model::whereNullOrValue('column_name', [$operator],$value, [$boolean]); to check if column null or specific value (follows laravel where specification where operator is optional)
```

### Enforced Non Nullable Relations (orFail chain)
```php
function related(){
  return $this->hasOne()->orFail();
}
```
## String Macros
`Str::reverse(string)` - to safely reverse a string that is multibyte safe.

## Date Manipulation

You can set user timezones via the following options:
1. optionally create a migration with
```
Schema::table('users', function (Blueprint $table) {
            $table->string('timezone', 40)->nullable();
        });
```
2. in user model:
```
pubic function timezone(): Attribute
{
  return Attribute::get(fn($value) => $value ?? config('app.user_timezone'));
}
Additinoally you can set defaults on the timezone via the attributes method or a setter or even in the migration.
3. in your app config file add the `user_timezone` parameter.

4. add the UserTimeZone middleware to your api middleware list.

You can now access the current requests timezone via `config('request.user.timezone')`

## Credits

- [](https://github.com/custom-d/laravel-helpers)
- [All contributors](https://github.com/custom-d/laravel-helpers/graphs/contributors)

