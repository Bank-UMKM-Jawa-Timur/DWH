@extends('layout.master')

@section('title', $title)

@section('content')

    <div class="panel-header">
        <div class="page-inner py-5">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                <div>
                    <h2 class="text-primary pb-2 fw-bold">{{ $pageTitle }}</h2>
                    <h5 class="text-primary mb-2 total-notif-message">Anda mempunyai {{ $total_belum_dibaca }} notifikasi
                        yang belum dibaca.</h5>
                </div>
            </div>
        </div>
    </div>
    <div class="page-inner mt--5">
        <div class="row mt--2">
            <div class="col-md-12">
                {{-- <div class="card card-notif-title">
                    <div class="card-body">
                        <h3 style="font-weight: 800;line-height:10px;margin-top:10px;">Notifikasi</h3>
                        Kamu Mempunyai 3 Notifikasi
                    </div>
                </div> --}}

                {{-- foreach notif --}}
                @forelse ($data as $item)
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
    </div>
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
