@extends('layouts.base_admin.base_dashboard')

@section('judul', 'Detail Surat Keluar')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Detail Surat Keluar</h1>
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
                    {{ $suratKeluar->id_suratkeluar }}
                </div>
            </div>

            <div class="row mb-1">
                <div class="col-md-2">
                    <strong>No Surat:</strong>
                </div>
                <div class="col-md-2">
                    {{ $suratKeluar->no_surat }}
                </div>
            </div>

            <div class="row mb-1">
                <div class="col-md-2">
                    <strong>Tanggal:</strong>
                </div>
                <div class="col-md-2">
                    {{ $suratKeluar->tgl_surat }}
                </div>
            </div>

            <div class="row mb-1">
                <div class="col-md-2">
                    <strong>Perihal:</strong>
                </div>
                <div class="col-md-2">
                    {{ $suratKeluar->perihal }}
                </div>
            </div>

            <div class="row mb-1">
                <div class="col-md-2">
                    <strong>Lampiran:</strong>
                </div>
                <div class="col-md-2">
                    {{ $suratKeluar->lampiran }}
                </div>
            </div>

            <div class="row mb-1">
                <div class="col-md-2">
                    <strong>Keterangan:</strong>
                </div>
                <div class="col-md-2">
                    {{ $suratKeluar->keterangan }}
                </div>
            </div>

            <div class="row mb-1">
                <div class="col-md-2">
                    <strong>Jenis Surat:</strong>
                </div>
                <div class="col-md-2">
                    {{ $suratKeluar->jenisSurat->jenis_surat }}
                </div>
            </div>

            <div class="row mb-1">
                <div class="col-md-2">
                    <strong>Asal Surat:</strong>
                </div>
                <div class="col-md-2">
                    {{ $suratKeluar->asalSurat->asal_surat }}
                </div>
            </div>

            <div class="row mb-1">
                <div class="col-md-2">
                    <strong>Sifat Surat:</strong>
                </div>
                <div class="col-md-2">
                    {{ $suratKeluar->sifatSurat->sifat_surat }}
                </div>
            </div>

        </div>
    </div>
</section>
@endsection
