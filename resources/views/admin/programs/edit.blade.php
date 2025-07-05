@extends('adminlte::page')

@section('title', 'Edit Program')

@section('content_header')
    <h1>Edit Program: {{ $program->judul_konten }}</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Formulir Edit Program</h3>
    </div>
    <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.programs.update', $program->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group mb-3">
                <label for="judul_konten">Judul Konten Utama</label>
                <input type="text" id="judul_konten" name="judul_konten" class="form-control" value="{{ old('judul_konten', $program->judul_konten) }}" required>
            </div>

            <div class="form-group mb-3">
                <label for="deskripsi">Deskripsi Lengkap Program</label>
                <textarea id="deskripsi" name="deskripsi" class="form-control" rows="4" required>{{ old('deskripsi', $program->deskripsi) }}</textarea>
            </div>

            <div class="form-group mb-3">
                <label for="keunggulan">Keunggulan Program (Satu keunggulan per baris)</label>
                <textarea id="keunggulan" name="keunggulan" class="form-control" rows="5" required>{{ old('keunggulan', $program->keunggulan) }}</textarea>
            </div>

            <div class="form-group mb-3">
                <label for="gambar">Ganti Gambar (Kosongkan jika tidak ingin diubah)</label>
                <input type="file" id="gambar" name="gambar" class="form-control">
                <div class="mt-2">
                    <label>Gambar Saat Ini:</label><br>
                    <img src="{{ asset('uploads/programs/' . $program->gambar) }}" alt="Current Image" class="img-thumbnail" width="150">
                </div>
            </div>

            <div class="form-check mb-3">
                <input type="checkbox" id="status_aktif_default" name="status_aktif_default" class="form-check-input" value="1" @if(old('status_aktif_default', $program->status_aktif_default)) checked @endif>
                <label for="status_aktif_default" class="form-check-label">Jadikan tab ini aktif secara default?</label>
            </div>
            
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('admin.programs.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@stop