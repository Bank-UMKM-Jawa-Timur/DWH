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
            <div class="review-penyelia space-y-5 mb-5">
                <h2>Review dari Penyelia</h2>
                <div class="review-timeline bg-theme-primary/5 h-auto border overflow-y-auto p-5">
                    <ol class="relative border-l border-gray-200">
                        @forelse ($pendapat as $item)
                            <li class="mb-10 ml-4">
                                <div class="absolute w-3 h-3  rounded-full mt-1.5 -left-1.5 border border-theme-primary bg-theme-primary"></div>
                                <time class="mb-1 text-sm font-normal leading-none text-gray-400 dark:text-gray-500">{{date('d-m-Y', strtotime($item->created_at))}}</time>
                                {{--  <h3 class="text-lg font-semibold text-theme-primary ">Application UI code in Tailwind CSS</h3>  --}}
                                <p class="mb-4 text-base font-normal text-gray-500 dark:text-gray-400">{{$item->pendapat}}</p>
                            </li>
                        @empty
                            <span>Belum ada review dari penyelia.</span>
                        @endforelse
                    </ol>
                </div>
            </div>
            <div class="space-y-5 " accept="">
                <div class="lg:grid-cols-3 md:grid-cols-2 grid-cols-1 grid gap-5 justify-center">
                    <div class="input-box space-y-3">
                        <label class="uppercase">Pilih Perusahaan Asuransi</label>
                        <input type="text" class="disabled-input bg-disabled p-2 w-full border" id="perusahaan"
                            name="perusahaan" value="{{$perusahaan->nama}}" readonly />
                        <small class="form-text text-red-600 error"></small>
                    </div>
                    <div class="input-box space-y-3">
                        @php
                            $nip_penyelia = 'undifined';
                            $penyelia = 'undifined';
                            if (array_key_exists('penyelia', $pengajuan)) {
                                if ($pengajuan['penyelia']) {
                                    if (array_key_exists('nip', $pengajuan['penyelia'])) {
                                        $nip_penyelia = $pengajuan['penyelia']['nip'];
                                    }
                                    if (array_key_exists('nama', $pengajuan['penyelia'])) {
                                        $penyelia = $pengajuan['penyelia']['nama'];
                                    }
                                }
                            }
                        @endphp
                        <label for="" class="uppercase">Penyelia</label>
                        <input type="text" class="disabled-input bg-disabled p-2 w-full border" id=""
                            name="penyelia" value="{{$nip_penyelia.' - '.$penyelia}}" readonly />
                        <small class="form-text text-red-600 error"></small>
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
                        <input type="text" class="bg-disabled disabled-input p-2 w-full border"
                            id="no_rekening" name="no_rekening"
                            value="{{old('no_rekening', $asuransi->no_rek)}}" readonly/>
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
                        <input type="text" class="bg-disabled disabled-input p-2 w-full border" name="jenis_pengajuan"
                            id="jenis_pengajuan" value="@if (old('jenis_pengajuan', $asuransi->jenis_pengajuan) == '00') Baru @elseif (old('jenis_pengajuan', $asuransi->jenis_pengajuan) == '01') Top Up @else - @endif" readonly>
                    </div>
                    <div class="input-box space-y-3">
                        <label for="" class="uppercase">Kolektibilitas<span
                                class="text-theme-primary">*</span></label>
                        <input type="text" class="bg-disabled disabled-input p-2 w-full border" name="kolektibilitas"
                            id="kolektibilitas" value="{{old('kolektibilitas', $asuransi->kolektibilitas)}}" readonly>
                        <small class="form-text text-red-600 error"></small>
                    </div>
                    <div class="input-box space-y-3">
                        <label for="add-role" class="uppercase">Jenis PERTANGGUNGAN<span
                                class="text-theme-primary">*</span> </label>
                        <input type="text" class="bg-disabled disabled-input p-2 w-full border" name="jenis_pertanggungan"
                            id="jenis_pertanggungan" value="@if (old('jenis_pertanggungan', $asuransi->jenis_pertanggungan) == '01') Pokok @elseif (old('jenis_pertanggungan', $asuransi->jenis_pertanggungan) == '02') Sisa Kredit @else - @endif" readonly>
                    </div>
                    <div class="input-box space-y-3">
                        <label for="" class="uppercase">Tipe Premi<span class="text-theme-primary">*</span>
                        </label>
                        <input type="text" class="bg-disabled disabled-input p-2 w-full border" name="tipe_premi"
                            id="tipe_premi" value="@if (old('tipe_premi', $asuransi->tipe_premi) == '0') Biasa @elseif (old('tipe_premi', $asuransi->tipe_premi) == '1') Refund @else - @endif" readonly>
                    </div>
                </div>

                {{-- form data register 6 should be hidden when choosing baru in jenis pengajuan --}}
                <div class="form-6 hidden lg:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-5 justify-center">
                    <div class="input-box space-y-3">
                        <label for="add-role" class="uppercase">No Polis Sebelumya<span
                                class="text-theme-primary">*</span> </label>
                        <input type="text" value="{{old('no_polis_sebelumnya', $asuransi->no_polis_sebelumnya)}}"
                            class="bg-disabled disabled-input p-2 w-full border " id="" name="no_polis_sebelumnya" readonly/>
                        <small class="form-text text-red-600 error"></small>
                    </div>
                    <div class="input-box space-y-3">
                        <label for="" class="uppercase">Baki Debet<span
                                class="text-theme-primary">*</span></label>
                        <input type="text" class="bg-disabled disabled-input rupiah p-2 w-full border " id="" value="{{old('baki_debet', number_format($asuransi->baki_debet, 0, ',', '.'))}}" name="baki_debet" readonly/>
                        <small class="form-text text-red-600 error"></small>
                    </div>
                    <div class="input-box space-y-3">
                        <label for="" class="uppercase">Tunggakan<span
                                class="text-theme-primary">*</span></label>
                        <input type="text" class="bg-disabled disabled-input rupiah p-2 w-full border " id="tunggakan" value="{{old('tunggakan', $asuransi->tunggakan)}}" name="tunggakan" readonly/>
                        <small class="form-text text-red-600 error"></small>
                    </div>
                </div>
                {{-- form data register 5 --}}
                <div class="lg:grid-cols-3 md:grid-cols-2 grid-cols-1 grid gap-5 justify-center">
                    <div class="input-box space-y-3">
                        <label for="" class="uppercase">Premi</label>
                        <input type="hidden" id="rate_premi" name="rate_premi" value="{{old('rate_premi', $asuransi->tarif)}}" />
                        <input type="text" class="rupiah p-2 w-full border disabled-input bg-disabled" id="premi"
                            name="premi" value="{{old('premi', $asuransi->premi)}}" readonly />
                        <small class="form-text text-red-600 error"></small>
                    </div>
                    <div class="input-box space-y-3">
                        <label for="" class="uppercase">Jenis Coverage<span class="text-theme-primary">*</span>
                        </label>
                        @php
                            $jenis_coverage_arr = [
                                '01' => 'PNS & NON PNS (PA+ND)',
                                '02' => 'NON PNS (PA+ND+PHK)',
                                '03' => 'PNS (PA+ND+PHK+MACET)',
                                '04' => 'DPRD (PA+ND+PAW)',
                                '05' => 'PNS & PENSIUN (PA+ND)',
                                '06' => 'DPRD (PA+ND+PAW)',
                            ];
                        @endphp
                        <input type="text" class="bg-disabled disabled-input p-2 w-full border" id="jenis_coverage"
                            name="jenis_coverage" value="{{old('jenis_coverage', $jenis_coverage_arr[$asuransi->jenis_coverage])}}" readonly />
                    </div>
                    <div class="input-box space-y-3">
                        <label for="" class="uppercase">Tarif<span class="text-theme-primary">*</span></label>
                        <input type="text" class="disabled-input bg-disabled p-2 w-full border " id="tarif"
                            name="tarif" value="{{old('tarif', $asuransi->tarif)}}" readonly />
                        <small class="form-text text-red-600 error"></small>
                    </div>
                    <div class="input-box space-y-3 form-6 hidden">
                        <label for="" class="uppercase">Refund<span class="text-theme-primary">*</span></label>
                        <input type="text" class="bg-disabled disabled-input rupiah p-2 w-full border" id="refund" name="refund"
                            onchange="hitungPremiDisetor()" value="{{old('refund', number_format($asuransi->refund, 0, ',' ,'.'))}}" readonly/>
                        <small class="form-text text-red-600 error"></small>
                    </div>
                    <div class="input-box space-y-3">
                        <label for="" class="uppercase">Kode Layanan Syariah</label>
                        @php
                            $kode_ls_arr = [
                                '0' => 'KV',
                                '1' => 'SY'
                            ];
                        @endphp
                        <input type="text" class="disabled-input bg-disabled p-2 w-full border " id="kode_ls"
                            name="kode_ls" value="{{old('kode_ls', $kode_ls_arr[$asuransi->kode_layanan_syariah])}}" readonly />
                    </div>
                    <div class="input-box space-y-3">
                        <label for="" class="uppercase">Handling Fee<span
                                class="text-theme-primary">*</span></label>
                        <input type="text" class="bg-disabled disabled-input rupiah p-2 w-full border " id="handling_fee" name="handling_fee"
                            onchange="hitungPremiDisetor()" value="{{old('handling_fee', number_format($asuransi->handling_fee, 0, ',', '.'))}}" readonly/>
                        <small class="form-text text-red-600 error"></small>
                    </div>
                    <div class="input-box space-y-3">
                        <label for="" class="uppercase">Premi Disetor<span
                                class="text-theme-primary">*</span></label>
                        <input type="text" class="disabled-input bg-disabled p-2 w-full border " id="premi_disetor"
                            name="premi_disetor" value="{{old('premi_disetor', number_format($asuransi->premi_disetor, 0, ',', '.'))}}" readonly />
                        <small class="form-text text-red-600 error"></small>
                    </div>
                </div>
                <div class="mt-5 space-y-5 bg-white border p-5 w-auto">
                    <h2 class="text-theme-primary font-bold">Pendapat dari Penyelia</h2>
                    <p>Apakah form diatas yang diisi sudah benar atau ada kesalahan?.  berikan keterangan secara ringkas</p>
                    <textarea name="pendapat" class="w-full h-60 border p-4 resize-none hover:bg-theme-pages focus:bg-theme-pages"
                        placeholder="Tulis pendapat anda disini..." id="pendapat"></textarea>
                </div>
                <div class="flex gap-5">
                    <button class="px-6 py-2 bg-theme-primary flex gap-3 rounded text-white" type="button"
                        id="review-asuransi">
                        <span class="lg:mt-0 mt-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 24 24">
                                <path fill="none" stroke="currentColor" stroke-linecap="round"
                                    stroke-linejoin="round" stroke-width="2" d="M5 12h14m-7-7v14" />
                            </svg>
                        </span>
                        <span class="lg:block hidden"> Simpan </span>
                    </button>
                    <button type="button" id="btn-back-staf"
                        class="px-6 py-2 bg-theme-primary/10 flex gap-3 rounded text-theme-primary">
                        <span class="lg:mt-1.5 mt-0">
                            @include('components.svg.reset')
                        </span>
                        <span class="lg:block hidden"> Kembalikan ke staf </span>
                    </button>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('extraScript')
    <script>
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

        $("#review-asuransi").on("click", function(e) {
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

                storeReview('approved')
            }
        });

        $('#btn-back-staf').on("click", function(e) {
            $("#preload-data").removeClass("hidden");

            storeReview('revition')
        })

        function storeReview(type) {
            var body = {
                '_token': generateCsrfToken(),
                'id_asuransi': "{{$_GET['asuransi']}}",
                'type': type,
            };
            if (type == 'revition') {
                var pendapat = $('#pendapat').val()
                if (pendapat != '') {
                    body.pendapat = pendapat
                }
                else {
                    $("#preload-data").addClass("hidden");
                    alertWarning('Harap isi kolom pendapat untuk mengembalikan data ke staf');
                }
            }
            $.ajax({
                url: "{{ route('asuransi.registrasi.review_store') }}",
                type: "POST",
                accept: "Application/json",
                data: body,
                success: function(response) {
                    if (response.status == 'success') {
                        Swal.fire({
                            tittle: 'Success!',
                            html: 'Berhasil melakukan review',
                            icon: 'success',
                            iconColor: '#DC3545',
                            confirmButtonText: 'Ok',
                            confirmButtonColor: '#DC3545'
                        }).then(() => {
                            window.location = response.redirect_url
                        })
                    }
                    else {
                        console.log(response)
                        alertWarning('Gagal melakukan review')
                    }
                },
                error: function(response) {
                    console.log(response)
                    $("#preload-data").addClass("hidden");
                    alertError('Terjadi kesalahan')
                }
            })
        }

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

        function alertSuccess(message) {
            Swal.fire({
                tittle: 'Success!',
                html: message,
                icon: 'success',
                iconColor: '#DC3545',
                confirmButtonText: 'Ok',
                confirmButtonColor: '#DC3545'
            })
        }

        function alertWarning(message) {
            Swal.fire({
                tittle: 'Warning!',
                html: message,
                icon: 'warning',
                iconColor: '#DC3545',
                confirmButtonText: 'Ok',
                confirmButtonColor: '#DC3545'
            })
        }

        function alertError(message) {
            Swal.fire({
                tittle: 'Error!',
                html: message,
                icon: 'error',
                iconColor: '#DC3545',
                confirmButtonText: 'Ok',
                confirmButtonColor: '#DC3545'
            })
        }
    </script>
@endpush
