@extends('layout.master')
@push('extraScript')
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script>
        Pusher.logToConsole = true;
        const app_key = "{{ config('broadcasting.connections.pusher.key') }}"

        if (app_key) {
            var pusher = new Pusher(app_key, {
                cluster: 'ap1'
            });

            var channel = pusher.subscribe('kredit');
            channel.bind('data-table', function(data) {
                console.log('Received')
                refreshTable();
            });
        } else {
            //console.log(`Pusher app key isn't have value`)
        }

        function refreshTable() {
            console.log('refresh table')
            var page = $("#page").val()
            var tab_type = $("#tab_type").val()
            var page_length = $("#page_length").val()
            var tAwal = $("#tAwal").val() != 'dd/mm/yyyy' ? $('#tAwal').val() : ''
            var tAkhir = $("#tAkhir").val() != 'dd/mm/yyyy' ? $('#tAkhir').val() : ''
            var status = $("#status").val()

            $.ajax({
                type: "POST",
                url: "{{ route('kredit.load_json') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    page: page,
                    page_length: page_length,
                    tab_type: tab_type,
                    tAwal: tAwal,
                    tAkhir: tAkhir,
                    status: status,
                },
                success: function(response) {
                    console.log('response')
                    if (response) {
                        if (response.status == 'success') {
                            if ("html" in response) {
                                $('#table_content').html(response.html);
                            }
                            if ("html_import" in response) {
                                console.log(response.html_import)
                                $('#table_content_import').html(response.html_import);
                            }
                        }
                    }
                    $('#preload-data').addClass("hidden")
                },
                error: function(e) {
                    console.log('Error load json')
                    console.log(e)
                    $('#preload-data').addClass("hidden")
                }
            });
        }

        function showModal(identifier) {
            const targetId = $(identifier).data("target-id");
            const user_role_id = "{{ \Session::get(config('global.role_id_session')) }}";

            $(`#${targetId}`).removeClass("hidden");
            $(".layout-overlay-edit-form").removeClass("hidden");

            if (targetId == 'modalAturKetersedian') {
                var id = $(identifier).data('id_kkb');
                $(`#${targetId}`).find('#id_kkb').val(id);
            } else if (targetId == 'modalUploadBerkasTagihan') {
                var id = $(identifier).data('id_kkb');
                $(`#${targetId}`).find('#id_kkb').val(id);
            } else if (targetId == 'modalTagihan') {
                var id = $(identifier).data('id');
                $(`#${targetId}`).find('#id_kkb').val(id);
            } else if (targetId == 'modalUploadBuktiPembayaran') {
                var id = $(identifier).data('id_kkb');
                $(`#${targetId}`).find('#id_kkb').val(id);
            } else if (targetId == 'modalConfirmBuktiPembayaran') {
                const confirm_id = $(identifier).data('id-doc')
                const is_confirm = $(identifier).data('confirm')
                const confirm_category_id = $(identifier).data('id-category')
                const kategori = ($(identifier).data('kategori') === 'data_import') ? 'Catatan! Data ini merupakan data import google spreadsheet' : ''
                const file = $(identifier).data('file');
                const status = $(identifier).data('confirm') ? 'Sudah dikonfirmasi oleh vendor.' :
                    'Menunggu konfirmasi dari vendor.';
                const tanggal = $(identifier).data('tanggal');
                var path_file = "{{ asset('storage') }}" + "/dokumentasi-bukti-pembayaran/" + file + "#navpanes=0";

                $(`#${targetId} #confirm_bukti_pembayaran_img`).attr('src', path_file)
                $(`#${targetId} #confirm_tanggal_pembayaran`).val(tanggal)
                $(`#${targetId} #status_confirm`).val(status)
                $(`#${targetId} #kategori_data`).text(kategori)
                $(`#${targetId} #confirm_id`).val(confirm_id)
                $(`#${targetId} #confirm_id_category`).val(confirm_category_id)

                if (is_confirm) {
                    $(`#${targetId} .modal-footer`).css('display', 'none')
                }
            } else if (targetId == 'modalConfirmImbalJasa') {
                const data_id = $(identifier).data('id')
                const tanggal = $(identifier).data('tanggal')
                const nominal = $(identifier).data('nominal')
                const kategori = ($(identifier).data('kategori') === 'data_import') ? 'Catatan! Data ini merupakan data import google spreadsheet' : ''
                const is_confirm = $(identifier).data('confirm')
                const confirm = $(identifier).data('confirm') ? 'Sudah dikonfirmasi' : 'Belum dikonfirmasi'
                const file_bukti = $(identifier).data('file') ? $(identifier).data('file') : ''
                var path_file = "{{ asset('storage') }}" + "/dokumentasi-imbal-jasa/" + file_bukti;

                fetch(path_file).then(function(response){
                    if(!response.ok){
                        $('.content-bukti-imbal-jasa').addClass("hidden");
                        $('.alert-bukti-imbal-jasa').removeClass("hidden");
                    }else{
                        $('.content-bukti-imbal-jasa').removeClass("hidden");
                        $('.alert-bukti-imbal-jasa').addClass("hidden");
                    }
                })

                $(`#${targetId} #preview_imbal_jasa`).attr("src", path_file);
                $(`#${targetId} #id_cat`).val(data_id)
                $(`#${targetId} #kategori_data`).text(kategori)
                $(`#${targetId} #tgl_upload_imbal_jasa`).val(tanggal)
                $(`#${targetId} #nominal_imbal_jasa`).val(nominal)
                $(`#${targetId} #status_konfirmasi_imbal_jasa`).val(confirm)

                if (is_confirm) {
                    $(`#${targetId} .title-modal`).html('Bukti Imbal Jasa')
                    $(`#${targetId} .modal-footer`).css('display', 'none')
                }
            } else if (targetId == 'modalConfirmPenyerahanUnit') {
                $(`#${targetId}`).removeClass("hidden");
                $(".layout-overlay-edit-form").removeClass("hidden");

                const id_kkb = $(identifier).data('id_kkb');
                const data_id = $(identifier).data('id-doc')
                const kategori = ($(identifier).data('kategori') === 'data_import') ? 'Catatan! Data ini merupakan data import google spreadsheet' : ''

                const data_category_doc_id = $(identifier).data('id-category')
                const tanggal = $(identifier).data('tanggal');
                const is_confirm = $(identifier).data('confirm');
                const confirm_at = $(identifier).data('confirm_at');
                const id_doc = $(identifier).data('id-doc');
                const status = $(identifier).data('confirm') ? 'Sudah dikonfirmasi oleh cabang.' :
                    'Belum dikonfirmasi cabang.';
                const file = $(identifier).data('file');
                var path_file = "{{ asset('storage') }}" + "/dokumentasi-peyerahan/" + file;

                fetch(path_file).then(function(response){
                    if(!response.ok){
                        $('.content-penyerahan-unit').addClass("hidden");
                        $('.alert-penyerahan-unit').removeClass("hidden");
                    }else{
                        $('.content-penyerahan-unit').removeClass("hidden");
                        $('.alert-penyerahan-unit').addClass("hidden");
                    }
                })

                $(`#${targetId} #preview_penyerahan_unit`).attr("src", path_file);
                $(`#${targetId} #confirm_penyerahan_id`).val(data_id)
                $(`#${targetId} #kategori_data`).text(kategori);
                $(`#${targetId} #confirm_penyerahan_id_category`).val(data_category_doc_id)
                $(`#${targetId} #status_confirm_penyerahan_unit`).val(status)
                $(`#${targetId} #tanggal_penyerahan_unit`).val(tanggal)
                $(`#${targetId} #tanggal_confirm_penyerahan_unit`).val(confirm_at)
                if (is_confirm) {
                    $(`#${targetId} .title-modal`).html('Penyerahan Unit')
                    $(`#${targetId} .form-confirm`).css('display', 'none');
                    $(`#${targetId} .penyerahan-unit-title`).html('Penyerahan Unit');
                } else {
                    var role_id = "{{ \Session::get(config('global.role_id_session')) }}"
                    var role_name = "{{ \Session::get(config('global.user_role_session')) }}"
                    if (role_id == 2 && role_name == 'Staf Analis Kredit') {
                        $(`#${targetId} .title-modal`).html('Konfirmasi Penyerahan Unit')
                        $(`#${targetId} .form-confirm`).css('display', 'block');
                        $(`#${targetId} .penyerahan-unit-title`).html('Konfirmasi Penyerahan Unit');
                    } else {
                        $(`#${targetId} .title-modal`).html('Penyerahan Unit')
                        $(`#${targetId} .form-confirm`).css('display', 'none');
                        $(`#${targetId} .penyerahan-unit-title`).html('Penyerahan Unit');
                    }
                }
            } else if (targetId == 'modalDetailPo') {
                $(".active-tab").trigger("click");
                const id = $(identifier).data('id');
                const kategori = ($(identifier).data('kategori') === 'data_import') ? 'Catatan! Data ini merupakan data import google spreadsheet' : ''
                console.log(kategori)
                const data_kategori = $(identifier).data('kategori')
                const data_is_import = data_kategori != 'data_kkb'
                var url = "{{ url('/kredit') }}/" + id
                if (data_is_import)
                    url += "?is_import=true"

                $(`#${targetId} #kategori_data`).text(kategori)

                Swal.fire({
                    showConfirmButton: false,
                    timer: 3000,
                    closeOnClickOutside: true,
                    title: 'Memuat data...',
                    html: 'Silahkan tunggu...',
                    allowEscapeKey: false,
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading()
                    }
                });
                $.ajax({
                    url: url,
                    method: "GET",
                    success: function(response) {
                        console.log(response)
                        for (var i = 0; i < response.data.documents.length; i++) {
                            var content = '';
                            const document = response.data || response.data.documents[i] ? response.data
                                .documents[i] : null;
                            const karyawan = response.data.karyawan ? response.data.karyawan : null;

                            if (document.category == "Penyerahan Unit") {
                                if (document.file) {
                                    $(`#${targetId} .alert-detailpo`).hide();
                                    $(`#${targetId} .img-detailpo`).attr('src', document.file_path)
                                }
                                // console.log(document.file_path)
                                fetch(document.file_path).then(function(response){
                                    if(!response.ok){
                                        $('.content-po').addClass("hidden");
                                        $('.alert-po').removeClass("hidden");
                                    }else{
                                        $('.content-po').removeClass("hidden");
                                        $('.alert-po').addClass("hidden");
                                    }
                                })
                            }
                            if (document.category == "Bukti Pembayaran") {
                                if (document.file) {
                                    // console.log("ada file");
                                    $(`#${targetId} #detail_bukti_pembayaran`).attr('src', document.file_path +
                                        "#navpanes=0")
                                }
                                // console.log(document.file_path)
                                fetch(document.file_path).then(function(response){
                                    if(!response.ok){
                                        $('.content-dbp').addClass("hidden");
                                        $('.alert-dbp').removeClass("hidden");
                                    }else{
                                        $('.content-dbp').removeClass("hidden");
                                        $('.alert-dbp').addClass("hidden");
                                    }
                            })
                        }


                            let category = document.category.toLowerCase();

                            if(category === "stnk"){
                                if(!document.file){
                                    $(`.alert-stnk-detail-modal`).removeClass("hidden")
                                    $(`.content-stnk-detail-modal`).addClass("hidden")
                                }else{
                                fetch(document.file_path).then(function(response){
                                    if(!response.ok){
                                        $(`.alert-stnk-detail-modal`).addClass("hidden")
                                        $(`.content-stnk-detail-modal`).addClass("hidden")
                                        $(`.alert-stnk-not-found`).removeClass("hidden")
                                    }else{
                                        $(`.alert-stnk-not-found`).addClass("hidden")
                                        $(`.content-stnk-detail-modal`).removeClass("hidden")
                                        $(`.alert-stnk-detail-modal`).addClass("hidden")
                                    }
                                })
                                }
                            }
                            if(category === "bpkb"){
                                if(!document.file){
                                    $(`.alert-bpkb-detail-modal`).removeClass("hidden")
                                    $(`.content-bpkb-detail-modal`).addClass("hidden")
                                    console.log(`${category} tidak ada`);
                                }else{
                                fetch(document.file_path).then(function(response){
                                    if(!response.ok){
                                        $(`.alert-bpkb-detail-modal`).addClass("hidden")
                                        $(`.content-bpkb-detail-modal`).addClass("hidden")
                                        $(`.alert-bpkb-not-found`).removeClass("hidden")
                                    }else{
                                        $(`.alert-bpkb-not-found`).addClass("hidden")
                                        $(`.content-bpkb-detail-modal`).removeClass("hidden")
                                        $(`.alert-bpkb-detail-modal`).addClass("hidden")
                                    }
                                })
                                }
                            }
                            if(category === "polis"){
                                if(!document.file){
                                    $(`.alert-polis-detail-modal`).removeClass("hidden")
                                    $(`.content-polis-detail-modal`).addClass("hidden")
                                    console.log(`${category} tidak ada`);
                                }else{
                                    fetch(document.file_path).then(function(response){
                                    if(!response.ok){
                                        $(`.alert-polis-detail-modal`).addClass("hidden")
                                        $(`.content-polis-detail-modal`).addClass("hidden")
                                        $(`.alert-polis-not-found`).removeClass("hidden")
                                    }else{
                                        $(`.alert-polis-not-found`).addClass("hidden")
                                        $(`.content-polis-detail-modal`).removeClass("hidden")
                                        $(`.alert-polis-detail-modal`).addClass("hidden")
                                    }
                                })
                                }
                            }


                            if (document.file) {
                                switch (document.category) {
                                    case 'STNK':
                                        $(`#${targetId} .alert-stnk`).addClass("hidden")
                                        $(`#${targetId} .content-stnk`).removeClass("hidden")
                                        $(`#${targetId} .alert-stnk-file`).css('display', 'none !important');
                                        $(`#${targetId} #detail_tanggal_unggah_stnk`).val(document.date)
                                        if (document.confirm_at)
                                            $(`#${targetId} #detail_tanggal_confirm_stnk`).val(document
                                                .confirm_at)
                                        if (karyawan)
                                            $(`#${targetId} #detail_status_confirm_stnk`).val(document
                                                .is_confirm ?
                                                'Sudah dikonfirmasi oleh cabang ' + karyawan[
                                                    'entitas']['cab']['nama_cabang'] + '.' :
                                                'Belum dikonfirmasi')
                                        else
                                            $(`#${targetId} #detail_status_confirm_stnk`).val(document
                                                .is_confirm ?
                                                'Sudah dikonfirmasi oleh cabang.' :
                                                'Belum dikonfirmasi')
                                        $(`#${targetId} #detail_no_stnk`).val(document.text ? document.text :
                                            '-')
                                        $(`#${targetId} #detail_preview_stnk`).attr('src', document.file_path +
                                            "#navpanes=0")
                                        break;
                                    case 'Polis':
                                        $(`#${targetId} .alert-polis`).css('display', 'none !important')
                                        $(`#${targetId} .content-polis`).css('display', 'block')
                                        $(`#${targetId} .alert-polis-file`).css('display', 'none !important');
                                        $(`#${targetId} #detail_tanggal_unggah_polis`).val(document.date)
                                        if (document.confirm_at)
                                            $(`#${targetId} #detail_tanggal_confirm_polis`).val(document
                                                .confirm_at)
                                        if (karyawan)
                                            $(`#${targetId} #detail_status_confirm_polis`).val(document
                                                .is_confirm ?
                                                'Sudah dikonfirmasi oleh cabang ' + karyawan[
                                                    'entitas']['cab']['nama_cabang'] + '.' :
                                                'Belum dikonfirmasi')
                                        else
                                            $(`#${targetId} #detail_status_confirm_polis`).val(document
                                                .is_confirm ?
                                                'Sudah dikonfirmasi oleh cabang.' :
                                                'Belum dikonfirmasi')
                                        $(`#${targetId} #detail_no_polis`).val(document.text ? document.text :
                                            '-')
                                        $(`#${targetId} #detail_preview_polis`).attr('src', document
                                            .file_path + "#navpanes=0")
                                        break;
                                    case 'BPKB':
                                        $(`#${targetId} .alert-bpkb`).css('display', 'none !important')
                                        $(`#${targetId} .content-bpkb`).css('display', 'block')
                                        $(`#${targetId} .alert-bpkb-file`).css('display', 'none !important');
                                        $(`#${targetId} #detail_tanggal_unggah_bpkb`).val(document.date)
                                        if (document.confirm_at)
                                            $(`#${targetId} #detail_tanggal_confirm_bpkb`).val(document
                                                .confirm_at)
                                        if (karyawan)
                                            $(`#${targetId} #detail_status_confirm_bpkb`).val(document
                                                .is_confirm ?
                                                'Sudah dikonfirmasi oleh cabang ' + karyawan[
                                                    'entitas']['cab']['nama_cabang'] + '.' :
                                                'Belum dikonfirmasi')
                                        else
                                            $(`#${targetId} #detail_status_confirm_bpkb`).val(document
                                                .is_confirm ?
                                                'Sudah dikonfirmasi oleh cabang.' :
                                                'Belum dikonfirmasi')
                                        $(`#${targetId} #detail_no_bpkb`).val(document.text ? document.text :
                                            '-')
                                        $(`#${targetId} #detail_preview_bpkb`).attr('src', document
                                            .file_path + "#navpanes=0")
                                        break;
                                    default:
                                        break;
                                }
                            } else {
                                switch (document.category) {
                                    case 'STNK':
                                        $(`#${targetId} .alert-stnk`).removeClass("hidden")
                                        $(`#${targetId} .content-stnk`).addClass("hidden")
                                        $(`#${targetId} #detail_no_stnk`).css('display', 'none')
                                        $(`#${targetId} #detail_preview_stnk`).css('display', 'none')
                                        $(`#${targetId} .detail-input-stnk`).css('display', 'none')
                                        break;
                                    case 'Polis':
                                        $(`#${targetId} .alert-polis`).css('display', 'block')
                                        $(`#${targetId} .content-polis`).css('display', 'none !important')
                                        $(`#${targetId} #detail_no_polis`).css('display', 'none !important')
                                        $(`#${targetId} #detail_preview_polis`).css('display', 'none')
                                        $(`#${targetId} .detail-input-polis`).css('display', 'none')
                                        break;
                                    case 'BPKB':
                                        $(`#${targetId} .alert-bpkb`).css('display', 'block')
                                        $(`#${targetId} .content-bpkb`).css('display', 'none !important')
                                        $(`#${targetId} #detail_no_bpkb`).css('display', 'none !important')
                                        $(`#${targetId} #detail_preview_bpkb`).css('display', 'none')
                                        $(`#${targetId} .detail-input-bpkb`).css('display', 'none')
                                        break;
                                    default:
                                        break;
                                }
                            }
                        }
                        if (response.data.documents.length == 0) {
                            $(`#${targetId} .alert-stnk`).removeClass("hidden")
                            $(`#${targetId} .content-stnk`).addClass("hidden")
                            $(`#${targetId} .alert-bpkb`).removeClass("hidden")
                            $(`#${targetId} .content-bpkb`).addClass("hidden")
                            $(`#${targetId} .alert-polis`).removeClass("hidden")
                            $(`#${targetId} .content-polis`).addClass("hidden")
                        }

                        if (data_is_import) {
                            if (response.data.import) {
                                console.log('import')
                                var data = response.data.import;
                                console.log(data)
                                $(`#${targetId} #detail_nomorPo`).val('-')
                                $(`#${targetId} #detail_tanggalPo`).val(data.tgl_po)
                                $(`#${targetId} #detail_nama_pengaju`).val(data.name);
                                $(`#${targetId} #detail_alamat_pengaju`).val('-');
                                $(`#${targetId} #detail_cabang`).val(data.cabang);
                                $(`#${targetId} #detail_no_po`).val('-');
                                $(`#${targetId} #detail_merk`).val(data.merk);
                                $(`#${targetId} #detail_tipe`).val(data.tipe);
                                $(`#${targetId} #detail_tahun`).val(data.tahun_kendaraan);
                                $(`#${targetId} #detail_harga`).val('Rp ' + formatMoney(data
                                    .harga, 0, ',', '.'));
                                $(`#${targetId} #detail_jumlah_pesanan`).val(data
                                    .jumlah);
                                var file_po_path = "{{ config('global.los_asset_url') }}" + data.po +
                                    "#navpanes=0";
                                //$(`#${targetId} #new_detail_filepo`).attr("src", file_po_path)
                            }
                        }
                        else {
                            if (response.data.pengajuan) {
                                var data = response.data.pengajuan;
                                $(`#${targetId} #detail_nomorPo`).val(data.no_po)
                                $(`#${targetId} #detail_tanggalPo`).val(data.tanggal)
                                $(`#${targetId} #detail_nama_pengaju`).val(data.nama);
                                $(`#${targetId} #detail_alamat_pengaju`).val(data.alamat_rumah);
                                $(`#${targetId} #detail_cabang`).val(data.cabang);
                                $(`#${targetId} #detail_no_po`).val(data.no_po);
                                $(`#${targetId} #detail_merk`).val(data.merk);
                                $(`#${targetId} #detail_tipe`).val(data.tipe);
                                $(`#${targetId} #detail_tahun`).val(data.tahun_kendaraan);
                                $(`#${targetId} #detail_harga`).val('Rp ' + formatMoney(data
                                    .harga_kendaraan, 0, ',', '.'));
                                $(`#${targetId} #detail_jumlah_pesanan`).val(data
                                    .jumlah_kendaraan);
                                var file_po_path = "{{ config('global.los_asset_url') }}" + data.po +
                                    "#navpanes=0";
                                $(`#${targetId} #new_detail_filepo`).attr("src", file_po_path)
                            }
                        }
                        Swal.close()

                        $(`#${targetId}`).removeClass("hidden");
                        $(`#${targetId} .layout-overlay-edit-form`).removeClass("hidden");
                    },
                    error: function(error) {
                        Swal.close()
                        //ErrorMessage('Terjadi kesalahan')
                        console.log('detail error')
                        console.log(error)
                    }
                })
            } else if (targetId == 'modalPO') {
                $(`#${targetId}`).removeClass("hidden");
                $(`#${targetId} .layout-overlay-edit-form`).removeClass("hidden");
                var nomorPo = $(identifier).data('nomorpo');
                var tanggalPo = $(identifier).data('tanggalpo');
                var filePo = $(identifier).data('filepo') + "#navpanes=0";

                var functionPrint = 'PrintPdfPO("' + $(identifier).data('filepo') + '")';
                $(`#${targetId} #nomorPo`).val(nomorPo);
                $(`#${targetId} #tanggalPo`).val(tanggalPo);
                $(`#${targetId} #filepo`).attr("src", filePo);
            } else if (targetId == 'modalUploadBerkas') {
                $(`#${targetId}`).removeClass("hidden");
                $(".layout-overlay-edit-form").removeClass("hidden");

                var id = $(identifier).data('id_kkb')
                var id_stnk = $(identifier).data('id-stnk') ? $(identifier).data('id-stnk') : '';
                var id_polis = $(identifier).data('id-polis') ? $(identifier).data('id-polis') : '';
                var id_bpkb = $(identifier).data('id-bpkb') ? $(identifier).data('id-bpkb') : '';
                var no_stnk = $(identifier).data('no-stnk') ? $(identifier).data('no-stnk') : ''
                var no_polis = $(identifier).data('no-polis') ? $(identifier).data('no-polis') : ''
                var no_bpkb = $(identifier).data('no-bpkb') ? $(identifier).data('no-bpkb') : ''
                var file_stnk = $(identifier).data('file-stnk') ? $(identifier).data('file-stnk') : ''
                var file_polis = $(identifier).data('file-polis') ? $(identifier).data('file-polis') : ''
                var file_bpkb = $(identifier).data('file-bpkb') ? $(identifier).data('file-bpkb') : ''
                var tanggal_stnk = $(identifier).data('date-stnk') ? $(identifier).data('date-stnk') : ''
                var tanggal_polis = $(identifier).data('date-polis') ? $(identifier).data('date-polis') : ''
                var tanggal_bpkb = $(identifier).data('date-bpkb') ? $(identifier).data('date-bpkb') : ''
                var confirm_at_stnk = $(identifier).data('confirm-at-stnk') ? $(identifier).data('confirm-at-stnk') : '-'
                var confirm_at_polis = $(identifier).data('confirm-at-polis') ? $(identifier).data('confirm-at-polis') : '-'
                var confirm_at_bpkb = $(identifier).data('confirm-at-bpkb') ? $(identifier).data('confirm-at-bpkb') : '-'
                var confirm_stnk = $(identifier).data('confirm-stnk') ? $(identifier).data('confirm-stnk') : ''
                var confirm_polis = $(identifier).data('confirm-polis') ? $(identifier).data('confirm-polis') : ''
                var confirm_bpkb = $(identifier).data('confirm-bpkb') ? $(identifier).data('confirm-bpkb') : ''

                var upload_stnk = $(identifier).data('file-stnk') ? $(identifier).data('file-stnk') : ''
                var upload_polis = $(identifier).data('file-polis') ? $(identifier).data('file-polis') : ''
                var upload_bpkb = $(identifier).data('file-bpkb') ? $(identifier).data('file-bpkb') : ''
                var is_confirm_stnk = $(identifier).data('confirm-stnk') ? $(identifier).data('confirm-stnk') : ''
                var is_confirm_polis = $(identifier).data('confirm-polis') ? $(identifier).data('confirm-polis') : ''
                var is_confirm_bpkb = $(identifier).data('confirm-bpkb') ? $(identifier).data('confirm-bpkb') : ''

                if (upload_stnk != '') {
                    if (is_confirm_stnk != '')
                        $(`#${targetId} #btn-confirm-stnk`).addClass('hidden')
                } else {
                    if (user_role_id == 2)
                        $(`#${targetId} .confirm-input-stnk`).addClass('hidden')
                    $(`#${targetId} #btn-confirm-stnk`).addClass('hidden')
                }

                if (upload_bpkb != '') {
                    if (is_confirm_bpkb != '')
                        $(`#${targetId} #btn-confirm-bpkb`).addClass('hidden')
                } else {
                    if (user_role_id == 2)
                        $(`#${targetId} .confirm-input-bpkb`).addClass('hidden')
                    $(`#${targetId} #btn-confirm-bpkb`).addClass('hidden')
                }

                if (upload_polis != '') {
                    if (is_confirm_polis != '')
                        $(`#${targetId} #btn-confirm-polis`).addClass('hidden')
                } else {
                    if (user_role_id == 2)
                        $(`#${targetId} .confirm-input-polis`).addClass('hidden')
                    $(`#${targetId} #btn-confirm-polis`).addClass('hidden')
                }

                // Visibility Components
                var stnkActive = $(`#${targetId} #stnk-tab-menu`).hasClass('active')
                var polisActive = $(`#${targetId} #polis-tab-menu`).hasClass('active')
                var bpkbActive = $(`#${targetId} #bpkb-tab-menu`).hasClass('active')

                if (file_stnk != '') {
                    if (user_role == 3)
                        $(`#${targetId} .form-submit-berkas`).css('display', 'none')
                    if (user_role == 2 && !confirm_stnk && stnkActive)
                        $(`#${targetId} .form-submit-berkas`).css('display', 'block')
                    else
                        $(`#${targetId} .form-submit-berkas`).css('display', 'none')
                    $(`#${targetId} .input-stnk`).css('display', 'none')
                    $(`#${targetId} #no_stnk`).prop('readonly', true)
                    $(`#${targetId} #modalUploadBerkas #tanggal_upload_stnk`).val(tanggal_stnk);
                    $(`#${targetId} #tanggal_confirm_stnk`).val((confirm_at_stnk));
                    $(`#${targetId} #status_confirm_stnk`).val((confirm_stnk ? 'Sudah dikonfirmasi' :
                        'Belum dikonfirmasi'));
                } else {
                    if (user_role == 2) {
                        $(`#${targetId} #stnk_belum_diunggah`).html('Berkas belum diunggah.')
                        $(`#${targetId} .input-no-stnk`).css('display', 'none')
                        if (stnkActive)
                            $(`#${targetId} .form-submit-berkas`).css('display', 'none')
                    } else {
                        if (stnkActive)
                            $(`#${targetId} .form-submit-berkas`).css('display', 'block')
                    }
                }

                try {
                    $(`#${targetId} #modal-berkas #id_kkb`).val(id);
                    if (id_stnk != '')
                        $(`#${targetId} #modal-berkas #id_stnk`).val(id_stnk);
                    if (id_polis != '')
                        $(`#${targetId} #modal-berkas #id_polis`).val(id_polis);
                    if (id_bpkb != '')
                        $(`#${targetId} #modal-berkas #id_bpkb`).val(id_bpkb);
                    if (no_stnk != '')
                        $(`#${targetId} #modal-berkas #no_stnk`).val(no_stnk);
                    if (no_polis != '')
                        $(`#${targetId} #modal-berkas #no_polis`).val(no_polis);
                    if (no_bpkb != '')
                        $(`#${targetId} #modal-berkas #no_bpkb`).val(no_bpkb);
                    if (file_stnk != '')
                        $(`#${targetId} #modal-berkas #stnk_scan`).val(file_stnk);
                    if (file_polis != '')
                        $(`#${targetId} #modal-berkas #polis_scan`).val(file_polis);
                    if (file_bpkb != '')
                        $(`#${targetId} #modal-berkas #bpkb_scan`).val(file_bpkb);
                } catch (e) {
                    console.log('error : ' + e)
                }
                var path_polis = "{{ asset('storage') }}" + "/dokumentasi-polis/" + file_polis;
                var path_bpkb = "{{ asset('storage') }}" + "/dokumentasi-bpkb/" + file_bpkb;

                if (file_stnk != '') {
                    var path_stnk = "{{ asset('storage') }}" + "/dokumentasi-stnk/" + file_stnk + "#navpanes=0";
                    $(`#${targetId} #preview_stnk`).attr("src", path_stnk);
                    if (user_role == 2) {
                        $(`#${targetId} #alert_stnk`).addClass("hidden")
                    } else {
                        $(`#${targetId} #stnk_input`).addClass("hidden")
                    }
                } else {
                    $(`#${targetId} #preview_stnk`).css("display", 'none');
                    if (user_role == 2) {
                        $(`#${targetId} #alert_stnk`).removeClass("hidden")
                    } else {
                        $(`#${targetId} #stnk_input`).removeClass("hidden")
                    }
                }

                if (file_polis != '') {
                    var path_polis = "{{ asset('storage') }}" + "/dokumentasi-polis/" + file_polis + "#navpanes=0";
                    $(`#${targetId} #preview_polis`).attr("src", path_polis);
                    $(`#${targetId} #polis_input`).addClass("hidden")
                    if (user_role == 2) {
                        $(`#${targetId} #alert_polis`).addClass("hidden")
                    } else {
                        $(`#${targetId} #polis_input`).addClass("hidden")
                    }

                } else {
                    $(`#${targetId} #polis_input`).removeClass("hidden")
                    $(`#${targetId} #preview_polis`).css("display", 'none');
                    if (user_role == 2) {
                        $(`#${targetId} #alert_polis`).removeClass("hidden")
                    } else {
                        $(`#${targetId} #polis_input`).removeClass("hidden")
                    }
                }

                if (file_bpkb != '') {
                    var path_bpkb = "{{ asset('storage') }}" + "/dokumentasi-bpkb/" + file_bpkb + "#navpanes=0";
                    $(`#${targetId} #preview_bpkb`).attr("src", path_bpkb);
                    if (user_role == 2) {
                        $(`#${targetId} #alert_bpkb`).addClass("hidden")
                    } else {
                        $(`#${targetId} #bpkb_input`).addClass("hidden")
                    }
                } else {
                    $(`#${targetId} #preview_bpkb`).css("display", 'none');
                    if (user_role == 2) {
                        $(`#${targetId} #alert_bpkb`).removeClass("hidden")
                    } else {
                        $(`#${targetId} #bpkb_input`).removeClass("hidden")
                    }
                }
            } else if (targetId == 'modalUploadImbalJasa') {
                $(`#${targetId}`).removeClass("hidden");
                $(".layout-overlay-edit-form").removeClass("hidden");
                const data_id = $(identifier).data('id')
                const data_nominal = $(identifier).data('nominal')
                $(`#${targetId} #id_kkbimbaljasa`).val(data_id)
                $(`#${targetId} #nominal_imbal_jasa`).val(data_nominal)
            } else if (targetId == 'modalUploadBuktiPenyerahanUnit') {
                $(`#${targetId}`).removeClass("hidden");
                $(".layout-overlay-edit-form").removeClass("hidden");

                const id = $(identifier).data('id_kkb');

                $(`#${targetId} #id_kkb`).val(id)
            } else if (targetId == 'modalBuktiPembayaran') {
                $(`#${targetId}`).removeClass("hidden");
                $(".layout-overlay-edit-form").removeClass("hidden");

                const file = $(identifier).data('file');
                const status = $(identifier).data('confirm') ? 'Sudah dikonfirmasi oleh vendor.' :
                    'Menunggu konfirmasi dari vendor.';
                const kategori = ($(identifier).data('kategori') === 'data_import') ? 'Catatan! Data ini merupakan data import google spreadsheet' : '';
                const tanggal = $(identifier).data('tanggal');
                const confirm_at = $(identifier).data('confirm_at');
                var path_file = "{{ asset('storage') }}" + "/dokumentasi-bukti-pembayaran/" + file + "#navpanes=0";
                fetch(path_file).then(function(response){
                    if(!response.ok){
                        $('.content-bukti-pembayaran').addClass("hidden");
                        $('.alert-bukti-pembayaran').removeClass("hidden");
                    }else{
                        $('.content-bukti-pembayaran').removeClass("hidden");
                        $('.alert-bukti-pembayaran').addClass("hidden");
                    }
                })

                $(`#${targetId} #kategori_data`).text(kategori)
                $('#bukti_pembayaran_img').attr('src', path_file)
                $('#tanggal_pembayaran').val(tanggal)
                $('#tanggal_confirm_pembayaran').val(confirm_at)
                $('#status_confirm').val(status)
            }
        }
    </script>
