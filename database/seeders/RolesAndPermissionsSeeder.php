<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'access_dashboard',

            // User management
            'view_users',
            'create_users',
            'edit_users',
            'delete_users',

            // Role management
            'view_roles',
            'create_roles',
            'edit_roles',
            'delete_roles',

            // Subject management
            'view_subjects',
            'create_subjects',
            'edit_subjects',
            'delete_subjects',

            // Course management
            'view_courses',
            'create_courses',
            'edit_courses',
            'delete_courses',
            'publish_courses',

            // Resource management
            'view_resources',
            'create_resources',
            'edit_resources',
            'delete_resources',
            'upload_resources',

            // Student actions
            'enroll_courses',
            'bookmark_resources',
            'download_resources',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Admin role
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo([
            'access_dashboard',
            'view_users', 'create_users', 'edit_users', 'delete_users',
            'view_roles', 'create_roles', 'edit_roles', 'delete_roles',
            'view_subjects', 'create_subjects', 'edit_subjects', 'delete_subjects',
            'view_courses', 'create_courses', 'edit_courses', 'delete_courses', 'publish_courses',
            'view_resources', 'create_resources', 'edit_resources', 'delete_resources', 'upload_resources',
        ]);

        // Teacher role
        $teacherRole = Role::create(['name' => 'teacher']);
        $teacherRole->givePermissionTo([
            'access_dashboard',
            'view_courses', 'create_courses', 'edit_courses',
            'view_resources', 'create_resources', 'edit_resources', 'delete_resources', 'upload_resources',
            'view_subjects',
        ]);

        // Student role
        $studentRole = Role::create(['name' => 'student']);
        $studentRole->givePermissionTo([
            'access_dashboard',
            'view_courses', 'view_resources',
            'enroll_courses', 'bookmark_resources', 'download_resources',
        ]);
    }
}
