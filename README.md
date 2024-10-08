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
    - [Permission naming.](#permission-naming)
  - [Crud Access Permission Global Trait \& Scope for Model](#crud-access-permission-global-trait--scope-for-model)
  - [Helpers](#helpers)
  - [DB Macros](#db-macros)
    - [Null Or Empty](#null-or-empty)
    - [Case insensitive statments](#case-insensitive-statments)
    - [Enforced Non Nullable Relations (orFail chain)](#enforced-non-nullable-relations-orfail-chain)
  - [DB Repositories](#db-repositories)
  - [Observerable trait (Deprecated)](#observerable-trait-deprecated)
  - [Record or Fake HTTP Calls](#record-or-fake-http-calls)
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

By using the `CustomD\LaravelHelpers\Models\Policies\CrudPermissions` trait in your model policy alongside `Spatie role permissions` you can avoid having to write lots of boilerplate methods in your policy file.


Instead of a Policy like this
```php
class UserPolicy
{
    public function viewAny(Authenticatable $user): Response
    {
        return $user->can('users.viewAny');
    }

    public function view(Authenticatable $user, User $targetUser): Response
    {
        return $user->can('users.view');
    }
    ...
```

it would now look as follows:

```php

namespace App\Models\Policies;

use App\Models\Policies\Traits\CrudPermissions;

class UserPolicy
{
    use CrudPermissions;
}
```

and it will automatically check for the following permissions which you would have seeded / set up in Spatie's package.

- users.viewAny
- users.view
- users.create
- users.update
- users.delete
- users.forceDelete
- users.restore

Additionally if using the `PermissionBasedAccess` trait on your model, the following extra ones are taken into account
- users.viewOwn (as long as you own the record)
- users.updateOwn
- users.deleteOwn

If the `user_id` column is not `user_id`, set that in the `getOwnerKeyColumn` method documented below in the Crud Access for Model section.

viewAny, create, restore & forceDelete will use the base versions only, and you would need to customise if needed yourself by setting up the permission or policy method to deal with it based on your business logic.
Create and viewAny do not have Ownership within a default policy, and the restore / forceDelete are mainly only an administrative functionality.

### Permission naming.
The permission naming  will by default use a plural of the model name: ie `User` becomes `users` / `BlogPost` becomes `blog_posts`

## Crud Access Permission Global Trait & Scope for Model
If you use the `CustomD\LaravelHelpers\Traits\PermissionBasedAccess` trait on your model it will look for the following Spatie permissions by default:

`xxx.view`, `xxx.viewOwn`

This works as follows:

* if `viewOwn` is true, then it will call `scopeCanRetrieveOwnRecord` scope
* else if  `view` is true, then it will call `scopeCanRetrieveAnyRecord` scope
* else it will fallback to `scopeCannotRetrieveAnyRecord` scope

To modify the user column, add this to the model.
```
    public function getOwnerKeyColumn(): ?string
    {
        return 'custom_id';
    }
```

to call without the scopes: use the `withoutPermissionCheck` modifier: eg `Model::withoutPermissionCheck()->get()`

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

## String Helpers

**isEmail** -- usage `Str::isEmail('xxx')` or `str('xxx')->isEmail()` -- will return boolean.

## Array Helpers

**pushBefore** - will push one or more items before the specified key, or append to the end of the array if the key does not exist:
**PushAfter** will push one or more items after the specified key
```php
$arr = [
  'one' => 'two',
  'three' => 'four',
  'ten' => 'eleven',
];

$missing = [
  'four' => 'five',
  'six' => 'seven',
  'eight' => 'nine'
];

$new = Arr::pushBefore($arr, 'eight', $missing);
// or
$new = Arr::pushAfter($arr, 'three', $missing);

```
## Collection Helpers

**pushBefore** - will push one or more items before the specified key, or append to the end of the array if the key does not exist:
**PushAfter** will push one or more items after the specified key
```php
$collection = collect([
  'one' => 'two',
  'three' => 'four',
  'ten' => 'eleven',
]);

$missing = [
  'four' => 'five',
  'six' => 'seven',
  'eight' => 'nine'
];

$collection->pushBefore('eight', $missing);
// or
$collection->pushAfter('three', $missing);

```

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



## Record or Fake HTTP Calls

The `RecordsOrFakesHttpCalls` trait will allow you to record or fake http calls in your tests. This is useful for testing external api calls without actually calling them.

Add the trait to your PHPUnit test file, ensure the `tests/stubs/` directory exists, and then wrap your test code in a callable:

```php
  public function test_external_api()
  {
      // $this->record = true; // Toggle to create recorded files
      $this->processRecordedTest(
          'test_external_api',
          function () {
              // Any HTTP calls made by MyServiceClass will be recorded or returned from recorded responses, depending on `$this->record` above.
              $result = resolve(MyServiceClass::class)->execute('foo');
              $this->assertEquals('bar', $result->value);
          },
          'json'
      );
  }
```

## Date Manipulation

You can set user timezones via the following options:
1. optionally create a migration with:
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

Additionally you can set defaults on the timezone via the attributes method or a setter or even in the migration.


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
//either in your code where you need it.
$object = ValueObject::fromRequest($MyFormRequest, true|false); //defaults to true (validated only values, false will be all from the request);

//or add a method to your FormRequest
public function getObject(): ValueObject
{
  return ValueObject::fromRequest($this);
}
```

As we are using final classes, these are immutable, if you need a cloned copy with edited values you can use the put command to create a new value object:
eg:

```php
$object->put('key','value'); //will set the key to value
$object->put('key.sub', 'valuesub' ); // will set the array key sub value to valuesub
$object->put('key', ['new' =>'yes']); //will set the array key to be ['new' => 'yes'] -- overwriting the entire array
$object->put(['key.new' => 'yes','key.maybe' => 'no']); //this will NOT override the entire key array, only set the new and maybe values in the actual value object array.


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
