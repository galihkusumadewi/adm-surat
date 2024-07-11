@extends('layouts.base_admin.base_dashboard')@section('judul', 'Olah Data Disposisi')
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
                <h1>DATA DISPOSISI</h1>
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
        <div class="card-body p-0" style="margin: 20px">
            <table id="previewDisposisi" class="table table-striped table-bordered display" style="width:100%">
                <thead>
                    <tr>
                        <th>ID Disposisi</th>
                        <th>No Disposisi</th>
                        <th>Tanggal</th>
                        <th>Asal Disposisi</th>
                        <th>Perihal</th>
                        <th>Instruksi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
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
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                confirmButtonText: 'OK'
            });
        @endif
    });
</script>

</script>

    <script>
        $(document).ready(function () {
    $('#previewDisposisi').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.disposisi.disposisi') }}",
        columns: [
            { data: 'id_disposisi', name: 'id_disposisi' },
            { data: 'no_disposisi', name: 'no_disposisi' },
            { data: 'tanggal', name: 'tanggal' },
            { data: 'asal_disposisi', name: 'asal_disposisi' },
            { data: 'perihal', name: 'perihal' },
            { data: 'instruksi', name: 'instruksi' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        language: {
            // ... (Bahasa konfigurasi lainnya)
        }
    });


    $('#previewDisposisi').on('click', '.hapusData', function () {
        var id_disposisi = $(this).data('id');
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
                        $('#previewDisposisi').DataTable().ajax.reload();
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