@endpush
@section('modal')
    <!-- Modal-Filter -->
    @include('pages.kredit.modal.filter-modal')
    <!-- Modal PO -->
    @include('pages.kredit.modal.detail-po')
    <!-- Modal Atur Ketersediaan Unit -->
    @include('pages.kredit.modal.atur-ketersediaan-unit-modal')
    <!-- Modal Upload Bukti Pembayaran -->
    @include('pages.kredit.modal.upload-bukti-pembayaran-modal')
    <!-- Modal Preview Bukti Pembayaran -->
    @include('pages.kredit.modal.bukti-pembayaran-modal')
    <!-- Modal Confirm Bukti Pembayaran -->
    @include('pages.kredit.modal.confirm-bukti-pembayaran-modal')
    <!-- Modal Upload Bukti Penyerahan Unit -->
    @include('pages.kredit.modal.upload-penyerahan-unit-modal')
    <!-- Modal Confirm Bukti Penyerahan Unit -->
    @include('pages.kredit.modal.confirm-penyerahan-unit')
    <!-- Modal Upload Tagihan -->
    @include('pages.kredit.modal.upload-berkas-tagihan-modal')
    <!-- Modal Show Tagihan -->
    @include('pages.kredit.modal.show-tagihan-modal')
    <!-- Modal Upload Berkas -->
    @include('pages.kredit.modal.upload-berkas-modal')
    <!-- Modal Upload Imbal Jasa -->
    @include('pages.kredit.modal.upload-bukti-imbal-jasa')
    <!-- Modal Confirm Imbal Jasa -->
    @include('pages.kredit.modal.confirm-bukti-pembayaran-imbal-jasa-modal')
    <!-- Modal Detail PO -->
    @include('pages.kredit.modal.detail-modal')
