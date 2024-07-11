@extends('layouts.base_admin.base_dashboard')

@section('judul', 'Tambah Data Surat Keluar')

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
                <h1>TAMBAH SURAT KELUAR</h1>
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
            <form id="formTambahSuratKeluar" action="{{ route('admin.keluar.tambah') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="no_surat">No. Surat</label>
                            <input name="no_surat" class="form-control" type="text" placeholder="No. Surat" required>
                        </div>
                        <div class="form-group">
                            <label for="tgl_surat">Tanggal</label>
                            <input name="tgl_surat" class="form-control" type="date" placeholder="tgl_surat" required>
                        </div>
                        <div class="form-group">
                            <label for="Asal Surat">Asal Surat</label>
                            <input name="asal" class="form-control" type="text" placeholder="Asal Surat" value="Dinas Sosial D.I. Yogyakarta" readonly>
                        </div>
                        <div class="form-group">
                            <label for="asal_surat">Tujuan</label>
                            <select name="id_asal_surat" class="form-control" required>
                                <option value="">Tujuan</option>
                                @foreach($asalSurat as $asal)
                                    <option value="{{ $asal->id_asal_surat }}">{{ $asal->asal_surat }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="jenis_surat">Jenis Surat</label>
                            <select name="id_jenis" class="form-control" required>
                                <option value="">Pilih Jenis Surat</option>
                                @foreach($jenisSurat as $jenis)
                                    <option value="{{ $jenis->id_jenis }}">{{ $jenis->jenis_surat }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                    <div class="form-group">
                            <label for="sifat_surat">Sifat Surat</label>
                            <select name="id_sifat" class="form-control" required>
                                <option value="">Pilih Sifat Surat</option>
                                @foreach($sifatSurat as $sifat)
                                    <option value="{{ $sifat->id_sifat }}">{{ $sifat->sifat_surat }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="perihal">Perihal</label>
                            <input name="perihal" class="form-control" type="text" placeholder="Perihal">
                        </div>
                        <div class="form-group">
                            <label for="lampiran">Lampiran</label>
                            <input name="lampiran" class="form-control" type="text" placeholder="Lampiran">
                        </div>
                        <div class="form-group">
                            <label for="file">Upload File</label>
                            <input name="file_surat" class="form-control" type="file">
                        </div>
                        <div class="form-group">
                            <label for="keterangan">Keterangan</label>
                            <textarea name="keterangan" class="form-control" placeholder="Keterangan"></textarea>
                        </div> 
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="{{ route('admin.keluar.surat_keluar') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection

@section('script_footer')
<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
<script>
      $(document).ready(function () {
    $('#formTambahSuratKeluar').on('submit', function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        
        // Tambahkan CSRF token ke formData
        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
        
        $.ajax({
            url: $(this).attr('action'),
            method: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                Swal.fire('Sukses!', 'Data berhasil ditambahkan!', 'success').then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "{{ route('admin.keluar.surat_keluar') }}";
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
