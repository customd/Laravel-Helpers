<?php

namespace CustomD\LaravelHelpers\Models\Scopes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Builder;

class PermissionBasedAccessScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     * @phpstan-ignore missingType.generics
     */
    public function apply(Builder $builder, Model $model): void
    {
        if (is_null(auth()->id())) {
            return;
        }

        $permissions = $model->getViewPermissionsFromGate(); //@phpstan-ignore-line => method from trait

        if ($permissions['canViewOwn']) {
            $builder->canRetrieveOwnRecord();
            return;
        }
        if ($permissions['canView']) {
            $builder->canRetrieveAnyRecord();
            return;
        }

        $builder->cannotRetrieveAnyRecord(); //@phpstan-ignore-line => scope from trait
    }


    /**
     * @phpstan-ignore missingType.generics
     */
    public function extend(Builder $builder): void
    {
        $builder->macro('withoutPermissionCheck', function (Builder $builder) {
            return $builder->withoutGlobalScope($this);
        });
    }
}
