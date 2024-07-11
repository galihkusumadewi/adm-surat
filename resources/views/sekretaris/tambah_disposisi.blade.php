@extends('layouts.base_admin.base_dashboard')
@section('judul', 'Tambah Data Disposisi')

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
                <h1>TAMBAH DISPOSISI</h1>
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
    <div class="card">
        <div class="card-header">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form id="formTambahDisposisi" action="{{ route('sekretaris.disposisi.add', ['id_suratmasuk' => $id_suratmasuk]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="no_disposisi">No Disposisi</label>
                            <input name="no_disposisi" class="form-control" type="text" placeholder="No. Disposisi" value="{{ old('no_disposisi') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="tanggal">Tanggal</label>
                            <input name="tanggal" class="form-control" type="date" placeholder="Tanggal" value="{{ old('tanggal', $suratMasuk->tanggal) }}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="sifat_disposisi">Sifat Disposisi</label>
                            <input name="sifat_disposisi" class="form-control" type="text" placeholder="Sifat Disposisi" value="{{ old('sifat_disposisi') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="jenis_disposisi">Jenis Disposisi</label>
                            <input name="jenis_disposisi" class="form-control" type="text" placeholder="Jenis Disposisi" value="{{ old('jenis_disposisi') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="asal_surat">Asal Surat</label>
                            <input type="text" class="form-control" value="{{ $asalSuratDetail->asal_surat }}" disabled>
                            <input type="hidden" name="id_asal_surat" value="{{ $asalSuratDetail->id_asal_surat }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="surat_masuk">Surat Masuk</label>
                            <input type="text" class="form-control" value="{{ $suratMasuk->no_surat }}" disabled>
                            <input type="hidden" name="id_suratmasuk" value="{{ $suratMasuk->id_suratmasuk }}">
                        </div>
                        <div class="form-group">
                            <label for="perihal">Perihal</label>
                            <input name="perihal" class="form-control" type="text" placeholder="Perihal" value="{{ old('perihal', $suratMasuk->perihal) }}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="instruksi">Instruksi</label>
                            <input name="instruksi" class="form-control" type="text" placeholder="Instruksi" value="{{ old('instruksi') }}">
                        </div>
                        <div class="form-group">
                            <label for="keterangan">Keterangan</label>
                            <textarea name="keterangan" class="form-control" placeholder="Keterangan">{{ old('keterangan') }}</textarea>
                        </div> 
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="{{ route('sekretaris.disposisi.disposisi') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
