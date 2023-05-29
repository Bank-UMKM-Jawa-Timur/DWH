<!-- Detail Modal -->
<div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                Detail
            </div>
            <div class="modal-body">
                <div class="detail-content">
                </div>
                <div class="row">
                    <div class="col">
                        @if (Auth::user()->role_id != 3)
                        <h4 class="m-0">Data Pengajuan</h4>
                        <p class="m-0" id="detail_nama_pengaju">Nama : Ahmad Riyanto</p>
                        <hr>
                        @endif
                        <h4 class="m-0">Data PO</h4>
                        <p class="m-0" id="detail_no_po">Nomor : 12398123</p>
                        <p class="m-0" id="detail_kendaraan">Kendaraan : Honda Beat</p>
                        <p class="m-0" id="detail_tahun">Tahun : -</p>
                        <p class="m-0" id="detail_harga">Harga : -</p>
                        <p class="m-0" id="detail_jumlah_pesanan">Jumlah Pemesanan : -</p>
                    </div>
                    <div class="col">
                        <img class="img img-thumbnail" src="https://penjualmobil.com/wp-content/uploads/2022/02/Foto-Penyerahan-Unit-Adrian-3.jpeg" alt="bukti penyerahan unit">
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <ul class="nav nav-tabs" id="detail_myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="stnk-tab" data-toggle="tab" href="#detail_stnk"
                                    role="tab" aria-controls="stnk" aria-selected="true">STNK</a>
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
                        <div class="tab-content" id="myTabContent">
                            {{--  STNK  --}}
                            <div class="tab-pane fade show active" id="detail_stnk" role="tabpanel"
                                aria-labelledby="detail-stnk-tab">
                                <div class="form-group">
                                    <label>Nomor</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="detail_no_stnk" name="no_stnk" readonly>
                                    </div>
                                    <small class="form-text text-danger error"></small>
                                </div>
                                <iframe id="detail_preview_stnk" src="" width="100%" height="500px"></iframe>
                                <p class="m-0" id="detail_ket_stnk">Berkas tidak ada.</p>
                            </div>
                            {{--  Polis  --}}
                            <div class="tab-pane fade" id="detail_polis" role="tabpanel" aria-labelledby="detail-polis-tab">
                                <div class="form-group">
                                    <label>Nomor</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="detail_no_polis" name="no_polis" readonly>
                                    </div>
                                    <small class="form-text text-danger error"></small>
                                </div>
                                <iframe id="detail_preview_polis" src="" width="100%"></iframe>
                                <p class="m-0" id="detail_ket_polis">Berkas tidak ada.</p>
                            </div>
                            {{--  BPKB  --}}
                            <div class="tab-pane fade" id="detail_bpkb" role="tabpanel" aria-labelledby="detail-bpkb-tab">
                                <div class="form-group">
                                    <label>Nomor</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="detail_no_bpkb" name="no_bpkb" readonly>
                                    </div>
                                    <small class="form-text text-danger error"></small>
                                </div>
                                <iframe id="detail_preview_bpkb" src="" width="100%"></iframe>
                                <p class="m-0" id="detail_ket_bpkb">Berkas tidak ada.</p>
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
            url: "{{url('/kredit')}}/"+id,
            method: "GET",
            success: function(response) {
                for(var i = 0; i < response.data.documents.length; i++) {
                    var content = '';
                    if (response.data.documents[i].file != "") {
                        switch(response.data.documents[i].category) {
                            case 'STNK':
                                $('#detail_no_stnk').val(response.data.documents[i].text)
                                $('#detail_preview_stnk').attr('src', response.data.documents[i].file_path+"#toolbar=0")
                                $('#detail_ket_stnk').css('display', 'none')
                                break;
                            case 'Polis':
                                $('#detail_no_polis').val(response.data.documents[i].text)
                                $('#detail_preview_polis').attr('src', response.data.documents[i].file_path+"#toolbar=0")
                                $('#detail_ket_polis').css('display', 'none')
                                break;
                            case 'BPKB':
                                $('#detail_no_bpkb').val(response.data.documents[i].text)
                                $('#detail_preview_bpkb').attr('src', response.data.documents[i].file_path+"#toolbar=0")
                                $('#detail_ket_bpkb').css('display', 'none')
                                break;
                            default:
                                break;
                        }
                    }
                    else {
                        switch(response.data.documents[i].category) {
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
                        $('#detail_nama_pengaju').html('Nama : '+data.nama);
                        $('#detail_no_po').html('Nomor : '+data.no_po);
                        $('#detail_kendaraan').html('Kendaraan : '+data.kendaraan);
                        $('#detail_tahun').html('Tahun : '+data.tahun_kendaraan);
                        $('#detail_harga').html('Harga : '+'Rp '+formatMoney(data.harga_kendaraan, 0, ',', '.'));
                        $('#detail_jumlah_pesanan').html('Jumlah Pemesanan : '+data.jumlah_kendaraan);
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