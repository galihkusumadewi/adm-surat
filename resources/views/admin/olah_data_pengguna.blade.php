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
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#tambahPenggunaModal">
                    <i class="fas fa-plus"></i> Tambah Data
                </button>
            </div>
        </div>
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
                    @foreach ($pengguna as $pgn)
                        <tr>
                            <td>{{ $pgn->id_pengguna }}</td>
                            <td>{{ $pgn->nama }}</td>
                            <td>{{ $pgn->NIK }}</td>
                            <td>{{ $pgn->no_hp }}</td>
                            <td>{{ $pgn->alamat }}</td>
                            <td>{{ $pgn->jabatan->jabatan ?? 'Jabatan tidak ditemukan' }}</td>
                            <td>
                                <button class="btn btn-danger hapusData" data-id="{{ $pgn->id_pengguna }}" data-hapus-url="{{ route('admin.pengguna.delete', $pgn->id_pengguna) }}">Hapus</button>
                                <button class="btn btn-primary editData" 
                                        data-id="{{ $pgn->id_pengguna }}" 
                                        data-nama="{{ $pgn->nama }}" 
                                        data-id_jabatan="{{ $pgn->id_jabatan }}" 
                                        data-NIK="{{ $pgn->NIK }}"
                                        data-alamat="{{ $pgn->alamat }}"
                                        data-no_hp="{{ $pgn->no_hp }}"
                                        data-toggle="modal" data-target="#editPenggunaModal">Edit</button>
                                <button class="btn btn-info detailData" data-id="{{ $pgn->id_pengguna }}" data-detail-url="{{ route('admin.pengguna.detail', $pgn->id_pengguna) }}">Detail</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="tambahPenggunaModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah Data Pengguna</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formTambahPengguna" action="{{ route('admin.pengguna.add') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <input class="form-control mb-3" type="text" name="nama" placeholder="Masukkan Nama Pengguna" required>
                        </div>
                        <div class="form-group">
                            <input class="form-control mb-3" type="text" name="NIK" placeholder="Masukkan NIK" required>
                        </div>
                        <div class="form-group">
                            <input class="form-control mb-3" type="text" name="alamat" placeholder="Masukkan Alamat" required>
                        </div>
                        <div class="form-group">
                            <input class="form-control mb-3" type="text" name="no_hp" placeholder="Masukkan No Hp" required>
                        </div>
                        <div class="form-group">
                            <select class="form-control mb-3" name="id_jabatan" required>
                                <option value="" disabled selected>Pilih Jabatan</option>
                                @foreach ($jabatan as $item)
                                    <option value="{{ $item->id_jabatan }}">{{ $item->jabatan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary" id="simpanPengguna">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div> 

    <!-- Modal Edit Pengguna -->
    <div class="modal fade" id="editPenggunaModal" tabindex="-1" role="dialog" aria-labelledby="editPenggunaModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editPenggunaModalLabel">Edit Pengguna</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div> 
                <div class="modal-body">
                    <form id="formEditPengguna" action="{{ route('admin.pengguna.update', ['id_pengguna' => ':id_pengguna']) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <input type="hidden" name="id_pengguna" value="">
                        </div>
                        <div class="form-group">
                            <label for="nama">Nama Pengguna</label>
                            <input class="form-control" type="text" name="nama" placeholder="Masukkan Nama Pengguna" required>
                        </div>
                        <div class="form-group">
                            <label for="NIK">NIK</label>
                            <input class="form-control" type="text" name="NIK" placeholder="Masukkan NIK" required>
                        </div>
                        <div class="form-group">
                            <label for="alamat">Alamat</label>
                            <input class="form-control" type="text" name="alamat" placeholder="Masukkan Alamat" required>
                        </div>
                        <div class="form-group">
                            <label for="no_hp">Nomor HP</label>
                            <input class="form-control" type="text" name="no_hp" placeholder="Masukkan No Hp" required>
                        </div>
                        <div class="form-group">
                            <label for="id_jabatan">Jabatan</label>
                            <select class="form-control" name="id_jabatan" required>
                                <option value="" disabled selected>Pilih Jabatan</option>
                                @foreach ($jabatan as $item)
                                    <option value="{{ $item->id_jabatan }}">{{ $item->jabatan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary" id="editPengguna">Simpan</button>
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
    $('#previewPengguna').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.pengguna.pengguna') }}",
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

    $('#simpanPengguna').on('click', function(e) {
        e.preventDefault();
        var data = $('#formTambahPengguna').serialize();
        $.ajax({
            url: "{{ route('admin.pengguna.add') }}",
            method: "POST",
            data: data,
            success: function(response) {
                $('#tambahPenggunaModal').modal('hide');
                $('#previewPengguna').DataTable().ajax.reload();
                Swal.fire('Sukses!', 'Data berhasil ditambahkan!', 'success');
            },
            error: function(response) {
                Swal.fire('Gagal!', 'Terjadi kesalahan, silakan coba lagi.', 'error');
            }
        });
    });

    $('#editPengguna').on('click', function(e) {
        e.preventDefault();
        var id_pengguna = $('#formEditPengguna').find('input[name="id_pengguna"]').val();
        var data = $('#formEditPengguna').serialize();
        $.ajax({
            url: "{{ route('admin.pengguna.update', ['id_pengguna' => ':id_pengguna']) }}".replace(':id_pengguna', id_pengguna),
            method: "PUT",
            data: data,
            success: function(response) {
                $('#editPenggunaModal').modal('hide');
                $('#previewPengguna').DataTable().ajax.reload();
                Swal.fire('Sukses!', 'Data berhasil diubah!', 'success');
            },
            error: function(response) {
                Swal.fire('Gagal!', 'Terjadi kesalahan, silakan coba lagi.', 'error');
            }
        });
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
