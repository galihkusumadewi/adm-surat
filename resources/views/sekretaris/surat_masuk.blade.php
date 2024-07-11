@extends('layouts.base_admin.base_dashboard')@section('judul', 'Olah Data Surat Masuk')
@section('script_head')
<link
    rel="stylesheet"
    type="text/css"
    href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>DATA SURAT MASUK</h1>
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

<!-- Main content -->
<section class="content">

    <!-- Default box -->
    <div class="card">
        <div class="card-header">
            <div class="card-tools">
                <a href="{{ route('sekretaris.masuk.add') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Data
                </a>
            </div>
        </div>
        <div class="card-body p-0" style="margin: 20px">
            <table id="previewSuratMasuk" class="table table-striped table-bordered display" style="width:100%">
                <thead>
                    <tr>
                        <th>Id Surat Masuk</th>
                        <th>No Surat</th>
                        <th>Tanggal</th>
                        <th>Sifat</th>
                        <th>File Surat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    
                </tbody>
            </table>
        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->

    @endsection

@section('script_footer')
<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
    
    <script>
        $(document).ready(function () {$('#previewSuratMasuk').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('sekretaris.masuk.surat_masuk') }}",
        columns: [
            { data: 'id_suratmasuk', name: 'id_suratmasuk' },
            { data: 'no_surat', name: 'no_surat' },
            { data: 'tanggal', name: 'tanggal' },
            { data: 'id_sifat', name: 'id_sifat' },
            { data: 'file_surat', name: 'file_surat' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        language: {
            // ... (Bahasa konfigurasi lainnya)
        }
    });
 
    $('#simpanSuratMasuk').on('click', function() {
        var data = $('#formTambahSuratMasuk').serialize();
        $.ajax({
            url: "{{ route('sekretaris.masuk.add') }}",
            method: "POST",
            data: data,
            success: function(response) {
                $('#tambahSuratMasukModal').modal('hide');
                $('#previewSuratMasuk').DataTable().ajax.reload();
                Swal.fire('Berhasil!', response.msg, 'success');
            },
            error: function(response) {
                Swal.fire('Gagal!', response.msg, 'error');
            }
        });
    });

    $('#previewSuratMasuk').on('click', '.hapusData', function () {
        var id_suratmasuk = $(this).data('id');
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
                        $('#previewSuratMasuk').DataTable().ajax.reload();
                        Swal.fire('Sukses!', 'Data berhasil dihapus!', 'success');
                    },
                    error: function(response) {
                        Swal.fire('Gagal!', 'Terjadi kesalahan, silakan coba lagi.', 'error');
                    }
                });
            }
        });
    });
});
</script>
@endsection