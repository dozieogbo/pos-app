<?php

use App\Role;
use App\User;
use Illuminate\Database\Seeder;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::insert([
            ['name' => 'super', 'description' => 'Super Admin'],
            ['name' => 'admin', 'description' => 'Admin'],
            ['name' => 'sales', 'description' => 'Sales Department'],
            ['name' => 'supervisor', 'description' => 'Supervisor']
        ]);

        $super = Role::where('name', 'super')->first();
        $admin = Role::where('name', 'admin')->first();

        $superUser = User::create([
            'name' => 'Super Admin',
            'password' => bcrypt('12afrivelle345.'),
            'gender' => 'male',
            'is_active' => 1
        ]);

        $adminUser = User::create([
            'name' => 'Default Admin',
            'password' => bcrypt('12password345.'),
            'gender' => 'male',
            'is_active' => 1,
            'staff_id' => 'ADM001'
        ]);

        $superUser->roles()->save($super);
        $adminUser->roles()->save($admin);
    }
}
