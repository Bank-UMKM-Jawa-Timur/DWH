@extends('layout.master')

@section('title', $title)

@section('content')
    <div class="panel-header">
        <div class="page-inner py-5">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                <div>
                    <h2 class="text-primary pb-2 fw-bold">{{ $pageTitle }} {{ $role }}</h2>
                    {{-- <h5 class="text-primary op-7">6819456 | Cabang Bondowoso</h5> --}}
                </div>
            </div>
        </div>
    </div>
    <div class="page-inner mt--5">
        {{-- <div class="card welcome">
            <div class="card-body ">
                <div class="d-flex justify-content-start align-items-end">
                    <img src="{{ asset('template') }}/assets/img/flat_welcome.png" class="img-fluid img-welcome">
                    <div class="box">
                        <h2 class="title-welcome">Hai,Selamat Datang SuperAdmin</h2>
                        <p class="lead-welcome">Senang Betermu dengan SuperAdmin,Selamat Datang di website
                            data
                            warehouse
                            kamu memiliki 3 notifikasi</p>
                    </div>
                </div>
            </div>
        </div> --}}
        {{-- <div class="row">
            @if (strtolower($role) != 'vendor')
                <div class="col-sm">
                    <div class="card card-stats card-round">
                        <div class="card-body ">
                            <div class="row align-items-center">
                                <div class="col-icon">
                                    <div class="icon-big text-center icon-primary bubble-shadow-small">
                                        <i class="icon-people"></i>
                                    </div>
                                </div>
                                <div class="col col-stats ml-3 ml-sm-0">
                                    <div class="numbers">
                                        <p class="card-category">{{ $total_pengguna }}</p>
                                        <h4 class="card-title">Pengguna</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <div class="col-sm">
                <div class="card card-stats card-round">
                    <div class="card-body ">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-primary bubble-shadow-small">
                                    <i class="icon-briefcase"></i>
                                </div>
                            </div>
                            <div class="col col-stats ml-3 ml-sm-0">
                                <div class="numbers">
                                    <p class="card-category">{{ $total_vendor }}</p>
                                    <h4 class="card-title">Vendor</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm">
                <div class="card card-stats card-round">
                    <div class="card-body ">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-primary bubble-shadow-small">
                                    <i class="icon-earphones-alt"></i>
                                </div>
                            </div>
                            <div class="col col-stats ml-3 ml-sm-0">
                                <div class="numbers">
                                    <p class="card-category">{{ $total_cabang }}</p>
                                    <h4 class="card-title">Cabang</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
        @if (strtolower($role) == 'cabang')
            {{-- <div class="col-sm-12"> --}}
            <div class="row">
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-header">
                            Chart Data Realisasi
                        </div>
                        <div class="card-body">
                            <span>Target : {{ $target }}</span>
                            <canvas id="chartCabang" height="100"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-header">
                            Notifikasi
                        </div>
                        <div class="card-body">
                            @forelse ($notification as $item)
                                <div class="notif-app">
                                    <span class="alert-notif text-success notif-status-{{ $item->id }}">
                                        @if ($item->read)
                                            Sudah Dibaca
                                        @else
                                            Belum Dibaca
                                        @endif
                                    </span>
                                    <h4>
                                        {{ $item->title }} -
                                        {{ strlen($item->content) >= 100 ? substr($item->content, 0, 100) . '...' : $item->content }}
                                    </h4>
                                    <p class="lead-notif">{{ date('Y-m-d H:i', strtotime($item->created_at)) }}</p>
                                </div>
                            @empty
                                <span>Belum ada notifikasi.</span>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
            {{-- </div> --}}
        @endif
        @if (strtolower($role) == 'pemasaran')
            <div class="card">
                <div class="card-header">
                    Chart Target Pencapaian
                </div>
                <div class="card-body">
                    <canvas id="chart" height="100"></canvas>
                </div>
            </div>
        @endif
        <div class="row mt--2">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        Data KKB
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table mt-3" id="basic-datatables">
                                <thead>
                                    <tr class="bg-danger text-light">
                                        <th scope="col">No</th>
                                        <th scope="col">Nama</th>
                                        <th scope="col">PO</th>
                                        <th scope="col">Ketersediaan Unit</th>
                                        <th scope="col">Bukti Pembayaran</th>
                                        <th scope="col">Penyerahan Unit</th>
                                        {{--  <th scope="col">STNK</th>
                                        <th scope="col">Polis</th>
                                        <th scope="col">BPKB</th>  --}}
                                        @foreach ($documentCategories as $item)
                                            <th scope="col">{{ $item->name }}</th>
                                        @endforeach
                                        <th scope="col">Bukti Pembayaran Imbal Jasa</th>
                                        <th scope="col">Imbal Jasa</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($data as $item)
                                        @php
                                            $buktiPembayaran = \App\Models\Document::where('kredit_id', $item->id)
                                                ->where('document_category_id', 1)
                                                ->first();
                                            $penyerahanUnit = \App\Models\Document::where('kredit_id', $item->id)
                                                ->where('document_category_id', 2)
                                                ->first();
                                            $stnk = \App\Models\Document::where('kredit_id', $item->id)
                                                ->where('document_category_id', 3)
                                                ->first();
                                            $polis = \App\Models\Document::where('kredit_id', $item->id)
                                                ->where('document_category_id', 4)
                                                ->first();
                                            $bpkb = \App\Models\Document::where('kredit_id', $item->id)
                                                ->where('document_category_id', 5)
                                                ->first();
                                            $imbalJasa = \App\Models\Document::where('kredit_id', $item->id)
                                                ->where('document_category_id', 6)
                                                ->first();
                                            $setImbalJasa = DB::table('tenor_imbal_jasas')->find($item->id_tenor_imbal_jasa);
                                        @endphp
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                @if ($item->detail)
                                                    {{$item->detail['nama']}}
                                                @endif
                                            </td>
                                            <td class="@if ($item->detail) link-po @endif">
                                                @if ($buktiPembayaran)
                                                    @if ($item->detail)
                                                        <a class="open-po" data-toggle="modal" data-target="#detailPO"
                                                            data-nomorPo="{{ $item->detail['no_po'] }}"
                                                            data-tanggalPo="{{ $item->detail['tanggal'] }}"
                                                            data-filepo="{{ config('global.los_host') . $item->detail['po'] }}">
                                                            {{ $item->detail['nama'] }}</a>
                                                    @else
                                                        -
                                                    @endif
                                                @else
                                                    @if ($item->detail)
                                                        <a class="open-po" data-toggle="modal" data-target="#detailPO"
                                                            data-nomorPo="{{ $item->detail['no_po'] }}"
                                                            data-tanggalPo="20 April 2023"
                                                            data-filepo="{{ config('global.los_host') . $item->detail['po'] }}">
                                                            {{ $item->detail['no_po'] }}</a>
                                                    @else
                                                        -
                                                    @endif
                                                @endif
                                            </td>
                                            <td>
                                                @if (Auth::user()->vendor_id)
                                                    @if (!$item->tgl_ketersediaan_unit)
                                                        <a style="text-decoration: underline;" data-toggle="modal"
                                                            data-target="#tglModal" data-id_kkb="{{ $item->kkb_id }}"
                                                            href="#">Atur</a>
                                                    @else
                                                        {{ $item->tgl_ketersediaan_unit }}
                                                    @endif
                                                @elseif ($item->tgl_ketersediaan_unit)
                                                    {{ $item->tgl_ketersediaan_unit }}
                                                @else
                                                    <span class="text-danger">Menunggu tanggal ketersediaan unit</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($item->tgl_ketersediaan_unit)
                                                    @if (Auth::user()->role_id == 3)
                                                        {{--  vendor  --}}
                                                        @if ($buktiPembayaran)
                                                            @if (!$buktiPembayaran->is_confirm)
                                                            <a style="cursor: pointer; text-decoration: underline;" class="confirm-bukti-pembayaran"
                                                                data-toggle="modal" data-id-category="1"
                                                                data-id-doc="{{ $buktiPembayaran ? $buktiPembayaran->id : 0 }}"
                                                                data-file="@isset($buktiPembayaran->file){{ $buktiPembayaran->file }}@endisset"
                                                                href="#confirmModalVendor">Konfirmasi</a>
                                                            @elseif ($buktiPembayaran->is_confirm)
                                                            <a class="m-0 bukti-pembayaran-modal" style="cursor: pointer; text-decoration: underline;" data-toggle="modal" data-target="#previewBuktiPembayaranModal"
                                                            data-file="{{ $buktiPembayaran->file }}" data-confirm="{{$buktiPembayaran->is_confirm}}"
                                                            data-tanggal="{{$buktiPembayaran->date}}">Selesai</a>
                                                            @else
                                                            Menunggu Pembayaran dari Cabang
                                                            @endif
                                                        @else
                                                            Menunggu Pembayaran dari Cabang
                                                        @endif
                                                    @else
                                                        {{--  role selain vendor  --}}
                                                        @if (!$buktiPembayaran)
                                                            <a class="" data-toggle="modal"
                                                                data-target="#buktiPembayaranModal"
                                                                data-id_kkb="{{ $item->id }}" href="#"
                                                                onclick="uploadBuktiPembayaran({{ $item->id }})">Bayar</a>
                                                        @else
                                                            @if (!$buktiPembayaran->is_confirm)
                                                            <a class="m-0 bukti-pembayaran-modal" style="cursor: pointer; text-decoration: underline;" data-toggle="modal" data-target="#previewBuktiPembayaranModal"
                                                            data-file="{{ $buktiPembayaran->file }}" data-confirm="{{$buktiPembayaran->is_confirm}}">Menunggu Konfirmasi Vendor</a>
                                                            @elseif ($buktiPembayaran->is_confirm)
                                                            <a class="m-0 bukti-pembayaran-modal" style="cursor: pointer; text-decoration: underline;" data-toggle="modal" data-target="#previewBuktiPembayaranModal"
                                                            data-file="{{ $buktiPembayaran->file }}" data-confirm="{{$buktiPembayaran->is_confirm}}"
                                                            data-tanggal="{{$buktiPembayaran->date}}">Selesai</a>
                                                            @endif
                                                        @endif
                                                    @endif
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                @if ($item->tgl_ketersediaan_unit)
                                                    @if ($penyerahanUnit)
                                                        {{ $penyerahanUnit->date }}
                                                    @else
                                                        <span class="text-info">Maksimal tanggal penyerahan unit
                                                            {{ date('Y-m-d', strtotime($item->tgl_ketersediaan_unit . ' +1 days')) }}</span>
                                                    @endif
                                                @else
                                                    <span class="text-danger">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($penyerahanUnit)
                                                    @if ($stnk)
                                                        @if ($stnk->file && $stnk->is_confirm)
                                                            <a href="/storage/dokumentasi-stnk/{{ $stnk->file }}"
                                                                target="_blank">{{ $stnk->date }}</a>
                                                        @else
                                                            <span class="text-warning">Menunggu konfirmasi</span>
                                                        @endif
                                                    @else
                                                        @if (Auth::user()->role_id == 3)
                                                            @if ($penyerahanUnit->is_confirm)
                                                                <span class="text-info">Maksimal tanggal upload STNK
                                                                    {{ date('Y-m-d', strtotime($penyerahanUnit->date . ' +1 month')) }}</span>
                                                            @else
                                                            <span class="text-warning">Menunggu konfirmasi penyerahan unit</span>
                                                            @endif
                                                        @else
                                                            <span class="text-warning">Menunggu penyerahan STNK</span>
                                                        @endif
                                                    @endif
                                                @else
                                                    <span class="text-warning">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($penyerahanUnit)
                                                    @if ($polis)
                                                        @if ($polis->file && $polis->is_confirm)
                                                            <a href="/storage/dokumentasi-polis/{{ $polis->file }}"
                                                                target="_blank">{{ $polis->date }}</a>
                                                        @else
                                                            @if (Auth::user()->role_id == 3)
                                                                <span class="text-warning">Menunggu konfirmasi</span>
                                                            @else
                                                                <span class="text-warning">Menunggu penyerahan polis</span>
                                                            @endif
                                                        @endif
                                                    @else
                                                        @if (Auth::user()->role_id == 3)
                                                            @if ($penyerahanUnit->is_confirm)
                                                                <span class="text-info">Maksimal tanggal upload Polis
                                                                {{ date('Y-m-d', strtotime($penyerahanUnit->date . ' +1 month')) }}</span>
                                                            @else
                                                                <span class="text-warning">Menunggu konfirmasi penyerahan unit</span>
                                                            @endif
                                                        @else
                                                            <span class="text-warning">Menunggu Penyerahan Polis</span>
                                                        @endif
                                                    @endif
                                                @else
                                                    <span class="text-warning">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($penyerahanUnit)
                                                    @if ($bpkb)
                                                        @if ($polis->file && $polis->is_confirm)
                                                            <a href="/storage/dokumentasi-bpkb/{{ $bpkb->file }}"
                                                                target="_blank">{{ $bpkb->date }}</a>
                                                        @else
                                                            <span class="text-warning">Menunggu konfirmasi</span>
                                                        @endif
                                                    @else
                                                        @if (Auth::user()->role_id == 3)
                                                            @if ($penyerahanUnit->is_confirm)
                                                            <span class="text-info">Maksimal tanggal upload BPKB
                                                                {{ date('Y-m-d', strtotime($penyerahanUnit->date . ' +1 month')) }}</span>
                                                            @else
                                                                <span class="text-warning">Menunggu konfirmasi penyerahan unit</span>
                                                            @endif
                                                        @else
                                                            <span class="text-warning">Menunggu Penyerahan BPKB</span>
                                                        @endif
                                                    @endif
                                                @else
                                                    <span class="text-warning">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if (Auth::user()->role_id == 3)
                                                    {{--  vendor  --}}
                                                    @if ($imbalJasa)
                                                        @if (!$imbalJasa->is_confirm)
                                                        <a style="cursor: pointer; text-decoration: underline;" class="confirm-bukti-pembayaran"
                                                            data-toggle="modal" data-id-category="1"
                                                            data-id-doc="{{ $imbalJasa ? $imbalJasa->id : 0 }}"
                                                            data-file="@isset($imbalJasa->file){{ $imbalJasa->file }}@endisset"
                                                            href="#confirmModalVendor">Konfirmasi Bukti Pembayaran</a>
                                                        @elseif ($imbalJasa->is_confirm)
                                                        <p class="m-0">Selesai</p>
                                                        <a class="bukti-pembayaran-modal" style="cursor: pointer; text-decoration: underline;" data-toggle="modal" data-target="#previewBuktiPembayaranModal"
                                                        data-file="{{ $imbalJasa->file }}">Lihat Bukti Pembayaran</a>
                                                        @else
                                                        Menunggu Pembayaran dari Cabang
                                                        @endif
                                                    @else
                                                    Menunggu Pembayaran dari Cabang
                                                    @endif
                                                @else
                                                    {{--  role selain vendor  --}}
                                                    @if ($stnk && $polis && $bpkb)
                                                        @if (!$imbalJasa)
                                                        <a href="#" class="dropdown-item upload-imbal-jasa"
                                                            data-toggle="modal"
                                                            data-target="#uploadImbalJasaModal"
                                                            data-id="{{ $item->id }}">Bayar</a>
                                                        @else
                                                        @if (!$imbalJasa->is_confirm)
                                                        <p class="m-0">Menunggu Konfirmasi Vendor</p>
                                                        @elseif ($imbalJasa->is_confirm)
                                                        <p class="m-0">Selesai</p>
                                                        @endif
                                                        <a class="bukti-pembayaran-modal" style="text-decoration: underline;" data-toggle="modal" data-target="#previewBuktiPembayaranModal"
                                                        data-file="{{ $imbalJasa->file }}">Lihat Bukti Pembayaran</a>
                                                        @endif
                                                    @else
                                                        -
                                                    @endif
                                                @endif
                                            </td>
                                            <td>
                                                @if ($penyerahanUnit)
                                                    @if ($imbalJasa)
                                                        @if ($imbalJasa->file && $imbalJasa->is_confirm)
                                                            <a href="/storage/dokumentasi-imbal-jasa/{{ $imbalJasa->file }}"
                                                                target="_blank">Rp.
                                                                {{ number_format($setImbalJasa->imbaljasa, 0, '', '.') }}</a>
                                                        @else
                                                            @if (Auth::user()->role_id == 3)
                                                                <span class="text-info">Silahkan konfirmasi bukti transfer
                                                                    imbal
                                                                    jasa</span>
                                                            @else
                                                                <span class="text-info">Menunggu bukti transfer imbal
                                                                    jasa</span>
                                                            @endif
                                                        @endif
                                                    @else
                                                        @if ($stnk && $polis && $bpkb)
                                                            @if (Auth::user()->role_id == 2)
                                                                <span class="text-info">Silahkan upload bukti transfer imbal
                                                                    jasa</span>
                                                            @else
                                                                <span class="text-info">Menunggu bukti transfer imbal
                                                                    jasa</span>
                                                            @endif
                                                        @else
                                                            <span class="text-warning">Menunggu penyerahan semua
                                                                berkas</span>
                                                        @endif
                                                    @endif
                                                @else
                                                    <span class="text-warning">-</span>
                                                @endif
                                            </td>
                                            <td
                                                class="@if ($item->status == 'done' && $setImbalJasa) text-success @else text-info @endif">
                                                @if ($setImbalJasa)
                                                    {{ $item->status }}
                                                @else
                                                    progress
                                                @endif
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-info dropdown-toggle" type="button"
                                                        data-toggle="dropdown" aria-expanded="false">
                                                        Selengkapnya
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        @if ($item->tgl_ketersediaan_unit)
                                                            @if ($buktiPembayaran)
                                                                @if (!$penyerahanUnit && $buktiPembayaran->is_confirm && Auth::user()->vendor_id)
                                                                    <a data-toggle="modal" data-target="#tglModalPenyerahan"
                                                                        data-id_kkb="{{ $item->kkb_id }}" href="#"
                                                                        class="dropdown-item"
                                                                        onclick="setPenyerahan({{ $item->kkb_id }})">Kirim
                                                                        Unit</a>
                                                                @endif
                                                            @endif
                                                        @endif
                                                        {{--  Upload Berkas  --}}
                                                        @if (Auth::user()->role_id == 3 && $penyerahanUnit)
                                                            @if ($penyerahanUnit->is_confirm)
                                                                @if (!isset($stnk->file) || !isset($polis->file) || !isset($bpkb->is_confirm))
                                                                    {{--  Vendor  --}}
                                                                    <a data-toggle="modal" data-target="#uploadBerkasModal"
                                                                        data-id_kkb="{{ $item->kkb_id }}"
                                                                        data-no-stnk="@isset($stnk->text){{ $stnk->text }}@endisset"
                                                                        data-file-stnk="@isset($stnk->file){{ $stnk->file }}@endisset"
                                                                        data-no-polis="@isset($polis->text){{ $polis->text }}@endisset"
                                                                        data-file-polis="@isset($polis->file){{ $polis->file }}@endisset"
                                                                        data-no-bpkb="@isset($bpkb->text){{ $bpkb->text }}@endisset"
                                                                        data-file-bpkb="@isset($bpkb->file){{ $bpkb->file }}@endisset"
                                                                        href="#"
                                                                        class="dropdown-item upload-berkas">
                                                                        Upload Berkas
                                                                    </a>
                                                                @endif
                                                            @endif
                                                        @endif
                                                        {{--  Konfirmasi penyerahan unit (Cabang)  --}}
                                                        @if (Auth::user()->role_id == 2 && $penyerahanUnit)
                                                            @if (!$penyerahanUnit->is_confirm)
                                                                @if (!isset($stnk->file) || !isset($polis->file) || !isset($bpkb->is_confirm))
                                                                    {{--  Vendor  --}}
                                                                    <a class="dropdown-item confirm-penyerahan-unit"
                                                                        data-toggle="modal" data-id-category="2"
                                                                        data-id-doc="{{ $penyerahanUnit ? $penyerahanUnit->id : 0 }}"
                                                                        data-file="@isset($penyerahanUnit->file){{ $penyerahanUnit->file }}@endisset"
                                                                        href="#confirmModalPenyerahanUnit">Konfirmasi
                                                                        Penyerahan Unit</a>
                                                                @endif
                                                            @endif
                                                        @endif
                                                        @if (Auth::user()->role_id == 2)
                                                            {{--  Cabang  --}}
                                                            @if ($stnk || $polis || $bpkb)
                                                                @if (isset($stnk->is_confirm) || isset($polis->is_confirm) || isset($bpkb->is_confirm))
                                                                    @if (!$stnk->is_confirm || !$polis->is_confirm || !$bpkb->is_confirm)
                                                                        <a data-toggle="modal"
                                                                            data-target="#uploadBerkasModal"
                                                                            data-id_kkb="{{ $item->kkb_id }}"
                                                                            data-id-stnk="@if ($stnk) {{ $stnk->id }}@else- @endif"
                                                                            data-id-polis="@if ($polis) {{ $polis->id }}@else- @endif"
                                                                            data-id-bpkb="@if ($bpkb) {{ $bpkb->id }}@else- @endif"
                                                                            data-no-stnk="@isset($stnk->text){{ $stnk->text }}@endisset"
                                                                            data-file-stnk="@isset($stnk->file){{ $stnk->file }}@endisset"
                                                                            data-no-polis="@isset($polis->text){{ $polis->text }}@endisset"
                                                                            data-file-polis="@isset($polis->file){{ $polis->file }}@endisset"
                                                                            data-no-bpkb="@isset($bpkb->text){{ $bpkb->text }}@endisset"
                                                                            data-file-bpkb="@isset($bpkb->file){{ $bpkb->file }}@endisset"
                                                                            href="#"
                                                                            class="dropdown-item upload-berkas">
                                                                            Konfirmasi Berkas
                                                                        </a>
                                                                    @endif
                                                                @endif
                                                            @endif
                                                        @endif
                                                        @if (Auth::user()->role_id == 2)
                                                            @if ($stnk && $polis && $bpkb && !$imbalJasa)
                                                                <a href="#" class="dropdown-item upload-imbal-jasa"
                                                                    data-toggle="modal"
                                                                    data-target="#uploadImbalJasaModal"
                                                                    data-id="{{ $item->id }}">Upload bukti imbal
                                                                    jasa</a>
                                                            @endif
                                                        @else
                                                            @if ($stnk && $polis && $bpkb)
                                                                @if ($imbalJasa && $imbalJasa->is_confirm == false)
                                                                    <a href="#"
                                                                        class="dropdown-item confirm-imbal-jasa"
                                                                        data-id="{{ $imbalJasa->id }}"
                                                                        data-file="{{ $imbalJasa->file }}"
                                                                        data-toggle="modal"
                                                                        data-target="#confirmModalImbalJasa">Konfirmasi
                                                                        bukti
                                                                        imbal
                                                                        jasa</a>
                                                                @endif
                                                            @endif
                                                        @endif
                                                        <a class="dropdown-item detail-link" data-toggle="modal"
                                                            data-target="#detailModal" data-id="{{ $item->id }}"
                                                            href="#">Detail</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <td colspan="{{ 8 + count($documentCategories) }}" class="text-center">
                                            <span class="text-danger">Maaf data belum tersedia.</span>
                                        </td>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="paginated">
                            {{ $data->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="detailPO" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row container">
                        <div class="col-sm-6">
                            <h5 class="title-po">Nomor PO</h5>
                            <b class="content-po" id="nomorPo">12345678</b>
                        </div>
                        <div class="col-sm-6">
                            <h5 class="title-po">Tanggal PO</h5>
                            <b class="content-po" id="tanggalPo">21 Maret 2023</b>
                        </div>
                        <div class="col-sm-12 mt-4">
                            <h5 class="title-po">File PO</h5>
                            <div class="form-inline mt-1">
                                <button type="button" class="btn btn-primary mr-1 btn-sm">Unduh File PO</button>
                                <button onclick="printPDF()" class="btn btn-info btn-sm" id="printfile">Print File
                                    PO</button>
                                <iframe id="filePo"
                                    src="C:\Users\iqbalronii\Downloads\REv 16 Jan_Jadwal Genap 2023.pdf" class="mt-2"
                                    width="100%" height="500"></iframe>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary">Konfirmasi</button>
                </div> --}}
            </div>
        </div>
    </div>


    <!-- Modal bukti pembayaran -->
    @include('pages.kredit.modal.bukti-pembayaran-modal')

    @push('extraScript')
        <!-- Chart JS -->
        <script src="{{ asset('template') }}/assets/js/plugin/chart.js/chart.min.js"></script>

        <script src="{{ asset('template') }}/assets/js/plugin/datatables/datatables.min.js"></script>
        <script>
            function printPDF() {
                const pdfURL = 'https://www.africau.edu/images/default/sample.pdf';
                const pdfWindow = window.open(pdfURL, '_blank');

                pdfWindow.onload = function() {
                    pdfWindow.print();
                };
            }

            $('#basic-datatables').DataTable({});
        </script>
        <script>
            var label = {{ Js::from($barChartLabel) }};
            var data = {{ Js::from($barChartData) }};
            console.log(data);
            $(document).ready(function() {
                const ctx = document.getElementById('chart').getContext('2d');

                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: label,
                        datasets: [{
                            label: 'Data Set',
                            data: data,
                            fill: false,
                            backgroundColor: [
                                'rgba(255, 99, 132)',
                                'rgba(255, 159, 64)',
                                'rgba(255, 205, 86)',
                                'rgba(75, 192, 192)',
                                'rgba(54, 162, 235)',
                                'rgba(153, 102, 255)',
                                'rgba(201, 203, 207)'
                            ],
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 0
                        }]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    min: 0,
                                }
                            }]
                        }
                    }
                });
            });

            $(document).on("click", ".link-po", function() {
                var nomorPo = $(this).data('nomorpo');
                var tanggalPo = $(this).data('tanggalpo');
                var filePo = $(this).data('filepo') + "#toolbar=0";

                $("#nomorPo").text(nomorPo);
                $("#tanggalPo").text(tanggalPo);
                $("#filePo").attr("src", filePo);
            });
        </script>
        @if (Auth::user()->role_id != 3)
            <script>
                $(document).ready(function() {
                    var doughnutChart = document.getElementById('chartCabang').getContext('2d');

                    const target = parseInt("{{ $target }}");
                    const done = parseInt("{{ $total_kkb_done }}");
                    const undone = target - done;

                    var myDoughnutChart = new Chart(doughnutChart, {
                        type: 'doughnut',
                        data: {
                            datasets: [{
                                data: [undone, done],
                                backgroundColor: ['#AFD3E2', '#19A7CE']
                            }],

                            labels: [
                                'Belum Terealisasi',
                                'Sudah Terealisasi',
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: true,
                            legend: {
                                position: 'right'
                            },
                            layout: {
                                padding: {
                                    // left: 20,
                                    // right: 20,
                                    // top: 20,
                                    // bottom: 20
                                }
                            }
                        }
                    });
                });
            </script>
        @endif
    @endpush
@endsection
