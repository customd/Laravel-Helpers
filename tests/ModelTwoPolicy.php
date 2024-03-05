<?php
namespace CustomD\LaravelHelpers\Tests;

use CustomD\LaravelHelpers\Models\Policies\CrudPermissions;

class ModelTwoPolicy
{
    use CrudPermissions;

    protected string $modelField = 'user_id';
}
