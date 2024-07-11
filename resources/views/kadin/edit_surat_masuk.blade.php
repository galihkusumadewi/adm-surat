@extends('layouts.base_admin.base_dashboard')

@section('judul', 'Edit Data Surat Masuk')
 
@section('script_head')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>EDIT SURAT MASUK</h1>
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
            <form id="formEditSuratMasuk" action="{{ route('kadin.masuk.update', ['id_suratmasuk' => $suratMasuk->id_suratmasuk]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="no_surat">No. Surat</label>
                            <input name="no_surat" class="form-control" type="text" value="{{ $suratMasuk->no_surat }}" placeholder="No. Surat">
                        </div>
                        <div class="form-group">
                            <label for="tanggal">Tanggal</label>
                            <input name="tanggal" class="form-control" type="date" value="{{ $suratMasuk->tanggal }}" placeholder="Tanggal">
                        </div>
                        <div class="form-group">
                            <label for="tujuan">Tujuan</label>
                            <input name="tujuan" class="form-control" type="text" value="{{ $suratMasuk->tujuan }}" placeholder="Tujuan" readonly>
                        </div>
                        <div class="form-group">
                            <label for="asal_surat">Asal Surat</label>
                            <select name="id_asal_surat" class="form-control">
                                <option value="">Pilih Asal Surat</option>
                                @foreach($asalSurat as $asal)
                                    <option value="{{ $asal->id_asal_surat }}" {{ $asal->id_asal_surat == $suratMasuk->id_asal_surat ? 'selected' : '' }}>{{ $asal->asal_surat }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="sifat_surat">Sifat Surat</label>
                            <select name="id_sifat" class="form-control">
                                <option value="">Pilih Sifat Surat</option>
                                @foreach($sifatSurat as $sifat)
                                    <option value="{{ $sifat->id_sifat }}" {{ $sifat->id_sifat == $suratMasuk->id_sifat ? 'selected' : '' }}>{{ $sifat->sifat_surat }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="jenis_surat">Jenis Surat</label>
                            <select name="id_jenis" class="form-control">
                                <option value="">Pilih Jenis Surat</option>
                                @foreach($jenisSurat as $jenis)
                                    <option value="{{ $jenis->id_jenis }}" {{ $jenis->id_jenis == $suratMasuk->id_jenis ? 'selected' : '' }}>{{ $jenis->jenis_surat }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="jabatan">Jabatan</label>
                            <select name="id_jabatan" class="form-control">
                                <option value="">Pilih Jabatan</option>
                                @foreach($jabatan as $jab)
                                    <option value="{{ $jab->id_jabatan }}" {{ $jab->id_jabatan == $suratMasuk->id_jabatan ? 'selected' : '' }}>{{ $jab->jabatan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="pengguna">Pengguna</label>
                            <select name="id_pengguna" class="form-control">
                                <option value="">Pilih Pengguna</option>
                                @foreach($pengguna as $pgn)
                                    <option value="{{ $pgn->id_pengguna }}" {{ $pgn->id_pengguna == $suratMasuk->id_pengguna ? 'selected' : '' }}>{{ $pgn->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="lampiran">Lampiran</label>
                            <input name="lampiran" class="form-control" type="text" value="{{ $suratMasuk->lampiran }}" placeholder="Lampiran">
                        </div>
                        <div class="form-group">
                            <label for="perihal">Perihal</label>
                            <input name="perihal" class="form-control" type="text" value="{{ $suratMasuk->perihal }}" placeholder="Perihal">
                        </div>
                        <div class="form-group">
                            <label for="file_surat">Upload File</label>
                            <input name="file_surat" class="form-control" type="file">
                            @if($suratMasuk->file_surat)
                                <p>File saat ini: {{ $suratMasuk->file_surat }}</p>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="keterangan">Keterangan</label>
                            <textarea name="keterangan" class="form-control"  placeholder="Keterangan">{{ $suratMasuk->keterangan }}</textarea>
                        </div> 
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="{{ route('kadin.masuk.surat_masuk') }}" class="btn btn-secondary">Batal</a>
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
        $('#formEditSuratMasuk').on('submit', function (e) {
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
                            window.location.href = "{{ route('kadin.masuk.surat_masuk') }}";
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
