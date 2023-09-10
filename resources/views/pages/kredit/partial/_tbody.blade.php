@forelse ($data as $item)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>
            @if ($item->detail)
                {{ array_key_exists('nama', $item->detail) ? $item->detail['nama'] : '-' }}
            @else
                undifined
            @endif
        </td>
        @if ($role_id == 3)
            <td>{{ array_key_exists('cabang', $item->detail) ? $item->detail['cabang'] : '-' }}</td>
        @endif
        <td class="@if ($item->detail) link-po @endif">
            @if ($item->bukti_pembayaran)
                @if ($item->detail)
                <button class="toggle-modal underline" data-target-id="modalPO"
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
                    <button class="toggle-modal underline" data-target-id="modalPO"
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
                @if ($is_kredit_page)
                    @if (!$item->tgl_ketersediaan_unit)
                        <button class="toggle-modal underline" data-target-id="modalAturKetersedian"
                            data-id_kkb="{{ $item->kkb_id }}">
                            Atur
                        </button>
                    @else
                        {{ date('d-m-Y', strtotime($item->tgl_ketersediaan_unit)) }}
                    @endif
                @else
                    @if (!$item->tgl_ketersediaan_unit)
                        <span class="text-danger">Menunggu tanggal ketersediaan unit</span>
                    @else
                        {{ date('d-m-Y', strtotime($item->tgl_ketersediaan_unit)) }}
                    @endif
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
                    @if ($item->bukti_pembayaran)
                        @if ($is_kredit_page)
                            @if (!$item->bukti_pembayaran->is_confirm)
                                <a style="cursor: pointer; text-decoration: underline;"
                                    class="confirm-bukti-pembayaran toggle-modal" data-target-id="modalConfirmBuktiPembayaran"
                                    data-id-category="1"
                                    data-tanggal="{{ date('d-m-Y', strtotime($item->bukti_pembayaran->date)) }}"
                                    data-id-doc="{{ $item->bukti_pembayaran ? $item->bukti_pembayaran->id : 0 }}"
                                    data-file="@isset($item->bukti_pembayaran->file){{ $item->bukti_pembayaran->file }}@endisset"
                                    href="#">Konfirmasi</a>
                            @elseif ($item->bukti_pembayaran->is_confirm)
                                <a class="m-0 bukti-pembayaran-modal toggle-modal"
                                    style="cursor: pointer; text-decoration: underline;"
                                    data-target-id="modalConfirmBuktiPembayaran"
                                    data-file="{{ $item->bukti_pembayaran->file }}"
                                    data-confirm="{{ $item->bukti_pembayaran->is_confirm }}"
                                    data-tanggal="{{ date('d-m-Y', strtotime($item->bukti_pembayaran->date)) }}"
                                    data-confirm_at="{{ date('d-m-Y', strtotime($item->bukti_pembayaran->confirm_at)) }}">Selesai</a>
                            @else
                                Menunggu Pembayaran dari Cabang
                            @endif
                        @else
                            @if (!$item->bukti_pembayaran->is_confirm)
                                Menunggu konfirmasi dari Cabang
                            @elseif ($item->bukti_pembayaran->is_confirm)
                                <a class="m-0 bukti-pembayaran-modal toggle-modal"
                                    style="cursor: pointer; text-decoration: underline;"
                                    data-target-id="modalConfirmBuktiPembayaran"
                                    data-file="{{ $item->bukti_pembayaran->file }}"
                                    data-confirm="{{ $item->bukti_pembayaran->is_confirm }}"
                                    data-tanggal="{{ date('d-m-Y', strtotime($item->bukti_pembayaran->date)) }}"
                                    data-confirm_at="{{ date('d-m-Y', strtotime($item->bukti_pembayaran->confirm_at)) }}">Selesai</a>
                            @else
                                Menunggu Pembayaran dari Cabang
                            @endif
                        @endif
                    @else
                        Menunggu Pembayaran dari Cabang
                    @endif
                @else
                    {{--  role selain vendor  --}}
                    @if (!$item->bukti_pembayaran && \Session::get(config('global.role_id_session')) != 3)
                        @if ($is_kredit_page)
                            @if (\Session::get(config('global.user_role_session')) == $staf_analisa_kredit_role)
                                <button class="toggle-modal underline" data-target-id="modalUploadBuktiPembayaran"
                                    data-id_kkb="{{ $item->kkb_id }}">
                                    Bayar
                                </button>
                            @else
                                <span>Menunggu pembayaran</span>
                            @endif
                        @else
                            -
                        @endif
                    @else
                        @if ($item->bukti_pembayaran)
                            @if ($is_kredit_page)
                                @if (!$item->bukti_pembayaran->is_confirm)
                                    @if (\Session::get(config('global.role_id_session')) == 3)
                                        <button class="toggle-modal" data-target-id="modalConfirmBuktiPembayaran"
                                            data-file="{{ $item->bukti_pembayaran->file }}"
                                            data-tanggal="{{ $item->bukti_pembayaran->date }}"
                                            data-id-category="{{$item->bukti_pembayaran->document_category_id}}"
                                            data-id-doc="{{ $item->bukti_pembayaran ? $item->bukti_pembayaran->id : 0 }}">
                                            Konfirmasi
                                        </button>
                                    @else
                                        <button class="toggle-modal underline" data-target-id="modalBuktiPembayaran"
                                            data-file="{{ $item->bukti_pembayaran->file }}"
                                            data-tanggal="{{ $item->bukti_pembayaran->date }}"
                                            data-confirm="{{ $item->bukti_pembayaran->is_confirm }}"
                                            data-confirm_at="{{ $item->bukti_pembayaran->confirm_at ? date('d-m-Y', strtotime($item->bukti_pembayaran->confirm_at)) : '-' }}">
                                            Menunggu Konfirmasi Vendor
                                        </button>
                                    @endif
                                @elseif ($item->bukti_pembayaran->is_confirm)
                                    <button class="toggle-modal underline" data-target-id="modalBuktiPembayaran"
                                        data-file="{{ $item->bukti_pembayaran->file }}"
                                        data-tanggal="{{ $item->bukti_pembayaran->date }}"
                                        data-confirm="{{ $item->bukti_pembayaran->is_confirm }}"
                                        data-confirm_at="{{ $item->bukti_pembayaran->confirm_at ? date('d-m-Y', strtotime($item->bukti_pembayaran->confirm_at)) : '-' }}">
                                        Selesai
                                    </button>
                                @endif
                            @else
                                @if ($item->bukti_pembayaran->is_confirm)
                                    <button class="toggle-modal underline" data-target-id="modalBuktiPembayaran"
                                        data-file="{{ $item->bukti_pembayaran->file }}"
                                        data-tanggal="{{ $item->bukti_pembayaran->date }}"
                                        data-confirm="{{ $item->bukti_pembayaran->is_confirm }}"
                                        data-confirm_at="{{ $item->bukti_pembayaran->confirm_at ? date('d-m-Y', strtotime($item->bukti_pembayaran->confirm_at)) : '-' }}">
                                        Selesai
                                    </button>
                                @else
                                    -
                                @endif
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
                @if ($item->bukti_pembayaran)
                    @if ($is_kredit_page)
                        @if ($item->bukti_pembayaran->file)
                            @if ($item->penyerahan_unit)
                                @if ($item->penyerahan_unit['file'])
                                    @if (\Session::get(config('global.role_id_session')) == 3)
                                        @if ($item->penyerahan_unit['is_confirm'])
                                            <a style="text-decoration: underline; cursor: pointer;"
                                                class="toggle-modal" data-target-id="modalConfirmPenyerahanUnit"
                                                data-id-category="2"
                                                data-id-doc="{{ $item->penyerahan_unit ? $item->penyerahan_unit->id : 0 }}"
                                                data-file="@isset($item->penyerahan_unit['file']){{ $item->penyerahan_unit['file'] }}@endisset"
                                                data-confirm="{{ $item->penyerahan_unit['is_confirm'] }}"
                                                data-tanggal="{{ date('d-m-Y', strtotime($item->penyerahan_unit['date'])) }}"
                                                data-confirm_at="{{ $item->penyerahan_unit['confirm_at'] ? date('d-m-Y', strtotime($item->penyerahan_unit['confirm_at'])) : '-' }}"
                                                href="#">{{ date('d-m-Y', strtotime($item->penyerahan_unit['date'])) }}</a>
                                        @else
                                            <a style="text-decoration: underline; cursor: pointer;"
                                                class="toggle-modal" data-target-id="modalConfirmPenyerahanUnit"
                                                data-id-category="2"
                                                data-id-doc="{{ $item->penyerahan_unit ? $item->penyerahan_unit->id : 0 }}"
                                                data-file="@isset($item->penyerahan_unit['file']){{ $item->penyerahan_unit['file'] }}@endisset"
                                                data-confirm="{{ $item->penyerahan_unit['is_confirm'] }}"
                                                data-tanggal="{{ date('d-m-Y', strtotime($item->penyerahan_unit['date'])) }}"
                                                data-confirm_at="{{ $item->penyerahan_unit['confirm_at'] ? date('d-m-Y', strtotime($item->penyerahan_unit['confirm_at'])) : '-' }}"
                                                href="#">{{ date('d-m-Y', strtotime($item->penyerahan_unit['date'])) }}</a>
                                        @endif
                                    @elseif(\Session::get(config('global.role_id_session')) == 2 && \Session::get(config('global.user_role_session')) == $staf_analisa_kredit_role)
                                        @if ($item->penyerahan_unit['is_confirm'])
                                            <a style="text-decoration: underline; cursor: pointer;"
                                                class="toggle-modal" data-target-id="modalConfirmPenyerahanUnit"
                                                data-id-category="2"
                                                data-id-doc="{{ $item->penyerahan_unit ? $item->penyerahan_unit->id : 0 }}"
                                                data-file="@isset($item->penyerahan_unit['file']){{ $item->penyerahan_unit['file'] }}@endisset"
                                                data-confirm="1"
                                                data-tanggal="{{ date('d-m-Y', strtotime($item->penyerahan_unit['date'])) }}"
                                                data-confirm_at="{{ $item->penyerahan_unit['confirm_at'] ? date('d-m-Y', strtotime($item->penyerahan_unit['confirm_at'])) : '-' }}"
                                                href="#">{{ date('d-m-Y', strtotime($item->penyerahan_unit['date'])) }}</a>
                                        @else
                                            <a style="text-decoration: underline; cursor: pointer;"
                                                class="toggle-modal" data-target-id="modalConfirmPenyerahanUnit"
                                                data-id-category="2"
                                                data-id-doc="{{ $item->penyerahan_unit ? $item->penyerahan_unit->id : 0 }}"
                                                data-file="@isset($item->penyerahan_unit['file']){{ $item->penyerahan_unit['file'] }}@endisset"
                                                data-confirm="0"
                                                data-tanggal="{{ date('d-m-Y', strtotime($item->penyerahan_unit['date'])) }}"
                                                data-confirm_at="{{ $item->penyerahan_unit['confirm_at'] ? date('d-m-Y', strtotime($item->penyerahan_unit['confirm_at'])) : '-' }}"
                                                href="#">Konfirmasi</a>
                                        @endif
                                    @endif
                                @else
                                    
                                @endif
                            @else
                                <span class="text-info">Maksimal
                                    {{ date('d-m-Y', strtotime($item->bukti_pembayaran->date . ' +1 day')) }}</span>
                            @endif
                        @else
                            @if (\Session::get(config('global.role_id_session')) == 3 && \Session::get(config('global.user_role_session')) != $staf_analisa_kredit_role)
                                <span>Menunggu konfirmasi cabang</span>
                            @else
                                <a style="text-decoration: underline; cursor: pointer;"
                                    class="toggle-modal"
                                    data-target-id="modalConfirmPenyerahanUnit"
                                    data-id-category="2"
                                    data-id-doc="{{ $item->penyerahan_unit ? $item->penyerahan_unit->id : 0 }}"
                                    data-file="@isset($item->penyerahan_unit['file']){{ $item->penyerahan_unit['file'] }}@endisset"
                                    data-confirm="{{ $item->penyerahan_unit['is_confirm'] }}"
                                    data-tanggal="{{ date('d-m-Y', strtotime($item->penyerahan_unit['date'])) }}"
                                    data-confirm_at="{{ $item->penyerahan_unit['confirm_at'] ? date('d-m-Y', strtotime($item->penyerahan_unit['confirm_at'])) : '-' }}">Konfirmasi</a>
                            @endif
                        @endif
                    @else
                        @if ($item->bukti_pembayaran->file)
                            @if ($item->penyerahan_unit)
                                @if ($item->penyerahan_unit['file'])
                                    @if (\Session::get(config('global.role_id_session')) == 3)
                                        @if ($item->penyerahan_unit['is_confirm'])
                                            <a style="text-decoration: underline; cursor: pointer;"
                                                class="toggle-modal" data-target-id="modalConfirmPenyerahanUnit"
                                                data-id-category="2"
                                                data-id-doc="{{ $item->penyerahan_unit ? $item->penyerahan_unit->id : 0 }}"
                                                data-file="@isset($item->penyerahan_unit['file']){{ $item->penyerahan_unit['file'] }}@endisset"
                                                data-confirm="{{ $item->penyerahan_unit['is_confirm'] }}"
                                                data-tanggal="{{ date('d-m-Y', strtotime($item->penyerahan_unit['date'])) }}"
                                                data-confirm_at="{{ $item->penyerahan_unit['confirm_at'] ? date('d-m-Y', strtotime($item->penyerahan_unit['confirm_at'])) : '-' }}"
                                                href="#">{{ date('d-m-Y', strtotime($item->penyerahan_unit['date'])) }}</a>
                                        @else
                                            <a style="text-decoration: underline; cursor: pointer;"
                                                class="toggle-modal" data-target-id="modalConfirmPenyerahanUnit"
                                                data-id-category="2"
                                                data-id-doc="{{ $item->penyerahan_unit ? $item->penyerahan_unit->id : 0 }}"
                                                data-file="@isset($item->penyerahan_unit['file']){{ $item->penyerahan_unit['file'] }}@endisset"
                                                data-confirm="{{ $item->penyerahan_unit['is_confirm'] }}"
                                                data-tanggal="{{ date('d-m-Y', strtotime($item->penyerahan_unit['date'])) }}"
                                                data-confirm_at="{{ $item->penyerahan_unit['confirm_at'] ? date('d-m-Y', strtotime($item->penyerahan_unit['confirm_at'])) : '-' }}"
                                                href="#">{{ date('d-m-Y', strtotime($item->penyerahan_unit['date'])) }}</a>
                                        @endif
                                    @elseif(\Session::get(config('global.role_id_session')) == 2 && \Session::get(config('global.user_role_session')) == $staf_analisa_kredit_role)
                                        @if ($item->penyerahan_unit['is_confirm'])
                                            <a style="text-decoration: underline; cursor: pointer;"
                                                class="toggle-modal" data-target-id="modalConfirmPenyerahanUnit"
                                                data-id-category="2"
                                                data-id-doc="{{ $item->penyerahan_unit ? $item->penyerahan_unit->id : 0 }}"
                                                data-file="@isset($item->penyerahan_unit['file']){{ $item->penyerahan_unit['file'] }}@endisset"
                                                data-confirm="1"
                                                data-tanggal="{{ date('d-m-Y', strtotime($item->penyerahan_unit['date'])) }}"
                                                data-confirm_at="{{ $item->penyerahan_unit['confirm_at'] ? date('d-m-Y', strtotime($item->penyerahan_unit['confirm_at'])) : '-' }}"
                                                href="#">{{ date('d-m-Y', strtotime($item->penyerahan_unit['date'])) }}</a>
                                        @else
                                            Menunggu konfirmasi
                                        @endif
                                    @endif
                                @else
                                    
                                @endif
                            @else
                                <span class="text-info">Maksimal
                                    {{ date('d-m-Y', strtotime($item->bukti_pembayaran->date . ' +1 day')) }}</span>
                            @endif
                        @else
                            @if (\Session::get(config('global.role_id_session')) == 3 && \Session::get(config('global.user_role_session')) != $staf_analisa_kredit_role)
                                <span>Menunggu konfirmasi cabang</span>
                            @else
                                <a style="text-decoration: underline; cursor: pointer;"
                                    class="toggle-modal"
                                    data-target-id="modalConfirmPenyerahanUnit"
                                    data-id-category="2"
                                    data-id-doc="{{ $item->penyerahan_unit ? $item->penyerahan_unit->id : 0 }}"
                                    data-file="@isset($item->penyerahan_unit['file']){{ $item->penyerahan_unit['file'] }}@endisset"
                                    data-confirm="{{ $item->penyerahan_unit['is_confirm'] }}"
                                    data-tanggal="{{ date('d-m-Y', strtotime($item->penyerahan_unit['date'])) }}"
                                    data-confirm_at="{{ $item->penyerahan_unit['confirm_at'] ? date('d-m-Y', strtotime($item->penyerahan_unit['confirm_at'])) : '-' }}">Konfirmasi</a>
                            @endif
                        @endif
                    @endif
                @else
                    -
                @endif
            @else
                <span class="text-danger">-</span>
            @endif
        </td>
        <td>
            @if ($item->penyerahan_unit)
                @if ($is_kredit_page)
                    @if ($item->penyerahan_unit['is_confirm'])
                        @if ($item->stnk)
                            @if ($item->stnk['file'] && $item->stnk['is_confirm'])
                                <a style="text-decoration: underline; cursor: pointer;"
                                    class="open-po detailFileStnk" data-toggle="modal"
                                    data-target="#detailStnk"
                                    data-file="{{ $item->stnk['file'] }}"
                                    data-confirm="{{ $item->stnk['is_confirm'] }}"
                                    data-tanggal="{{ date('d-m-Y', strtotime($item->stnk['date'])) }}"
                                    data-confirm_at="{{ date('d-m-Y', strtotime($item->stnk['confirm_at'])) }}">{{ date('d-m-Y', strtotime($item->stnk['date'])) }}</a>
                            @else
                                <span class="text-warning">Menunggu konfirmasi</span>
                            @endif
                        @else
                            @if (\Session::get(config('global.role_id_session')) == 3)
                                @if ($item->penyerahan_unit['is_confirm'])
                                    <span class="text-info">Maksimal
                                        {{ date('d-m-Y', strtotime($item->penyerahan_unit['date'] . ' +1 month')) }}</span>
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
                    @if ($item->penyerahan_unit['is_confirm'])
                        @if ($item->stnk)
                            @if ($item->stnk['file'] && $item->stnk['is_confirm'])
                                <a style="text-decoration: underline; cursor: pointer;"
                                    class="open-po detailFileStnk" data-toggle="modal"
                                    data-target="#detailStnk"
                                    data-file="{{ $item->stnk['file'] }}"
                                    data-confirm="{{ $item->stnk['is_confirm'] }}"
                                    data-tanggal="{{ date('d-m-Y', strtotime($item->stnk['date'])) }}"
                                    data-confirm_at="{{ date('d-m-Y', strtotime($item->stnk['confirm_at'])) }}">{{ date('d-m-Y', strtotime($item->stnk['date'])) }}</a>
                            @else
                                <span class="text-warning">Menunggu konfirmasi</span>
                            @endif
                        @else
                            @if (\Session::get(config('global.role_id_session')) == 3)
                                @if ($item->penyerahan_unit['is_confirm'])
                                    <span class="text-info">Maksimal
                                        {{ date('d-m-Y', strtotime($item->penyerahan_unit['date'] . ' +1 month')) }}</span>
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
                @endif
            @else
                <span class="text-warning">-</span>
            @endif
        </td>
        <td>
            @if ($item->penyerahan_unit)
                @if ($is_kredit_page)
                    @if ($item->penyerahan_unit['is_confirm'])
                        @if ($item->bpkb)
                            @if ($item->bpkb['file'] && $item->bpkb['is_confirm'])
                                <a style="text-decoration: underline; cursor: pointer;"
                                    class="open-po detailFileBpkb" data-toggle="modal"
                                    data-target="#detailBpkb"
                                    data-file="{{ $item->bpkb['file'] }}"
                                    data-confirm="{{ $item->bpkb['is_confirm'] }}"
                                    data-tanggal="{{ date('d-m-Y', strtotime($item->bpkb['date'])) }}"
                                    data-confirm_at="{{ date('d-m-Y', strtotime($item->bpkb['confirm_at'])) }}">{{ date('d-m-Y', strtotime($item->bpkb['date'])) }}</a>
                            @else
                                <span class="text-warning">Menunggu konfirmasi</span>
                            @endif
                        @else
                            @if (\Session::get(config('global.role_id_session')) == 3)
                                @if ($item->penyerahan_unit['is_confirm'])
                                    <span class="text-info">Maksimal
                                        {{ date('d-m-Y', strtotime($item->penyerahan_unit['date'] . ' +3 month')) }}</span>
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
                    @if ($item->penyerahan_unit['is_confirm'])
                        @if ($item->bpkb)
                            @if ($item->bpkb['file'] && $item->bpkb['is_confirm'])
                                <a style="text-decoration: underline; cursor: pointer;"
                                    class="open-po detailFileBpkb" data-toggle="modal"
                                    data-target="#detailBpkb"
                                    data-file="{{ $item->bpkb['file'] }}"
                                    data-confirm="{{ $item->bpkb['is_confirm'] }}"
                                    data-tanggal="{{ date('d-m-Y', strtotime($item->bpkb['date'])) }}"
                                    data-confirm_at="{{ date('d-m-Y', strtotime($item->bpkb['confirm_at'])) }}">{{ date('d-m-Y', strtotime($item->bpkb['date'])) }}</a>
                            @else
                                <span class="text-warning">Menunggu konfirmasi</span>
                            @endif
                        @else
                            @if (\Session::get(config('global.role_id_session')) == 3)
                                @if ($item->penyerahan_unit['is_confirm'])
                                    <span class="text-info">Maksimal
                                        {{ date('d-m-Y', strtotime($item->penyerahan_unit['date'] . ' +3 month')) }}</span>
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
                @endif
            @else
                <span class="text-warning">-</span>
            @endif
        </td>
        <td>
            @if ($item->penyerahan_unit)
                @if ($is_kredit_page)
                    @if ($item->penyerahan_unit['is_confirm'])
                        @if ($item->polis)
                            @if ($item->polis['file'] && $item->polis['is_confirm'])
                                <a style="text-decoration: underline; cursor: pointer;"
                                    class="open-po detailFilePolis" data-toggle="modal"
                                    data-target="#detailPolis"
                                    data-file="{{ $item->polis['file'] }}"
                                    data-confirm="{{ $item->polis['is_confirm'] }}"
                                    data-tanggal="{{ date('d-m-Y', strtotime($item->polis['date'])) }}"
                                    data-confirm_at="{{ date('d-m-Y', strtotime($item->polis['confirm_at'])) }}">{{ date('d-m-Y', strtotime($item->polis['date'])) }}</a>
                            @else
                                <span class="text-warning">Menunggu konfirmasi</span>
                            @endif
                        @else
                            @if (\Session::get(config('global.role_id_session')) == 3)
                                @if ($item->penyerahan_unit['is_confirm'])
                                    <span class="text-info">Maksimal
                                        {{ date('d-m-Y', strtotime($item->penyerahan_unit['date'] . ' +3 month')) }}</span>
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
                    @if ($item->penyerahan_unit['is_confirm'])
                        @if ($item->polis)
                            @if ($item->polis['file'] && $item->polis['is_confirm'])
                                <a style="text-decoration: underline; cursor: pointer;"
                                    class="open-po detailFilePolis" data-toggle="modal"
                                    data-target="#detailPolis"
                                    data-file="{{ $item->polis['file'] }}"
                                    data-confirm="{{ $item->polis['is_confirm'] }}"
                                    data-tanggal="{{ date('d-m-Y', strtotime($item->polis['date'])) }}"
                                    data-confirm_at="{{ date('d-m-Y', strtotime($item->polis['confirm_at'])) }}">{{ date('d-m-Y', strtotime($item->polis['date'])) }}</a>
                            @else
                                <span class="text-warning">Menunggu konfirmasi</span>
                            @endif
                        @else
                            @if (\Session::get(config('global.role_id_session')) == 3)
                                @if ($item->penyerahan_unit['is_confirm'])
                                    <span class="text-info">Maksimal
                                        {{ date('d-m-Y', strtotime($item->penyerahan_unit['date'] . ' +3 month')) }}</span>
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
                @endif
            @else
                <span class="text-warning">-</span>
            @endif
        </td>
        <td>
            @if (\Session::get(config('global.role_id_session')) == 3)
                {{--  vendor  --}}
                @if ($item->imbal_jasa)
                    @if ($is_kredit_page)
                        @if (!$item->imbal_jasa['is_confirm'])
                            <a style="cursor: pointer; text-decoration: underline;"
                                class="confirm-imbal-jasa toggle-modal" data-target-id="modalConfirmImbalJasa"
                                data-id="{{ $item->imbal_jasa['id'] }}"
                                data-file="{{ $item->imbal_jasa['file'] }}"
                                data-tanggal="{{ \Carbon\Carbon::parse($item->imbal_jasa['created_at'])->format('d-m-Y') }}"
                                data-confirm="{{ $item->imbal_jasa['is_confirm'] }}"
                                href="#">Konfirmasi Bukti Pembayaran</a>
                        @elseif ($item->imbal_jasa['is_confirm'])
                            <a class="bukti-pembayaran-modal toggle-modal"
                                style="cursor: pointer; text-decoration: underline;"
                                data-target-id="modalConfirmImbalJasa"
                                data-confirm="{{ $item->imbal_jasa['is_confirm'] }}"
                                data-tanggal="{{ \Carbon\Carbon::parse($item->imbal_jasa['date'])->format('d-m-Y') }}"
                                data-confirm_at="{{ \Carbon\Carbon::parse($item->imbal_jasa['confirm_at'])->format('d-m-Y') }}"
                                data-file="{{ $item->imbal_jasa['file'] }}">Selesai</a>
                        @else
                            Menunggu Pembayaran dari Cabang
                        @endif
                    @else
                        @if (!$item->imbal_jasa['is_confirm'])
                            Menunggu konfirmasi
                        @elseif ($item->imbal_jasa['is_confirm'])
                            <a class="bukti-pembayaran-modal toggle-modal"
                                style="cursor: pointer; text-decoration: underline;"
                                data-target-id="modalConfirmImbalJasa"
                                data-confirm="{{ $item->imbal_jasa['is_confirm'] }}"
                                data-tanggal="{{ \Carbon\Carbon::parse($item->imbal_jasa['date'])->format('d-m-Y') }}"
                                data-confirm_at="{{ \Carbon\Carbon::parse($item->imbal_jasa['confirm_at'])->format('d-m-Y') }}"
                                data-file="{{ $item->imbal_jasa['file'] }}">Selesai</a>
                        @else
                            Menunggu Pembayaran dari Cabang
                        @endif
                    @endif
                @else
                    -
                @endif
            @else
                {{--  role selain vendor  --}}
                @if ($item->stnk && $item->bpkb && $item->polis)
                    @if ($is_kredit_page)
                        @if ($item->stnk['is_confirm'] && $item->bpkb['is_confirm'] && $item->polis['is_confirm'])
                            @if (!$item->imbal_jasa)
                                @if (\Session::get(config('global.user_role_session')) == $staf_analisa_kredit_role)
                                    <a href="#"
                                        style="text-decoration: underline; cursor: pointer;"
                                        class="toggle-modal underline"
                                        data-target-id="modalUploadImbalJasa"
                                        data-id="{{ $item->id }}">Bayar</a>
                                @endif
                            @else
                                @if (!$item->imbal_jasa['is_confirm'])
                                    <p class="m-0">Menunggu Konfirmasi Vendor</p>
                                @elseif ($item->imbal_jasa['is_confirm'])
                                    <a class="bukti-pembayaran-modal toggle-modal"
                                        style="cursor: pointer; text-decoration: underline;"
                                        data-target-id="modalConfirmImbalJasa"
                                        data-confirm="{{ $item->imbal_jasa['is_confirm'] }}"
                                        data-tanggal="{{ \Carbon\Carbon::parse($item->imbal_jasa['date'])->format('d-m-Y') }}"
                                        data-confirm_at="{{ \Carbon\Carbon::parse($item->imbal_jasa['confirm_at'])->format('d-m-Y') }}"
                                        data-file="{{ $item->imbal_jasa['file'] }}">Selesai</a>
                                @endif
                            @endif
                        @else
                        -
                        @endif
                    @else
                        @if ($item->stnk['is_confirm'] && $item->bpkb['is_confirm'] && $item->polis['is_confirm'])
                            @if (!$item->imbal_jasa)
                                @if (\Session::get(config('global.user_role_session')) == $staf_analisa_kredit_role)
                                    Menunggu pembayaran
                                @endif
                            @else
                                @if (!$item->imbal_jasa['is_confirm'])
                                    <p class="m-0">Menunggu Konfirmasi Vendor</p>
                                @elseif ($item->imbal_jasa['is_confirm'])
                                    <a class="bukti-pembayaran-modal toggle-modal"
                                        style="cursor: pointer; text-decoration: underline;"
                                        data-target-id="modalConfirmImbalJasa"
                                        data-confirm="{{ $item->imbal_jasa['is_confirm'] }}"
                                        data-tanggal="{{ \Carbon\Carbon::parse($item->imbal_jasa['date'])->format('d-m-Y') }}"
                                        data-confirm_at="{{ \Carbon\Carbon::parse($item->imbal_jasa['confirm_at'])->format('d-m-Y') }}"
                                        data-file="{{ $item->imbal_jasa['file'] }}">Selesai</a>
                                @endif
                            @endif
                        @else
                        -
                        @endif
                    @endif
                @else
                    -
                @endif
            @endif
        </td>
        <td>
            @if ($item->penyerahan_unit)
                @if ($is_kredit_page)
                    @if ($item->imbal_jasa)
                        @if ($item->imbal_jasa['file'] && $item->imbal_jasa['is_confirm'])
                            <a>Rp {{ number_format($item->set_imbal_jasa->imbaljasa, 0, '', '.') }}</a>
                        @else
                            @if (\Session::get(config('global.role_id_session')) == 3)
                                <span class="text-info">Silahkan konfirmasi bukti transfer imbal jasa</span>
                            @else
                                <span>Rp {{ number_format($item->set_imbal_jasa->imbaljasa, 0, '', '.') }}</span>
                            @endif
                        @endif
                    @else
                        @if ($item->imbal_jasa)
                            @if ($item->imbal_jasa['file'] && $item->imbal_jasa['is_confirm'])
                                @if ($item->stnk && $item->polis && $item->bpkb)
                                    @if (\Session::get(config('global.role_id_session')) == 2 && \Session::get(config('global.user_role_session')) == $staf_analisa_kredit_role)
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
                    @if ($item->imbal_jasa)
                        @if ($item->imbal_jasa['file'] && $item->imbal_jasa['is_confirm'])
                            <a>Rp {{ number_format($item->set_imbal_jasa->imbaljasa, 0, '', '.') }}</a>
                        @else
                            @if (\Session::get(config('global.role_id_session')) == 3)
                                <span class="text-info">Silahkan konfirmasi bukti transfer imbal jasa</span>
                            @else
                                <span>Rp {{ number_format($item->set_imbal_jasa->imbaljasa, 0, '', '.') }}</span>
                            @endif
                        @endif
                    @else
                        @if ($item->imbal_jasa)
                            @if ($item->imbal_jasa['file'] && $item->imbal_jasa['is_confirm'])
                                @if ($item->stnk && $item->polis && $item->bpkb)
                                    @if (\Session::get(config('global.role_id_session')) == 2 && \Session::get(config('global.user_role_session')) == $staf_analisa_kredit_role)
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
                @endif
            @else
                <span class="text-warning">-</span>
            @endif
        </td>
        <td class="text-center @if ($item->status == 'done' && $item->set_imbal_jasa) text-success @else text-info @endif">
            @if ($item->set_imbal_jasa)
                {{ ucwords($item->status) }}
            @else
                Progress
            @endif
        </td>
        <td>
            @if ($is_kredit_page)
                <div class="dropdown">
                    <button class="px-4 py-2 bg-theme-btn/10 rounded text-theme-btn">
                        Selengkapnya
                    </button>
                    <ul class="dropdown-menu  right-20">
                        @if ($item->tgl_ketersediaan_unit)
                            @if ($item->bukti_pembayaran)
                                @if (!$item->penyerahan_unit && $item->bukti_pembayaran->is_confirm && \Session::get(config('global.role_id_session')) == 3)
                                    <li>
                                        <a href="#" class="item-dropdown toggle-modal"
                                            data-target-id="modalUploadBuktiPenyerahanUnit"
                                            data-id_kkb="{{ $item->kkb_id }}">Kirim Unit</a>
                                    </li>
                                @endif
                            @endif
                        @endif
                        {{--  Upload Berkas  --}}
                        @if (\Session::get(config('global.role_id_session')) == 3 && $item->penyerahan_unit)
                            @if ($item->penyerahan_unit['is_confirm'])
                                @if (!isset($item->stnk['file']) || !isset($item->polis['file']) || !isset($item->bpkb['is_confirm']))
                                    {{--  Vendor  --}}
                                    <li>
                                        <a class="item-dropdown toggle-modal"
                                            data-target-id="modalUploadBerkas"
                                            data-id_kkb="{{ $item->kkb_id }}"
                                            data-no-stnk="@isset($item->stnk->text){{ $item->stnk->text }}@endisset"
                                            data-file-stnk="@isset($item->stnk['file']){{ $item->stnk['file'] }}@endisset"
                                            data-date-stnk="@isset($item->stnk['date']){{ date('d-m-Y', strtotime($item->stnk['date'])) }}@endisset"
                                            data-confirm-stnk="@isset($item->stnk['is_confirm']){{ $item->stnk['is_confirm'] }}@endisset"
                                            data-confirm-at-stnk="@isset($item->stnk['confirm_at']){{ date('d-m-Y', strtotime($item->stnk['confirm_at'])) }}@endisset"
                                            data-no-polis="@isset($item->polis->text){{ $item->polis->text }}@endisset"
                                            data-file-polis="@isset($item->polis['file']){{ $item->polis['file'] }}@endisset"
                                            data-date-polis="@isset($item->polis['date']){{ date('d-m-Y', strtotime($item->polis['date'])) }}@endisset"
                                            data-confirm-polis="@isset($item->polis['is_confirm']){{ $item->polis['is_confirm'] }}@endisset"
                                            data-confirm-at-polis="@isset($item->polis['confirm_at']){{ date('d-m-Y', strtotime($item->polis['confirm_at'])) }}@endisset"
                                            data-no-bpkb="@isset($item->bpkb->text){{ $item->bpkb->text }}@endisset"
                                            data-file-bpkb="@isset($item->bpkb['file']){{ $item->bpkb['file'] }}@endisset"
                                            data-date-bpkb="@isset($item->bpkb['date']){{ date('d-m-Y', strtotime($item->bpkb['date'])) }}@endisset"
                                            data-confirm-bpkb="@isset($item->bpkb['is_confirm']){{ $item->bpkb['is_confirm'] }}@endisset"
                                            data-confirm-at-bpkb="@isset($item->bpkb['confirm_at']){{ date('d-m-Y', strtotime($item->bpkb['confirm_at'])) }}@endisset"
                                            href="#">
                                            Upload Berkas
                                        </a>
                                    </li>
                                @endif
                            @endif
                        @endif
                        @if (\Session::get(config('global.role_id_session')) == 2 && \Session::get(config('global.user_role_session')) == $staf_analisa_kredit_role)
                            {{--  Cabang  --}}
                            @if ($item->stnk || $item->polis || $item->bpkb)
                                @if (
                                    (isset($item->stnk['is_confirm']) && !$item->stnk['is_confirm']) ||
                                        (isset($item->polis['is_confirm']) && !$item->polis['is_confirm']) ||
                                        (isset($item->bpkb['is_confirm']) && !$item->bpkb['is_confirm']))
                                    <li>
                                        <a class="item-dropdown toggle-modal"
                                            data-target-id="modalUploadBerkas"
                                            data-id_kkb="{{ $item->kkb_id }}"
                                            data-id-stnk="@if ($item->stnk) {{ $item->stnk->id }}@else- @endif"
                                            data-id-polis="@if ($item->polis) {{ $item->polis->id }}@else- @endif"
                                            data-id-bpkb="@if ($item->bpkb) {{ $item->bpkb->id }}@else- @endif"
                                            data-no-stnk="@isset($item->stnk->text){{ $item->stnk->text }}@endisset"
                                            data-file-stnk="@isset($item->stnk['file']){{ $item->stnk['file'] }}@endisset"
                                            data-date-stnk="@isset($item->stnk['date']){{ date('d-m-Y', strtotime($item->stnk['date'])) }}@endisset"
                                            data-confirm-stnk="@isset($item->stnk['is_confirm']){{ $item->stnk['is_confirm'] }}@endisset"
                                            data-confirm-at-stnk="@isset($item->stnk['confirm_at']){{ date('d-m-Y', strtotime($item->stnk['confirm_at'])) }}@endisset"
                                            data-no-polis="@isset($item->polis->text){{ $item->polis->text }}@endisset"
                                            data-file-polis="@isset($item->polis['file']){{ $item->polis['file'] }}@endisset"
                                            data-date-polis="@isset($item->polis['date']){{ date('d-m-Y', strtotime($item->polis['date'])) }}@endisset"
                                            data-confirm-polis="@isset($item->polis['is_confirm']){{ $item->polis['is_confirm'] }}@endisset"
                                            data-confirm-at-polis="@isset($item->polis['confirm_at']){{ date('d-m-Y', strtotime($item->polis['confirm_at'])) }}@endisset"
                                            data-no-bpkb="@isset($item->bpkb->text){{ $item->bpkb->text }}@endisset"
                                            data-file-bpkb="@isset($item->bpkb['file']){{ $item->bpkb['file'] }}@endisset"
                                            data-date-bpkb="@isset($item->bpkb['date']){{ date('d-m-Y', strtotime($item->bpkb['date'])) }}@endisset"
                                            data-confirm-bpkb="@isset($item->bpkb['is_confirm']){{ $item->bpkb['is_confirm'] }}@endisset"
                                            data-confirm-at-bpkb="@isset($item->bpkb['confirm_at']){{ date('d-m-Y', strtotime($item->bpkb['confirm_at'])) }}@endisset"
                                            href="#">
                                            Konfirmasi Berkas
                                        </a>
                                    </li>
                                @endif
                            @endif
                        @endif
                        <li>
                            <a class="item-dropdown toggle-modals"
                                data-target-id="modalDetailPo" data-id="{{ $item->id }}"
                                href="#">Detail</a>
                        </li>
                    </ul>
                </div>
            @else
                <button class="px-4 py-2 bg-theme-btn/10 rounded text-theme-btn toggle-modals"
                    data-target-id="modalDetailPo" data-id="{{ $item->id }}">
                    Detail
                </button>
            @endif
        </td>
    </tr>
@empty
    <td colspan="{{ 8 + count($documentCategories) }}" class="text-center">
        <span class="text-danger">Maaf data belum tersedia.</span>
    </td>
@endforelse