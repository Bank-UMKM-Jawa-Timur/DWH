<div class="main-header">
    <!-- Logo Header -->
    <div class="logo-header" data-background-color="blue">

        <a href="../index.html" class="logo">
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
                        <span class="notification">4</span>
                    </a>
                    <ul class="dropdown-menu notif-box animated fadeIn" aria-labelledby="notifDropdown">
                        <li>
                            <div class="dropdown-title">Kamu Mempunyai 1 Notifikasi Belum Dibaca</div>
                        </li>
                        <li>
                            <div class="notif-center">
                                <a href="#">
                                    <div class="notif-icon notif-primary"> <i class="fa fa-user-plus"></i>
                                    </div>
                                    <div class="notif-content">
                                        <span class="block">
                                            New user registered
                                        </span>
                                        <span class="time">5 minutes ago</span>
                                    </div>
                                </a>
                                <a href="#">
                                    <div class="notif-icon notif-success"> <i class="fa fa-comment"></i> </div>
                                    <div class="notif-content">
                                        <span class="block">
                                            Rahmad commented on Admin
                                        </span>
                                        <span class="time">12 minutes ago</span>
                                    </div>
                                </a>
                                <a href="#">
                                    <div class="notif-img">
                                        <img src="{{ asset('template') }}/assets/img/profile2.jpg" alt="Img Profile">
                                    </div>
                                    <div class="notif-content">
                                        <span class="block">
                                            Reza send messages to you
                                        </span>
                                        <span class="time">12 minutes ago</span>
                                    </div>
                                </a>
                                <a href="#">
                                    <div class="notif-icon notif-danger"> <i class="fa fa-heart"></i> </div>
                                    <div class="notif-content">
                                        <span class="block">
                                            Farrah liked Admin
                                        </span>
                                        <span class="time">17 minutes ago</span>
                                    </div>
                                </a>
                            </div>
                        </li>
                        <li>
                            <a class="see-all" href="/notifikasi">See all notifications<i class="fa fa-angle-right"></i>
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
                                <a class="dropdown-item" href="/reset_password">Reset Password</a>
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
