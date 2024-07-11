@extends('layouts.base_admin.base_dashboard')
@section('judul', 'Olah Data Sifat Surat')
@section('script_head')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>DATA SIFAT SURAT</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    @if(auth()->user()->role == 'admin')
                    <li class="breadcrumb-item active"><a href="{{ route('admin.home') }}">Home Admin</a></li>
                    @elseif(auth()->user()->role == 'kabag')
                    <li class="breadcrumb-item active"><a href="{{ route('kabag.home') }}">Home Kabag</a></li>
                    @elseif(auth()->user()->role == 'sekretaris')
                    <li class="breadcrumb-item active"><a href="{{ route('sekretaris.home') }}">Home Sekretaris</a></li>
                    @endif
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="card">
        <div class="card-header">
            <div class="card-tools">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#tambahSifatModal">
                    <i class="fas fa-plus"></i> Tambah Data
                </button>
            </div>
        </div>
        <div class="card-body p-0" style="margin: 20px">
            <table id="previewSifat" class="table table-striped table-bordered display" style="width:100%">
                <thead>
                    <tr>
                        <th>ID Sifat</th>
                        <th>Sifat Surat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="tambahSifatModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah Data Sifat</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formTambahSifat" action="{{ route('admin.sifat.add') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <input class="form-control mb-3" type="text" name="sifat_surat"
                                placeholder="Masukkan Sifat Surat" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary" id="simpanSifat">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editSifatModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Data Sifat</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formEditSifat" action="" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="sifat_surat">Sifat Surat</label>
                            <input type="text" class="form-control" id="sifat_surat" name="sifat_surat" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</section>

@if (session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        Swal.fire('Sukses!', '{{ session('
        success ') }}', 'success');
    });
</script>
@endif

@if (session('error'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        Swal.fire('Gagal!', '{{ session('
        error ') }}', 'error');
    });
</script>
@endif

@endsection

@section('script_footer')
<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
<script>
   $(document).ready(function () {
    $('#previewSifat').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.sifat.sifat_surat') }}",
        columns: [
            { data: 'id_sifat', name: 'id_sifat' },
            { data: 'sifat_surat', name: 'sifat_surat' },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false,
                render: function (data, type, row, meta) {
                    var editBtn = '<a href="#" class="btn btn-primary editBtn" data-id="' + row.id_sifat + '" data-sifat_surat="' + row.sifat_surat + '">Edit</a>';
                    var deleteBtn = '<button type="button" class="btn btn-danger hapusData" data-id="' + row.id_sifat + '">Hapus</button>';

                    return editBtn + ' ' + deleteBtn;
                }
            }
        ],
        language: {
            // Konfigurasi bahasa DataTables (opsional)
        }
    });

    // Event listener untuk tombol Edit pada setiap baris
    $('#previewSifat').on('click', '.editBtn', function (e) {
        e.preventDefault();
        var id_sifat = $(this).data('id');
        var sifat_surat = $(this).data('sifat_surat');

        $('#editSifatModal').modal('show');
        $('#editSifatModal').find('.modal-title').text('Edit Data Sifat ' + id_sifat);
        $('#editSifatModal').find('form').attr('action', "{{ route('admin.sifat.update', ['id_sifat' => ':id_sifat']) }}".replace(':id_sifat', id_sifat));
        $('#editSifatModal').find('input[name="sifat_surat"]').val(sifat_surat);
    });

    // Event listener untuk form Edit di dalam modal
    $('#editSifat').on('click', function(e) {
    e.preventDefault();
    
    var id_sifat = $(this).data('id'); // Assuming the button has the data-id attribute
    var data = $('#formEditSifat').serialize();
    
    $.ajax({
        url: "{{ route('admin.sifat.update', ['id_sifat' => ':id_sifat']) }}".replace(':id_sifat', id_sifat),
        method: "POST", // Change this to PUT
        data: data,
        success: function(response) {
            $('#editSifatModal').modal('hide');
            $('#previewSifat').DataTable().ajax.reload();
            Swal.fire('Sukses!', 'Data berhasil diubah!', 'success');
        },
        error: function(response) {
            Swal.fire('Gagal!', 'Terjadi kesalahan, silakan coba lagi.', 'error');
        }
    });
});


    // Event listener untuk tombol Hapus pada setiap baris
    $('#previewSifat').on('click', '.hapusData', function () {
        var id_sifat = $(this).data('id');
        var hapusUrl = "{{ route('admin.sifat.delete', ['id_sifat' => ':id_sifat']) }}";
        hapusUrl = hapusUrl.replace(':id_sifat', id_sifat);

        Swal.fire({
            title: 'Apakah kamu yakin?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Menggunakan metode DELETE untuk penghapusan
                $.ajax({
                    url: hapusUrl,
                    type: 'DELETE',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                    },
                    success: function (response) {
                        $('#previewSifat').DataTable().ajax.reload();
                        Swal.fire('Sukses!', 'Data berhasil dihapus!', 'success');
                    },
                    error: function (response) {
                        Swal.fire('Gagal!', 'Terjadi kesalahan, silakan coba lagi.', 'error');
                    }
                });
            }
        });
    });

    // Event listener untuk tombol Simpan pada modal Tambah Sifat
    $('#simpanSifat').on('click', function (e) {
        e.preventDefault();
        var data = $('#formTambahSifat').serialize();
        $.ajax({
            url: "{{ route('admin.sifat.add') }}",
            method: "POST",
            data: data,
            success: function (response) {
                $('#tambahSifatModal').modal('hide');
                $('#previewSifat').DataTable().ajax.reload();
                Swal.fire('Sukses!', 'Data berhasil ditambahkan!', 'success');
            },
            error: function (response) {
                Swal.fire('Gagal!', 'Terjadi kesalahan, silakan coba lagi.', 'error');
            }
        });
    });

});

</script>
@endsection
