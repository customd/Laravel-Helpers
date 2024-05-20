<?php

namespace CustomD\LaravelHelpers\Models\Policies;

use Illuminate\Support\Str;
use \Illuminate\Auth\Access\Response;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Contracts\Auth\Access\Authorizable;
use CustomD\LaravelHelpers\Traits\PermissionBasedAccess;
use CustomD\LaravelHelpers\Models\Scopes\PermissionBasedAccessScope;

trait CrudPermissions
{
    use HandlesAuthorization;

    public function can(Authenticatable&Authorizable $user, string $action, ?Model $model = null): Response
    {
        $permission = collect([
            $this->permission_name ?? self::parsePermissionNameFromPolicy(),
            $action
        ])->filter()->implode(".");

        if ($model) {
            $can = $this->canOnModel($user, $action, $model);
            if ($can === true) {
                return $can;
            }
            $can = $this->canOnModelField($user, $permission, $model);
            if ($can === true) {
                return $can;
            }
        }

        return $user->can($permission) ? $this->allow() : $this->deny();
    }


    protected function canOnModel(Authenticatable&Authorizable $user, string $action, Model $model): ?bool
    {

        // if we are using the permission based access, this should allow us to lock to the owner user.
        if (! in_array(PermissionBasedAccess::class, class_uses_recursive($model)) && (! method_exists($model, 'getOwnerKeyColumn') || $model->getOwnerKeyColumn() === null)) {
            return null;
        }

        $permission = $this->permission_name ?? self::parsePermissionNameFromPolicy();
        if ($action === 'view' || $action === 'viewAny') {
            $permission = $permission . '.viewOwn';
        }
        if ($action === 'update') {
            $permission = $permission . '.updateOwn';
        }
        if ($action === 'delete') {
            $permission = $permission . '.deleteOwn';
        }

        if ($model->getOwnerKeyColumn() === $user->getAuthIdentifier()) {
            return $user->can($permission);
        }

        return null;
    }

    /**
     * @still in development - not yet tested nor used and can change at any given time.
     */
    protected function canOnModelField(Authenticatable&Authorizable $user, string $permission, Model $model): ?bool
    {
        if (property_exists($this, 'modelField') === false || $this->modelField === false) {
            return null;
        }

        if ($this->modelField === true || $this->modelField === '*') {
            $permission .= ".*";
        } else {
            $permission .= "." . $model->getAttribute($this->modelField);
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

    public function viewAny(Authenticatable&Authorizable $user): Response
    {
        return $this->can($user, 'viewAny');
    }


    public function view(Authenticatable&Authorizable $user, Model $model): Response
    {
        return $this->can($user, 'view');
    }

    public function create(Authenticatable&Authorizable $user): Response
    {
        return $this->can($user, 'create');
    }

    public function update(Authenticatable&Authorizable $user, Model $model): Response
    {
        return $this->can($user, 'update');
    }

    public function delete(Authenticatable&Authorizable $user, Model $model): Response
    {
        return $this->can($user, 'delete');
    }

    public function forceDelete(Authenticatable&Authorizable $user, Model $model): Response
    {
        return $this->can($user, 'forceDelete');
    }

    public function restore(Authenticatable&Authorizable $user, Model $model): Response
    {
        return $this->can($user, 'restore');
    }
}
