[![Latest Release](https://git.customd.com/composer/Laravel-Helpers/-/badges/release.svg)](https://git.customd.com/composer/Laravel-Helpers/-/releases)
[![coverage report](https://git.customd.com/composer/Laravel-Helpers/badges/master/coverage.svg)](https://git.customd.com/composer/Laravel-Helpers/-/commits/master)
[![Github Issues](https://img.shields.io/github/issues/customd/laravel-helpers)](https://github.com/customd/Laravel-Helpers/issue)
[![For Laravel 5](https://img.shields.io/badge/Laravel-8%20/%209-red.svg)](https://github.com/phpsa/laravel-api-controller/issues)
<a href="https://packagist.org/packages/custom-d/laravel-helpers"><img src="https://img.shields.io/packagist/dt/custom-d/laravel-helpers" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/custom-d/laravel-helpers"><img src="https://img.shields.io/packagist/v/custom-d/laravel-helpers" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/custom-d/laravel-helpers"><img src="https://img.shields.io/packagist/l/custom-d/laravel-helpers" alt="License"></a>

# Laravel Helpers <!-- no toc -->

Collection of helpers for re-use accross a few of our projects

- [Laravel Helpers ](#laravel-helpers-)
  - [Installation](#installation)
  - [Upgrade V2 to V3](#upgrade-v2-to-v3)
  - [Crud Policy Trait](#crud-policy-trait)
  - [Helpers](#helpers)
  - [DB Macros](#db-macros)
    - [Null Or Empty](#null-or-empty)
    - [Case insensitive statments](#case-insensitive-statments)
    - [Enforced Non Nullable Relations (orFail chain)](#enforced-non-nullable-relations-orfail-chain)
  - [DB Repositories](#db-repositories)
  - [Observerable trait (Deprecated)](#observerable-trait-deprecated)
  - [Date Manipulation](#date-manipulation)
    - [Date(Carbon) Helpers attached to above:](#datecarbon-helpers-attached-to-above)
  - [Value Objects](#value-objects)
  - [Larastan Stubs](#larastan-stubs)
  - [Filament Plugin](#filament-plugin)
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

### Case insensitive statments
```php
Model::iWhere('col',$value);
Model::iWhere('col',$operator,$value);
Model::iWhere(['col' => $value]);
```

### Enforced Non Nullable Relations (orFail chain)
```php
function related(){
  return $this->hasOne()->orFail();
}
```

## DB Repositories
use of repositories via extending the `CustomD\LaravelHelpers\Repository\BaseRepository` abstract
example in the [UserRepository.stub.php](https://git.customd.com/composer/Laravel-Helpers/-/blob/master/src/Repository/UserRepository.php.stub) file


## Observerable trait (Deprecated)
adding this trait to your models will automatically look for an observer in the app/Observers folder with the convension {model}Observer as the classname,

you can additionally/optionally add
```php
protected static $observers = [ ...arrayOfObservers]
```
to add a additional ones if

Replace this with
```
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use App\Observers\UserObserver;

#[ObservedBy(UserObserver::class)]
#[ObservedBy(AnotherUserObserver::class)]
class User extends Model
{
    //
}
```

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
```

Additinoally you can set defaults on the timezone via the attributes method or a setter or even in the migration.
3. in your app config file add the `user_timezone` parameter.

4. add the UserTimeZone middleware to your api middleware list.

You can now access the current requests timezone via `config('request.user.timezone')`

### Date(Carbon) Helpers attached to above:
methods available:

* `setUserTimezone(string $timezone) : Static` - sets the users timezone (default set by helper)
* `getUserTimezone() : string` - gets current users timezone
* `setSystemTimezone(string $timezone) : Static` - sets system timezone (Default app.timezone)
* `getSystemTimezone(): string` - gets teh current timezone
* `toUsersTimezone(): Static` - returns carbon instance set to users timezone
* `toSystemTimezone(): Static` - returns carbon instance set to system timezone
* `usersStartOfDay(): Static` - returns carbon instance set to start of users day (converts to users timezone => start of day => to systemtime)
* `usersEndOfDay(): Static` - users end of day
* `usersStartOfWeek(): Static`
* `usersEndOfWeek(): Static`
* `usersStartOfMonth(): Static`
* `usersEndOfMonth(): Static`
* `usersStartOfQuarter(): Static`
* `usersEndOfQuarter(): Static`
* `usersStartOfYear(): Static`
* `usersEndOfYear(): Static`
* `parseWithTz(string $time): Static` - parses the time passed using the users timezone unless the timezone is in the timestamp
* `hasISOFormat(string $date): bool` - checks if the date is in iso format.

You can also use the CDCarbonDate to create a few different date objects.

## Value Objects
Example:
```php
<?php
declare(strict_types=1);

namespace CustomD\LaravelHelpers\Tests\ValueObjects;

use CustomD\LaravelHelpers\ValueObjects\ValueObject;

final readonly class SimpleValue extends ValueObject
{
    protected function __construct(
        public string $value,
        public int $count = 0
    ) {
    }

  /** optionally add some validation rules, leave out the method if the type sets are enough **/
    public function rules(): array
    {
        return [
          'value' => ['string', 'max:250'],
          'count' => ['max:99'],
        ];
    }
}

$simpleValue = SimpleValue::make(value: 'hello World', count: 33);

```
Or using attributes to make advanced objects.
```php
<?php
declare(strict_types=1);

namespace CustomD\LaravelHelpers\Tests\ValueObjects;

use Illuminate\Support\Collection;
use CustomD\LaravelHelpers\ValueObjects\ValueObject;
use CustomD\LaravelHelpers\ValueObjects\Attributes\MakeableObject;
use CustomD\LaravelHelpers\ValueObjects\Attributes\ChildValueObject;
use CustomD\LaravelHelpers\ValueObjects\Attributes\CollectableValue;
use CustomD\LaravelHelpers\ValueObjects\Attributes\MapToCase;

#[MapToCase('camel')]
final readonly class ComplexValue extends ValueObject
{
    public function __construct(
        #[ChildValueObject(StringValue::class)]
        readonly public StringValue $value,
        readonly public array $address,
        #[ChildValueObject(SimpleValue::class)]
        readonly public SimpleValue $simpleValue,
        #[MakeableObject(Constructable::class)]
        readonly public ?Constructable $constructable = null,
        #[CollectableValue(SimpleValue::class)]
        readonly ?Collection $simpleValues = null,
    ) {
    }

}
```

Best practice is to use the make option, which will validate, if you use a public constructor it will not.

These should all be marked as READONLY and FINAL.

The attributes available are:
* `ChildValueObject(valueobectclass)` - which will make a new valueObject
* `CollectableValue(valueobjectclass)` - which will convert an array to a coollection of the value objects
* `MakeableObject(class, [?$spread = false])` - will look for a make method or else construct if passed an non object - if spread is true will expand the array else will pass the array as a single argument
* `MapToCase(('snake|camel|studly'))` - for the fromRequest method


Mapping Valueobject from your Request would be as easy as doing one of the following:
```php
//eiterh in your code where you need it.
$object = ValueObject::fromRequest($MyFormRequest, true|false); //defaults to true (validated only values, false will be all from the request);

//or add a method to your FormRequest
public function getObject(): ValueObject
{
  return ValueObject::fromRequest($this);
}
```

## Larastan Stubs
**these are temporary only till implemented by larastan**

add to your phpstan.neon.dist file
```yaml
parameters:
    stubFiles:
        - ./vendor/custom-d/laravel-helpers/larastan/blank_filled.stub
```

## Filament Plugin
** this is only if you want to deal with user timezones for display, else will be in UTC in the Filament panel **

simply add to your panelProvider
`->plugin(UserTimeZonePlugin::make())`

## Credits

- [](https://github.com/custom-d/laravel-helpers)
- [All contributors](https://github.com/custom-d/laravel-helpers/graphs/contributors)
