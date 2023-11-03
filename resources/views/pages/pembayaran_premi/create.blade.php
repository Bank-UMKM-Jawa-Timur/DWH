@extends('layout.master')

@section('modal')
    @include('pages.pembayaran_premi.modal.modal-calculator')
    @include('pages.pembayaran_premi.modal.loading')
@endsection

@section('content')
    <style>
        .errorSpan{
            min-height: 20px;
        }

        .errorSpan p{
            color: red;
        }
    </style>
    <div class="head-pages">
        <p class="text-sm">Asuransi</p>
        <h2 class="text-2xl font-bold text-theme-primary tracking-tighter">
            Tambah Pembayaran Premi
        </h2>
        <div class="mt-5 flex justify-start">
            {{-- <button data-target-id="modal-calculator"
                class="toggle-modal px-6 py-2 bg-white hover:bg-white/20 border flex gap-3 rounded text-gray-500">
                <span class="lg:mt-0 mt-0">
                    @include('components.svg.calculator')
                </span>
                <span class="block"> Kalkulator </span>
            </button> --}}
        </div>
    </div>
    <div class="body-pages p-5">
        <div class="flex justify-start mb-5">
            <button type="button" id="form-toggle" class="px-6 py-2 bg-theme-primary/10 flex gap-3 rounded text-theme-primary">
                <span class="form-button-text"> Sembunyikan form </span>
            </button>
        </div>
        <form id="FormId" action="{{ route('asuransi.pembayaran-premi.store') }}" method="post">
            @csrf
            {{-- form pembayaran --}}
            <div class="bg-white form-selection w-full flex-none p-5 border">
                <h2 class="font-bold text-lg text-theme-text tracking-tighter mb-3">
                    Pembayaran Premi
                </h2>
                <div class="lg:grid-cols-2 md:grid-cols-2 grid-cols-1 grid gap-5 justify-center">
                    <div class="input-box space-y-3" id="inputBoxNoAplikasi">
                        <label for="add-role" class="uppercase">Nomor Aplikasi<span class="text-theme-primary">*</span> </label>
                        <select name="no_aplikasi" id="no_aplikasi" class="w-full p-2 border">
                            <option value="" selected>-- Pilih No Aplikasi ---</option>
                            @foreach ($noAplikasi as $item)
                                <option @if (old('no_aplikasi') == $item->no_aplikasi)
                                    selected @endif value="{{$item->no_aplikasi}}">{{$item->no_aplikasi}} - {{$item->nama_debitur}}</option>
                            @endforeach
                        </select>
                        <div class="errorSpan" id="errorNoAplikasi">
                            <p id="errorText">Nomor aplikasi harus diisi.</p>
                        </div>
                    </div>
                    <div class="input-box-calendar space-y-3">
                        <label for="" class="uppercase">Tanggal Bayar<span class="text-theme-primary">*</span></label>
                        <div class="flex border justify-center ">
                            <div class="flex justify-center p-2 "><span>@include('components.svg.calendar')</span></div>
                        <input type="text" value="old('tgl_bayar')" class="datepicker p-2 w-full" id="tgl_bayar" name="tgl_bayar" />
                        </div>
                        <small class="form-text text-red-600 error tgl-bayar-error"></small>
                    </div>
                    <div class="input-box space-y-3">
                        <label for="" class="uppercase">No Bukti Pembayaran<span class="text-theme-primary">*</span></label>
                        <input type="text" value="old('no_bukti_pembayaran')" class="p-2 w-full border " id="no_bukti_pembayaran" name="no_bukti_pembayaran"/>
                        <small class="form-text text-red-600 error no-bukti-pembayaran-error"></small>
                    </div>
                    <div class="input-box space-y-3">
                        <label for="" class="uppercase">Total Premi<span class="text-theme-primary">*</span></label>
                        <input type="hidden" id="total_premi" name="total_premi"/>
                        <input type="text" value="old('display_total_premi')" class="input-disabled bg-disabled p-2 w-full border " id="display_total_premi" name="display_total_premi" readonly/>
                        <small class="form-text text-red-600 error"></small>
                    </div>

                </div>
                <div class="">
                    <div class="p-2 mt-3 mb-3 space-y-4" id="inputBoxJenisAsuransi">
                        <h2 class="font-bold font-lexend jenis-asuransi-title hidden">Jenis Asuransi</h2>
                        <div class="jenis-asuransi flex justify-start gap-5 mt-5"></div>
                        <div class="errorSpan" id="errorJenisAsuransi">
                            <p id="errorText">Jenis asuransi harus diisi.</p>
                        </div>
                    </div>
                    <div class="p-2 hidden"  id="btnLeftForm">
                        <button
                        href="{{route('asuransi.pembayaran-premi.create')}}"
                        class="px-6 py-2 bg-theme-primary flex gap-3 rounded text-white">
                            <span class="lg:mt-0 mt-0">
                                @include('components.svg.plus')
                            </span>
                            <span class="lg:block hidden"> Pilih</span>
                        </button>
                    </div>
                </div>
            </div>

            <div class="lg:flex grid md:grid-cols-1 grid-cols-1 w-full gap-5 mt-3">
                <div class="flex-auto lg:w-[40rem] w-full">
                    <div class="table-wrapper bg-white border rounded-md w-full p-2">
                        {{-- <form action="{{ route('asuransi.pembayaran-premi.store') }}" method="post">
                            @csrf --}}
                            <div class="tables mt-2">
                                <table class="table-auto w-full">
                                    <tr>
                                        <th>No.</th>
                                        <th>Jenis</th>
                                        <th>No Aplikasi.</th>
                                        <th>Premi</th>
                                        <th>No PK</th>
                                        <th>No Polis</th>
                                        <th>No Rekening.</th>
                                        <th>Periode Bayar</th>
                                        <th>Total Periode Bayar (dalam tahun)</th>
                                        <th>Aksi</th>
                                    </tr>
                                    <tbody id="rightForm"></tbody>
                                </table>
                            </div>
                            <div class="footer-table p-3 text-theme-text lg:flex lg:space-y-0 space-y-10 justify-end">
                                <button type="submit"
                                {{-- href="{{route('asuransi.pembayaran-premi.create')}}"  --}}
                                class="px-6 py-2 bg-theme-primary flex gap-3 rounded text-white" id="btnRightForm">
                                    <span class="lg:mt-0 mt-0">
                                        @include('components.svg.plus')
                                    </span>
                                    <span class=""> Simpan</span>
                                </button>
                            </div>
                        {{-- </form> --}}
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('extraScript')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var form = document.getElementById('FormId');
            var btnLeft = document.getElementById('btnLeftForm');

            btnLeft.addEventListener('click', function(event) {
                event.preventDefault();
            });
        });

        $('#no_aplikasi').select2()

        $('#no_aplikasi').on('change', function() {
            const selected = $(this).val()
            var jenis_asuransi_div = $('.jenis-asuransi')

            if (selected != '') {
                $.ajax({
                    url: "{{route('asuransi.jenis_by_no_aplikasi')}}",
                    type: "GET",
                    data: {
                        'no_aplikasi': selected,
                    },
                    success: function(response) {
                        var data = response.data
                        if (data.length > 0) {
                            $('#btnLeftForm').removeClass('hidden')
                            $('.jenis-asuransi-title').removeClass('hidden')
                            jenis_asuransi_div.html('')
                            for (var i=0; i < data.length; i++) {
                                var item = data[i]
                                var checked = arr_selected_key.indexOf(item.id) > -1 ? 'checked' : ''
                                var checkbox_element = `
                                <div class="input-checked flex gap-5">
                                    <input type="checkbox" name="jenis[]" id="${item.jenis}"
                                        class="accent-theme-primary rounded h-5 w-5 jenis-asuransi-check"
                                        data-key="${item.generate_key}" data-id="${item.id}"
                                        data-jenis="${item.jenis}" data-no_aplikasi="${item.no_aplikasi}"
                                        data-premi="${item.premi}" data-no_pk="${item.no_pk}"
                                        data-no_polis="${item.no_polis}" data-no_rek="${item.no_rek}" ${checked}>
                                    <label for="${item.jenis}">${item.jenis}</label>
                                </div>`
                                jenis_asuransi_div.append(checkbox_element)
                            }
                        }
                    },
                    error: function(e) {
                        console.log(e)
                        $('#btnLeftForm').addClass('hidden')
                    }
                })
            }
            else {
                jenis_asuransi_div.html('')
                $('.jenis-asuransi-title').addClass('hidden')
                $('#btnLeftForm').addClass('hidden')
            }
        })

        $("#errorNoAplikasi").hide();
        $("#errorJenisAsuransi").hide();

        $('#form-toggle').on('click', function(){
            $('.form-selection').toggleClass('hidden');
            if($('.form-selection').hasClass('hidden')){
                $('.form-button-text').html('Tampilkan form')
            }else{
                $('.form-button-text').html('Sembunyikan form')
            }
        })

        var arr_selected_key = [];
        var temp_no = 1;
        var total_premi = 0;
        hitungTotalPremi()

        function hitungTotalPremi() {
            var format_total_premi = formatRupiah(total_premi.toString())
            $('#total_premi').val(total_premi)
            $('#display_total_premi').val(format_total_premi)
        }

        $("#btnLeftForm").on("click", function(e){
            var checked = $('input[name="jenis[]"]:checked').length;
            var no_aplikasi_val = $("#no_aplikasi").val()
            if(no_aplikasi_val == "" && checked < 1){
                e.preventDefault();

                $("#leftForm").find("#inputBoxNoAplikasi").find('select').css({"border": "2px solid red"});
                $("#leftForm").find("#inputBoxJenisAsuransi").css({"border": "2px solid red"});

                $("#errorNoAplikasi").show();
                $("#errorJenisAsuransi").show();
            } else if(no_aplikasi_val == ""){
                e.preventDefault();
                $("#leftForm").find("#inputBoxNoAplikasi").css({"border": "2px solid red"});

                $("#errorNoAplikasi").show();
            } else if(checked < 1){
                e.preventDefault();
                $("#leftForm").find("#inputBoxJenisAsuransi").css({"border": "2px solid red"});

                $("#errorJenisAsuransi").show();
            }
            else {
                $("#errorNoAplikasi").hide();
                $("#errorJenisAsuransi").hide();
                $("#leftForm").find("#inputBoxNoAplikasi").css({"border": "none"});
                $("#leftForm").find("#inputBoxJenisAsuransi").css({"border": "none"});

                var checkboxs = $('.jenis-asuransi').find('.jenis-asuransi-check');
                checkboxs.each(function(i, item) {
                    const checked = $(this).is(':checked')
                    if (checked) {
                        var generate_key = $(this).data('id');
                        var jenis = $(this).data('jenis');
                        var no_aplikasi = $(this).data('no_aplikasi');
                        var id = $(this).data('id');
                        if (arr_selected_key.includes(generate_key)) {
                            console.log('data sudah dipilih')
                            /*Swal.fire({
                                icon: 'warning',
                                title: 'Peringatan',
                                html: `Jenis asuransi <b>${jenis}</b> dengan nomor aplikasi <b>${no_aplikasi}</b> telah dipilih!`,
                            })*/
                        }
                        else {
                            var premi = $(this).data('premi');
                            var premiRupiah = '0';
                            var no_pk = $(this).data('no_pk');
                            var no_polis = $(this).data('no_polis');
                            var no_rek = $(this).data('no_rek');

                            if (premi != '') {
                                premiRupiah = premi.toString().replaceAll('.', ',')
                                premiRupiah = formatRupiah(premiRupiah.toString())
                            }

                            var row_element = `<tr>
                                <input type="hidden" name="row_key[]" class="row-key" value="${generate_key}">
                                <td>${temp_no}</td>
                                <td>${jenis}</td>
                                <td>
                                    <input type="hidden" name="row_id_no_aplikasi[]" value="${id}">
                                    <input type="hidden" name="row_no_aplikasi[]" value="${no_aplikasi}">
                                    ${no_aplikasi}
                                </td>
                                <td>
                                    <input type="hidden" name="row_premi[]" class="row-premi" value="${premi}">
                                    Rp ${premiRupiah}
                                </td>
                                <td>
                                    <input type="hidden" name="row_no_pk[]" value="${no_pk}">
                                    ${no_pk}
                                </td>
                                <td>
                                    <input type="hidden" name="row_no_polis[]" value="${no_polis}">
                                    ${no_polis}
                                </td>
                                <td>
                                    <input type="hidden" name="row_no_rek[]" value="${no_rek}">
                                    ${no_rek}
                                </td>
                                <td>
                                    <div class="input-box">
                                        <input type="number" placeholder="Input nilai disini.." class="bg-white border px-5 py-2" name="row_periode_bayar[]">
                                        <div class="errorSpan hidden" id="errorPeriodeBayar">
                                            <p id="errorText">Periode bayar harus diisi.</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="input-box">
                                        <input type="number" placeholder="Input nilai disini.." class="bg-white border px-5 py-2" name="row_total_periode_bayar[]">
                                        <div class="errorSpan hidden" id="errorTotalPeriodeBayar">
                                            <p id="errorText">Total periode bayar harus diisi.</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <button id="btn-remove-row" class="btn-remove-row">
                                        <span class="lg:mt-0 mt-0">
                                            @include('components.svg.tr-dibatalkan')
                                        </span>
                                    </button>
                                </td>
                            </tr>`;

                            $('#rightForm').append(row_element)

                            arr_selected_key.push(generate_key)
                            temp_no++;
                            total_premi += premi
                            hitungTotalPremi()
                        }
                    }
                })
            }
        })

        $("#rightForm").on("click", ".btn-remove-row", function() {
            var table = $(this).parent().parent().parent()
            var premi = $(this).parent().parent().find('.row-premi').val()
            var key = $(this).parent().parent().find('.row-key').val()
            const index = arr_selected_key.indexOf(parseInt(key));
            if (index > -1) {
                // only splice array when item is found
                arr_selected_key.splice(index, 1);
                temp_no--;
                total_premi -= premi
                hitungTotalPremi()
                $(this).closest("tr").remove();
                resetNoSequence(table)
            }
        });

        function resetNoSequence(table) {
            var tr = table.find('tr')
            var table = document.getElementById('rightForm');

            var rowLength = table.rows.length;

            for(var i=0; i<rowLength; i+=1){
                var row = table.rows[i];
                var cell = row.cells[0];
                cell.innerText = i+1
            }
        }

        $("#btnRightForm").on("click", function(e){
            var total_input_null = 0;
            var no_bukti_pembayaran = $('#no_bukti_pembayaran').val()
            if(no_bukti_pembayaran == ''){
                e.preventDefault();
                total_input_null++;
                $(`#no_bukti_pembayaran`).addClass('border-2 border-rose-600')
                $(`.no-bukti-pembayaran-error`).html('Nomor aplikasi tidak boleh kosong')
            } else {
                $(`#no_bukti_pembayaran`).removeClass('border-2 border-rose-600')
                $(`.no-bukti-pembayaran-error`).html('')
            }

            var tgl_bayar = $('#tgl_bayar').val()
            if (tgl_bayar != 'dd/mm/yyyy') {
                $(`#tgl_bayar`).removeClass('border-2 border-rose-600')
                $(`.tgl-bayar-error`).html('')
            }
            else {
                total_input_null++;
                $(`#tgl_bayar`).addClass('border-2 border-rose-600')
                $(`.tgl-bayar-error`).html('Harap pilih tanggal bayar')
            }

            $("#rightForm tr").each(function(){
                if($(this).find('input[name="no_rekening[]"]').val() == ''){
                    total_input_null++;
                    e.preventDefault();
                    $(this).find("#errorNoRekening").show();
                    $(this).find('input[name="no_rekening[]"]').css({"border": "2px solid red"});
                } else {
                    $(this).find("#errorNoRekening").hide();
                }
                if($(this).find('input[name="row_nobukti_pembayaran[]"]').val() == ''){
                    total_input_null++;
                    e.preventDefault();
                    $(this).find("#errorNobuktiPembayaran").show();
                    $(this).find('input[name="row_nobukti_pembayaran[]"]').css({"border": "2px solid red"});
                } else {
                    $(this).find("#errorPeriodeBayar").hide();
                }
                if($(this).find('input[name="row_periode_bayar[]"]').val() == ''){
                    total_input_null++;
                    e.preventDefault();
                    $(this).find("#errorPeriodeBayar").show();
                    $(this).find('input[name="row_periode_bayar[]"]').css({"border": "2px solid red"});
                } else {
                    $(this).find("#errorPeriodeBayar").hide();
                }
                if($(this).find('input[name="row_total_periode_bayar[]"]').val() == ''){
                    total_input_null++;
                    e.preventDefault();
                    $(this).find('input[name="row_total_periode_bayar[]"]').css({"border": "2px solid red"});
                    $(this).find("#errorTotalPeriodeBayar").show();
                } else {
                    $(this).find("#errorTotalPeriodeBayar").hide();
                }
            })

            if (total_input_null == 0) {
                $("#preload-data").removeClass("hidden");
            }
        })

            // var data = {
            //     "nobukti_pembayaran": "0002",
            //     "tgl_bayar": "2017-08-07",
            //     "total_premi": 472500,
            //     "rincian_bayar": [
            //         {
            //             "premi": 472500,
            //             "no_rek": "10060572000001",
            //             "no_aplikasi": "BWj5FFUZfG",
            //             "no_pk": "PK\/0085\/73\/SH\/0817-0820",
            //             "no_polis": "045912120817012100",
            //             "periode_bayar": "1",
            //             "total_periode": "10"
            //         }
            //     ]
            // }
        $('#btn-simpan').on('click', function(){
            // $.ajax({
            //     url: 'http://sandbox-umkm.ekalloyd.id:8387/bayar',
            //     type: 'POST',
            //     headers: {
            //         'Content-Type': 'application/json',
            //         'X-API-Key': 'elj-bprjatim-123',
            //         'Accept': 'application/json'
            //     },
            //     data: JSON.stringify(data),
            //     success: function(response) {
            //         console.log('Berhasil', response);
            //     },
            //     error: function(error) {
            //         console.error('Gagal', error);
            //     }
            // });
        });

        function alertError(message) {
            Swal.fire({
                title: 'Error',
                html: message,
                icon: 'error',
                iconColor: '#DC3545',
                confirmButtonText: 'OK',
                confirmButtonColor: '#DC3545'
            })
        }
    </script>
@endpush
