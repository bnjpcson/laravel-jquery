<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //


        //insert data

        //create role

        //superadmin

        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        $arrayOfPermissionNames = [
            "class-view",
            "class-add",
            "class-edit",
            "class-delete",
            "subjects-view",
            "subjects-add",
            "subjects-edit",
            "subjects-delete",
            "students-view",
            "students-add",
            "students-edit",
            "students-delete",
            "roles-view",
            "permissions-view",
            "roles-add"
        ];

        $permissions = collect($arrayOfPermissionNames)->map(function ($permission) {
            return ['name' => $permission, 'guard_name' => 'web'];
        });

        Permission::insert($permissions->toArray());


        // create roles and assign created permissions

        //superadmin
        $role = Role::create(['name' => 'superadmin']);
        $role->givePermissionTo(Permission::all());

        $superadmin = User::create([
            'name' => "ADMINISTRATOR",
            'email' => "administrator@example.com",
            'password' => Hash::make("Test@123"),
        ]);

        $superadmin->assignRole('superadmin');

        //admin
        $role = Role::create(['name' => 'admin']);
        $arrayOfPermissionNames = [
            "class-view",
            "class-add",
            "class-edit",
            "class-delete",
            "subjects-view",
            "subjects-add",
            "subjects-edit",
            "subjects-delete",
            "students-view",
            "students-add",
            "students-edit",
            "students-delete"
        ];
        $role->givePermissionTo($arrayOfPermissionNames);

        //user
        $role = Role::create(['name' => 'user']);
        $arrayOfPermissionNames = [
            "class-view",
            "subjects-view",
            "students-view",
        ];
        $role->givePermissionTo($arrayOfPermissionNames);
    }
}