@endsection
@section('content')
    <div class="head-pages">
        <p class="text-sm">KKB</p>
        <h2 class="text-2xl font-bold text-theme-primary tracking-tighter">
            KKB
        </h2>
    </div>
    <div class="body-pages">
        <input type="hidden" name="tab" id="tab_type"
            value="@isset($_GET['tab_type']) {{ $_GET['tab_type'] }} @endisset">
        <div class="tab-wrapper flex">
            <a href="" data-tab="tab-kkb"
                class="tab-btn bg-white px-5 py-2 border border-b-0 text-theme-primary  rounded-tr-md rounded-tl-md">Data
                KKB</a></li>
            @if (\Session::get(config('global.role_id_session')) != 3)
                <a href="" data-tab="tab-import-kkb"
                    class="tab-btn px-5 py-2 border border-b-0 rounded-tr-md rounded-tl-md">Data Import Google SpreadSheet</a></li>
            @endif
        </div>

        <div id="tab-kkb" class="tab-content-table">
            <div class="table-wrapper bg-white border rounded-md w-full p-2">
                <div class="table-accessiblity lg:flex text-center lg:space-y-0 space-y-5 justify-between">
                    <div class="title-table lg:p-3 p-2 text-left">
                        <h2 class="font-bold text-lg text-theme-text tracking-tighter">
                            Data KKB
                        </h2>
                        @if (\Request::get('tab_type') == 'tab-kkb')
                            @if (\Request::get('tAwal') && \Request::get('tAkhir'))
                                <p class="text-gray-600 text-sm">Menampilkan data mulai tanggal <b>{{date('d-m-Y', strtotime(\Request::get('tAwal')))}}</b> s/d <b>{{date('d-m-Y', strtotime(\Request::get('tAkhir')))}}</b>.</p>
                            @endif
                        @endif
                    </div>
                    <div class="table-action flex lg:justify-normal justify-center p-2 gap-2">
                        @if (\Request::has('tAwal') || \Request::has('tAkhir') || \Request::has('status'))
                            <form action="" method="get">
                                <button type="submit"
                                    class="px-6 py-2 bg-theme-primary/10 flex gap-3 rounded text-theme-primary">
                                    <span class="lg:mt-1.5 mt-0">
                                        @include('components.svg.reset')
                                    </span>
                                    <span class="lg:block hidden"> Reset </span>
                                </button>
                            </form>
                        @endif
                        <a>
                            <button data-target-id="filter-kkb" type="button"
                                class="toggle-modal px-6 py-2 bg-theme-primary flex gap-3 rounded text-white">
                                <span class="lg:mt-1 mt-0">
                                    @include('components.svg.filter')
                                </span>
                                <span class="lg:block hidden"> Filter </span>
                            </button>
                        </a>
                    </div>
                </div>
                <form action="" id="form_kkb">
                    <input type="hidden" name="tab_type" class="tab_type_kkb"
                        value="@isset($_GET['tab_type']) {{ $_GET['tab_type'] }} @endisset">
                    <div class="lg:flex lg:space-y-0 space-y-5 lg:text-left text-center justify-between mt-2 p-2">
                        <div class="sorty pl-1 w-full">
                            <input type="hidden" name="page" id="page"
                                value="{{ isset($_GET['page']) ? $_GET['page'] : 1 }}">
                            <label for="page_length" class="mr-3 text-sm text-neutral-400">show</label>
                            <select name="page_length" id="page_length"
                                class="border px-4 py-1.5 cursor-pointer rounded appearance-none text-center"
                                id="">
                                <option value="5"
                                    @isset($_GET['page_length']) {{ $_GET['page_length'] == 5 ? 'selected' : '' }} @endisset>
                                    5</option>
                                <option value="10"
                                    @isset($_GET['page_length']) {{ $_GET['page_length'] == 10 ? 'selected' : '' }} @endisset>
                                    10</option>
                                <option value="15"
                                    @isset($_GET['page_length']) {{ $_GET['page_length'] == 15 ? 'selected' : '' }} @endisset>
                                    15</option>
                                <option value="20"
                                    @isset($_GET['page_length']) {{ $_GET['page_length'] == 20 ? 'selected' : '' }} @endisset>
                                    20</option>
                            </select>
                            <label for="" class="ml-3 text-sm text-neutral-400">entries</label>
                        </div>
                        <div class="search-table lg:w-96 w-full">
                            <div class="input-search text-[#BFBFBF] rounded-md border flex gap-2">
                                <span class="mt-2 ml-3">
                                    @include('components.svg.search')
                                </span>
                                <input type="search" placeholder="Search" name="query" value="@if(\Request::has('tab_type') )@if(\Request::get('tab_type') == 'tab-kkb'){{\Request::has('query') ?\Request::get('query'):''}}@endif @endif"
                                    class="p-2 rounded-md w-full outline-none text-[#BFBFBF]" autocomplete="off" />
                            </div>
                        </div>
                    </div>
                </form>
                <div id="table_content">
                    @include('pages.kredit.partial._table')
                </div>
            </div>
        </div>
        @if (\Session::get(config('global.role_id_session')) != 3)
            <div id="tab-import-kkb" class="tab-content-table">
                <div class="table-wrapper bg-white border rounded-md w-full p-2">
                    <div class="table-accessiblity lg:flex text-center lg:space-y-0 space-y-5 justify-between">
                        <div class="title-table lg:p-3 p-2 text-left">
                            <h2 class="font-bold text-lg text-theme-text tracking-tighter">
                                Data Import Google SpreadSheet
                            </h2>
                            @if (\Request::get('tab_type') == 'tab-import-kkb')
                                @if (\Request::get('tAwal') && \Request::get('tAkhir'))
                                    <p class="text-gray-600 text-sm">Menampilkan data mulai tanggal <b>{{date('d-m-Y', strtotime(\Request::get('tAwal')))}}</b> s/d <b>{{date('d-m-Y', strtotime(\Request::get('tAkhir')))}}</b>.</p>
                                @endif
                            @endif
                        </div>
                        <div class="table-action flex lg:justify-normal justify-center p-2 gap-2">
                            @if ($is_kredit_page && (\Session::get(config('global.role_id_session')) == 4 || \Session::get(config('global.role_id_session')) == 2))
                                <a href="{{ route('import-kkb.index') }}">
                                    <button type="button"
                                        class="toggle-modal px-6 py-2 border bg-white flex gap-3 rounded text-gray-600">
                                        <span class="lg:mt-1 mt-0">
                                            @include('components.svg.import-table')
                                        </span>
                                        <span class="lg:block hidden"> Import </span>
                                    </button>
                                </a>
                            @endif
                            @if (\Request::has('tAwal') || \Request::has('tAkhir') || \Request::has('status'))
                                    <a href="{{route('kredit.index')}}"
                                        class="px-6 py-2 bg-theme-primary/10 flex gap-3 rounded text-theme-primary">
                                        <span class="lg:mt-1.5 mt-0">
                                            @include('components.svg.reset')
                                        </span>
                                        <span class="lg:block hidden"> Reset </span>
                                    </a>
                            @endif
                            <a>
                                <button data-target-id="filter-kkb"
                                    class="toggle-modal px-6 py-2 bg-theme-primary flex gap-3 rounded text-white">
                                    <span class="lg:mt-1 mt-0">
                                        @include('components.svg.filter')
                                    </span>
                                    <span class="lg:block hidden"> Filter </span>
                                </button>
                            </a>
                        </div>
                    </div>
                    <form action="" id="form_import">
                        <input type="hidden" name="tab_type" class="tab_type_import"
                            value="@isset($_GET['tab_type']) {{ $_GET['tab_type'] }} @endisset">
                        <div class="lg:flex lg:space-y-0 space-y-5 lg:text-left text-center justify-between mt-2 p-2">
                            <div class="sorty pl-1 w-full">
                                <input type="hidden" name="page" id="page"
                                    value="{{ isset($_GET['page']) ? $_GET['page'] : 1 }}">
                                <label for="page_length_import" class="mr-3 text-sm text-neutral-400">show</label>
                                <select name="page_length_import" id="page_length_import"
                                    class="border px-4 py-1.5 cursor-pointer rounded appearance-none text-center" id="">
                                    <option value="5"
                                        @isset($_GET['page_length_import']) {{ $_GET['page_length_import'] == 5 ? 'selected' : '' }} @endisset>
                                        5</option>
                                    <option value="10"
                                        @isset($_GET['page_length_import']) {{ $_GET['page_length_import'] == 10 ? 'selected' : '' }} @endisset>
                                        10</option>
                                    <option value="15"
                                        @isset($_GET['page_length_import']) {{ $_GET['page_length_import'] == 15 ? 'selected' : '' }} @endisset>
                                        15</option>
                                    <option value="20"
                                        @isset($_GET['page_length_import']) {{ $_GET['page_length_import'] == 20 ? 'selected' : '' }} @endisset>
                                        20</option>
                                </select>
                                <label for="" class="ml-3 text-sm text-neutral-400">entries</label>
                            </div>
                            <div class="search-table lg:w-96 w-full">
                                <div class="input-search text-[#BFBFBF] rounded-md border flex gap-2">
                                    <span class="mt-2 ml-3">
                                        @include('components.svg.search')
                                    </span>
                                    <input type="search" placeholder="Search" name="query" value="@if(\Request::has('tab_type') )@if(\Request::get('tab_type') == 'tab-import-kkb'){{\Request::has('query') ?\Request::get('query'):''}}@endif @endif"
                                        class="p-2 rounded-md w-full outline-none text-[#BFBFBF]" autocomplete="off" />
                                </div>
                            </div>
                        </div>
                    </form>
                    <div id="table_content_import">
                        @include('pages.kredit.partial.imported._table')
                    </div>
                </div>
            </div>
        @endif
    </div>
    </div>

