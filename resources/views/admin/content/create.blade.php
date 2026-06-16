@extends('layouts.app')
@section('title', 'Tambah Halaman Baru')

@push('styles')
<style>
    .form-group { margin-bottom: 20px; }
    .form-label { display: block; font-weight: 600; margin-bottom: 8px; font-size: 13.5px; color: #374151; }
    .form-control { width: 100%; padding: 10px 14px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; transition: border-color 0.2s; box-sizing: border-box; font-family: inherit; }
    .form-control:focus { outline: none; border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,0.1); }
    .form-text { font-size: 12px; color: #6b7280; margin-top: 6px; display: block; }
    .btn-group { display: flex; gap: 12px; margin-top: 30px; }
    .btn { padding: 10px 20px; border-radius: 6px; font-size: 13.5px; font-weight: 600; text-decoration: none; cursor: pointer; border: none; transition: background 0.2s; }
    .btn-primary { background: #2563eb; color: white; }
    .btn-primary:hover { background: #1d4ed8; }
    .btn-secondary { background: #f3f4f6; color: #4b5563; border: 1px solid #d1d5db; display: inline-flex; align-items: center; justify-content: center; }
    .btn-secondary:hover { background: #e5e7eb; }
</style>
@endpush

@section('content')
<div class="page-header" style="margin-bottom: 24px;">
    <h1 class="page-title">Tambah Halaman Baru</h1>
    <p class="page-subtitle">Daftarkan URL menu statis baru ke database agar isinya dinamis</p>
</div>

<div class="content-card">
    <div class="card-body" style="padding: 30px;">
        <form action="{{ route('admin.content.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="form-label">Judul Halaman <span style="color:#ef4444;">*</span></label>
                <input type="text" name="title" class="form-control" placeholder="Contoh: Program Ekstrakurikuler Sekolah" required>
            </div>

            <div class="form-group">
                <label class="form-label">Gambar Unggulan / Banner Halaman</label>
                <input type="file" name="image" class="form-control" accept="image/*">
                <p style="font-size: 12px; color: #6b7280; margin-top: 6px;">Opsional. Gunakan untuk banner atau gambar utama halaman. Maksimal 2MB.</p>
            </div>
            
            <div class="form-group">
                <label class="form-label">Slug URL Key <span style="color:#ef4444;">*</span></label>
                <input type="text" name="slug" class="form-control" placeholder="Contoh: akademik-ekstrakurikuler" required>
                <span class="form-text"><i class="fas fa-info-circle"></i> Gunakan huruf kecil semua dan pisahkan dengan tanda strip (-).</span>
            </div>
            
            <div class="form-group">
                <label class="form-label">Isi Konten Utama Halaman <span style="color:#ef4444;">*</span></label>
                <textarea name="content" id="editor_halaman_baru" class="form-control" required></textarea>
                
                <script src="https://cdn.ckeditor.com/4.22.1/full/ckeditor.js"></script>
                <script>
                    setTimeout(function() {
                        if (document.getElementById('editor_halaman_baru')) {
                            CKEDITOR.replace('editor_halaman_baru', {
                                filebrowserUploadUrl: "{{ route('admin.content.upload', ['_token' => csrf_token() ]) }}",
                                filebrowserUploadMethod: 'form',
                                height: 500,
                                toolbarGroups: [
                                    { name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
                                    { name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
                                    { name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
                                    { name: 'forms', groups: [ 'forms' ] },
                                    '/',
                                    { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
                                    { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
                                    { name: 'links', groups: [ 'links' ] },
                                    { name: 'insert', groups: [ 'insert' ] },
                                    '/',
                                    { name: 'styles', groups: [ 'styles' ] },
                                    { name: 'colors', groups: [ 'colors' ] },
                                    { name: 'tools', groups: [ 'tools' ] },
                                    { name: 'others', groups: [ 'others' ] },
                                    { name: 'about', groups: [ 'about' ] }
                                ],
                                removeButtons: 'Save,NewPage,ExportPdf,Preview,Print,Templates,Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,About'
                            });
                        }
                    }, 300);
                </script>
            </div>
            
            <div class="btn-group">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save" style="margin-right: 6px;"></i> Simpan Data Halaman</button>
                <a href="{{ route('admin.content.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection