<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JadwalPelajaran;
use App\Models\Kelas;
use App\Models\Guru;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function index(Request $request)
    {
        $query = JadwalPelajaran::with(['kelas', 'guru.user', 'mataPelajaran']);

        if ($request->filled('filter_hari'))   $query->where('hari', $request->filter_hari);
        if ($request->filled('filter_kelas'))  $query->where('kelas_id', $request->filter_kelas);
        if ($request->filled('filter_semester')) $query->where('semester', $request->filter_semester);

        $jadwals = $query->orderBy('hari')->orderBy('jam_ke')->get();
        $kelas   = Kelas::all();
        $gurus   = Guru::with('user')->get();
        $mapels  = MataPelajaran::orderBy('nama')->get();

        return view('admin.jadwal.index', compact('jadwals', 'kelas', 'gurus', 'mapels'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kelas_id'          => 'required',
            'guru_id'           => 'required',
            'mata_pelajaran_id' => 'required',
            'hari'              => 'required',
            'jam_ke'            => 'required|integer|min:1|max:9',
            'waktu_mulai'       => 'required',
            'waktu_selesai'     => 'required',
            'tahun_ajaran'      => 'required',
            'semester'          => 'required|in:1,2',
        ]);

        $validated['is_aktif'] = true;

        JadwalPelajaran::create($validated);
        return redirect()->back()->with('success', 'Jadwal berhasil ditambah!');
    }

    public function update(Request $request, JadwalPelajaran $jadwal)
    {
        $validated = $request->validate([
            'kelas_id'          => 'required',
            'guru_id'           => 'required',
            'mata_pelajaran_id' => 'required',
            'hari'              => 'required',
            'jam_ke'            => 'required|integer|min:1|max:9',
            'waktu_mulai'       => 'required',
            'waktu_selesai'     => 'required',
            'tahun_ajaran'      => 'required',
            'semester'          => 'required|in:1,2',
        ]);

        $jadwal->update($validated);
        return redirect()->back()->with('success', 'Jadwal berhasil diupdate!');
    }

    public function destroy(JadwalPelajaran $jadwal)
    {
        $jadwal->delete();
        return redirect()->back()->with('success', 'Jadwal dihapus!');
    }

    public function export()
    {
        $jadwals = JadwalPelajaran::with([
            'kelas', 'mataPelajaran', 'guru.user'
        ])->orderBy('hari')->orderBy('jam_ke')->get();
    
        $filename = 'jadwal_pelajaran_' . date('Ymd_His') . '.csv';
    
        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];
    
        $callback = function () use ($jadwals) {
            $file = fopen('php://output', 'w');
    
            // BOM agar Excel tidak salah baca karakter UTF-8
            fputs($file, "\xEF\xBB\xBF");
    
            // Header kolom
            fputcsv($file, [
                'ID', 'Kelas', 'Hari', 'Mata Pelajaran',
                'Guru', 'Jam Ke', 'Waktu Mulai', 'Waktu Selesai', 'Tahun Ajaran',
            ]);
    
            foreach ($jadwals as $j) {
                fputcsv($file, [
                    $j->id,
                    $j->kelas->nama_kelas      ?? '',
                    ucfirst($j->hari),
                    $j->mataPelajaran->nama_mapel ?? '',
                    $j->guru->user->name       ?? '',
                    $j->jam_ke,
                    substr($j->waktu_mulai,  0, 5),
                    substr($j->waktu_selesai, 0, 5),
                    $j->tahun_ajaran,
                ]);
            }
    
            fclose($file);
        };
    
        return response()->stream($callback, 200, $headers);
    }
    
    
    // ─── 2. DOWNLOAD TEMPLATE ────────────────────
    /**
     * Download file template CSV kosong untuk keperluan import.
     */
    public function template()
    {
        $filename = 'template_import_jadwal.csv';
    
        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];
    
        // Ambil referensi data agar user tahu ID yang valid
        $kelas  = \App\Models\Kelas::orderBy('nama_kelas')->get(['id', 'nama_kelas']);
        $mapels = \App\Models\MataPelajaran::orderBy('nama_mapel')->get(['id', 'nama_mapel']);
        $gurus  = \App\Models\Guru::with('user')->get();
    
        $callback = function () use ($kelas, $mapels, $gurus) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF");
    
            // Baris 1: Header utama (untuk import)
            fputcsv($file, [
                'kelas_id', 'hari', 'mata_pelajaran_id', 'guru_id',
                'jam_ke', 'waktu_mulai', 'waktu_selesai', 'tahun_ajaran',
            ]);
    
            // Baris 2: Contoh data
            fputcsv($file, [
                $kelas->first()?->id  ?? 1,
                'senin',
                $mapels->first()?->id ?? 1,
                $gurus->first()?->id  ?? 1,
                1,
                '07:00',
                '07:45',
                '2024/2025',
            ]);
    
            // Spasi pemisah
            fputcsv($file, []);
            fputcsv($file, ['--- REFERENSI ID KELAS ---']);
            fputcsv($file, ['id', 'nama_kelas']);
            foreach ($kelas as $k) {
                fputcsv($file, [$k->id, $k->nama_kelas]);
            }
    
            fputcsv($file, []);
            fputcsv($file, ['--- REFERENSI ID MATA PELAJARAN ---']);
            fputcsv($file, ['id', 'nama_mapel']);
            foreach ($mapels as $m) {
                fputcsv($file, [$m->id, $m->nama_mapel]);
            }
    
            fputcsv($file, []);
            fputcsv($file, ['--- REFERENSI ID GURU ---']);
            fputcsv($file, ['id', 'nama_guru']);
            foreach ($gurus as $g) {
                fputcsv($file, [$g->id, $g->user->name ?? '-']);
            }
    
            fclose($file);
        };
    
        return response()->stream($callback, 200, $headers);
    }
    
    
    // ─── 3. IMPORT ───────────────────────────────
    /**
     * Import jadwal dari file CSV.
     * Baris pertama dianggap header, dilewati.
     * Baris dengan kolom lebih dari 8 (referensi) diabaikan.
     */
    public function import(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt,xlsx,xls|max:2048',
        ], [
            'file.required' => 'File import wajib dipilih.',
            'file.mimes'    => 'Format file harus .csv, .xlsx, atau .xls.',
            'file.max'      => 'Ukuran file maksimal 2MB.',
        ]);
    
        $file = $request->file('file');
        $ext  = strtolower($file->getClientOriginalExtension());
    
        // ── Baca CSV ──
        if (in_array($ext, ['csv', 'txt'])) {
            $rows = array_map('str_getcsv', file($file->getRealPath()));
        }
        // ── Baca XLSX (butuh package PhpSpreadsheet / Laravel Excel) ──
        elseif (in_array($ext, ['xlsx', 'xls'])) {
            // Pastikan sudah install: composer require maatwebsite/excel
            // Uncomment kode di bawah jika sudah install:
            //
            // $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getRealPath());
            // $sheet = $spreadsheet->getActiveSheet();
            // $rows  = $sheet->toArray();
    
            // Fallback sementara jika belum install PhpSpreadsheet:
            return redirect()->route('admin.jadwal.index')
                ->with('error', 'Import XLSX belum didukung. Gunakan format CSV.');
        }
    
        $imported = 0;
        $errors   = [];
    
        // Hari yang valid
        $hariValid = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu'];
    
        foreach ($rows as $rowIndex => $row) {
            // Skip header (baris pertama) dan baris kosong/referensi
            if ($rowIndex === 0)          continue; // header
            if (empty(array_filter($row))) continue; // baris kosong
            if (count($row) < 8)          continue; // baris referensi / tidak lengkap
            if (!is_numeric($row[0]))     continue; // baris judul referensi
    
            [$kelas_id, $hari, $mapel_id, $guru_id,
            $jam_ke, $waktu_mulai, $waktu_selesai, $tahun_ajaran] = $row;
    
            $lineNum = $rowIndex + 1;
    
            // Validasi manual per baris
            if (!in_array(strtolower(trim($hari)), $hariValid)) {
                $errors[] = "Baris {$lineNum}: hari tidak valid ({$hari}).";
                continue;
            }
    
            // Cek relasi
            $kelasExists = \App\Models\Kelas::find((int)$kelas_id);
            $mapelExists = \App\Models\MataPelajaran::find((int)$mapel_id);
            $guruExists  = \App\Models\Guru::find((int)$guru_id);
    
            if (!$kelasExists) { $errors[] = "Baris {$lineNum}: kelas_id {$kelas_id} tidak ditemukan."; continue; }
            if (!$mapelExists) { $errors[] = "Baris {$lineNum}: mata_pelajaran_id {$mapel_id} tidak ditemukan."; continue; }
            if (!$guruExists)  { $errors[] = "Baris {$lineNum}: guru_id {$guru_id} tidak ditemukan."; continue; }
    
            // Insert / skip duplikat (hari + jam_ke + kelas sama)
            JadwalPelajaran::firstOrCreate(
                [
                    'kelas_id'        => (int)$kelas_id,
                    'hari'            => strtolower(trim($hari)),
                    'jam_ke'          => (int)$jam_ke,
                ],
                [
                    'mata_pelajaran_id' => (int)$mapel_id,
                    'guru_id'           => (int)$guru_id,
                    'waktu_mulai'       => trim($waktu_mulai),
                    'waktu_selesai'     => trim($waktu_selesai),
                    'tahun_ajaran'      => trim($tahun_ajaran) ?: '2024/2025',
                ]
            );
    
            $imported++;
        }
    
        if (!empty($errors)) {
            $errMsg = "Import selesai ({$imported} data berhasil). "
                . count($errors) . " baris dilewati: "
                . implode(' | ', array_slice($errors, 0, 3))
                . (count($errors) > 3 ? '...' : '');
            return redirect()->route('admin.jadwal.index')->with('error', $errMsg);
        }
    
        return redirect()->route('admin.jadwal.index')
            ->with('success', "Import berhasil! {$imported} jadwal ditambahkan.");
    }
}