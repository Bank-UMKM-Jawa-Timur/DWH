
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
                <div class="table-action flex lg:justify-normal justify-center p-2 gap-2">
                    <a href="{{ route('pengajuan-klaim.create') }}">
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
                        <th>Cabang</th>
                        <th>No Aplikasi</th>
                        <th>No Rekening</th>
                        <th>No Klaim</th>
                        <th>Tanggal Klaim</th>
                        <th>Refund</th>
                        <th>Nilai Persetujuan</th>
                        <th>No Rekening Debet</th>
                        <th>Keterangan</th>
                        <th>Aksi</th>
                    </tr>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Surabaya</td>
                            <td>K21002022000010</td>
                            <td>21000572548826</td>
                            <td>2225</td>
                            <td>326/SP-02/JSB/331/VII-2022</td>
                            <td>18-07-2022</td>
                            <td>158210522.00</td>
                            <td>0000</td>
                            <td>Sedang  diprosess</td>
                            <td>
                            <div class="dropdown">
                                <button class="px-4 py-2 bg-theme-btn/10 rounded text-theme-btn">
                                    Selengkapnya
                                </button>
                                <ul class="dropdown-menu">
                                    <li class="">
                                        <a class="item-dropdown" href="#">Cek Status</a>
                                    </li>
                                </ul>
                                </div>

                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="footer-table p-3 text-theme-text lg:flex lg:space-y-0 space-y-10 justify-between">
                <div>
                    <p class="mt-3 text-sm">Menampilkan 1 - 5 dari 100 Data</p>
                </div>
                <div>
                    <div class="pagination">
                        <button class="btn-pagination">Previous</button>
                        <button class="btn-pagination is-active">1</button>
                        <button class="btn-pagination">2</button>
                        <button class="btn-pagination">3</button>
                        <button class="btn-pagination">4</button>
                        <button class="btn-pagination">5</button>
                        <button class="btn-pagination">...</button>
                        <button class="btn-pagination">100</button>
                        <button class="btn-pagination">Next</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
