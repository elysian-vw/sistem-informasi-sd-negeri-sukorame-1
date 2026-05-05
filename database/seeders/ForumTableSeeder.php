<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ForumTableSeeder extends Seeder
{
    public function run(): void
    {
        $userId = DB::table('users')->where('role', 'guru')->value('id');
        $kelas  = DB::table('kelas')->pluck('id');

        $forum = [
            ['judul' => 'Diskusi Matematika', 'isi' => 'Bahasan soal pecahan', 'untuk' => 'kelas'],
            ['judul' => 'Pengumuman Lomba', 'isi' => 'Lomba OSN tingkat kabupaten', 'untuk' => 'semua'],
        ];

        foreach ($forum as $f) {
            $forumId = DB::table('forum')->insertGetId([
                'judul'    => $f['judul'],
                'isi'      => $f['isi'],
                'user_id'  => $userId,
                'kelas_id' => $f['untuk'] == 'kelas' ? $kelas->random() : null,
                'untuk'    => $f['untuk'],
                'is_pinned'=> false,
                'created_at'=> now(),
                'updated_at'=> now(),
            ]);

            // Komentar
            DB::table('forum_komentar')->insert([
                'forum_id'   => $forumId,
                'user_id'    => $userId,
                'isi'        => 'Terima kasih informasinya',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}