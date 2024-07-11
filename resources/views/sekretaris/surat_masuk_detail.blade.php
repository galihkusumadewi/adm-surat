@extends('layouts.base_admin.base_dashboard')

@section('judul', 'Detail Surat Masuk')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Detail Surat Masuk</h1>
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
        <div class="card-body">
            <div class="row mb-1">
                <div class="col-md-2">
                    <strong>ID Surat Masuk:</strong>
                </div>
                <div class="col-md-2">
                    {{ $suratMasuk->id_suratmasuk }}
                </div>
            </div>

            <div class="row mb-1">
                <div class="col-md-2">
                    <strong>No Surat:</strong>
                </div>
                <div class="col-md-2">
                    {{ $suratMasuk->no_surat }}
                </div>
            </div>

            <div class="row mb-1">
                <div class="col-md-2">
                    <strong>Tanggal:</strong>
                </div>
                <div class="col-md-2">
                    {{ $suratMasuk->tanggal }}
                </div>
            </div>

            <div class="row mb-1">
                <div class="col-md-2">
                    <strong>Tujuan:</strong>
                </div>
                <div class="col-md-2">
                    {{ $suratMasuk->tujuan }}
                </div>
            </div>

            <div class="row mb-1">
                <div class="col-md-2">
                    <strong>Perihal:</strong>
                </div>
                <div class="col-md-2">
                    {{ $suratMasuk->perihal }}
                </div>
            </div>

            <div class="row mb-1">
                <div class="col-md-2">
                    <strong>Lampiran:</strong>
                </div>
                <div class="col-md-2">
                    {{ $suratMasuk->lampiran }}
                </div>
            </div>

            <div class="row mb-1">
                <div class="col-md-2">
                    <strong>Keterangan:</strong>
                </div>
                <div class="col-md-2">
                    {{ $suratMasuk->Keterangan }}
                </div>
            </div>

            <div class="row mb-1">
                <div class="col-md-2">
                    <strong>Jenis Surat:</strong>
                </div>
                <div class="col-md-2">
                    {{ $suratMasuk->jenisSurat->jenis_surat }}
                </div>
            </div>

            <div class="row mb-1">
                <div class="col-md-2">
                    <strong>Jabatan:</strong>
                </div>
                <div class="col-md-2">
                    {{ $suratMasuk->jabatan->jabatan }}
                </div>
            </div>

            <div class="row mb-1">
                <div class="col-md-2">
                    <strong>Asal Surat:</strong>
                </div>
                <div class="col-md-2">
                    {{ $suratMasuk->asalSurat->asal_surat }}
                </div>
            </div>

            <div class="row mb-1">
                <div class="col-md-2">
                    <strong>Sifat:</strong>
                </div>
                <div class="col-md-2">
                    {{ $suratMasuk->sifatSurat->sifat_surat }}
                </div>
            </div>

            <div class="row mb-1">
                <div class="col-md-2">
                    <strong>Pengguna:</strong>
                </div>
                <div class="col-md-2">
                    {{ $suratMasuk->pengguna->nama }}
                </div>
            </div>

            <div class="row mb-1">
                <div class="col-md-2">
                    <strong>File Surat:</strong>
                </div>
                <div class="col-md-2">
                    <a href="{{ asset('uploads/surat_masuk/' . $suratMasuk->file_surat) }}" target="_blank">Lihat File</a>
                </div>
            </div>

            <div class="row mb-1">
                <div class="col-md-12 text-center">
                    <a href="{{ route('sekretaris.disposisi.tambah_disposisi', ['id_suratmasuk' => $suratMasuk->id_suratmasuk]) }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-file-signature"></i> Buat Disposisi
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
