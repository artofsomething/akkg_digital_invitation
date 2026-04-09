<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ✅ Manually provide UUID
        Role::firstOrCreate(
            ['name' => 'admin', 'guard_name' => 'web'],
            ['id' => Str::uuid()->toString()]           // ✅ Force UUID
        );

        Role::firstOrCreate(
            ['name' => 'user', 'guard_name' => 'web'],
            ['id' => Str::uuid()->toString()]           // ✅ Force UUID
        );

        $this->command->info('Roles created successfully!');
    }
}