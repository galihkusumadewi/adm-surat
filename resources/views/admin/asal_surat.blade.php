@extends('layouts.base_admin.base_dashboard')
@section('judul', 'Olah Data AsalSurat')
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
                <h1>DATA ASAL SURAT</h1>
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
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#tambahAsalModal">
                    <i class="fas fa-plus"></i> Tambah Data
                </button>
            </div>
        </div>
        <div class="card-body p-0" style="margin: 20px">
            <table id="previewAsal" class="table table-striped table-bordered display" style="width:100%">
                <thead>
                    <tr>
                        <th>ID Asal</th>
                        <th>Asal Surat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($asalSurat as $asal)
                        <tr>
                            <td>{{ $asal->id_asal_surat }}</td>
                            <td>{{ $asal->asal_surat }}</td>
                            <td>
                                <button class="btn btn-danger hapusData" data-id="{{ $asal->id_asal_surat }}" data-hapus-url="{{ route('admin.asal.delete', $asal->id_asal_surat) }}">Hapus</button>
                                <button class="btn btn-primary editData" data-id="{{ $asal->id_asal_surat }}" data-edit="{{ $asal->asal_surat }}" data-toggle="modal" data-target="#editAsalModal">Edit</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="tambahAsalModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah Data Asal</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formTambahAsal" action="{{ route('admin.asal.add') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <input class="form-control mb-3" type="text" name="asal_surat" placeholder="Masukkan AsalSurat" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary" id="simpanAsal">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editAsalModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Data Asal</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formEditAsal" action="{{ route('admin.asal.update', ['id_asal_surat' => ':id_asal_surat']) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="asal_surat">AsalSurat</label>
                        <input type="text" class="form-control" id="asal_surat" name="asal_surat" required>
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
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire('Sukses!', '{{ session('success') }}', 'success');
        });
    </script>
@endif

@if (session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire('Gagal!', '{{ session('error') }}', 'error');
        });
    </script>
@endif

@endsection

@section('script_footer')
<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
<script>
$(document).ready(function () {
    $('#previewAsal').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.asal.asal_surat') }}",
        columns: [
            { data: 'id_asal_surat', name: 'id_asal_surat' },
            { data: 'asal_surat', name: 'asal_surat' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        language: {
            // ... (Bahasa konfigurasi lainnya)
        }
    });

    $('#simpanAsal').on('click', function(e) {
        e.preventDefault();
        var data = $('#formTambahAsal').serialize();
        $.ajax({
            url: "{{ route('admin.asal.add') }}",
            method: "POST",
            data: data,
            success: function(response) {
                $('#tambahAsalModal').modal('hide');
                $('#previewAsal').DataTable().ajax.reload();
                Swal.fire('Sukses!', 'Data berhasil ditambahkan!', 'success');
            },
            error: function(response) {
                Swal.fire('Gagal!', 'Terjadi kesalahan, silakan coba lagi.', 'error');
            }
        });
    });

    $('#editAsal').on('click', function(e) {
            e.preventDefault();
            
            var id_asal_surat = $(this).data('id'); // Assuming the button has the data-id attribute
            var data = $('#formEditAsal').serialize();
            
            $.ajax({
                url: "{{ route('admin.asal.update', ['id_asal_surat' => ':id_asal_surat']) }}".replace(':id_asal_surat', id_asal_surat),
                method: "POST",
                data: data,
                success: function(response) {
                    $('#editAsalModal').modal('hide');
                    $('#previewAsal').DataTable().ajax.reload();
                    Swal.fire('Sukses!', 'Data berhasil diubah!', 'success');
                },
                error: function(response) {
                    Swal.fire('Gagal!', 'Terjadi kesalahan, silakan coba lagi.', 'error');
                }
            });
        });

        $('#previewAsal').on('click', '.hapusData', function () {
            var id_asal_surat = $(this).data('id');
            var hapusUrl = "{{ route('admin.asal.delete', ['id_asal_surat' => ':id_asal_surat']) }}";
            hapusUrl = hapusUrl.replace(':id_asal_surat', id_asal_surat);

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
                        success: function(response) {
                            $('#previewAsal').DataTable().ajax.reload();
                            Swal.fire('Sukses!', 'Data berhasil dihapus!', 'success');
                        },
                        error: function(response) {
                            Swal.fire('Gagal!', 'Terjadi kesalahan, silakan coba lagi.', 'error');
                        }
                    });
                }
            });
        });

    $(document).ready(function () {
    $('#editAsalModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var id_asal_surat = button.data('id'); 
        var asal_surat = button.data('asal_surat');
        
        // Mengatur nilai form di dalam modal
        var modal = $(this);
        modal.find('.modal-title').text('Edit Data Asal ' +  id_asal_surat);
        modal.find('form').attr('action', "{{ route('admin.asal.update', ['id_asal_surat' => ':id_asal_surat']) }}".replace(':id_asal_surat', id_asal_surat));
        modal.find('input[name="asal_surat"]').val(asal_surat);
    });
});


    });
</script>
@endsection
