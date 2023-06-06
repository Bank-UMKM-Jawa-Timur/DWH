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
                                    <p class="lead-notif">{{ date('d-m-Y H:i', strtotime($item->created_at)) }}</p>
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
                        <div class="">
                            <table class="mt-3" id="basic-datatables">
                                <thead>
                                    <tr class="bg-danger text-light">
                                        <th class="px-2 text-center" scope="col">No</th>
                                        <th class="px-2 text-center" scope="col" width="150">Nama</th>
                                        <th class="px-2 text-center" scope="col">PO</th>
                                        <th class="px-2 text-center" scope="col">Ketersediaan Unit</th>
                                        <th class="px-2 text-center" scope="col">Bukti Pembayaran</th>
                                        <th class="px-2 text-center" scope="col">Penyerahan Unit</th>
                                        {{--  <th scope="col">STNK</th>
                                        <th scope="col">Polis</th>
                                        <th scope="col">BPKB</th>  --}}
                                        @foreach ($documentCategories as $item)
                                            <th class="px-2 text-center" scope="col">{{ $item->name }}</th>
                                        @endforeach
                                        <th class="px-2 text-center" scope="col">Bukti Pembayaran Imbal Jasa</th>
                                        <th class="px-2 text-center" scope="col">Imbal Jasa</th>
                                        <th class="px-2 text-center" scope="col">Status</th>
                                        <th class="px-2 text-center" scope="col">Aksi</th>
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
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td class="text-center">
                                                @if ($item->detail)
                                                    {{$item->detail['nama']}}
                                                @else
                                                undifined
                                                @endif
                                            </td>
                                            <td class="@if ($item->detail) link-po @endif text-center">
                                                @if ($buktiPembayaran)
                                                    @if ($item->detail)
                                                        <a class="open-po" data-toggle="modal" data-target="#detailPO"
                                                            data-nomorPo="{{ $item->detail['no_po'] }}"
                                                            data-tanggalPo="{{ date('d-m-Y', strtotime($item->detail['tanggal'])) }}"
                                                            data-filepo="{{ config('global.los_host').'/public' . $item->detail['po'] }}">
                                                            {{ $item->detail['no_po'] }}</a>
                                                    @else
                                                        -
                                                    @endif
                                                @else
                                                    @if ($item->detail)
                                                        <a class="open-po" data-toggle="modal" data-target="#detailPO"
                                                            data-nomorPo="{{ $item->detail['no_po'] }}"
                                                            data-tanggalPo="{{ date('d-m-Y', strtotime($item->detail['tanggal'])) }}"
                                                            data-filepo="{{ config('global.los_host').'/public'. $item->detail['po'] }}">
                                                            {{ $item->detail['no_po'] }}</a>
                                                    @else
                                                        -
                                                    @endif
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if (Auth::user()->vendor_id)
                                                    @if (!$item->tgl_ketersediaan_unit)
                                                        <a style="text-decoration: underline;" data-toggle="modal"
                                                            data-target="#tglModal" data-id_kkb="{{ $item->kkb_id }}"
                                                            href="#">Atur</a>
                                                    @else
                                                        {{ date('d-m-Y', strtotime($item->tgl_ketersediaan_unit)) }}
                                                    @endif
                                                @elseif ($item->tgl_ketersediaan_unit)
                                                    {{ date('d-m-Y', strtotime($item->tgl_ketersediaan_unit)) }}
                                                @else
                                                    <span class="text-danger">Menunggu tanggal ketersediaan unit</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
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
                                            <td class="text-center">
                                                @if ($item->tgl_ketersediaan_unit)
                                                    @if ($penyerahanUnit)
                                                        @if ($penyerahanUnit->is_confirm)
                                                        <a style="text-decoration: underline; cursor: pointer;" class="confirm-penyerahan-unit"
                                                            data-toggle="modal" data-id-category="2"
                                                            data-id-doc="{{ $penyerahanUnit ? $penyerahanUnit->id : 0 }}"
                                                            data-file="@isset($penyerahanUnit->file){{ $penyerahanUnit->file }}@endisset"
                                                            data-confirm="{{$penyerahanUnit->is_confirm}}"
                                                            data-tanggal="{{date('d-m-Y', strtotime($penyerahanUnit->date))}}"
                                                            data-confirm_at="{{date('d-m-Y', strtotime($penyerahanUnit->confirm_at))}}"
                                                            href="#confirmModalPenyerahanUnit">{{ date('d-m-Y', strtotime($penyerahanUnit->date)) }}</a>
                                                        @else
                                                            @if (Auth::user()->role_id == 3)
                                                                <span>Menunggu konfirmasi cabang</span>
                                                            @else
                                                                <a style="text-decoration: underline; cursor: pointer;" class="confirm-penyerahan-unit"
                                                                    data-toggle="modal" data-id-category="2"
                                                                    data-id-doc="{{ $penyerahanUnit ? $penyerahanUnit->id : 0 }}"
                                                                    data-file="@isset($penyerahanUnit->file){{ $penyerahanUnit->file }}@endisset"
                                                                    data-confirm="{{$penyerahanUnit->is_confirm}}"
                                                                    data-tanggal="{{date('d-m-Y', strtotime($penyerahanUnit->date))}}"
                                                                    data-confirm_at="{{date('d-m-Y', strtotime($penyerahanUnit->confirm_at))}}"
                                                                    href="#confirmModalPenyerahanUnit">Konfirmasi</a>
                                                            @endif
                                                        @endif
                                                    @else
                                                        <span class="text-info">Maksimal {{ date('d-m-Y', strtotime($item->tgl_ketersediaan_unit . ' +1 month')) }}</span>
                                                    @endif
                                                @else
                                                    <span class="text-danger">-</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
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
                                                                <span class="text-info">Maksimal {{ date('d-m-Y', strtotime($penyerahanUnit->confirm_at . ' +1 month')) }}</span>
                                                            @else
                                                                <span class="text-warning">Menunggu konfirmasi</span>
                                                            @endif
                                                        @else
                                                            -
                                                        @endif
                                                    @endif
                                                @else
                                                    <span class="text-warning">-</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if ($penyerahanUnit)
                                                    @if ($polis)
                                                        @if ($polis->file && $polis->is_confirm)
                                                            <a href="/storage/dokumentasi-polis/{{ $polis->file }}"
                                                                target="_blank">{{ $polis->date }}</a>
                                                        @else
                                                            @if (Auth::user()->role_id == 3)
                                                                @if ($penyerahanUnit->is_confirm)
                                                                    <span class="text-info">Maksimal {{ date('d-m-Y', strtotime($penyerahanUnit->confirm_at . ' +1 month')) }}</span>
                                                                @else
                                                                    <span class="text-warning">Menunggu konfirmasi</span>
                                                                @endif
                                                            @else
                                                                -
                                                            @endif
                                                        @endif
                                                    @else
                                                        @if (Auth::user()->role_id == 3)
                                                            @if ($penyerahanUnit->is_confirm)
                                                                <span class="text-info">Maksimal {{ date('d-m-Y', strtotime($penyerahanUnit->confirm_at . ' +1 month')) }}</span>
                                                            @else
                                                                -
                                                            @endif
                                                        @else
                                                            <span class="text-warning">Menunggu penyerahan</span>
                                                        @endif
                                                    @endif
                                                @else
                                                    <span class="text-warning">-</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
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
                                                            <span class="text-info">Maksimal {{ date('d-m-Y', strtotime($penyerahanUnit->confirm_at . ' +3 month')) }}</span>
                                                            @else
                                                                -
                                                            @endif
                                                        @else
                                                            <span class="text-warning">Menunggu penyerahan</span>
                                                        @endif
                                                    @else
                                                    -
                                                    @endif
                                                @else
                                                    <span class="text-warning">-</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if ($penyerahanUnit)
                                                    @if ($penyerahanUnit->is_confirm)
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
                                                                <span class="text-info">Maksimal {{ date('d-m-Y', strtotime($penyerahanUnit->confirm_at . ' +3 month')) }}</span>
                                                                @else
                                                                    <span class="text-warning">Menunggu konfirmasi penyerahan unit</span>
                                                                @endif
                                                            @else
                                                                <span class="text-warning">Menunggu penyerahan</span>
                                                            @endif
                                                        @endif
                                                    @else
                                                    -
                                                    @endif
                                                @else
                                                    <span class="text-warning">-</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
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
                                                    -
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
                                            <td class="text-center">
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
                                                        @if ($imbalJasa)
                                                            @if ($imbalJasa->file && $imbalJasa->is_confirm)
                                                                @if ($stnk && $polis && $bpkb)
                                                                    @if (Auth::user()->role_id == 2)
                                                                        <span class="text-info">Silahkan upload bukti transfer imbal
                                                                            jasa</span>
                                                                    @else
                                                                        <span class="text-info">Menunggu bukti transfer imbal
                                                                            jasa</span>
                                                                    @endif
                                                                @else
                                                                    <span class="text-warning">Menunggu penyerahan semua berkas</span>
                                                                @endif
                                                            @else
                                                                -
                                                            @endif
                                                        @else
                                                            <span class="text-warning">-</span>
                                                        @endif
                                                    @endif
                                                @else
                                                    <span class="text-warning">-</span>
                                                @endif
                                            </td>
                                            <td
                                                class="text-center @if ($item->status == 'done' && $setImbalJasa) text-success @else text-info @endif">
                                                @if ($setImbalJasa)
                                                    {{ $item->status }}
                                                @else
                                                    progress
                                                @endif
                                            </td>
                                            <td class="text-center">
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
                                                                        data-date-stnk="@isset($stnk->date){{ date('d-m-Y', strtotime($stnk->date)) }}@endisset"
                                                                        data-confirm-stnk="@isset($stnk->is_confirm){{ $stnk->is_confirm }}@endisset"
                                                                        data-confirm-at-stnk="@isset($stnk->confirm_at){{ date('d-m-Y', strtotime($stnk->confirm_at)) }}@endisset"
                                                                        data-no-polis="@isset($polis->text){{ $polis->text }}@endisset"
                                                                        data-file-polis="@isset($polis->file){{ $polis->file }}@endisset"
                                                                        data-date-polis="@isset($polis->date){{ date('d-m-Y', strtotime($polis->date)) }}@endisset"
                                                                        data-confirm-polis="@isset($polis->is_confirm){{ $polis->is_confirm }}@endisset"
                                                                        data-confirm-at-polis="@isset($polis->confirm_at){{ date('d-m-Y', strtotime($polis->confirm_at)) }}@endisset"
                                                                        data-no-bpkb="@isset($bpkb->text){{ $bpkb->text }}@endisset"
                                                                        data-file-bpkb="@isset($bpkb->file){{ $bpkb->file }}@endisset"
                                                                        data-date-bpkb="@isset($bpkb->date){{ date('d-m-Y', strtotime($bpkb->date)) }}@endisset"
                                                                        data-confirm-bpkb="@isset($bpkb->is_confirm){{ $bpkb->is_confirm }}@endisset"
                                                                        data-confirm-at-bpkb="@isset($bpkb->confirm_at){{ date('d-m-Y', strtotime($bpkb->confirm_at)) }}@endisset"
                                                                        href="#"
                                                                        class="dropdown-item upload-berkas">
                                                                        Upload Berkas
                                                                    </a>
                                                                @endif
                                                            @endif
                                                        @endif
                                                        {{--  Konfirmasi penyerahan unit (Cabang)  --}}
                                                        {{--  @if (Auth::user()->role_id == 2 && $penyerahanUnit)
                                                            @if (!$penyerahanUnit->is_confirm)
                                                                @if (!isset($stnk->file) || !isset($polis->file) || !isset($bpkb->is_confirm))
                                                                    <a class="dropdown-item confirm-penyerahan-unit"
                                                                        data-toggle="modal" data-id-category="2"
                                                                        data-id-doc="{{ $penyerahanUnit ? $penyerahanUnit->id : 0 }}"
                                                                        data-file="@isset($penyerahanUnit->file){{ $penyerahanUnit->file }}@endisset"
                                                                        href="#confirmModalPenyerahanUnit">Konfirmasi
                                                                        Penyerahan Unit</a>
                                                                @endif
                                                            @endif
                                                        @endif  --}}
                                                        @if (Auth::user()->role_id == 2)
                                                            {{--  Cabang  --}}
                                                            @if ($stnk || $polis || $bpkb)
                                                                @if ((isset($stnk->is_confirm) && !$stnk->is_confirm) || (isset($polis->is_confirm) && !$polis->is_confirm) || (isset($bpkb->is_confirm) && !$bpkb->is_confirm))
                                                                    <a data-toggle="modal"
                                                                        data-target="#uploadBerkasModal"
                                                                        data-id_kkb="{{ $item->kkb_id }}"
                                                                        data-id-stnk="@if ($stnk) {{ $stnk->id }}@else- @endif"
                                                                        data-id-polis="@if ($polis) {{ $polis->id }}@else- @endif"
                                                                        data-id-bpkb="@if ($bpkb) {{ $bpkb->id }}@else- @endif"
                                                                        data-no-stnk="@isset($stnk->text){{ $stnk->text }}@endisset"
                                                                        data-file-stnk="@isset($stnk->file){{ $stnk->file }}@endisset"
                                                                        data-date-stnk="@isset($stnk->date){{ date('d-m-Y', strtotime($stnk->date)) }}@endisset"
                                                                        data-confirm-stnk="@isset($stnk->is_confirm){{ $stnk->is_confirm }}@endisset"
                                                                        data-confirm-at-stnk="@isset($stnk->confirm_at){{ date('d-m-Y', strtotime($stnk->confirm_at)) }}@endisset"
                                                                        data-no-polis="@isset($polis->text){{ $polis->text }}@endisset"
                                                                        data-file-polis="@isset($polis->file){{ $polis->file }}@endisset"
                                                                        data-date-polis="@isset($polis->date){{ date('d-m-Y', strtotime($polis->date)) }}@endisset"
                                                                        data-confirm-polis="@isset($polis->is_confirm){{ $polis->is_confirm }}@endisset"
                                                                        data-confirm-at-polis="@isset($polis->confirm_at){{ date('d-m-Y', strtotime($polis->confirm_at)) }}@endisset"
                                                                        data-no-bpkb="@isset($bpkb->text){{ $bpkb->text }}@endisset"
                                                                        data-file-bpkb="@isset($bpkb->file){{ $bpkb->file }}@endisset"
                                                                        data-date-bpkb="@isset($bpkb->date){{ date('d-m-Y', strtotime($bpkb->date)) }}@endisset"
                                                                        data-confirm-bpkb="@isset($bpkb->is_confirm){{ $bpkb->is_confirm }}@endisset"
                                                                        data-confirm-at-bpkb="@isset($bpkb->confirm_at){{ date('d-m-Y', strtotime($bpkb->confirm_at)) }}@endisset"
                                                                        href="#"
                                                                        class="dropdown-item upload-berkas">
                                                                        Konfirmasi Berkas
                                                                    </a>
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

    <!-- Modal bukti pembayaran -->
    @include('pages.kredit.modal.bukti-pembayaran-modal')

    {{-- Detail PO --}}
    @include('pages.kredit.modal.detail-po')

    <!-- Tanggal Ketersediaan Unit Modal -->
    <div class="modal fade" id="tglModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <form id="modal-tgl-form">
                        <input type="hidden" name="id_kkb" id="id_kkb">
                        <div class="form-group">
                            <label>Tanggal Ketersediaan Unit</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="tgl_ketersediaan_unit"
                                    name="tgl_ketersediaan_unit">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fa fa-calendar-check"></i>
                                    </span>
                                </div>
                            </div>
                            <small class="form-text text-danger error"></small>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Imbal Jasa Modal -->
    <div class="modal fade" id="uploadImbalJasaModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <form id="modal-imbal-jasa-form">
                        @csrf
                        <input type="hidden" name="id_kkbimbaljasa" id="id_kkbimbaljasa">
                        <div class="form-group">
                            <label>Upload bukti transfer imbal jasa</label>
                            <div class="input-group">
                                <input type="file" class="form-control" accept="image/*" id="file_imbal_jasa"
                                    name="file_imbal_jasa" required>
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fa fa-image"></i>
                                    </span>
                                </div>
                            </div>
                            <small class="form-text text-danger error"></small>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Upload Bukti Pembayaran Modal -->
    <div class="modal fade" id="buktiPembayaranModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <form id="modal-bukti-pembayaran" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id_kkb" id="id_kkb">
                        <div class="form-group">
                            <label>Scan Bukti Pembayaran (pdf)</label>
                            <div class="input-group">
                                <input type="file" class="form-control" id="bukti_pembayaran_scan"
                                    name="bukti_pembayaran_scan" accept="application/pdf" required>
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fa fa-file"></i>
                                    </span>
                                </div>
                            </div>
                            <small class="form-text text-danger error"></small>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Tanggal Penyerahan Unit Modal -->
    <div class="modal fade" id="tglModalPenyerahan" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <form id="modal-tgl-penyerahan" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id_kkb" id="id_kkb">
                        <div class="form-group">
                            <label>Tanggal Pengiriman</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="tgl_pengiriman" name="tgl_pengiriman">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fa fa-calendar-check"></i>
                                    </span>
                                </div>
                            </div>
                            <small class="form-text text-danger error"></small>
                        </div>
                        <div class="form-group">
                            <label>Foto Bukti Penyerahan Unit</label>
                            <div class="input-group">
                                <input type="file" class="form-control" id="upload_penyerahan_unit"
                                    name="upload_penyerahan_unit" accept="image/png, image/jpeg, image/jpeg">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fa fa-image"></i>
                                    </span>
                                </div>
                            </div>
                            <small class="form-text text-danger error"></small>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Upload Berkas Modal -->
    @include('pages.kredit.modal.upload-berkas-modal')

    <!-- Upload BKPB Modal -->
    <div class="modal fade" id="uploadBpkbModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <form id="modal-bpkb" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id_kkb" id="id_kkb">
                        <div class="form-group">
                            <label>Nomor</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="no_bpkb" name="no_bpkb" required>
                            </div>
                            <small class="form-text text-danger error"></small>
                        </div>
                        <div class="form-group">
                            <label>Scan Berkas (pdf)</label>
                            <div class="input-group">
                                <input type="file" class="form-control" id="bpkb_scan" name="bpkb_scan"
                                    accept="application/pdf" required>
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fa fa-file"></i>
                                    </span>
                                </div>
                            </div>
                            <small class="form-text text-danger error"></small>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Upload STNK Modal -->
    <div class="modal fade" id="uploadStnkModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <form id="modal-stnk" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id_kkb" id="id_kkb">
                        <div class="form-group">
                            <label>Nomor</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="no_stnk" name="no_stnk" required>
                            </div>
                            <small class="form-text text-danger error"></small>
                        </div>
                        <div class="form-group">
                            <label>Scan Berkas (pdf)</label>
                            <div class="input-group">
                                <input type="file" class="form-control" id="stnk_scan" name="stnk_scan"
                                    accept="application/pdf" required>
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fa fa-file"></i>
                                    </span>
                                </div>
                            </div>
                            <small class="form-text text-danger error"></small>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Confirm Cabang --}}
    <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="form-group name" id="konfirmasi">
                        Yakin ingin mengkonfirmasi data ini?
                    </div>
                    <div class="form-inline">
                        <button data-dismiss="modal" class="btn btn-danger mr-2">Tidak</button>
                        <form id="confirm-form">
                            <input type="hidden" name="confirm_id" id="confirm_id">
                            <input type="hidden" name="confirm_id_category" id="confirm_id_category">
                            <button type="submit" class="btn btn-primary">Ya</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Confirm Vendor --}}
    <div class="modal fade" id="confirmModalVendor" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="form-group name" id="konfirmasi">
                        Yakin ingin mengkonfirmasi data ini?
                    </div>
                    @if (Auth::user()->role_id == 3)
                        <iframe id="preview_bukti_tf" class="mt-2" width="100%" height="500"></iframe>
                    @endif
                    <div class="form-inline">
                        <button data-dismiss="modal" class="btn btn-danger mr-2">Tidak</button>
                        <form id="confirm-form-vendor">
                            <input type="hidden" name="confirm_id" id="confirm_id">
                            <input type="hidden" name="confirm_id_category" id="confirm_id_category">
                            <button type="submit" class="btn btn-primary">Ya</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Confirm Penyerahan Unit --}}
    @include('pages.kredit.modal.confirm-penyerahan-unit')

    {{-- Modal Confirm Imbal Jasa --}}
    <div class="modal fade" id="confirmModalImbalJasa" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="form-group name" id="konfirmasi">
                        Yakin ingin mengkonfirmasi data ini?
                    </div>
                    <iframe id="preview_imbal-jasa" src="" width="100%" height="450px"></iframe>
                    <div class="form-inline">
                        <button data-dismiss="modal" class="btn btn-danger mr-2">Tidak</button>
                        <form id="confirm-form-imbal-jasa">
                            <input type="hidden" name="id_cat" id="id_cat">
                            <button type="submit" class="btn btn-primary">Ya</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('pages.kredit.modal.detail-modal')

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
