<!-- Detail Modal -->
<div class="modal fade modaldetailpo" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                Detail
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
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
                                                    id="printfile">Print File
                                                    PO</button>
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
                                                    <td id="detail_nama_pengaju">undifined</td>
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
                                <div class="form-group">
                                    <label>Nomor</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="detail_no_stnk"
                                            name="no_stnk" readonly>
                                    </div>
                                    <small class="form-text text-danger error"></small>
                                    <iframe id="detail_preview_stnk" src="" width="100%"
                                        height="450px"></iframe>
                                    <p class="m-0" id="detail_ket_stnk">Berkas tidak ada.</p>
                                </div>

                            </div>
                            {{--  Polis  --}}
                            <div class="tab-pane fade" id="detail_polis" role="tabpanel"
                                aria-labelledby="detail-polis-tab">
                                <div class="form-group">
                                    <label>Nomor</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="detail_no_polis"
                                            name="no_polis" readonly>
                                    </div>
                                    <small class="form-text text-danger error"></small>
                                    <iframe id="detail_preview_polis" src="" width="100%"
                                        height="450px"></iframe>
                                    <p class="m-0" id="detail_ket_polis">Berkas tidak ada.</p>
                                </div>
                            </div>
                            {{--  BPKB  --}}
                            <div class="tab-pane fade" id="detail_bpkb" role="tabpanel"
                                aria-labelledby="detail-bpkb-tab">
                                <div class="form-group">
                                    <label>Nomor</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="detail_no_bpkb"
                                            name="no_bpkb" readonly>
                                    </div>
                                    <small class="form-text text-danger error"></small>
                                    <iframe id="detail_preview_bpkb" src="" width="100%"
                                        height="450px"></iframe>
                                    <p class="m-0" id="detail_ket_bpkb">Berkas tidak ada.</p>
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
                        if (response.data.documents[i].category == "Penyerahan Unit") {
                            if (response.data.documents[i].file)
                                $(".img-detailpo").attr('src', response.data.documents[i].file_path)
                        }
                        if (response.data.documents[i].file != "") {
                            switch (response.data.documents[i].category) {
                                case 'STNK':
                                    $('#detail_no_stnk').val(response.data.documents[i].text)
                                    $('#detail_preview_stnk').attr('src', response.data.documents[i]
                                        .file_path + "#toolbar=0")
                                    $('#detail_ket_stnk').css('display', 'none')
                                    break;
                                case 'Polis':
                                    $('#detail_no_polis').val(response.data.documents[i].text)
                                    $('#detail_preview_polis').attr('src', response.data.documents[i]
                                        .file_path + "#toolbar=0")
                                    $('#detail_ket_polis').css('display', 'none')
                                    break;
                                case 'BPKB':
                                    $('#detail_no_bpkb').val(response.data.documents[i].text)
                                    $('#detail_preview_bpkb').attr('src', response.data.documents[i]
                                        .file_path + "#toolbar=0")
                                    $('#detail_ket_bpkb').css('display', 'none')
                                    break;
                                default:
                                    break;
                            }
                        } else {
                            switch (response.data.documents[i].category) {
                                case 'STNK':
                                    $('#detail_no_stnk').css('display', 'none')
                                    $('#detail_preview_stnk').css('display', 'none')
                                    $('#detail_ket_stnk').css('display', 'block')
                                    break;
                                case 'Polis':
                                    $('#detail_no_polis').css('display', 'none !important')
                                    $('#detail_preview_polis').css('display', 'none')
                                    $('#detail_ket_polis').css('display', 'block')
                                    break;
                                case 'BPKB':
                                    $('#detail_no_bpkb').css('display', 'none !important')
                                    $('#detail_preview_bpkb').css('display', 'none')
                                    $('#detail_ket_bpkb').css('display', 'block')
                                    break;
                                default:
                                    break;
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
                            const file_po_path = "{{config('global.los_host')}}"+data.po+"#toolbar=0";
                            console.log(file_po_path)
                            $("#detail_filepo").attr("src", file_po_path)
                            $("#detail_nomorPo").html(data.no_po)
                            $("#detail_tanggalPo").html(data.tanggal)
                        }
                    }
                },
                error: function(error) {
                    ErrorMessage('Terjadi kesalahan')
                }
            })
        })
    </script>
@endpush
