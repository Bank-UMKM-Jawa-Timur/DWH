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
                                            <b class="content-po" id="detail_nomorPo">undifined</b>
                                        </div>
                                        <div class="col-sm-6">
                                            <h5 class="title-po">Tanggal PO</h5>
                                            <b class="content-po" id="detail_tanggalPo">undifined</b>
                                        </div>
                                        <div class="col-sm-12 mt-4">
                                            <h5 class="title-po">File PO</h5>
                                            <div class="form-inline mt-1">
                                                <button type="button" class="btn btn-primary mr-1 btn-sm">Unduh File
                                                    PO</button>
                                                <button onclick="printPDF()" class="btn btn-info btn-sm"
                                                    id="printfile">Print File PO</button>
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
                                                    <td><label id="detail_nama_pengaju" style="font-weight: 400 !important;">undifined</label></td>
                                                </tr>
                                                <hr>
                                            @endif
                                            <h4 class="m-0 title-detailpo">Data PO</h4>
                                            <table>
                                                <tr>
                                                    <td>Nomor</td>
                                                    <td> :</td>
                                                    <td id="detail_no_po">undifined</td>
                                                </tr>
                                                <tr>
                                                    <td>Merk</td>
                                                    <td> :</td>
                                                    <td id="detail_merk">undifined</td>
                                                </tr>
                                                <tr>
                                                    <td>Merk</td>
                                                    <td> :</td>
                                                    <td id="detail_tipe">undifined</td>
                                                </tr>
                                                <tr>
                                                    <td>Tahun</td>
                                                    <td> :</td>
                                                    <td id="detail_tahun">-</td>
                                                </tr>
                                                <tr>
                                                    <td>Harga</td>
                                                    <td> :</td>
                                                    <td id="detail_harga">-</td>
                                                </tr>
                                                <tr>
                                                    <td>Jumlah Pemesanan</td>
                                                    <td> :</td>
                                                    <td id="detail_jumlah_pesanan">-</td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col">
                                            <img class="img img-thumbnail img-detailpo" src="{{asset('template/assets/img/img-not-found.jpg')}}"
                                                alt="bukti penyerahan unit">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{--  STNK  --}}
                            <div class="tab-pane fade" id="detail_stnk" role="tabpanel"
                                aria-labelledby="detail-stnk-tab">
                                <p class="m-0" id="detail_tanggal_unggah_stnk">Tanggal : -</p>
                                <p class="m-0" id="detail_tanggal_confirm_stnk">Tanggal Konfirmasi : -</p>
                                <p class="m-0" id="detail_status_confirm_stnk">Status : Berkas belum diunggah</p>
                                <div class="form-group detail-input-stnk">
                                    <label>Nomor</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="detail_no_stnk"
                                            name="no_stnk" readonly>
                                    </div>
                                    <small class="form-text text-danger error"></small>
                                    <iframe id="detail_preview_stnk" src="" width="100%"
                                        height="450px"></iframe>
                                </div>

                            </div>
                            {{--  Polis  --}}
                            <div class="tab-pane fade" id="detail_polis" role="tabpanel"
                                aria-labelledby="detail-polis-tab">
                                <p class="m-0" id="detail_tanggal_unggah_polis">Tanggal : -</p>
                                <p class="m-0" id="detail_tanggal_confirm_polis">Tanggal Konfirmasi : -</p>
                                <p class="m-0" id="detail_status_confirm_polis">Status : Berkas belum diunggah</p>
                                <div class="form-group detail-input-polis">
                                    <label>Nomor</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="detail_no_polis"
                                            name="no_polis" readonly>
                                    </div>
                                    <small class="form-text text-danger error"></small>
                                    <iframe id="detail_preview_polis" src="" width="100%"
                                        height="450px"></iframe>
                                </div>
                            </div>
                            {{--  BPKB  --}}
                            <div class="tab-pane fade" id="detail_bpkb" role="tabpanel"
                                aria-labelledby="detail-bpkb-tab">
                                <p class="m-0" id="detail_tanggal_unggah_bpkb">Tanggal : -</p>
                                <p class="m-0" id="detail_tanggal_confirm_bpkb">Tanggal Konfirmasi : -</p>
                                <p class="m-0" id="detail_status_confirm_bpkb">Status : Berkas belum diunggah</p>
                                <div class="form-group detail-input-bpkb">
                                    <label>Nomor</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="detail_no_bpkb"
                                            name="no_bpkb" readonly>
                                    </div>
                                    <small class="form-text text-danger error"></small>
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
                            if (document.file)
                                $(".img-detailpo").attr('src', document.file_path)
                        }

                        if (document.file != null) {
                            switch (document.category) {
                                case 'STNK':
                                    $('#detail_tanggal_unggah_stnk').html('Tanggal : '+document.date)
                                    if (document.confirm_at)
                                        $('#detail_tanggal_confirm_stnk').html('Tanggal Konfirmasi : '+document.confirm_at)
                                    if (karyawan)
                                        $('#detail_status_confirm_stnk').html(document.is_confirm ? 'Status : Sudah dikonfirmasi oleh cabang '+karyawan['entitas']['cab']['nama_cabang']+'.' : 'Status : Belum dikonfirmasi')
                                    else
                                        $('#detail_status_confirm_stnk').html(document.is_confirm ? 'Status : Sudah dikonfirmasi oleh cabang.' : 'Status : Belum dikonfirmasi')
                                    $('#detail_no_stnk').val(document.text ? document.text : '-')
                                    $('#detail_preview_stnk').attr('src', document.file_path + "#toolbar=0")
                                    break;
                                case 'Polis':
                                    $('#detail_tanggal_unggah_polis').html('Tanggal : '+document.date)
                                    if (document.confirm_at)
                                        $('#detail_tanggal_confirm_polis').html('Tanggal Konfirmasi : '+document.confirm_at)
                                    if (karyawan)
                                        $('#detail_status_confirm_polis').html(document.is_confirm ? 'Status : Sudah dikonfirmasi oleh cabang '+karyawan['entitas']['cab']['nama_cabang']+'.' : 'Status : Belum dikonfirmasi')
                                    else
                                        $('#detail_status_confirm_polis').html(document.is_confirm ? 'Status : Sudah dikonfirmasi oleh cabang.' : 'Status : Belum dikonfirmasi')
                                    $('#detail_no_polis').val(document.text ? document.text : '-')
                                    $('#detail_preview_polis').attr('src', document
                                        .file_path + "#toolbar=0")
                                    break;
                                case 'BPKB':
                                    $('#detail_tanggal_unggah_bpkb').html('Tanggal : '+document.date)
                                    if (document.confirm_at)
                                        $('#detail_tanggal_confirm_bpkb').html('Tanggal Konfirmasi : '+document.confirm_at)
                                    if (karyawan)
                                        $('#detail_status_confirm_bpkb').html(document.is_confirm ? 'Status : Sudah dikonfirmasi oleh cabang '+karyawan['entitas']['cab']['nama_cabang']+'.' : 'Status : Belum dikonfirmasi')
                                    else
                                        $('#detail_status_confirm_bpkb').html(document.is_confirm ? 'Status : Sudah dikonfirmasi oleh cabang.' : 'Status : Belum dikonfirmasi')
                                    $('#detail_no_bpkb').val(document.text ? document.text : '-')
                                    $('#detail_preview_bpkb').attr('src', document
                                        .file_path + "#toolbar=0")
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
                        const file_po_path = "{{config('global.los_host')}}/public"+data.po+"#toolbar=0";
                        $("#detail_filepo").attr("src", file_po_path)
                        $("#detail_nomorPo").html(data.no_po)
                        $("#detail_tanggalPo").html(data.tanggal)
                    }
                },
                error: function(error) {
                    ErrorMessage('Terjadi kesalahan')
                }
            })
        })
    </script>
@endpush
