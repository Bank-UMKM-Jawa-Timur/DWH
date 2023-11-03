
@extends('layout.master')
@push('extraStyle')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.7.7/xlsx.core.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xls/0.7.4-a/xls.core.min.js"></script>
@endpush
@section('content')
    <div class="head-pages">
        <p class="text-sm">Asuransi</p>
        <h2 class="text-2xl font-bold text-theme-primary tracking-tighter">
            List Pengajuan Klaim
        </h2>
    </div>
    <div class="body-pages">
        <div class="table-wrapper bg-white border rounded-md w-full p-2">
            <div class="table-accessiblity lg:flex text-center lg:space-y-0 space-y-5 justify-between">
                <div class="title-table lg:p-3 p-2 text-center">
                    <h2 class="font-bold text-lg text-theme-text tracking-tighter">
                        Status data klaim
                    </h2>
                </div>
                @if ($role == 'Staf Analis Kredit')
                    <div class="table-action flex lg:justify-normal justify-center p-2 gap-2">
                        <a href="{{ route('asuransi.pengajuan-klaim.create') }}">
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
                @endif
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
                                autocomplete="off" name="search" value="{{Request()->search}}" />
                        </div>
                    </div>
                </div>
            </form>
            <div class="tables mt-2">
                <table class="table-auto w-full">
                    <tr>
                        <th>No.</th>
                        <th>No Aplikasi</th>
                        <th>No Rekening</th>
                        <th>Status Klaim</th>
                        <th>Keterangan</th>
                        <th>Aksi</th>
                    </tr>
                    <tbody>
                        @forelse ($data as $item)
                        <form action="{{ route('asuransi.pengajuan-klaim.cek-status') }}" method="post">
                            @csrf
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>
                                    <input type="hidden" name="row_no_aplikasi" value="{{ $item->no_aplikasi }}">
                                    {{$item->no_aplikasi}}
                                </td>
                                <td>
                                    <input type="hidden" name="row_no_rek" value="{{ $item->no_rek }}">
                                    {{$item->no_rek}}
                                </td>
                                <td>
                                    <input type="hidden" name="row_status_klaim" value="{{ $item->stat_klaim }}">
                                    @if ($item->stat_klaim == 1)
                                        sedang di proses
                                    @elseif ($item->stat_klaim == 2)
                                        disetujui dan sedang menunggu pembayaran
                                    @elseif ($item->stat_klaim == 3)
                                        disetujui dan telah dibayarkan
                                    @elseif ($item->stat_klaim == 4)
                                        dokumen belum lengkap
                                    @elseif ($item->stat_klaim == 5)
                                        premi belum dibayar
                                    @elseif ($item->stat_klaim == 6)
                                        ditolak
                                    @else
                                        data tidak ditemukan
                                    @endif
                                </td>
                                <td>
                                    <input type="hidden" name="row_no_rekening_debit" value="778866550">
                                    <input type="hidden" name="row_keterangan" value="{{$item->status}}">
                                    {{$item->status}}
                                </td>
                                <td>
                                    @if ($role == 'Staf Analis Kredit')
                                        <div class="dropdown">
                                            <button class="px-4 py-2 bg-theme-btn/10 rounded text-theme-btn">
                                                Selengkapnya
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li class="">
                                                    <button type="submit" class="item-dropdown">Cek Status</button>
                                                </li>
                                                <li class="item-dropdown">
                                                    <form action="{{ route('asuransi.pengajuan-klaim.pembatalan-klaim') }}" method="post" enctype="multipart/form-data">
                                                        @csrf
                                                        <input type="hidden" name="id" value="{{ $item->id }}">
                                                        <input type="hidden" name="no_aplikasi" value="{{ $item->no_aplikasi }}">
                                                        <input type="hidden" name="no_rekening" value="{{ $item->no_rek }}">
                                                        <button type="submit">Pembatalan</button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        </form>
                        @empty
                        <tr>
                            <td class="text-theme-primary text-center" colspan="7">
                                Data tidak tersedia.
                            </td>
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
    $('#page_length').on('change', function() {
        $('#form').submit()
    })
</script>
@endpush
