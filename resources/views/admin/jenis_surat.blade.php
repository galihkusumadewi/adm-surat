@extends('layouts.base_admin.base_dashboard')
@section('judul', 'Olah Data Jenis Surat')
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
                <h1>DATA JENIS SURAT</h1>
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
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#tambahJenisModal">
                    <i class="fas fa-plus"></i> Tambah Data
                </button>
            </div>
        </div>
        <div class="card-body p-0" style="margin: 20px">
            <table id="previewJenis" class="table table-striped table-bordered display" style="width:100%">
                <thead>
                    <tr>
                        <th>ID Jenis</th>
                        <th>Jenis Surat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($jenisSurat as $jenis)
                        <tr>
                            <td>{{ $jenis->id_jenis }}</td>
                            <td>{{ $jenis->jenis_surat }}</td>
                            <td>
                                <button class="btn btn-danger hapusData" data-id="{{ $jenis->id_jenis }}" data-hapus-url="{{ route('admin.jenis.delete', $jenis->id_jenis) }}">Hapus</button>
                                <button class="btn btn-primary editData" data-id="{{ $jenis->id_jenis }}" data-jenis="{{ $jenis->jenis_surat }}" data-toggle="modal" data-target="#editJenisModal">Edit</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="tambahJenisModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah Data Jenis</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formTambahJenis" action="{{ route('admin.jenis.add') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <input class="form-control mb-3" type="text" name="jenis_surat" placeholder="Masukkan Nama Jenis">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary" id="simpanJenis">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editJenisModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Data Jenis</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formEditJenis" action="{{ route('admin.jenis.update', ['id_jenis' => ':id_jenis']) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="nama">Jenis Surat</label>
                        <input class="form-control" type="text" id="jenis_surat" name="jenis_surat" placeholder="Masukkan Jenis Surat" >
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" id="editJenis" >Simpan</button>
                    </div>
                </form> 
            </div>
        </div>
    </div>
</div>


</section>

@endsection

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


@section('script_footer')
<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
<script>
    $(document).ready(function () {
        $('#previewJenis').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.jenis.jenis_surat') }}",
            columns: [
                { data: 'id_jenis', name: 'id_jenis' },
                { data: 'jenis_surat', name: 'jenis_surat' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
            language: {
                // ... (Bahasa konfigurasi lainnya)
            }
        });

        $('#simpanJenis').on('click', function(e) {
            e.preventDefault();
            var data = $('#formTambahJenis').serialize();
            $.ajax({
                url: "{{ route('admin.jenis.add') }}",
                method: "POST",
                data: data,
                success: function(response) {
                    $('#tambahJenisModal').modal('hide');
                    $('#previewJenis').DataTable().ajax.reload();
                    Swal.fire('Sukses!', 'Data berhasil ditambahkan!', 'success');
                },
                error: function(response) {
                    Swal.fire('Gagal!', 'Terjadi kesalahan, silakan coba lagi.', 'error');
                }
            });
        });

        $(document).ready(function () {
            $('#editJenisModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget); 
                var id_jenis = button.data('id');
                var jenis_surat = button.data('jenis_surat'); 
                
                var modal = $(this);
                modal.find('.modal-title').text('Edit Data Jenis ' + id_jenis);
                modal.find('form').attr('action', "{{ route('admin.jenis.update', ['id_jenis' => ':id_jenis']) }}".replace(':id_jenis', id_jenis));
                modal.find('input[name="jenis_surat"]').val(jenis_surat);
            });
        });

        $('#editJenis').on('submit', function (e) {
        e.preventDefault();
        var id_jenis = $('input[name="id_jenis"]').val();
        var data = $(this).serialize();

        $.ajax({
            url: "{{ route('admin.jenis.update', ['id_jenis' => ':id_jenis']) }}".replace(':id_jenis', id_jenis),
            method: "PUT",
            data: data,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                $('#editJenisModal').modal('hide');
                $('#previewJenis').DataTable().ajax.reload();
                Swal.fire('Sukses!', 'Data berhasil diubah!', 'success');
            },
            error: function (response) {
                Swal.fire('Gagal!', 'Terjadi kesalahan, silakan coba lagi.', 'error');
            }
        });
    });

        $('#previewJenis').on('click', '.hapusData', function () {
            var id_jenis = $(this).data('id');
            var hapusUrl = "{{ route('admin.jenis.delete', ['id_jenis' => ':id_jenis']) }}";
            hapusUrl = hapusUrl.replace(':id_jenis', id_jenis);

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
                            $('#previewJenis').DataTable().ajax.reload();
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
