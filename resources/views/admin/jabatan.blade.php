@extends('layouts.base_admin.base_dashboard')
@section('judul', 'Olah Data Jabatan')
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
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#tambahJabatanModal">
                    <i class="fas fa-plus"></i> Tambah Data
                </button>
            </div>
        </div>
        <div class="card-body p-0" style="margin: 20px">
            <table id="previewJabatan" class="table table-striped table-bordered display" style="width:100%">
                <thead>
                    <tr>
                        <th>ID Jabatan</th>
                        <th>Jabatan Surat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="tambahJabatanModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah Data Jabatan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formTambahJabatan" action="{{ route('admin.jabatan.add') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <input class="form-control mb-3" type="text" name="jabatan" placeholder="Masukkan Nama Jabatan" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary" id="simpanJabatan">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editJabatanModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Data Jabatan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formEditJabatan" action="{{ route('admin.jabatan.update', ['id_jabatan' => ':id_jabatan']) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="jabatan">Jabatan Surat</label>
                        <input type="text" class="form-control" id="jabatan" name="jabatan" required>
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

@endsection

@section('script_footer')
<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function () {
    $('#previewJabatan').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.jabatan.jabatan') }}",
        columns: [
            { data: 'id_jabatan', name: 'id_jabatan' },
            { data: 'jabatan', name: 'jabatan' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        language: {
            // ... (Bahasa konfigurasi lainnya)
        }
    });

    $('#simpanJabatan').on('click', function(e) {
        e.preventDefault();
        var data = $('#formTambahJabatan').serialize();
        $.ajax({
            url: "{{ route('admin.jabatan.add') }}",
            method: "POST",
            data: data,
            success: function(response) {
                $('#tambahJabatanModal').modal('hide');
                $('#previewJabatan').DataTable().ajax.reload();
                setTimeout(function() {
                    Swal.fire('Sukses!', 'Data berhasil ditambahkan!', 'success');
                }, 200); // Tambahkan delay 200 ms
            },
            error: function(response) {
                Swal.fire('Gagal!', 'Terjadi kesalahan, silakan coba lagi.', 'error');
            }
        });
    });

    $('#editJabatan').on('click', function(e) {
        e.preventDefault();
        
        var id_jabatan = $(this).data('id'); // Assuming the button has the data-id attribute
        var data = $('#formEditJabatan').serialize();
        
        $.ajax({
            url: "{{ route('admin.jabatan.update', ['id_jabatan' => ':id_jabatan']) }}".replace(':id_jabatan', id_jabatan),
            method: "POST",
            data: data,
            success: function(response) {
                $('#editJabatanModal').modal('hide');
                $('#previewJabatan').DataTable().ajax.reload();
                setTimeout(function() {
                    Swal.fire('Sukses!', 'Data berhasil diubah!', 'success');
                }, 200); // Tambahkan delay 200 ms
            },
            error: function(response) {
                Swal.fire('Gagal!', 'Terjadi kesalahan, silakan coba lagi.', 'error');
            }
        });
    });

    $('#previewJabatan').on('click', '.hapusData', function () {
        var id_jabatan = $(this).data('id');
        var hapusUrl = "{{ route('admin.jabatan.delete', ['id_jabatan' => ':id_jabatan']) }}";
        hapusUrl = hapusUrl.replace(':id_jabatan', id_jabatan);

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
                        $('#previewJabatan').DataTable().ajax.reload();
                        setTimeout(function() {
                            Swal.fire('Sukses!', 'Data berhasil dihapus!', 'success');
                        }, 200); // Tambahkan delay 200 ms
                    },
                    error: function(response) {
                        Swal.fire('Gagal!', 'Terjadi kesalahan, silakan coba lagi.', 'error');
                    }
                });
            }
        });
    });

    $('#editJabatanModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); 
        var id_jabatan = button.data('id');
        var jabatan = button.data('jabatan'); 
        
        // Mengatur nilai form di dalam modal
        var modal = $(this);
        modal.find('.modal-title').text('Edit Data Jabatan ' + id_jabatan);
        modal.find('form').attr('action', "{{ route('admin.jabatan.update', ['id_jabatan' => ':id_jabatan']) }}".replace(':id_jabatan', id_jabatan));
        modal.find('input[name="jabatan"]').val(jabatan);
    });
});
</script>
@endsection
