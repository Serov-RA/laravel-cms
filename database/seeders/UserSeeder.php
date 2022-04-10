<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $password = Str::random(10);
        $email    = Str::random(10).'@gmail.com';

        $this->command->info('Email: '.$email);
        $this->command->info('Password: '.$password);

        $role = Role::create([
            'name' => 'Admin',
            'is_admin' => true,
        ]);

        User::create([
            'name' => 'Administrator',
            'email' => $email,
            'password' => Hash::make($password),
            'role_id' => $role->id,
            'phone' => '112',
        ]);
    }
}
