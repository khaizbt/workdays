<?php

use Illuminate\Database\Seeder;
use App\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;
class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminRole = Role::create(['name' => 'Super']);
        $adminPermissions = ['manage-users', 'view-users', 'create-users', 'edit-users', 'delete-users'];
        foreach($adminPermissions as $ap)
        {
            $permission = Permission::create(['name' => $ap]);
            $adminRole->givePermissionTo($permission);
        }
        $adminUser = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@example.com',
            'password' => Hash::make('123456'),
            'level' => 1
        ]);
        $adminUser->assignRole($adminRole);

        $editorRole = Role::create(['name' => 'Admin']);
        $editorPermissions = ['manage-users', 'view-users'];
        foreach($editorPermissions as $ep)
        {
            $permission = Permission::firstOrCreate(['name' => $ep]);
            $editorRole->givePermissionTo($permission);
        }
        $editorUser = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('123456'),
            'level' => 2
        ]);
        $editorUser->assignRole($editorRole);

        $userRole = Role::create(['name' => 'User']);
        $generalUser = User::create([
            'name' => 'Employee',
            'email' => 'employee@example.com',
            'password' => Hash::make('123456'),
            'level' => 3
        ]);
        $generalUser->assignRole($userRole);
    }
}
