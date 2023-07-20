<!-- Detail Modal -->
<div class="modal fade modaldetailpo" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title" id="detailModalLabel">Detail</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="text-light">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <ul class="nav nav-tabs" id="detail_myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="stnk-tab" data-toggle="tab" href="#po"
                                    role="tab" aria-controls="stnk" aria-selected="true">File PO</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="stnk-tab" data-toggle="tab" href="#datapengajuan"
                                    role="tab" aria-controls="stnk" aria-selected="true">Data Pengajuan</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="stnk-tab" data-toggle="tab" href="#detail_stnk" role="tab"
                                    aria-controls="stnk" aria-selected="true">STNK</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="polis-tab" data-toggle="tab" href="#detail_polis" role="tab"
                                    aria-controls="polis" aria-selected="false">Polis</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="bpkb-tab" data-toggle="tab" href="#detail_bpkb" role="tab"
                                    aria-controls="bpkb" aria-selected="false">BPKB</a>
                            </li>
                        </ul>
                        <div class="tab-content pt-3" id="myTabContent">
                            {{--  Data pengajuan  --}}
                            <div class="tab-pane fade show active" id="po" role="tabpanel"
                                aria-labelledby="detail-stnk-tab">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <h5 class="title-po">Nomor PO</h5>
                                            <input type="text" class="form-control text-field" id="detail_nomorPo"
                                                readonly>
                                            <h5 class="title-po">Tanggal PO</h5>
                                            <input type="text" class="form-control text-field" id="detail_tanggalPo"
                                                readonly>
                                        </div>
                                        <div class="col-sm-6">
                                            <h5 class="title-po">File PO</h5>
                                            <div class="form-inline mt-1 show-pdf">
                                                {{-- <button type="button" class="btn btn-primary mr-1 btn-sm">Unduh File
                                                    PO</button>
                                                <button onclick="printPDF()" class="btn btn-info btn-sm"
                                                    id="printfile">Print File PO</button> --}}
                                                <iframe id="detail_filepo" src="" class="mt-2" width="100%"
                                                    height="500"></iframe>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{--  Data pengajuan  --}}
                            <div class="tab-pane fade" id="datapengajuan" role="tabpanel"
                                aria-labelledby="detail-stnk-tab">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col">
                                            @if (Auth::user()->role_id != 3)
                                                <h4 class="m-0 title-detailpo">Data Pengajuan</h4>
                                                <tr>
                                                    <td>Nama</td>
                                                    <td>:</td>
                                                    <td><label id="detail_nama_pengaju"
                                                            style="font-weight: 400 !important;">undifined</label></td>
                                                </tr>
                                                <hr>
                                            @endif
                                            <h4 class="m-0 title-detailpo">Data PO</h4>
                                            <table>
                                                <tr>
                                                    <td class="tabel-bold">Nomor</td>
                                                    <td> :</td>
                                                    <td id="detail_no_po">undifined</td>
                                                </tr>
                                                <tr>
                                                    <td class="tabel-bold">Merk</td>
                                                    <td> :</td>
                                                    <td id="detail_merk">undifined</td>
                                                </tr>
                                                <tr>
                                                    <td class="tabel-bold">Merk</td>
                                                    <td> :</td>
                                                    <td id="detail_tipe">undifined</td>
                                                </tr>
                                                <tr>
                                                    <td class="tabel-bold">Tahun</td>
                                                    <td> :</td>
                                                    <td id="detail_tahun">-</td>
                                                </tr>
                                                <tr>
                                                    <td class="tabel-bold">Harga</td>
                                                    <td> :</td>
                                                    <td id="detail_harga">-</td>
                                                </tr>
                                                <tr>
                                                    <td class="tabel-bold">Jumlah Pemesanan</td>
                                                    <td> :</td>
                                                    <td id="detail_jumlah_pesanan">-</td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col">
                                            <div class="alert alert-danger alert-detailpo">
                                                Data bukti penyerahan unit belum di upload
                                            </div>
                                            <img class="img img-thumbnail img-detailpo"
                                                src="{{ asset('template/assets/img/img-not-found.jpg') }}"
                                                alt="bukti penyerahan unit">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{--  STNK  --}}
                            <div class="tab-pane fade" id="detail_stnk" role="tabpanel"
                                aria-labelledby="detail-stnk-tab">
                                <div class="alert alert-danger alert-stnk-file">
                                    File STNK belum di upload
                                </div>
                                <div class="form-group detail-input-stnk">
                                    <div class="row mb-2">
                                        <div class="col-sm-6 mb-2">
                                            <h5 class="title-po">Tanggal :</h5>
                                            <input type="text" class="form-control text-field"
                                                id="detail_tanggal_unggah_stnk" readonly>
                                            <h5 class="title-po">Tanggal Konfirmasi :</h5>
                                            <input type="text" class="form-control text-field"
                                                id="detail_tanggal_confirm_stnk" readonly>
                                            <h5 class="title-po">Status :</h5>
                                            <input type="text" class="form-control text-field"
                                                id="detail_status_confirm_stnk" readonly>
                                            <h5 class="title-po">Nomor :</h5>
                                            <input type="text" class="form-control text-field" id="detail_no_stnk"
                                                readonly>
                                        </div>
                                        <div class="col-sm-6">
                                            <h5 class="title-po">File STNK :</h5>
                                            <small class="form-text text-danger error"></small>
                                            <div class="form-inline mt-1 show-pdf">
                                                <iframe id="detail_preview_stnk" src="" width="100%"
                                                    height="450px"></iframe>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- <label>Nomor</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" readonly>
                                    </div> --}}

                                </div>

                            </div>
                            {{--  Polis  --}}
                            <div class="tab-pane fade" id="detail_polis" role="tabpanel"
                                aria-labelledby="detail-polis-tab">
                                <div class="alert alert-danger alert-polis-file">
                                    File Polis belum di upload
                                </div>
                                {{-- <p class="m-0" id="">Tanggal : -</p>
                                <p class="m-0" id="">Tanggal Konfirmasi : -</p>
                                <p class="m-0" id="">Status : Berkas belum diunggah</p> --}}
                                <div class="form-group detail-input-polis">
                                    <div class="row mb-2">
                                        <div class="col-sm-6 mb-2">
                                            <h5 class="title-po">Tanggal :</h5>
                                            <input type="text" class="form-control text-field"
                                                id="detail_tanggal_unggah_polis" readonly>
                                            <h5 class="title-po">Tanggal Konfirmasi :</h5>
                                            <input type="text" class="form-control text-field"
                                                id="detail_tanggal_confirm_polis" readonly>
                                            <h5 class="title-po">Status :</h5>
                                            <input type="text" class="form-control text-field"
                                                id="detail_status_confirm_polis" readonly>
                                            <h5 class="title-po">Nomor :</h5>
                                            <input type="text" class="form-control text-field"
                                                id="detail_no_polis" readonly>
                                        </div>
                                        <div class="col-sm-6">
                                            <h5 class="title-po">File Polis :</h5>
                                            <small class="form-text text-danger error"></small>
                                            <div class="form-inline mt-1 show-pdf">
                                                <iframe id="detail_preview_polis" src="" width="100%"
                                                    height="450px"></iframe>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{--  BPKB  --}}
                            <div class="tab-pane fade" id="detail_bpkb" role="tabpanel"
                                aria-labelledby="detail-bpkb-tab">
                                <div class="alert alert-danger alert-bpkb-file">
                                    File BPKB belum di upload
                                </div>
                                <div class="form-group detail-input-bpkb">
                                    <div class="row mb-2">
                                        <div class="col-sm-6 mb-2">
                                            <h5 class="title-po">Tanggal :</h5>
                                            <input type="text" class="form-control text-field"
                                                id="detail_tanggal_unggah_bpkb" readonly>
                                            <h5 class="title-po">Tanggal Konfirmasi :</h5>
                                            <input type="text" class="form-control text-field"
                                                id="detail_tanggal_confirm_bpkb" readonly>
                                            <h5 class="title-po">Status :</h5>
                                            <input type="text" class="form-control text-field"
                                                id="detail_status_confirm_bpkb" readonly>
                                            <h5 class="title-po">Nomor :</h5>
                                            <input type="text" class="form-control text-field" id="detail_no_bpkb"
                                                readonly>
                                        </div>
                                        <div class="col-sm-6">
                                            <h5 class="title-po">File BPKB :</h5>
                                            <small class="form-text text-danger error"></small>
                                            <div class="form-inline mt-1 show-pdf">
                                                <iframe id="detail_preview_bpkb" src="" width="100%"
                                                    height="450px"></iframe>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('extraScript')
    <script>
        $('.detail-link').on('click', function(e) {
            const id = $(this).data('id');
            $.ajax({
                url: "{{ url('/kredit') }}/" + id,
                method: "GET",
                success: function(response) {
                    for (var i = 0; i < response.data.documents.length; i++) {
                        var content = '';
                        const document = response.data.documents[i];
                        const karyawan = response.data.karyawan ? response.data.karyawan : null;

                        if (document.category == "Penyerahan Unit") {
                            if (document.file) {
                                $(".alert-detailpo").hide();
                                $(".img-detailpo").attr('src', document.file_path)
                            }

                        }

                        if (document.file != null) {
                            switch (document.category) {
                                case 'STNK':
                                    $('.alert-stnk-file').hide();
                                    $('#detail_tanggal_unggah_stnk').val(document.date)
                                    if (document.confirm_at)
                                        $('#detail_tanggal_confirm_stnk').val(document.confirm_at)
                                    if (karyawan)
                                        $('#detail_status_confirm_stnk').val(document.is_confirm ?
                                            'Sudah dikonfirmasi oleh cabang ' + karyawan[
                                                'entitas']['cab']['nama_cabang'] + '.' :
                                            'Belum dikonfirmasi')
                                    else
                                        $('#detail_status_confirm_stnk').val(document.is_confirm ?
                                            'Sudah dikonfirmasi oleh cabang.' :
                                            'Belum dikonfirmasi')
                                    $('#detail_no_stnk').val(document.text ? document.text : '-')
                                    $('#detail_preview_stnk').attr('src', document.file_path +
                                        "#navpanes=0")
                                    break;
                                case 'Polis':
                                    $(".alert-polis-file").hide();
                                    $('#detail_tanggal_unggah_polis').val(document.date)
                                    if (document.confirm_at)
                                        $('#detail_tanggal_confirm_polis').val(document.confirm_at)
                                    if (karyawan)
                                        $('#detail_status_confirm_polis').val(document.is_confirm ?
                                            'Sudah dikonfirmasi oleh cabang ' + karyawan[
                                                'entitas']['cab']['nama_cabang'] + '.' :
                                            'Belum dikonfirmasi')
                                    else
                                        $('#detail_status_confirm_polis').val(document.is_confirm ?
                                            'Sudah dikonfirmasi oleh cabang.' :
                                            'Belum dikonfirmasi')
                                    $('#detail_no_polis').val(document.text ? document.text : '-')
                                    $('#detail_preview_polis').attr('src', document
                                        .file_path + "#navpanes=0")
                                    break;
                                case 'BPKB':
                                    $(".alert-bpkb-file").hide();
                                    $('#detail_tanggal_unggah_bpkb').val(document.date)
                                    if (document.confirm_at)
                                        $('#detail_tanggal_confirm_bpkb').val(document.confirm_at)
                                    if (karyawan)
                                        $('#detail_status_confirm_bpkb').val(document.is_confirm ?
                                            'Sudah dikonfirmasi oleh cabang ' + karyawan[
                                                'entitas']['cab']['nama_cabang'] + '.' :
                                            'Belum dikonfirmasi')
                                    else
                                        $('#detail_status_confirm_bpkb').val(document.is_confirm ?
                                            'Sudah dikonfirmasi oleh cabang.' :
                                            'Belum dikonfirmasi')
                                    $('#detail_no_bpkb').val(document.text ? document.text : '-')
                                    $('#detail_preview_bpkb').attr('src', document
                                        .file_path + "#navpanes=0")
                                    break;
                                default:
                                    break;
                            }
                        } else {
                            switch (document.category) {
                                case 'STNK':
                                    $('#detail_no_stnk').css('display', 'none')
                                    $('#detail_preview_stnk').css('display', 'none')
                                    $('.detail-input-stnk').css('display', 'none')
                                    break;
                                case 'Polis':
                                    $('#detail_no_polis').css('display', 'none !important')
                                    $('#detail_preview_polis').css('display', 'none')
                                    $('.detail-input-polis').css('display', 'none')
                                    break;
                                case 'BPKB':
                                    $('#detail_no_bpkb').css('display', 'none !important')
                                    $('#detail_preview_bpkb').css('display', 'none')
                                    $('.detail-input-bpkb').css('display', 'none')
                                    break;
                                default:
                                    break;
                            }
                        }
                    }
                    if (response.data.pengajuan) {
                        var data = response.data.pengajuan;
                        $('#detail_nama_pengaju').html(data.nama);
                        $('#detail_no_po').html(data.no_po);
                        $('#detail_merk').html(data.merk);
                        $('#detail_tipe').html(data.tipe);
                        $('#detail_tahun').html(data.tahun_kendaraan);
                        $('#detail_harga').html('Rp ' + formatMoney(data
                            .harga_kendaraan, 0, ',', '.'));
                        $('#detail_jumlah_pesanan').html(data
                            .jumlah_kendaraan);
                        const file_po_path = "{{ config('global.los_asset_url') }}" + data.po +
                            "#navpanes=0";
                        $("#detail_filepo").attr("src", file_po_path)
                        $("#detail_nomorPo").val(data.no_po)
                        $("#detail_tanggalPo").val(data.tanggal)
                    }
                },
                error: function(error) {
                    ErrorMessage('Terjadi kesalahan')
                }
            })
        })
    </script>
@endpush
