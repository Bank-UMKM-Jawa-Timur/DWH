@extends('layout.master')
@push('extraStyle')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.7.7/xlsx.core.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xls/0.7.4-a/xls.core.min.js"></script>
@endpush
@section('content')
    <div class="head-pages">
        <p class="text-sm">Laporan</p>
        <h2 class="text-2xl font-bold text-theme-primary tracking-tighter">
            Pembayaran
        </h2>
    </div>
    <div class="body-pages">
        <form id="form-report" action="" method="get" class="mt-4 w-full">
            <div class="bg-white border rounded-md w-full p-2">
                <div class="table-accessiblity lg:flex text-center lg:space-y-0 justify-between">
                    <div class="title-table lg:p-3 p-2 text-left">
                        <h2 class="font-bold text-lg text-theme-text tracking-tighter">
                            Form Laporan Pembayaran
                        </h2>
                        @if (\Request::get('tAwal') && \Request::get('tAkhir'))
                            <p class="text-gray-600 text-sm">Menampilkan data mulai tanggal <b>{{date('d-m-Y', strtotime(\Request::get('tAwal')))}}</b> s/d <b>{{date('d-m-Y', strtotime(\Request::get('tAkhir')))}}</b> dengan status <b>{{Request()->status == 'canceled' ? 'dibatalkan' : 'onprogres'}}</b>.</p>
                        @endif
                    </div>
                </div>
                <div class="px-3 mb-5">
                    <div class="lg:grid-cols-4 w-full md:grid-cols-2 grid-cols-1 grid gap-5 justify-center">
                        <div class="input-box-calendar space-y-3 col-span-1">
                            <label for="" class="uppercase">Dari<span class="text-theme-primary">*</span></label>
                            <input type="text" class="datepicker p-2 w-full border" id="dari"
                                name="dari" value="@if(\Request::has('dari')) {{\Request::get('dari')}} @endif" required/>
                            <small class="form-text text-red-600 error dari-error"></small>
                        </div>
                        <div class="input-box-calendar space-y-3 col-span-1">
                            <label for="" class="uppercase">Sampai<span class="text-theme-primary">*</span></label>
                            <input type="text" class="datepicker p-2 w-full border" id="sampai"
                                name="sampai" value="{{old('sampai')}}" required/>
                            <small class="form-text text-red-600 error sampai-error"></small>
                        </div>
                        <div class="input-box space-y-3 col-span-1">
                            <label for="" class="uppercase">Cabang</label> 
                            <select name="cabang" id="cabang" class="w-full p-2 border" required>
                                <option value="all" selected>-- Semua cabang ---</option>
                                @foreach ($cabang as $item)
                                    <option value="{{$item['kode_cabang']}}" @if(\Request::has('cabang')){{$item['kode_cabang'] == \Request::get('cabang') ? 'selected' : ''}}@endif>{{$item['kode_cabang']}} - {{$item['cabang']}}</option>
                                @endforeach
                            </select>
                            <small class="form-text text-red-600 error"></small>
                        </div>
                        <div class="input-box space-y-3 col-span-1">
                            <label for="" class="uppercase">NIP</label>
                            <select name="nip" id="nip" class="w-full p-2 border" required>
                                <option value="all" selected>-- Semua nip ---</option>
                                @if (\Request::has('cabang'))
                                    @if (\Request::get('cabang') != 'all')
                                        @foreach ($staf as $item)
                                            @if (is_array($item))
                                                @if (array_key_exists('id', $item))
                                                    <option value="{{$item['id']}}" @if(\Request::has('nip')){{$item['id'] == \Request::get('nip') ? 'selected' : ''}}@endif>{{$item['nip']}} - {{$item['detail']['nama']}}</option>
                                                @endif
                                            @endif
                                        @endforeach
                                    @endif
                                @endif
                            </select>
                            <small class="form-text text-red-600 error"></small>
                        </div>
                    </div>
                    <div class="flex gap-5 justify-end mt-4">
                        <button class="px-6 py-2 bg-theme-primary flex gap-3 rounded text-white" type="button"
                            id="btn-show">
                            <span class="lg:block hidden"> Tampilkan </span>
                        </button>
                        @if (\Request::has('dari') && \Request::has('sampai'))
                            <a href="{{route('asuransi.report.pembayaran')}}" id="btn-reset"
                                class="px-6 py-2 bg-theme-primary/10 flex gap-3 rounded text-theme-primary">
                                <span class="lg:block hidden"> Reset </span>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="table-wrapper bg-white border rounded-md w-full p-2 mt-5">
                <div class="table-accessiblity lg:flex text-center lg:space-y-0 space-y-5 justify-between">
                    <div class="title-table lg:p-3 p-2 text-left">
                        <h2 class="font-bold text-lg text-theme-text tracking-tighter">
                            Laporan Registrasi Asuransi
                        </h2>
                        @if (!\Request::has('dari') && !\Request::has('sampai'))
                            <p class="text-gray-600 text-sm">Menampilkan data pada bulan ini.</p>
                        @endif
                    </div>
                </div>
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
                                autocomplete="off" name="q" id="q" value="{{Request()->q}}" />
                        </div>
                    </div>
                </div>
                <div class="tables mt-2">
                    <table class="table-auto w-full">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Perusahaan</th>
                                <th>Debitur</th>
                                <th>Cabang</th>
                                <th>No Aplikasi</th>
                                <th>No Bukti Pembayaran</th>
                                <th>No Rekening</th>
                                <th>No PK</th>
                                <th>Tanggal</th>
                                <th>Premi</th>
                                <th>Premi Disetor</th>
                                <th>Periode Bayar</th>
                                <th>Total Periode(dalam tahun)</th>
                                <th>Status Bayar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pembayaran as $item)
                                @php
                                    $cabang = $item->pengajuan ? $item->pengajuan['cabang'] : 'undifined';
                                @endphp
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$item->perusahaan}}</td>
                                    <td>{{$item->nama_debitur}}</td>
                                    <td>{{$cabang}}</td>
                                    <td>{{$item->no_aplikasi}}</td>
                                    <td>{{$item->nobukti_pembayaran}}</td>
                                    <td>{{$item->no_rek}}</td>
                                    <td>{{$item->no_pk}}</td>
                                    <td>{{date('d-m-y', strtotime($item->tgl_bayar))}}</td>
                                    <td>{{number_format($item->premi, 0, ',', '.')}}</td>
                                    <td>{{number_format($item->premi_disetor, 0, ',', '.')}}</td>
                                    <td>{{$item->periode_bayar}}</td>
                                    <td>{{$item->total_periode}}</td>
                                    <td>{{$item->is_paid ? 'Sudah' : 'Belum'}}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="13">Belum ada data.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="footer-table p-3 text-theme-text lg:flex lg:space-y-0 space-y-10 justify-between">
                    <div class="w-full">
                        <div class="pagination kkb-pagination">
                            @if ($pembayaran instanceof \Illuminate\Pagination\LengthAwarePaginator)
                                {{ $pembayaran->links('pagination::tailwind') }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
@push('extraScript')
    {{--  <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>  --}}
    <script>
        $('#page_length').on('change', function() {
            $('#form-report').submit()
        })
        
        $('.datepicker').val('dd-mm-yyyy');
        $('#cabang').select2()
        $('#nip').select2()

        $('#cabang').on('change', function() {
            $('#preload-data').removeClass('hidden')
            const kode_cabang = $(this).val()
            if (kode_cabang != '') {
                $.ajax({
                    url: `{{url('/get-staf-by-cabang')}}/${kode_cabang}`,
                    method: "GET",
                    success: function(response) {
                        if (Array.isArray(response)) {
                            $('#nip').empty()
                            $('#nip').append(`<option value="all" selected>-- Semua nip ---</option>`)
                            for (var i=0; i < response.length; i++) {
                                $('#nip').append(`<option value="${response[i]['id']}" selected>${response[i]['nip']} - ${response[i]['detail']['nama']}</option>`)
                            }
                            $('#preload-data').addClass('hidden')
                        }
                    },
                    error: function(e) {
                        $('#preload-data').addClass('hidden')
                        console.log(e)
                    }
                })
            }
        })

        $('#btn-show').on('click', function(e) {
            e.preventDefault()
            $('#preload-data').removeClass('hidden')
            var dari = $('#dari').val()
            var sampai = $('#sampai').val()

            if (dari != 'dd-mm-yyyy' && sampai != 'dd-mm-yyyy') {
                $('#form-report').submit()
            }
            else {
                $('#preload-data').addClass('hidden')
                var msg = 'Harap pilih tanggal terlebih dahulu.'
                $('.dari-error').html(msg)
                $('.sampai-error').html(msg)
                $('#dari').addClass('border-red-500')
                $('#sampai').addClass('border-red-500')
            }
        })

        $('#btn-reset').on('click', function(e) {
            $('#preload-data').removeClass('hidden')
        })

        $('#q').keypress(function (e) {
            if (e.which == 13) {
                $('#form-report').submit();
              return false;    //<---- Add this line
            }
        });
    </script>
    @if(\Request::has('dari'))
        <script>
            $('#dari').val("{{\Request::get('dari')}}")
        </script>
    @endif
    @if(\Request::has('sampai'))
        <script>
            $('#sampai').val("{{\Request::get('sampai')}}")
        </script>
    @endif
@endpush
