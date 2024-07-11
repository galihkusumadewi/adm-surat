@extends('layouts.base_admin.base_dashboard')

@section('judul', 'Detail Pengguna')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Detail Pengguna</h1>
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
            <h5>ID Pengguna: {{ $pengguna->id_pengguna }}</h5>
            <p>Nama Pengguna: {{ $pengguna->nama }}</p>
            <p>NIK: {{ $pengguna->NIK }}</p>
            <p>No HP: {{ $pengguna->no_hp }}</p>
            <p>Jabatan: {{ $pengguna->jabatan->jabatan }}</p>
        </div>
    </div>
</section>
@endsection
