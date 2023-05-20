<div class="main-header">
    <!-- Logo Header -->
    <div class="logo-header" data-background-color="blue">

        <a href="#" class="logo">
            <img src="{{ asset('template') }}/assets/img/logo.png" alt="navbar brand" class="navbar-brand">
        </a>
        <button class="navbar-toggler sidenav-toggler ml-auto" type="button" data-toggle="collapse"
            data-target="collapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon">
                <i class="icon-menu"></i>
            </span>
        </button>
        <button class="topbar-toggler more"><i class="icon-options-vertical"></i></button>
        <div class="nav-toggle">
            <button class="btn btn-toggle toggle-sidebar">
                <i class="icon-menu"></i>
            </button>
        </div>
    </div>
    <!-- End Logo Header -->

    <!-- Navbar Header -->
    <nav class="navbar navbar-header navbar-expand-lg" data-background-color="blue2">

        <div class="container-fluid">
            <ul class="navbar-nav topbar-nav ml-md-auto align-items-center">
                <li class="nav-item dropdown hidden-caret">
                    <a class="nav-link dropdown-toggle" href="#" id="notifDropdown" role="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-bell"></i>
                        <span class="notification">1</span>
                    </a>
                    <ul class="dropdown-menu notif-box animated fadeIn" aria-labelledby="notifDropdown">
                        <li>
                            <div class="dropdown-title"></div>
                        </li>
                        <li>
                            <div class="notif-center">
                                <a href="#" data-toggle="modal" class="notif-modal" data-target="#notif"
                                    data-title="tes-1" data-timer="5 Menit Yang Lalu" data-content="lorem ipsum">
                                    <div class="notif-content reading">
                                        <span class="text-success alert-notif">Telah Dibaca</span>
                                        <span class="block">
                                            vendor dimohon untuk mengisikan Ketersediaan Unit...
                                        </span>
                                        <span class="time">5 menit yang lalu</span>
                                    </div>
                                </a>
                                <a href="#" data-toggle="modal" class="notif-modal" data-target="#notif"
                                    data-title="tes-2" data-timer="5 Menit Yang Lalu" data-content="lorem ipsum">
                                    <div class="notif-content reading">
                                        <span class="text-success alert-notif">Telah Dibaca</span>
                                        <span class="block">
                                            vendor dimohon untuk mengisikan Ketersediaan Unit...
                                        </span>
                                        <span class="time">5 menit yang lalu</span>
                                    </div>
                                </a>
                                <a href="#" data-toggle="modal" class="notif-modal" data-target="#notif"
                                    data-title="tes-3" data-timer="5 Menit Yang Lalu" data-content="lorem ipsum">
                                    <div class="notif-content">
                                        <span class="text-danger alert-notif">Belum Dibaca</span>
                                        <span class="block">
                                            vendor dimohon untuk mengisikan Ketersediaan Unit...
                                        </span>
                                        <span class="time">5 menit yang lalu</span>
                                    </div>
                                </a>
                            </div>
                        </li>
                        <li>
                            <a class="see-all" href="/notifikasi">Tampilkan Semua<i class="fa fa-angle-right"></i>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item dropdown hidden-caret">
                    <a class="dropdown-toggle profile-pic" data-toggle="dropdown" href="#" aria-expanded="false">
                        <div class="avatar-sm">
                            <img src="{{ asset('template') }}/assets/img/profile.jpg" alt="..."
                                class="avatar-img rounded-circle">
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-user animated fadeIn">
                        <div class="dropdown-user-scroll scrollbar-outer">
                            <li>
                                <div class="user-box">
                                    <div class="avatar-lg"><img src="{{ asset('template') }}/assets/img/profile.jpg"
                                            alt="image profile" class="avatar-img rounded"></div>
                                    <div class="u-text">
                                        <h4>{{ Auth::user()->nip }}</h4>
                                        <p class="text-muted">{{ Auth::user()->email }}</p>
                                        {{-- <a href="profile.html"
                                            class="btn btn-xs btn-secondary btn-sm">View Profile</a> --}}
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('change_password') }}">Ubah Password</a>
                                {{-- <a class="dropdown-item" href="#"></a> --}}
                                {{-- <a class="dropdown-item" href="#">Inbox</a> --}}
                                {{-- <div class="dropdown-divider"></div> --}}
                                {{-- <a class="dropdown-item" href="#">Account Setting</a> --}}
                                {{-- <div class="dropdown-divider"></div> --}}
                                <button type="submit" class="dropdown-item" style="cursor: pointer;"
                                    data-toggle="modal" data-target="#logout">Logout</button>
                            </li>
                        </div>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>


    <!-- End Navbar -->
</div>

<!-- Modal -->
<div class="modal fade" id="logout" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                Apakah Kamu Yakin Ingin Logout?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Batal</button>
                <form action="{{ route('logout') }}" method="post">
                    @csrf
                    <button type="submit" class="btn btn-primary btn-sm">Logout</button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- modal notif --}}
<div class="modal fade modal-notifikasi" id="notif" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-body">
                <h3 class="title" id="title-notif"> - </h3>
                <span class="time" id="timer"> - </span>
                <hr>
                <p id="content-notif"</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

@push('extraScript')
    <script>
        $(document).on("click", ".notif-modal", function() {
            var title = $(this).data('title');
            var time = $(this).data('timer');
            var content = $(this).data('content');

            $("#title-notif").text(title);
            $("#timer").text(time);
            $("#content-notif").text(content);

            // alert(title);
        });
    </script>
@endpush
