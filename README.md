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

- user.list
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
```

### Enforced Non Nullable Relations (orFail chain)
```php
function related(){
  return $this->hasOne()->orFail();
}
```
## String Macros
`Str::reverse(string)` - to safely reverse a string that is multibyte safe.

## Credits

- [](https://github.com/custom-d/laravel-helpers)
- [All contributors](https://github.com/custom-d/laravel-helpers/graphs/contributors)

This package is bootstrapped with the help of
[melihovv/laravel-package-generator](https://github.com/melihovv/laravel-package-generator).
