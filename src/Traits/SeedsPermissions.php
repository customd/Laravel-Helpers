<?php

namespace namespace CustomD\LaravelHelpers\Traits;

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
}
