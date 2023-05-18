@extends('layout.master')

@section('title', $title)

@push('extraStyle')
    <style>
        .pdfobject-container {
            height: 50rem;
            border: 1rem solid rgba(0, 0, 0, .1);
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
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table mt-3">
                                <thead>
                                    <tr class="bg-danger text-light">
                                        <th scope="col">No</th>
                                        <th scope="col">Nama</th>
                                        <th scope="col">PO</th>
                                        <th scope="col">Ketersediaan Unit</th>
                                        <th scope="col">Penyerahan Unit</th>
                                        {{--  <th scope="col">STNK</th>
                                        <th scope="col">Polis</th>
                                        <th scope="col">BPKB</th>  --}}
                                        @foreach ($documentCategories as $item)
                                            <th scope="col">{{ $item->name }}</th>
                                        @endforeach
                                        <th scope="col">Imbal Jasa</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($data as $item)
                                        @php
                                            $buktiPembayaran = \App\Models\Document::where('kredit_id', $item->kkb_id)
                                                ->where('document_category_id', 1)
                                                ->first();
                                            $penyerahanUnit = \App\Models\Document::where('kredit_id', $item->kkb_id)
                                                ->where('document_category_id', 2)
                                                ->first();
                                            $stnk = \App\Models\Document::where('kredit_id', $item->kkb_id)
                                                ->where('document_category_id', 3)
                                                ->first();
                                            $polis = \App\Models\Document::where('kredit_id', $item->kkb_id)
                                                ->where('document_category_id', 4)
                                                ->first();
                                            $bpkb = \App\Models\Document::where('kredit_id', $item->kkb_id)
                                                ->where('document_category_id', 5)
                                                ->first();
                                        @endphp
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>Rio Ardiansyah</td>
                                            <td class="@if (!$buktiPembayaran) link-po @endif}">
                                                @if ($buktiPembayaran)
                                                    2AFda12j7s
                                                @else
                                                    <a data-toggle="modal" data-target="#buktiPembayaranModal"
                                                        data-id_kkb="{{ $item->kkb_id }}" href="#"
                                                        onclick="uploadBuktiPembayaran({{ $item->kkb_id }})">Atur</a>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($buktiPembayaran)
                                                    @if ($buktiPembayaran->is_confirm)
                                                        @if ($item->tgl_ketersediaan_unit)
                                                            {{ $item->tgl_ketersediaan_unit }}
                                                        @else
                                                            @if (Auth::user()->vendor_id)
                                                                <a data-toggle="modal" data-target="#tglModal"
                                                                    data-id_kkb="{{ $item->kkb_id }}"
                                                                    href="#">Atur</a>
                                                            @endif
                                                        @endif
                                                    @else
                                                        <span class="text-danger">Menunggu konfirmasi bukti
                                                            pembayaran</span>
                                                    @endif
                                                @else
                                                    <span class="text-danger">Menunggu upload bukti pembayaran</span>
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
                                                    <span class="text-danger">Menunggu tanggal ketersediaan unit</span>
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
                                                        <span class="text-info">Maksimal tanggal upload STNK
                                                            {{ date('Y-m-d', strtotime($penyerahanUnit->date . ' +1 month')) }}</span>
                                                    @endif
                                                @else
                                                    <span class="text-danger">Menunggu tanggal ketersediaan unit</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($penyerahanUnit)
                                                    @if ($polis)
                                                        @if ($polis->file && $polis->is_confirm)
                                                            <a href="/storage/dokumentasi-polis/{{ $polis->file }}"
                                                                target="_blank">{{ $polis->date }}</a>
                                                        @else
                                                            <span class="text-warning">Menunggu konfirmasi</span>
                                                        @endif
                                                    @else
                                                        <span class="text-info">Maksimal tanggal upload Polis
                                                            {{ date('Y-m-d', strtotime($penyerahanUnit->date . ' +1 month')) }}</span>
                                                    @endif
                                                @else
                                                    <span class="text-danger">Menunggu tanggal ketersediaan unit</span>
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
                                                        <span class="text-info">Maksimal tanggal upload Polis
                                                            {{ date('Y-m-d', strtotime($penyerahanUnit->date . ' +3 month')) }}</span>
                                                    @endif
                                                @else
                                                    <span class="text-danger">Menunggu tanggal ketersediaan unit</span>
                                                @endif
                                            </td>
                                            <td>Rp.5000</td>
                                            <td
                                                class="@if ($item->status == 'done') text-success @else text-info @endif">
                                                {{ $item->status }}</td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-info dropdown-toggle" type="button"
                                                        data-toggle="dropdown" aria-expanded="false">
                                                        Selengkapnya
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        @if (!$buktiPembayaran)
                                                            <a class="dropdown-item" data-toggle="modal"
                                                                data-target="#buktiPembayaranModal"
                                                                data-id_kkb="{{ $item->kkb_id }}" href="#"
                                                                onclick="uploadBuktiPembayaran({{ $item->kkb_id }})">Upload
                                                                Bukti Pembayaran</a>
                                                        @endif
                                                        @if ($buktiPembayaran)
                                                            @if ($buktiPembayaran->file && !$buktiPembayaran->is_confirm)
                                                                <a class="dropdown-item confirm-stnk" data-toggle="modal"
                                                                    data-id-category="4"
                                                                    data-id-doc="{{ $buktiPembayaran ? $buktiPembayaran->id : 0 }}"
                                                                    href="#confirmModalVendor">Konfirmasi Bukti
                                                                    Pembayaran</a>
                                                            @endif
                                                        @endif
                                                        @if ($item->tgl_ketersediaan_unit)
                                                            @if (!$penyerahanUnit)
                                                                <a data-toggle="modal" data-target="#tglModalPenyerahan"
                                                                    data-id_kkb="{{ $item->kkb_id }}" href="#"
                                                                    class="dropdown-item"
                                                                    onclick="setPenyerahan({{ $item->kkb_id }})">Kirim
                                                                    Unit</a>
                                                            @endif
                                                        @endif
                                                        {{--  STNK  --}}
                                                        {{--  @if ($stnk)
                                                            @if ($stnk->file && !$stnk->is_confirm && Auth::user()->role_id == 2)
                                                                <a class="dropdown-item confirm-stnk" data-toggle="modal"
                                                                    data-id-category="4"
                                                                    data-id-doc="{{ $stnk ? $stnk->id : 0 }}"
                                                                    href="#confirmModal">Konfirmasi STNK</a>
                                                            @else
                                                                <a href="/storage/dokumentasi-stnk/{{ $stnk->file }}"
                                                                    target="_blank">{{ $stnk->date }}</a>
                                                            @endif
                                                        @elseif(!$stnk && Auth::user()->vendor_id != null)
                                                            <a data-toggle="modal" data-target="#uploadStnkModal"
                                                                data-id_kkb="{{ $item->kkb_id }}" href="#" class="dropdown-item"
                                                                onclick="uploadStnk({{ $item->kkb_id }})">Upload STNK</a>
                                                        @else
                                                        @endif  --}}
                                                        {{--  End STNK  --}}
                                                        {{--  Polis  --}}
                                                        {{--  @if ($polis)
                                                            @if ($polis->file && !$polis->is_confirm)
                                                                <a class="dropdown-item confirm-police" data-toggle="modal"
                                                                    data-id-category="2"
                                                                    data-id-doc="{{ $polis ? $polis->id : 0 }}"
                                                                    href="#confirmModal">Konfirmasi Polis</a>
                                                            @endif
                                                        @else
                                                        <a data-toggle="modal" data-target="#uploadPolisModal"
                                                                data-id_kkb="{{ $item->kkb_id }}" href="#" class="dropdown-item"
                                                                onclick="uploadPolis({{ $item->kkb_id }})">Upload Polis</a>
                                                        @endif  --}}
                                                        {{--  End Polis  --}}
                                                        {{--  BPKB  --}}
                                                        {{--  @if ($bpkb)
                                                            @if ($bpkb->file && !$bpkb->is_confirm)
                                                                <a class="dropdown-item confirm-bpkb" data-toggle="modal"
                                                                    data-id-category="3"
                                                                    data-id-doc="{{ $bpkb ? $bpkb->id : 0 }}"
                                                                    href="#confirmModal">Konfirmasi BPKB</a>
                                                            @endif
                                                        @else
                                                        <a data-toggle="modal" data-target="#uploadPolisModal"
                                                            data-id_kkb="{{ $item->kkb_id }}" href="#" class="dropdown-item"
                                                            onclick="uploadPolis({{ $item->kkb_id }})">Upload BPKB</a>
                                                        @endif  --}}
                                                        {{--  End BPKB  --}}
                                                        {{--  Upload Berkas  --}}
                                                        @if (Auth::user()->role_id == 3)
                                                            {{--  Vendor  --}}
                                                            <a data-toggle="modal" data-target="#uploadBerkasModal"
                                                                data-id_kkb="{{ $item->kkb_id }}"
                                                                data-no-stnk="@isset($stnk->text){{ $stnk->text }}@endisset"
                                                                data-file-stnk="@isset($stnk->file){{ $stnk->file }}@endisset"
                                                                data-no-polis="@isset($polis->text){{ $polis->text }}@endisset"
                                                                data-file-polis="@isset($polis->file){{ $polis->file }}@endisset"
                                                                data-no-bpkb="@isset($bpkb->text){{ $bpkb->text }}@endisset"
                                                                data-file-bpkb="@isset($bpkb->file){{ $bpkb->file }}@endisset"
                                                                href="#" class="dropdown-item upload-berkas">
                                                                Upload Berkas
                                                            </a>
                                                        @endif
                                                        @if (Auth::user()->role_id == 2)
                                                            {{--  Cabang  --}}
                                                            @if ($stnk || $polis || $bpkb)
                                                                @if (!$stnk->is_confirm || !$polis->is_confirm || !$bpkb->is_confirm)
                                                                    <a data-toggle="modal" data-target="#uploadBerkasModal"
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
                                                                        href="#" class="dropdown-item upload-berkas">
                                                                        Konfirmasi Berkas
                                                                    </a>
                                                                @endif
                                                            @endif
                                                        @endif
                                                        <a class="dropdown-item" data-toggle="modal"
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
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                {{-- <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div> --}}
                <div class="modal-body">
                    <form method="POST" action="#" id="modal-form">
                        <div class="form-group name">
                            <label for="name">Nama Peran</label>
                            <input type="text" class="form-control" id="name" name="name">
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
    <div class="modal fade" id="uploadBerkasModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <form id="modal-berkas" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id_kkb" id="id_kkb">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="stnk-tab" data-toggle="tab" href="#stnk"
                                    role="tab" aria-controls="stnk" aria-selected="true">STNK</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="polis-tab" data-toggle="tab" href="#polis" role="tab"
                                    aria-controls="polis" aria-selected="false">Polis</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="bpkb-tab" data-toggle="tab" href="#bpkb" role="tab"
                                    aria-controls="bpkb" aria-selected="false">BPKB</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            {{--  STNK  --}}
                            <div class="tab-pane fade show active" id="stnk" role="tabpanel"
                                aria-labelledby="stnk-tab">
                                <input type="hidden" name="id_stnk" id="id_stnk">
                                <div class="form-group">
                                    <label>Nomor</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="no_stnk" name="no_stnk"
                                            required>
                                    </div>
                                    <small class="form-text text-danger error"></small>
                                </div>
                                @if (Auth::user()->role_id == 2)
                                    <div id="preview_stnk"></div>
                                @endif
                                @if (Auth::user()->role_id == 3)
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
                                @endif
                            </div>
                            {{--  Polis  --}}
                            <div class="tab-pane fade" id="polis" role="tabpanel" aria-labelledby="polis-tab">
                                <input type="hidden" name="id_polis" id="id_polis">
                                <div class="form-group">
                                    <label>Nomor</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="no_polis" name="no_polis"
                                            required>
                                    </div>
                                    <small class="form-text text-danger error"></small>
                                </div>
                                @if (Auth::user()->role_id == 2)
                                    <div id="preview_polis"></div>
                                @endif
                                @if (Auth::user()->role_id == 3)
                                    <div class="form-group">
                                        <label>Scan Berkas (pdf)</label>
                                        <div class="input-group">
                                            <input type="file" class="form-control" id="polis_scan" name="polis_scan"
                                                accept="application/pdf" required>
                                            <div class="input-group-append">
                                                <span class="input-group-text">
                                                    <i class="fa fa-file"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <small class="form-text text-danger error"></small>
                                    </div>
                                @endif
                            </div>
                            {{--  BPKB  --}}
                            <div class="tab-pane fade" id="bpkb" role="tabpanel" aria-labelledby="bpkb-tab">
                                <input type="hidden" name="id_bpkb" id="id_bpkb">
                                <div class="form-group">
                                    <label>Nomor</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="no_bpkb" name="no_bpkb"
                                            required>
                                    </div>
                                    <small class="form-text text-danger error"></small>
                                </div>
                                @if (Auth::user()->role_id == 2)
                                    <div id="preview_bpkb"></div>
                                @endif
                                @if (Auth::user()->role_id == 3)
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
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                @if (Auth::user()->role_id == 2)
                                    Konfirmasi
                                @endif
                                @if (Auth::user()->role_id == 3)
                                    Kirim
                                @endif
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

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

    @push('extraScript')
        <script src="{{ asset('template') }}/assets/js/pdfobject.min.js"></script>
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
        <script>
            // Initial datepicker
            $('#tgl_ketersediaan_unit').datetimepicker({
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

            $('.upload-berkas').on('click', function(e) {
                e.preventDefault()
                console.log('upload berkas');
                var id = $(this).data('id_kkb')
                var id_stnk = $(this).data('id-stnk') ? $(this).data('id-stnk') : '';
                var id_polis = $(this).data('id-polis') ? $(this).data('id-polis') : '';
                var id_bpkb = $(this).data('id-bpkb') ? $(this).data('id-bpkb') : '';
                var no_stnk = $(this).data('no-stnk') ? $(this).data('no-stnk') : ''
                var no_polis = $(this).data('no-polis') ? $(this).data('no-polis') : ''
                var no_bpkb = $(this).data('no-bpkb') ? $(this).data('no-bpkb') : ''
                var file_stnk = $(this).data('file-stnk') ? $(this).data('file-stnk') : ''
                var file_polis = $(this).data('file-polis') ? $(this).data('file-polis') : ''
                var file_bpkb = $(this).data('file-bpkb') ? $(this).data('file-bpkb') : ''

                $('#modal-berkas #id_kkb').val(id);
                $('#modal-berkas #id_stnk').val(id_stnk);
                $('#modal-berkas #id_polis').val(id_polis);
                $('#modal-berkas #id_bpkb').val(id_bpkb);
                $('#modal-berkas #no_stnk').val(no_stnk);
                $('#modal-berkas #no_polis').val(no_polis);
                $('#modal-berkas #no_bpkb').val(no_bpkb);
                $('#modal-berkas #stnk_scan').val(file_stnk);
                $('#modal-berkas #polis_scan').val(file_polis);
                $('#modal-berkas #bpkb_scan').val(file_bpkb);
                var path_stnk = "{{ asset('storage') }}" + "/dokumentasi-bpkb/" + file_stnk;
                var path_polis = "{{ asset('storage') }}" + "/dokumentasi-bpkb/" + file_polis;
                var path_bpkb = "{{ asset('storage') }}" + "/dokumentasi-bpkb/" + file_bpkb;

                PDFObject.embed(path_stnk, "#preview_stnk");
                PDFObject.embed(path_polis, "#preview_polis");
                PDFObject.embed(path_bpkb, "#preview_bpkb");
            })

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

            $('#modal-berkas').on("submit", function(event) {
                event.preventDefault();
                var is_confirm = "{{ Auth::user()->role_id }}" == 2;

                if (!is_confirm) {
                    // Upload
                    const req_id = document.getElementById('id_kkb')
                    const req_no_stnk = document.getElementById('no_stnk')
                    const req_file_stnk = document.getElementById('stnk_scan')
                    const req_no_polis = document.getElementById('no_polis')
                    const req_file_polis = document.getElementById('polis_scan')
                    const req_no_bpkb = document.getElementById('no_bpkb')
                    const req_file_bpkb = document.getElementById('bpkb_scan')
                    var formData = new FormData($(this)[0]);

                    $.ajax({
                        type: "POST",
                        url: "{{ route('kredit.upload_berkas') }}",
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function(data) {
                            if (Array.isArray(data.error)) {
                                for (var i = 0; i < data.error.length; i++) {
                                    var message = data.error[i];
                                    if (message.toLowerCase().includes('no_stnk'))
                                        showError(req_date, message)
                                    if (message.toLowerCase().includes('stnk_scan'))
                                        showError(req_image, message)
                                    if (message.toLowerCase().includes('no_polis'))
                                        showError(req_date, message)
                                    if (message.toLowerCase().includes('polis_scan'))
                                        showError(req_image, message)
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
                                $('#uploadBerkasModal').modal().hide()
                                $('body').removeClass('modal-open');
                                $('.modal-backdrop').remove();
                            }
                        },
                        error: function(e) {
                            console.log(e)
                            ErrorMessage('Terjadi kesalahan')
                        }
                    })
                } else {
                    // Confirm
                    const req_id_stnk = document.getElementById('id_stnk')
                    const req_id_polis = document.getElementById('id_polis')
                    const req_id_bpkb = document.getElementById('id_bpkb')
                    var formData = new FormData($(this)[0]);

                    $.ajax({
                        type: "POST",
                        url: "{{ route('kredit.confirm_berkas') }}",
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function(data) {
                            if (Array.isArray(data.error)) {
                                for (var i = 0; i < data.error.length; i++) {
                                    var message = data.error[i];
                                    console.log(message)
                                    /*if (message.toLowerCase().includes('no_stnk'))
                                        showError(req_date, message)
                                    if (message.toLowerCase().includes('stnk_scan'))
                                        showError(req_image, message)
                                    if (message.toLowerCase().includes('no_polis'))
                                        showError(req_image, message)*/
                                }
                            } else {
                                if (data.status == 'success') {
                                    SuccessMessage(data.message);
                                } else {
                                    ErrorMessage(data.message)
                                }
                                $('#uploadBerkasModal').modal().hide()
                                $('body').removeClass('modal-open');
                                $('.modal-backdrop').remove();
                            }
                        },
                        error: function(e) {
                            console.log(e)
                            ErrorMessage('Terjadi kesalahan')
                        }
                    })
                }
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
                    timer: 3000,
                    closeOnClickOutside: false
                }).then(() => {
                    location.reload();
                });
                setTimeout(function() {
                    location.reload();
                }, 3000);
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
