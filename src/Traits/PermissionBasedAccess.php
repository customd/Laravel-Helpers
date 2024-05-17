<?php


namespace CustomD\LaravelHelpers\Traits;

use CustomD\LaravelHelpers\Models\Scopes\PermissionBasedAccessScope;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable;

trait PermissionBasedAccess
{
    protected static function bootPermissionBasedAccess(): void
    {
        static::addGlobalScope(new PermissionBasedAccessScope());
    }

    protected function getOwnerKeyColumn(): string
    {
        return 'user_id';
    }

    protected function getPermissionKey(): string
    {
        return $this->getTable();
    }

     /**
     * @param Builder<self> $query
     */
    protected function scopeAccessForbidden(Builder $query): void
    {
        $query->whereNull($this->getOwnerKeyColumn());
    }

     /**
     * @param Builder<self> $query
     */
    protected function scopeUserAccessAllowed(Builder $query): void
    {
        $query->where($this->getOwnerKeyColumn(), auth()->id());
    }

     /**
     * @param Builder<self> $query
     */
    protected function scopeFullAccessAllowed(Builder $query): void
    {
        // do nothing here :-)
    }

    /**
     * @return array{canView:bool, canViewOwn:bool}
     */
    protected function getPermissionKeys(Authenticatable $user): array
    {
        throw_unless($user instanceof Authorizable, 'InvalidArgumentException', 'User must implement Authorizable');

        $tablePermissionKey = $this->getPermissionKey();

        $viewAny = "{$tablePermissionKey}.viewAny";
        $view = "{$tablePermissionKey}.view";
        $viewOwn = "{$tablePermissionKey}.viewOwn";

        // what if a specific permission does not exist?
        $canViewAny = Gate::has($viewAny) ? $user->can($viewAny) : true;
        $canView = Gate::has($view) ? $user->can($view) : $canViewAny;
        $canViewOwn = Gate::has($viewOwn) ? $user->can($viewOwn) : true;

        return [
            'canView'    => $canView,
            'canViewOwn' => $canViewOwn,
        ];
    }

    /**
     * @return array{canView:bool, canViewOwn:bool}
     */
    public function getViewPermissionsFromGate(): array
    {
        $user = auth()->user();

        if (is_null($user)) {
            return [
                'canView'    => false,
                'canViewOwn' => false,
            ];
        }

        return $this->getPermissionKeys($user);
    }
}
