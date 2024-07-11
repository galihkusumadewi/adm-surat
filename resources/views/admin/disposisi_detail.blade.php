@extends('layouts.base_admin.base_dashboard')

@section('judul', 'Detail Disposisi')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Detail Disposisi</h1>
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
                    <strong>ID Disposisi:</strong>
                </div>
                <div class="col-md-2">
                    {{ $disposisi->id_disposisi }}
                </div>
            </div>

            <div class="row mb-1">
                <div class="col-md-2">
                    <strong>No Disposisi:</strong>
                </div>
                <div class="col-md-4">
                    {{ $disposisi->no_disposisi }}
                </div>
            </div>

            <div class="row mb-1">
                <div class="col-md-2">
                    <strong>Tanggal:</strong>
                </div>
                <div class="col-md-2">
                    {{ $disposisi->tanggal }}
                </div>
            </div>

            <div class="row mb-1">
                <div class="col-md-2">
                    <strong>Sifat Surat:</strong>
                </div>
                <div class="col-md-2">
                    {{ $disposisi->sifat_disposisi }}
                </div>
            </div>

            <div class="row mb-1">
                <div class="col-md-2">
                    <strong>Jenis Surat:</strong>
                </div>
                <div class="col-md-2">
                    {{ $disposisi->jenis_disposisi }}
                </div>
            </div>

            <div class="row mb-1">
                <div class="col-md-2">
                    <strong>Perihal:</strong>
                </div>
                <div class="col-md-2">
                    {{ $disposisi->perihal }}
                </div>
            </div>

            <div class="row mb-1">
                <div class="col-md-2">
                    <strong>Instruksi:</strong>
                </div>
                <div class="col-md-2">
                    {{ $disposisi->instruksi }}
                </div>
            </div>

            <div class="row mb-1">
                <div class="col-md-2">
                    <strong>Keterangan:</strong>
                </div>
                <div class="col-md-2">
                    {{ $disposisi->keterangan }}
                </div>
            </div>

            <div class="row mb-1">
                <div class="col-md-2">
                    <strong>Surat Masuk:</strong>
                </div>
                <div class="col-md-2">
                    {{ $disposisi->suratMasuk->no_surat }}
                </div>
            </div>

            <div class="row mb-1">
                <div class="col-md-2">
                    <strong>Asal Surat:</strong>
                </div>
                <div class="col-md-2">
                    {{ $disposisi->asalSurat->asal_surat }}
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
