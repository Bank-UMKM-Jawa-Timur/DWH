<aside class="layout-sidebar lg:block hidden">
    <!-- logo brands -->
    <div class="app-brand">
      <a
        href="#"
        class="link-brand"
      >
        <img
          src="{{ asset('template/assets/img/news/logo.png') }}"
          alt=""
        />
      </a>
    </div>
    <!-- menu sidebar -->
    <ul class="menu-link">
      <li class="menu-category-link">
        <p>MENU</p>
      </li>
      <li class="item-link {{ request()->is('/', 'dashboard') ? 'active-link' : '' }}">
        <a
          href="{{ route('dashboard') }}"
          class="nav-link"
        >
          <span>
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="icon-nav"
              viewBox="0 0 24 24"
            >
              <path
                fill="none"
                stroke="currentColor"
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M2 5a2 2 0 0 1 2-2h6v18H4a2 2 0 0 1-2-2V5Zm12-2h6a2 2 0 0 1 2 2v5h-8V3Zm0 11h8v5a2 2 0 0 1-2 2h-6v-7Z"
              />
            </svg>
          </span>
          <div>Dashboard</div>
        </a>
      </li>
      <li class="menu-category-link">
        <p>FITUR APLIKASI</p>
      </li>
      <li class="item-link {{ request()->is('kredit') || request()->is('import-kkb') ? 'active-link' : '' }}">
        <a
          href="{{ route('kredit.index') }}"
          class="nav-link"
        >
          <span>
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="icon-nav"
              viewBox="0 0 24 24"
            >
              <path
                fill="currentColor"
                d="M6.25 3A3.25 3.25 0 0 0 3 6.25v11.5A3.25 3.25 0 0 0 6.25 21h11.5A3.25 3.25 0 0 0 21 17.75V6.25A3.25 3.25 0 0 0 17.75 3H6.25ZM4.5 6.25c0-.966.784-1.75 1.75-1.75H14v4H4.5V6.25Zm0 3.75h4v4h-4v-4Zm5.5 0h9.5v4H10v-4Zm9.5-1.5h-4v-4h2.25c.966 0 1.75.784 1.75 1.75V8.5Zm-4 7h4v2.25a1.75 1.75 0 0 1-1.75 1.75H15.5v-4Zm-11 2.25V15.5H14v4H6.25a1.75 1.75 0 0 1-1.75-1.75Z"
              />
            </svg>
          </span>
          <div>KKB</div>
        </a>
      </li>
      @if (\Session::get(config('global.role_id_session')) != 3)
      <li class="item-link dropdown-toggle {{ request()->is('asuransi/registrasi', 'asuransi/registrasi/*', 'asuransi/pengajuan-klaim', 'asuransi/pengajuan-klaim/*', 'asuransi/pembayaran-premi', 'asuransi/pembayaran-premi/*', 'asuransi/pelaporan-pelunasan', 'asuransi/pelaporan-pelunasan/*') ? 'active-link' : '' }}">
        <div class="relative">
          <a
            href="#"
            class="nav-link relative"
          >
            <span>
              @include('components.svg.insurance')
            </span>
            <div>Asuransi</div>
          </a>
          <span class="dropdown-arrow absolute right-2 bottom-2.5">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="24"
              height="24"
              viewBox="0 0 24 24"
            >
              <path
                fill="currentColor"
                d="M12 15.121a.997.997 0 0 1-.707-.293L7.05 10.586a1 1 0 0 1 1.414-1.414L12 12.707l3.536-3.535a1 1 0 0 1 1.414 1.414l-4.243 4.242a.997.997 0 0 1-.707.293Z"
              />
            </svg>
          </span>
        </div>
      </li>
      <!-- dropdown -->
      <div class="dropdown-menu-link  {{ request()->is('asuransi/registrasi', 'asuransi/registrasi/*', 'asuransi/pengajuan-klaim', 'asuransi/pengajuan-klaim/*', 'asuransi/pembayaran-premi', 'asuransi/pembayaran-premi/*','asuransi/pelaporan-pelunasan', 'asuransi/pelaporan-pelunasan/*') ? 'show' : 'hidden' }}">
        <ul class="menu-dropdown">
          <!-- add rule class active-dropdown-link for active navigation -->
          <a href="{{ route('asuransi.registrasi.index') }}">
            <li class="dropdown-item-link {{ request()->is('asuransi/registrasi', 'asuransi/registrasi/*', 'asuransi/pengajuan-klaim', 'asuransi/pengajuan-klaim/*') ? 'active-dropdown-link' : '' }}">
              List Asuransi
            </li>
          </a>
          <a href="{{ route('asuransi.pembayaran-premi.index') }}">
            <li class="dropdown-item-link {{ request()->is('asuransi/pembayaran-premi', 'asuransi/pembayaran-premi/*') ? 'active-dropdown-link' : '' }}">
              Pembayaran Premi
            </li>
          </a>
          {{-- <a href="{{ route('asuransi.pengajuan-klaim.index') }}">
            <li class="dropdown-item-link {{ request()->is('asuransi/pengajuan-klaim', 'asuransi/pengajuan-klaim/*') ? 'active-dropdown-link' : '' }}">
            Pengajuan Klaim
            </li>
          </a> --}}
        </ul>
      </div>
      <li class="item-link dropdown-toggle">
        <div class="relative">
          <a
            href="#"
            class="nav-link relative"
          >
            <span>
              @include('components.svg.insurance')
            </span>
            <div>Laporan</div>
          </a>
          <span class="dropdown-arrow absolute right-2 bottom-2.5">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="24"
              height="24"
              viewBox="0 0 24 24"
            >
              <path
                fill="currentColor"
                d="M12 15.121a.997.997 0 0 1-.707-.293L7.05 10.586a1 1 0 0 1 1.414-1.414L12 12.707l3.536-3.535a1 1 0 0 1 1.414 1.414l-4.243 4.242a.997.997 0 0 1-.707.293Z"
              />
            </svg>
          </span>
        </div>
      </li>
      <div class="dropdown-menu-link {{ request()->is('asuransi/report/*') ? 'show' : 'hidden' }}">
        <ul class="menu-dropdown">
          {{--  <a href="{{ route('asuransi.pembayaran-premi.index') }}">
            <li class="dropdown-item-link">
              KKB
            </li>
          </a>  --}}
          <li class="item-link dropdown-toggle">
            <div class="relative">
              <a
                href="#"
                class="nav-link relative"
              >
                <div>Asuransi</div>
              </a>
              <span class="dropdown-arrow absolute right-2 bottom-2.5">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  width="24"
                  height="24"
                  viewBox="0 0 24 24"
                >
                  <path
                    fill="currentColor"
                    d="M12 15.121a.997.997 0 0 1-.707-.293L7.05 10.586a1 1 0 0 1 1.414-1.414L12 12.707l3.536-3.535a1 1 0 0 1 1.414 1.414l-4.243 4.242a.997.997 0 0 1-.707.293Z"
                  />
                </svg>
              </span>
            </div>
          </li>
          <div class="dropdown-menu-link {{ request()->is('asuransi/report/*') ? 'show' : 'hidden' }}">
            <ul class="menu-dropdown">
              <!-- add rule class active-dropdown-link for active navigation -->
              <a href="{{route('asuransi.report.registrasi.registrasi')}}">
                <li class="dropdown-item-link {{ request()->is('asuransi/report/registrasi/registrasi') ? 'active-dropdown-link' : '' }}">
                  Registrasi
                </li>
              </a>
              <a href="{{route('asuransi.report.registrasi.pembatalan')}}">
                <li class="dropdown-item-link {{ request()->is('asuransi/report/registrasi/pembatalan') ? 'active-dropdown-link' : '' }}">
                  Pembatalan Registrasi
                </li>
              </a>
              <a href="{{route('asuransi.report.pembayaran')}}">
                <li class="dropdown-item-link {{ request()->is('asuransi/pengajuan-klaim', 'asuransi/pengajuan-klaim/*') ? 'active-dropdown-link' : '' }}">
                  Pembayaran Premi
                </li>
              </a>
              <a href="#">
                <li class="dropdown-item-link {{ request()->is('asuransi/pengajuan-klaim', 'asuransi/pengajuan-klaim/*') ? 'active-dropdown-link' : '' }}">
                  Pengajuan Klaim
                </li>
              </a>
              <a href="{{ route('asuransi.report.pengajuan.pembatalan-klaim') }}">
                <li class="dropdown-item-link {{ request()->is('asuransi/report/pengajuan/pembatalan-klaim', 'asuransi/pengajuan-klaim/*') ? 'active-dropdown-link' : '' }}">
                  Pembatalan Klaim
                </li>
              </a>
              <a href="{{route('asuransi.report.registrasi.pelaporan-pelunasan')}}">
                <li class="dropdown-item-link {{ request()->is('asuransi.report.registrasi.pelaporan-pelunasan') ? 'active-dropdown-link' : '' }}">
                  Pelaporan Pelunasan
                </li>
              </a>
              <a href="{{route('asuransi.report.registrasi.log-data')}}">
                <li class="dropdown-item-link {{ request()->is('asuransi/report/registrasi/log-data') ? 'active-dropdown-link' : '' }}">
                  Log Data
                </li>
              </a>
            </ul>
          </div>
        </ul>
      </div>
      @endif
      @if (\Session::get(config('global.role_id_session')) == 4)
      <li class="item-link dropdown-toggle {{ request()->is('master/template-notifikasi', 'master/template-notifikasi*', 'master/vendor', 'master/vendor/*', 'master/role', 'master/role/*', /*'master/pengguna',*/ 'master/pengguna/*', 'master/kategori-dokumen', 'master/kategori-dokumen/*', 'master/imbal-jasa/*', 'master/imbal-jasa', 'master/imbal-jasa/*', 'master/perusahaan-asuransi', 'master/perusahaan-asuransi/*', 'master/jenis-asuransi', 'master/jenis-asuransi/*','master/mst_form_system_asuransi', 'master/mst_form_system_asuransi/*') ? 'active-link' : '' }}">
        <div class="relative">
          <a
            href="#"
            class="nav-link relative"
          >
            <span>
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="icon-nav"
                viewBox="0 0 14 14"
              >
                <path 
                  fill="none"
                  stroke="currentColor"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="m13.5 4l-3 3L7 2L3.5 7l-3-3v6.5A1.5 1.5 0 0 0 2 12h10a1.5 1.5 0 0 0 1.5-1.5Z"
                />
              </svg>
            </span>
            <div>Master</div>
          </a>
          <span class="dropdown-arrow absolute right-2 bottom-2.5">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="24"
              height="24"
              viewBox="0 0 24 24"
            >
              <path
                fill="currentColor"
                d="M12 15.121a.997.997 0 0 1-.707-.293L7.05 10.586a1 1 0 0 1 1.414-1.414L12 12.707l3.536-3.535a1 1 0 0 1 1.414 1.414l-4.243 4.242a.997.997 0 0 1-.707.293Z"
              />
            </svg>
          </span>
        </div>
      </li>
      <!-- dropdown -->
      <div class="dropdown-menu-link  {{ request()->is('master/template-notifikasi', 'master/template-notifikasi/*', 'master/vendor', 'master/vendor/*', 'master/role', 'master/role/*', 'master/pengguna', 'master/pengguna/*', 'master/kategori-dokumen', 'master/kategori-dokumen/*', 'master/imbal-jasa', 'master/imbal-jasa/*', 'master/perusahaan-asuransi', 'master/perusahaan-asuransi/*', 'master/jenis-asuransi', 'master/jenis-asuransi/*', 'master/mst_form_system_asuransi', 'master/mst_form_system_asuransi/*') ? 'show' : 'hidden' }}">
        <ul class="menu-dropdown">
          <!-- add rule class active-dropdown-link for active navigation -->
          <a href="{{ route('role.index') }}">
            <li class="dropdown-item-link {{ request()->is('master/role') ? 'active-dropdown-link' : '' }}">
              Role / Peran
            </li>
          </a>
          {{--  <a href="{{ route('pengguna.index') }}">
            <li class="dropdown-item-link {{ request()->is('master/pengguna') ? 'active-dropdown-link' : '' }}">
              Pengguna
            </li>
          </a>  --}}
          <a href="{{ route('vendor.index') }}">
            <li class="dropdown-item-link {{ request()->is('master/vendor') ? 'active-dropdown-link' : '' }}">
              Vendor
            </li>
          </a>
          <a href="{{ route('kategori-dokumen.index') }}">
            <li class="dropdown-item-link {{ request()->is('master/kategori-dokumen') ? 'active-dropdown-link' : '' }}">
              Kategori Dokumen
            </li>
          </a>
          <a href="{{ route('imbal-jasa.index') }}">
            <li class="dropdown-item-link {{ request()->is('master/imbal-jasa') ? 'active-dropdown-link' : '' }}">
              Imbal jasa
            </li>
          </a>
          <a href="{{ route('template-notifikasi.index') }}">
            <li class="dropdown-item-link {{ request()->is('master/template-notifikasi') ? 'active-dropdown-link' : '' }}">
              Template notifikasi
            </li>
          </a>
          <a href="{{ route('perusahaan-asuransi.index') }}">
            <li class="dropdown-item-link {{ request()->is('master/perusahaan-asuransi') ? 'active-dropdown-link' : '' }}">
              Perusahaan Asuransi
            </li>
          </a>
          <a href="{{ route('jenis-asuransi.index') }}">
            <li class="dropdown-item-link {{ request()->is('master/jenis-asuransi') ? 'active-dropdown-link' : '' }}">
              Jenis Asuransi
            </li>
          </a>
          <a href="{{ route('mst_form_system_asuransi.index') }}">
            <li class="dropdown-item-link {{ request()->is('master/mst_form_system_asuransi') ? 'active-dropdown-link' : '' }}">
              Form Item Asuransi
            </li>
          </a>
          {{--  <a href="{{ route('dictionary.index') }}">
            <li class="dropdown-item-link {{ request()->is('master/dictionary') ? 'active-dropdown-link' : '' }}">
              Dictionary
            </li>
          </a>  --}}
        </ul>
      </div>
      @endif
      @if (\Session::get(config('global.role_id_session')) == 4)
      <li class="item-link {{ request()->is('log_aktivitas') ? 'active-link' : '' }}">
        <a
          href="{{ route('log_aktivitas.index') }}"
          class="nav-link relative"
        >
          <span>
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="icon-nav"
              viewBox="0 0 20 20"
            >
              <path
                fill="currentColor"
                d="M5.75 3h8.5A2.75 2.75 0 0 1 17 5.75V9.6a5.465 5.465 0 0 0-1-.393V5.75A1.75 1.75 0 0 0 14.25 4h-8.5A1.75 1.75 0 0 0 4 5.75v8.5c0 .966.784 1.75 1.75 1.75h3.457c.099.349.23.683.393 1H5.75A2.75 2.75 0 0 1 3 14.25v-8.5A2.75 2.75 0 0 1 5.75 3Zm3.75 7h1.837c-.403.284-.767.62-1.08 1H9.5a.5.5 0 0 1 0-1ZM6.75 8a.75.75 0 1 0 0-1.5a.75.75 0 0 0 0 1.5Zm.75 2.25a.75.75 0 1 1-1.5 0a.75.75 0 0 1 1.5 0Zm0 3a.75.75 0 1 1-1.5 0a.75.75 0 0 1 1.5 0ZM9 7.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1-.5-.5Zm10 7a4.5 4.5 0 1 1-9 0a4.5 4.5 0 0 1 9 0ZM14.5 12a.5.5 0 0 0-.5.5v2a.5.5 0 0 0 .5.5H16a.5.5 0 0 0 0-1h-1v-1.5a.5.5 0 0 0-.5-.5Z"
              />
            </svg>
          </span>
          <div>Log Aktivitas</div>
        </a>
      </li>
      @endif
      @if (\Session::get(config('global.role_id_session')) == 4)
      <li class="item-link {{ request()->is('target') ? 'active-link' : '' }}">
        <a
          href="{{ route('target.index') }}"
          class="nav-link relative"
        >
          <span>
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="icon-nav"
              viewBox="0 0 24 24"
            >
              <path
                fill="currentColor"
                d="M11 2.05v3.02a7.002 7.002 0 1 0 5.192 12.536l2.137 2.137A9.958 9.958 0 0 1 12 22C6.477 22 2 17.523 2 12c0-5.185 3.947-9.449 9-9.95ZM21.95 13a9.954 9.954 0 0 1-2.207 5.328l-2.137-2.136A6.964 6.964 0 0 0 18.93 13h3.022ZM13.002 2.05a10.004 10.004 0 0 1 8.95 8.95H18.93a7.005 7.005 0 0 0-5.928-5.929V2.049Z"
              />
            </svg>
          </span>
          <div>Target</div>
        </a>
      </li>
      @endif
      @if (\Session::get(config('global.role_id_session')) != 3)
        <li class="item-link {{ request()->is('notifikasi') ? 'active-link' : '' }}">
          <a
            href="{{ route('notification.index') }}"
            class="nav-link relative"
          >
            <span>
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="icon-nav"
                viewBox="0 0 14 14"
              >
                <path
                  fill="none"
                  stroke="currentColor"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M7 .5a4.29 4.29 0 0 1 4.29 4.29c0 4.77 1.74 5.71 2.21 5.71H.5c.48 0 2.21-.95 2.21-5.71A4.29 4.29 0 0 1 7 .5ZM5.5 12.33a1.55 1.55 0 0 0 3 0"
                />
              </svg>
            </span>
            <div>Notifikasi</div>
          </a>
        </li>
      @endif
    </ul>
  </aside>
