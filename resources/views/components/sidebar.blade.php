<div class="sidebar sidebar-style-2">
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <div class="user">
                <!-- <div class="avatar-sm float-left mr-2">
   <img src="{{ asset('template') }}/assets/img/profile.jpg" alt="..." class="avatar-img rounded-circle">
  </div> -->
                <div class="info">
                    <a data-toggle="collapse">
                        <span>
                            <span class="user-level">Data Warehouse</span>
                        </span>
                    </a>
                </div>
            </div>
            <ul class="nav nav-danger">
                <li class="nav-item {{ request()->is('/', 'dashboard') ? 'active' : '' }}">
                    <a href="/">
                        <i class="icon-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">Fitur Aplikasi</h4>
                </li>
                <li class="nav-item {{ request()->is('kredit') ? 'active' : '' }}">
                    <a href="/kredit">
                        <i class="icon-credit-card"></i>
                        <p>KKB</p>
                    </a>
                </li>
                <li
                    class="nav-item {{ request()->is('master/template-notifikasi', 'master/template-notifikasi*', 'master/vendor', 'master/vendor/*', 'master/role', 'master/role/*', 'master/pengguna', 'master/pengguna/*', 'master/kategori-dokumen', 'master/kategori-dokumen/*', 'master/imbal-jasa/*') ? 'active' : '' }}">
                    <a data-toggle="collapse" href="#base">
                        <i class="
                            icon-user-following"></i>
                        <p>Master</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->is('master/template-notifikasi', 'master/template-notifikasi/*', 'master/vendor', 'master/vendor/*', 'master/role', 'master/role/*', 'master/pengguna', 'master/pengguna/*', 'master/kategori-dokumen', 'master/kategori-dokumen/*') ? 'show' : '' }}"
                        id="base">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->is('master/role') ? 'active' : '' }}">
                                <a href="{{ route('role.index') }}">
                                    <span class="sub-item">Role / Peran</span>
                                </a>
                            </li>
                            <li class="{{ request()->is('master/pengguna') ? 'active' : '' }}">
                                <a href="/master/pengguna">
                                    <span class="sub-item">Pengguna</span>
                                </a>
                            </li>
                            <li class="{{ request()->is('master/vendor') ? 'active' : '' }}">
                                <a href="/master/vendor">
                                    <span class="sub-item">Vendor</span>
                                </a>
                            </li>
                            <li class="{{ request()->is('master/kategori-dokumen') ? 'active' : '' }}">
                                <a href="/master/kategori-dokumen">
                                    <span class="sub-item">Kategori Dokumen</span>
                                </a>
                            </li>
                            <li class="{{ request()->is('master/imbal-jasa') ? 'active' : '' }}">
                                <a href="/master/imbal-jasa">
                                    <span class="sub-item">Imbal Jasa</span>
                                </a>
                            </li>
                            <li class="{{ request()->is('master/template-notifikasi') ? 'active' : '' }}">
                                <a href="/master/template-notifikasi">
                                    <span class="sub-item">Template Notifikasi</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item {{ request()->is('log_aktivitas') ? 'active' : '' }}">
                    <a href="/log_aktivitas">
                        <i class="icon-clock"></i>
                        <p>Log Aktivitas</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/laporan">
                        <i class="icon-printer"></i>
                        <p>Laporan</p>
                    </a>
                </li>
                <li class="nav-item {{ request()->is('target') ? 'active' : '' }}">
                    <a href="/target">
                        <i class="icon-graph"></i>
                        <p>Target</p>
                    </a>
                </li>
                {{-- <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">Settings</h4>
                </li> --}}
                <li class="nav-item {{ request()->is('notifikasi') ? 'active' : '' }}">
                    <a href="/notifikasi">
                        <i class="icon-bell"></i>
                        <p>Notifikasi</p>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
