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
        $canView    = $permissions['canView'];
        $canViewOwn = $permissions['canViewOwn'];

        // no permissions at all
        if (! $canView  && ! $canViewOwn) {
            $builder->accessForbidden(); //@phpstan-ignore-line => scope from trait
            return;
        }

        if ($canView && ! $canViewOwn) {
            $builder->fullAccessAllowed(); //@phpstan-ignore-line => scope from trait
            return;
        }

        $builder->userAccessAllowed(); //@phpstan-ignore-line => scope from trait
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
