@extends('layouts.app')
@section('title', 'Edit Keunggulan')

@section('content')
<div class="page-header" style="margin-bottom:24px;">
    <h1 class="page-title">Edit Keunggulan</h1>
    <p class="page-subtitle">Perbarui data kartu keunggulan sekolah.</p>
</div>

<div class="content-card" style="background:white;border-radius:12px;border:1px solid #e5e7eb;padding:28px;max-width:640px;">
    <form action="{{ route('admin.keunggulan.update', $keunggulan->id) }}" method="POST">
        @csrf @method('PUT')
        @include('admin.keunggulan._form')
        <div style="display:flex;gap:10px;margin-top:24px;">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Perbarui</button>
            <a href="{{ route('admin.keunggulan.index') }}" class="btn" style="background:#f3f4f6;color:#374151;border:1px solid #d1d5db;">Batal</a>
        </div>
    </form>
</div>
@endsection