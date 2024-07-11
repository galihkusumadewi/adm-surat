@extends('layouts.base_admin.base_dashboard')
@section('judul', 'Olah Data Pengguna')
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
                <h1>DATA PENGGUNA</h1>
            </div>
            <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                    @if(auth()->user()->role == 'admin')
                    <li class="breadcrumb-item active"><a href="{{ route('admin.home') }}">Home Admin</a></li>
                    @elseif(auth()->user()->role == 'kabag')
                    <li class="breadcrumb-item active"><a href="{{ route('kabag.home') }}">Home Kabag</a></li>
                    @elseif(auth()->user()->role == 'kadin')
                    <li class="breadcrumb-item active"><a href="{{ route('kadin.home') }}">Home Kadin</a></li>
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
        <div class="card-body p-0" style="margin: 20px">
            <table id="previewPengguna" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>ID Pengguna</th>
                        <th>Nama</th>
                        <th>NIK</th>
                        <th>No Hp</th>
                        <th>Alamat</th>
                        <th>Jabatan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

@endsection

@section('script_footer')
<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
<script>
$(document).ready(function () {
    $('#previewPengguna').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('kadin.pengguna.pengguna') }}",
        columns: [
            { data: 'id_pengguna', name: 'id_pengguna' },
            { data: 'nama', name: 'nama' },
            { data: 'NIK', name: 'NIK' },
            { data: 'no_hp', name: 'no_hp' },
            { data: 'alamat', name: 'alamat' },
            { data: 'jabatan', name: 'jabatan' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        language: {
            // ... (Bahasa konfigurasi lainnya)
        }
    });


    $('#previewPengguna').on('click', '.hapusData', function () {
        var id_pengguna = $(this).data('id');
        var hapusUrl = $(this).data('hapus-url');
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
                $.ajax({
                    url: hapusUrl,
                    method: 'POST',
                    data: {
                        _method: 'DELETE',
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        Swal.fire('Terhapus!', response.message, 'success');
                        $('#previewPengguna').DataTable().ajax.reload();
                    },
                    error: function(error) {
                        Swal.fire('Gagal!', error.responseJSON.message, 'error');
                    }
                });
            }
        });
    });

    $('#previewPengguna').on('click', '.editData', function () {
        var id_pengguna = $(this).data('id');
        var nama = $(this).data('nama');
        var alamat = $(this).data('alamat');
        var id_jabatan = $(this).data('id_jabatan');
        var NIK = $(this).data('NIK');
        var no_hp = $(this).data('no_hp');

        $('#editPenggunaModal').find('input[name="id_pengguna"]').val(id_pengguna);
        $('#editPenggunaModal').find('input[name="nama"]').val(nama);
        $('#editPenggunaModal').find('input[name="NIK"]').val(NIK);
        $('#editPenggunaModal').find('input[name="alamat"]').val(alamat);
        $('#editPenggunaModal').find('input[name="no_hp"]').val(no_hp);
        $('#editPenggunaModal').find('select[name="id_jabatan"]').val(id_jabatan);

        $('#editPenggunaModal').modal('show');
    });

    $('#previewPengguna').on('click', '.detailData', function () {
        var id_pengguna = $(this).data('id');
        var detailUrl = $(this).data('detail-url');

        $.ajax({
            url: detailUrl,
            method: 'GET',
            success: function(response) {
                Swal.fire({
                    title: 'Detail Data Pengguna',
                    html: '<b>ID Pengguna:</b> ' + response.id_pengguna + '<br>' +
                          '<b>Nama:</b> ' + response.nama + '<br>' +
                          '<b>NIK:</b> ' + response.NIK + '<br>' +
                          '<b>No HP:</b> ' + response.no_hp + '<br>' +
                          '<b>Alamat:</b> ' + response.alamat + '<br>' +
                          '<b>Jabatan:</b> ' + response.jabatan.jabatan,
                    icon: 'info',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Tutup'
                });
            },
            error: function(error) {
                Swal.fire('Gagal!', error.responseJSON.message, 'error');
            }
        });
    });
});
</script>
@endsection