<div
    style="border:1px solid var(--border);border-radius:10px;padding:16px;margin-bottom:14px;position:relative;background:var(--bg);">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;">
        <span style="font-weight:700;font-size:13px;color:var(--purple);">
            <i class="fas fa-circle-question"></i> Soal #<span class="soal-num">{{ $i + 1 }}</span>
        </span>
        <button type="button" onclick="hapusSoal(this)"
            style="background:none;border:none;color:var(--danger);cursor:pointer;font-size:14px;" title="Hapus soal">
            <i class="fas fa-trash"></i>
        </button>
    </div>

    <div class="form-group">
        <label style="font-size:12px;">Pertanyaan <span style="color:var(--danger);">*</span></label>
        <textarea name="soal[{{ $i }}][soal]" rows="2" class="form-control" placeholder="Tulis pertanyaan di sini..."
            required>{{ $s['soal'] ?? '' }}</textarea>
    </div>

    <div class="form-group">
        <label style="font-size:12px;">Gambar Soal (opsional)</label>
        <input type="file" name="soal[{{ $i }}][gambar_soal]" class="form-control" accept="image/*">
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
        @foreach(['a', 'b', 'c', 'd'] as $opt)
            <div class="form-group" style="margin-bottom:8px;">
                <label style="font-size:12px;">Pilihan {{ strtoupper($opt) }} <span
                        style="color:var(--danger);">*</span></label>
                <input type="text" name="soal[{{ $i }}][pilihan_{{ $opt }}]" class="form-control"
                    placeholder="Pilihan {{ strtoupper($opt) }}" value="{{ $s['pilihan_' . $opt] ?? '' }}" required>
            </div>
        @endforeach
    </div>

    <div class="form-group" style="margin-bottom:0;">
        <label style="font-size:12px;">Jawaban Benar <span style="color:var(--danger);">*</span></label>
        <select name="soal[{{ $i }}][jawaban_benar]" class="form-control" required>
            <option value="">-- Pilih --</option>
            <option value="A" {{ (isset($s['jawaban_benar']) && $s['jawaban_benar'] == 'A') ? 'selected' : '' }}>A
            </option>
            <option value="B" {{ (isset($s['jawaban_benar']) && $s['jawaban_benar'] == 'B') ? 'selected' : '' }}>B
            </option>
            <option value="C" {{ (isset($s['jawaban_benar']) && $s['jawaban_benar'] == 'C') ? 'selected' : '' }}>C
            </option>
            <option value="D" {{ (isset($s['jawaban_benar']) && $s['jawaban_benar'] == 'D') ? 'selected' : '' }}>D
            </option>
        </select>
    </div>
</div>