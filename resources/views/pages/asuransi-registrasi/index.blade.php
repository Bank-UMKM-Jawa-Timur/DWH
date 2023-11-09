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
    @include('pages.asuransi-registrasi.modal.pembatalanKlaim')
    <!-- Modal-Pelunasan -->
    @include('pages.asuransi-registrasi.modal.pelunasan')
    <!-- Modal-Send -->
    @include('pages.asuransi-registrasi.modal.send')
    <!-- Modal-Tidak Registrasi -->
    @include('pages.asuransi-registrasi.modal.tidak-registrasi')
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
                        <th>Nama Debitur</th>
                        @if ($role_id != 2)
                            <th>Cabang</th>
                        @endif
                        <th>Tanggal Pengajuan</th>
                        <th>Nomor PK</th>
                        <th>Jenis Kredit</th>
                        <th>Plafond</th>
                        <th>Jumlah Asuransi</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                    <tbody>
                        {{-- @php
                            dd($data);
                        @endphp --}}
                        @forelse ($data as $item)
                            @php
                                $totalData = count($item['jenis_asuransi']);
                                $totalDataTerproses = 0;
                                foreach ($item['jenis_asuransi'] as $key => $value) {
                                    if ($value->asuransi) {
                                        if ($value->asuransi->registered == 1 || $value->asuransi->registered == 0){
                                            $totalDataTerproses++;
                                        }
                                    }
                                }
                            @endphp
                            <tr class="view cursor-pointer">
                                <td>{{$loop->iteration}}</td>
                                <td>{{$item['nama']}}</td>
                                @if ($role_id != 2)
                                    <td>{{$item['cabang']}}</td>
                                @endif
                                <td>{{date('d-m-Y', strtotime($item['tanggal']))}}</td>
                                <td>{{$item['no_pk']}}</td>
                                <td>{{$item['skema_kredit']}}</td>
                                <td>Rp {{number_format($item['jumlah_kredit'], 0, ',', '.')}}</td>
                                <td>{{ $totalDataTerproses . '/' . $totalData }}</td>
                                <td>
                                    @if ($totalDataTerproses == 0)
                                        {{ 'Open' }}
                                    @elseif ($totalDataTerproses != 0 && $totalDataTerproses < $totalData)
                                        {{ 'Process' }}
                                    @elseif ($totalDataTerproses == $totalData)
                                        {{ 'Close' }}
                                    @endif
                                </td>
                                <td>
                                    <div class="flex gap-4 justify-center">
                                        @if(count($item['jenis_asuransi']) > 0)
                                            <button class="flex gap-2 hover:bg-slate-50 border px-3 py-2">
                                                <span class="caret-icon transform">
                                                    @include('components.svg.caret')
                                                </span>
                                                <span id="text_collapse" class="collapse-text">Sembunyikan Asuransi</span>
                                            </button>
                                        @else
                                            <span class="caret-icon transform"></span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            <tr class="collapse-table">
                                <td class="bg-theme-primary/5"></td>
                                <td colspan="8" class="p-0">
                                    <div class="bg-theme-primary/5">
                                        <div>
                                            <table class="table-collapse">
                                                <thead>
                                                    <th>#</th>
                                                    <th>Perusahaan</th>
                                                    <th>Jenis</th>
                                                    <th>Nomor Aplikasi</th>
                                                    <th>Nomor Polis</th>
                                                    <th>Tanggal Polis</th>
                                                    <th>Tanggal Rekam</th>
                                                    <th>Status Asuransi</th>
                                                    <th>Aksi</th>
                                                </thead>
                                                <tbody>
                                                    @foreach ($item['jenis_asuransi'] as $jenis)
                                                        @php
                                                            $alpha = strtolower(chr(64+$loop->iteration));
                                                            $id_pengajuan = $item['id'];
                                                            $no_pk = $item['no_pk'];
                                                            $perusahaan = $jenis->asuransi ? $jenis->asuransi->perusahaan : '-';
                                                            $no_aplikasi = $jenis->asuransi ? $jenis->asuransi->no_aplikasi : '-';
                                                            $no_polis = $jenis->asuransi ? $jenis->asuransi->no_polis : '-';
                                                            $tgl_polis = $jenis->asuransi ? date('d-m-Y', strtotime($jenis->asuransi->tgl_polis)) : '-';
                                                            $tgl_rekam = $jenis->asuransi ? date('d-m-Y', strtotime($jenis->asuransi->tgl_rekam)) : '-';
                                                            $status = $jenis->asuransi ? $jenis->asuransi->status : '-';
                                                            $is_paid = $jenis->asuransi ? $jenis->asuransi->is_paid : '-';
                                                            $registered = $jenis->asuransi ? $jenis->asuransi->registered : null;
                                                        @endphp
                                                        <tr>
                                                            <td>{{$alpha}}</td>
                                                            <td>
                                                                @if ($registered == 1)
                                                                    {{$perusahaan}}
                                                                @else
                                                                    -
                                                                @endif
                                                            </td>
                                                            <td>{{$jenis->jenis}}</td>
                                                            <td>
                                                                @if ($registered == 1)
                                                                    {{$no_aplikasi}}
                                                                @else
                                                                    -
                                                                @endif
                                                            </td>
                                                            <td>{{$is_paid ? $no_polis : '-'}}</td>
                                                            <td>{{$is_paid ? $tgl_polis : '-'}}</td>
                                                            <td>
                                                                {{-- @if ($registered == 1)
                                                                    {{$tgl_rekam}}
                                                                @else
                                                                    -
                                                                @endif --}}
                                                                {{ $is_paid == true ? $tgl_rekam : '-' }}
                                                            </td>
                                                            <td>
                                                                @if ($jenis->asuransi)
                                                                    @if ($registered == 1)
                                                                        @if ($is_paid == 1)
                                                                            Sudah dibayar
                                                                        @else
                                                                            @if ($status == 'waiting approval')
                                                                                Menunggu persetujuan
                                                                            @elseif ($status == 'approved')
                                                                                Disetujui
                                                                            @elseif ($status == 'revition')
                                                                                Revisi data
                                                                            @elseif ($status == 'sended')
                                                                                Harap bayar premi
                                                                            @else
                                                                                Dibatalkan
                                                                            @endif
                                                                        @endif
                                                                    @else
                                                                        Tidak Registrasi
                                                                    @endif
                                                                @else
                                                                    -
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <div class="flex gap-5 justify-center">
                                                                    @if ($jenis->asuransi)
                                                                        @if ($registered == 1)
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
                                                                                        data-no_aplikasi="{{$jenis->asuransi->no_aplikasi}}" data-no_rek={{$jenis->asuransi->no_rek}} data-debitur="{{$item['nama']}}" data-tgl_pengajuan="{{$item['tanggal']}}" data-jenis_kredit="{{$jenis->jenis}}" data-premi="{{$jenis->asuransi->premi}}" data-tarif="{{$jenis->asuransi->tarif}}" data-fee="{{$jenis->asuransi->handling_fee}}" data-premi_disetor={{$jenis->asuransi->premi_disetor}}>
                                                                                        Registrasi
                                                                                    </button>
                                                                                @elseif(strtolower($status) == 'revition')
                                                                                    <a href="{{route('asuransi.registrasi.edit', $id_pengajuan)}}" class="px-4 py-2  bg-orange-400/20 rounded text-orange-500">
                                                                                        Edit
                                                                                    </a>
                                                                                @elseif(strtolower($status) == 'sended')
                                                                                    @if ($role == 'Staf Analisa Kredit')
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
                                                                            @elseif(strtolower($status) == 'revition')
                                                                                @if ($role == 'Staf Analis Kredit')
                                                                                    <a href="{{route('asuransi.registrasi.edit', $id_pengajuan)}}" class="px-4 py-2  bg-orange-400/20 rounded text-orange-500">
                                                                                        Edit
                                                                                    </a>
                                                                                @else
                                                                                    -
                                                                                @endif
                                                                            @elseif(strtolower($status) == 'sended')
                                                                                @if ($role == 'Staf Analis Kredit')
                                                                                    @if ($is_paid == 1)
                                                                                        @if ($jenis->pengajuan_klaim != null)
                                                                                            @if ($jenis->pengajuan_klaim->status == 'waiting approval')
                                                                                                -
                                                                                            @elseif ($jenis->pengajuan_klaim->status == 'approved')
                                                                                                <form action="{{ route('asuransi.pengajuan-klaim.hit-endpoint', $jenis->pengajuan_klaim->id) }}" method="post">
                                                                                                    @csrf
                                                                                                    <a href="#">
                                                                                                        <button type="submit" class="px-4 py-2 bg-theme-btn/10 rounded text-theme-btn" id="btnKirim">
                                                                                                            Kirim
                                                                                                        </button>
                                                                                                    </a>
                                                                                                </form>
                                                                                            @elseif ($jenis->pengajuan_klaim->status == 'revition')
                                                                                                <a href="{{ route('asuransi.pengajuan-klaim.edit', $jenis->pengajuan_klaim->id) }}">
                                                                                                    <button type="button" class="px-4 py-2 bg-theme-btn/10 rounded text-theme-btn">
                                                                                                        Edit
                                                                                                    </button>
                                                                                                </a>
                                                                                            @elseif ($jenis->pengajuan_klaim->status == 'sended')
                                                                                                <div class="dropdown">
                                                                                                    <button class="px-4 py-2 bg-theme-btn/10 rounded text-theme-btn">
                                                                                                        Selengkapnya
                                                                                                    </button>
                                                                                                    <ul class="dropdown-menu">
                                                                                                        <li class="">
                                                                                                            <button type="button" id="btnCekStatus" class="item-dropdown">Cek Data Pengajuan Klaim</button>
                                                                                                        </li>
                                                                                                        <li class="item-dropdown">
                                                                                                            <a class="item-dropdown modal-batal" href="#"
                                                                                                                data-modal-toggle="modalBatalKlaim" data-modal-target="modalBatalKlaim"
                                                                                                                data-id="{{ $jenis->pengajuan_klaim->id }}" data-no_aplikasi="{{ $jenis->asuransi->no_aplikasi }}"
                                                                                                                data-no_polis="{{ $jenis->asuransi->no_polis }}" data-no_rekening= {{$jenis->asuransi->no_rek}}>Pembatalan
                                                                                                            </a>
                                                                                                            {{-- <form action="{{ route('asuransi.pengajuan-klaim.pembatalan-klaim') }}" method="post" enctype="multipart/form-data">
                                                                                                                @csrf
                                                                                                                <input type="hidden" name="id" value="{{ $jenis->pengajuan_klaim->id }}">
                                                                                                                <input type="hidden" name="no_aplikasi" value="{{ $jenis->asuransi->no_aplikasi }}">
                                                                                                                <input type="hidden" name="no_rekening" value="{{ $jenis->asuransi->no_rek }}">
                                                                                                                <input type="hidden" name="no_polis" value="{{ $jenis->asuransi->no_polis }}">
                                                                                                                <button type="submit" id="btnBatal">Pembatalan</button>
                                                                                                            </form> --}}
                                                                                                        </li>
                                                                                                    </ul>
                                                                                                </div>
                                                                                            @endif
                                                                                        @else
                                                                                            <div class="dropdown">
                                                                                                <button class="px-4 py-2 bg-theme-btn/10 rounded text-theme-btn">
                                                                                                    Selengkapnya
                                                                                                </button>
                                                                                                <ul class="dropdown-menu right-16">
                                                                                                    @if (!$jenis->asuransi->is_paid)
                                                                                                        <li class="">
                                                                                                            <a class="item-dropdown modal-batal" href="#"
                                                                                                                data-modal-toggle="modalBatal" data-modal-target="modalBatal"
                                                                                                                data-id="{{$jenis->asuransi->id}}" data-no_aplikasi="{{$jenis->asuransi->no_aplikasi}}"
                                                                                                                data-no_polis="{{$jenis->asuransi->no_polis}}">Pembatalan</a>
                                                                                                        </li>
                                                                                                    @endif
                                                                                                    <li class="item-dropdown">
                                                                                                        <form class="form-inquery" action="{{route('asuransi.registrasi.inquery')}}" method="get">
                                                                                                            <input type="hidden" name="no_aplikasi" value="{{$jenis->asuransi->no_aplikasi}}">
                                                                                                            <input type="hidden" name="id_asuransi" value="{{$id_pengajuan}}">
                                                                                                            <button type="submit">Cek(Inquery)</button>
                                                                                                        </form>
                                                                                                    </li>
                                                                                                    @if ($jenis->asuransi->is_paid && !$jenis->asuransi->canceled_at)
                                                                                                        <li class="">
                                                                                                            <a class="item-dropdown modal-pelunasan" href="#" data-modal-toggle="modalPelunasan"
                                                                                                                data-modal-target="modalPelunasan"  data-id="{{$jenis->asuransi->id}}"
                                                                                                                data-no_aplikasi="{{$jenis->asuransi->no_aplikasi}}" data-no_rek="{{$jenis->asuransi->no_rek}}"
                                                                                                                data-no_polis="{{$jenis->asuransi->no_polis}}" data-refund="{{$jenis->asuransi->refund}}"
                                                                                                                data-tgl_awal="{{$jenis->asuransi->tanggal_awal}}" data-tgl_akhir="{{$jenis->asuransi->tanggal_akhir}}">Pelunasan</a>
                                                                                                        </li>
                                                                                                        <li>
                                                                                                            <a href="{{ route('asuransi.pengajuan-klaim.add', $jenis->asuransi->id) }}" class="item-dropdown">
                                                                                                                Pengajuan klaim
                                                                                                            </a>
                                                                                                        </li>
                                                                                                    @endif
                                                                                                </ul>
                                                                                            </div>
                                                                                        @endif
                                                                                    @else
                                                                                        <div class="dropdown">
                                                                                            <button class="px-4 py-2 bg-theme-btn/10 rounded text-theme-btn">
                                                                                                Selengkapnya
                                                                                            </button>
                                                                                            <ul class="dropdown-menu right-16">
                                                                                                <li>
                                                                                                    <form action="{{route('asuransi.registrasi.inquery')}}" method="get">
                                                                                                        <input type="hidden" name="no_aplikasi" value="{{$jenis->asuransi->no_aplikasi}}">
                                                                                                        <input type="hidden" name="id_asuransi" value="{{$id_pengajuan}}">
                                                                                                        <button class="item-dropdown w-full" type="submit">Cek(Inquery)</button>
                                                                                                    </form>

                                                                                                </li>
                                                                                                <li>
                                                                                                    <a class="item-dropdown modal-batal" href="#"
                                                                                                        data-modal-toggle="modalBatal" data-modal-target="modalBatal"
                                                                                                        data-id="{{$jenis->id}}" data-no_aplikasi="{{ $jenis->asuransi->no_aplikasi }}"
                                                                                                        data-no_polis="{{ $jenis->asuransi->no_polis }}">Pembatalan
                                                                                                    </a>
                                                                                                    {{-- <form action="{{ route('asuransi.registrasi.batal') }}" method="post" enctype="multipart/form-data">
                                                                                                        @csrf
                                                                                                        <input type="hidden" name="id" value="{{$jenis->id}}">
                                                                                                        <input type="hidden" name="no_aplikasi" value="{{ $jenis->asuransi->no_aplikasi }}">
                                                                                                        <input type="hidden" name="no_polis" value="{{ $jenis->asuransi->no_polis }}">
                                                                                                        <button class="item-dropdown w-full" type="submit">Pembatalan</button>
                                                                                                    </form> --}}
                                                                                                </li>
                                                                                            </ul>
                                                                                        </div>
                                                                                    @endif
                                                                                @elseif($role == 'Penyelia Kredit')
                                                                                    @if ($is_paid)
                                                                                        @if ($jenis->pengajuan_klaim != null)
                                                                                            @if ($jenis->pengajuan_klaim->status == 'waiting approval')
                                                                                                <a href="{{ route('asuransi.pengajuan-klaim.review-penyelia', $jenis->pengajuan_klaim->id) }}">
                                                                                                    <button type="button" class="px-4 py-2 bg-theme-btn/10 rounded text-theme-btn">
                                                                                                        Review
                                                                                                    </button>
                                                                                                </a>
                                                                                            @else
                                                                                                -
                                                                                            @endif
                                                                                        @endif
                                                                                    @endif
                                                                                @else
                                                                                    <button class="px-4 py-2 bg-theme-btn/10 rounded text-theme-btn">
                                                                                        Detail
                                                                                    </button>
                                                                                @endif
                                                                            @else
                                                                                -
                                                                            @endif
                                                                        @elseif ($registered == 0)
                                                                        -
                                                                        @else
                                                                            <a href="{{route('asuransi.registrasi.create')}}?id={{$id_pengajuan}}&jenis_asuransi={{$jenis->id}}">
                                                                                <button class="px-4 py-2 bg-blue-500/20 rounded text-blue-500">
                                                                                    Registrasi
                                                                                </button>
                                                                            </a>
                                                                            <button class="px-4 py-2 bg-theme-primary/20 rounded text-theme-primary modal-tidak-register" data-modal-toggle="modalTidakRegister" data-modal-target="modalTidakRegister" data-id="{{$item['id']}}"
                                                                                data-no_pk="{{$item['no_pk']}}" data-debitur="{{$item['nama']}}"
                                                                                data-jenis_asuransi_id="{{$jenis->id}}" data-jenis_asuransi="{{$jenis->jenis}}">
                                                                                Tidak Registrasi
                                                                            </button>
                                                                        @endif
                                                                    @else
                                                                        @if ($role == 'Staf Analis Kredit')
                                                                            @if ($jenis->asuransi)
                                                                                @if ($registered == null)
                                                                                    <a href="{{route('asuransi.registrasi.create')}}?id={{$id_pengajuan}}&jenis_asuransi={{$jenis->id}}">
                                                                                        <button class="px-4 py-2 bg-blue-500/20 rounded text-blue-500">
                                                                                            Registrasi
                                                                                        </button>
                                                                                    </a>
                                                                                    <button class="px-4 py-2 bg-theme-primary/20 rounded text-theme-primary modal-tidak-register" data-modal-toggle="modalTidakRegister" data-modal-target="modalTidakRegister" data-id="{{$item['id']}}"
                                                                                        data-no_pk="{{$item['no_pk']}}" data-debitur="{{$item['nama']}}"
                                                                                        data-jenis_asuransi_id="{{$jenis->id}}" data-jenis_asuransi="{{$jenis->jenis}}">
                                                                                        Tidak Registrasi
                                                                                    </button>
                                                                                @else
                                                                                    {{$registered ? '-' : 'Tidak registrasi'}}
                                                                                @endif
                                                                            @else
                                                                            <a href="{{route('asuransi.registrasi.create')}}?id={{$id_pengajuan}}&jenis_asuransi={{$jenis->id}}">
                                                                                <button class="px-4 py-2 bg-blue-500/20 rounded text-blue-500">
                                                                                    Registrasi
                                                                                </button>
                                                                            </a>
                                                                            <button class="px-4 py-2 bg-theme-primary/20 rounded text-theme-primary modal-tidak-register" data-modal-toggle="modalTidakRegister" data-modal-target="modalTidakRegister" data-id="{{$item['id']}}"
                                                                                data-no_pk="{{$item['no_pk']}}" data-debitur="{{$item['nama']}}"
                                                                                data-jenis_asuransi_id="{{$jenis->id}}" data-jenis_asuransi="{{$jenis->jenis}}">
                                                                                Tidak Registrasi
                                                                            </button>
                                                                            @endif
                                                                        @else
                                                                            -
                                                                        @endif
                                                                    @endif
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">Data tidak tersedia.</td>
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
        $("table .view").on("click", function(e) {
            const $collapseTable = $(this).next(".collapse-table");
            $collapseTable.toggleClass("hidden");

            const $textCollapse = $(this).find('#text_collapse');

            if ($collapseTable.hasClass('hidden')) {
                $textCollapse.text("Tampilkan Asuransi");
            } else {
                $textCollapse.text("Sembunyikan Asuransi");
            }
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

        $('.form-inquery').on('submit', function() {
            $('#preload-data').removeClass('hidden')
        })

        $(".table-collapse").on("click", "#btnCekStatus", function(){
        var noAplikasi = $(this).parents('tr').find("[name=row_no_aplikasi]").val();
        $.ajax({
            type: "POST",
            url: "{{ route('asuransi.pengajuan-klaim.cek-status') }}",
            data: {
                _token: "{{ csrf_token() }}",
                no_aplikasi: noAplikasi
            },
            success: function(res){
                if(res.status == "Berhasil"){
                    Swal.fire({
                        // icon: 'success',
                        // title: 'Berhasil',
                        html: `
                            <table style="text-align: left !important;" class="w-full">
                                <tr>
                                    <td><strong>No. Rekening</strong></td>
                                    <td>${res.response.no_rekening}</td>
                                </tr>
                                <tr>
                                    <td><strong>No. Aplikasi</strong></td>
                                    <td>${res.response.no_aplikasi}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status Klaim</strong></td>
                                    <td>${statKlaim[parseInt(res.response.stat_klaim) + 1]}</td>
                                </tr>
                                <tr>
                                    <td><strong>Keterangan</strong></td>
                                    <td>${res.response.keterangan}</td>
                                </tr>
                                <tr>
                                    <td><strong>Nilai Persetujuan</strong></td>
                                    <td>${res.response.nilai_persetujuan}</td>
                                </tr>
                                <tr>
                                    <td><strong>Tanggal klaim</strong></td>
                                    <td>${res.response.tgl_klaim}</td>
                                </tr>
                                <tr>
                                    <td><strong>No. Polis</strong></td>
                                    <td>${res.response.no_sp}</td>
                                </tr>
                            </table>
                        `
                    })
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: res.message,
                    })
                }
            }
        })
    })

    $(".table-collapse").on("click", "#btnBatal", function(){
        var parent = $(this).parent();
        var id = parent.find("[name=id]").val()
        var no_aplikasi = parent.find("[name=no_aplikasi]").val()
        var no_rekening = parent.find("[name=no_rekening]").val()
        var no_polis = parent.find("[name=no_polis]").val()

        $.ajax({
            type: "POST",
            url: "{{ route('asuransi.pengajuan-klaim.pembatalan-klaim') }}",
            data: {
                _token: "{{ csrf_token() }}",
                id: id,
                no_aplikasi: no_aplikasi,
                no_rekening: no_rekening,
                no_polis: no_polis
            },
            success: function(res){
                Swal.fire({
                    icon: (res.status == "Berhasil") ? "success" : "error",
                    title: res.status,
                    text: res.message,
                })
            },
            error: function(res){
                Swal.fire({
                    icon: "error",
                    title: "Terjadi kesalahan",
                    text: res.message
                });
            }
        })
    })
    </script>
@endpush
