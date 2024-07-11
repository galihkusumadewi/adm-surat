@extends('layouts.base_admin.base_dashboard')@section('judul', 'Edit Data Disposisi')
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
                <h1>EDIT DISPOSISI</h1>
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
        <form id="formEditDisposisi" action="{{ route('kadin.disposisi.update', ['id_disposisi' => $disposisi->id_disposisi]) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="no_disposisi">No Disposisi</label>
                <input name="no_disposisi" class="form-control" type="text" value="{{ $disposisi->no_disposisi }}" placeholder="No. Disposisi" required>
            </div>
            <div class="form-group">
                <label for="tanggal">Tanggal</label>
                <input name="tanggal" class="form-control" type="date" value="{{ $disposisi->tanggal }}" placeholder="Tanggal" required>
            </div>
            <div class="form-group">
                <label for="sifat_disposisi">Sifat Disposisi</label>
                <input name="sifat_disposisi" class="form-control" type="text" value="{{ $disposisi->sifat_disposisi }}" placeholder="Sifat Disposisi" required>
            </div>
            <div class="form-group">
                <label for="jenis_disposisi">Jenis Disposisi</label>
                <input name="jenis_disposisi" class="form-control" type="text" value="{{ $disposisi->jenis_disposisi }}" placeholder="Jenis Disposisi" required>
            </div>
                <div class="form-group">
                    <label for="asal_surat">Asal Surat</label>
                    <select name="id_asal_surat" class="form-control">
                    <option value="">Pilih Asal Surat</option>
                        @foreach($asalSurat as $asal)
                            <option value="{{ $asal->id_asal_surat }}" {{ $asal->id_asal_surat == $disposisi->id_asal_surat ? 'selected' : '' }}>{{ $asal->asal_surat }}</option>
                        @endforeach
                    </select>
                </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="surat_masuk">Surat Masuk</label>
                <select name="id_suratmasuk" class="form-control">
                <option value="">Pilih Surat Masuk</option>
                    @foreach($suratMasuk as $masuk)
                        <option value="{{ $masuk->id_suratmasuk }}" {{ $masuk->id_suratmasuk == $disposisi->id_suratmasuk ? 'selected' : '' }}>{{ $masuk->no_surat }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="perihal">Perihal</label>
                <input name="perihal" class="form-control" type="text" value="{{ $disposisi->perihal }}" placeholder="Perihal" required>
            </div>
            <div class="form-group">
                <label for="instruksi">Instruksi</label>
                <input name="instruksi" class="form-control" type="text" value="{{ $disposisi->instruksi }}" placeholder="Instruksi">
            </div>
            <div class="form-group">
                <label for="keterangan">Keterangan</label>
                <textarea name="keterangan" class="form-control" placeholder="Keterangan">{{ $disposisi->keterangan }}</textarea>
            </div> 
        </div>
    </div>
    <div class="modal-footer">
        <a href="{{ route('kadin.disposisi.disposisi') }}" class="btn btn-secondary">Batal</a>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </div>
</form>

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

<script>
    $(document).ready(function () {
        $('#formEditDisposisi').on('submit', function (e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                url: $(this).attr('action'),
                method: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    Swal.fire('Sukses!', 'Data berhasil diubah!', 'success').then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = "{{ route('kadin.disposisi.disposisi') }}";
                        }
                    });
                },
                error: function (response) {
                    Swal.fire('Gagal!', 'Terjadi kesalahan, silakan coba lagi.', 'error');
                }
            });
        });
    });
</script>
@endsection
