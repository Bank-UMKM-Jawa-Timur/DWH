@extends('layout.master')
@section('modal')
<!-- Modal-Filter -->
@include('pages.kredit.modal.filter-modal')
<!-- Modal PO -->
@include('pages.kredit.modal.detail-po')
<!-- Modal Atur Ketersediaan Unit -->
@include('pages.kredit.modal.atur-ketersediaan-unit-modal')
<!-- Modal Upload Bukti Pembayaran -->
@include('pages.kredit.modal.upload-bukti-pembayaran-modal')
<!-- Modal Preview Bukti Pembayaran -->
@include('pages.kredit.modal.bukti-pembayaran-modal')
<!-- Modal Confirm Bukti Pembayaran -->
@include('pages.kredit.modal.confirm-bukti-pembayaran-modal')
<!-- Modal Upload Bukti Penyerahan Unit -->
@include('pages.kredit.modal.upload-penyerahan-unit-modal')
<!-- Modal Confirm Bukti Penyerahan Unit -->
@include('pages.kredit.modal.confirm-penyerahan-unit')
<!-- Modal Upload Berkas -->
@include('pages.kredit.modal.upload-berkas-modal')
<!-- Modal Upload Imbal Jasa -->
@include('pages.kredit.modal.upload-bukti-imbal-jasa')
<!-- Modal Confirm Imbal Jasa -->
@include('pages.kredit.modal.confirm-bukti-pembayaran-imbal-jasa-modal')
<!-- Modal Detail PO -->
@include('pages.kredit.modal.detail-modal')
@endsection
@section('content')
    <div class="head-pages">
        <p class="text-sm">KKB</p>
        <h2 class="text-2xl font-bold text-theme-primary tracking-tighter">
            KKB
        </h2>
    </div>
    <div class="body-pages">
        <div class="table-wrapper bg-white border rounded-md w-full p-2">
            <div class="table-accessiblity lg:flex text-center lg:space-y-0 space-y-5 justify-between">
                <div class="title-table lg:p-3 p-2 text-center">
                    <h2 class="font-bold text-lg text-theme-text tracking-tighter">
                        Data KKB
                    </h2>
                </div>
                <div class="table-action flex lg:justify-normal justify-center p-2 gap-2">
                    @if (isset($_GET['tglAwal']) || isset($_GET['tglAkhir']) || isset($_GET['status']))
                    <form action="" method="get">
                        <button type="submit" class="px-6 py-2 bg-theme-primary/10 flex gap-3 rounded text-theme-primary">
                            <span class="lg:mt-1.5 mt-0">
                                @include('components.svg.reset')
                            </span>
                            <span class="lg:block hidden"> Reset </span>
                        </button>
                    </form>
                        
                    @endif
                    <button data-target-id="filter-kkb" type="button"
                        class="toggle-modal px-6 py-2 bg-theme-primary flex gap-3 rounded text-white">
                        <span class="lg:mt-1 mt-0">
                            @include('components.svg.filter')
                        </span>
                        <span class="lg:block hidden"> Filter </span>
                    </button>
                </div>
            </div>
            <div class="lg:flex lg:space-y-0 space-y-5 lg:text-left text-center justify-between mt-2 p-2">
                <div class="sorty pl-1 w-full">
                    <label for="" class="mr-3 text-sm text-neutral-400">show</label>
                    <select name="" class="border px-4 py-1.5 cursor-pointer rounded appearance-none text-center"
                        id="">
                        <option value="">5</option>
                        <option value="">10</option>
                        <option value="">15</option>
                        <option value="">20</option>
                    </select>
                    <label for="" class="ml-3 text-sm text-neutral-400">entries</label>
                </div>
                <div class="search-table lg:w-96 w-full">
                    <div class="input-search text-[#BFBFBF] rounded-md border flex gap-2">
                        <span class="mt-2 ml-3">
                            @include('components.svg.search')
                        </span>
                        <input type="search" placeholder="Search" class="p-2 rounded-md w-full outline-none text-[#BFBFBF]"
                            autocomplete="off" />
                    </div>
                </div>
            </div>
            <div class="tables mt-2">
                @include('pages.kredit.partial._table')
                {{--  <table class="table-auto w-full">
                    <tr>
                        <th>No.</th>
                        <th>Nama</th>
                        <th>PO</th>
                        <th>Kendaraan Unit</th>
                        <th>Bukti Pembayaran</th>
                        <th>Penyerahan Unit</th>
                        <th>STNK</th>
                        <th>POLIS</th>
                        <th>BKPB</th>
                        <th>Bukti Pembayaran imbal jasa</th>
                        <th>Imbal jasa</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Farhan</td>
                            <td>001/PO/07/2023</td>
                            <td>15-08-2023</td>
                            <td>-</td>
                            <td>22-08-2023</td>
                            <td>24-08-2023</td>
                            <td>24-08-2023</td>
                            <td>24-08-2023</td>
                            <td>Rp.100.000</td>
                            <td>Selesai</td>
                            <td>Done</td>
                            <td>
                                <div class="dropdown">
                                    <button class="px-4 py-2 bg-theme-btn/10 rounded text-theme-btn">
                                        Selengkapnya
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li class="">
                                            <a class="item-dropdown" href="#">Detail</a>
                                        </li>
                                        <li class="">
                                            <a class="item-dropdown" href="#">Hapus</a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Muhammad Khalil Z.</td>
                            <td>001/PO/07/2023</td>
                            <td>Menunggu tanggal ketersediaan unit</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>diproses</td>
                            <td>
                                <div class="dropdown">
                                    <button class="px-4 py-2 bg-theme-btn/10 rounded text-theme-btn">
                                        Selengkapnya
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li class="">
                                            <a class="item-dropdown" href="#">Detail</a>
                                        </li>
                                        <li class="">
                                            <a class="item-dropdown" href="#">Hapus</a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Edi</td>
                            <td>001/PO/07/2023</td>
                            <td>Menunggu tanggal ketersediaan unit</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>diproses</td>
                            <td>
                                <div class="dropdown">
                                    <button class="px-4 py-2 bg-theme-btn/10 rounded text-theme-btn">
                                        Selengkapnya
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li class="">
                                            <a class="item-dropdown" href="#">Detail</a>
                                        </li>
                                        <li class="">
                                            <a class="item-dropdown" href="#">Hapus</a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>Ahmad Roni.</td>
                            <td>001/PO/07/2023</td>
                            <td>Menunggu tanggal ketersediaan unit</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>diproses</td>
                            <td>
                                <div class="dropdown">
                                    <button class="px-4 py-2 bg-theme-btn/10 rounded text-theme-btn">
                                        Selengkapnya
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li class="">
                                            <a class="item-dropdown" href="#">Detail</a>
                                        </li>
                                        <li class="">
                                            <a class="item-dropdown" href="#">Hapus</a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>5</td>
                            <td>Dani Riyadi.</td>
                            <td>001/PO/07/2023</td>
                            <td>Menunggu tanggal ketersediaan unit</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>diproses</td>
                            <td>
                                <div class="dropdown">
                                    <button class="px-4 py-2 bg-theme-btn/10 rounded text-theme-btn">
                                        Selengkapnya
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li class="">
                                            <a class="item-dropdown" href="#">Detail</a>
                                        </li>
                                        <li class="">
                                            <a class="item-dropdown" href="#">Hapus</a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>  --}}
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
    </div>
@endsection
