@extends('layout.master')
@push('extraStyle')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.7.7/xlsx.core.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xls/0.7.4-a/xls.core.min.js"></script>
@endpush
@section('content')
    <div class="head-pages">
        <p class="text-sm">KKB</p>
        <h2 class="text-2xl font-bold text-theme-primary tracking-tighter">
            Import Table KKB
        </h2>
    </div>
    <div class="body-pages">
        <div class="form-dictionary bg-white w-full space-y-5 p-4 rounded-md border">
            <div class="table-wrapper bg-white border rounded-md w-full p-2">
                <div class="table-accessiblity lg:flex text-center lg:space-y-0 space-y-5 justify-between">
                    <div class="title-table lg:p-3 p-2 text-center">
                        <h2 class="font-bold text-lg text-theme-text tracking-tighter">
                            Item
                        </h2>
                    </div>
                </div>
                <form action="{{ route('import-kkb.store') }}" id="import-form" method="post">
                    @csrf
                    <div class="row">
                    {{--  <div class="lg:flex lg:space-y-0 space-y-5 lg:text-left text-center justify-between mt-2 p-2">  --}}
                        <div class="col-md-6">
                            <div class="sorty pl-1">
                                <a href="#" class="font-bold tracking-tighter">Lihat Contoh Format</a>
                                <p>
                                    Catatan! Jika menggunakan fitur import, maka data pada
                                    tabel akan dikosongkan terlebih dahulu.
                                </p>
                            </div>
                            <div class="table-action flex lg:justify-normal justify-center p-2 gap-2">
                                @if (\Session::get(config('global.role_id_session')) === 4)
                                    {{--  superadmin  --}}
                                    <label for="" class="mr-3 text-sm text-neutral-400">Pilih Cabang</label>
                                    <select class="border px-4 py-1.5 cursor-pointer rounded appearance-none text-center"
                                        name="kode_cabang" id="kode_cabang" required>
                                        <option value="">-- Pilih Cabang --</option>
                                        @if ($cabang)
                                            @for ($i=0;$i<count($cabang); $i++)
                                                <option value="{{ $cabang[$i]['kode_cabang'] }}" {{ old('kode_cabang') == $cabang[$i]['kode_cabang'] ? 'selected' : '' }}>
                                                    {{ $cabang[$i]['kode_cabang'].' - '.$cabang[$i]['cabang'] }}
                                                </option>
                                            @endfor
                                        @endif
                                    </select>
                                @else
                                    {{--  selain superadmin  --}}
                                    <label for="" class="mr-3">Cabang : </label>
                                    <label for="" class="mr-3">
                                        @if ($cabang)
                                            @for ($i=0;$i<count($cabang); $i++)
                                                @if (Session::get(config('global.user_kode_cabang_session')) == $cabang[$i]['kode_cabang'])
                                                    {{ $cabang[$i]['kode_cabang'].' - '.$cabang[$i]['cabang'] }}
                                                @endif
                                            @endfor
                                        @endif
                                    </label>
                                    <input type="hidden" name="kode_cabang" id="kode_cabang" value="{{Session::get(config('global.user_kode_cabang_session'))}}">
                                @endif
                                <input type="file" name="file" id="file" class=""
                                    accept="application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                                <button type="button" class="px-6 py-2 bg-green-500 flex gap-3 rounded text-white btn-import">
                                    <span class="lg:mt-1 mt-0">
                                        @include('components.svg.import-table')
                                    </span>
                                    <span class=""> Import </span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="tables mt-2">
                        <span id="span_info_save"></span>
                        <span id="span_total_data"></span>
                        <table class="table-for-kkb-import table-auto w-full mt-2" id="table_item">
                            <thead>
                                <tr>
                                    <th rowspan="2" scope="col">No.</th>
                                    <th rowspan="2" scope="col">Nama Debitur</th>
                                    <th rowspan="2" scope="col">Tanggal PO</th>
                                    <th rowspan="2" scope="col">Merk Kendaraan</th>
                                    <th rowspan="2" scope="col">Tipe Kendaraan</th>
                                    <th rowspan="2" scope="col">Tahun</th>
                                    <th rowspan="2" scope="col">Warna</th>
                                    <th rowspan="2" scope="col">Qty</th>
                                    <th rowspan="2" scope="col">Harga</th>
                                    <th rowspan="2" scope="col">Nama STNK</th>
                                    <th rowspan="2" scope="col">Nominal Realisasi</th>
                                    <th rowspan="2" scope="col">Nominal Imbal Jasa</th>
                                    <th rowspan="2" scope="col">Nominal DP</th>
                                    <th rowspan="2" scope="col">Tanggal Realisasi</th>
                                    <th rowspan="2" scope="col">Tanggal Pelunasan ke BJSC</th>
                                    <th rowspan="2" scope="col">Tanggal Penyerahan Unit</th>
                                    <th rowspan="2" scope="col">Tanggal Penyerahan STNK dan Plat Nomor</th>
                                    <th rowspan="2" scope="col">Tanggal Penyerahan BPKB</th>
                                    <th rowspan="2" scope="col">BPKB Via BJSC</th>
                                    <th rowspan="2" scope="col">Tanggal Polis Asuransi</th>
                                    <th rowspan="2" scope="col">Polis Via BJSC</th>
                                    <th scope="col" colspan="2">
                                        Pembayaran Imbal Jasa
                                    </th>
                                    <th rowspan="2" scope="col">
                                        Keterangan (diisi kendala)
                                    </th>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Nominal (Rp)</th>
                                    </tr>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                    <div class="footer-table p-3 text-theme-text lg:flex lg:space-y-0 space-y-10 justify-between">
                        <button id="btn-simpan"
                            class="px-6 py-2 bg-theme-primary flex gap-3 rounded text-white hidden">
                            <span class=""> Simpan </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('extraScript')
    <script>
        $(document).ready(function() {
            var role_id = "{{\Session::get(config('global.role_id_session'))}}"
            if (role_id == 4)
                $('#kode_cabang').select2({});
        })
        $('#import-form').on('submit', function(e) {
            Swal.fire({
                showConfirmButton: false,
                closeOnClickOutside: false,
                title: 'Memproses data...',
                html: 'Silahkan tunggu...',
                allowEscapeKey: false,
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading()
                }
            });
        })

        $('.btn-import').on('click', function(e) {
            var kode_cabang = $('#kode_cabang').val()
            var file = $('#file').val()
            if (kode_cabang && file) {
                // open loading dialog
                Swal.fire({
                    showConfirmButton: false,
                    closeOnClickOutside: false,
                    title: 'Memuat data...',
                    html: 'Silahkan tunggu...',
                    allowEscapeKey: false,
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading()
                    }
                });

                importExcel();
            }
            else {
                var alert_msg = ''
                if (!kode_cabang && file)
                    alert_msg += 'kode cabang'
                else if (!file && kode_cabang)
                    alert_msg += 'berkas'
                else if (!kode_cabang && !file)
                    alert_msg += 'kode cabang & berkas'

                Swal.fire({
                    showConfirmButton: false,
                    timer: 3000,
                    closeOnClickOutside: true,
                    icon: 'error',
                    title: 'Gagal',
                    text: `Harap pilih ${alert_msg} terlebih dahulu`,
                })
            }
        })

        function showToTable(data) {
            var total_data = data.length
            $('#span_info_save').html('Informasi! Geser layar ke bawah untuk menyimpan data.')
            $('#span_total_data').html(`Total data : ${total_data}`)
            $('#btn-simpan').removeClass('hidden')

            for (var i = 0; i < data.length; i++) {
                var row = data[i]

                // format harga
                var harga_kendaraan = row[8].replaceAll(',.', '')
                var nominal_realisasi = row[10].replaceAll(',.', '')
                var nominal_imbal_jasa = row[11].replaceAll(',.', '')
                var nominal_dp = row[13].replaceAll(',.', '')
                var nominal_pembayaran_imbal_jasa = row[22].replaceAll(',', '')
                nominal_pembayaran_imbal_jasa = nominal_pembayaran_imbal_jasa.replaceAll('.', '')

                var format_harga_kendaraan = row[8] != '-' ? `Rp ${formatMoney(harga_kendaraan, 0, ',', '.')}` : '-'
                var format_nominal_realisasi = row[10] != '-' ? `Rp ${formatMoney(nominal_realisasi, 0, ',', '.')}` : '-'
                var format_nominal_imbal_jasa = row[11] != '-' ? `Rp ${formatMoney(nominal_imbal_jasa, 0, ',', '.')}` : '-'
                var format_nominal_dp = row[13] != '-' ? `Rp ${formatMoney(nominal_dp, 0, ',', '.')}` : '-'
                var format_nominal_pembayaran_imbal_jasa = row[22] != '-' ? `Rp ${formatMoney(nominal_pembayaran_imbal_jasa, 0, ',', '.')}` : '-'

                var ket = null
                var ket_hidden_html = ``;
                var ket_html = ``;

                if (row[23] != '-') {
                    ket = row[23].split('-')
                }
                
                if (ket) {
                    for (var j=0; j<ket.length; j++) {
                        if (ket[j]) {
                            ket_hidden_html += `<input type="hidden" name="keterangan[${i}][]" id="keterangan[${i}][]"value="${ket[j]}">`
                            ket_html += `<span style="text-align: justify !important;">${j}. ${ket[j]}</span>`
                        }
                    }
                }
                else {
                    ket_hidden_html = `<input type="hidden" name="keterangan[][0]" id="keterangan[][0]"value="">`
                    ket_html = '-';
                }

                var new_tr = `
                <tr>
                    <td>${row[0]}</td>
                    <td>
                        <input type="hidden" name="nama_debitur[]" id="nama_debitur[]"value="${row[1]}">
                        ${row[1]}
                    </td>
                    <td>
                        <input type="hidden" name="tgl_po[]" id="tgl_po[]"value="${row[2]}">
                        ${row[2]}
                    </td>
                    <td>
                        <input type="hidden" name="merk_kendaraan[]" id="merk_kendaraan[]"value="${row[3]}">
                        ${row[3]}
                    </td>
                    <td>
                        <input type="hidden" name="tipe_kendaraan[]" id="tipe_kendaraan[]"value="${row[4]}">
                        ${row[4]}
                    </td>
                    <td>
                        <input type="hidden" name="tahun_kendaraan[]" id="tahun_kendaraan[]"value="${row[5]}">
                        ${row[5]}
                    </td>
                    <td>
                        <input type="hidden" name="warna_kendaraan[]" id="warna_kendaraan[]"value="${row[6]}">
                        ${row[6]}
                    </td>
                    <td>
                        <input type="hidden" name="qty_kendaraan[]" id="qty_kendaraan[]"value="${row[7]}">
                        ${row[7]}
                    </td>
                    <td>
                        <input type="hidden" name="harga_kendaraan[]" id="harga_kendaraan[]"value="${harga_kendaraan}">
                        ${format_harga_kendaraan}
                    </td>
                    <td>
                        <input type="hidden" name="nama_stnk[]" id="nama_stnk[]"value="${row[9]}">
                        ${row[9]}
                    </td>
                    <td>
                        <input type="hidden" name="nominal_realisasi[]" id="nominal_realisasi[]"value="${nominal_realisasi}">
                        ${format_nominal_realisasi}
                    </td>
                    <td>
                        <input type="hidden" name="nominal_imbal_jasa[]" id="nominal_imbal_jasa[]"value="${nominal_imbal_jasa}">
                        ${format_nominal_imbal_jasa}
                    </td>
                    <td>
                        <input type="hidden" name="nominal_dp[]" id="nominal_dp[]"value="${nominal_dp}">
                        ${format_nominal_dp}
                    </td>
                    <td>
                        <input type="hidden" name="tgl_realisasi[]" id="tgl_realisasi[]"value="${row[13]}">
                        ${row[13]}
                    </td>
                    <td>
                        <input type="hidden" name="tgl_pelunasan[]" id="tgl_pelunasan[]"value="${row[14]}">
                        ${row[14]}
                    </td>
                    <td>
                        <input type="hidden" name="tgl_penyerahan_unit[]" id="tgl_penyerahan_unit[]"value="${row[15]}">
                        ${row[15]}
                    </td>
                    <td>
                        <input type="hidden" name="tgl_penyerahan_stnk[]" id="tgl_penyerahan_stnk[]"value="${row[16]}">
                        ${row[16]}
                    </td>
                    <td>
                        <input type="hidden" name="tgl_penyerahan_bpkb[]" id="tgl_penyerahan_bpkb[]"value="${row[17]}">
                        ${row[17]}
                    </td>
                    <td>
                        <input type="hidden" name="bpkb_via_bjsc[]" id="bpkb_via_bjsc[]"value="${row[18]}">
                        ${row[18]}
                    </td>
                    <td>
                        <input type="hidden" name="tgl_penyerahan_polis[]" id="tgl_penyerahan_polis[]"value="${row[19]}">
                        ${row[19]}
                    </td>
                    <td>
                        <input type="hidden" name="polis_via_bjsc[]" id="polis_via_bjsc[]"value="${row[20]}">
                        ${row[20]}
                    </td>
                    <td>
                        <input type="hidden" name="tgl_pembayaran_imbal_jasa[]" id="tgl_pembayaran_imbal_jasa[]"value="${row[21]}">
                        ${row[21]}
                    </td>
                    <td>
                        <input type="hidden" name="nominal_pembayaran_imbal_jasa[]" id="nominal_pembayaran_imbal_jasa[]"value="${nominal_pembayaran_imbal_jasa}">
                        ${format_nominal_pembayaran_imbal_jasa}
                    </td>
                    <td>
                        ${ket_hidden_html}
                        ${ket_html}
                    </td>
                </tr>
                `;
                $('#table_item tbody').append(new_tr);
            }

            Swal.close() // close loading dialog
        }

        function importExcel() {
            $('#table_item tbody').empty()
            var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xlsx|.xls)$/;  
            /*Checks whether the file is a valid excel file*/  
            if (regex.test($("#file").val().toLowerCase())) {  
                var xlsxflag = false; /*Flag for checking whether excel is .xls format or .xlsx format*/  
                if ($("#file").val().toLowerCase().indexOf(".xlsx") > 0) {  
                    xlsxflag = true;  
                }  
                /*Checks whether the browser supports HTML5*/  
                if (typeof (FileReader) != "undefined") {  
                    var reader = new FileReader();  
                    reader.onload = function (e) {  
                        var data = e.target.result;
                        /*Converts the excel data in to object*/  
                        if (xlsxflag) {  
                            var workbook = XLSX.read(data, { type: 'binary' });  
                        }
                        /*Gets all the sheetnames of excel in to a variable*/  
                        var sheet_name_list = workbook.SheetNames;
                        var cnt = 0; /*This is used for restricting the script to consider only first sheet of excel*/  
                        sheet_name_list.forEach(function (y) { /*Iterate through all sheets*/  
                            var exceljson = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[y]);
                            var excel = workbook.Sheets[y];
                            var cell_range = excel['!ref'] != '' ? excel['!ref'].split(':') : [];
                            var cell_from = cell_range.length == 2 ? cell_range[0] : ''
                            var cell_to = cell_range.length == 2 ? cell_range[1] : ''
                            var letterPattern = /[a-z]+/gi;
                            var cell_from_letter = cell_from.match(letterPattern)[0]
                            var cell_to_letter = cell_to.match(letterPattern)[0]
                            var numberPattern = /\d+/g;
                            var cell_from_number = cell_from.match(numberPattern)[0]
                            var cell_to_number = cell_to.match(numberPattern)[0]
                            var cell_range_letter = [
                                'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J',
                                'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T',
                                'U', 'V', 'W', 'X',
                            ]

                            var arr_data = [];
                            var arr_index = [];

                            for (var i = 1; i <= cell_to_number; i++) {
                                var arr_row = [];
                                for (var j = 0; j < cell_range_letter.length; j++) {
                                    var index = `${cell_range_letter[j]}${i}`
                                    arr_index.push(index)
                                    //arr_row.push(excel[index])
                                    if (i > 2) {
                                        //arr_row.push(excel[index])
                                        if (excel[index])
                                            arr_row.push(excel[index].w)
                                        else
                                            arr_row.push('-')
                                    }
                                }
                                if (arr_row.length > 0)
                                    arr_data.push(arr_row)
                            }
                            console.log(arr_index)
                            console.log(arr_data)
                            // Show excel data to html table
                            showToTable(arr_data)
                        }); 
                    }  
                    if (xlsxflag) {/*If excel file is .xlsx extension than creates a Array Buffer from excel*/  
                        reader.readAsArrayBuffer($("#file")[0].files[0]);  
                    }  
                    else {  
                        reader.readAsBinaryString($("#file")[0].files[0]);  
                    }  
                }  
                else {  
                    alert("Maaf! Browser Anda tidak mendukung HTML5!");  
                }  
            }  
            else {  
                alert("Unggah file Excel yang valid!");  
            }  
        }

    </script>
@endpush