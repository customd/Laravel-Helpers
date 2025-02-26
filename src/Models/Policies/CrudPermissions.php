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

/**
 * @phpstan-ignore trait.unused
 */
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
            if ($this->canOnModel($user, $action, $model)) {
                return $this->allow();
            }
            if ($this->canOnModelField($user, $permission, $model)) {
                return $this->allow();
            }
        }

        return $user->can($permission) ? $this->allow() : $this->deny();
    }


    /**
     * allows us to check if the user can perform the action on the model before the global key,
     * only if true is returned will it override the other checks.
     */
    protected function canOnModel(Authenticatable&Authorizable $user, string $action, Model $model): bool
    {

        // if we are using the permission based access, this should allow us to lock to the owner user.
        // we only use this if the class us using the permissionBasedAccess Trait and the ownerKeyColumn is set. (this could be more advanced in the future)
        // also only keys onto the basic crud actions excluding create
        if (! in_array($action, [ 'view', 'update', 'delete'])  ||
            ! in_array(PermissionBasedAccess::class, class_uses_recursive($model)) ||
            $model->getOwnerKeyColumn() === null
        ) {
            return false;
        }

        $permission = $this->permission_name ?? self::parsePermissionNameFromPolicy();
        $permission = $permission . '.' . $action . 'Own'; //is there an {action}Own permission?

        if ($model->getAttribute($model->getOwnerKeyColumn()) === $user->getAuthIdentifier()) {
            return $user->can($permission);
        }

        return false;
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
        return $this->can($user, 'view', $model);
    }

    public function create(Authenticatable&Authorizable $user): Response
    {
        return $this->can($user, 'create');
    }

    public function update(Authenticatable&Authorizable $user, Model $model): Response
    {
        return $this->can($user, 'update', $model);
    }

    public function delete(Authenticatable&Authorizable $user, Model $model): Response
    {
        return $this->can($user, 'delete', $model);
    }

    public function forceDelete(Authenticatable&Authorizable $user, Model $model): Response
    {
        return $this->can($user, 'forceDelete', $model);
    }

    public function restore(Authenticatable&Authorizable $user, Model $model): Response
    {
        return $this->can($user, 'restore', $model);
    }
}
