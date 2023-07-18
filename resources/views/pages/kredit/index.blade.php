@extends('layout.master')

@section('title', $title)

@push('extraStyle')
    <style>
        .pdfobject-container {
            height: 50rem;
            border: 1rem solid rgba(0, 0, 0, .1);
        }

        td {
            padding: 5px;
        }
    </style>
@endpush

@section('content')

    <div class="panel-header">
        <div class="page-inner py-5">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                <div>
                    <h2 class="text-primary fw-bold">{{ $pageTitle }}</h2>
                </div>
            </div>
        </div>
    </div>
    <div class="page-inner mt--5">
        <div class="row mt--2">
            <div class="col-md-12">
                {{-- <div class="card">
                    <div class="card-header">
                        Filter Data KKB
                    </div>
                    <div class="card-body">
                        <form action="" method="get">
                            <div class="row">
                                <div class="col-sm-6">
                                    <h5 class="modal-title penyerahan-unit-title">Ketersediaan Unit</h5>
                                    <input type="date" class="form-control" name="ketersediaan_unit" required>
                                </div>
                                <div class="col-sm-6">
                                    <h5 class="modal-title penyerahan-unit-title">Status</h5>
                                    <select class="custom-select form-control" name="status" required>
                                        <option value="" selected>Pilih Status...</option>
                                        <option value="process">process</option>
                                        <option value="done">Done</option>
                                    </select>
                                </div>
                            </div>
                            <button class="btn btn-sm btn-primary mt-3">Filter Data</button>
                        </form>
                    </div>
                </div> --}}
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        Data KKB
                        <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#filter">
                            Filter Data
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
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
                                                    {{ $item->detail['nama'] }}
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
                                                            data-filepo="{{ config('global.los_host') . '/public' . $item->detail['po'] }}">
                                                            {{ $item->detail['no_po'] }}</a>
                                                    @else
                                                        -
                                                    @endif
                                                @else
                                                    @if ($item->detail)
                                                        <a class="open-po" data-toggle="modal" data-target="#detailPO"
                                                            data-nomorPo="{{ $item->detail['no_po'] }}"
                                                            data-tanggalPo="{{ date('d-m-Y', strtotime($item->detail['tanggal'])) }}"
                                                            data-filepo="{{ config('global.los_host') . '/public' . $item->detail['po'] }}">
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
                                                                <a style="cursor: pointer; text-decoration: underline;"
                                                                    class="confirm-bukti-pembayaran" data-toggle="modal"
                                                                    data-id-category="1"
                                                                    data-id-doc="{{ $buktiPembayaran ? $buktiPembayaran->id : 0 }}"
                                                                    data-file="@isset($buktiPembayaran->file){{ $buktiPembayaran->file }}@endisset"
                                                                    href="#confirmModalVendor">Konfirmasi</a>
                                                            @elseif ($buktiPembayaran->is_confirm)
                                                                <a class="m-0 bukti-pembayaran-modal"
                                                                    style="cursor: pointer; text-decoration: underline;"
                                                                    data-toggle="modal"
                                                                    data-target="#previewBuktiPembayaranModal"
                                                                    data-file="{{ $buktiPembayaran->file }}"
                                                                    data-confirm="{{ $buktiPembayaran->is_confirm }}"
                                                                    data-tanggal="{{ date('d-m-Y', strtotime($buktiPembayaran->date)) }}"
                                                                    data-confirm_at="{{ date('d-m-Y', strtotime($buktiPembayaran->confirm_at)) }}">Selesai</a>
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
                                                                <a class="m-0 bukti-pembayaran-modal"
                                                                    style="cursor: pointer; text-decoration: underline;"
                                                                    data-toggle="modal"
                                                                    data-target="#previewBuktiPembayaranModal"
                                                                    data-file="{{ $buktiPembayaran->file }}"
                                                                    data-confirm="{{ $buktiPembayaran->is_confirm }}"
                                                                    data-confirm_at="{{ date('d-m-Y', strtotime($buktiPembayaran->confirm_at)) }}">Menunggu
                                                                    Konfirmasi Vendor</a>
                                                            @elseif ($buktiPembayaran->is_confirm)
                                                                <a class="m-0 bukti-pembayaran-modal"
                                                                    style="cursor: pointer; text-decoration: underline;"
                                                                    data-toggle="modal"
                                                                    data-target="#previewBuktiPembayaranModal"
                                                                    data-file="{{ $buktiPembayaran->file }}"
                                                                    data-confirm="{{ $buktiPembayaran->is_confirm }}"
                                                                    data-tanggal="{{ date('d-m-Y', strtotime($buktiPembayaran->date)) }}"
                                                                    data-confirm_at="{{ date('d-m-Y', strtotime($buktiPembayaran->confirm_at)) }}">Selesai</a>
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
                                                            <a style="text-decoration: underline; cursor: pointer;"
                                                                class="confirm-penyerahan-unit" data-toggle="modal"
                                                                data-id-category="2"
                                                                data-id-doc="{{ $penyerahanUnit ? $penyerahanUnit->id : 0 }}"
                                                                data-file="@isset($penyerahanUnit->file){{ $penyerahanUnit->file }}@endisset"
                                                                data-confirm="{{ $penyerahanUnit->is_confirm }}"
                                                                data-tanggal="{{ date('d-m-Y', strtotime($penyerahanUnit->date)) }}"
                                                                data-confirm_at="{{ date('d-m-Y', strtotime($penyerahanUnit->confirm_at)) }}"
                                                                href="#confirmModalPenyerahanUnit">{{ date('d-m-Y', strtotime($penyerahanUnit->date)) }}</a>
                                                        @else
                                                            @if (Auth::user()->role_id == 3)
                                                                <span>Menunggu konfirmasi cabang</span>
                                                            @else
                                                                <a style="text-decoration: underline; cursor: pointer;"
                                                                    class="confirm-penyerahan-unit" data-toggle="modal"
                                                                    data-id-category="2"
                                                                    data-id-doc="{{ $penyerahanUnit ? $penyerahanUnit->id : 0 }}"
                                                                    data-file="@isset($penyerahanUnit->file){{ $penyerahanUnit->file }}@endisset"
                                                                    data-confirm="{{ $penyerahanUnit->is_confirm }}"
                                                                    data-tanggal="{{ date('d-m-Y', strtotime($penyerahanUnit->date)) }}"
                                                                    data-confirm_at="{{ date('d-m-Y', strtotime($penyerahanUnit->confirm_at)) }}"
                                                                    href="#confirmModalPenyerahanUnit">Konfirmasi</a>
                                                            @endif
                                                        @endif
                                                    @else
                                                        <span class="text-info">Maksimal
                                                            {{ date('d-m-Y', strtotime($item->tgl_ketersediaan_unit . ' +1 month')) }}</span>
                                                    @endif
                                                @else
                                                    <span class="text-danger">-</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if ($penyerahanUnit)
                                                    @if ($penyerahanUnit->is_confirm)
                                                        @if ($stnk)
                                                            @if ($stnk->file && $stnk->is_confirm)
                                                                <a style="text-decoration: underline; cursor: pointer;"
                                                                    class="open-po detailFileStnk" data-toggle="modal"
                                                                    data-target="#detailStnk"
                                                                    data-file="{{ $stnk->file }}"
                                                                    data-confirm="{{ $stnk->is_confirm }}"
                                                                    data-tanggal="{{ date('d-m-Y', strtotime($stnk->date)) }}"
                                                                    data-confirm_at="{{ date('d-m-Y', strtotime($stnk->confirm_at)) }}">{{ date('d-m-Y', strtotime($stnk->date)) }}</a>
                                                            @else
                                                                <span class="text-warning">Menunggu konfirmasi</span>
                                                            @endif
                                                        @else
                                                            @if (Auth::user()->role_id == 3)
                                                                @if ($penyerahanUnit->is_confirm)
                                                                    <span class="text-info">Maksimal
                                                                        {{ date('d-m-Y', strtotime($penyerahanUnit->confirm_at . ' +1 month')) }}</span>
                                                                @else
                                                                    <span class="text-warning">Menunggu konfirmasi
                                                                        penyerahan unit</span>
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
                                                @if ($penyerahanUnit)
                                                    @if ($penyerahanUnit->is_confirm)
                                                        @if ($polis)
                                                            @if ($polis->file && $polis->is_confirm)
                                                                <a style="text-decoration: underline; cursor: pointer;"
                                                                    class="open-po detailFilePolis" data-toggle="modal"
                                                                    data-target="#detailPolis"
                                                                    data-file="{{ $polis->file }}"
                                                                    data-confirm="{{ $polis->is_confirm }}"
                                                                    data-tanggal="{{ date('d-m-Y', strtotime($polis->date)) }}"
                                                                    data-confirm_at="{{ date('d-m-Y', strtotime($polis->confirm_at)) }}">{{ date('d-m-Y', strtotime($polis->date)) }}</a>
                                                            @else
                                                                <span class="text-warning">Menunggu konfirmasi</span>
                                                            @endif
                                                        @else
                                                            @if (Auth::user()->role_id == 3)
                                                                @if ($penyerahanUnit->is_confirm)
                                                                    <span class="text-info">Maksimal
                                                                        {{ date('d-m-Y', strtotime($penyerahanUnit->confirm_at . ' +1 month')) }}</span>
                                                                @else
                                                                    <span class="text-warning">Menunggu konfirmasi
                                                                        penyerahan unit</span>
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
                                                @if ($penyerahanUnit)
                                                    @if ($penyerahanUnit->is_confirm)
                                                        @if ($bpkb)
                                                            @if ($bpkb->file && $bpkb->is_confirm)
                                                                <a style="text-decoration: underline; cursor: pointer;"
                                                                    class="open-po detailFileBpkb" data-toggle="modal"
                                                                    data-target="#detailBpkb"
                                                                    data-file="{{ $bpkb->file }}"
                                                                    data-confirm="{{ $bpkb->is_confirm }}"
                                                                    data-tanggal="{{ date('d-m-Y', strtotime($bpkb->date)) }}"
                                                                    data-confirm_at="{{ date('d-m-Y', strtotime($bpkb->confirm_at)) }}">{{ date('d-m-Y', strtotime($bpkb->date)) }}</a>
                                                            @else
                                                                <span class="text-warning">Menunggu konfirmasi</span>
                                                            @endif
                                                        @else
                                                            @if (Auth::user()->role_id == 3)
                                                                @if ($penyerahanUnit->is_confirm)
                                                                    <span class="text-info">Maksimal
                                                                        {{ date('d-m-Y', strtotime($penyerahanUnit->confirm_at . ' +3 month')) }}</span>
                                                                @else
                                                                    <span class="text-warning">Menunggu konfirmasi
                                                                        penyerahan unit</span>
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
                                                            <a style="cursor: pointer; text-decoration: underline;"
                                                                class="confirm-imbal-jasa" data-toggle="modal"
                                                                data-id="{{ $imbalJasa->id }}"
                                                                data-file="{{ $imbalJasa->file }}"
                                                                data-tanggal="{{ \Carbon\Carbon::parse($imbalJasa->created_at)->format('d-m-Y') }}"
                                                                href="#confirmModalImbalJasa">Konfirmasi Bukti
                                                                Pembayaran</a>
                                                        @elseif ($imbalJasa->is_confirm)
                                                            <a class="bukti-pembayaran-modal"
                                                                style="cursor: pointer; text-decoration: underline;"
                                                                data-toggle="modal" data-target="#previewImbalJasaModal"
                                                                data-tanggal="{{ \Carbon\Carbon::parse($imbalJasa->date)->format('d-m-Y') }}"
                                                                data-confirm_at="{{ \Carbon\Carbon::parse($imbalJasa->confirm_at)->format('d-m-Y') }}"
                                                                data-confirm="{{ $imbalJasa->is_confirm }}"
                                                                data-file="{{ $imbalJasa->file }}">Selesai</a>
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
                                                            <a href="#"
                                                                style="text-decoration: underline; cursor: pointer;"
                                                                class="upload-imbal-jasa" data-toggle="modal"
                                                                data-target="#uploadImbalJasaModal"
                                                                data-id="{{ $item->id }}">Bayar</a>
                                                        @else
                                                            @if (!$imbalJasa->is_confirm)
                                                                <p class="m-0">Menunggu Konfirmasi Vendor</p>
                                                            @elseif ($imbalJasa->is_confirm)
                                                                <a class="bukti-pembayaran-modal"
                                                                    style="cursor: pointer; text-decoration: underline;"
                                                                    data-toggle="modal"
                                                                    data-target="#previewImbalJasaModal"
                                                                    data-confirm="{{ $imbalJasa->is_confirm }}"
                                                                    data-tanggal="{{ \Carbon\Carbon::parse($imbalJasa->date)->format('d-m-Y') }}"
                                                                    data-confirm_at="{{ \Carbon\Carbon::parse($imbalJasa->confirm_at)->format('d-m-Y') }}"
                                                                    data-file="{{ $imbalJasa->file }}">Selesai</a>
                                                            @endif
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
                                                            <a {{-- style="text-decoration: underline; cursor: pointer;"
                                                                class="open-po detailFileImbalJasa" data-toggle="modal"
                                                                data-target="#imbaljasadetail"
                                                                data-file="{{ $imbalJasa->file }}" --}}>Rp.
                                                                {{ number_format($setImbalJasa->imbaljasa, 0, '', '.') }}</a>
                                                        @else
                                                            @if (Auth::user()->role_id == 3)
                                                                <span class="text-info">Silahkan konfirmasi bukti transfer
                                                                    imbal
                                                                    jasa</span>
                                                            @else
                                                                <span>Rp.
                                                                    {{ number_format($setImbalJasa->imbaljasa, 0, '', '.') }}</span>
                                                            @endif
                                                        @endif
                                                    @else
                                                        @if ($imbalJasa)
                                                            @if ($imbalJasa->file && $imbalJasa->is_confirm)
                                                                @if ($stnk && $polis && $bpkb)
                                                                    @if (Auth::user()->role_id == 2)
                                                                        <span class="text-info">Silahkan upload bukti
                                                                            transfer imbal
                                                                            jasa</span>
                                                                    @else
                                                                        <span class="text-info">Menunggu bukti transfer
                                                                            imbal
                                                                            jasa</span>
                                                                    @endif
                                                                @else
                                                                    <span class="text-warning">Menunggu penyerahan semua
                                                                        berkas</span>
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
                                                    {{ ucwords($item->status) }}
                                                @else
                                                    Progress
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
                                                                    <a data-toggle="modal"
                                                                        data-target="#tglModalPenyerahan"
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
                                                                    <a data-toggle="modal"
                                                                        data-target="#uploadBerkasModal"
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
                                                        @if (Auth::user()->role_id == 2)
                                                            {{--  Cabang  --}}
                                                            @if ($stnk || $polis || $bpkb)
                                                                @if (
                                                                    (isset($stnk->is_confirm) && !$stnk->is_confirm) ||
                                                                        (isset($polis->is_confirm) && !$polis->is_confirm) ||
                                                                        (isset($bpkb->is_confirm) && !$bpkb->is_confirm))
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
                                                            {{--  @if ($stnk && $polis && $bpkb && !$imbalJasa)  --}}
                                                            @if (isset($stnk->is_confirm) &&
                                                                    !$stnk->is_confirm &&
                                                                    (isset($polis->is_confirm) && !$polis->is_confirm) &&
                                                                    (isset($bpkb->is_confirm) && !$bpkb->is_confirm))
                                                                <a href="#" class="dropdown-item upload-imbal-jasa"
                                                                    data-toggle="modal"
                                                                    data-target="#uploadImbalJasaModal"
                                                                    data-id="{{ $item->id }}">Upload
                                                                    bukti imbal
                                                                    jasa</a>
                                                            @endif
                                                        @else
                                                            {{--  @if ($stnk && $polis && $bpkb)
                                                                @if ($imbalJasa && $imbalJasa->is_confirm == false)
                                                                    <a href="#"
                                                                        class="dropdown-item confirm-imbal-jasa"
                                                                        data-id="{{ $imbalJasa->id }}"
                                                                        data-file="{{ $imbalJasa->file }}"
                                                                        data-toggle="modal"
                                                                        data-target="#confirmModalImbalJasa">Konfirmasi bukti imbal jasa</a>
                                                                @endif
                                                            @endif  --}}
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

    @include('pages.kredit.modal.filter-modal')
    {{-- File STNK --}}
    @include('pages.kredit.modal.file-stnk')
    {{-- File Polis --}}
    @include('pages.kredit.modal.file-polis')
    {{-- File BPKB --}}
    @include('pages.kredit.modal.file-bpkb')
    {{-- File imbalJasa --}}
    @include('pages.kredit.modal.imbal-jasa')

    <!-- Modal bukti pembayaran -->
    @include('pages.kredit.modal.bukti-pembayaran-modal')

    <!-- Modal bukti pembayaran Imbal Jasa -->
    @include('pages.kredit.modal.bukti-imbal-jasa-modal')

    {{-- Detail PO --}}
    @include('pages.kredit.modal.detail-po')

    <!-- Tanggal Ketersediaan Unit Modal -->
    <div class="modal fade" id="tglModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title penyerahan-unit-title">Konfirmasi Penyerahan Unit</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-light">&times;</span>
                    </button>
                </div>
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
                <div class="modal-header bg-primary">
                    <h5 class="modal-title penyerahan-unit-title">Bukti Transfer</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-light">&times;</span>
                    </button>
                </div>
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
                <div class="modal-header bg-primary">
                    <h5 class="modal-title penyerahan-unit-title">Bukti Pembayaran</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-light">&times;</span>
                    </button>
                </div>
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
                <div class="modal-header bg-primary">
                    <h5 class="modal-title penyerahan-unit-title">Penyerahan Unit</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-light">&times;</span>
                    </button>
                </div>
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
                {{-- <div class="modal-header bg-primary">
                    <h5 class="modal-title penyerahan-unit-title">Konfirmasi Penyerahan Unit</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-light">&times;</span>
                    </button>
                </div> --}}
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
                <div class="modal-header bg-primary">
                    <h5 class="modal-title penyerahan-unit-title">Konfirmasi Bukti Pembayaran</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-light">&times;</span>
                    </button>
                </div>
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
                    <div class="form-inline">
                        <form id="confirm-form-imbal-jasa">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-6 mb-3">
                                        <h5>Tanggal Upload :</h5>
                                        <b id="tgl-upload-imbal-jasa">-</b>
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <h5>Tanggal Konfirmasi :</h5>
                                        <b>-</b>
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <h5>Status Konfirmasi :</h5>
                                        <b>Belum di Konfirmasi Vendor</b>
                                    </div>
                                    <div class="col-sm-12">
                                        <img id="preview_imbal-jasa" src="" width="100%">
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="id_cat" id="id_cat">
                            <button data-dismiss="modal" class="btn btn-danger mr-2">Tidak</button>
                            <button type="submit" class="btn btn-primary">Ya</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('pages.kredit.modal.detail-modal')

    @push('extraScript')
        <script src="{{ asset('template') }}/assets/js/pdfobject.min.js"></script>
        <script type="module" src="https://unpkg.com/x-frame-bypass"></script>

        @if (session('status'))
            <script>
                swal("Berhasil!", '{{ session('status') }}', {
                    icon: "success",
                    timer: 3000,
                    closeOnClickOutside: false
                }).then(() => {
                    location.reload();
                });
                setTimeout(function() {
                    location.reload();
                }, 3000);
            </script>
        @endif
        @if (session('error'))
            <script>
                swal("Gagal!", '{{ session('status') }}', {
                    icon: "error",
                    timer: 3000,
                    closeOnClickOutside: false
                }).then(() => {
                    location.reload();
                });
                setTimeout(function() {
                    location.reload();
                }, 3000);
            </script>
        @endif
        <!-- DateTimePicker -->
        <script src="{{ asset('template') }}/assets/js/plugin/datepicker/bootstrap-datetimepicker.min.js"></script>
        <script src="{{ asset('template') }}/assets/js/plugin/datatables/datatables.min.js"></script>
        <script>
            $('#basic-datatables').DataTable({});
            // Initial datepicker
            $('#tgl_ketersediaan_unit').datetimepicker({
                format: 'MM/DD/YYYY',
            });
            $('#ketersediaan_unit').datetimepicker({
                format: 'MM/DD/YYYY',
            });
            $('#tgl_pengiriman').datetimepicker({
                format: 'MM/DD/YYYY',
            });
            // End

            $('#modal-tgl-form').on("submit", function(event) {
                event.preventDefault();

                const req_id = document.getElementById('id_kkb')
                const req_date = document.getElementById('tgl_ketersediaan_unit')

                if (req_date == '') {
                    showError(req_date, 'Tanggal ketersediaan unit harus dipilih.');
                    return false;
                }

                $.ajax({
                    type: "POST",
                    url: "{{ route('kredit.set_tgl_ketersediaan_unit') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id_kkb: req_id.value,
                        date: req_date.value,
                    },
                    success: function(data) {
                        console.log(data);
                        if (Array.isArray(data.error)) {
                            showError(req_date, data.error[0])
                        } else {
                            if (data.status == 'success') {
                                SuccessMessage(data.message);
                            } else {
                                ErrorMessage(data.message)
                            }
                            $('#tglModal').modal().hide()
                            $('body').removeClass('modal-open');
                            $('.modal-backdrop').remove();
                        }
                    },
                    error: function(e) {
                        console.log(e)
                        ErrorMessage('Terjadi kesalahan')
                    }
                })
            })

            function uploadBuktiPembayaran(id) {
                $('#modal-bukti-pembayaran #id_kkb').val(id);
            }

            function setPenyerahan(id) {
                $('#modal-tgl-penyerahan #id_kkb').val(id);
            }

            function uploadPolis(id) {
                $('#modal-polis #id_kkb').val(id);
            }

            function uploadBpkb(id) {
                $('#modal-bpkb #id_kkb').val(id);
            }

            function uploadStnk(id) {
                $('#modal-stnk #id_kkb').val(id);
            }

            $('#modal-bukti-pembayaran').on("submit", function(e) {
                e.preventDefault();

                const req_id = document.getElementById('id_kkb')
                const req_file = document.getElementById('bukti_pembayaran_scan')
                var formData = new FormData($(this)[0]);

                $.ajax({
                    type: "POST",
                    url: "{{ route('kredit.upload_bukti_pembayaran') }}",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        console.log(data)
                        if (Array.isArray(data.error)) {
                            for (var i = 0; i < data.error.length; i++) {
                                var message = data.error[i];
                                if (message.toLowerCase().includes('bukti_pembayaran_scan'))
                                    showError(req_image, message)
                            }
                        } else {
                            if (data.status == 'success') {
                                SuccessMessage(data.message);
                            } else {
                                ErrorMessage(data.message)
                            }
                            $('#buktiPembayaranModal').modal().hide()
                            $('body').removeClass('modal-open');
                            $('.modal-backdrop').remove();
                        }
                    },
                    error: function(e) {
                        console.log(e)
                        ErrorMessage('Terjadi kesalahan')
                    }
                })
            })

            $('#modal-tgl-penyerahan').on("submit", function(event) {
                event.preventDefault();

                const req_id = document.getElementById('id_kkb')
                const req_date = document.getElementById('tgl_pengiriman')
                const req_image = document.getElementById('upload_penyerahan_unit')
                var formData = new FormData($(this)[0]);

                $.ajax({
                    type: "POST",
                    url: "{{ route('kredit.set_tgl_penyerahan_unit') }}",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        if (Array.isArray(data.error)) {
                            for (var i = 0; i < data.error.length; i++) {
                                var message = data.error[i];
                                if (message.toLowerCase().includes('tanggal'))
                                    showError(req_date, message)
                                if (message.toLowerCase().includes('gambar'))
                                    showError(req_image, message)
                            }
                        } else {
                            if (data.status == 'success') {
                                SuccessMessage(data.message);
                            } else {
                                ErrorMessage(data.message)
                            }
                            $('#tglModalPenyerahan').modal().hide()
                            $('body').removeClass('modal-open');
                            $('.modal-backdrop').remove();
                        }
                    },
                    error: function(e) {
                        console.log(e)
                        ErrorMessage('Terjadi kesalahan')
                    }
                })
            })

            $('#modal-stnk').on("submit", function(event) {
                event.preventDefault();

                const req_id = document.getElementById('id_kkb')
                const req_no = document.getElementById('no_stnk')
                const req_file = document.getElementById('stnk_scan')
                var formData = new FormData($(this)[0]);

                if (req_no == '') {
                    showError(req_no, 'Nomor harus diisi.');
                    return false;
                }

                $.ajax({
                    type: "POST",
                    url: "{{ route('kredit.upload_stnk') }}",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        if (Array.isArray(data.error)) {
                            for (var i = 0; i < data.error.length; i++) {
                                var message = data.error[i];
                                if (message.toLowerCase().includes('no_stnk'))
                                    showError(req_no, message)
                                if (message.toLowerCase().includes('scan'))
                                    showError(req_file, message)
                            }
                        } else {
                            if (data.status == 'success') {
                                SuccessMessage(data.message);
                            } else {
                                ErrorMessage(data.message)
                            }
                            $('#uploadStnkModal').modal().hide()
                            $('body').removeClass('modal-open');
                            $('.modal-backdrop').remove();
                        }
                    },
                    error: function(e) {
                        console.log(e)
                        ErrorMessage('Terjadi kesalahan')
                    }
                })
            })

            $('#modal-stnkbpkb').on("submit", function(event) {
                event.preventDefault();

                const req_id = document.getElementById('id_kkb')
                const req_no = document.getElementById('no_bpkb')
                const req_file = document.getElementById('bpkb_scan')
                var formData = new FormData($(this)[0]);

                if (req_no == '') {
                    showError(req_no, 'Nomor harus diisi.');
                    return false;
                }

                $.ajax({
                    type: "POST",
                    url: "{{ route('kredit.upload_bpkb') }}",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        if (Array.isArray(data.error)) {
                            for (var i = 0; i < data.error.length; i++) {
                                var message = data.error[i];
                                if (message.toLowerCase().includes('no_bpkb'))
                                    showError(req_date, message)
                                if (message.toLowerCase().includes('bpkb_scan'))
                                    showError(req_image, message)
                            }
                        } else {
                            if (data.status == 'success') {
                                SuccessMessage(data.message);
                            } else {
                                ErrorMessage(data.message)
                            }
                            $('#uploadBpkbModal').modal().hide()
                            $('body').removeClass('modal-open');
                            $('.modal-backdrop').remove();
                        }
                    },
                    error: function(e) {
                        console.log(e)
                        ErrorMessage('Terjadi kesalahan')
                    }
                })
            })

            // Modal
            $('body').on('click', '.confirm-police', function(e) {
                const data_id = $(this).data('id-doc')
                const data_category_doc_id = $(this).data('id-category')

                $('#confirm_id').val(data_id)
                $('#confirm_id_category').val(data_category_doc_id)
            })
            $('body').on('click', '.confirm-stnk', function(e) {
                const data_id = $(this).data('id-doc')
                const data_category_doc_id = $(this).data('id-category')

                $('#confirm_id').val(data_id)
                $('#confirm_id_category').val(data_category_doc_id)
            })

            $('.confirm-bukti-pembayaran').on('click', function(e) {
                const data_id = $(this).data('id-doc')
                const data_category_doc_id = $(this).data('id-category')
                const file_bukti = $(this).data('file') ? $(this).data('file') : ''
                var path_file = "{{ asset('storage') }}" + "/dokumentasi-bukti-pembayaran/" + file_bukti +
                    "#navpanes=0";
                $("#preview_bukti_tf").attr("src", path_file);
                $('#confirm_id').val(data_id)
                $('#confirm_id_category').val(data_category_doc_id)
            })

            // Imbal Jasa
            $('.upload-imbal-jasa').on('click', function(e) {
                const data_id = $(this).data('id')
                $('#id_kkbimbaljasa').val(data_id)
            })
            $('.confirm-imbal-jasa').on('click', function(e) {
                const data_id = $(this).data('id')
                const tanggal = $(this).data('tanggal')
                const file_bukti = $(this).data('file') ? $(this).data('file') : ''
                var path_file = "{{ asset('storage') }}" + "/dokumentasi-imbal-jasa/" + file_bukti;

                $("#preview_imbal-jasa").attr("src", path_file);
                $('#id_cat').val(data_id)
                $('#tgl-upload-imbal-jasa').html(tanggal)
            })

            $('#modal-imbal-jasa-form').submit(function(e) {
                e.preventDefault()
                const req_id = document.getElementById('id_kkbimbaljasa')
                const req_file = document.getElementById('file_imbal_jasa')
                var formData = new FormData($(this)[0]);
                $.ajax({
                    type: "POST",
                    url: "{{ route('kredit.upload_imbal_jasa') }}",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        if (Array.isArray(data.error)) {
                            for (var i = 0; i < data.error.length; i++) {
                                var message = data.error[i];
                                if (message.toLowerCase().includes('no_bpkb'))
                                    showError(req_date, message)
                                if (message.toLowerCase().includes('bpkb_scan'))
                                    showError(req_image, message)
                            }
                        } else {
                            if (data.status == 'success') {
                                SuccessMessage(data.message);
                                // console.log(data.message)
                            } else {
                                ErrorMessage(data.message)
                                // console.log(data.message)
                            }
                            $('#uploadImbalJasaModal').modal().hide()
                            $('body').removeClass('modal-open');
                            $('.modal-backdrop').remove();
                        }
                    },
                    error: function(e) {
                        console.log(e)
                        // ErrorMessage('Terjadi kesalahan')
                    }
                });
            });
            $('#confirm-form-imbal-jasa').on('submit', function(e) {
                e.preventDefault()
                const req_id = $('#id_cat').val()

                $.ajax({
                    type: "POST",
                    url: "{{ route('kredit.confirm-imbal-jasa') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: req_id,
                    },
                    success: function(data) {
                        if (Array.isArray(data.error)) {
                            console.log(data.error)
                            /*for (var i = 0; i < data.error.length; i++) {
                                var message = data.error[i];
                                if (message.toLowerCase().includes('id'))
                                    showError(req_id, message)
                                if (message.toLowerCase().includes('category_id'))
                                    showError(req_category_doc_id, message)
                            }*/
                        } else {
                            if (data.status == 'success') {
                                SuccessMessage(data.message);
                            } else {
                                ErrorMessage(data.message)
                            }
                            $('#modal-imbal-jasa-form').modal().hide()
                            $('body').removeClass('modal-open');
                            $('.modal-backdrop').remove();
                        }
                    }
                })
            })
            // Cabang
            $('#confirm-form').on('submit', function(e) {
                e.preventDefault()
                const req_id = $('#confirm_id').val()
                const req_category_doc_id = $('#confirm_id_category').val()

                $.ajax({
                    type: "POST",
                    url: "{{ route('kredit.confirm_document') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: req_id,
                        category_id: req_category_doc_id
                    },
                    success: function(data) {
                        if (Array.isArray(data.error)) {
                            console.log(data.error)
                            /*for (var i = 0; i < data.error.length; i++) {
                                var message = data.error[i];
                                if (message.toLowerCase().includes('id'))
                                    showError(req_id, message)
                                if (message.toLowerCase().includes('category_id'))
                                    showError(req_category_doc_id, message)
                            }*/
                        } else {
                            if (data.status == 'success') {
                                SuccessMessage(data.message);
                            } else {
                                ErrorMessage(data.message)
                            }
                            // $('#uploadPolisModal').modal().hide()
                            // $('body').removeClass('modal-open');
                            // $('.modal-backdrop').remove();
                        }
                    }
                })
            })

            // Vendor
            $('#confirm-form-vendor').on('submit', function(e) {
                e.preventDefault()
                const req_id = $('#confirm_id').val()
                const req_category_doc_id = $('#confirm_id_category').val()

                $.ajax({
                    type: "POST",
                    url: "{{ route('kredit.confirm_document_vendor') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: req_id,
                        category_id: req_category_doc_id
                    },
                    success: function(data) {
                        if (Array.isArray(data.error)) {
                            console.log(data.error)
                            /*for (var i = 0; i < data.error.length; i++) {
                                var message = data.error[i];
                                if (message.toLowerCase().includes('id'))
                                    showError(req_id, message)
                                if (message.toLowerCase().includes('category_id'))
                                    showError(req_category_doc_id, message)
                            }*/
                        } else {
                            if (data.status == 'success') {
                                SuccessMessage(data.message);
                            } else {
                                ErrorMessage(data.message)
                            }
                        }
                    }
                })
            })

            // Cabang - Confirm penyerahan unit
            $('#confirm-form-penyerahan-unit').on('submit', function(e) {
                e.preventDefault()
                const req_id = $('#confirm_id').val()
                const req_category_doc_id = $('#confirm_id_category').val()

                $.ajax({
                    type: "POST",
                    url: "{{ route('kredit.confirm_penyerahan_unit') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: req_id,
                        category_id: req_category_doc_id
                    },
                    success: function(data) {
                        if (Array.isArray(data.error)) {
                            console.log(data.error)
                            /*for (var i = 0; i < data.error.length; i++) {
                                var message = data.error[i];
                                if (message.toLowerCase().includes('id'))
                                    showError(req_id, message)
                                if (message.toLowerCase().includes('category_id'))
                                    showError(req_category_doc_id, message)
                            }*/
                        } else {
                            if (data.status == 'success') {
                                SuccessMessage(data.message);
                            } else {
                                ErrorMessage(data.message)
                            }
                        }
                    }
                })
            })

            $(document).ready(function() {
                $('a[data-toggle=modal], button[data-toggle=modal]').click(function() {
                    var data_id_kkb = '';
                    if (typeof $(this).data('id_kkb') !== 'undefined') {
                        data_id_kkb = $(this).data('id_kkb');
                    }
                    $('#id_kkb').val(data_id_kkb);
                })

            });

            function SuccessMessage(message) {
                swal("Berhasil!", message, {
                    icon: "success",
                    timer: 3000,
                    closeOnClickOutside: false
                }).then(() => {
                    location.reload();
                });
                setTimeout(function() {
                    location.reload();
                }, 3000);
            }

            function ErrorMessage(message) {
                swal("Gagal!", message, {
                    icon: "error",
                    // timer: 3000,
                    closeOnClickOutside: false
                }).then(() => {
                    location.reload();
                });
                // setTimeout(function() {
                //     location.reload();
                // }, 3000);
            }

            function showError(input, message) {
                const inputGroup = input.parentElement;
                const formGroup = inputGroup.parentElement;
                const errorSpan = formGroup.querySelector('.error');

                formGroup.classList.add('has-error');
                errorSpan.innerText = message;
                input.focus();
                input.value = '';
            }
        </script>
    @endpush
@endsection
