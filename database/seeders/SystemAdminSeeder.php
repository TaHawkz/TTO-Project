<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SystemAdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@tto.northsouth.edu'],
            [
                'name'              => 'System Administrator',
                'password'          => Hash::make('Admin@TTO2026'),
                'role'              => 'system_admin',
                'department'        => 'Technology Transfer Office',
                'phone'             => '01711000001',
                'is_active'         => true,
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('System admin created: admin@tto.northsouth.edu / Admin@TTO2026');
    }
}
