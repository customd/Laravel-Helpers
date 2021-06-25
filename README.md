# Laravel Helpers

Collection of helpers for re-use accross a few of our projects

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

## Credits

- [](https://github.com/custom-d/laravel-helpers)
- [All contributors](https://github.com/custom-d/laravel-helpers/graphs/contributors)

This package is bootstrapped with the help of
[melihovv/laravel-package-generator](https://github.com/melihovv/laravel-package-generator).
