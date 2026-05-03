@extends('adminlte::page')

@section('title', 'Edit Galeri')

@section('content_header')
    <h1>Edit Galeri: {{ $gallery->title }}</h1>
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Ups!</strong> Ada kesalahan dalam input:
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.galleries.update', $gallery->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="title">Judul Galeri</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $gallery->title) }}"
                        required>
                </div>

                <div class="form-group">
                    <label for="description">Deskripsi</label>
                    <textarea name="description" class="form-control">{{ old('description', $gallery->description) }}</textarea>
                </div>

                <div class="form-group">
                    <label for="status">Status</label>
                    <select name="status" class="form-control">
                        <option value="1" {{ $gallery->status ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ !$gallery->status ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="images">Upload Foto Baru <small class="text-muted">(opsional, maks 5MB/foto)</small></label>
                    <input type="file" name="images[]" class="form-control" multiple accept="image/*">
                    <small class="text-muted">Kosongkan jika tidak ingin menambah foto baru.</small>
                </div>

                <div class="form-group">
                    <label for="videos">Upload Video Baru <small class="text-muted">(opsional, maks 100MB/video, format: mp4, mov, avi, webm)</small></label>
                    <div id="video-pairs-container"></div>
                    <button type="button" class="btn btn-sm btn-outline-secondary mt-1" onclick="addVideoPair()">
                        <i class="fas fa-plus"></i> Tambah Video
                    </button>
                    <small class="text-muted d-block mt-1">Kosongkan jika tidak ingin menambah video baru.</small>
                </div>

                <div class="form-group">
                    <label for="video_urls">Tambah Link Video YouTube Baru <small class="text-muted">(opsional, satu link per baris)</small></label>
                    <textarea name="video_urls" class="form-control" rows="3"
                        placeholder="https://www.youtube.com/watch?v=xxxxx&#10;https://youtu.be/xxxxx"></textarea>
                    <small class="text-muted">Masukkan URL YouTube baru, satu link per baris.</small>
                </div>

                <div class="card-footer px-0">
                    <button type="submit" class="btn btn-success">Update Galeri</button>
                    <a href="{{ route('admin.galleries.index') }}" class="btn btn-secondary">Kembali</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-body">
            <h5>Media Saat Ini</h5>
            <div class="row">
                @forelse ($gallery->images as $image)
                    <div class="col-md-3 mb-3">
                        <div class="card shadow-sm">
                            @if ($image->isLocalVideo())
                                <video class="card-img-top" style="height:140px; object-fit:cover;" controls>
                                    <source src="{{ asset('storage/' . $image->image_path) }}">
                                </video>
                            @elseif ($image->isYoutubeVideo())
                                <div class="card-img-top bg-dark d-flex align-items-center justify-content-center"
                                    style="height: 140px;">
                                    <div class="text-center text-white p-2">
                                        <i class="fab fa-youtube fa-3x text-danger mb-1"></i>
                                        <p class="mb-0" style="font-size:11px; word-break:break-all;">
                                            {{ Str::limit($image->video_url, 40) }}</p>
                                    </div>
                                </div>
                            @else
                                <img src="{{ asset('storage/' . $image->image_path) }}"
                                    class="card-img-top rounded" alt="Foto Galeri"
                                    style="height:140px; object-fit:cover;">
                            @endif
                            <div class="card-body p-2 text-center">
                                <span class="badge {{ $image->type === 'video' ? 'badge-danger' : 'badge-info' }} mb-1">
                                    {{ $image->isLocalVideo() ? 'Video (Lokal)' : ($image->isYoutubeVideo() ? 'Video (YouTube)' : 'Foto') }}
                                </span>
                                @if ($image->isLocalVideo())
                                    <button type="button" class="btn btn-sm btn-warning btn-block mb-1"
                                        data-toggle="modal"
                                        data-target="#editVideoModal{{ $image->id }}">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                @endif
                                <button class="btn btn-sm btn-danger btn-block btn-delete-image"
                                    data-id="{{ $image->id }}"
                                    data-url="{{ route('admin.galleries.images.destroy', $image->id) }}">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <p class="text-muted">Belum ada media yang ditambahkan.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Modal Edit Video --}}
    @foreach ($gallery->images as $image)
        @if ($image->isLocalVideo())
            <div class="modal fade" id="editVideoModal{{ $image->id }}" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <form action="{{ route('admin.galleries.images.update', $image->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Video</h5>
                                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label class="small font-weight-bold">Ganti File Video <span class="text-muted">(opsional, maks 100MB)</span></label>
                                    <input type="file" name="video" class="form-control-file" accept="video/*">
                                    <small class="text-muted">Kosongkan jika tidak ingin mengganti video.</small>
                                </div>
                                <div class="form-group mb-0">
                                    <label class="small font-weight-bold">Ganti Foto Cover <span class="text-muted">(opsional, maks 5MB)</span></label>
                                    @if ($image->thumbnail_path)
                                        <div class="mb-1">
                                            <img src="{{ asset('storage/' . $image->thumbnail_path) }}" style="height:80px; object-fit:cover; border-radius:4px;" alt="Cover saat ini">
                                            <small class="d-block text-muted">Cover saat ini</small>
                                        </div>
                                    @endif
                                    <input type="file" name="video_cover" class="form-control-file" accept="image/*">
                                    <small class="text-muted">Kosongkan jika tidak ingin mengganti cover.</small>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-warning"><i class="fas fa-save mr-1"></i> Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    @endforeach

    <form id="delete-image-form" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function addVideoPair() {
            const container = document.getElementById('video-pairs-container');
            const idx = container.querySelectorAll('.video-pair').length;
            const html = `
                <div class="video-pair border rounded p-2 mb-2 bg-light">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <strong class="small">Video ${idx + 1}</strong>
                        <button type="button" class="btn btn-sm btn-outline-danger py-0" onclick="this.closest('.video-pair').remove()">
                            <i class="fas fa-times"></i> Hapus
                        </button>
                    </div>
                    <div class="form-group mb-1">
                        <label class="small mb-0">File Video <span class="text-danger">*</span></label>
                        <input type="file" name="videos[]" class="form-control-file" accept="video/*" required>
                    </div>
                    <div class="form-group mb-0">
                        <label class="small mb-0">Foto Cover <span class="text-muted">(opsional)</span></label>
                        <input type="file" name="video_covers[]" class="form-control-file" accept="image/*">
                        <small class="text-muted">Gambar yang tampil di galeri sebelum video diputar.</small>
                    </div>
                </div>`;
            container.insertAdjacentHTML('beforeend', html);
        }

        document.addEventListener('DOMContentLoaded', function() {
            const deleteButtons = document.querySelectorAll('.btn-delete-image');
            const deleteForm = document.getElementById('delete-image-form');

            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const url = this.getAttribute('data-url');

                    Swal.fire({
                        title: 'Yakin hapus gambar ini?',
                        text: "Gambar akan dihapus permanen!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonColor: '#d33',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            deleteForm.setAttribute('action', url);
                            deleteForm.submit();
                        }
                    });
                });
            });
        });
    </script>
@stop

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if (session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            timer: 3000,
            showConfirmButton: false
        });
    </script>
@endif

@if (session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: '{{ session('error') }}',
            timer: 3000,
            showConfirmButton: false
        });
    </script>
@endif
