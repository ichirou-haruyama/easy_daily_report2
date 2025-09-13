<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 管理者アカウント
        User::create([
            'email' => 'admin@example.com',
            'password' => Hash::make(Str::random(12)), // 自動発行
            'role' => 1, // 管理者
        ]);

        // 一般ユーザーアカウント（例: 3件）
        User::create([
            'email' => 'user1@example.com',
            'password' => Hash::make(Str::random(12)),
            'role' => 0, // 一般
        ]);

        User::create([
            'email' => 'user2@example.com',
            'password' => Hash::make(Str::random(12)),
            'role' => 0,
        ]);

        User::create([
            'email' => 'user3@example.com',
            'password' => Hash::make(Str::random(12)),
            'role' => 0,
        ]);
    }
}
