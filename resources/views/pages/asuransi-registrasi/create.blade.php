@extends('layout.master')
@section('modal')
    @include('pages.asuransi-registrasi.modal.loading')
@endsection
@section('content')
    <div class="head-pages">
        <p class="text-sm">Asuransi</p>
        <h2 class="text-2xl font-bold text-theme-primary tracking-tighter">
            Tambah Registrasi Asuransi
        </h2>
    </div>
    <div class="body-pages">
        <div class="bg-white w-full p-5">
            <form id="form-asuransi-registrasi" action="{{route('asuransi.registrasi.store')}}" method="post" class="space-y-5 " accept="">
                @csrf
                <div class="lg:grid-cols-3 md:grid-cols-2 grid-cols-1 grid gap-5 justify-center">
                    <div class="input-box space-y-3">
                        <label class="uppercase">Pilih Data Pengajuan</label>
                        <select name="pengajuan" id="pengajuan" class="w-full p-2 border">
                            <option selected>-- Pilih Data Pengajuan ---</option>
                            @forelse ($dataPengajuan as $key => $item)
                                <option value="{{$item['id']}}" data-key="{{$key}}">{{$item['nama']}}, {{$item['tanggal_lahir']}} </option>
                            @empty
                                <option>Data Pengajuan Belum Ada.</option>
                            @endforelse
                        </select>
                    </div>
                </div>
                <div class="title-form">
                    <h2 class="text-theme-primary font-bold text-lg">Data Debitur</h2>
                </div>

                    {{-- form data debitur 1 --}}
                <div class="lg:grid-cols-3 md:grid-cols-2 grid-cols-1 grid gap-5 justify-center">
                    <div class="input-box space-y-3">
                        <label for="" class="uppercase">Nama<span class="text-theme-primary">*</span></label>
                        <input type="text" class="disabled-input bg-disabled p-2 w-full border" id="" name="nama_debitur" readonly/>
                        <small class="form-text text-red-600 error"></small>
                    </div>
                    <div class="input-box-calendar space-y-3">
                        <label for="" class="uppercase">Tanggal lahir<span class="text-theme-primary">*</span></label>
                        <input type="text" class="disabled-input bg-disabled  p-2 w-full border" id="tgl_lahir" name="tgl_lahir" readonly/>
                        <small class="form-text text-red-600 error"></small>
                    </div>
                    <div class="input-box space-y-3">
                        <label for="" class="uppercase">Alamat<span class="text-theme-primary">*</span></label>
                        <input type="text" class="disabled-input bg-disabled p-2 w-full border " id="" name="alamat_debitur" readonly/>
                        <small class="form-text text-red-600 error"></small>
                    </div>
                </div>
                {{-- form data debitur 2 --}}
                <div class="lg:grid-cols-3 md:grid-cols-2 grid-cols-1 grid gap-5 justify-center">
                    <div class="input-box space-y-3">
                        <label for="" class="uppercase">No KTP<span class="text-theme-primary">*</span></label>
                        <input type="text" class="disabled-input bg-disabled p-2 w-full border " id="" name="no_ktp" readonly/>
                        <small class="form-text text-red-600 error"></small>
                    </div>
                    <div class="input-box space-y-3">
                        <label for="" class="uppercase">No Aplikasi<span class="text-theme-primary">*</span></label>
                        <input type="text" class="p-2 w-full border disabled-input bg-disabled " id="no_aplikasi" name="no_aplikasi" readonly/>
                        <small class="form-text text-red-600 error"></small>
                    </div>
                    <div class="input-box space-y-3">
                        <label for="" class="uppercase">Kode Cabang Bank<span class="text-theme-primary">*</span></label>
                        <input type="text" class="disabled-input bg-disabled p-2 w-full border " id="" name="kode_cabang" readonly/>
                        <small class="form-text text-red-600 error"></small>
                    </div>
                </div>
                <div class="lg:grid-cols-3 md:grid-cols-2 grid-cols-1 grid gap-5 justify-center">
                    <div class="input-box-calendar space-y-3">
                        <label for="" class="uppercase">Tanggal Awal Kredit<span class="text-theme-primary">*</span></label>
                        <input type="text" name="tanggal_awal_kredit" id="tanggal_awal_kredit" class="disabled-input bg-disabled p-2 w-full border" readonly/>
                        <small class="form-text text-red-600 error"></small>
                    </div>
                    <div class="input-box-calendar space-y-3">
                        <label for="" class="uppercase">Tanggal Akhir Kredit<span class="text-theme-primary">*</span></label>
                        <input type="text" name="tanggal_akhir_kredit" id="tanggal_akhir_kredit" class="disabled-input bg-disabled p-2 w-full border" readonly/>
                        <small class="form-text text-red-600 error"></small>
                    </div>
                    <div class="input-box-calendar space-y-3">
                        <label for="" class="uppercase">Tanggal Jatuh Tempo<span class="text-theme-primary">*</span></label>
                        <input type="text" name="tgl_jatuhtempo" id="tgl_jatuhtempo" class="disabled-input bg-disabled  p-2 w-full border" readonly/>
                        <small class="form-text text-red-600 error"></small>
                    </div>
                    <div class="input-box space-y-3">
                        <label for="" class="uppercase">Jumlah Bulan<span class="text-theme-primary">*</span></label>
                        <input type="number" class="disabled-input bg-disabled p-2 w-full border " id="jumlah_bulan" name="jumlah_bulan" readonly/>
                        <small class="form-text text-red-600 error"></small>
                    </div>
                    <div class="input-box space-y-3">
                        <label for="add-role" class="uppercase">Jenis Kredit<span class="text-theme-primary">*</span> </label>
                        <input type="text" class="disabled-input bg-disabled p-2 w-full border" id="" name="jenis_kredit" readonly/>
                    </div>
                    <div class="input-box space-y-3">
                        <label for="" class="uppercase">No PK<span class="text-theme-primary">*</span></label>
                        <input type="text" class="disabled-input bg-disabled p-2 w-full border " id="no_pk" name="no_pk" readonly/>
                        <small class="form-text text-red-600 error"></small>
                    </div>
                    <div class="input-box-calendar space-y-3">
                        <label for="" class="uppercase">Tanggal PK<span class="text-theme-primary">*</span></label>
                        <input type="text" class="disabled-input bg-disabled p-2 w-full border" name="tgl_pk" readonly/>
                        <small class="form-text text-red-600 error"></small>
                    </div>
                    <div class="input-box space-y-3">
                        <label for="" class="uppercase">Tanggal Pengajuan<span class="text-theme-primary">*</span></label>
                        <input type="text" class="disabled-input bg-disabled p-2 w-full border" name="tgl_pengajuan" readonly/>
                        <small class="form-text text-red-600 error"></small>
                    </div>
                    <div class="input-box space-y-3">
                        <label for="" class="uppercase">Plafon Kredit</label>
                        <input type="text" class="disabled-input bg-disabled p-2 w-full border " id="plafon_kredit" name="plafon_kredit" readonly/>
                        <small class="form-text text-red-600 error"></small>
                    </div>
                </div>
                <div class="title-form">
                    <h2 class="text-theme-primary font-bold text-lg">Data Registrasi</h2>
                </div>
                {{-- form data register 1 --}}
                <div class="lg:grid-cols-3 md:grid-cols-2 grid-cols-1 grid gap-5 justify-center">
                    <div class="input-box space-y-3">
                        <label for="add-role" class="uppercase">No Rekening<span class="text-theme-primary">*</span> </label>
                        <input type="text" class="p-2 w-full border " id="no_rekening" name="no_rekening"/>
                        <small class="form-text text-red-600 error"></small>
                    </div>
                    <div class="input-box space-y-3">
                        <label for="add-role" class="uppercase">Jenis Asuransi<span class="text-theme-primary">*</span> </label>
                        <select name="jenis_asuransi" class="w-full p-2 border" id="jenis_asuransi">
                            <option selected value="">-- Pilih Jenis Asuransi ---</option>
                            <option value="3">Jiwa</option>
                            <option value="2">Jaminan</option>
                        </select>
                    </div>
                    <div class="input-box space-y-3">
                        <label for="" class="uppercase">Jenis Pengajuan<span class="text-theme-primary">*</span> </label>
                        <select name="jenis_pengajuan" class="jenis-pengajuan w-full p-2 border">
                            <option selected value="">-- Pilih Jenis Pengajuan ---</option>
                            <option value="00">Baru</option>
                            <option value="01">Top Up</option>
                        </select>
                    </div>
                    <div class="input-box space-y-3">
                        <label for="" class="uppercase">Kolektibilitas<span class="text-theme-primary">*</span></label>
                        <select name="kolektibilitas" class="w-full p-2 border">
                            <option selected value="">-- Kolektibilitas ---</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                        <small class="form-text text-red-600 error"></small>
                    </div>
                    <div class="input-box space-y-3">
                        <label for="add-role" class="uppercase">Jenis PERTANGGUNGAN<span class="text-theme-primary">*</span> </label>
                        <select name="jenis_pertanggungan" id="jenis_pertanggungan" class="w-full p-2 border">
                            <option selected value="">-- Pilih Jenis Pertanggungan ---</option>
                            <option value="01">Berdasarkan Pokok</option>
                            <option value="02">Berdasarkan Sisa Kredit</option>
                        </select>
                    </div>
                    <div class="input-box space-y-3">
                        <label for="" class="uppercase">Tipe Premi<span class="text-theme-primary">*</span> </label>
                        <select name="tipe_premi" class="w-full p-2 border">
                            <option selected value="">-- Pilih Tipe Premi ---</option>
                            <option value="0">Biasa</option>
                            <option value="1">Refund</option>
                        </select>
                    </div>
                </div>

                {{-- form data register 6 should be hidden when choosing baru in jenis pengajuan --}}
                <div class="form-6 hidden lg:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-5 justify-center">
                    <div class="input-box space-y-3">
                        <label for="add-role" class="uppercase">No Polis Sebelumya<span class="text-theme-primary">*</span> </label>
                        <input type="text" class="p-2 w-full border " id="" name="no_polis_sebelumnya"/>
                        <small class="form-text text-red-600 error"></small>
                    </div>
                    <div class="input-box space-y-3">
                        <label for="" class="uppercase">Baki Debet<span class="text-theme-primary">*</span></label>
                        <input type="text" class="rupiah p-2 w-full border " id="" name="baki_debet"/>
                        <small class="form-text text-red-600 error"></small>
                    </div>
                    <div class="input-box space-y-3">
                        <label for="" class="uppercase">Tunggakan<span class="text-theme-primary">*</span></label>
                        <input type="text" class="rupiah p-2 w-full border " id="tunggakan" name="tunggakan"/>
                        <small class="form-text text-red-600 error"></small>
                    </div>
                </div>
                {{-- form data register 5 --}}
                <div class="lg:grid-cols-3 md:grid-cols-2 grid-cols-1 grid gap-5 justify-center">
                    <div class="input-box space-y-3">
                        <label for="" class="uppercase">Premi</label>
                        <input type="hidden" id="rate_premi" name="rate_premi"/>
                        <input type="text" class="rupiah p-2 w-full border disabled-input bg-disabled" id="premi" name="premi" readonly/>
                        <small class="form-text text-red-600 error"></small>
                    </div>
                    <div class="input-box space-y-3">
                        <label for="" class="uppercase">Jenis Coverage<span class="text-theme-primary">*</span> </label>
                        <select name="jenis_coverage" class="w-full p-2 border">
                            <option selected value="">-- Pilih jenis ---</option>
                        </select>
                    </div>
                    <div class="input-box space-y-3">
                        <label for="" class="uppercase">Tarif<span class="text-theme-primary">*</span></label>
                        <input type="text" class="disabled-input bg-disabled p-2 w-full border " id="tarif" name="tarif" readonly/>
                        <small class="form-text text-red-600 error"></small>
                    </div>
                    <div class="input-box space-y-3 form-6 hidden">
                        <label for="" class="uppercase">Refund<span class="text-theme-primary">*</span></label>
                        <input type="text" class="rupiah p-2 w-full border" id="refund" name="refund"
                            onchange="hitungPremiDisetor()"/>
                        <small class="form-text text-red-600 error"></small>
                    </div>
                    <div class="input-box space-y-3">
                        <label for="" class="uppercase">Kode Layanan Syariah</label>
                        <select name="kode_ls" class="w-full p-2 border">
                            <option selected value="">-- Kode Layanan Syariah ---</option>
                            <option value="0">KV</option>
                            <option value="1">SY</option>
                        </select>
                    </div>
                    <div class="input-box space-y-3">
                        <label for="" class="uppercase">Handling Fee<span class="text-theme-primary">*</span></label>
                        <input type="text" class="rupiah p-2 w-full border " id="handling_fee" name="handling_fee"
                            onchange="hitungPremiDisetor()"/>
                        <small class="form-text text-red-600 error"></small>
                    </div>
                    <div class="input-box space-y-3">
                        <label for="" class="uppercase">Premi Disetor<span class="text-theme-primary">*</span></label>
                        <input type="text" class="disabled-input bg-disabled p-2 w-full border " id="premi_disetor" name="premi_disetor" readonly/>
                        <small class="form-text text-red-600 error"></small>
                    </div>
                </div>

                <div class="flex gap-5">
                    <button class="px-6 py-2 bg-theme-primary flex gap-3 rounded text-white" type="submit" id="simpan">
                        <span class="lg:mt-0 mt-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 24 24">
                                <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2" d="M5 12h14m-7-7v14" />
                            </svg>
                        </span>
                        <span class="lg:block hidden"> Simpan </span>
                    </button>
                <button type="button"
                    id="form-reset"
                    class="px-6 py-2 bg-theme-primary/10 flex gap-3 rounded text-theme-primary">
                    <span class="lg:mt-1.5 mt-0">
                        @include('components.svg.reset')
                    </span>
                    <span class="lg:block hidden"> Reset </span>
                </button>
            </div>
            </div>
            </form>
        </div>
