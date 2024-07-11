@extends('layouts.base_admin.base_dashboard')

@section('judul', 'Olah Data Surat Keluar')

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
                <h1>DATA SURAT KELUAR</h1>
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

<!-- Main content -->
<section class="content">

    <!-- Default box -->
    <div class="card">
        <div class="card-body p-0" style="margin: 20px">
            <table id="previewSuratKeluar" class="table table-striped table-bordered display" style="width:100%">
                <thead>
                    <tr>
                        <th>Id Surat Keluar</th>
                        <th>No Surat</th>
                        <th>Tanggal</th>
                        <th>Perihal</th>
                        <th>Sifat</th>
                        <th>File Surat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->

</section>
@endsection

@section('script_footer')
<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
<script>
    $(document).ready(function () {
        $('#previewSuratKeluar').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('kabag.keluar.surat_keluar') }}",
            columns: [
                { data: 'id_suratkeluar', name: 'id_suratkeluar' },
                { data: 'no_surat', name: 'no_surat' },
                { data: 'tgl_surat', name: 'tgl_surat' },
                { data: 'perihal', name: 'perihal' },
                { data: 'id_sifat', name: 'id_sifat' },
                { data: 'file_surat', name: 'file_surat' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
            language: {
                // Sesuaikan dengan bahasa yang diinginkan
                "sEmptyTable": "Tidak ada data yang tersedia pada tabel ini",
                "sProcessing": "Sedang memproses...",
                "sLengthMenu": "Tampilkan _MENU_ data",
                "sZeroRecords": "Tidak ditemukan data yang sesuai",
                "sInfo": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                "sInfoEmpty": "Menampilkan 0 sampai 0 dari 0 data",
                "sInfoFiltered": "(disaring dari _MAX_ total data)",
                "sInfoPostFix": "",
                "sSearch": "Cari:",
                "sUrl": "",
                "oPaginate": {
                    "sFirst": "Pertama",
                    "sPrevious": "Sebelumnya",
                    "sNext": "Selanjutnya",
                    "sLast": "Terakhir"
                },
                "oAria": {
                    "sSortAscending": ": aktifkan untuk menyortir kolom secara ascending",
                    "sSortDescending": ": aktifkan untuk menyortir kolom secara descending"
                }
            }
        });
        });
</script>
@endsection
