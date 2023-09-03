<table class="table-auto w-full">
    <thead>
        <tr>
            <th>No</th>
            <th width="150">Nama</th>
            <th>PO</th>
            <th>Ketersediaan Unit</th>
            <th>Bukti Pembayaran</th>
            <th>Penyerahan Unit</th>
            @foreach ($documentCategories as $item)
                <th>{{ $item->name }}</th>
            @endforeach
            <th>Bukti Pembayaran Imbal Jasa</th>
            <th>Imbal Jasa + 2% Pajak</th>
            <th>Status</th>
            <th>Aksi</th>
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
                        {{ array_key_exists('nama', $item->detail) ? $item->detail['nama'] : '-' }}
                    @else
                        undifined
                    @endif
                </td>
                <td class="@if ($item->detail) link-po @endif">
                    @if ($buktiPembayaran)
                        @if ($item->detail)
                        <button class="toggle-modal" data-target-id="modalPO"
                            data-nomorPo="{{ array_key_exists('no_po', $item->detail) ? $item->detail['no_po'] : '' }}"
                            data-tanggalPo="{{ array_key_exists('tanggal', $item->detail) ? date('d-m-Y', strtotime($item->detail['tanggal'])) : '' }}"
                            data-filepo="{{ array_key_exists('po', $item->detail) ? config('global.los_asset_url') . $item->detail['po'] : '' }}">
                            {{ array_key_exists('no_po', $item->detail) ? $item->detail['no_po'] : '' }}
                        </button>
                        @else
                            -
                        @endif
                    @else
                        @if ($item->detail)
                            <button class="toggle-modal" data-target-id="modalPO"
                            data-nomorPo="{{ array_key_exists('no_po', $item->detail) ? $item->detail['no_po'] : '' }}"
                            data-tanggalPo="{{ array_key_exists('tanggal', $item->detail) ? date('d-m-Y', strtotime($item->detail['tanggal'])) : '' }}"
                            data-filepo="{{ array_key_exists('po', $item->detail) ? config('global.los_asset_url') . $item->detail['po'] : '' }}">
                                {{ array_key_exists('no_po', $item->detail) ? $item->detail['no_po'] : '' }}
                            </button>
                        @else
                            -
                        @endif
                    @endif
                </td>
                <td>
                    @if (\Session::get(config('global.role_id_session')) == 3)
                        @if (!$item->tgl_ketersediaan_unit)
                        <button class="toggle-modal" data-target-id="modalAturKetersedian"
                            data-id_kkb="{{ $item->kkb_id }}">
                            Atur
                        </button>
                        @else
                            {{ date('d-m-Y', strtotime($item->tgl_ketersediaan_unit)) }}
                        @endif
                    @elseif ($item->tgl_ketersediaan_unit)
                        {{ date('d-m-Y', strtotime($item->tgl_ketersediaan_unit)) }}
                    @else
                        <span class="text-danger">Menunggu tanggal ketersediaan unit</span>
                    @endif
                </td>
                <td>
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
                            @if (!$buktiPembayaran && \Session::get(config('global.role_id_session')) != 3)
                                <button class="toggle-modal" data-target-id="modalUploadBuktiPembayaran"
                                    data-id_kkb="{{ $item->kkb_id }}">
                                    Bayar
                                </button>
                            @else
                                @if ($buktiPembayaran)
                                    @if (!$buktiPembayaran->is_confirm)
                                        @if (\Session::get(config('global.role_id_session')) == 3)
                                            <button class="toggle-modal" data-target-id="modalConfirmBuktiPembayaran"
                                                data-file="{{ $buktiPembayaran->file }}"
                                                data-tanggal="{{ $buktiPembayaran->date }}"
                                                data-id-category="{{$buktiPembayaran->document_category_id}}"
                                                data-id-doc="{{ $buktiPembayaran ? $buktiPembayaran->id : 0 }}">
                                                Konfirmasi
                                            </button>
                                        @else
                                            <button class="toggle-modal" data-target-id="modalBuktiPembayaran"
                                                data-file="{{ $buktiPembayaran->file }}"
                                                data-tanggal="{{ $buktiPembayaran->date }}"
                                                data-confirm="{{ $buktiPembayaran->is_confirm }}"
                                                data-confirm_at="{{ $buktiPembayaran->confirm_at ? date('d-m-Y', strtotime($buktiPembayaran->confirm_at)) : '-' }}">
                                                Menunggu Konfirmasi Vendor
                                            </button>
                                        @endif
                                    @elseif ($buktiPembayaran->is_confirm)
                                        <button class="toggle-modal" data-target-id="modalBuktiPembayaran"
                                            data-file="{{ $buktiPembayaran->file }}"
                                            data-tanggal="{{ $buktiPembayaran->date }}"
                                            data-confirm="{{ $buktiPembayaran->is_confirm }}"
                                            data-confirm_at="{{ $buktiPembayaran->confirm_at ? date('d-m-Y', strtotime($buktiPembayaran->confirm_at)) : '-' }}">
                                            Selesai
                                        </button>
                                        {{--  <a class="m-0 bukti-pembayaran-modal"
                                            style="cursor: pointer; text-decoration: underline;"
                                            data-toggle="modal"
                                            data-target="#previewBuktiPembayaranModal"
                                            data-file="{{ $buktiPembayaran->file }}"
                                            data-confirm="{{ $buktiPembayaran->is_confirm }}"
                                            data-tanggal="{{ date('d-m-Y', strtotime($buktiPembayaran->date)) }}"
                                            data-confirm_at="{{ date('d-m-Y', strtotime($buktiPembayaran->confirm_at)) }}">Selesai</a>  --}}
                                    @endif
                                @else
                                    Menunggu cabang mengunggah bukti pembayaran
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
                            @if ($penyerahanUnit->is_confirm)
                                <a style="text-decoration: underline; cursor: pointer;"
                                    class="toggle-modal" data-target-id="confirmModalPenyerahanUnit"
                                    data-id-category="2"
                                    data-id-doc="{{ $penyerahanUnit ? $penyerahanUnit->id : 0 }}"
                                    data-file="@isset($penyerahanUnit->file){{ $penyerahanUnit->file }}@endisset"
                                    data-confirm="{{ $penyerahanUnit->is_confirm }}"
                                    data-tanggal="{{ date('d-m-Y', strtotime($penyerahanUnit->date)) }}"
                                    data-confirm_at="{{ date('d-m-Y', strtotime($penyerahanUnit->confirm_at)) }}"
                                    href="#">{{ date('d-m-Y', strtotime($penyerahanUnit->date)) }}</a>
                            @else
                                @if (\Session::get(config('global.role_id_session')) == 3)
                                    <span>Menunggu konfirmasi cabang</span>
                                @else
                                    <a class="toggle-modal"
                                        data-target-id="confirmModalPenyerahanUnit"
                                        data-id-category="2"
                                        data-id-doc="{{ $penyerahanUnit ? $penyerahanUnit->id : 0 }}"
                                        data-file="@isset($penyerahanUnit->file){{ $penyerahanUnit->file }}@endisset"
                                        data-confirm="{{ $penyerahanUnit->is_confirm }}"
                                        data-tanggal="{{ date('d-m-Y', strtotime($penyerahanUnit->date)) }}"
                                        data-confirm_at="{{ $penyerahanUnit->confirm_at ? date('d-m-Y', strtotime($penyerahanUnit->confirm_at)) : '-' }}">Konfirmasi</a>
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
                <td>
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
                <td>
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
                <td>
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
                <td>
                    @if (\Session::get(config('global.role_id_session')) == 3)
                        {{--  vendor  --}}
                        @if ($imbalJasa)
                            @if (!$imbalJasa->is_confirm)
                                <a style="cursor: pointer; text-decoration: underline;"
                                    class="confirm-imbal-jasa toggle-modal" data-target-id="confirmImbalJasa"
                                    data-id="{{ $imbalJasa->id }}"
                                    data-file="{{ $imbalJasa->file }}"
                                    data-tanggal="{{ \Carbon\Carbon::parse($imbalJasa->created_at)->format('d-m-Y') }}"
                                    data-confirm="{{ $imbalJasa->is_confirm }}"
                                    href="#confirmModalImbalJasa">Konfirmasi Bukti Pembayaran</a>
                            @elseif ($imbalJasa->is_confirm)
                                <a class="bukti-pembayaran-modal toggle-modal"
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
                                    class="toggle-modal"
                                    data-target-id="uploadImbalJasaModal"
                                    data-id="{{ $item->id }}">Bayar</a>
                            @else
                                @if (!$imbalJasa->is_confirm)
                                    <p class="m-0">Menunggu Konfirmasi Vendor</p>
                                @elseif ($imbalJasa->is_confirm)
                                    <a class="bukti-pembayaran-modal toggle-modal"
                                        style="cursor: pointer; text-decoration: underline;"
                                        data-target-id="confirmImbalJasa"
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
                <td>
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
                <td>
                    <div class="dropdown">
                        <button class="px-4 py-2 bg-theme-btn/10 rounded text-theme-btn">
                            Selengkapnya
                        </button>
                        <ul class="dropdown-menu w-full">
                            @if ($item->tgl_ketersediaan_unit)
                                @if ($buktiPembayaran)
                                    @if (!$penyerahanUnit && $buktiPembayaran->is_confirm && Auth::user()->vendor_id)
                                        <li>
                                            <a href="#" class="item-dropdown toggle-modal"
                                                data-target-id="modalUploadBuktiPenyerahanUnit"
                                                data-id_kkb="{{ $item->kkb_id }}">Kirim Unit</a>
                                        </li>
                                    @endif
                                @endif
                            @endif
                            {{--  Upload Berkas  --}}
                            @if (\Session::get(config('global.role_id_session')) == 3 && $penyerahanUnit)
                                @if ($penyerahanUnit->is_confirm)
                                    @if (!isset($stnk->file) || !isset($polis->file) || !isset($bpkb->is_confirm))
                                        {{--  Vendor  --}}
                                        <li>
                                            <a class="item-dropdown toggle-modal"
                                                data-target-id="uploadBerkasModal"
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
                                                href="#">
                                                Upload Berkas
                                            </a>
                                        </li>
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
                                        <li>
                                            <a class="item-dropdown toggle-modal"
                                                data-target-id="uploadBerkasModal"
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
                                                href="#">
                                                Konfirmasi Berkas
                                            </a>
                                        </li>
                                    @endif
                                @endif
                            @endif
                            @if (\Session::get(config('global.role_id_session')) == 2)
                                {{--  @if ($stnk && $polis && $bpkb && !$imbalJasa)  --}}
                                @if (isset($stnk->is_confirm) &&
                                        !$stnk->is_confirm &&
                                        (isset($polis->is_confirm) && !$polis->is_confirm) &&
                                        (isset($bpkb->is_confirm) && !$bpkb->is_confirm))
                                    <li>
                                        <a href="#" class="item-dropdown upload-imbal-jasa"
                                            data-toggle="modal"
                                            data-target="#uploadImbalJasaModal"
                                            data-id="{{ $item->id }}">Upload bukti imbal jasa</a>
                                    </li>
                                @endif
                            @else
                            @endif
                            <li>
                                <a class="item-dropdown toggle-modals"
                                    data-target-id="modalDetailPo" data-id="{{ $item->id }}"
                                    href="#">Detail</a>
                            </li>
                        </ul>
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
