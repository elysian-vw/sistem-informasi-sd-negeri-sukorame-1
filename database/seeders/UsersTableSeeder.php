<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        DB::table('users')->insert([
            'name'       => 'Administrator',
            'email'      => 'admin@sekolah.com',
            'password'   => Hash::make('password'),
            'role'       => 'admin',
            'foto'       => null,
            'no_hp'      => '081234567890',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Guru (5 orang)
        $guru = [
            ['Budi Santoso', 'guru.budi@sekolah.com', '081234567891'],
            ['Siti Aminah',  'guru.siti@sekolah.com', '081234567892'],
            ['Agus Wijaya',  'guru.agus@sekolah.com', '081234567893'],
            ['Dewi Lestari', 'guru.dewi@sekolah.com', '081234567894'],
            ['Eko Prasetyo', 'guru.eko@sekolah.com',  '081234567895'],
        ];
        foreach ($guru as $g) {
            DB::table('users')->insert([
                'name'       => $g[0],
                'email'      => $g[1],
                'password'   => Hash::make('password'),
                'role'       => 'guru',
                'no_hp'      => $g[2],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Wali Murid (10 orang)
        for ($i = 1; $i <= 10; $i++) {
            DB::table('users')->insert([
                'name'       => "Wali Murid $i",
                'email'      => "wali$i@example.com",
                'password'   => Hash::make('password'),
                'role'       => 'wali_murid',
                'no_hp'      => '0812' . rand(10000000, 99999999),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Siswa (30 orang)
        for ($i = 1; $i <= 30; $i++) {
            DB::table('users')->insert([
                'name'       => "Siswa $i",
                'email'      => "siswa$i@sekolah.com",
                'password'   => Hash::make('password'),
                'role'       => 'siswa',
                'no_hp'      => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}