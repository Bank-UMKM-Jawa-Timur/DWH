@extends('layout.master')

@section('title', $title)

@section('modal')
@include('pages.notifikasi.modal.read')
@endsection

@section('content')
    <div class="head-pages">
        <p class="text-sm">{{ $pageTitle }}</p>
        <h2 class="text-2xl font-bold text-theme-primary tracking-tighter">
            Notifikasi
        </h2>
        <p class="text-sm text-theme-text">
            Anda mempunyai {{ $total_belum_dibaca }} notifikasi yang belum dibaca.
        </p>
    </div>
    <div class="body-pages">
        <div class="table-wrapper bg-white border rounded-md w-full p-2">
            <div class="table-accessiblity lg:flex text-center lg:space-y-0 space-y-5 justify-between">
                <div class="title-table lg:p-3 p-2 text-center">
                    <h2 class="font-bold text-lg text-theme-text tracking-tighter">
                        ({{ $total_belum_dibaca }}) - Notifikasi 
                    </h2>
                </div>
                <div class="table-action flex lg:justify-normal justify-center p-2 gap-2">
                </div>
            </div>
            <div class="lg:flex lg:space-y-0 space-y-5 lg:text-left text-center justify-between mt-2 p-2">
                <div class="sorty pl-1 w-full">
                    <label for="" class="mr-3 text-sm text-neutral-400">show</label>
                    <select disabled class="border px-4 py-1.5 cursor-pointer rounded appearance-none text-center"
                    name="page_length" id="page_length">
                    <option selected>All</option>
                        {{-- <option value="5" {{ Request::get('page_length') == '5' ? 'selected' : '' }}>5</option>
                        <option value="10" {{ Request::get('page_length') == '10' ? 'selected' : '' }}>10</option>
                        <option value="15" {{ Request::get('page_length') == '15' ? 'selected' : '' }}>15</option>
                        <option value="20" {{ Request::get('page_length') == '20' ? 'selected' : '' }}>20</option>
                        <option value="all" {{ Request::get('page_length') == 'all' ? 'selected' : '' }}>All</option> --}}
                    </select>
                    <label for="" class="ml-3 text-sm text-neutral-400">entries</label>
                </div>
                <div class="search-table lg:w-96 w-full">
                    <div class="input-search text-[#BFBFBF] rounded-md border flex gap-2">
                        <span class="mt-2 ml-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24">
                                <path fill="currentColor"
                                    d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0 0 16 9.5A6.5 6.5 0 1 0 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5S14 7.01 14 9.5S11.99 14 9.5 14z" />
                            </svg>
                        </span>
                        <input type="search" placeholder="Search" class="p-2 rounded-md w-full outline-none text-[#BFBFBF]"
                            autocomplete="off" />
                    </div>
                </div>
            </div>
            <div class="tables mt-2">
                <table class="table-auto w-full">
                    
                    <tr>
                        <th>No.</th>
                        <th>Pesan</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                    <tbody>
                        @forelse ($data as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="text-left">{{ $item->title }} -
                                {{ strlen($item->content) >= 100 ? substr($item->content, 0, 100) . '...' : $item->content }}
                            </td>
                            <td>{{ date('Y-m-d H:i', strtotime($item->created_at)) }}</td>
                            <td>
                            @if ($item->read)
                                <div class="text-green-600">Sudah Dibaca</div>
                            @else
                                <div class="text-theme-primary">Belum Dibaca</div>
                            @endif
                            </td>
                            <td>
                                <button class="px-4 py-2 bg-theme-btn/10 rounded text-theme-btn toggle-modal" data-id="{{ $item->id }}" data-target-id="modalNotifikasi">
                                    Baca
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">
                                <span class="text-danger">Tidak ada notifikasi.</span>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="footer-table p-3 text-theme-text lg:flex lg:space-y-0 space-y-10 justify-between">
                <div class="w-full">
                    <div class="pagination">
                        @if($data instanceof \Illuminate\Pagination\LengthAwarePaginator )
                        {{ $data->links('pagination::tailwind') }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @push('extraScript')
        <script>
            $('[data-dismiss-id]').on('click', function(){
                location.reload();
            })
            $('.toggle-modal').on('click', function(e) {
                const data_id = $(this).data('id')
                $.ajax({
                    type: "GET",
                    url: "{{ url('/notifikasi') }}/" + data_id,
                    success: function(response) {
                        console.log(response)
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
                            // if (notif.read) {
                            //     $('.modal-notifikasi').show()
                            //     $('.notification-click').find('.notif-body-card-' + data_id).addClass(
                            //         'reading')
                            //     $('.notification-click').find('span').html('Sudah Dibaca')
                            //     const total_unread = response.total_belum_dibaca
                            //     if (total_unread > 0) {
                            //         $('.total-notif-message').html(
                            //             `Anda mempunyai ${total_unread} notifikasi yang belum dibaca.`)
                            //     } else {
                            //         $('.total-notif-message').html(
                            //             'Anda tidak mempunyai notifikasi yang belum dibaca.')
                            //     }
                            // } else {
                            //     ErrorMessage('Tidak dapat membuka notifikasi')
                            // }
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