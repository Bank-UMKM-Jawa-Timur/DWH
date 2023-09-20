@extends('layout.master')

@section('title', $title)

@section('content')
<div class="head-pages">
    <p class="text-sm">Master</p>
    <h2 class="text-2xl font-bold text-theme-primary tracking-tighter">
        Tambah Dictionary
    </h2>
</div>
<div class="body-pages">
    <div class="form-dictionary bg-white w-full space-y-5 p-4 rounded-md border">
        <div class="form-input lg:flex grid grid-cols-1 gap-5">
            <div class="input-box space-y-3 w-full">
                <label for="" class="">File</label>
                <input type="text" class="p-2 w-full border bg-gray-100" />
            </div>
            <div class="input-box space-y-3 w-full">
                <label for="" class="">Deskripsi</label>
                <input type="text" class="p-2 w-full border bg-gray-100" />
            </div>
        </div>
        <div class="table-wrapper bg-white border rounded-md w-full p-2">
            <div class="table-accessiblity lg:flex text-center lg:space-y-0 space-y-5 justify-between">
                <div class="title-table lg:p-3 p-2 text-center">
                    <h2 class="font-bold text-lg text-theme-text tracking-tighter">
                        Item
                    </h2>
                </div>
            </div>
            <div class="lg:flex lg:space-y-0 space-y-5 lg:text-left text-center justify-between mt-2 p-2">
                <div class="sorty pl-1">
                    <a href="#" class="font-bold tracking-tighter">Lihat Contoh Format</a>
                    <p>
                        Catatan! Jika menggunakan fitur import, maka data pada
                        tabel akan dikosongkan terlebih dahulu.
                    </p>
                </div>
                <div class="table-action flex lg:justify-normal justify-center p-2 gap-2">
                    <button id="form-toggle" class="px-6 py-2 bg-gray-600 border flex gap-3 rounded text-white">
                        <span class="lg:block hidden"> Upload file</span>
                    </button>
                    <button id="form-toggle" class="px-6 py-2 border flex gap-3 rounded text-gray-600">
                        <span class="lg:block hidden"> Import </span>
                    </button>
                </div>
            </div>
            <div class="tables mt-2">
                <table class="table-dictionary table-auto w-full">
                    <tr>
                        <th>No.</th>
                        <th>Field</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Length</th>
                        <th>
                            <button class="w-10 h-10 text-center text-lg rounded-full bg-gray-600 text-white">
                                <span class="flex justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24">
                                        <path fill="currentColor" d="M19 12.998h-6v6h-2v-6H5v-2h6v-6h2v6h6z" />
                                    </svg>
                                </span>
                            </button>
                        </th>
                    </tr>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>
                                <input type="text" class="border p-2 bg-gray-100" />
                            </td>
                            <td>
                                <input type="text" class="border p-2 bg-gray-100" />
                            </td>
                            <td>
                                <input type="text" class="border p-2 bg-gray-100" />
                            </td>
                            <td>
                                <input type="text" class="border p-2 bg-gray-100" />
                            </td>

                            <td>
                                <button class="w-10 h-10 text-center text-lg rounded-full bg-theme-primary text-white">
                                    <span class="flex justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24">
                                            <path fill="currentColor" d="M19 12.998H5v-2h14z" />
                                        </svg>
                                    </span>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="footer-table p-3 text-theme-text lg:flex lg:space-y-0 space-y-10 justify-between"></div>
        </div>
    </div>
</div>
@endsection