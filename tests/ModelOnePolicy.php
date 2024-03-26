<?php
namespace CustomD\LaravelHelpers\Tests;

use Illuminate\Auth\Access\Response;
use Illuminate\Foundation\Auth\User;
use CustomD\LaravelHelpers\Models\Policies\CrudPermissions;

class ModelOnePolicy
{
    use CrudPermissions;

    public function fetch(User $user, ModelOne $model): Response
    {
        return $this->can($user, 'fetch', $model);
    }
}
