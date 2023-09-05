@extends('layout.master')

@section('title', $title)

@section('content')

    {{-- <div class="panel-header">
        <div class="page-inner py-5">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                <div>
                    <h2 class="text-primary pb-2 fw-bold">{{ $pageTitle }}</h2>
                    <h5 class="text-primary mb-2 total-notif-message">Anda mempunyai {{ $total_belum_dibaca }} notifikasi
                        yang belum dibaca.</h5>
                </div>
            </div>
        </div>
    </div> --}}
    <div class="head-pages">
        <p class="text-sm">{{ $pageTitle }}</p>
        <h2
          class="text-2xl font-bold text-theme-primary tracking-tighter"
        >
          Notifikasi
        </h2>
        <p class="text-sm text-theme-text">
            Anda mempunyai {{ $total_belum_dibaca }} notifikasi yang belum dibaca.
        </p>
    </div>
    <div class="body-pages space-y-2">
        @forelse ($data as $item)
            <a href="#" data-toggle="modal" class="notification-click" data-target="#notif"
            data-id="{{ $item->id }}">
                <div class="card flex p-2 bg-white w-full rounded-md mt-2">
                    <div
                    class="bg-theme-primary/10 p-5 text-theme-primary rounded-md"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="24"
                            height="24"
                            viewBox="0 0 36 36"
                        >
                            <path
                            fill="currentColor"
                            d="M32.51 27.83A14.4 14.4 0 0 1 30 24.9a12.63 12.63 0 0 1-1.35-4.81v-4.94A10.81 10.81 0 0 0 19.21 4.4V3.11a1.33 1.33 0 1 0-2.67 0v1.31a10.81 10.81 0 0 0-9.33 10.73v4.94a12.63 12.63 0 0 1-1.35 4.81a14.4 14.4 0 0 1-2.47 2.93a1 1 0 0 0-.34.75v1.36a1 1 0 0 0 1 1h27.8a1 1 0 0 0 1-1v-1.36a1 1 0 0 0-.34-.75ZM5.13 28.94a16.17 16.17 0 0 0 2.44-3a14.24 14.24 0 0 0 1.65-5.85v-4.94a8.74 8.74 0 1 1 17.47 0v4.94a14.24 14.24 0 0 0 1.65 5.85a16.17 16.17 0 0 0 2.44 3Z"
                            class="clr-i-outline clr-i-outline-path-1"
                            />
                            <path
                            fill="currentColor"
                            d="M18 34.28A2.67 2.67 0 0 0 20.58 32h-5.26A2.67 2.67 0 0 0 18 34.28Z"
                            class="clr-i-outline clr-i-outline-path-2"
                            />
                            <path
                            fill="none"
                            d="M0 0h36v36H0z"
                            />
                        </svg>
                    </div>
                    <div class="p-2 pl-5">
                        <div class="flex gap-3">
                            @if ($item->read)
                                <div class="text-theme-success">Sudah Dibaca</div>
                            @else
                                <div class="text-theme-primary">Belum Dibaca</div>
                            @endif
                            <div class="text-gray-400">{{ date('Y-m-d H:i', strtotime($item->created_at)) }}</div>
                        </div>
                        <h2
                            class="font-bold tracking-tighter text-lg text-theme-text"
                        >
                            {{ $item->title }} -
                            {{ strlen($item->content) >= 100 ? substr($item->content, 0, 100) . '...' : $item->content }}
                        </h2>
                    </div>
                </div>
            </a>
        @empty
            <p>Belum ada notifikasi.</p>
        @endforelse
    </div>
    {{-- <div class="page-inner mt--5">
        <div class="row mt--2">
            <div class="col-md-12">
                {{-- <div class="card card-notif-title">
                    <div class="card-body">
                        <h3 style="font-weight: 800;line-height:10px;margin-top:10px;">Notifikasi</h3>
                        Kamu Mempunyai 3 Notifikasi
                    </div>
                </div> --}}

                {{-- foreach notif --}}
                {{-- @forelse ($data as $item)
                    <a href="#" data-toggle="modal" class="notification-click" data-target="#notif"
                        data-id="{{ $item->id }}">
                        <div class="card card-notif">
                            <div
                                class="card-body notif-body-card-{{ $item->id }} @if ($item->read) reading @endif">
                                <div class="notif ">
                                    @if ($item->read)
                                        <span class="alert-notif text-success">Sudah Dibaca</span>
                                    @else
                                        <span class="alert-notif text-danger">Belum Dibaca</span>
                                    @endif
                                    <h4>
                                        {{ $item->title }} -
                                        {{ strlen($item->content) >= 100 ? substr($item->content, 0, 100) . '...' : $item->content }}
                                    </h4>
                                    <p class="lead-notif">{{ date('Y-m-d H:i', strtotime($item->created_at)) }}</p>
                                </div>
                            </div>
                        </div>
                    </a>
                @empty
                    <p>Belum ada notifikasi.</p>
                @endforelse
            </div>
        </div>
    </div> --}}
    @push('extraScript')
        <script>
            $('.notification-click').on('click', function(e) {
                const data_id = $(this).data('id')
                $.ajax({
                    type: "GET",
                    url: "{{ url('/notifikasi') }}/" + data_id,
                    success: function(response) {
                        if (response.status == 'success') {
                            const notif = response.data
                            var datetime = notif.created_at.replace("T", " ").substring(0, 16)
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
                                $('.notification-click').find('.notif-body-card-' + data_id).addClass(
                                    'reading')
                                $('.notification-click').find('span').html('Sudah Dibaca')
                                const total_unread = response.total_belum_dibaca
                                if (total_unread > 0) {
                                    $('.total-notif-message').html(
                                        `Anda mempunyai ${total_unread} notifikasi yang belum dibaca.`)
                                } else {
                                    $('.total-notif-message').html(
                                        'Anda tidak mempunyai notifikasi yang belum dibaca.')
                                }
                            } else {
                                ErrorMessage('Tidak dapat membuka notifikasi')
                            }
                        } else {
                            ErrorMessage('Terjadi kesalahan')
                        }
                    },
                    error: function(error) {
                        console.log(error)
                    }
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

            function showError(input, message) {
                const inputGroup = input.parentElement;
                const formGroup = inputGroup.parentElement;
                const errorSpan = formGroup.querySelector('.error');

                formGroup.classList.add('has-error');
                errorSpan.innerText = message;
                input.focus();
                input.value = '';
            }
        </script>
    @endpush
@endsection
