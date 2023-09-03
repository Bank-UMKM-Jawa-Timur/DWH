@extends('layout.master')
@section('modal')
<!-- Modal-Filter -->
@include('pages.kredit.modal.filter-modal')
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
<<<<<<< HEAD
                    </h2>
                </div>
                <div class="table-action flex lg:justify-normal justify-center p-2 gap-2">
                    <button class="px-6 py-2 bg-theme-primary/10 flex gap-3 rounded text-theme-primary">
                        <span class="lg:mt-1.5 mt-0">
                            @include('components.svg.reset')
                        </span>
                        <span class="lg:block hidden"> Reset </span>
=======
                        <div>
                            <button type="button" class="btn btn-sm btn-primary" id="buttonFilter" data-toggle="modal"
                                data-target="#filter">
                                Filter Data
                            </button>
                            @if (Request()->query() != null)
                                <a href="/kredit" type="button" class="btn btn-sm btn-warning">
                                    Reset Filter
                                </a>
                            @endif
                        </div>

                    </div>
                    <div class="card-body">
                        <form id="form" action="" method="get">
                            @include('pages.kredit.modal.filter-modal')
                            <input type="hidden" name="page" value="{{isset($_GET['page']) ? $_GET['page'] : 1}}">
                            <div class="d-flex justify-content-between" style="padding-left: 15px;padding-right: 15px;">
                                <div>
                                    <div class="form-inline">
                                        <label>Show</label>
                                        &nbsp;
                                        <select class="form-control form-control-sm" name="page_length" id="page_length" >
                                            <option value="5" {{ Request::get('page_length') == '5' ? 'selected' : '' }}>5</option>
                                            <option value="10" {{ Request::get('page_length') == '10' ? 'selected' : '' }}>10</option>
                                            <option value="15" {{ Request::get('page_length') == '15' ? 'selected' : '' }}>15</option>
                                            <option value="20" {{ Request::get('page_length') == '20' ? 'selected' : '' }}>20</option>
                                            <option value="all" {{ Request::get('page_length') == 'all' ? 'selected' : '' }}>All</option>
                                        </select>
                                        &nbsp;
                                        <label>entries</label>
                                    </div>
                                </div>
                                <div>
                                    <div class="form-inline">
                                        <label>Search : </label>
                                        &nbsp;
                                        <input type="search" class="form-control form-control-sm"
                                            name="query" id="query" value="{{ old('query', Request::get('query')) }}">
                                    </div>
                                </div>
                            </div>
                        </form>
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
                                                    {{ array_key_exists('nama', $item->detail) ? $item->detail['nama'] : '-' }}
                                                @else
                                                    undifined
                                                @endif
                                            </td>
                                            <td class="@if ($item->detail) link-po @endif text-center">
                                                @if ($buktiPembayaran)
                                                    @if ($item->detail)
                                                        <a class="open-po" data-toggle="modal" data-target="#detailPO"
                                                            data-nomorPo="{{ array_key_exists('no_po', $item->detail) ? $item->detail['no_po'] : '' }}"
                                                            data-tanggalPo="{{ array_key_exists('tanggal', $item->detail) ? date('d-m-Y', strtotime($item->detail['tanggal'])) : '' }}"
                                                            data-filepo="{{ array_key_exists('po', $item->detail) ? config('global.los_asset_url') . $item->detail['po'] : '' }}">
                                                            {{ array_key_exists('no_po', $item->detail) ? $item->detail['no_po'] : '' }}</a>
                                                    @else
                                                        -
                                                    @endif
                                                @else
                                                    @if ($item->detail)
                                                        <a class="open-po" data-toggle="modal" data-target="#detailPO"
                                                            data-nomorPo="{{ array_key_exists('no_po', $item->detail) ? $item->detail['no_po'] : '' }}"
                                                            data-tanggalPo="{{ array_key_exists('tanggal', $item->detail) ? date('d-m-Y', strtotime($item->detail['tanggal'])) : '' }}"
                                                            data-filepo="{{ array_key_exists('po', $item->detail) ? config('global.los_asset_url') . $item->detail['po'] : '' }}">
                                                            {{ array_key_exists('no_po', $item->detail) ? $item->detail['no_po'] : '' }}</a>
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
                                                    @if (\Session::get(config('global.role_id_session')) == 3)
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
                                                            @if (\Session::get(config('global.role_id_session')) == 3)
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
                                                            @if (\Session::get(config('global.role_id_session')) == 3)
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
                                                            @if (\Session::get(config('global.role_id_session')) == 3)
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
                                                            @if (\Session::get(config('global.role_id_session')) == 3)
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
                                                @if (\Session::get(config('global.role_id_session')) == 3)
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
                                                            @if (\Session::get(config('global.role_id_session')) == 3)
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
                                                                    @if (\Session::get(config('global.role_id_session')) == 2)
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
                                                        @if (\Session::get(config('global.role_id_session')) == 3 && $penyerahanUnit)
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
                                                        @if (\Session::get(config('global.role_id_session')) == 2)
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
                                                        @if (\Session::get(config('global.role_id_session')) == 2)
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
                            @if($data instanceof \Illuminate\Pagination\LengthAwarePaginator )
                            {{ $data->links('pagination::bootstrap-5') }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
>>>>>>> develop
                    </button>
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
                <table class="table-auto w-full">
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
                                        Selangkapnya
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
                                        Selangkapnya
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
                                        Selangkapnya
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
                                        Selangkapnya
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
                                        Selangkapnya
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
                </table>
            </div>
<<<<<<< HEAD
            <div class="footer-table p-3 text-theme-text lg:flex lg:space-y-0 space-y-10 justify-between">
                <div>
                    <p class="mt-3 text-sm">Menampilkan 1 - 5 dari 100 Data</p>
=======
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
                    @if (\Session::get(config('global.role_id_session')) == 3)
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
>>>>>>> develop
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
