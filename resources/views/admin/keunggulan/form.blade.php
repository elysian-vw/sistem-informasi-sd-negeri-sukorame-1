{{-- Partial: admin/keunggulan/_form.blade.php --}}
{{-- Dipakai oleh create.blade.php dan edit.blade.php --}}

@if($errors->any())
<div style="background:#fef2f2;color:#dc2626;padding:12px 16px;border-radius:8px;margin-bottom:20px;border:1px solid #fecaca;font-size:13px;">
    <ul style="margin:0;padding-left:16px;">
        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
    </ul>
</div>
@endif

{{-- Icon --}}
<div class="form-group" style="margin-bottom:18px;">
    <label class="form-label" style="font-weight:600;font-size:13.5px;color:#374151;display:block;margin-bottom:6px;">
        Icon <span style="color:#ef4444;">*</span>
        <a href="https://fontawesome.com/v5/search?m=free" target="_blank" style="font-size:11px;font-weight:400;margin-left:8px;color:#2563eb;">
            <i class="fas fa-external-link-alt"></i> Cari icon FA5
        </a>
    </label>
    <div style="display:flex;gap:10px;align-items:center;">
        <input type="text" name="icon" id="iconInput"
               class="form-control" style="flex:1;"
               value="{{ old('icon', $keunggulan->icon ?? 'fa-star') }}"
               placeholder="contoh: fa-award, fa-trophy, fa-heart"
               oninput="updatePreview()">
        <div id="iconPreview" style="width:44px;height:44px;background:#eff6ff;border-radius:10px;display:flex;align-items:center;justify-content:center;border:1px solid #bfdbfe;flex-shrink:0;">
            <i class="fa {{ old('icon', $keunggulan->icon ?? 'fa-star') }} text-blue-600" id="previewIcon"></i>
        </div>
    </div>
</div>

{{-- Judul --}}
<div class="form-group" style="margin-bottom:18px;">
    <label class="form-label" style="font-weight:600;font-size:13.5px;color:#374151;display:block;margin-bottom:6px;">
        Judul <span style="color:#ef4444;">*</span>
    </label>
    <input type="text" name="title" class="form-control"
           value="{{ old('title', $keunggulan->title ?? '') }}"
           placeholder="contoh: Sekolah Unggulan" maxlength="200" required>
</div>

{{-- Deskripsi --}}
<div class="form-group" style="margin-bottom:18px;">
    <label class="form-label" style="font-weight:600;font-size:13.5px;color:#374151;display:block;margin-bottom:6px;">Deskripsi Singkat</label>
    <input type="text" name="description" class="form-control"
           value="{{ old('description', $keunggulan->description ?? '') }}"
           placeholder="contoh: Terbaik di Kota Kediri" maxlength="500">
    <small style="color:#9ca3af;font-size:12px;">Maks. 500 karakter.</small>
</div>

{{-- Warna --}}
<div class="form-group" style="margin-bottom:18px;">
    <label class="form-label" style="font-weight:600;font-size:13.5px;color:#374151;display:block;margin-bottom:6px;">
        Warna <span style="color:#ef4444;">*</span>
    </label>
    <select name="color" class="form-control" required>
        @php
            $colors = [
                'blue'   => 'Biru',
                'indigo' => 'Indigo',
                'purple' => 'Ungu',
                'amber'  => 'Amber / Kuning',
                'pink'   => 'Pink',
                'teal'   => 'Teal / Hijau Tosca',
                'green'  => 'Hijau',
                'red'    => 'Merah',
            ];
            $current = old('color', $keunggulan->color ?? 'blue');
        @endphp
        @foreach($colors as $val => $label)
            <option value="{{ $val }}" {{ $current === $val ? 'selected' : '' }}>{{ $label }}</option>
        @endforeach
    </select>
</div>

{{-- Urutan --}}
<div class="form-group" style="margin-bottom:18px;">
    <label class="form-label" style="font-weight:600;font-size:13.5px;color:#374151;display:block;margin-bottom:6px;">Urutan Tampil</label>
    <input type="number" name="urutan" class="form-control" style="width:120px;"
           value="{{ old('urutan', $keunggulan->urutan ?? 0) }}" min="0" max="99">
    <small style="color:#9ca3af;font-size:12px;">Angka lebih kecil tampil lebih awal.</small>
</div>

{{-- Status --}}
<div class="form-group" style="margin-bottom:4px;">
    <label style="display:flex;align-items:center;gap:10px;cursor:pointer;">
        <input type="hidden" name="is_aktif" value="0">
        <input type="checkbox" name="is_aktif" value="1"
               {{ old('is_aktif', $keunggulan->is_aktif ?? true) ? 'checked' : '' }}
               style="width:16px;height:16px;">
        <span style="font-weight:600;font-size:13.5px;color:#374151;">Tampilkan di beranda</span>
    </label>
</div>

@push('scripts')
<script>
function updatePreview() {
    const val = document.getElementById('iconInput').value.trim();
    const el  = document.getElementById('previewIcon');
    el.className = 'fa ' + (val || 'fa-star') + ' text-blue-600';
}
</script>
@endpush