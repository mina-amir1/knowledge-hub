<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name' => 'User Test',
            'email' => 'user@test.com',
            'password'=> Hash::make('password1')
        ]);
        $user->assignRole('user');
        $user->save();

        $admin = User::create([
            'name' => 'Admin Test',
            'email' => 'admin@test.com',
            'password'=> Hash::make('password1')
        ]);
        $admin->super_admin = true;
        $admin->assignRole('admin');
        $admin->save();

    }
}
