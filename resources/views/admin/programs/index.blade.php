@extends('adminlte::page')

@section('title', 'Daftar Program')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Daftar Program</h1>
        <a href="{{ route('admin.programs.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Program
        </a>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">List Program</h3>
        </div>
        <div class="card-body">
            <div class="mb-4">
                <form action="{{ route('admin.programs.index') }}" method="GET">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Cari berdasarkan judul program..." value="{{ request('search') }}">
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-custom-header">
                        <tr>
                            <th style="width: 10px;">No</th> 
                            <th style="width: 150px;">Gambar</th>
                            <th>Judul Konten</th>
                            <th>Deskripsi</th>       {{-- <-- KOLOM BARU --}}
                            <th>Keunggulan</th>      {{-- <-- KOLOM BARU --}}
                            <th>Status Aktif Default</th>
                            <th style="width: 150px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($programs as $program)
                            <tr>
                                <td>{{ $loop->iteration }}</td> 
                                <td>
                                    <img src="{{ asset('uploads/programs/' . $program->gambar) }}" alt="{{ $program->judul_konten }}" class="img-thumbnail" width="100">
                                </td>
                                <td>{{ $program->judul_konten }}</td>
                                
                                {{-- Menampilkan deskripsi dengan batasan 50 karakter --}}
                                <td>{{ Str::limit($program->deskripsi, 50, '...') }}</td>
                                
                                {{-- Menampilkan keunggulan dengan batasan 50 karakter --}}
                                <td>{{ Str::limit($program->keunggulan, 50, '...') }}</td>
                                
                                <td>
                                    @if($program->status_aktif_default)
                                        <span class="badge bg-success">Ya</span>
                                    @else
                                        <span class="badge bg-secondary">Tidak</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.programs.edit', $program->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('admin.programs.destroy', $program->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Anda yakin ingin menghapus program ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                {{-- Colspan disesuaikan menjadi 7 karena ada 2 kolom baru --}}
                                <td colspan="7" class="text-center">Tidak ada data program.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
        </div>
    </div>
@stop

@push('css')
    <style>
        .table-custom-header {
            background-color: #3c8dbc;
            color: white;
        }
    </style>
@endpush