<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestUsersSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name'        => 'Director',
                'email'       => 'director@tto.northsouth.edu',
                'phone'       => '01711000002',
                'role'        => 'director',
                'department'  => 'Technology Transfer Office',
                'designation' => 'Director',
            ],
            [
                'name'        => 'TTO Officer',
                'email'       => 'tto.officer@tto.northsouth.edu',
                'phone'       => '01711000003',
                'role'        => 'tto_officer',
                'department'  => 'Technology Transfer Office',
                'designation' => 'Technology Transfer Officer',
            ],
            [
                'name'        => 'Legal Officer',
                'email'       => 'legal.officer@tto.northsouth.edu',
                'phone'       => '01711000004',
                'role'        => 'legal_officer',
                'department'  => 'Legal Affairs',
                'designation' => 'Legal Officer',
            ],
            [
                'name'        => 'Reviewer',
                'email'       => 'reviewer@tto.northsouth.edu',
                'phone'       => '01711000005',
                'role'        => 'reviewer',
                'department'  => 'Research & Innovation',
                'designation' => 'IP Reviewer',
            ],
            [
                'name'        => 'Faculty Member',
                'email'       => 'faculty@northsouth.edu',
                'phone'       => '01711000006',
                'role'        => 'faculty',
                'department'  => 'Computer Science & Engineering',
                'designation' => 'Associate Professor',
            ],
            [
                'name'        => 'Staff Member',
                'email'       => 'staff@northsouth.edu',
                'phone'       => '01711000007',
                'role'        => 'staff',
                'department'  => 'Research & Innovation',
                'designation' => 'Research Associate',
            ],
            [
                'name'        => 'Student',
                'email'       => 'student@northsouth.edu',
                'phone'       => '01711000008',
                'role'        => 'student',
                'department'  => 'Computer Science & Engineering',
                'designation' => null,
            ],
        ];

        foreach ($users as $data) {
            User::updateOrCreate(
                ['email' => $data['email']],
                [
                    ...$data,
                    'password'          => Hash::make('Test@1234'),
                    'is_active'         => true,
                    'email_verified_at' => now(),
                ]
            );
        }

        $this->command->newLine();
        $this->command->info('─────────────────────────────────────────────────────────────────────');
        $this->command->info('  Test Users (password: Test@1234 for all)');
        $this->command->info('─────────────────────────────────────────────────────────────────────');
        $this->command->table(
            ['Role', 'Email', 'Phone'],
            collect($users)->map(fn ($u) => [$u['role'], $u['email'], $u['phone']])->toArray()
        );
        $this->command->newLine();
    }
}
