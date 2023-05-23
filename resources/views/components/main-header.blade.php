@php
    $total_notifications = \App\Models\Notification::where('user_id', Auth::user()->id)->where('read', false)->count();
@endphp
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
                        <span class="notification">{{$total_notifications}}</span>
                    </a>
                    <ul class="dropdown-menu notif-box animated fadeIn" aria-labelledby="notifDropdown">
                        <li>
                            <div class="dropdown-title"></div>
                        </li>
                        <li>
                            <div class="notif-center">
                                {{--  <a href="#" data-toggle="modal" class="notif-modal" data-target="#notif"
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
                                    <div class="notif-content w-100">
                                        <span class="text-danger alert-notif">Belum Dibaca</span>
                                        <span class="block">
                                            vendor dimohon untuk mengisikan Ketersediaan Unit...
                                        </span>
                                        <span class="time">5 menit yang lalu</span>
                                    </div>
                                </a>  --}}
                            </div>
                        </li>
                        <li>
                            <a class="see-all" href="{{route('notification.index')}}">Tampilkan Semua<i class="fa fa-angle-right"></i>
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
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('change_password') }}">Ubah Password</a>
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

@push('extraScript')
    <script>
        $(document).ready(function() {
            $('#notifDropdown').on('click', function(e) {
                $.ajax({
                    type: "GET",
                    url: "{{url('/notifikasi/json')}}",
                    success: function(response) {
                        if (response.status == 'success') {
                            $('.notif-center').empty()
                            const notifications = response.data
                            if (notifications.length > 0) {
                                for(var i=0;i<notifications.length;i++) {
                                    const notifId = notifications[i].id
                                    const notifTime = notifications[i].created_at.replace("T", " ").substring(0,16)
                                    const notifContent = notifications[i].content
                                    const notifWidget = `<a href="#" id="top-notification" class="top-notification-click" data-id="${notifId}"><div class="notif-content w-100"><span class="text-success alert-notif">Belum Dibaca</span><span class="block">${notifContent}</span><span class="time">${notifTime}</span></div></a>`
                                    $('.notif-center').append(notifWidget)
                                }
                            }
                            else {
                                $('.notif-center').html(`<span class="px-3 py-2">Belum ada notifikasi.</span>`)
                            }
                        }
                        else {
                            ErrorMessage('Terjadi kesalahan')
                        }
                    },
                    error: function(error) {
                        console.log(error)
                    }
                })
            })

            $('#top-notification').on('click', function(e) {
                console.log('asd')
                const data_id = $(this).data('id');
                console.log("notification id :"+data_id+";")
                
                /*$.ajax({
                    type: "GET",
                    url: "{{url('/notifikasi')}}/"+data_id,
                    success: function(response) {
                        console.log(response)
                        if (response.status == 'success') {
                            const notif = response.data
                            var datetime = notif.created_at.replace("T", " ").substring(0,16)
                            $('#title-notif').html(notif.title)
                            $('#time-notif').html(datetime)
                            $('#content-notif').html(notif.content)
                            if (notif.extra) {
                                $('.extra-notif').empty()
                                var extra = `<div class="row">
                                    <div class="col-12">
                                        ${notif.extra}
                                    </div>
                                </div>`;
                                $('.extra-notif').append(extra)
                            }
                            if (notif.read) {
                                $('.modal-notifikasi').show()
                                $('.notification-click').find('.notif-body-card-'+data_id).addClass('reading')
                                $('.notification-click').find('span').html('Sudah Dibaca')
                            }
                            else {
                                ErrorMessage('Tidak dapat membuka notifikasi')
                            }
                        }
                        else {
                            ErrorMessage('Terjadi kesalahan')
                        }
                    },
                    error: function(error) {
                        console.log(error)
                    }
                })*/
            })
        })

        function ErrorMessage(message) {
            swal("Gagal!", message, {
                icon: "error",
                // timer: 3000,
                closeOnClickOutside: false
            }).then(() => {
                location.reload();
            });
            setTimeout(function() {
                location.reload();
            }, 3000);
        }
    </script>
@endpush
