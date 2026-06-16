<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>e-Raport - {{ $raport->siswa->nama_lengkap }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; line-height: 1.5; color: #333; }
        .header { text-align: center; border-bottom: 3px solid #333; padding-bottom: 10px; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 20px; text-transform: uppercase; }
        .header p { margin: 3px 0 0; }
        
        .identitas { width: 100%; margin-bottom: 20px; }
        .identitas td { padding: 4px; }
        
        .table-nilai { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .table-nilai th, .table-nilai td { border: 1px solid #666; padding: 8px; text-align: left; }
        .table-nilai th { background-color: #f0f0f0; text-align: center; }
        
        .absensi-box { width: 300px; border: 1px solid #666; border-collapse: collapse; }
        .absensi-box th, .absensi-box td { border: 1px solid #666; padding: 6px; }
        
        .ttd-box { width: 100%; margin-top: 50px; }
        .ttd-box td { text-align: center; width: 50%; }
    </style>
</head>
<body>

    <div class="header">
        <h1>{{ strtoupper(data_get($sekolah, 'nama_sekolah', config('app.name'))) }}</h1>
        <p>{{ data_get($sekolah, 'alamat', 'Jl. Penanggungan No. 1, Kec. Mojoroto, Kota Kediri') }}</p>
        <p><strong>E-RAPORT PESERTA DIDIK - SEMESTER {{ strtoupper($raport->semester) }}</strong></p>
    </div>

    <table class="identitas">
        <tr>
            <td width="20%">Nama Peserta Didik</td><td width="2%">:</td><td width="48%"><strong>{{ $raport->siswa->nama_lengkap }}</strong></td>
            <td width="15%">Kelas / Tingkat</td><td width="2%">:</td><td width="13%">{{ $raport->siswa->kelas->nama_kelas ?? '-' }}</td>
        </tr>
        <tr>
            <td>NISN</td><td>:</td><td>{{ $raport->siswa->nisn ?? '-' }}</td>
            <td>Tahun Ajaran</td><td>:</td><td>{{ $raport->tahun_ajaran }}</td>
        </tr>
    </table>

    <table class="table-nilai">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="30%">Mata Pelajaran</th>
                <th width="10%">Tugas</th>
                <th width="10%">UTS</th>
                <th width="10%">UAS</th>
                <th width="10%">Nilai Akhir</th>
                <th width="10%">KKM</th>
            </tr>
        </thead>
        <tbody>
            @foreach($nilai as $i => $n)
            <tr>
                <td style="text-align: center;">{{ $i + 1 }}</td>
                <td>{{ $n->mataPelajaran->nama_mapel ?? ($n->mataPelajaran->nama ?? 'Mapel') }}</td>
                <td style="text-align:center;">{{ $n->nilai_tugas ?? '-' }}</td>
                <td style="text-align:center;">{{ $n->nilai_uts ?? '-' }}</td>
                <td style="text-align:center;">{{ $n->nilai_uas ?? '-' }}</td>
                <td style="text-align:center;"><strong>{{ $n->nilai_akhir ?? '-' }}</strong></td>
                <td style="text-align:center;">{{ $n->mataPelajaran->kkm ?? 75 }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <table class="absensi-box">
        <tr>
            <th colspan="2" style="background-color: #f0f0f0;">Ketidakhadiran Semester Ini</th>
        </tr>
        <tr>
            <td width="60%">Sakit</td>
            <td width="40%" style="text-align: center;">{{ $absensi['sakit'] }} Hari</td>
        </tr>
        <tr>
            <td>Izin</td>
            <td style="text-align: center;">{{ $absensi['izin'] }} Hari</td>
        </tr>
        <tr>
            <td>Alpha (Tanpa Keterangan)</td>
            <td style="text-align: center;">{{ $absensi['alpha'] }} Hari</td>
        </tr>
    </table>

    <table class="ttd-box">
        <tr>
            <td>
                Mengetahui,<br>
                Wali Murid
                <br><br><br><br><br>
                ( ......................................... )
            </td>
            <td>
                Kediri, {{ date('d F Y') }}<br>
                Wali Kelas
                <br><br><br><br><br>
                <strong>( Guru Wali Kelas )</strong><br>
                NIP. .....................................
            </td>
        </tr>
    </table>

</body>
</html>