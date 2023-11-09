@extends('layout.master')
@section('modal')
    @include('pages.asuransi-registrasi.modal.loading')
@endsection
@section('content')
    <div class="head-pages">
        <p class="text-sm">Asuransi</p>
        <h2 class="text-2xl font-bold text-theme-primary tracking-tighter">
            Registrasi
        </h2>
    </div>
    <div class="body-pages">
        <div class="bg-white w-full p-5">
            <form id="form-asuransi-registrasi" action="{{ route('asuransi.registrasi.store') }}" method="post"
                class="space-y-5 " accept="">
                @csrf
                <input type="hidden" name="pengajuan" value="{{$_GET['id']}}">
                <div class="lg:grid-cols-3 md:grid-cols-2 grid-cols-1 grid gap-5 justify-center">
                    <div class="input-box space-y-3">
                        <label class="uppercase">Pilih Perusahaan Asuransi</label>
                        <select name="perusahaan" id="perusahaan" class="w-full p-2 border" required>
                            <option selected>-- Pilih ---</option>
                            @foreach ($perusahaan as $key => $item)
                                <option value="{{ $item->id }}" data-key="{{ $key }}"
                                    @if (old('perusahaan') == $item->id) selected @endif>
                                    {{ $item->nama }} </option>
                            @endforeach
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
                        <input type="text" class="disabled-input bg-disabled p-2 w-full border" id=""
                            name="nama_debitur" value="{{$pengajuan['nama']}}" readonly />
                        <small class="form-text text-red-600 error"></small>
                    </div>
                    <div class="input-box-calendar space-y-3">
                        <label for="" class="uppercase">Tanggal lahir<span
                                class="text-theme-primary">*</span></label>
                        <input type="text" class="disabled-input bg-disabled  p-2 w-full border" id="tgl_lahir"
                            name="tgl_lahir" value="{{date('d-m-Y', strtotime($pengajuan['tanggal_lahir']))}}" readonly />
                        <small class="form-text text-red-600 error"></small>
                    </div>
                    <div class="input-box space-y-3">
                        <label for="" class="uppercase">Alamat<span class="text-theme-primary">*</span></label>
                        <input type="text" class="disabled-input bg-disabled p-2 w-full border " id=""
                            name="alamat_debitur" value="{{$pengajuan['alamat_rumah']}}" readonly />
                        <small class="form-text text-red-600 error"></small>
                    </div>
                </div>
                {{-- form data debitur 2 --}}
                <div class="lg:grid-cols-3 md:grid-cols-2 grid-cols-1 grid gap-5 justify-center">
                    <div class="input-box space-y-3">
                        <label for="" class="uppercase">No KTP<span class="text-theme-primary">*</span></label>
                        <input type="text" class="disabled-input bg-disabled p-2 w-full border " id=""
                            name="no_ktp" value="{{$pengajuan['no_ktp']}}" readonly />
                        <small class="form-text text-red-600 error"></small>
                    </div>
                    <div class="input-box space-y-3">
                        <label for="" class="uppercase">No Aplikasi<span class="text-theme-primary">*</span></label>
                        <input type="text" class="p-2 w-full border disabled-input bg-disabled " id="no_aplikasi"
                            name="no_aplikasi" value="{{$pengajuan['no_aplikasi']}}" readonly />
                        <small class="form-text text-red-600 error"></small>
                    </div>
                    <div class="input-box space-y-3">
                        <label for="" class="uppercase">Kode Cabang Bank<span
                                class="text-theme-primary">*</span></label>
                        <input type="text" class="disabled-input bg-disabled p-2 w-full border " id=""
                            name="kode_cabang" value="{{$pengajuan['kode_cabang']}}" readonly />
                        <small class="form-text text-red-600 error"></small>
                    </div>
                </div>
                <div class="lg:grid-cols-3 md:grid-cols-2 grid-cols-1 grid gap-5 justify-center">
                    <div class="input-box-calendar space-y-3">
                        <label for="" class="uppercase">Tanggal Awal Kredit<span
                                class="text-theme-primary">*</span></label>
                        <input type="text" name="tanggal_awal_kredit" id="tanggal_awal_kredit"
                            class="disabled-input bg-disabled p-2 w-full border" value="{{date('d-m-Y', strtotime($pengajuan['tanggal']))}}" readonly />
                        <small class="form-text text-red-600 error"></small>
                    </div>
                    <div class="input-box-calendar space-y-3">
                        <label for="" class="uppercase">Tanggal Akhir Kredit<span
                                class="text-theme-primary">*</span></label>
                        <input type="text" name="tanggal_akhir_kredit" id="tanggal_akhir_kredit"
                            class="disabled-input bg-disabled p-2 w-full border" value="{{date('d-m-Y', strtotime($pengajuan['tgl_akhir_kredit']))}}" readonly />
                        <small class="form-text text-red-600 error"></small>
                    </div>
                    <div class="input-box-calendar space-y-3">
                        <label for="" class="uppercase">Tanggal Jatuh Tempo<span
                                class="text-theme-primary">*</span></label>
                        <input type="text" name="tgl_jatuhtempo" id="tgl_jatuhtempo"
                            class="disabled-input bg-disabled  p-2 w-full border" value="{{date('d-m-Y', strtotime($pengajuan['tgl_akhir_kredit']))}}" readonly />
                        <small class="form-text text-red-600 error"></small>
                    </div>
                    <div class="input-box space-y-3">
                        <label for="" class="uppercase">Jumlah Bulan<span
                                class="text-theme-primary">*</span></label>
                        <input type="number" class="disabled-input bg-disabled p-2 w-full border " id="jumlah_bulan"
                            name="jumlah_bulan" value="{{$pengajuan['tenor_yang_diminta']}}" readonly />
                        <small class="form-text text-red-600 error"></small>
                    </div>
                    <div class="input-box space-y-3">
                        <label for="add-role" class="uppercase">Jenis Kredit<span class="text-theme-primary">*</span>
                        </label>
                        <input type="text" class="disabled-input bg-disabled p-2 w-full border" id=""
                            name="jenis_kredit" value="{{$pengajuan['skema_kredit']}}" readonly />
                    </div>
                    <div class="input-box space-y-3">
                        <label for="" class="uppercase">No PK<span class="text-theme-primary">*</span></label>
                        <input type="text" class="disabled-input bg-disabled p-2 w-full border " id="no_pk"
                            name="no_pk" value="{{$pengajuan['no_pk']}}" readonly />
                        <small class="form-text text-red-600 error"></small>
                    </div>
                    <div class="input-box-calendar space-y-3">
                        <label for="" class="uppercase">Tanggal PK<span
                                class="text-theme-primary">*</span></label>
                        <input type="text" class="disabled-input bg-disabled p-2 w-full border" name="tgl_pk"
                            value="{{date('d-m-Y', strtotime($pengajuan['tgl_cetak_pk']))}}" readonly />
                        <small class="form-text text-red-600 error"></small>
                    </div>
                    <div class="input-box space-y-3">
                        <label for="" class="uppercase">Tanggal Pengajuan<span
                                class="text-theme-primary">*</span></label>
                        <input type="text" class="disabled-input bg-disabled p-2 w-full border" name="tgl_pengajuan"
                            value="{{date('d-m-Y', strtotime($pengajuan['tanggal']))}}" readonly />
                        <small class="form-text text-red-600 error"></small>
                    </div>
                    <div class="input-box space-y-3">
                        <label for="" class="uppercase">Plafon Kredit</label>
                        <input type="text" class="disabled-input bg-disabled p-2 w-full border " id="plafon_kredit"
                            name="plafon_kredit" value="{{number_format($pengajuan['jumlah_kredit'], 0, ',', '.')}}" readonly />
                        <small class="form-text text-red-600 error"></small>
                    </div>
                </div>
                <div class="title-form">
                    <h2 class="text-theme-primary font-bold text-lg">Data Registrasi</h2>
                </div>
                {{-- form data register 1 --}}
                <div class="lg:grid-cols-3 md:grid-cols-2 grid-cols-1 grid gap-5 justify-center">
                    <div class="input-box space-y-3">
                        <label for="add-role" class="uppercase">No Rekening<span class="text-theme-primary">*</span>
                        </label>
                        <input type="text" class="p-2 w-full border "
                        id="no_rekening" name="no_rekening" value="{{old('no_rekening')}}"/>
                        <small class="form-text text-red-600 error"></small>
                    </div>
                    <div class="input-box space-y-3">
                        <label for="add-role" class="uppercase">Jenis Asuransi<span class="text-theme-primary">*</span>
                        </label>
                        <input type="hidden" name="jenis_asuransi" id="jenis_asuransi"
                            value="{{$jenisAsuransi->id.'-'.$jenisAsuransi->kode}}">
                        <input type="text" class="bg-disabled disabled-input p-2 w-full border" name="display_jenis_asuransi"
                            id="display_jenis_asuransi" value="{{$jenisAsuransi->jenis}}" readonly>
                        <small class="form-text text-red-600 error"></small>
                    </div>
                    <div class="input-box space-y-3">
                        <label for="" class="uppercase">Jenis Pengajuan<span class="text-theme-primary">*</span>
                        </label>
                        <select name="jenis_pengajuan" class="jenis-pengajuan w-full p-2 border">
                            <option selected value="">-- Pilih Jenis Pengajuan ---</option>
                            <option @if (old('jenis_pengajuan') == '00') selected @endif value="00">Baru</option>
                            <option @if (old('jenis_pengajuan') == '01')selected @endif value="01">Top Up</option>
                        </select>
                    </div>
                    <div class="input-box space-y-3">
                        <label for="" class="uppercase">Kolektibilitas<span
                                class="text-theme-primary">*</span></label>
                        <select name="kolektibilitas" class="w-full p-2 border">
                            <option selected value="">-- Kolektibilitas ---</option>
                            <option @if (old('kolektibilitas') == '1')selected @endif value="1">1</option>
                            <option @if (old('kolektibilitas') == '2')selected @endif value="2">2</option>
                            <option @if (old('kolektibilitas') == '3')selected @endif value="3">3</option>
                            <option @if (old('kolektibilitas') == '4')selected @endif value="4">4</option>
                            <option @if (old('kolektibilitas') == '5')selected @endif value="5">5</option>
                        </select>
                        <small class="form-text text-red-600 error"></small>
                    </div>
                    <div class="input-box space-y-3">
                        <label for="add-role" class="uppercase">Jenis PERTANGGUNGAN<span
                                class="text-theme-primary">*</span> </label>
                        <select name="jenis_pertanggungan" id="jenis_pertanggungan" class="w-full p-2 border">
                            <option selected value="">-- Pilih Jenis Pertanggungan ---</option>
                            <option @if (old('jenis_pertanggungan') == '01') selected @endif value="01">Pokok</option>
                            <option @if (old('jenis_pertanggungan') == '02') selected @endif value="02">Sisa Kredit</option>
                        </select>
                    </div>
                    <div class="input-box space-y-3">
                        <label for="" class="uppercase">Tipe Premi<span class="text-theme-primary">*</span>
                        </label>
                        <select name="tipe_premi" class="w-full p-2 border">
                            <option selected value="">-- Pilih Tipe Premi ---</option>
                            <option @if (old('tipe_premi') == '0') selected @endif value="0">Biasa</option>
                            <option @if (old('tipe_premi') == '1') selected @endif value="1">Refund</option>
                        </select>
                    </div>
                </div>

                {{-- form data register 6 should be hidden when choosing baru in jenis pengajuan --}}
                <div class="form-6 hidden lg:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-5 justify-center">
                    <div class="input-box space-y-3">
                        <label for="add-role" class="uppercase">No Polis Sebelumya<span
                                class="text-theme-primary">*</span> </label>
                        <input type="text" value="{{old('no_polis_sebelumnya')}}" class="p-2 w-full border " id="" name="no_polis_sebelumnya" />
                        <small class="form-text text-red-600 error"></small>
                    </div>
                    <div class="input-box space-y-3">
                        <label for="" class="uppercase">Baki Debet<span
                                class="text-theme-primary">*</span></label>
                        <input type="text" class="rupiah p-2 w-full border " id="" value="{{old('baki_debet')}}" name="baki_debet" />
                        <small class="form-text text-red-600 error"></small>
                    </div>
                    <div class="input-box space-y-3">
                        <label for="" class="uppercase">Tunggakan<span
                                class="text-theme-primary">*</span></label>
                        <input type="text" class="rupiah p-2 w-full border " id="tunggakan" value="{{old('tunggakan')}}" name="tunggakan" />
                        <small class="form-text text-red-600 error"></small>
                    </div>
                </div>
                {{-- form data register 5 --}}
                <div class="lg:grid-cols-3 md:grid-cols-2 grid-cols-1 grid gap-5 justify-center">
                    <div class="input-box space-y-3">
                        <label for="" class="uppercase">Premi</label>
                        <input type="hidden" id="rate_premi" name="rate_premi" />
                        <input type="text" class="rupiah p-2 w-full border disabled-input bg-disabled" id="premi"
                            name="premi"  readonly />
                        <small class="form-text text-red-600 error"></small>
                    </div>
                    <div class="input-box space-y-3">
                        <label for="" class="uppercase">Jenis Coverage<span class="text-theme-primary">*</span>
                        </label>
                        <select name="jenis_coverage" class="w-full p-2 border">
                            <option selected value="">-- Pilih jenis ---</option>
                            @if ($pengajuan['age'] <= 60)
                                <option @if (old('jenis_coverage') == '01') selected @endif value="01">PNS & NON PNS (PA+ND)</option>
                                <option @if (old('jenis_coverage') == '02') selected @endif value="02">NON PNS (PA+ND+PHK)</option>
                                <option @if (old('jenis_coverage') == '03') selected @endif value="03">PNS (PA+ND+PHK+MACET)</option>
                                <option @if (old('jenis_coverage') == '04') selected @endif value="04">DPRD (PA+ND+PAW)</option>
                            @else
                                <option @if (old('jenis_coverage') == '05') selected @endif value="05">PNS & PENSIUN (PA+ND)</option>
                                <option @if (old('jenis_coverage') == '06') selected @endif value="06">DPRD (PA+ND+PAW)</option>
                            @endif
                        </select>
                    </div>
                    <div class="input-box space-y-3">
                        <label for="" class="uppercase">Tarif<span class="text-theme-primary">*</span></label>
                        <input type="text" class="disabled-input bg-disabled p-2 w-full border " id="tarif"
                            name="tarif" readonly />
                        <small class="form-text text-red-600 error"></small>
                    </div>
                    <div class="input-box space-y-3 form-6 hidden">
                        <label for="" class="uppercase">Refund<span class="text-theme-primary">*</span></label>
                        <input type="text" class="rupiah p-2 w-full border" id="refund" name="refund"
                            onchange="hitungPremiDisetor()"  />
                        <small class="form-text text-red-600 error"></small>
                    </div>
                    <div class="input-box space-y-3">
                        <label for="" class="uppercase">Kode Layanan Syariah</label>
                        <select name="kode_ls" class="w-full p-2 border">
                            <option selected value="">-- Kode Layanan Syariah ---</option>
                            <option @if (old('kode_ls') == '0') selected @endif value="0">KV</option>
                            <option @if (old('kode_ls') == '1') selected @endif value="1">SY</option>
                        </select>
                    </div>
                    <div class="input-box space-y-3">
                        <label for="" class="uppercase">Handling Fee<span
                                class="text-theme-primary">*</span></label>
                        <input type="text" class="rupiah p-2 w-full border " id="handling_fee" name="handling_fee"
                            onchange="hitungPremiDisetor()" />
                        <small class="form-text text-red-600 error"></small>
                    </div>
                    <div class="input-box space-y-3">
                        <label for="" class="uppercase">Premi Disetor<span
                                class="text-theme-primary">*</span></label>
                        <input type="text" class="disabled-input bg-disabled p-2 w-full border " id="premi_disetor"
                            name="premi_disetor" readonly />
                        <small class="form-text text-red-600 error"></small>
                    </div>
                </div>
                <div class="flex gap-5">
                    <button class="px-6 py-2 bg-theme-primary flex gap-3 rounded text-white" type="submit"
                        id="simpan-asuransi">
                        <span class="lg:mt-0 mt-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 24 24">
                                <path fill="none" stroke="currentColor" stroke-linecap="round"
                                    stroke-linejoin="round" stroke-width="2" d="M5 12h14m-7-7v14" />
                            </svg>
                        </span>
                        <span class="lg:block hidden"> Simpan </span>
                    </button>
                    <button type="button" id="form-reset"
                        class="px-6 py-2 bg-theme-primary/10 flex gap-3 rounded text-theme-primary">
                        <span class="lg:mt-1.5 mt-0">
                            @include('components.svg.reset')
                        </span>
                        <span class="lg:block hidden"> Reset </span>
                    </button>
                </div>
            </form>

        </div>

    </div>