@endsection

@push('extraScript')
    <script>
        $('#page_length').on('change', function() {
            $('#form_kkb').submit()
        })

        $('#page_length_import').on('change', function() {
            $('#form_import').submit()
        })

        let tabId;
        $(".tab-wrapper .tab-btn").click(function(e) {
            e.preventDefault();
            tabId = $(this).data("tab")
            $('#tab_type').val(tabId)
            if (tabId == 'tab-kkb') {
                $('.tab_type_kkb').val(tabId)
            }
            if (tabId == 'tab-import-kkb') {
                $('.tab_type_import').val(tabId)
            }

            $(".tab-content-table").addClass("hidden");
            $(".tab-wrapper .tab-btn").removeClass("bg-white border");
            $(".tab-wrapper .tab-btn").removeClass("text-gray-400");
            $(".tab-wrapper .tab-btn").removeClass("text-theme-primary");
            $(".tab-wrapper .tab-btn").addClass("text-gray-400");
            $(".tab-wrapper .tab-btn").addClass("border-b-2 border");
            $(this).addClass("bg-white border-b-2");
            $(this).addClass("text-theme-primary");
            if (tabId) {
                $(this).removeClass("text-gray-400");
                $(this).removeClass("bg-[#dcdcdc]");
            }
            $(`#${tabId}`).removeClass("hidden");

            var btn_pagination = $(`#${tabId} .pagination`).find('a')
            var page_url = window.location.href
            $(`#${tabId} .pagination`).find('a').each(function(i, obj) {
                if (tabId) {
                    if (page_url.includes('tab_type')) {
                        var url_before = page_url.split('?')
                        var url_params = url_before[1]
                        var split_param = url_params.split('&')
                        var tab_type_param = '';
                        for (var j = 0; j < split_param.length; j++) {
                            if (split_param[j].includes('tab_type')) {
                                tab_type_param = split_param[j];
                                break;
                            }
                        }
                        if (tab_type_param) {
                            var type_param = tab_type_param.split('=')[0]
                            if (!btn_pagination[i].href.includes('type_param'))
                                btn_pagination[i].href += `&tab_type=${tabId}`
                        }
                    } else {
                        if (!btn_pagination[i].href.includes('type_param'))
                            btn_pagination[i].href += `&tab_type=${tabId}`
                    }
                }
            })
        });

        var tab_type = "@isset($_GET['tab_type']){{ $_GET['tab_type'] }}@endisset"
        if (tab_type == 'tab-kkb' || !tab_type)
            $("div.tab-wrapper .tab-btn:first").trigger("click");
        if (tab_type == 'tab-import-kkb')
            $("div.tab-wrapper .tab-btn:last").trigger("click");
    </script>
@endpush
