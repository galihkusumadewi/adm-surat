@extends('layouts.base_admin.base_dashboard')

@section('judul', 'Edit Data Surat Keluar')

@section('script_head')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>EDIT SURAT KELUAR</h1>
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
            <form id="formEditSuratKeluar" action="{{ route('admin.keluar.update', ['id_suratkeluar' => $suratKeluar->id_suratkeluar]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="no_surat">No. Surat</label>
                            <input name="no_surat" class="form-control" type="text" value="{{ $suratKeluar->no_surat }}" placeholder="No. Surat">
                        </div>
                        <div class="form-group">
                            <label for="tgl_surat">Tanggal</label>
                            <input name="tgl_surat" class="form-control" type="date" value="{{ $suratKeluar->tgl_surat }}" placeholder="Tanggal">
                        </div>
                        <div class="form-group">
                            <label for="surat">Asal Surat</label>
                            <input name="surat" class="form-control" type="text" value="{{ $suratKeluar->asal }}" placeholder="Asal Surat" readonly>
                        </div>
                        <div class="form-group">
                            <label for="asal_surat">Tujuan</label>
                            <select name="id_asal_surat" class="form-control">
                                <option value="">Pilih Tujuan</option>
                                @foreach($asalSurat as $asal)
                                    <option value="{{ $asal->id_asal_surat }}" {{ $asal->id_asal_surat == $suratKeluar->id_asal_surat ? 'selected' : '' }}>{{ $asal->asal_surat }}</option>
                                @endforeach
                            </select>
                        </div>
                        <!--  -->
                        <div class="form-group">
                            <label for="jenis_surat">Jenis Surat</label>
                            <select name="id_jenis" class="form-control">
                                <option value="">Pilih Jenis Surat</option>
                                @foreach($jenisSurat as $jenis)
                                    <option value="{{ $jenis->id_jenis }}" {{ $jenis->id_jenis == $suratKeluar->id_jenis ? 'selected' : '' }}>{{ $jenis->jenis_surat }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                    <div class="form-group">
                            <label for="sifat_surat">Sifat Surat</label>
                            <select name="id_sifat" class="form-control">
                                <option value="">Pilih Sifat Surat</option>
                                @foreach($sifatSurat as $sifat)
                                    <option value="{{ $sifat->id_sifat }}" {{ $sifat->id_sifat == $suratKeluar->id_sifat ? 'selected' : '' }}>{{ $sifat->sifat_surat }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="perihal">Perihal</label>
                            <input name="perihal" class="form-control" type="text" value="{{ $suratKeluar->perihal }}" placeholder="Perihal">
                        </div>
                        <div class="form-group">
                            <label for="lampiran">Lampiran</label>
                            <input name="lampiran" class="form-control" type="text" value="{{ $suratKeluar->lampiran }}" placeholder="Lampiran">
                        </div>
                        <div class="form-group">
                            <label for="file">Upload File</label>
                            <input name="file_surat" class="form-control" type="file">
                            @if($suratKeluar->file_surat)
                                <p>File saat ini: {{ $suratKeluar->file_surat }}</p>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="keterangan">Keterangan</label>
                            <textarea name="keterangan" class="form-control" placeholder="Keterangan">{{ $suratKeluar->keterangan }}</textarea>
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
        $('#formEditSuratKeluar').on('submit', function (e) {
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
