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

## Delayed notifications blocking:

using this package you can setup your notifications to stop - wheter using feature flags or some other reason -
Whenever a notification is being handled in Laravel the `NotificationSending` event is first emitted. We can use this event to once again check if the notification should be sent, thanks to `SerializesModels` and our `blockSending` method.

if the `blockSending` method returns true, we stop the notification as it is no longer needed.

### Steps

1. setup your notification -

```php
class TaskReminder extends Notification implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $task;

    public function __construct(Task $task)
    {
        $this->task = $task;

        $this->delay($task->start_date_time)->subDay(1); //optional
    }

    public function vai(){
      return ['slack'];
    }

    /** NEW METHOD HERE FOR STOPPING THE NOTIFICATION **/
    public function blockSending($notifiable): bool
    {
        return $this->task->isCompleted() || $this->task->isCancelled(); //prevent if complete or cancelled
        //Feature Flag Example
        return Feature::disabled('trigger_task_reminder_via_slack'); //Feature Flag Example
    }
```

By listening to that event and then checking our `blockSending()` method on our notification, we can do in-the-moment checks as the notification is being handled to see if it’s still valid. You can even access the `$notifiable` object, which allows you to check fresh data about the user or entity you’re notifying.


## DB Macros

### Null Or Empty
when dealing with some of our legacy databases we have some columns where the entry is either null or empty and these macros allow you to query this without the double entries:

```php
Model::whereNullOrEmpty('column_name'); //generates select * where 1=1 and (column_name is null or column_name = '')
Model::orWhereNullOrEmpty('column_name'); //generates select * where 1=1 or (column_name is null or column_name = '')
Model::whereNotNullOrEmpty('column_name'); //generates select * where 1=1 and (column_name is not null and column_name != '')
Model::orWhereNotNullOrEmpty('column_name'); //generates select * where 1=1 or (column_name is not null and column_name != '')
```
## String Macros
`Str::reverse(string)` - to safely reverse a string that is multibyte safe.

## Credits

- [](https://github.com/custom-d/laravel-helpers)
- [All contributors](https://github.com/custom-d/laravel-helpers/graphs/contributors)

This package is bootstrapped with the help of
[melihovv/laravel-package-generator](https://github.com/melihovv/laravel-package-generator).
