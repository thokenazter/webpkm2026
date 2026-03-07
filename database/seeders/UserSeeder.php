<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Thoken Azter',
            'email' => 'admin@admin.com',
            'password' => bcrypt('12121212'),
            'role' => 'super_admin',
            'approved_at' => 'now',
        ]);
        User::create([
            'name' => 'Makdalena Ilely, S.Kep.,Ns',
            'email' => 'user1@admin.com',
            'password' => bcrypt('12121212'),
            'role' => 'user',
            'approved_at' => 'now',
        ]);
        User::create([
            'name' => 'Cindy Claudia Latusanay, A.Md.Kes',
            'email' => 'user2@admin.com',
            'password' => bcrypt('12121212'),
            'role' => 'user',
            'approved_at' => 'now',
        ]);
        User::create([
            'name' => 'Kardioka Silaban, A.Md.Keb',
            'email' => 'user3@admin.com',
            'password' => bcrypt('12121212'),
            'role' => 'user',
            'approved_at' => 'now',
        ]);
    }
}
