@php
$links = [
    [
        "text" => "Dashboard",
        "icon" => "fas fa-home",
        "is_multi" => false,
        "roles" => [1, 2, 3, 4], // Admin, Kabag, Kadin, Sekretaris
        "routes" => [
            1 => route('admin.home'),
            2 => route('kabag.home'),
            3 => route('kadin.home'),
            4 => route('sekretaris.home'),
        ]
    ],
    [
        "text" => "Olah Data Admin",
        "icon" => "fas fa-envelope",
        "is_multi" => false,
        "roles" => [1], // Admin
        "routes" => [
            1 => route('admin.data_admin.data_admin'),
        ]
    ],
    [
        "text" => "Olah Data Pengguna",
        "icon" => "fas fa-envelope",
        "is_multi" => false,
        "roles" => [1, 3, 4], // Admin, Kadin, Sekretaris
        "routes" => [
            1 => route('admin.pengguna.pengguna'),
            3 => route('kadin.pengguna.pengguna'),
            4 => route('sekretaris.pengguna.pengguna'),
        ]
    ],
    [
        "text" => "Olah Data Jenis Surat",
        "icon" => "fas fa-paper-plane",
        "is_multi" => false,
        "roles" => [1], // Admin
        "routes" => [
            1 => route('admin.jenis.jenis_surat'),
        ]
    ],
    [
        "text" => "Olah Data Sifat Surat",
        "icon" => "fas fa-file-alt",
        "is_multi" => false,
        "roles" => [1], // Admin
        "routes" => [
            1 => route('admin.sifat.sifat_surat'),
        ]
    ],
    [
        "text" => "Olah Data Jabatan",
        "icon" => "fas fa-briefcase",
        "is_multi" => false,
        "roles" => [1], // Admin
        "routes" => [
            1 => route('admin.jabatan.jabatan'),
        ]
    ],
    [
        "text" => "Olah Data Asal Surat",
        "icon" => "fas fa-map-marker-alt",
        "is_multi" => false,
        "roles" => [1], // Admin
        "routes" => [
            1 => route('admin.asal.asal_surat'),
        ]
    ],
    [
        "text" => "Surat Masuk",
        "icon" => "fas fa-inbox",
        "is_multi" => true,
        "roles" => [1, 2, 3, 4], // Admin, Kabag, Kadin, Sekretaris
        "href" => [
            [
                "section_text" => "Data Surat Masuk",
                "section_icon" => "far fa-circle",
                "section_href" => [
                    1 => route('admin.masuk.surat_masuk'),
                    2 => route('kabag.masuk.surat_masuk'),
                    3 => route('kadin.masuk.surat_masuk'),
                    4 => route('sekretaris.masuk.surat_masuk'),
                ]
            ],
            // Add "Tambah Surat Masuk" only for Admin and Sekretaris
            (in_array(auth()->user()->id_jabatan, [1, 3, 4])) ? [
                "section_text" => "Tambah Surat Masuk",
                "section_icon" => "far fa-circle",
                "section_href" => [
                    1 => route('admin.masuk.add'),
                    3 => route('kadin.masuk.add'),
                    4 => route('sekretaris.masuk.add'),
                ]
            ] : null
        ]
    ],
    [
        "text" => "Surat Keluar",
        "icon" => "fas fa-paper-plane",
        "is_multi" => true,
        "roles" => [1, 2, 3, 4], // Admin, Kabag, Kadin, Sekretaris
        "href" => [
            [
                "section_text" => "Data Surat Keluar",
                "section_icon" => "far fa-plane",
                "section_href" => [
                    1 => route('admin.keluar.surat_keluar'),
                    2 => route('kabag.keluar.surat_keluar'),
                    3 => route('kadin.keluar.surat_keluar'),
                    4 => route('sekretaris.keluar.surat_keluar'),
                ]
            ],
            // Add "Tambah Surat Keluar" only for Admin and Sekretaris
            (in_array(auth()->user()->id_jabatan, [1,3, 4])) ? [
                "section_text" => "Tambah Surat Keluar",
                "section_icon" => "far fa-circle",
                "section_href" => [
                    1 => route('admin.keluar.add'),
                    3 => route('kadin.keluar.add'),
                    4 => route('sekretaris.keluar.add'),
                ]
            ] : null
        ]
    ],
    [
        "text" => "Disposisi",
        "icon" => "fas fa-share-square",
        "is_multi" => true,
        "roles" => [1,2, 3, 4], // Admin, Kadin, Kabag, Sekretaris
        "href" => [
            [
                "section_text" => "Data Disposisi",
                "section_icon" => "far fa-circle",
                "section_href" => [
                    1 => route('admin.disposisi.disposisi'),
                    3 => route('kadin.disposisi.disposisi'),
                    2 => route('kabag.disposisi.disposisi'),
                    4 => route('sekretaris.disposisi.disposisi'),
                ]
            ]
        ]
    ]
];
@endphp

<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="#" class="brand-link">
    <img src="{{ asset('vendor/adminlte3/img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light">AdminLTE 3</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- SidebarSearch Form -->
    <div class="form-inline mt-2">
      <div class="input-group" data-widget="sidebar-search">
        <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-sidebar">
            <i class="fas fa-search fa-fw"></i>
          </button>
        </div>
      </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        @if(Auth::check())
          @foreach ($links as $link)
            @if (in_array(Auth::user()->id_jabatan, $link['roles']))
              @if (!$link['is_multi'])
                @php
                  $route = $link['routes'][Auth::user()->id_jabatan] ?? '#';
                @endphp
                <li class="nav-item">
                  <a href="{{ $route }}" class="nav-link {{ (url()->current() == $route) ? 'active' : '' }}">
                    <i class="nav-icon {{ $link['icon'] }}"></i>
                    <p>{{ $link['text'] }}</p>
                  </a>
                </li>
              @else
                @php
                  $open = '';
                  $status = '';
                  foreach($link['href'] as $section){
                    if ($section !== null) {
                      $sectionHref = $section['section_href'][Auth::user()->id_jabatan] ?? '#';
                      if (url()->current() == $sectionHref) {
                        $open = 'menu-open';
                        $status = 'active';
                        break;
                      }
                    }
                  }
                @endphp
                <li class="nav-item {{ $open }}">
                  <a href="#" class="nav-link {{ $status }}">
                    <i class="nav-icon {{ $link['icon'] }}"></i>
                    <p>
                      {{ $link['text'] }}
                      <i class="right fas fa-angle-left"></i>
                    </p>
                  </a>
                  <ul class="nav nav-treeview">
                    @foreach ($link['href'] as $section)
                      @if ($section !== null)
                        @php
                          $sectionHref = $section['section_href'][Auth::user()->id_jabatan] ?? '#';
                        @endphp
                        <li class="nav-item">
                          <a href="{{ $sectionHref }}" class="nav-link {{ (url()->current() == $sectionHref) ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>{{ $section['section_text'] }}</p>
                          </a>
                        </li>
                      @endif
                    @endforeach
                  </ul>
                </li>
              @endif
            @endif
          @endforeach
        @else
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-exclamation-triangle"></i>
              <p>User not authenticated</p>
            </a>
          </li>
        @endif
      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>
