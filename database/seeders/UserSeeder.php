<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create users and assign roles
        $users = [
            [
                'name' => 'Alice Admin',
                'email' => 'alice@example.com',
                'password' => Hash::make('password'),
                'roles' => ['Administrator'],
            ],
            [
                'name' => 'Bob Assistant',
                'email' => 'bob@example.com',
                'password' => Hash::make('password'),
                'roles' => ['Administrator Assistant', 'User'],
            ],
            [
                'name' => 'Charlie Analyst',
                'email' => 'charlie@example.com',
                'password' => Hash::make('password'),
                'roles' => ['Management Analyst'],
            ],
            [
                'name' => 'David Manager',
                'email' => 'david@example.com',
                'password' => Hash::make('password'),
                'roles' => ['Management Assistant', 'User'],
            ],
        ];

        // Loop through each user, create them, and attach roles
        foreach ($users as $userData) {
            $user = User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => $userData['password'],
            ]);

            // Attach roles based on names in the roles array
            $roleIds = Role::whereIn('role_name', $userData['roles'])->pluck('id');
            $user->roles()->attach($roleIds);
        }
    }
}
