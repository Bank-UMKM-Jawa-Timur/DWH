@forelse ($imported as $item)
    <tr>
        {{--  No  --}}
        <td>{{ $loop->iteration }}</td>
        {{--  Nama  --}}
        <td>{{ $item->name }}</td>
        {{--  Cabang  --}}
        @if ($role_id == 3 || $role_id == 1 || $role_id == 4)
            <td>{{ $item->cabang }} </td>
        @endif
        {{--  Ketersediaan Unit  --}}
        <td>{{ date('d-m-Y', strtotime($item->tgl_ketersediaan_unit)) }}</td>
        {{--  Tagihan  --}}
        <td>
            @if ($item->tgl_ketersediaan_unit)
                @if ($role_id == 3)
                    {{--  vendor  --}}
                    @if($item->invoice)
                        @if (property_exists($item->invoice, 'is_confirm'))
                            @if ($item->invoice->is_confirm)
                                <a class="m-0 tagihan-modal toggle-modal-tagihan"
                                    style="cursor: pointer; text-decoration: underline;"
                                    data-target-id="modalTagihan"
                                    data-id="{{$item->id}}"
                                    data-file="{{ $item->invoice->file }}"
                                    data-confirm="{{ $item->invoice->is_confirm }}"
                                    data-tanggal="{{ date('d-m-Y', strtotime($item->invoice->date)) }}"
                                    data-confirm_at="{{ date('d-m-Y', strtotime($item->invoice->confirm_at)) }}"
                                    onclick="showModal(this)">
                                    Selesai
                                </a>
                            @else
                                <a class="m-0 tagihan-modal toggle-modal-tagihan"
                                    style="cursor: pointer; text-decoration: underline;"
                                    data-target-id="modalTagihan"
                                    data-id="{{$item->id}}"
                                    data-file="{{ $item->invoice->file }}"
                                    data-confirm="{{ $item->invoice->is_confirm }}"
                                    data-tanggal="{{ date('d-m-Y', strtotime($item->invoice->date)) }}"
                                    data-confirm_at="{{ date('d-m-Y', strtotime($item->invoice->confirm_at)) }}"
                                    onclick="showModal(this)">
                                    Menunggu pembayaran cabang
                                </a>
                            @endif
                        @else
                            <a class="m-0 tagihan-modal toggle-modal-tagihan"
                                style="cursor: pointer; text-decoration: underline;"
                                data-target-id="modalTagihan"
                                data-id="{{$item->id}}"
                                data-file="@if(property_exists($item->invoice, 'file')) {{ $item->invoice->file }} @endif"
                                data-confirm="@if(property_exists($item->invoice, 'is_confirm')) {{ $item->invoice->is_confirm }} @endif"
                                data-tanggal="@if(property_exists($item->invoice, 'date')) {{ date('d-m-Y', strtotime($item->invoice->date)) }} @endif"
                                data-confirm_at="@if(property_exists($item->invoice, 'confirm_at')) {{ date('d-m-Y', strtotime($item->invoice->confirm_at)) }} @endif"
                                onclick="showModal(this)">
                                Menunggu pembayaran cabang
                            </a>
                        @endif
                    @else
                        @php
                            $current_date = date('d-m-Y');
                            $hmin1_tgl_ketersediaan_unit = date('d-m-Y', strtotime($item->tgl_ketersediaan_unit . ' -1 day'));
                        @endphp
                        @if ($is_kredit_page)
                            <button class="toggle-modal-upload-berkas-tagihan underline text-red-600"
                                data-target-id="modalUploadBerkasTagihan"
                                data-id_kkb="{{ $item->kkb_id }}" onclick="showModal(this)">
                                Upload Berkas
                            </button>
                        @else
                            @if (($current_date == $hmin1_tgl_ketersediaan_unit || $current_date == $item->tgl_ketersediaan_unit) || $current_date > $item->tgl_ketersediaan_unit)
                            @else
                                -
                            @endif
                        @endif
                    @endif
                @else
                    {{--  role selain vendor  --}}
                    @if ($item->invoice)
                        @if (!$item->bukti_pembayaran)
                            @if ($is_kredit_page)
                                <a class="m-0 tagihan-modal toggle-modal-tagihan"
                                    style="cursor: pointer; text-decoration: underline;"
                                    data-target-id="modalTagihan"
                                    data-id="{{$item->id}}"
                                    data-file="{{ $item->invoice->file ? $item->invoice->file : '' }}"
                                    data-confirm="{{ $item->invoice->is_confirm }}"
                                    data-tanggal="{{ date('d-m-Y', strtotime($item->invoice->date)) }}"
                                    data-confirm_at="{{ date('d-m-Y', strtotime($item->invoice->confirm_at)) }}"
                                    onclick="showModal(this)">
                                    Menunggu pembayaran cabang
                                </a>
                            @else
                                <span>Menunggu pembayaran</span>
                            @endif
                        @else
                            @if ($item->bukti_pembayaran)
                                <a class="m-0 tagihan-modal toggle-modal-tagihan"
                                    style="cursor: pointer; text-decoration: underline;"
                                    data-target-id="modalTagihan"
                                    data-id="{{$item->id}}"
                                    data-file="@if(property_exists($item->invoice, 'file')) {{ $item->invoice ? $item->invoice->file : '' }} @endif"
                                    data-confirm="@if(property_exists($item->invoice, 'is_confirm')) {{ $item->invoice->is_confirm }} @endif"
                                    data-tanggal="@if(property_exists($item->invoice, 'date')) {{ date('d-m-Y', strtotime($item->invoice->date)) }} @endif"
                                    data-confirm_at="@if(property_exists($item->invoice, 'confirm_at')) {{ date('d-m-Y', strtotime($item->invoice->confirm_at)) }} @endif"
                                    onclick="showModal(this)">
                                    Selesai
                                </a>
                            @else
                                Menunggu cabang mengunggah bukti pembayaran
                            @endif
                        @endif
                    @else
                        Menunggu berkas tagihan diupload vendor
                    @endif
                @endif
            @else
                -
            @endif
        </td>
        {{--  Bukti Pembayaran  --}}
        <td>
            @if ($item->tgl_ketersediaan_unit)
                @if ($role_id == 3)
                    {{--  vendor  --}}
                    @if($item->invoice)
                        @if ($item->bukti_pembayaran)
                            @if ($is_kredit_page)
                                @if (property_exists($item->bukti_pembayaran, 'is_confirm'))
                                    @if (!$item->bukti_pembayaran->is_confirm)
                                        <a style="cursor: pointer; text-decoration: underline;"
                                            class="confirm-bukti-pembayaran toggle-modal-confirm-bukti-pembayaran text-red-600" data-target-id="modalConfirmBuktiPembayaran"
                                            data-id-category="1"
                                            data-tanggal="{{ date('d-m-Y', strtotime($item->bukti_pembayaran->date)) }}"
                                            data-id-doc="{{ $item->bukti_pembayaran ? $item->bukti_pembayaran->id : 0 }}"
                                            data-file="@if($item->bukti_pembayaran->file){{ $item->bukti_pembayaran->file }}@endif"
                                            onclick="showModal(this)"
                                            href="#">Konfirmasi Bukti Pembayaran</a>
                                    @elseif ($item->bukti_pembayaran->is_confirm)
                                        <a class="m-0 bukti-pembayaran-modal toggle-modal-confirm-bukti-pembayaran"
                                            style="cursor: pointer; text-decoration: underline;"
                                            data-target-id="modalConfirmBuktiPembayaran"
                                            data-file="@if($item->bukti_pembayaran->file){{ $item->bukti_pembayaran->file }}@endif"
                                            data-confirm="{{ $item->bukti_pembayaran->is_confirm }}"
                                            data-tanggal="{{ date('d-m-Y', strtotime($item->bukti_pembayaran->date)) }}"
                                            data-confirm_at="{{ date('d-m-Y', strtotime($item->bukti_pembayaran->confirm_at)) }}"
                                            onclick="showModal(this)">
                                            Selesai
                                        </a>
                                    @else
                                        Menunggu Pembayaran dari Cabang
                                    @endif
                                @else
                                    <a style="cursor: pointer; text-decoration: underline;"
                                        class="confirm-bukti-pembayaran toggle-modal-confirm-bukti-pembayaran text-red-600" data-target-id="modalConfirmBuktiPembayaran"
                                        data-id-category="1"
                                        data-tanggal="{{ date('d-m-Y', strtotime($item->bukti_pembayaran->date)) }}"
                                        data-id-doc="{{ $item->bukti_pembayaran ? $item->bukti_pembayaran->id : 0 }}"
                                        data-file="@if(property_exists($item->bukti_pembayaran, 'file')) {{ $item->bukti_pembayaran->file }} @endif"
                                        onclick="showModal(this)"
                                        href="#">Konfirmasi Bukti Pembayaran</a>
                                @endif
                            @else
                                @if (!$item->bukti_pembayaran->is_confirm)
                                    Menunggu konfirmasi
                                @elseif ($item->bukti_pembayaran->is_confirm)
                                    <a class="m-0 bukti-pembayaran-modal toggle-modal-confirm-bukti-pembayaran"
                                        style="cursor: pointer; text-decoration: underline;"
                                        data-target-id="modalConfirmBuktiPembayaran"
                                        data-file="@if($item->bukti_pembayaran->file){{ $item->bukti_pembayaran->file }}@endif"
                                        data-confirm="{{ $item->bukti_pembayaran->is_confirm }}"
                                        data-tanggal="{{ date('d-m-Y', strtotime($item->bukti_pembayaran->date)) }}"
                                        data-confirm_at="{{ date('d-m-Y', strtotime($item->bukti_pembayaran->confirm_at)) }}"
                                        onclick="showModal(this)">
                                        Selesai
                                    </a>
                                @else
                                    Menunggu Pembayaran dari Cabang
                                @endif
                            @endif
                        @else
                            Menunggu Pembayaran dari Cabang
                        @endif
                    @else
                        @php
                            $current_date = date('d-m-Y');
                            $hmin1_tgl_ketersediaan_unit = date('d-m-Y', strtotime($item->tgl_ketersediaan_unit . ' -1 day'));
                        @endphp
                        @if ($is_kredit_page)
                            <button class="toggle-modal-upload-berkas-tagihan underline text-red-600" data-target-id="modalUploadBerkasTagihan"
                                data-id_kkb="{{ $item->kkb_id }}" onclick="showModal(this)">
                                Upload Berkas
                            </button>
                        @else
                            @if (($current_date == $hmin1_tgl_ketersediaan_unit || $current_date == $item->tgl_ketersediaan_unit) || $current_date > $item->tgl_ketersediaan_unit)
                            @else
                                -
                            @endif
                        @endif
                    @endif
                @else
                    {{--  role selain vendor  --}}
                    @if ($item->invoice)
                        @if (!$item->bukti_pembayaran && \Session::get(config('global.role_id_session')) != 3)
                            @if ($is_kredit_page)
                                @if (\Session::get(config('global.user_role_session')) == $staf_analisa_kredit_role)
                                    <button class="toggle-modal-upload-bukti-pembayaran underline text-red-600" data-target-id="modalUploadBuktiPembayaran"
                                        data-id_kkb="{{ $item->kkb_id }}" onclick="showModal(this)">
                                        Bayar
                                    </button>
                                @else
                                    <span>Menunggu pembayaran</span>
                                @endif
                            @else
                                <span>Menunggu pembayaran</span>
                            @endif
                        @else
                            @if ($item->bukti_pembayaran)
                                @if ($is_kredit_page)
                                    @if (property_exists($item->bukti_pembayaran, 'is_confirm'))
                                        @if (!$item->bukti_pembayaran->is_confirm)
                                            @if (\Session::get(config('global.role_id_session')) == 3)
                                                <button class="toggle-modal-confirm-bukti-pembayaran text-red-600"
                                                    style="cursor: pointer;"
                                                    data-target-id="modalConfirmBuktiPembayaran"
                                                    data-file="@if($item->bukti_pembayaran->file){{ $item->bukti_pembayaran->file }}@endif"
                                                    data-tanggal="{{ $item->bukti_pembayaran->date }}"
                                                    data-id-category="{{$item->bukti_pembayaran->document_category_id}}"
                                                    data-id-doc="{{ $item->bukti_pembayaran ? $item->bukti_pembayaran->id : 0 }}"
                                                    onclick="showModal(this)">
                                                    Konfirmasi
                                                </button>
                                            @else
                                                <button class="toggle-modal-bukti-pembayaran underline"
                                                    style="cursor: pointer;"
                                                    data-target-id="modalBuktiPembayaran"
                                                    data-file="@if($item->bukti_pembayaran->file){{ $item->bukti_pembayaran->file }}@endif"
                                                    data-tanggal="{{ $item->bukti_pembayaran->date }}"
                                                    data-confirm="{{ $item->bukti_pembayaran->is_confirm }}"
                                                    data-confirm_at="{{ $item->bukti_pembayaran->confirm_at ? date('d-m-Y', strtotime($item->bukti_pembayaran->confirm_at)) : '-' }}"
                                                    onclick="showModal(this)">
                                                    Menunggu Konfirmasi Vendor
                                                </button>
                                            @endif
                                        @elseif ($item->bukti_pembayaran->is_confirm)
                                            <button class="toggle-modal-bukti-pembayaran underline"
                                                style="cursor: pointer;"
                                                data-target-id="modalBuktiPembayaran"
                                                data-file="@if($item->bukti_pembayaran->file){{ $item->bukti_pembayaran->file }}@endif"
                                                data-tanggal="{{ $item->bukti_pembayaran->date }}"
                                                data-confirm="{{ $item->bukti_pembayaran->is_confirm }}"
                                                data-confirm_at="{{ $item->bukti_pembayaran->confirm_at ? date('d-m-Y', strtotime($item->bukti_pembayaran->confirm_at)) : '-' }}"
                                                onclick="showModal(this)">
                                                Selesai
                                            </button>
                                        @endif
                                    @endif
                                @else
                                    @if ($item->bukti_pembayaran->is_confirm)
                                        <button class="toggle-modal-bukti-pembayaran underline"
                                            style="cursor: pointer;"
                                            data-target-id="modalBuktiPembayaran"
                                            data-file="@if($item->bukti_pembayaran->file){{ $item->bukti_pembayaran->file }}@endif"
                                            data-tanggal="{{ $item->bukti_pembayaran->date }}"
                                            data-confirm="{{ $item->bukti_pembayaran->is_confirm }}"
                                            data-confirm_at="{{ $item->bukti_pembayaran->confirm_at ? date('d-m-Y', strtotime($item->bukti_pembayaran->confirm_at)) : '-' }}"
                                            onclick="showModal(this)">
                                            Selesai
                                        </button>
                                    @else
                                        <span>Menunggu konfirmasi</span>
                                    @endif
                                @endif
                            @else
                                Menunggu cabang mengunggah bukti pembayaran
                            @endif
                        @endif
                    @else
                        Menunggu berkas tagihan diupload vendor
                    @endif
                @endif
            @else
                -
            @endif
        </td>
        {{--  Penyerahan Unit  --}}
        <td>
            @if ($item->tgl_ketersediaan_unit)
                @if ($item->bukti_pembayaran)
                    @if ($is_kredit_page)
                        @if ($item->penyerahan_unit)
                            @if (\Session::get(config('global.role_id_session')) == 3)
                                @if (isset($item->penyerahan_unit->is_confirm))
                                    @if ($item->penyerahan_unit->is_confirm)
                                        <a style="text-decoration: underline; cursor: pointer;"
                                            class="toggle-modal" data-target-id="modalConfirmPenyerahanUnit"
                                            data-id-category="2"
                                            data-kategori="{{$item->kategori}}"
                                            data-id-doc="{{ $item->penyerahan_unit ? $item->penyerahan_unit->id : 0 }}"
                                            data-file="@isset($item->penyerahan_unit->file){{ $item->penyerahan_unit->file }}@endisset"
                                            data-confirm="{{ $item->penyerahan_unit->is_confirm }}"
                                            data-tanggal="{{ date('d-m-Y', strtotime($item->penyerahan_unit->date)) }}"
                                            data-confirm_at="{{ $item->penyerahan_unit->confirm_at ? date('d-m-Y', strtotime($item->penyerahan_unit->confirm_at)) : '-' }}"
                                            href="#"
                                            onclick="showModal(this)">{{ date('d-m-Y', strtotime($item->penyerahan_unit->date)) }}</a>
                                    @else
                                        <a style="text-decoration: underline; cursor: pointer;"
                                            class="toggle-modal" data-target-id="modalConfirmPenyerahanUnit"
                                            data-id-category="2"
                                            data-kategori="{{$item->kategori}}"
                                            data-id-doc="{{ $item->penyerahan_unit ? $item->penyerahan_unit->id : 0 }}"
                                            data-file="@isset($item->penyerahan_unit->file){{ $item->penyerahan_unit->file }}@endisset"
                                            data-confirm="{{ $item->penyerahan_unit->is_confirm }}"
                                            data-tanggal="{{ date('d-m-Y', strtotime($item->penyerahan_unit->date)) }}"
                                            data-confirm_at="{{ $item->penyerahan_unit->confirm_at ? date('d-m-Y', strtotime($item->penyerahan_unit->confirm_at)) : '-' }}"
                                            href="#"
                                            onclick="showModal(this)">{{ date('d-m-Y', strtotime($item->penyerahan_unit->date)) }}</a>
                                    @endif
                                @else
                                    <a style="text-decoration: underline; cursor: pointer;"
                                        class="toggle-modal" data-target-id="modalConfirmPenyerahanUnit"
                                        data-id-category="2"
                                        data-kategori="{{$item->kategori}}"
                                        data-id-doc="{{ $item->penyerahan_unit ? $item->penyerahan_unit->id : 0 }}"
                                        data-file="@isset($item->penyerahan_unit->file){{ $item->penyerahan_unit->file }}@endisset"
                                        data-confirm="{{ $item->penyerahan_unit->is_confirm }}"
                                        data-tanggal="{{ date('d-m-Y', strtotime($item->penyerahan_unit->date)) }}"
                                        data-confirm_at="{{ $item->penyerahan_unit->confirm_at ? date('d-m-Y', strtotime($item->penyerahan_unit->confirm_at)) : '-' }}"
                                        href="#"
                                        onclick="showModal(this)">{{ date('d-m-Y', strtotime($item->penyerahan_unit->date)) }}</a>
                                @endif
                            @elseif(\Session::get(config('global.role_id_session')) == 2 && \Session::get(config('global.user_role_session')) == $staf_analisa_kredit_role)
                                @if (property_exists($item->penyerahan_unit, 'is_confirm'))
                                    @if ($item->penyerahan_unit->is_confirm)
                                        <a style="text-decoration: underline; cursor: pointer;"
                                            class="toggle-modal" data-target-id="modalConfirmPenyerahanUnit"
                                            data-id-category="2"
                                            data-kategori="{{$item->kategori}}"
                                            data-id-doc="{{ $item->penyerahan_unit ? $item->penyerahan_unit->id : 0 }}"
                                            data-file="@isset($item->penyerahan_unit->file){{ $item->penyerahan_unit->file }}@endisset"
                                            data-confirm="1"
                                            data-tanggal="{{ date('d-m-Y', strtotime($item->penyerahan_unit->date)) }}"
                                            data-confirm_at="{{ $item->penyerahan_unit->confirm_at ? date('d-m-Y', strtotime($item->penyerahan_unit->confirm_at)) : '-' }}"
                                            href="#"
                                            onclick="showModal(this)">{{ date('d-m-Y', strtotime($item->penyerahan_unit->date)) }}</a>
                                    @else
                                        <a style="text-decoration: underline; cursor: pointer;"
                                            class="toggle-modal text-red-600" data-target-id="modalConfirmPenyerahanUnit"
                                            data-id-category="2"
                                            data-kategori="{{$item->kategori}}"
                                            data-id-doc="{{ $item->penyerahan_unit ? $item->penyerahan_unit->id : 0 }}"
                                            data-file="@isset($item->penyerahan_unit->file){{ $item->penyerahan_unit->file }}@endisset"
                                            data-confirm="0"
                                            data-tanggal="{{ date('d-m-Y', strtotime($item->penyerahan_unit->date)) }}"
                                            data-confirm_at="{{ $item->penyerahan_unit->confirm_at ? date('d-m-Y', strtotime($item->penyerahan_unit->confirm_at)) : '-' }}"
                                            href="#"
                                            onclick="showModal(this)">Konfirmasi</a>
                                    @endif
                                @else
                                    <a style="text-decoration: underline; cursor: pointer;"
                                        class="toggle-modal text-red-600" data-target-id="modalConfirmPenyerahanUnit"
                                        data-id-category="2"
                                        data-kategori="{{$item->kategori}}"
                                        data-id-doc="{{ $item->penyerahan_unit ? $item->penyerahan_unit->id : 0 }}"
                                        data-file="@isset($item->penyerahan_unit->file){{ $item->penyerahan_unit->file }}@endisset"
                                        data-confirm="0"
                                        data-tanggal="{{ date('d-m-Y', strtotime($item->penyerahan_unit->date)) }}"
                                        data-confirm_at="@if(property_exists($item->penyerahan_unit, 'confirm_at')) {{ $item->penyerahan_unit->confirm_at ? date('d-m-Y', strtotime($item->penyerahan_unit->confirm_at)) : '-' }} @else - @endif"
                                        href="#"
                                        onclick="showModal(this)">Konfirmasi</a>
                                @endif
                            @else
                                @if (property_exists($item->penyerahan_unit, 'is_confirm'))
                                    @if ($item->penyerahan_unit->is_confirm)
                                        <a style="text-decoration: underline; cursor: pointer;"
                                            class="toggle-modal" data-target-id="modalConfirmPenyerahanUnit"
                                            data-id-category="2"
                                            data-kategori="{{$item->kategori}}"
                                            data-id-doc="{{ $item->penyerahan_unit ? $item->penyerahan_unit->id : 0 }}"
                                            data-file="@isset($item->penyerahan_unit->file){{ $item->penyerahan_unit->file }}@endisset"
                                            data-confirm="1"
                                            data-tanggal="{{ date('d-m-Y', strtotime($item->penyerahan_unit->date)) }}"
                                            data-confirm_at="{{ $item->penyerahan_unit->confirm_at ? date('d-m-Y', strtotime($item->penyerahan_unit->confirm_at)) : '-' }}"
                                            href="#"
                                            onclick="showModal(this)">{{ date('d-m-Y', strtotime($item->penyerahan_unit->date)) }}</a>
                                    @else
                                        <a style="text-decoration: underline; cursor: pointer;"
                                            class="toggle-modal" data-target-id="modalConfirmPenyerahanUnit"
                                            data-id-category="2"
                                            data-kategori="{{$item->kategori}}"
                                            data-id-doc="{{ $item->penyerahan_unit ? $item->penyerahan_unit->id : 0 }}"
                                            data-file="@isset($item->penyerahan_unit->file){{ $item->penyerahan_unit->file }}@endisset"
                                            data-confirm="1"
                                            data-tanggal="{{ date('d-m-Y', strtotime($item->penyerahan_unit->date)) }}"
                                            data-confirm_at="{{ $item->penyerahan_unit->confirm_at ? date('d-m-Y', strtotime($item->penyerahan_unit->confirm_at)) : '-' }}"
                                            href="#"
                                            onclick="showModal(this)">Menunggu konfirmasi cabang</a>
                                    @endif
                                @else
                                    <a style="text-decoration: underline; cursor: pointer;"
                                        class="toggle-modal" data-target-id="modalConfirmPenyerahanUnit"
                                        data-id-category="2"
                                        data-kategori="{{$item->kategori}}"
                                        data-id-doc="{{ $item->penyerahan_unit ? $item->penyerahan_unit->id : 0 }}"
                                        data-file="@isset($item->penyerahan_unit->file){{ $item->penyerahan_unit->file }}@endisset"
                                        data-confirm="1"
                                        data-tanggal="{{ date('d-m-Y', strtotime($item->penyerahan_unit->date)) }}"
                                        data-confirm_at="{{ $item->penyerahan_unit->confirm_at ? date('d-m-Y', strtotime($item->penyerahan_unit->confirm_at)) : '-' }}"
                                        href="#"
                                        onclick="showModal(this)">Menunggu konfirmasi cabang</a>
                                @endif
                            @endif
                        @else
                            <span class="text-info">Maksimal
                                {{ date('d-m-Y', strtotime($item->bukti_pembayaran->date . ' +1 day')) }}</span>
                        @endif
                    @else
                        @if ($item->penyerahan_unit)
                            @if ($item->imported_data_id)
                                @if (\Session::get(config('global.role_id_session')) == 3)
                                    @if ($item->penyerahan_unit->is_confirm)
                                        <a style="text-decoration: underline; cursor: pointer;"
                                            class="toggle-modal" data-target-id="modalConfirmPenyerahanUnit"
                                            data-id-category="2"
                                            data-kategori="{{$item->kategori}}"
                                            data-id-doc="{{ $item->penyerahan_unit ? $item->penyerahan_unit->id : 0 }}"
                                            data-file="@isset($item->penyerahan_unit->file){{ $item->penyerahan_unit->file }}@endisset"
                                            data-confirm="{{ $item->penyerahan_unit->is_confirm }}"
                                            data-tanggal="{{ date('d-m-Y', strtotime($item->penyerahan_unit->date)) }}"
                                            data-confirm_at="{{ $item->penyerahan_unit->confirm_at ? date('d-m-Y', strtotime($item->penyerahan_unit->confirm_at)) : '-' }}"
                                            href="#"
                                            onclick="showModal(this)">{{ date('d-m-Y', strtotime($item->penyerahan_unit->date)) }}</a>
                                    @else
                                        <a style="text-decoration: underline; cursor: pointer;"
                                            class="toggle-modal" data-target-id="modalConfirmPenyerahanUnit"
                                            data-id-category="2"
                                            data-kategori="{{$item->kategori}}"
                                            data-id-doc="{{ $item->penyerahan_unit ? $item->penyerahan_unit->id : 0 }}"
                                            data-file="@isset($item->penyerahan_unit->file){{ $item->penyerahan_unit->file }}@endisset"
                                            data-confirm="{{ $item->penyerahan_unit->is_confirm }}"
                                            data-tanggal="{{ date('d-m-Y', strtotime($item->penyerahan_unit->date)) }}"
                                            data-confirm_at="{{ $item->penyerahan_unit->confirm_at ? date('d-m-Y', strtotime($item->penyerahan_unit->confirm_at)) : '-' }}"
                                            href="#"
                                            onclick="showModal(this)">{{ date('d-m-Y', strtotime($item->penyerahan_unit->date)) }}</a>
                                    @endif
                                @elseif(\Session::get(config('global.role_id_session')) == 2 && \Session::get(config('global.user_role_session')) == $staf_analisa_kredit_role)
                                    @if ($item->penyerahan_unit->is_confirm)
                                        <a style="text-decoration: underline; cursor: pointer;"
                                            class="toggle-modal" data-target-id="modalConfirmPenyerahanUnit"
                                            data-id-category="2"
                                            data-kategori="{{$item->kategori}}"
                                            data-id-doc="{{ $item->penyerahan_unit ? $item->penyerahan_unit->id : 0 }}"
                                            data-file="@isset($item->penyerahan_unit->file){{ $item->penyerahan_unit->file }}@endisset"
                                            data-confirm="1"
                                            data-tanggal="{{ date('d-m-Y', strtotime($item->penyerahan_unit->date)) }}"
                                            data-confirm_at="{{ $item->penyerahan_unit->confirm_at ? date('d-m-Y', strtotime($item->penyerahan_unit->confirm_at)) : '-' }}"
                                            href="#"
                                            onclick="showModal(this)">{{ date('d-m-Y', strtotime($item->penyerahan_unit->date)) }}</a>
                                    @else
                                        Menunggu konfirmasi
                                    @endif
                                @else
                                -
                                @endif
                            @else
                                @if (\Session::get(config('global.role_id_session')) == 3)
                                    @if ($item->penyerahan_unit->is_confirm)
                                        <a style="text-decoration: underline; cursor: pointer;"
                                            class="toggle-modal" data-target-id="modalConfirmPenyerahanUnit"
                                            data-id-category="2"
                                            data-kategori="{{$item->kategori}}"
                                            data-id-doc="{{ $item->penyerahan_unit ? $item->penyerahan_unit->id : 0 }}"
                                            data-file="@isset($item->penyerahan_unit->file){{ $item->penyerahan_unit->file }}@endisset"
                                            data-confirm="{{ $item->penyerahan_unit->is_confirm }}"
                                            data-tanggal="{{ date('d-m-Y', strtotime($item->penyerahan_unit->date)) }}"
                                            data-confirm_at="{{ $item->penyerahan_unit->confirm_at ? date('d-m-Y', strtotime($item->penyerahan_unit->confirm_at)) : '-' }}"
                                            href="#"
                                            onclick="showModal(this)">{{ date('d-m-Y', strtotime($item->penyerahan_unit->date)) }}</a>
                                    @else
                                        <a style="text-decoration: underline; cursor: pointer;"
                                            class="toggle-modal" data-target-id="modalConfirmPenyerahanUnit"
                                            data-id-category="2"
                                            data-kategori="{{$item->kategori}}"
                                            data-id-doc="{{ $item->penyerahan_unit ? $item->penyerahan_unit->id : 0 }}"
                                            data-file="@isset($item->penyerahan_unit->file){{ $item->penyerahan_unit->file }}@endisset"
                                            data-confirm="{{ $item->penyerahan_unit->is_confirm }}"
                                            data-tanggal="{{ date('d-m-Y', strtotime($item->penyerahan_unit->date)) }}"
                                            data-confirm_at="{{ $item->penyerahan_unit->confirm_at ? date('d-m-Y', strtotime($item->penyerahan_unit->confirm_at)) : '-' }}"
                                            href="#"
                                            onclick="showModal(this)">{{ date('d-m-Y', strtotime($item->penyerahan_unit->date)) }}</a>
                                    @endif
                                @elseif(\Session::get(config('global.role_id_session')) == 2 && \Session::get(config('global.user_role_session')) == $staf_analisa_kredit_role)
                                    @if ($item->penyerahan_unit->is_confirm)
                                        <a style="text-decoration: underline; cursor: pointer;"
                                            class="toggle-modal" data-target-id="modalConfirmPenyerahanUnit"
                                            data-id-category="2"
                                            data-kategori="{{$item->kategori}}"
                                            data-id-doc="{{ $item->penyerahan_unit ? $item->penyerahan_unit->id : 0 }}"
                                            data-file="@isset($item->penyerahan_unit->file){{ $item->penyerahan_unit->file }}@endisset"
                                            data-confirm="1"
                                            data-tanggal="{{ date('d-m-Y', strtotime($item->penyerahan_unit->date)) }}"
                                            data-confirm_at="{{ $item->penyerahan_unit->confirm_at ? date('d-m-Y', strtotime($item->penyerahan_unit->confirm_at)) : '-' }}"
                                            href="#"
                                            onclick="showModal(this)">{{ date('d-m-Y', strtotime($item->penyerahan_unit->date)) }}</a>
                                    @else
                                        Menunggu konfirmasi
                                    @endif
                                @else
                                -
                                @endif
                            @endif
                        @else
                            <span class="text-info">Maksimal
                                {{ date('d-m-Y', strtotime($item->bukti_pembayaran->date . ' +1 day')) }}</span>
                        @endif
                    @endif
                @else
                    -
                @endif
            @else
                <span class="text-danger">-</span>
            @endif
        </td>
        {{--  Bukti Pembayaran Imbal Jasa  --}}
        <td>
            @if (\Session::get(config('global.role_id_session')) == 3)
                {{--  vendor  --}}
                @if ($is_kredit_page)
                    @if ($item->bukti_pembayaran)
                        @if (property_exists($item->bukti_pembayaran, 'is_confirm'))
                            @if ($item->bukti_pembayaran->is_confirm)
                                @if ($item->penyerahan_unit)
                                    @if ($item->penyerahan_unit->is_confirm)
                                        @if ($item->imbal_jasa)
                                            @if (!$item->imbal_jasa->is_confirm)
                                                <a style="cursor: pointer; text-decoration: underline;"
                                                    class="confirm-imbal-jasa toggle-modal-confirm-imbal-jasa text-red-600" data-target-id="modalConfirmImbalJasa"
                                                    data-id="{{ $item->imbal_jasa->id }}"
                                                    data-file="@isset($item->imbal_jasa->file){{ $item->imbal_jasa->file }}@endisset"
                                                    data-tanggal="{{ \Carbon\Carbon::parse($item->imbal_jasa->created_at)->format('d-m-Y') }}"
                                                    data-nominal="Rp @if(property_exists($item, 'nominal_imbal_jasa')) {{ number_format($item->nominal_imbal_jasa, 0, '', '.') }} @endif"
                                                    data-confirm="{{ $item->imbal_jasa->is_confirm }}"
                                                    href="#"
                                                    onclick="showModal(this)">Konfirmasi Bukti Pembayaran</a>
                                            @elseif ($item->imbal_jasa->is_confirm)
                                                <a class="bukti-pembayaran-modal toggle-modal-confirm-imbal-jasa"
                                                    style="cursor: pointer; text-decoration: underline;"
                                                    data-target-id="modalConfirmImbalJasa"
                                                    data-confirm="{{ $item->imbal_jasa->is_confirm }}"
                                                    data-tanggal="{{ \Carbon\Carbon::parse($item->imbal_jasa->date)->format('d-m-Y') }}"
                                                    data-nominal="Rp @if(property_exists($item, 'nominal_imbal_jasa')) {{ number_format($item->nominal_imbal_jasa, 0, '', '.') }} @endif"
                                                    data-confirm_at="{{ \Carbon\Carbon::parse($item->imbal_jasa->confirm_at)->format('d-m-Y') }}"
                                                    data-file="@isset($item->imbal_jasa->file){{ $item->imbal_jasa->file }}@endisset"
                                                    onclick="showModal(this)">Selesai</a>
                                            @else
                                                Menunggu Pembayaran dari Cabang
                                            @endif
                                        @else
                                            Menunggu pembayaran imbal jasa
                                        @endif
                                    @else
                                        Menunggu konfirmasi penyerahan unit
                                    @endif
                                @else
                                    Menunggu penyerahan unit
                                @endif
                            @else
                                -
                            @endif
                        @else
                            -
                        @endif
                    @else
                        -
                    @endif
                @else
                    @if ($item->imbal_jasa)
                        @if (!$item->imbal_jasa->is_confirm)
                            Menunggu konfirmasi
                        @elseif ($item->imbal_jasa->is_confirm)
                            <a class="bukti-pembayaran-modal toggle-modal-confirm-imbal-jasa"
                                style="cursor: pointer; text-decoration: underline;"
                                data-target-id="modalConfirmImbalJasa"
                                data-confirm="{{ $item->imbal_jasa->is_confirm }}"
                                data-tanggal="{{ \Carbon\Carbon::parse($item->imbal_jasa->date)->format('d-m-Y') }}"
                                data-nominal="Rp @if(property_exists($item, 'nominal_imbal_jasa')) {{ number_format($item->nominal_imbal_jasa, 0, '', '.') }} @endif"
                                data-confirm_at="{{ \Carbon\Carbon::parse($item->imbal_jasa->confirm_at)->format('d-m-Y') }}"
                                data-file="@isset($item->imbal_jasa->file){{ $item->imbal_jasa->file }}@endisset"
                                onclick="showModal(this)">Selesai</a>
                        @else
                            Menunggu Pembayaran dari Cabang
                        @endif
                    @else
                        -
                    @endif
                @endif
            @else
                {{--  role selain vendor  --}}
                @if ($is_kredit_page)
                    @if ($item->bukti_pembayaran)
                        @if (property_exists($item->bukti_pembayaran, 'is_confirm'))
                            @if ($item->bukti_pembayaran->is_confirm)
                                @if ($item->penyerahan_unit)
                                    @if ($item->penyerahan_unit->is_confirm)
                                        @if (!$item->imbal_jasa)
                                            @if (\Session::get(config('global.user_role_session')) == $staf_analisa_kredit_role)
                                                <a href="#"
                                                    style="text-decoration: underline; cursor: pointer;"
                                                    class="toggle-modal-upload-imbal-jasa underline text-red-600"
                                                    data-target-id="modalUploadImbalJasa"
                                                    data-id="{{ $item->id }}"
                                                    data-nominal="Rp @if(property_exists($item, 'nominal_imbal_jasa')) {{ number_format($item->nominal_imbal_jasa, 0, '', '.') }} @endif"
                                                    onclick="showModal(this)">Bayar</a>
                                            @else
                                                -
                                            @endif
                                        @else
                                            @if (!$item->imbal_jasa->is_confirm)
                                                <p class="m-0">Menunggu Konfirmasi Vendor</p>
                                            @elseif ($item->imbal_jasa->is_confirm)
                                                <a class="bukti-pembayaran-modal toggle-modal-confirm-imbal-jasa"
                                                    style="cursor: pointer; text-decoration: underline;"
                                                    data-target-id="modalConfirmImbalJasa"
                                                    data-confirm="{{ $item->imbal_jasa->is_confirm }}"
                                                    data-tanggal="{{ \Carbon\Carbon::parse($item->imbal_jasa->date)->format('d-m-Y') }}"
                                                    data-nominal="Rp @if(property_exists($item, 'nominal_imbal_jasa')) {{ number_format($item->nominal_imbal_jasa, 0, '', '.') }} @endif"
                                                    data-confirm_at="{{ \Carbon\Carbon::parse($item->imbal_jasa->confirm_at)->format('d-m-Y') }}"
                                                    data-file="@isset($item->imbal_jasa->file){{ $item->imbal_jasa->file }}@endisset"
                                                    onclick="showModal(this)">Selesai</a>
                                            @endif
                                        @endif
                                    @else
                                        Menunggu konfirmasi penyerahan unit
                                    @endif
                                @else
                                    Menunggu penyerahan unit
                                @endif
                            @else
                                -
                            @endif
                        @else
                            -
                        @endif
                    @else
                        -
                    @endif
                @else
                    -
                @endif
            @endif
        </td>
        {{--  Nominal Imbal Jasa  --}}
        <td>
            @if ($item->penyerahan_unit)
                @if ($is_kredit_page)
                    @if (property_exists($item, 'imbal_jasa'))
                        @if ($item->imbal_jasa)
                            @if ($item->imported_data_id && $item->imbal_jasa->is_confirm)
                                <a>Rp {{ number_format($item->nominal_pembayaran_imbal_jasa, 0, '', '.') }}</a>
                            @else
                                @if (\Session::get(config('global.role_id_session')) == 3)
                                    <span class="text-info">Silahkan konfirmasi bukti transfer imbal jasa</span>
                                @else
                                    <span>Rp {{ number_format($item->nominal_pembayaran_imbal_jasa, 0, '', '.') }}</span>
                                @endif
                            @endif
                        @else
                            -
                        @endif
                    @else
                        -
                    @endif
                @else
                    @if ($item->imbal_jasa)
                        @if ($item->imported_data_id && $item->imbal_jasa->is_confirm)
                            <a>Rp {{ number_format($item->nominal_pembayaran_imbal_jasa, 0, '', '.') }}</a>
                        @else
                            @if (\Session::get(config('global.role_id_session')) == 3)
                                <span class="text-info">Silahkan konfirmasi bukti transfer imbal jasa</span>
                            @else
                                <span>Rp {{ number_format($item->nominal_pembayaran_imbal_jasa, 0, '', '.') }}</span>
                            @endif
                        @endif
                    @else
                        @if ($item->imbal_jasa)
                            @if ($item->imported_data_id && $item->imbal_jasa->is_confirm)
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
        {{--  STNK  --}}
        <td>
            @if ($item->penyerahan_unit)
                @if ($is_kredit_page)
                    @if (property_exists($item->penyerahan_unit, 'is_confirm'))
                        @if ($item->penyerahan_unit->is_confirm)
                            @if ($item->imbal_jasa)
                                @if ($item->imbal_jasa->is_confirm)
                                    @if ($item->stnk)
                                        @if ($item->imported_data_id && $item->stnk->is_confirm)
                                            <a style="text-decoration: underline; cursor: pointer;"
                                                class="open-po detailFileStnk" data-toggle="modal"
                                                data-target="#detailStnk"
                                                data-file="@if($item->stnk->file){{ $item->stnk->file }}@endif"
                                                data-confirm="{{ $item->stnk->is_confirm }}"
                                                data-tanggal="{{ date('d-m-Y', strtotime($item->stnk->date)) }}"
                                                data-confirm_at="{{ date('d-m-Y', strtotime($item->stnk->confirm_at)) }}">{{ date('d-m-Y', strtotime($item->stnk->date)) }}</a>
                                        @else
                                            <span class="text-warning">Menunggu konfirmasi</span>
                                        @endif
                                    @else
                                        @if (\Session::get(config('global.role_id_session')) == 3)
                                            @if ($item->imbal_jasa->is_confirm)
                                                <span class="text-info">Maksimal
                                                    {{ date('d-m-Y', strtotime($item->imbal_jasa->date . ' +1 month')) }}</span>
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
                                -
                            @endif
                        @else
                            -
                        @endif
                    @else
                        -
                    @endif
                @else
                    @if ($item->penyerahan_unit->is_confirm)
                        @if ($item->imbal_jasa)
                            @if ($item->imbal_jasa->is_confirm)
                                @if ($item->stnk)
                                    @if ($item->imported_data_id && $item->stnk->is_confirm)
                                        <a style="text-decoration: underline; cursor: pointer;"
                                            class="open-po detailFileStnk" data-toggle="modal"
                                            data-target="#detailStnk"
                                            data-file="@if($item->stnk->file){{ $item->stnk->file }}@endif"
                                            data-confirm="{{ $item->stnk->is_confirm }}"
                                            data-tanggal="{{ date('d-m-Y', strtotime($item->stnk->date)) }}"
                                            data-confirm_at="{{ date('d-m-Y', strtotime($item->stnk->confirm_at)) }}">{{ date('d-m-Y', strtotime($item->stnk->date)) }}</a>
                                    @else
                                        <span class="text-warning">Menunggu konfirmasi</span>
                                    @endif
                                @else
                                    @if (\Session::get(config('global.role_id_session')) == 3)
                                        @if ($item->penyerahan_unit->is_confirm)
                                            <span class="text-info">Maksimal
                                                {{ date('d-m-Y', strtotime($item->imbal_jasa->date . ' +1 month')) }}</span>
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
                            -
                        @endif
                    @else
                        -
                    @endif
                @endif
            @else
                <span class="text-warning">-</span>
            @endif
        </td>
        {{--  BPKB  --}}
        <td>
            @if ($item->penyerahan_unit)
                @if ($is_kredit_page)
                    @if (property_exists($item->penyerahan_unit, 'is_confirm'))
                        @if ($item->penyerahan_unit->is_confirm)
                            @if ($item->imbal_jasa)
                                @if ($item->imbal_jasa->is_confirm)
                                    @if ($item->bpkb)
                                        @if ($item->imported_data_id && $item->bpkb->is_confirm)
                                            <a style="text-decoration: underline; cursor: pointer;"
                                                class="open-po detailFileBpkb" data-toggle="modal"
                                                data-target="#detailBpkb"
                                                data-file="@if($item->bpkb->file){{ $item->bpkb->file }}@endif"
                                                data-confirm="{{ $item->bpkb->is_confirm }}"
                                                data-tanggal="{{ date('d-m-Y', strtotime($item->bpkb->date)) }}"
                                                data-confirm_at="{{ date('d-m-Y', strtotime($item->bpkb->confirm_at)) }}">{{ date('d-m-Y', strtotime($item->bpkb->date)) }}</a>
                                        @else
                                            <span class="text-warning">Menunggu konfirmasi</span>
                                        @endif
                                    @else
                                        @if (\Session::get(config('global.role_id_session')) == 3)
                                            @if ($item->penyerahan_unit->is_confirm)
                                                <span class="text-info">Maksimal
                                                    {{ date('d-m-Y', strtotime($item->imbal_jasa->date . ' +3 month')) }}</span>
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
                                -
                            @endif
                        @else
                            -
                        @endif
                    @else
                        -
                    @endif
                @else
                    @if ($item->penyerahan_unit->is_confirm)
                        @if ($item->imbal_jasa)
                            @if ($item->imbal_jasa->is_confirm)
                                @if ($item->bpkb)
                                    @if ($item->imported_data_id && $item->bpkb->is_confirm)
                                        <a style="text-decoration: underline; cursor: pointer;"
                                            class="open-po detailFileBpkb" data-toggle="modal"
                                            data-target="#detailBpkb"
                                            data-file="@if($item->bpkb->file){{ $item->bpkb->file }}@endif"
                                            data-confirm="{{ $item->bpkb->is_confirm }}"
                                            data-tanggal="{{ date('d-m-Y', strtotime($item->bpkb->date)) }}"
                                            data-confirm_at="{{ date('d-m-Y', strtotime($item->bpkb->confirm_at)) }}">{{ date('d-m-Y', strtotime($item->bpkb->date)) }}</a>
                                    @else
                                        <span class="text-warning">Menunggu konfirmasi</span>
                                    @endif
                                @else
                                    @if (\Session::get(config('global.role_id_session')) == 3)
                                        @if ($item->penyerahan_unit->is_confirm)
                                            <span class="text-info">Maksimal
                                                {{ date('d-m-Y', strtotime($item->imbal_jasa->date . ' +3 month')) }}</span>
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
                            -
                        @endif
                    @else
                        -
                    @endif
                @endif
            @else
                <span class="text-warning">-</span>
            @endif
        </td>
        {{--  Polis  --}}
        <td>
            @if ($item->penyerahan_unit)
                @if ($is_kredit_page)
                    @if (property_exists($item->penyerahan_unit, 'is_confirm'))
                        @if ($item->penyerahan_unit->is_confirm)
                            @if ($item->imbal_jasa)
                                @if ($item->imbal_jasa->is_confirm)
                                    @if ($item->polis)
                                        @if ($item->imported_data_id && $item->polis->is_confirm)
                                            <a style="text-decoration: underline; cursor: pointer;"
                                                class="open-po detailFilePolis" data-toggle="modal"
                                                data-target="#detailPolis"
                                                data-file="@if($item->polis->file){{ $item->polis->file }}@endif"
                                                data-confirm="{{ $item->polis->is_confirm }}"
                                                data-tanggal="{{ date('d-m-Y', strtotime($item->polis->date)) }}"
                                                data-confirm_at="{{ date('d-m-Y', strtotime($item->polis->confirm_at)) }}">{{ date('d-m-Y', strtotime($item->polis->date)) }}</a>
                                        @else
                                            <span class="text-warning">Menunggu konfirmasi</span>
                                        @endif
                                    @else
                                        @if (\Session::get(config('global.role_id_session')) == 3)
                                            @if ($item->penyerahan_unit->is_confirm)
                                                <span class="text-info">Maksimal
                                                    {{ date('d-m-Y', strtotime($item->imbal_jasa->date . ' +3 month')) }}</span>
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
                                -
                            @endif
                        @else
                            -
                        @endif
                    @else
                        -
                    @endif
                @else
                    @if ($item->penyerahan_unit->is_confirm)
                        @if ($item->imbal_jasa)
                            @if ($item->imbal_jasa->is_confirm)
                                @if ($item->polis)
                                    @if ($item->imported_data_id && $item->polis->is_confirm)
                                        <a style="text-decoration: underline; cursor: pointer;"
                                            class="open-po detailFilePolis" data-toggle="modal"
                                            data-target="#detailPolis"
                                            data-file="@if($item->polis->file){{ $item->polis->file }}@endif"
                                            data-confirm="{{ $item->polis->is_confirm }}"
                                            data-tanggal="{{ date('d-m-Y', strtotime($item->polis->date)) }}"
                                            data-confirm_at="{{ date('d-m-Y', strtotime($item->polis->confirm_at)) }}">{{ date('d-m-Y', strtotime($item->polis->date)) }}</a>
                                    @else
                                        <span class="text-warning">Menunggu konfirmasi</span>
                                    @endif
                                @else
                                    @if (\Session::get(config('global.role_id_session')) == 3)
                                        @if ($item->penyerahan_unit->is_confirm)
                                            <span class="text-info">Maksimal
                                                {{ date('d-m-Y', strtotime($item->imbal_jasa->date . ' +3 month')) }}</span>
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
                            -
                        @endif
                    @else
                        -
                    @endif
                @endif
            @else
                <span class="text-warning">-</span>
            @endif
        </td>
        {{--  Status  --}}
        <td class="text-center">
            <span class="@if (strtolower($item->status) == 'done') text-blue-600 @else text-grey @endif">{{ ucwords($item->status) }}</span>
        </td>
        {{--  Aksi  --}}
        <td>
            @if ($is_kredit_page)
                @if (strtolower($item->status) == 'done')
                    <button class="px-4 py-2 bg-theme-btn/10 rounded text-theme-btn toggle-modals"
                        data-target-id="modalDetailPo" data-id="{{ $item->id }}" data-is-import="true" onclick="showModal(this)">
                        Detail
                    </button>
                @else
                    <div class="dropdown">
                        <button class="px-4 py-2 bg-theme-btn/10 rounded text-theme-btn">
                            Selengkapnya
                        </button>
                        <ul class="dropdown-menu  right-20">
                            @if ($item->tgl_ketersediaan_unit)
                                @if ($item->bukti_pembayaran)
                                    @if (property_exists($item->bukti_pembayaran, 'is_confirm'))
                                        @if (!$item->penyerahan_unit && $item->bukti_pembayaran->is_confirm && $role_id == 3)
                                            <li>
                                                <a href="#" class="item-dropdown toggle-modal"
                                                    data-target-id="modalUploadBuktiPenyerahanUnit"
                                                    data-id_kkb="{{ $item->kkb_id }}"
                                                    onclick="showModal(this)">Kirim Unit</a>
                                            </li>
                                        @endif
                                    @endif
                                @endif
                            @endif
                            {{--  Upload Berkas  --}}
                            @if ($role_id == 3 && $item->penyerahan_unit)
                                @if (property_exists($item->penyerahan_unit, 'is_confirm'))
                                    @if ($item->penyerahan_unit->is_confirm)
                                        @if ($item->imbal_jasa)
                                            @if ($item->imbal_jasa->is_confirm)
                                                @if (!$item->stnk || !$item->polis || !$item->bpkb)
                                                    {{--  Vendor  --}}
                                                    <li>
                                                        <a class="item-dropdown toggle-modal"
                                                            data-target-id="modalUploadBerkas"
                                                            data-id_kkb="{{ $item->kkb_id }}"
                                                            data-no-stnk="@isset($item->stnk->text){{ $item->stnk->text }}@endisset"
                                                            data-file-stnk="@isset($item->stnk->file){{ $item->stnk->file }}@endisset"
                                                            data-date-stnk="@isset($item->stnk->date){{ date('d-m-Y', strtotime($item->stnk->date)) }}@endisset"
                                                            data-confirm-stnk="@isset($item->stnk->is_confirm){{ $item->stnk->is_confirm }}@endisset"
                                                            data-confirm-at-stnk="@isset($item->stnk->confirm_at){{ date('d-m-Y', strtotime($item->stnk->confirm_at)) }}@endisset"
                                                            data-no-polis="@isset($item->polis->text){{ $item->polis->text }}@endisset"
                                                            data-file-polis="@isset($item->polis->file){{ $item->polis->file }}@endisset"
                                                            data-date-polis="@isset($item->polis->date){{ date('d-m-Y', strtotime($item->polis->date)) }}@endisset"
                                                            data-confirm-polis="@isset($item->polis->is_confirm){{ $item->polis->is_confirm }}@endisset"
                                                            data-confirm-at-polis="@isset($item->polis->confirm_at){{ date('d-m-Y', strtotime($item->polis->confirm_at)) }}@endisset"
                                                            data-no-bpkb="@isset($item->bpkb->text){{ $item->bpkb->text }}@endisset"
                                                            data-file-bpkb="@isset($item->bpkb->file){{ $item->bpkb->file }}@endisset"
                                                            data-date-bpkb="@isset($item->bpkb->date){{ date('d-m-Y', strtotime($item->bpkb->date)) }}@endisset"
                                                            data-confirm-bpkb="@isset($item->bpkb->is_confirm){{ $item->bpkb->is_confirm }}@endisset"
                                                            data-confirm-at-bpkb="@isset($item->bpkb->confirm_at){{ date('d-m-Y', strtotime($item->bpkb->confirm_at)) }}@endisset"
                                                            href="#"
                                                            onclick="showModal(this)">
                                                            Upload Berkas
                                                        </a>
                                                    </li>
                                                @endif
                                            @endif
                                        @endif
                                    @endif
                                @endif
                            @endif
                            @if (\Session::get(config('global.role_id_session')) == 2 && \Session::get(config('global.user_role_session')) == $staf_analisa_kredit_role)
                                {{--  Cabang  --}}
                                @if (property_exists($item, 'imbal_jasa'))
                                    @if ($item->imbal_jasa)
                                        @if ($item->imbal_jasa->is_confirm)
                                            @if ($item->stnk || $item->polis || $item->bpkb)
                                                @if (
                                                    (isset($item->stnk->is_confirm) && !$item->stnk->is_confirm) ||
                                                        (isset($item->polis->is_confirm) && !$item->polis->is_confirm) ||
                                                        (isset($item->bpkb->is_confirm) && !$item->bpkb->is_confirm))
                                                    <li>
                                                        <a class="item-dropdown toggle-modal"
                                                            data-target-id="modalUploadBerkas"
                                                            data-id_kkb="{{ $item->kkb_id }}"
                                                            data-id-stnk="@if ($item->stnk) {{ $item->stnk->id }}@else- @endif"
                                                            data-id-polis="@if ($item->polis) {{ $item->polis->id }}@else- @endif"
                                                            data-id-bpkb="@if ($item->bpkb) {{ $item->bpkb->id }}@else- @endif"
                                                            data-no-stnk="@isset($item->stnk->text){{ $item->stnk->text }}@endisset"
                                                            data-file-stnk="@isset($item->stnk->file){{ $item->stnk->file }}@endisset"
                                                            data-date-stnk="@isset($item->stnk->date){{ date('d-m-Y', strtotime($item->stnk->date)) }}@endisset"
                                                            data-confirm-stnk="@isset($item->stnk->is_confirm){{ $item->stnk->is_confirm }}@endisset"
                                                            data-confirm-at-stnk="@isset($item->stnk->confirm_at){{ date('d-m-Y', strtotime($item->stnk->confirm_at)) }}@endisset"
                                                            data-no-polis="@isset($item->polis->text){{ $item->polis->text }}@endisset"
                                                            data-file-polis="@isset($item->polis->file){{ $item->polis->file }}@endisset"
                                                            data-date-polis="@isset($item->polis->date){{ date('d-m-Y', strtotime($item->polis->date)) }}@endisset"
                                                            data-confirm-polis="@isset($item->polis->is_confirm){{ $item->polis->is_confirm }}@endisset"
                                                            data-confirm-at-polis="@isset($item->polis->confirm_at){{ date('d-m-Y', strtotime($item->polis->confirm_at)) }}@endisset"
                                                            data-no-bpkb="@isset($item->bpkb->text){{ $item->bpkb->text }}@endisset"
                                                            data-file-bpkb="@isset($item->bpkb->file){{ $item->bpkb->file }}@endisset"
                                                            data-date-bpkb="@isset($item->bpkb->date){{ date('d-m-Y', strtotime($item->bpkb->date)) }}@endisset"
                                                            data-confirm-bpkb="@isset($item->bpkb->is_confirm){{ $item->bpkb->is_confirm }}@endisset"
                                                            data-confirm-at-bpkb="@isset($item->bpkb->confirm_at){{ date('d-m-Y', strtotime($item->bpkb->confirm_at)) }}@endisset"
                                                            href="#"
                                                            onclick="showModal(this)">
                                                            Konfirmasi Berkas
                                                        </a>
                                                    </li>
                                                @endif
                                            @endif
                                        @endif
                                    @endif
                                @endif
                            @endif
                            <li>
                                <a class="item-dropdown toggle-modals"
                                    data-target-id="modalDetailPo" data-id="{{ $item->id }}"
                                    data-is-import="true" href="#" onclick="showModal(this)">Detail</a>
                            </li>
                        </ul>
                    </div>
                @endif
            @else
                <button class="px-4 py-2 bg-theme-btn/10 rounded text-theme-btn toggle-modals"
                    data-target-id="modalDetailPo" data-is-import="true" data-id="{{ $item->id }}" onclick="showModal(this)">
                    Detail
                </button>
            @endif
        </td>
    </tr>
@empty
    <tr>
        <td colspan="{{ 7 + count($documentCategories) }}" class="text-center">
            <span class="text-danger">Maaf data belum tersedia.</span>
        </td>
    </tr>
@endforelse
