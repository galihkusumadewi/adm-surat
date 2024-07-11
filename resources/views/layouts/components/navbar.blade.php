<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                <i class="fas fa-bars"></i>
            </a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="#" class="nav-link">SISTEM PENDATAAN DOKUMEN SURAT MASUK SURAT KELUAR</a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        @auth
            <li class="nav-item dropdown user-menu">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                    <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <li class="user-footer">
                        @if(Auth::user()->id_jabatan == 1)
                            <!-- Admin -->
                            <a href="{{ route('admin.logout') }}"
                               onclick="event.preventDefault(); document.getElementById('admin-logout-form').submit();"
                               class="btn btn-default btn-flat float-right">
                                <i class="ni ni-user-run"></i>
                                <span>Logout as Admin</span>
                            </a>
                            <form id="admin-logout-form" action="{{ route('admin.logout') }}" method="POST"
                                  style="display: none;">
                                @csrf
                            </form>
                        @elseif(Auth::user()->id_jabatan == 2)
                            <!-- Kabag -->
                            <a href="{{ route('logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                               class="btn btn-default btn-flat float-right">
                                <i class="ni ni-user-run"></i>
                                <span>Logout as Kabag</span>
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                  style="display: none;">
                                @csrf
                            </form>
                        @elseif(Auth::user()->id_jabatan == 3)
                            <!-- Kepala Dinas -->
                            <a href="{{ route('logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                               class="btn btn-default btn-flat float-right">
                                <i class="ni ni-user-run"></i>
                                <span>Logout as Kepala Dinas</span>
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                  style="display: none;">
                                @csrf
                            </form>
                        @elseif(Auth::user()->id_jabatan == 4)
                            <!-- Kepala Dinas -->
                            <a href="{{ route('logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                               class="btn btn-default btn-flat float-right">
                                <i class="ni ni-user-run"></i>
                                <span>Logout as Sekretaris</span>
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                  style="display: none;">
                                @csrf
                            </form>
                        @endif
                    </li>
                </ul>
            </li>
        @endauth
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>
    </ul>
</nav>
<!-- /.navbar -->
