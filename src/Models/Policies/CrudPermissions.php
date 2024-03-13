<?php

namespace CustomD\LaravelHelpers\Models\Policies;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Illuminate\Database\Eloquent\InvalidCastException;
use Illuminate\Database\Eloquent\MissingAttributeException;
use Illuminate\Database\LazyLoadingViolationException;
use Illuminate\Support\Facades\Gate;
use LogicException;

trait CrudPermissions
{

    public function can(Authenticatable&Authorizable $user, string $action, ?Model $model = null): bool
    {
        $permission = collect([
            $this->permission_name ?? self::parsePermissionNameFromPolicy(),
            $action
        ])->filter()->implode(".");

        if ($model) {
            $can = $this->canOnModel($user, $permission, $model);
            if ($can === true) {
                return $can;
            }
            $can = $this->canOnModelField($user, $permission, $model);
            if ($can === true) {
                return $can;
            }
        }

        return $user->can($permission);
    }

    /**
     * @still in development
     */
    protected function canOnModel(Authenticatable&Authorizable $user, string $permission, Model $model): ?true
    {

        if (property_exists($this, 'ownerIdColumn') === false) {
            return null;
        }

        $ownerId = $model->getAttribute($this->ownerIdColumn);
        if ($ownerId === $user->getAuthIdentifier()) {
            return true;
        }

        return null;
    }

    /**
     * @still in development
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

    public function __call($method, $parameters)
    {
        return $this->can($parameters[0], $method, $parameters[1] ?? null);
    }
}
