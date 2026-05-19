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
        DB::table('users')->updateOrInsert(
            ['email' => 'admin@sekolah.com'], // Kondisi pencarian
            [
                'name'       => 'Administrator',
                'password'   => Hash::make('password'),
                'role'       => 'admin',
                'foto'       => null,
                'no_hp'      => '081234567890',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Kepala Sekolah (Tambahan Akun Baru)
        DB::table('users')->updateOrInsert(
            ['email' => 'kepsek@sekolah.sch.id'], // Kondisi pencarian
            [
                'name'       => 'H. Ahmad Sukorame, M.Pd',
                'password'   => Hash::make('password123'),
                'role'       => 'kepala_sekolah',
                'foto'       => null,
                'no_hp'      => '081234567800',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Guru (5 orang)
        $guru = [
            ['Budi Santoso', 'guru.budi@sekolah.com', '081234567891'],
            ['Siti Aminah',  'guru.siti@sekolah.com', '081234567892'],
            ['Agus Wijaya',  'guru.agus@sekolah.com', '081234567893'],
            ['Dewi Lestari', 'guru.dewi@sekolah.com', '081234567894'],
            ['Eko Prasetyo', 'guru.eko@sekolah.com',  '081234567895'],
        ];
        foreach ($guru as $g) {
            DB::table('users')->updateOrInsert(
                ['email' => $g[1]], // Kondisi pencarian
                [
                    'name'       => $g[0],
                    'password'   => Hash::make('password'),
                    'role'       => 'guru',
                    'no_hp'      => $g[2],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        // Wali Murid (10 orang)
        for ($i = 1; $i <= 10; $i++) {
            DB::table('users')->updateOrInsert(
                ['email' => "wali$i@example.com"], // Kondisi pencarian
                [
                    'name'       => "Wali Murid $i",
                    'password'   => Hash::make('password'),
                    'role'       => 'wali_murid',
                    'no_hp'      => '0812' . rand(10000000, 99999999),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        // Siswa (30 orang)
        for ($i = 1; $i <= 30; $i++) {
            DB::table('users')->updateOrInsert(
                ['email' => "siswa$i@sekolah.com"], // Kondisi pencarian
                [
                    'name'       => "Siswa $i",
                    'password'   => Hash::make('password'),
                    'role'       => 'siswa',
                    'no_hp'      => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}