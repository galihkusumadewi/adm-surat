@extends('layouts.base_admin.base_dashboard')@section('judul', 'Olah Data Admin')
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
                <h1>DATA ADMIN</h1>
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
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#tambahAdminModal">
                    <i class="fas fa-plus"></i> Tambah Data
                </button>
            </div>
        </div>
        <div class="card-body p-0" style="margin: 20px">
            <table id="previewAdmin" class="table table-striped table-bordered display" style="width:100%">
                <thead>
                    <tr>
                        <th>ID Admin</th>
                        <th>Nama</th>
                        <th>Jabatan</th>
                        <th>Email</th>
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

    <!-- Modal untuk tambah admin -->
    <div class="modal fade" id="tambahAdminModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Data Admin</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formTambahAdmin" action="{{ route('admin.data_admin.add') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <input class="form-control mb-3" type="text" name="name" placeholder="Masukkan Nama Pengguna" required>
                    </div>
                    <div class="form-group">
                        <input class="form-control mb-3" type="text" name="email" placeholder="Masukkan Email" required>
                    </div>
                    <div class="form-group">
                        <input class="form-control mb-3" type="password" name="password" placeholder="Masukkan Password" required>
                    </div>
                    <div class="form-group">
                        <input class="form-control mb-3" type="text" name="username" placeholder="Masukkan Username" required>
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
                        <button type="submit" class="btn btn-primary" id="simpanAdmin">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


    <!-- Modal Edit Admin -->
    <div class="modal fade" id="editAdminModal" tabindex="-1" role="dialog" aria-labelledby="editAdminModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editAdminModalLabel">Edit Pengguna</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formEditAdmin" action="{{ route('admin.data_admin.update', ['id' => ':id']) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <input type="hidden" id="id" name="id" value="">
                    </div>
                    <div class="form-group">
                        <label for="nama">Nama Pengguna</label>
                        <input class="form-control" type="text" id="nama" name="name" placeholder="Masukkan Nama Pengguna" required>
                    </div>
                    <div class="form-group">
                        <label for="nama">Email</label>
                        <input class="form-control" type="text" id="email" name="email" placeholder="Masukkan Email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input class="form-control" type="text" name="password" placeholder="Masukkan Password" required>
                    </div>
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input class="form-control" type="text" id="username" name="username" placeholder="Masukkan Username" required>
                    </div>
                    <div class="form-group">
                        <label for="id_jabatan">Jabatan</label>
                        <select class="form-control" id="id_jabatan" name="id_jabatan" required>
                            <option value="" disabled selected>Pilih Jabatan</option>
                            @foreach ($jabatan as $item)
                                <option value="{{ $item->id_jabatan }}">{{ $item->jabatan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" id="editAdmin">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

</section>


@endsection @section('script_footer')
<script
    type="text/javascript"
    src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script
    type="text/javascript"
    src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>>
<script>
$(document).ready(function () {
        $('#previewAdmin').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.data_admin.data_admin') }}",
            columns: [
                { data: 'id', name: 'id' },
                { data: 'name', name: 'name' },
                { data: 'jabatan', name: 'id_jabatan' },
                { data: 'email', name: 'email' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
            language: {
            }
        });

        $('#simpanAdmin').on('click', function(e) {
            e.preventDefault();
            var data = $('#formTambahAdmin').serialize();
            $.ajax({
                url: "{{ route('admin.data_admin.add') }}",
                method: "POST", // Gunakan metode POST di sini
                data: data,
                success: function(response) {
                    $('#tambahAdminModal').modal('hide');
                    $('#previewAdmin').DataTable().ajax.reload();
                    Swal.fire('Sukses!', 'Data berhasil ditambahkan!', 'success');
                },
                error: function(response) {
                    Swal.fire('Gagal!', 'Terjadi kesalahan, silakan coba lagi.', 'error');
                }
            });
        });

        // Script untuk hapus admin
        $('#previewAdmin').on('click', '.hapusData', function () {
            var id = $(this).data('id');
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
                            $('#previewAdmin').DataTable().ajax.reload();
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
            // Menampilkan data di modal saat tombol edit diklik
            $('#editAdminModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var id = button.data('id');
                var name = button.data('name');
                var email = button.data('email');
                var username = button.data('username');
                var id_jabatan = button.data('id_jabatan');

                var modal = $(this);
                modal.find('.modal-title').text('Edit Pengguna ' + name); // Menampilkan nama pengguna di judul modal
                modal.find('form').attr('action', "{{ route('admin.data_admin.update', ['id' => ':id']) }}".replace(':id', id));
                modal.find('input[name="id"]').val(id);
                modal.find('input[name="name"]').val(name);
                modal.find('input[name="username"]').val(username);
                modal.find('input[name="email"]').val(email); // Masukkan nilai email 
                modal.find('select[name="id_jabatan"]').val(id_jabatan);
            });

    // Mengirim data update admin ke server
    $('#formEditAdmin').on('submit', function (e) {
        e.preventDefault();
        var id = $('input[name="id"]').val();
        var data = $(this).serialize();

        $.ajax({
            url: "{{ route('admin.data_admin.update', ['id' => ':id']) }}".replace(':id', id),
            method: "PUT",
            data: data,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                $('#editAdminModal').modal('hide');
                $('#previewAdmin').DataTable().ajax.reload();
                Swal.fire('Sukses!', 'Data berhasil diubah!', 'success');
            },
            error: function (response) {
                Swal.fire('Gagal!', 'Terjadi kesalahan, silakan coba lagi.', 'error');
            }
        });
    });
});




    });
</script>
@endsection