@endsection

@push('extraScript')
    <script>
        $('#perusahaan').select2()

        var urlPost = "{{config('global.eka_lloyd_host')}}";

        $("#perusahaan").on("change", function(){
            var value = $(this).val();
            if(value == 1){
                Swal.fire({
                    icon: 'error',
                    message: 'Coming soon'
                });

                $("#perusahaan").val('').trigger('change');
            } 
        })

        $('#form-reset').on('click', function() {
            $('#form-asuransi-registrasi')[0].reset();
            if ($('#form-asuransi-registrasi .datepicker')[0]) {
                $('.datepicker').val('dd/mm/yyyy');
            }
        })

        $('#form-asuransi-registrasi .jenis-pengajuan').on('change', function() {
            if (parseInt($('.jenis-pengajuan').val()) === 1) {
                $('.form-6').removeClass('hidden')
                $('.form-7').removeClass('hidden')
                $('.form-6').addClass('grid')
                $('.form-7').addClass('grid')
            } else {
                $('.form-6').removeClass('grid')
                $('.form-7').removeClass('grid')
                $('.form-6').addClass('hidden')
                $('.form-7').addClass('hidden')
            }
        })

        $('#jenis_pertanggungan').on('change', function() {
            var masa_asuransi = $('#jumlah_bulan').val()
            if (masa_asuransi == '') {
                alertWarning('Harap pilih data pengajuan terlebih dahulu')
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
                    url: "{{ route('asuransi.registrasi.rate_premi') }}",
                    type: "GET",
                    accept: "Application/json",
                    data: {
                        'jenis': jenis,
                        'masa_asuransi': masa_asuransi,
                    },
                    success: function(response) {
                        if (response.status == 'success') {
                            if (response.data) {
                                var rate = parseFloat(response.data.rate)
                                premi = Math.round(plafon_kredit * (rate / 1000))
                                premi = formatRupiah(premi.toString())
                                $('#premi').val(premi)
                                $('#rate_premi').val(rate)
                                $('#tarif').val(rate)
                                hitungPremiDisetor()
                            } else {
                                alertWarning('Rate premi tidak ditemukan')
                            }
                        } else {
                            console.log(response.message)
                            alertWarning('Terjadi  kesalahan saat mengambil rate premi')
                        }
                    },
                    error: function(response) {
                        console.log(response)
                        alertWarning('Terjadi kesalahan saat mengambil rate premi')
                    }
                })
            }
        })

        $("#simpan-asuransi").on("click", function(e) {
            e.preventDefault();
            var total_empty_field = 0;
            var data = {};
            data['no_aplikasi'] = $("[name='no_aplikasi']").val()
            data['no_rekening'] = $("[name='no_rekening']").val()
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
                var message = 'Handling Fee belum diisi.'
                alertWarning(message)
                total_empty_field++;
            }
            if (data['kode_ls'] === '') {
                var message = 'Kode Layanan Syariah belum diisi.'
                alertWarning(message)
                total_empty_field++;
            }
            if (data['tarif'] === '') {
                var message = 'Tarif belum diisi.'
                alertWarning(message)
                total_empty_field++;
            }
            if (data['jenis_coverage'] === '') {
                var message = 'Jenis Coverage belum dipilih.'
                alertWarning(message)
                total_empty_field++;
            }
            if (data['tipe_premi'] === '') {
                var message = 'Tipe Premi belum dipilih.'
                alertWarning(message)
                total_empty_field++;
            }
            if (data['jenis_pertanggungan'] === '') {
                var message = 'Jenis Pertanggungan belum dipilih.'
                alertWarning(message)
                total_empty_field++;
            }

            if (data['jenis_pengajuan'] === '1') {
                if (data['refund'] === '') {
                    var message = 'Refund belum diisi.'
                    alertWarning(message)
                    total_empty_field++;
                } else {
                    total_empty_field++;
                }
                if (data['tunggakan'] === '') {
                    var message = 'Tunggakan belum diisi.'
                    alertWarning(message)
                    total_empty_field++;
                } else {
                    total_empty_field++;
                }
                if (data['bade'] === '') {
                    var message = 'Baki Debet belum diisi.'
                    alertWarning(message)
                    total_empty_field++;
                } else {
                    total_empty_field++;
                }
                if (data['no_polis_sebelumnya'] === '') {
                    var message = 'No Polis Sebelumnya belum diisi.'
                    alertWarning(message)
                    total_empty_field++;
                } else {
                    total_empty_field++;
                }
            }

            if (data['kolektibilitas'] === '') {
                var message = 'Kolektibilitas belum diisi.'
                alertWarning(message)
                total_empty_field++;
            }
            if (data['jenis_pengajuan'] === '') {
                var message = 'Jenis Pengajuan belum dipilih.'
                alertWarning(message)
                total_empty_field++;
            }

            if (data['jenis_asuransi'] == '') {
                var message = 'Jenis Asuransi belum dipilih.'
                alertWarning(message)
                total_empty_field++;
            }
            if (data['nama_debitur'] === '') {
                var message = 'Data Pengajuan belum dipilih.'
                alertWarning(message)
                total_empty_field++;
            }
            if (data['no_rekening'] === '') {
                var message = 'Nomor rekening belum diisi.'
                alertWarning(message)
                total_empty_field++;
            }
            if ($('#perusahaan') === '') {
                var message = 'Perusahaan harus diisi.'
                alertWarning(message)
                total_empty_field++;
            }

            if (total_empty_field == 0) {
                $("#preload-data").removeClass("hidden");

                $.ajax({
                    url: "{{ route('asuransi.registrasi.check_asuransi') }}",
                    type: "GET",
                    accept: "Application/json",
                    data: {
                        'no_pk': data['no_pk'],
                        'jenis_asuransi': data['jenis_asuransi'],
                    },
                    success: function(response) {
                        if (response.status == 'success') {
                            if (response.message == 'Data ini telah terdaftar') {
                                $("#preload-data").addClass("hidden");
                                alertWarning(`Data ini telah terdaftar pada asuransi ${response.jenis}. Harap pilih jenis asuransi yang lain.`)
                            }
                            else {
                                $('#form-asuransi-registrasi').submit()
                            }
                        }
                    },
                    error: function(response) {
                        $("#preload-data").addClass("hidden");
                        alertWarning('Terjadi kesalahan saat melakukan cek asuransi')
                    }
                })
            }
        });

        function hitungPremiDisetor() {
            //Nilai premi disetor (Premi -(Refund+Handling Fee))
            var premi = $('#premi').val()
            if (premi) {
                premi = premi.replaceAll('.', '')
                premi = parseInt(premi)
            }
            var refund = $('#refund').val()
            if (refund) {
                refund = refund.replaceAll('.', '')
                refund = parseInt(refund)
            }
            var handling_fee = $('#handling_fee').val()
            if (handling_fee) {
                handling_fee = handling_fee.replaceAll('.', '')
                handling_fee = parseInt(handling_fee)
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

        function validationData() {

        }

        function alertWarning(message) {
            Swal.fire({
                tittle: 'Warning!',
                html: message,
                icon: 'warning',
                iconColor: '#DC3545',
                confirmButtonText: 'Ya',
                confirmButtonColor: '#DC3545'
            })
        }
    </script>
@endpush
