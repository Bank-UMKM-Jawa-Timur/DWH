@extends('layout.master')
@section('modal')
<!-- Modal-tambah -->
@include('pages.vendor.modal.create')
<!-- Modal-edit -->
@include('pages.vendor.modal.edit')
@endsection
@section('content')
<div class="head-pages">
    <p class="text-sm">Master</p>
    <h2 class="text-2xl font-bold text-theme-primary tracking-tighter">
        Vendor
    </h2>
</div>
<div class="body-pages">
    <div class="table-wrapper bg-white border rounded-md w-full p-2">
        <div class="table-accessiblity lg:flex text-center lg:space-y-0 space-y-5 justify-between">
            <div class="title-table lg:p-3 p-2 text-center">
                <h2 class="font-bold text-lg text-theme-text tracking-tighter">
                    Vendor
                </h2>
            </div>
            <div class="table-action flex lg:justify-normal justify-center p-2 gap-2">
                <button data-target-id="add-vendor"
                    class="toggle-modal px-6 py-2 bg-theme-primary flex gap-3 rounded text-white">
                    <span class="lg:mt-0 mt-0">
                        @include('components.svg.plus')
                    </span>
                    <span class="lg:block hidden"> Tambah Vendor </span>
                </button>
            </div>
        </div>
        <div
            class="lg:flex lg:space-y-0 space-y-5 lg:text-left text-center justify-between mt-2 p-2">
            <div class="sorty pl-1 w-full">
                <label for="" class="mr-3 text-sm text-neutral-400">show</label>
                <select name=""
                    class="border px-4 py-1.5 cursor-pointer rounded appearance-none text-center"
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
                    <input type="search" placeholder="Search"
                        class="p-2 rounded-md w-full outline-none text-[#BFBFBF]"
                        autocomplete="off" />
                </div>
            </div>
        </div>
        <div class="tables mt-2">
            <table class="table-auto w-full">
                <tr>
                    <th>No.</th>
                    <th>Nama</th>
                    <th>Alamat</th>
                    <th>Nomor HP</th>
                    <th>Aksi</th>
                </tr>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>01497</td>
                        <td>-</td>
                        <td>-</td>
                        <td>
                            <div class="dropdown">
                                <button class="px-4 py-2 bg-theme-btn/10 rounded text-theme-btn">
                                    Selangkapnya
                                </button>
                                <ul class="dropdown-menu">
                                    <li class="">
                                        <a class="item-dropdown toggle-modal" data-target-id="edit-vendor"  href="#">Edit</a>
                                    </li>
                                    <li class="">
                                        <a class="item-dropdown" href="#">Hapus</a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
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
</div>
@endsection