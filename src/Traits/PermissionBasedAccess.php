<?php


namespace CustomD\LaravelHelpers\Traits;

use CustomD\LaravelHelpers\Models\Scopes\PermissionBasedAccessScope;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable;

/**
 * @phpstan-ignore trait.unused
 */
trait PermissionBasedAccess
{
    protected static function bootPermissionBasedAccess(): void
    {
        static::addGlobalScope(new PermissionBasedAccessScope());
    }

    public function getOwnerKeyColumn(): ?string
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
    protected function scopeCannotRetrieveAnyRecord(Builder $query): void
    {
        //primary key cannot be null, this will force an empty result set
        $query->whereNull($this->getKeyName());
    }

     /**
     * @param Builder<self> $query
     */
    protected function scopeCanRetrieveOwnRecord(Builder $query): void
    {
        $query->where($this->getOwnerKeyColumn(), auth()->id());
    }

     /**
     * @param Builder<self> $query
     */
    protected function scopeCanRetrieveAnyRecord(Builder $query): void
    {
        // do nothing here unless you want to limit it in your own implementation
        // by overriding this method in your model.
    }

    /**
     * @return array{canView:bool, canViewOwn:bool}
     */
    protected function getPermissionKeys(Authenticatable $user): array
    {
        throw_unless($user instanceof Authorizable, 'InvalidArgumentException', 'User must implement Authorizable');

        $tablePermissionKey = $this->getPermissionKey();

        $view = "{$tablePermissionKey}.view";
        $viewOwn = "{$tablePermissionKey}.viewOwn";

        // what if a specific permission does not exist?
        $canView = $user->can($view);
        $canViewOwn = $user->can($viewOwn);

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
