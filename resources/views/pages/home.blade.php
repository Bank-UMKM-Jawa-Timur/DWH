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
@php
    $user = \Session::get(config('global.auth_session'));
    $token = \Session::get(config('global.user_token_session'));
    $role = \Session::get(config('global.role_id_session')) == 3 ? 'Vendor' : $user['role'];
@endphp
    <div class="head-pages">
        <p class="text-sm">Dashboard</p>
        <h2 class="text-2xl font-bold text-theme-primary tracking-tighter">
            {{ $role }}
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
                    <button class="px-6 py-2 bg-theme-primary/10 flex gap-3 rounded text-theme-primary">
                        <span class="lg:mt-1.5 mt-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 16 16">
                                <path fill="currentColor" fill-rule="evenodd"
                                    d="M5.905.28A8 8 0 0 1 14.5 3.335V1.75a.75.75 0 0 1 1.5 0V6h-4.25a.75.75 0 0 1 0-1.5h1.727a6.5 6.5 0 1 0 .526 5.994a.75.75 0 1 1 1.385.575A8 8 0 1 1 5.905.279Z"
                                    clip-rule="evenodd" />
                            </svg>
                        </span>
                        <span class="lg:block hidden"> Reset </span>
                    </button>
                    <button data-target-id="filter-kkb" type="button"
                        class="toggle-modal px-6 py-2 bg-theme-primary flex gap-3 rounded text-white">
                        <span class="lg:mt-1 mt-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24">
                                <path fill="none" stroke="currentColor" stroke-width="1.5"
                                    d="M19 3H5c-1.414 0-2.121 0-2.56.412C2 3.824 2 4.488 2 5.815v.69c0 1.037 0 1.556.26 1.986c.26.43.733.698 1.682 1.232l2.913 1.64c.636.358.955.537 1.183.735c.474.411.766.895.898 1.49c.064.284.064.618.064 1.285v2.67c0 .909 0 1.364.252 1.718c.252.355.7.53 1.594.88c1.879.734 2.818 1.101 3.486.683c.668-.417.668-1.372.668-3.282v-2.67c0-.666 0-1 .064-1.285a2.68 2.68 0 0 1 .899-1.49c.227-.197.546-.376 1.182-.735l2.913-1.64c.948-.533 1.423-.8 1.682-1.23c.26-.43.26-.95.26-1.988v-.69c0-1.326 0-1.99-.44-2.402C21.122 3 20.415 3 19 3Z" />
                            </svg>
                        </span>
                        <span class="lg:block hidden"> Filter </span>
                    </button>
                </div>
            </div>
            <div class="lg:flex lg:space-y-0 space-y-5 lg:text-left text-center justify-between mt-2 p-2">
                <div class="sorty pl-1 w-full">
                    <label for="" class="mr-3 text-sm text-neutral-400">show</label>
                    <select name=""
                        class="border outline-none px-4 py-1.5 cursor-pointer rounded appearance-none text-center"
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
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24">
                                <path fill="currentColor"
                                    d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0 0 16 9.5A6.5 6.5 0 1 0 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5S14 7.01 14 9.5S11.99 14 9.5 14z" />
                            </svg>
                        </span>
                        <input type="search" placeholder="Search"
                            class="p-2 rounded-md w-full border-none outline-none text-[#BFBFBF]" autocomplete="off" />
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
