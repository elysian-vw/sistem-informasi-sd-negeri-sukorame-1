<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sekolah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SekolahController extends Controller
{
    public function index()
    {
        $sekolah = Sekolah::first() ?? new Sekolah();
        return view('admin.sekolah.index', compact('sekolah'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'npsn'               => 'nullable|string|max:20',
            'nama_sekolah'       => 'required|string|max=255',
            'alamat'             => 'nullable|string',
            'kelurahan'          => 'nullable|string|max=100',
            'kecamatan'          => 'nullable|string|max=100',
            'kota'               => 'nullable|string|max=100',
            'provinsi'           => 'nullable|string|max=100',
            'kode_pos'           => 'nullable|string|max=10',
            'status_sekolah'     => 'nullable|in:Negeri,Swasta',
            'jenjang'            => 'nullable|in:SD,SMP,SMA,SMK',
            'tahun_berdiri'      => 'nullable|digits:4',
            'telepon'            => 'nullable|string|max=20',
            'email'              => 'nullable|email|max=100',
            'website'            => 'nullable|url|max=255',
            'nama_kepala_sekolah'=> 'nullable|string|max:255',
            'nip_kepala_sekolah' => 'nullable|string|max:30',
            'foto_kepsek'        => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'sambutan_kepsek'    => 'nullable|string',
            'nama_singkat'       => 'nullable|string|max:50',
            'slogan'             => 'nullable|string|max:255',
            'logo'               => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'akreditasi'         => 'nullable|in:A,B,C,Belum Terakreditasi',
            'tahun_akreditasi'   => 'nullable|digits:4',
            'nomor_sk_akreditasi'=> 'nullable|string|max:100',
            'nilai_akreditasi'   => 'nullable|numeric|min:0|max:100',
            'facebook'           => 'nullable|url|max:255',
            'instagram'          => 'nullable|url|max:255',
            'youtube'            => 'nullable|url|max:255',
            'tahun_ajaran_aktif' => 'nullable|string|max:20',
            'semester_aktif'     => 'nullable|string|max:20',
            ]);

            $sekolah = Sekolah::first() ?? new Sekolah();
            $data = $request->except(['_token', '_method', 'logo', 'foto_kepsek']);

            // Handle upload logo
            if ($request->hasFile('logo')) {
            if ($sekolah->logo && Storage::disk('public')->exists($sekolah->logo)) {
                Storage::disk('public')->delete($sekolah->logo);
            }
            $data['logo'] = $request->file('logo')->store('logo', 'public');
            }

            // Handle upload foto kepsek
            if ($request->hasFile('foto_kepsek')) {
            if ($sekolah->foto_kepsek && Storage::disk('public')->exists($sekolah->foto_kepsek)) {
                Storage::disk('public')->delete($sekolah->foto_kepsek);
            }
            $data['foto_kepsek'] = $request->file('foto_kepsek')->store('sekolah', 'public');
            }


        $sekolah->fill($data)->save();

        return redirect()->route('admin.sekolah')->with('success', 'Data sekolah berhasil disimpan.');
    }
}