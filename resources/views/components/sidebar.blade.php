<div class="sidebar sidebar-style-2">
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <div class="user">
                <!-- <div class="avatar-sm float-left mr-2">
   <img src="{{ asset('template') }}/assets/img/profile.jpg" alt="..." class="avatar-img rounded-circle">
  </div> -->
                @php
                    $role = \App\Models\Role::where('id', \Session::get(config('global.role_id_session')))->pluck('name')[0];
                @endphp
                <div class="info">
                    <a data-toggle="collapse">
                        <span>
                            <span class="user-role">{{ $role }}</span>
                            <span class="user-level">Dashboard KKB</span>
                        </span>
                    </a>
                </div>
            </div>
            <ul class="nav nav-danger">
                <li class="nav-item {{ request()->is('/', 'dashboard') ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}">
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
                    <a href="{{ route('kredit.index') }}">
                        <i class="icon-credit-card"></i>
                        <p>KKB</p>
                    </a>
                </li>
                @if (\Session::get(config('global.role_id_session')) == 4)
                    <li
                        class="nav-item {{ request()->is('master/template-notifikasi', 'master/template-notifikasi*', 'master/vendor', 'master/vendor/*', 'master/role', 'master/role/*', 'master/pengguna', 'master/pengguna/*', 'master/kategori-dokumen', 'master/kategori-dokumen/*', 'master/imbal-jasa/*', 'master/imbal-jasa', 'master/imbal-jasa/*') ? 'active' : '' }}">
                        <a data-toggle="collapse" href="#base">
                            <i class="
                            icon-user-following"></i>
                            <p>Master</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse {{ request()->is('master/template-notifikasi', 'master/template-notifikasi/*', 'master/vendor', 'master/vendor/*', 'master/role', 'master/role/*', 'master/pengguna', 'master/pengguna/*', 'master/kategori-dokumen', 'master/kategori-dokumen/*', 'master/imbal-jasa', 'master/imbal-jasa/*') ? 'show' : '' }}"
                            id="base">
                            <ul class="nav nav-collapse">
                                <li class="{{ request()->is('master/role') ? 'active' : '' }}">
                                    <a href="{{ route('role.index') }}">
                                        <span class="sub-item">Role / Peran</span>
                                    </a>
                                </li>
                                <li class="{{ request()->is('master/pengguna') ? 'active' : '' }}">
                                    <a href="{{ route('pengguna.index') }}">
                                        <span class="sub-item">Pengguna</span>
                                    </a>
                                </li>
                                <li class="{{ request()->is('master/vendor') ? 'active' : '' }}">
                                    <a href="{{ route('vendor.index') }}">
                                        <span class="sub-item">Vendor</span>
                                    </a>
                                </li>
                                <li class="{{ request()->is('master/kategori-dokumen') ? 'active' : '' }}">
                                    <a href="{{ route('kategori-dokumen.index') }}">
                                        <span class="sub-item">Kategori Dokumen</span>
                                    </a>
                                </li>
                                <li class="{{ request()->is('master/imbal-jasa') ? 'active' : '' }}">
                                    <a href="{{ route('imbal-jasa.index') }}">
                                        <span class="sub-item">Imbal Jasa</span>
                                    </a>
                                </li>
                                <li class="{{ request()->is('master/template-notifikasi') ? 'active' : '' }}">
                                    <a href="{{ route('template-notifikasi.index') }}">
                                        <span class="sub-item">Template Notifikasi</span>
                                    </a>
                                </li>
                                <li class="{{ request()->is('master/dictionary') ? 'active' : '' }}">
                                    <a href="{{ route('dictionary.index') }}">
                                        <span class="sub-item">Dictionary</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif
                @if (\Session::get(config('global.role_id_session')) == 4)
                    <li class="nav-item {{ request()->is('log_aktivitas') ? 'active' : '' }}">
                        <a href="{{ route('log_aktivitas.index') }}">
                            <i class="icon-clock"></i>
                            <p>Log Aktivitas</p>
                        </a>
                    </li>
                @endif
                @if (\Session::get(config('global.role_id_session')) == 1 || \Session::get(config('global.role_id_session')) == 4)
                    <li class="nav-item">
                        <a href="/laporan">
                            <i class="icon-printer"></i>
                            <p>Laporan</p>
                        </a>
                    </li>
                @endif
                @if (\Session::get(config('global.role_id_session')) == 4)
                    <li class="nav-item {{ request()->is('target') ? 'active' : '' }}">
                        <a href="{{ route('target.index') }}">
                            <i class="icon-graph"></i>
                            <p>Target</p>
                        </a>
                    </li>
                    <li class="nav-item {{ request()->is('collection') ? 'active' : '' }}">
                        <a href="{{ route('collection.index') }}">
                            <i class="icon-folder-alt"></i>
                            <p>Collection</p>
                        </a>
                    </li>
                @endif
                {{-- <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">Settings</h4>
                </li> --}}
                <li class="nav-item {{ request()->is('notifikasi') ? 'active' : '' }}">
                    <a href="{{ route('notification.index') }}">
                        <i class="icon-bell"></i>
                        <p>Notifikasi</p>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
