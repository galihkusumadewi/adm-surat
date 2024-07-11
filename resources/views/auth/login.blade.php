@extends('layouts.base_admin.base_auth')
@section('judul', 'Halaman Login')
@section('content')
<div class="login-page" style="background-image: url('{{ asset('images/bg-1.png') }}'); background-size: cover; height: 100vh; display: flex; align-items: center; justify-content: center;">
    <div class="login-box" style="width: 600px; background: rgba(255, 255, 255, 0.9); padding: 40px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
        <div class="login-logo text-center" style="display: flex; align-items: center; justify-content: center; margin-bottom: 20px;">
            <a href="#">
                <img src="{{ asset('images/dinsos-diy.png') }}" alt="Logo Dinas Sosial Yogyakarta" style="max-width: 350px; height: auto; margin-right: 15px;">
                <h2 style="font-size: 24px; margin: 0; font-weight: bold;">Sistem Administrasi Surat Masuk Surat Keluar</h2>
            </a>
        </div>
        <!-- /.login-logo -->
        <div class="card" style="border: none;">
            <div class="card-body login-card-body" style="padding: 0;">
                <p class="login-box-msg" style="font-size: 20px;">Masuk untuk memulai sesi Anda</p>

                <form action="{{ route('login') }}" method="post">
                    @csrf
                    <div class="input-group mb-3">
                        <input
                            id="email"
                            type="email"
                            placeholder="Email"
                            class="form-control @error('email') is-invalid @enderror"
                            name="email"
                            value="{{ old('email') }}"
                            required="required"
                            autocomplete="email"
                            autofocus="autofocus"
                            style="height: 50px; font-size: 18px;">
                        <div class="input-group-append">
                            <div class="input-group-text" style="width: 50px; text-align: center;">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                        @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="input-group mb-3">
                        <input
                            id="password"
                            type="password"
                            placeholder="Password"
                            class="form-control @error('password') is-invalid @enderror"
                            name="password"
                            required="required"
                            autocomplete="current-password"
                            style="height: 50px; font-size: 18px;">
                        <div class="input-group-append">
                            <div class="input-group-text" style="width: 50px; text-align: center;">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                        @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="row">
                        <div class="col-8">
                            <div class="icheck-primary">
                                <input type="checkbox" id="remember">
                                <label for="remember" style="font-size: 16px;">
                                    Ingat sesi saya
                                </label>
                            </div>
                        </div>
                        <!-- /.col -->
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block" style="height: 50px; font-size: 18px; background-color: #007bff; border-color: #007bff;">Masuk</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>
            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
</div>
@endsection

<!-- /.login-box -->

<style>
    .login-page {
        background-image: url('{{ asset('images/bg-1.png') }}');  /* Ganti dengan URL gambar latar belakang */
        background-size: cover;
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .login-box {
        width: 600px;
        background: rgba(255, 255, 255, 0.9);  /* Warna latar belakang dengan transparansi */
        padding: 40px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .login-logo {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
    }

    .login-logo img {
        max-width: 150px;
        margin-right: 15px;
    }

    .login-logo h2 {
        font-size: 24px;
        margin: 0;
        font-weight: bold;
    }

    .card {
        border: none;
    }

    .login-card-body {
        padding: 0;
    }

    .login-box-msg {
        font-size: 18px;
    }

    .input-group-text {
        width: 50px;
        text-align: center;
    }

    .input-group .form-control {
        height: 50px;
        font-size: 18px;
        border: 1px solid #ced4da;
        transition: border-color 0.3s, box-shadow 0.3s;
    }

    .input-group .form-control:focus {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
        height: 50px;
        font-size: 18px;
        transition: background-color 0.3s, border-color 0.3s;
    }

    .btn-primary:hover {
        background-color: #0056b3;
        border-color: #0056b3;
    }

    .icheck-primary label {
        font-size: 16px;
    }
</style>
