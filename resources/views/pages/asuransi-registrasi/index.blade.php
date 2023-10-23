@extends('layout.master')
@push('extraStyle')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.7.7/xlsx.core.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xls/0.7.4-a/xls.core.min.js"></script>
@endpush
@section('modal')
    <!-- Modal-Filter -->
    @include('pages.asuransi-registrasi.modal.filter')
    <!-- Modal-Canceled -->
    @include('pages.asuransi-registrasi.modal.canceled')
@endsection
@section('content')
    <div class="head-pages">
        <p class="text-sm">Asuransi</p>
        <h2 class="text-2xl font-bold text-theme-primary tracking-tighter">
            List Registrasi Asuransi
        </h2>
    </div>
    <div class="body-pages">
        <div class="table-wrapper bg-white border rounded-md w-full p-2">
            <div class="table-accessiblity lg:flex text-center lg:space-y-0 space-y-5 justify-between">
                <div class="title-table lg:p-3 p-2 text-left">
                    <h2 class="font-bold text-lg text-theme-text tracking-tighter">
                        Data Registrasi Asuransi
                    </h2>
                    @if (\Request::get('tAwal') && \Request::get('tAkhir'))
                        <p class="text-gray-600 text-sm">Menampilkan data mulai tanggal <b>{{date('d-m-Y', strtotime(\Request::get('tAwal')))}}</b> s/d <b>{{date('d-m-Y', strtotime(\Request::get('tAkhir')))}}</b> dengan status <b>{{Request()->status == 'canceled' ? 'dibatalkan' : 'onprogres'}}</b>.</p>
                    @endif
                </div>
                <div class="table-action flex lg:justify-normal justify-center p-2 gap-2">
                    <a>
                        <button data-target-id="filter" type="button"
                            class="toggle-modal px-6 py-2 bg-theme-primary flex gap-3 rounded text-white">
                            <span class="lg:mt-1 mt-0">
                                @include('components.svg.filter')
                            </span>
                            <span class="lg:block hidden"> Filter </span>
                        </button>
                    </a>
                    <a href="{{ route('registrasi.create') }}">
                        <button class="px-6 py-2 bg-theme-primary flex gap-3 rounded text-white">
                            <span class="lg:mt-0 mt-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 24 24">
                                    <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="M5 12h14m-7-7v14" />
                                </svg>
                            </span>
                            <span class="lg:block hidden"> Tambah  </span>
                        </button>
                    </a>
                </div>
            </div>
            <form id="form" method="get">
                <div class="lg:flex lg:space-y-0 space-y-5 lg:text-left text-center justify-between mt-2 p-2">
                    <div class="sorty pl-1 w-full">
                        <label for="" class="mr-3 text-sm text-neutral-400">show</label>
                        <select name="page_length" id="page_length"
                            class="border px-4 py-1.5 cursor-pointer rounded appearance-none text-center">
                            <option value="5"
                                @isset($_GET['page_length']) {{ $_GET['page_length'] == 5 ? 'selected' : '' }} @endisset>
                                5</option>
                            <option value="10"
                                @isset($_GET['page_length']) {{ $_GET['page_length'] == 10 ? 'selected' : '' }} @endisset>
                                10</option>
                            <option value="15"
                                @isset($_GET['page_length']) {{ $_GET['page_length'] == 15 ? 'selected' : '' }} @endisset>
                                15</option>
                            <option value="20"
                                @isset($_GET['page_length']) {{ $_GET['page_length'] == 20 ? 'selected' : '' }} @endisset>
                                20</option>
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
                                autocomplete="off" name="q" value="{{Request()->q}}" />
                        </div>
                    </div>
                </div>
            </form>
            <div class="tables mt-2">
                <table class="table-auto w-full">
                    <tr>
                        <th>No.</th>
                        <th>Nama Debitur</th>
                        <th>No Aplikasi</th>
                        <th>No Polis</th>
                        <th>Tanggal Polis</th>
                        <th>Tanggal Rekam</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                    <tbody>
                        @forelse ($data as $item)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{$item->nama_debitur}}</td>
                                <td>{{$item->no_aplikasi}}</td>
                                <td>{{$item->no_polis}}</td>
                                <td>
                                    @if ($item->tgl_polis)
                                        {{date('d-m-Y', strtotime($item->tgl_polis))}}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if ($item->tgl_rekam)
                                        {{date('d-m-Y', strtotime($item->tgl_rekam))}}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if ($item->status == 'canceled')
                                        <button class="px-4 py-2 rounded text-red-500 toggle-canceled-modal"
                                            data-canceled_at="{{date('d-m-Y', strtotime($item->canceled_at))}}" data-user_id="{{ $item->canceled_by }}" data-target-id="modalCanceled">
                                            Dibatalkan
                                        </button>
                                    @else
                                        Onprogres
                                    @endif
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button class="px-4 py-2 bg-theme-btn/10 rounded text-theme-btn">
                                            Selengkapnya
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li class="">
                                                <a class="item-dropdown" href="#">Pembatalan</a>
                                            </li>
                                            <li class="">
                                                <a class="item-dropdown" href="#">Inquery</a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9">Data tidak tersedia.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="footer-table p-3 text-theme-text lg:flex lg:space-y-0 space-y-10 justify-between">
                <div class="w-full">
                    <div class="pagination kkb-pagination">
                        @if ($data instanceof \Illuminate\Pagination\LengthAwarePaginator)
                            {{ $data->links('pagination::tailwind') }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('extraScript')
    <script>
        function CanceledModalSuccessMessage(message) {
            Swal.fire({
                showConfirmButton: true,
                timer: 3000,
                closeOnClickOutside: true,
                title: 'Berhasil',
                icon: 'success',
            }).then((result) => {
                console.log('then')
                $("#modalConfirmPenyerahanUnit").addClass("hidden");
                //$('#preload-data').removeClass("hidden")

                //refreshTable()
                location.reload();
            })
        }

        function CanceledModalErrorMessage(message) {
            Swal.fire({
                showConfirmButton: false,
                timer: 3000,
                closeOnClickOutside: true,
                title: 'Gagal',
                icon: 'error',
            }).then((result) => {
                if (result.isConfirmed) {
                    //$('#preload-data').removeClass("hidden")

                    //refreshTable()
                    location.reload();
                }
            })
        }

        $('#page_length').on('change', function() {
            $('#form').submit()
        })
        $('#form-filter').on('submit', function(e) {
            const tAwal = $('#tAwal').val()
            const tAkhir = $('#tAkhir').val()
            const status = $('#status').val()
            if (tAwal != 'dd/mm/yyyy' && tAkhir != 'dd/mm/yyyy') {
                //$('#form-filter').submit();
            }
            else {
                e.preventDefault()
            }
            console.log(tAwal)
            console.log(tAkhir)
            console.log(status)
        })

        $('.toggle-canceled-modal').on('click', function() {
            var targetId = $(this).data("target-id");
            var data_canceled_at = $(this).data("canceled_at");
            var data_user_id = $(this).data("user_id");

            Swal.fire({
                showConfirmButton: false,
                closeOnClickOutside: false,
                title: 'Memuat...',
                html: 'Silahkan tunggu...',
                allowEscapeKey: false,
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading()
                }
            });

            $.ajax({
                type: "GET",
                url: "{{ url('/asuransi/registrasi/get-user') }}/"+data_user_id,
                success: function(data) {
                    Swal.close()
                    const nama = data['detail'] != 'undifined' ? data['detail']['nama'] : 'undifined';
                    
                    $("#" + targetId).removeClass("hidden");
                    $(`#${targetId} #canceled_at`).html(`Dibatalkan pada tanggal <b>${data_canceled_at}</b> oleh <b>${nama}</b>.`)
                    if (targetId.slice(0, 5) !== "modal") {
                        $(".layout-overlay-form").removeClass("hidden");
                    }
                },
                error: function(e) {
                    console.log(e)
                    Swal.close()
                }
            })
        })
    </script>
@endpush