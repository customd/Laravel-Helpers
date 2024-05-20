<?php

namespace CustomD\LaravelHelpers\Traits;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

trait SeedsPermissions
{
    /**
     * @var array<string, \Spatie\Permission\Models\Role>
     */
    protected static array $cachedRoles = [];

    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $this->seeding();

        if (property_exists($this, 'roles')) {
            $this->seedRoles();
        }
        if (property_exists($this, 'permissions')) {
            $this->seedPermissions();
        }
        if (property_exists($this, 'removeRoles')) {
            $this->removeRoles();
        }
        if (property_exists($this, 'removePermissions')) {
            $this->removePermissions();
        }

        $this->seeded();
    }

    protected function seedPermissions(): void
    {
        foreach ($this->permissions as $permission => $roles) {
            $rolePermissions = Permission::firstOrCreate(['name' => $permission]);
            foreach ($roles as $roleName) {
                $this->getRole($roleName)->givePermissionTo($rolePermissions);
            }
        }
    }

    protected function seedRoles(): void
    {
        foreach ($this->roles as $roleName) {
            $this->getRole($roleName);
        }
    }

    protected function getRole(string $roleName): Role
    {
        if (! array_key_exists($roleName, static::$cachedRoles)) {
            static::$cachedRoles[$roleName] = Role::firstOrCreate(['name' => $roleName]);
        }
        return static::$cachedRoles[$roleName];
    }

    protected function seeding(): void
    {
    }

    protected function seeded(): void
    {
    }


    protected function removeRoles(): void
    {
        foreach ($this->removeRoles as $role) {
            $role = Role::findByName($role);
            if ($role) {
                $role->delete();
            }
        }
    }

    protected function removePermissions(): void
    {
        foreach ($this->removePermissions as $permission => $roles) {
            $rolePermissions = Permission::findByName($permission);
            foreach ($roles as $roleName) {
                $this->getRole($roleName)->revokePermissionTo($rolePermissions);
            }
        }
    }
}
