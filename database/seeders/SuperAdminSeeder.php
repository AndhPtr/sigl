<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /** 
     * Run the database seeds. 
     */
    public function run(): void
    {
        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin']);
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $userRole = Role::firstOrCreate(['name' => 'User']);
        // Creating Super Admin User 
        $superAdmin = User::create([
            'name' => 'Rivo',
            'email' => 'superadmin@roles.id',
            'roles_id' => $superAdminRole->id,
            'password' => Hash::make('123456')
        ]);
        $superAdmin->assignRole('Super Admin');
        // Creating Admin User 
        $admin = User::create([
            'name' => 'Meri',
            'email' => 'admin@roles.id',
            'roles_id' => $adminRole->id,
            'password' => Hash::make('123456')
        ]);
        $admin->assignRole('Admin');
        $user = User::create([
            'name' => 'Dikal',
            'email' => 'user@roles.id',
            'roles_id' => $userRole->id,
            'password' => Hash::make('123456')
        ]);
        $user->assignRole('User');
    }
}
