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
    <!-- Modal-Batal -->
    @include('pages.asuransi-registrasi.modal.batal')
    <!-- Modal-Pelunasan -->
    @include('pages.asuransi-registrasi.modal.pelunasan')
    <!-- Modal-Send -->
    @include('pages.asuransi-registrasi.modal.send')
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
                    @if (\Request::get('tAwal') && \Request::get('tAkhir'))
                    <a href="{{route('asuransi.registrasi.index')}}"
                        class="px-6 py-2 bg-theme-primary/10 flex gap-3 rounded text-theme-primary">
                        <span class="lg:mt-1.5 mt-0">
                            @include('components.svg.reset')
                        </span>
                        <span class="lg:block hidden"> Reset </span>
                    </a>
                    @endif
                    <a>
                        <button data-target-id="filter" type="button"
                            class="toggle-modal px-6 py-2 bg-theme-primary flex gap-3 rounded text-white">
                            <span class="lg:mt-1 mt-0">
                                @include('components.svg.filter')
                            </span>
                            <span class="lg:block hidden"> Filter </span>
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
                        <th>Cabang</th>
                        <th>Tanggal Pengajuan</th>
                        <th>Nama Debitur</th>
                        <th>Nomor PK</th>
                        <th>Jenis Kredit</th>
                        <th>Plafond</th>
                        <th>Aksi</th>
                    </tr>
                    <tbody>
                        @forelse ($data as $item)
                            <tr class="view cursor-pointer">
                                <td>{{$loop->iteration}}</td>
                                <td>{{$item['cabang']}}</td>
                                <td>{{date('d-m-Y', strtotime($item['tanggal']))}}</td>
                                <td>{{$item['nama']}}</td>
                                <td>{{$item['no_pk']}}</td>
                                <td>{{$item['skema_kredit']}}</td>
                                <td>Rp {{number_format($item['jumlah_kredit'], 0, ',', '.')}}</td>
                                <td>
                                    <div class="flex gap-4 justify-center">
                                        @if(count($item['jenis_asuransi']) > 0)
                                            <button class="flex gap-2 hover:bg-slate-50 border px-3 py-2">
                                                <span class="caret-icon transform">
                                                    @include('components.svg.caret')
                                                </span>
                                                <span class="collapse-text">Sembunyikan Asuransi</span>
                                            </button>
                                        @else 
                                            <span class="caret-icon transform"></span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            <tr class="collapse-table">
                                <td colspan="11" class="p-0">
                                    <div class="bg-theme-primary/5">
                                        {{-- Jenis Asuransi  --}}
                                        @foreach ($item['jenis_asuransi'] as $jenis)
                                            @php
                                                $alpha = strtolower(chr(64+$loop->iteration));
                                                $id_pengajuan = $item['id'];
                                                $no_pk = $item['no_pk'];
                                                $perusahaan = $jenis->asuransi ? $jenis->asuransi->perusahaan : '-';
                                                $no_aplikasi = $jenis->asuransi ? $jenis->asuransi->no_aplikasi : '-';
                                                $no_aplikasi = $jenis->asuransi ? $jenis->asuransi->no_aplikasi : '-';
                                                $tarif = $jenis->asuransi ? $jenis->asuransi->tarif : '-';
                                                $premi = $jenis->asuransi ? $jenis->asuransi->premi : '-';
                                                $refund = $jenis->asuransi ? $jenis->asuransi->refund : '-';
                                                $handling_fee = $jenis->asuransi ? $jenis->asuransi->handling_fee : '-';
                                                $premi_disetor = $jenis->asuransi ? $jenis->asuransi->premi_disetor : '-';
                                                $status = $jenis->asuransi ? $jenis->asuransi->status : '-';
                                                $is_paid = $jenis->asuransi ? $jenis->asuransi->is_paid : '-';
                                            @endphp
                                            <div>
                                                <div class="flex justify-start p-3">
                                                    <h1 class="font-bold text-lg text-theme-primary">{{$alpha.'. '.$jenis->jenis}}</h1>
                                                </div>
                                                <div class="mt-2 p-3">
                                                    <table class="table-collapse">
                                                        <thead>
                                                            <th>Perusahaan</th>
                                                            <th>Nomor Aplikasi</th>
                                                            <th>Tarif</th>
                                                            <th>Premi </th>
                                                            <th>Refund</th>
                                                            <th>Handling Fee</th>
                                                            <th>Premi Disetor</th>
                                                            <th>Status</th>
                                                            <th>Aksi</th>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>{{$perusahaan}}</td>
                                                                <td>{{$no_aplikasi}}</td>
                                                                <td>{{$tarif}}</td>
                                                                <td>{{$premi != '-' ? 'Rp '.number_format($premi, 0, ',', '.') : '-'}}</td>
                                                                <td>{{$refund != '-' ? 'Rp '.number_format($refund, 0, ',', '.') : '-'}}</td>
                                                                <td>{{$handling_fee != '-' ? 'Rp '.number_format($handling_fee, 0, ',', '.') : '-'}}</td>
                                                                <td>{{$premi_disetor != '-' ? 'Rp '.number_format($premi_disetor, 0, ',', '.') : '-'}}</td>
                                                                <td>{{$status}}</td>
                                                                <td>
                                                                    <div class="flex gap-5 justify-center">
                                                                        @if ($jenis->asuransi)
                                                                            @if (strtolower($status) == 'waiting approval')
                                                                                @if ($role == 'Staf Analis Kredit')
                                                                                    -
                                                                                @else
                                                                                    <a href="{{route('asuransi.registrasi.review')}}?id={{$id_pengajuan}}&asuransi={{$jenis->asuransi->id}}">
                                                                                        <button class="px-4 py-2  bg-orange-400/20 rounded text-orange-500">
                                                                                            Review
                                                                                        </button>
                                                                                    </a>
                                                                                @endif
                                                                            @elseif(strtolower($status) == 'approved')
                                                                                @if ($role == 'Staf Analis Kredit')
                                                                                    <button class="px-4 py-2 bg-green-400/20 rounded text-green-500 modal-kirim"
                                                                                        data-modal-target="modalSend" data-id="{{$jenis->asuransi->id}}"
                                                                                        data-no_aplikasi="{{$jenis->asuransi->no_aplikasi}}" data-debitur="{{$item['nama']}}">
                                                                                        Kirim
                                                                                    </button>
                                                                                @else
                                                                                    -
                                                                                @endif
                                                                            @elseif(strtolower($status) == 'revition')
                                                                                @if ($role == 'Staf Analis Kredit')
                                                                                    <button class="px-4 py-2  bg-orange-400/20 rounded text-orange-500">
                                                                                        Edit
                                                                                    </button>
                                                                                @else
                                                                                    -
                                                                                @endif
                                                                            @elseif(strtolower($status) == 'sended')
                                                                                @if ($role == 'Staf Analis Kredit')
                                                                                    <div class="dropdown">
                                                                                        <button class="px-4 py-2 bg-theme-btn/10 rounded text-theme-btn">
                                                                                            Selengkapnya
                                                                                        </button>
                                                                                        <ul class="dropdown-menu right-16">
                                                                                            <li class="">
                                                                                                <a class="item-dropdown modal-batal" href="#"
                                                                                                    data-modal-toggle="modalBatal" data-modal-target="modalBatal"
                                                                                                    data-id="" data-no_aplikasi=""
                                                                                                    data-no_polis="">Pembatalan</a>
                                                                                            </li>
                                                                                            <li class="">
                                                                                                <form action="{{route('asuransi.registrasi.inquery')}}" method="get">
                                                                                                    <input type="hidden" name="no_aplikasi" value="">
                                                                                                    <button class="item-dropdown w-full" type="submit">Cek(Inquery)</button>
                                                                                                </form>
                                                                                            </li>
                                                                                            <li class="">
                                                                                                <a class="item-dropdown modal-pelunasan" href="#" data-modal-toggle="modalPelunasan"
                                                                                                    data-modal-target="modalPelunasan"  data-id=""
                                                                                                    data-no_aplikasi="" data-no_rek=""
                                                                                                    data-no_polis="" data-refund=""
                                                                                                    data-tgl_awal="" data-tgl_akhir="">Pelunasan</a>
                                                                                            </li>
                                                                                        </ul>
                                                                                    </div>
                                                                                @else
                                                                                    <button class="px-4 py-2 bg-theme-btn/10 rounded text-theme-btn">
                                                                                        Detail
                                                                                    </button>
                                                                                @endif
                                                                            @else
                                                                                -
                                                                            @endif
                                                                        @else
                                                                            @if ($role == 'Staf Analis Kredit')
                                                                                <button class="px-4 py-2  bg-theme-primary/20 rounded text-theme-primary">
                                                                                    Tidak Registrasi
                                                                                </button>
                                                                                <a href="{{route('asuransi.registrasi.create')}}?id={{$id_pengajuan}}&jenis_asuransi={{$jenis->id}}">
                                                                                    <button class="px-4 py-2 bg-blue-500/20 rounded text-blue-500">
                                                                                        Registrasi
                                                                                    </button>
                                                                                </a>
                                                                            @else
                                                                                -
                                                                            @endif
                                                                        @endif
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        @endforeach\
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
    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
    <script>
        $("table .view").on("click", function(e){
            // console.log($(this).nextElementSiblig("td div.collapse-table"));
            $(this).next(".collapse-table").toggleClass("hidden");
        });

        $('.dropdown .dropdown-menu .item-dropdown').on('click', function(e){
            e.stopPropagation();
        })

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