@endsection

@push('extraScript')
<script src="{{ asset('template/assets/js/axios.min.js') }}"></script>
<script>
    var urlPost = "http://sandbox-umkm.ekalloyd.id:8387";

    $('#pengajuan').select2();

    $('#form-reset').on('click', function(){
        $('#form-asuransi-registrasi')[0].reset();
        if($('#form-asuransi-registrasi .datepicker')[0]){
            $('.datepicker').val('dd/mm/yyyy');
        }
    })

    $('#form-asuransi-registrasi .jenis-pengajuan').on('change', function(){
        if(parseInt($('.jenis-pengajuan').val()) === 1){
            $('.form-6').removeClass('hidden')
            $('.form-7').removeClass('hidden')
            $('.form-6').addClass('grid')
            $('.form-7').addClass('grid')
        }else{
            $('.form-6').removeClass('grid')
            $('.form-7').removeClass('grid')
            $('.form-6').addClass('hidden')
            $('.form-7').addClass('hidden')

        }
    })

    $('#pengajuan').on('change', function(){
        var key = $(this).children("option:selected").data('key');
        var data = @json($dataPengajuan);
        var tanggalLahir = new Date(data[key]['tanggal_lahir']);
        var tempMonth = (tanggalLahir.getMonth() + 1).toString().length == 1 ? `0${tanggalLahir.getMonth() + 1}` : tanggalLahir.getMonth() + 1;
        var fullTanggalLahir = `${tanggalLahir.getDate()}-${tempMonth}-${tanggalLahir.getFullYear()}`
        var tanggalPK = new Date(data[key]['tgl_cetak_pk']);
        var tempMonth = (tanggalPK.getMonth() + 1).toString().length == 1 ? `0${tanggalPK.getMonth() + 1}` : tanggalPK.getMonth() + 1;
        var fullTanggalPK = `${tanggalPK.getDate()}-${tempMonth}-${tanggalPK.getFullYear()}`
        var tempTanggalAwalKredit = new Date(data[key]['tanggal']);
        var tanggalAwalKredit = new Date(data[key]['tanggal']);
        tempMonth = (tanggalAwalKredit.getMonth() + 1).toString().length == 1 ? `0${tanggalAwalKredit.getMonth() + 1}` : tanggalAwalKredit.getMonth() + 1;
        var fullTanggalAwalKredit = `${tanggalAwalKredit.getDate()}-${tempMonth}-${tanggalAwalKredit.getFullYear()}`;
        var tenor = parseInt(data[key]['tenor_yang_diminta']);
        var tanggalAkhirKredit = new Date(tempTanggalAwalKredit.setMonth(tempTanggalAwalKredit.getMonth()+tenor));
        tempMonth = (tanggalAkhirKredit.getMonth() + 1).toString().length == 1 ? `0${tanggalAkhirKredit.getMonth() + 1}` : tanggalAkhirKredit.getMonth() + 1;
        var fullTanggalAkhirKredit = `${tanggalAkhirKredit.getDate()}-${tempMonth}-${tanggalAkhirKredit.getFullYear()}`;

        var month_diff = Date.now() - tanggalLahir.getTime();
        var age_dt = new Date(month_diff);
        let date = new Date()
        let tahunSekarang = date.getUTCFullYear();
        var year = age_dt.getUTCFullYear();
        var noAplikasi = randomString(10);

        var age = Math.abs(year - tahunSekarang);

        var jumlahKredit = formatRupiah(data[key]['jumlah_kredit'])

        $('[name="nama_debitur"]').val(data[key]['nama'])
        $('[name="tgl_lahir"]').val(fullTanggalLahir)
        $('[name="alamat_debitur"]').val(data[key]['alamat_rumah'])
        $('[name="no_ktp"]').val(data[key]['no_ktp'])
        $('[name="kode_cabang"]').val(data[key]['kode_cabang'])
        $('[name="jenis_kredit"]').val(data[key]['skema_kredit'])
        $("[name='tgl_pengajuan']").val(fullTanggalAwalKredit)
        $("[name='tanggal_awal_kredit']").val(fullTanggalAwalKredit)
        $("[name='tanggal_akhir_kredit']").val(fullTanggalAkhirKredit)
        $("[name='tgl_jatuhtempo']").val(fullTanggalAkhirKredit)
        $("[name='jumlah_bulan']").val(tenor)
        $("[name='no_aplikasi']").val(noAplikasi)
        $("[name='plafon_kredit']").val(jumlahKredit)
        $("[name='no_pk']").val(data[key]['no_pk'])
        $("[name='tgl_pk']").val(data[key]['tgl_cetak_pk'])
        if (age <= 60 ) {
            $('[name="jenis_coverage"]').append(`
            <option value="01">PNS & NON PNS (PA+ND)</option>
            <option value="02">NON PNS (PA+ND+PHK)</option>
            <option value="03">PNS (PA+ND+PHK+MACET)</option>
            <option value="04">DPRD (PA+ND+PAW)</option>
            `)
        } else {
            $('[name="jenis_coverage"]').append(`
            <option value="05">PNS & PENSIUN (PA+ND)</option>
            <option value="06">DPRD (PA+ND+PAW)</option>
            `)
        }

        /*$.ajax({
            type: "GET",
            url: "{{ url('/asuransi/registrasi/jenis-asuransi') }}/"+data[key]['skema_kredit'],
            success: function(data) {
                $('#jenis_asuransi').empty()
                $('#jenis_asuransi').append(`<option selected value="">-- Pilih Jenis Asuransi ---</option>`)
                $.each(data.data, function (i, item) {
                    $('#jenis_asuransi').append(`<option value="${item.jenis}">${item.jenis}</option>`);
                });
            },
            error: function(e) {
                console.log(e)
            }
        })*/

    })

    $('#jenis_pertanggungan').on('change', function() {
        var masa_asuransi = $('#jumlah_bulan').val()
        if (masa_asuransi == '') {
            alert('Harap pilih data pengajuan terlebih dahulu')
        }
        var plafon_kredit = $('#plafon_kredit').val()
        if (plafon_kredit != '') {
            plafon_kredit = plafon_kredit.replaceAll('.', '')
            plafon_kredit = parseInt(plafon_kredit)
        }
        let premi;
        var jenis = $(this).val()
        if (jenis == '01')
            jenis = 'plafon'
        else if (jenis == '02')
            jenis = 'bade'
        else
            jenis = '';


        if (jenis != '') {
            $.ajax({
                url: "{{route('asuransi.registrasi.rate_premi')}}",
                type: "GET",
                accept: "Application/json",
                data: {
                    'jenis': jenis,
                    'masa_asuransi': masa_asuransi,
                },
                success: function(response){
                    if (response.status == 'success') {
                        if (response.data) {
                            var rate = parseFloat(response.data.rate)
                            premi = Math.round(plafon_kredit * (rate / 1000))
                            premi = formatRupiah(premi.toString())
                            $('#premi').val(premi)
                            $('#rate_premi').val(rate)
                            $('#tarif').val(rate)
                            hitungPremiDisetor()
                        }
                        else {
                            alert('Rate premi tidak ditemukan')
                        }
                    }
                    else {
                        console.log(response.message)
                        alert('Terjadi  kesalahan saat mengambil rate premi')
                    }
                },
                error: function(response){
                    console.log(response)
                    alert('Terjadi kesalahan saat mengambil rate premi')
                }
            })
        }
    })

    $("#simpan").on("click", function(){
        $("#preload-data").removeClass("hidden");
        /*var d = {"no_aplikasi":"SO2sBojfZN","jenis_asuransi":"Jaminan","tgl_pengajuan":"15-09-2023","kd_uker":"027","nama_debitur":"Riski Ridho","alamat_debitur":"Situbondo, mlandingan, campoan rt4","tgl_lahir":"31-05-1998","no_ktp":"3511145755975784","no_pk":"SBY/PK/001/09/2023","tgl_pk":"15-09-2023","plafon_kredit":"40.000.000","tgl_awal_kredit":"15-09-2023","tgl_akhir_kredit":"15-09-2025","jml_bulan":"24","jenis_pengajuan":"0","bade":"","tunggakan":"","kolektibilitas":"1","no_polis_sebelumnya":"","jenis_pertanggungan":"01","tipe_premi":"0","premi":"3.120.000","jenis_coverage":"01","tarif":"200.000","refund":"","kode_ls":"0","jenis_kredit":"Kusuma","handling_fee":"200.000","premi_disetor":"2.920.000"}

        /*axios.post(`${urlPost}/upload`, d, {
            mode: 'no-cors',
            withCredentials: false,
            headers: {
                "Accept": "application/json",
                "x-api-key": "elj-bprjatim-123",
                "Content-Type": "application/json",
                "Access-Control-Allow-Origin": '*',
                "Access-Control-Allow-Methods": "*",
                //"Access-Control-Allow-Headers": "x-requested-with",
                "Access-Control-Allow-Credentials": true,
                "Access-Control-Allow-Headers": "*",
            }
        })
        .then(function (response) {
            console.log(response);
        })
        .catch(function (error) {
            console.log(error);
        });

        $.ajax({
            url: urlPost + "/upload",
            method: "POST",
            headers: {
                "Access-Control-Allow-Credentials": false,
                "Access-Control-Allow-Headers": "*",
                "Access-Control-Allow-Methods": "*",
                "Access-Control-Allow-Origin": "*",
                "Content-Type": "application/json",
                "x-api-key": "elj-bprjatim-123",
            },
            data: d,
            crossDomain: true,
            success: function(response){
                console.log(response)
            },
            error: function(response){
                console.log(response)
            }
        })*/
        /*
        var data  = {};
        data['no_aplikasi'] = $("[name='no_aplikasi']").val()
        data['jenis_asuransi'] = $("[name='jenis_asuransi']").val()
        data['tgl_pengajuan'] = $("[name='tgl_pengajuan']").val()
        data['kd_uker'] = $("[name='kode_cabang']").val()
        data['nama_debitur'] = $("[name='nama_debitur']").val()
        data['alamat_debitur'] = $("[name='alamat_debitur']").val()
        data['tgl_lahir'] = $("[name='tgl_lahir']").val()
        data['no_ktp'] = $("[name='no_ktp']").val()
        data['no_pk'] = $("[name='no_pk']").val()
        data['tgl_pk'] = $("[name='tgl_pk']").val()
        data['plafon_kredit'] = $("[name='plafon_kredit']").val()
        data['tgl_awal_kredit'] = $("[name='tanggal_awal_kredit']").val()
        data['tgl_akhir_kredit'] = $("[name='tanggal_akhir_kredit']").val()
        data['jml_bulan'] = $("[name='jumlah_bulan']").val()
        data['jenis_pengajuan'] = $("[name='jenis_pengajuan']").val()
        data['bade'] = $("[name='baki_debet']").val()
        data['tunggakan'] = $("[name='tunggakan']").val()
        data['kolektibilitas'] = $("[name='kolektibilitas']").val()
        data['no_polis_sebelumnya'] = $("[name='no_polis_sebelumnya']").val()
        data['jenis_pertanggungan'] = $("[name='jenis_pertanggungan']").val()
        data['tipe_premi'] = $("[name='tipe_premi']").val()
        data['premi'] = $("[name='premi']").val()
        data['jenis_coverage'] = $("[name='jenis_coverage']").val()
        data['tarif'] = $("[name='tarif']").val()
        data['refund'] = $("[name='refund']").val()
        data['kode_ls'] = $("[name='kode_ls']").val()
        data['jenis_kredit'] = $("[name='jenis_kredit']").val()
        data['handling_fee'] = $("[name='handling_fee']").val()
        data['premi_disetor'] = $("[name='premi_disetor']").val()

       if (data['handling_fee'] === '') {
        var message = 'Handling Fee Belum Di Isi.'
        alertWarning(message)
       }
       if (data['kode_ls'] === '') {
        var message = 'Kode Layanan Syariah Belum Di Isi.'
        alertWarning(message)
       }
       if (data['tarif'] === '') {
        var message = 'Tarif Belum Di Isi.'
        alertWarning(message)
       }
       if (data['jenis_coverage'] === '') {
        var message = 'Jenis Coverage Belum Di Pilih.'
        alertWarning(message)
       }
       if (data['tipe_premi'] === '') {
        var message = 'Tipe Premi Belum Di Pilih.'
        alertWarning(message)
       }
       if (data['jenis_pertanggungan'] === '') {
        var message = 'Jenis Pertanggungan Belum Di Pilih.'
        alertWarning(message)
       }

       if (data['jenis_pengajuan'] === '1') {
            if (data['refund'] === '') {
                var message = 'Refund Belum Di Isi.'
                alertWarning(message)
            }
           if (data['tunggakan'] === '') {
               var message = 'Tunggakan Belum Di Isi.'
               alertWarning(message)
            }
            if (data['bade'] === '') {
                var message = 'Baki Debet Belum Di Isi.'
                alertWarning(message)
            }
            if (data['no_polis_sebelumnya'] === '') {
                var message = 'No Polis Sebelumnya Belum Di Isi.'
                alertWarning(message)
            }
       }

       if (data['kolektibilitas'] === '') {
        var message = 'Kolektibilitas Belum Di Isi.'
        alertWarning(message)
       }
       if (data['jenis_pengajuan'] === '') {
        var message = 'Jenis Pengajuan Belum Di Pilih.'
        alertWarning(message)
       }

       if (data['jenis_asuransi'] == '') {
        var message = 'Jenis Asurnasi Belum Di Pilih.'
        alertWarning(message)
       }
       if (data['nama_debitur'] === '') {
        var message = 'Data Pengajuan Belum Di Pilih.'
        alertWarning(message)
       }

       console.log(data)
       console.log(JSON.stringify(data))
       console.log(typeof(data))
       console.log(typeof(JSON.stringify(data)))

        $.ajax({
            url: urlPost + "/upload",
            method: "POST",
            accept: "Application/json",
            headers: {
                "Accept": "/",
                "x-api-key": "elj-bprjatim-123",
                "Content-Type": "application/json",
                "Access-Control-Allow-Origin": "*",
                "Access-Control-Allow-Methods": "*",
                "Access-Control-Allow-Headers": "x-requested-with",
                "Access-Control-Allow-Credentials": false,
                "Access-Control-Allow-Headers": "Content-Type"
            },
            data: d,
            crossDomain: true,
            success: function(response){
                console.log(response)
            },
            error: function(response){
                console.log(response)
            }
        })*/
    });

    function hitungPremiDisetor() {
        //Nilai premi disetor (Premi -(Refund+Handling Fee))
        var premi = $('#premi').val()
        if (premi) {
            premi = premi.replaceAll('.', '')
            premi =  parseInt(premi)
        }
        var refund = $('#refund').val()
        if (refund) {
            refund = refund.replaceAll('.', '')
            refund =  parseInt(refund)
        }
        var handling_fee = $('#handling_fee').val()
        if (handling_fee) {
            handling_fee = handling_fee.replaceAll('.', '')
            handling_fee =  parseInt(handling_fee)
        }

        var premi_disetor = Math.round(premi - (refund + handling_fee))
        var format_premi_disetor = formatRupiah(premi_disetor.toString())
        $('#premi_disetor').val(format_premi_disetor)
    }

    function randomString(length) {
        let result = '';
        const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        const charactersLength = characters.length;
        let counter = 0;
        while (counter < length) {
        result += characters.charAt(Math.floor(Math.random() * charactersLength));
        counter += 1;
        }
        return result;
    }

    function validationData(){

    }

    function alertWarning(message){
        Swal.fire({
            tittle : 'Warning!',
            html : message,
            icon : 'warning',
            iconColor : '#DC3545',
            confirmButtonText : 'Ya',
            confirmButtonColor : '#DC3545'
        })
    }

</script>
@endpush
