@extends('adminlte::page')

@section('title', 'Manajemen Periode')

@section('content_header')
    <h1 class="mb-3">Manajemen Periode</h1>
@stop

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        {{-- Ganti: data-toggle/data-target tidak efektif di x-adminlte-button --}}
        <x-adminlte-button label="Tambah Periode" theme="primary" icon="fas fa-plus" id="btnAddPeriod"/>
        <div style="width: 300px;">
            <input type="text" id="searchInput" class="form-control" placeholder="Cari tanggal...">
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-bordered table-striped align-middle" id="periodTable">
            <thead class="thead-light">
                <tr class="text-center">
                    <th>#</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th>Dibuat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($periods as $index => $period)
                    <tr>
                        <td class="text-center">{{ $index + $periods->firstItem() }}</td>
                        <td>{{ \Carbon\Carbon::parse($period->date)->format('d-m-Y') }}</td>
                        <td class="text-center">
                            <span class="badge {{ $period->is_active ? 'bg-success' : 'bg-secondary' }}">
                                {{ $period->is_active ? 'Aktif' : 'Tidak Aktif' }}
                            </span>
                        </td>
                        <td>{{ $period->created_at->diffForHumans() }}</td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-warning me-1 btn-edit-period"
                                data-id="{{ $period->id }}"
                                data-date="{{ $period->date->format('Y-m-d') }}"
                                data-is_active="{{ $period->is_active }}"
                            >
                                <i class="fas fa-edit"></i>
                            </button>
                            <form action="{{ route('admin.periods.destroy', $period->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Yakin hapus?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="fas fa-trash-alt"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted">Tidak ada data periode.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-between mt-3">
        <small class="text-muted">Menampilkan {{ $periods->firstItem() }} - {{ $periods->lastItem() }} dari {{ $periods->total() }} data</small>
        <div>{{ $periods->links() }}</div>
    </div>

    {{-- Modal Create --}}
    <x-adminlte-modal id="createPeriodModal" title="Tambah Periode" theme="light" size="md" static-backdrop>
        <form action="{{ route('admin.periods.store') }}" method="POST">
            @csrf
            <x-adminlte-input name="date" label="Tanggal" type="date" required />
            <x-adminlte-input-switch name="is_active" label="Jadikan Aktif" data-on-text="Ya" data-off-text="Tidak"/>
            <div class="mt-3 text-end">
                <x-adminlte-button label="Batal" theme="secondary" data-dismiss="modal" class="me-1"/>
                <x-adminlte-button label="Simpan" type="submit" theme="primary"/>
            </div>
        </form>
    </x-adminlte-modal>

    {{-- Modal Edit --}}
    <x-adminlte-modal id="editPeriodModal" title="Edit Periode" theme="light" size="md" static-backdrop>
        <form id="editPeriodForm" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="id" id="editId">
            <x-adminlte-input name="date" label="Tanggal" id="editDate" type="date" required />
            <x-adminlte-input-switch name="is_active" label="Jadikan Aktif" id="editIsActiveSwitch" data-on-text="Ya" data-off-text="Tidak"/>
            <div class="mt-3 text-end">
                <x-adminlte-button label="Batal" theme="secondary" data-dismiss="modal" class="me-1"/>
                <x-adminlte-button label="Simpan Perubahan" type="submit" theme="primary"/>
            </div>
        </form>
    </x-adminlte-modal>
@stop
@section('js')
<!-- jQuery dan Bootstrap JS (pastikan ini ada) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).ready(function () {
        // Buka modal create
        $('#btnAddPeriod').on('click', function () {
            $('#createPeriodModal').modal('show');
        });

        // Modal edit
        $(document).on('click', '.btn-edit-period', function () {
            const id = $(this).data('id');
            const date = $(this).data('date');
            const isActive = $(this).data('is_active') == 1;

            $('#editId').val(id);
            $('#editDate').val(date);
            $('#editIsActiveSwitch').prop('checked', isActive);
            $('#editPeriodForm').attr('action', `/admin/periods/${id}`);
            $('#editPeriodModal').modal('show');
        });

        // Live search
        $('#searchInput').on('keyup', function () {
            const value = this.value.toLowerCase();
            $('#periodTable tbody tr').each(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });
    });
</script>
@stop
