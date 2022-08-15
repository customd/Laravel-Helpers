<?php

namespace CustomD\LaravelHelpers\Models\Policies;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait CrudPermissions
{

    public function can(Authenticatable $user, string $action, ?Model $model = null): bool
    {
        $permission = collect([
            $this->permission_name ?? self::parsePermissionNameFromPolicy(),
            $action
        ])->filter()->implode(".");

        if ($model && method_exists($model, 'userHasPermission'))
        {
            info('Model::userHasPermission calls have been deprecated - and will be removed in the next version');
            if(! $model->userHasPermission($user)) {
                return false;
            }
        }
        return $user->can($permission);
    }

    public static function parsePermissionNameFromPolicy(): string
    {
        return Str::of(class_basename(get_called_class()))
            ->replaceLast('Policy', '')
            ->snake()
            ->plural()
            ->value;
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @return mixed
     */
    public function viewAny(Authenticatable $user)
    {
        return $this->can($user, 'viewAny');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return mixed
     */
    public function view(Authenticatable $user, Model $model)
    {
        return $this->can($user, 'view');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @return mixed
     */
    public function create(Authenticatable $user)
    {
        return $this->can($user, 'create');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return mixed
     */
    public function update(Authenticatable $user, Model $model)
    {
        return $this->can($user, 'update');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return mixed
     */
    public function delete(Authenticatable $user, Model $model)
    {
        return $this->can($user, 'delete');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return mixed
     */
    public function restore(Authenticatable $user, Model $model)
    {
        return $this->can($user, 'restore');
    }
}
