@extends('layout.master')
@push('extraStyle')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.7.7/xlsx.core.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xls/0.7.4-a/xls.core.min.js"></script>
@endpush
@section('content')
    <div class="head-pages">
        <p class="text-sm">Laporan</p>
        <h2 class="text-2xl font-bold text-theme-primary tracking-tighter">
            Log Activity Asuransi
        </h2>
    </div>
    <div class="body-pages">
        <form id="form-report" action="" method="get" class="mt-4 w-full">
            <div class="bg-white border rounded-md w-full p-2">
                <div class="table-accessiblity lg:flex text-center lg:space-y-0 justify-between">
                    <div class="title-table lg:p-3 p-2 text-left">
                        <h2 class="font-bold text-lg text-theme-text tracking-tighter">
                            Form Log Data Asuransi
                        </h2>
                        @if (\Request::get('dari') && \Request::get('sampai'))
                            <p class="text-gray-600 text-sm">Menampilkan data mulai tanggal <b>{{date('d-m-Y', strtotime(\Request::get('dari')))}}</b> s/d <b>{{date('d-m-Y', strtotime(\Request::get('sampai')))}}.</p>
                        @endif
                    </div>
                </div>
                <div class="px-3 mb-5">
                    <div class="lg:grid-cols-2 w-full md:grid-cols-2 grid-cols-1 grid gap-5 justify-center">
                        <div class="col-span-1">
                            <div class="flex gap-5 w-full">
                                <div class="input-box space-y-3  w-full">
                                    <label for="" class="uppercase">Nomor Aplikasi<span class="text-theme-primary">*</span></label>
                                    <select name="no_aplikasi" id="no_aplikasi" class="w-full p-2 border" required>
                                        <option value="" selected>-- Pilih No Aplikasi ---</option>
                                        @foreach ($noAplikasi as $item)
                                            <option @if (old('no_aplikasi') == $item->no_aplikasi)
                                                selected @endif value="{{$item->no_aplikasi}}" @if(\Request::has('no_aplikasi')) selected @endif>
                                                {{$item->no_aplikasi}} - {{$item->nama_debitur}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="flex space-y-8 col-span-1">
                                    <label for="" class="uppercase"><span class="text-theme-primary"></span></label>
                                    <button class="px-6 py-3 bg-theme-primary flex gap-3 rounded text-white" id="btn-show">
                                        <span class="lg:block hidden"> Tampilkan </span>
                                    </button>
                                </div>
                            </div>
                            <small class="form-text text-red-600 error no-aplikasi-error"></small>
                        </div>
                    </div>
                </div>
            </div>
            @if (\Request::has('no_aplikasi'))
                <div class="table-wrapper bg-white border rounded-md w-full p-2 mt-5">
                    <div class="table-accessiblity lg:flex text-center lg:space-y-0 space-y-5 justify-between">
                        <div class="title-table lg:p-3 p-2 text-left">
                            <h2 class="font-bold text-lg text-theme-text tracking-tighter">
                                Log Data Asuransi
                            </h2>
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
                                    <th>Aktivitas</th>
                                    <th>Waktu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($data as $item)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{$item->content}}</td>
                                        <td>{{date('d-m-Y H:i:s', strtotime($item->created_at))}}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3">Belum ada aktifitas.</td>
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
            @endif
        </form>
    </div>
@endsection
@push('extraScript')
    <script>
        $('#no_aplikasi').select2()

        $('#page_length').on('change', function() {
            $('#form-report').submit()
        })

        $('#btn-show').on('click', function(e) {
            e.preventDefault()
            var no_aplikasi = $('#no_aplikasi').val()

            if (no_aplikasi != '') {
                $('#form-report').submit()
            }
            else {
                var msg = 'Harap pilih tanggal terlebih dahulu.'
                $('.no-aplikasi-error').html(msg)
                $('#no_aplikasi').addClass('border-red-500')
            }
        })

        $('#q').keypress(function (e) {
            if (e.which == 13) {
                $('#form-report').submit();
              return false;    //<---- Add this line
            }
        });

        // Adjust pagination url
        var btn_pagination = $(`.pagination`).find('a')
        var page_url = window.location.href
        $(`.pagination`).find('a').each(function(i, obj) {
            if (page_url.includes('no_aplikasi')) {
                btn_pagination[i].href += `&no_aplikasi=${$('#no_aplikasi').val()}`
            }
            if (page_url.includes('q')) {
                btn_pagination[i].href += `&q=${$('#q').val()}`
            }
        })
    </script>
@endpush
