@extends('layout.master')
@section('content')
    <div class="head-pages">
        <p class="text-sm">Asuransi</p>
        <h2 class="text-2xl font-bold text-theme-primary tracking-tighter">
            Tambah Registrasi Asuransi
        </h2>
    </div>
<div class="body-pages">
    <div class="bg-white w-full p-5">
        <form id="form-asuransi-registrasi" action="{{route('registrasi.store')}}" method="" class="space-y-5 " accept="">
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
                    <input type="text" class="disabled-input p-2 w-full border" id="" name="nama_debitur" disabled/>
                    <small class="form-text text-red-600 error"></small>
                </div>
                <div class="input-box-calendar space-y-3">
                    <label for="" class="uppercase">Tanggal lahir<span class="text-theme-primary">*</span></label>
                    <div class="flex border justify-center bg-disabled">
                        <div class="flex justify-center p-2 bg-disabled"><span>@include('components.svg.calendar')</span></div>
                        <input type="text" class="disabled-input bg-disabled  p-2 w-full" value="dd/mm/yyyy" id="" name="tanggal_lahir" disabled/>
                    </div>
                    <small class="form-text text-red-600 error"></small>
                </div>
                <div class="input-box space-y-3">
                    <label for="" class="uppercase">Alamat<span class="text-theme-primary">*</span></label>
                    <input type="text" class="disabled-input p-2 w-full border " id="" name="alamat_debitur" disabled/>
                    <small class="form-text text-red-600 error"></small>
                </div>
            </div>
                {{-- form data debitur 2 --}}
            <div class="lg:grid-cols-3 md:grid-cols-2 grid-cols-1 grid gap-5 justify-center">
                <div class="input-box space-y-3">
                    <label for="" class="uppercase">No KTP<span class="text-theme-primary">*</span></label>
                    <input type="text" class="disabled-input p-2 w-full border " id="" name="no_ktp" disabled/>
                    <small class="form-text text-red-600 error"></small>
                </div>
                <div class="input-box space-y-3">
                    <label for="" class="uppercase">No Aplikasi<span class="text-theme-primary">*</span></label>
                    <input type="text" class="p-2 w-full border " id="" name=""/>
                    <small class="form-text text-red-600 error"></small>
                </div>
                <div class="input-box space-y-3">
                    <label for="" class="uppercase">Kode Cabang Bank<span class="text-theme-primary">*</span></label>
                    <input type="text" class="disabled-input p-2 w-full border " id="" name="kode_cabang" disabled/>
                    <small class="form-text text-red-600 error"></small>
                </div>
            </div>
            <div class="title-form">
                <h2 class="text-theme-primary font-bold text-lg">Data Registrasi</h2>
            </div>
            {{-- form data register 1 --}}
            <div class="lg:grid-cols-3 md:grid-cols-2 grid-cols-1 grid gap-5 justify-center">
                <div class="input-box space-y-3">
                    <label for="add-role" class="uppercase">Jenis Asuransi<span class="text-theme-primary">*</span> </label>
                    <select name="jenis_asur" class="w-full p-2 border" id="jenis_asuransi">
                        <option selected>-- Pilih Jenis Asuransi ---</option>
                    </select>
                </div>
                <div class="input-box-calendar space-y-3">
                    <label for="" class="uppercase">Tanggal Pengajuan<span class="text-theme-primary">*</span></label>
                    <div class="flex  border justify-center">
                        <div class="flex justify-center p-2"><span>@include('components.svg.calendar')</span></div>
                        <input type="text" class="datepicker  p-2 w-full" name="tanggal_pengajuan"/>
                    </div>
                    <small class="form-text text-red-600 error"></small>
                </div>
                <div class="input-box space-y-3">
                    <label for="" class="uppercase">Jenis Pengajuan<span class="text-theme-primary">*</span> </label>
                    <select name="jenis_pengajuan" class="jenis-pengajuan w-full p-2 border">
                        <option selected>-- Pilih Jenis Pengajuan ---</option>
                        <option value="0">Baru</option>
                        <option value="1">Top Up</option>
                    </select>
                </div>
                <div class="input-box space-y-3">
                    <label for="" class="uppercase">Tipe Premi<span class="text-theme-primary">*</span> </label>
                    <select name="tipe_premi" class="jenis-pengajuan w-full p-2 border">
                        <option selected>-- Pilih Tipe Premi ---</option>
                        <option value="0">Biasa</option>
                        <option value="1">Refund</option>
                    </select>
                </div>
                <div class="input-box space-y-3">
                    <label for="" class="uppercase">Premi<span class="text-theme-primary">*</span></label>
                    <input type="text" class="rupiah p-2 w-full border " id="" name="premi"/>
                    <small class="form-text text-red-600 error"></small>
                </div>
                <div class="input-box space-y-3">
                    <label for="" class="uppercase">Jenis Coverage<span class="text-theme-primary">*</span> </label>
                    <select name="jenis_coverage" class="jenis-pengajuan w-full p-2 border">
                        <option selected>-- Pilih jenis ---</option>
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
                    <label for="" class="uppercase">Refund<span class="text-theme-primary">*</span></label>
                    <input type="text" class="rupiah p-2 w-full border " id="" name="refund"/>
                    <small class="form-text text-red-600 error"></small>
                </div>
                <div class="input-box space-y-3">
                    <label for="" class="uppercase">Tunggakan<span class="text-theme-primary">*</span></label>
                    <input type="text" class="p-2 w-full border " id="" name="tunggakan"/>
                    <small class="form-text text-red-600 error"></small>
                </div>
            </div>
            {{-- form data register 7 should be hidden when choosing baru in jenis pengajuan --}}
            {{-- <div class="form-7 lg:grid-cols-3 md:grid-cols-2 grid-cols-1 hidden gap-5 justify-end">
            </div> --}}


            {{-- form data register 2 --}}
            <div class="lg:grid-cols-3 md:grid-cols-2 grid-cols-1 grid gap-5 justify-center">
                <div class="input-box space-y-3">
                    <label for="" class="uppercase">No PK<span class="text-theme-primary">*</span></label>
                    <input type="text" class="p-2 w-full border " id="" name="no_pk"/>
                    <small class="form-text text-red-600 error"></small>
                </div>
                <div class="input-box-calendar space-y-3">
                    <label for="" class="uppercase">Tanggal PK<span class="text-theme-primary">*</span></label>
                    <div class="flex border justify-center">
                        <div class="flex justify-center p-2"><span>@include('components.svg.calendar')</span></div>
                        <input type="text" class="datepicker  p-2 w-full" name="tanggal_pk"/>
                    </div>
                    <small class="form-text text-red-600 error"></small>
                </div>
                <div class="input-box space-y-3">
                    <label for="" class="uppercase">Plafon Kredit<span class="text-theme-primary">*</span></label>
                    <input type="text" class="rupiah p-2 w-full border " id="" name="plafon_kredit"/>
                    <small class="form-text text-red-600 error"></small>
                </div>
            </div>
            {{-- form data register 3 --}}
            <div class="lg:grid-cols-3 md:grid-cols-2 grid-cols-1 grid gap-5 justify-center">
                <div class="input-box-calendar space-y-3">
                    <label for="" class="uppercase">Tanggal Awal Kredit<span class="text-theme-primary">*</span></label>
                    <div class="flex border justify-center">
                        <div class="flex justify-center p-2"><span>@include('components.svg.calendar')</span></div>
                        <input type="text" name="tanggal_awal_kredit" class="datepicker  p-2 w-full"/>
                    </div>
                    <small class="form-text text-red-600 error"></small>
                </div>
                <div class="input-box-calendar space-y-3">
                    <label for="" class="uppercase">Tanggal Akhir Kredit<span class="text-theme-primary">*</span></label>
                    <div class="flex border justify-center">
                        <div class="flex justify-center p-2"><span>@include('components.svg.calendar')</span></div>
                        <input type="text" name="tanggal_akhir_kredit" class="datepicker  p-2 w-full"/>
                    </div>
                    <small class="form-text text-red-600 error"></small>
                </div>
                <div class="input-box space-y-3">
                    <label for="add-role" class="uppercase">Jenis Kredit<span class="text-theme-primary">*</span> </label>
                    <input type="text" class="disabled-input p-2 w-full border" id="" name="jenis_kredit" disabled/>
                </div>
            </div>
            {{-- form data register 4 --}}
            <div class="lg:grid-cols-3 md:grid-cols-2 grid-cols-1 grid gap-5 justify-center">
                <div class="input-box space-y-3">
                    <label for="" class="uppercase">Jumlah Bulan<span class="text-theme-primary">*</span></label>
                    <input type="number" class="p-2 w-full border " id="" name="jumlah_bulan"/>
                    <small class="form-text text-red-600 error"></small>
                </div>
                <div class="input-box space-y-3">
                    <label for="" class="uppercase">Kolektibilitas<span class="text-theme-primary">*</span></label>
                    <select name="kolektibilitas" class="jenis-pengajuan w-full p-2 border">
                        <option selected>-- Kolektibilitas ---</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                    </select>
                    <small class="form-text text-red-600 error"></small>
                </div>
                <div class="input-box space-y-3">
                    <label for="" class="uppercase">Handling Fee<span class="text-theme-primary">*</span></label>
                    <input type="text" class="rupiah p-2 w-full border " id="" name="handling_fee"/>
                    <small class="form-text text-red-600 error"></small>
                </div>
                <div class="input-box space-y-3">
                    <label for="" class="uppercase">Kode Is</label>
                    <select name="kode_is" class="jenis-pengajuan w-full p-2 border">
                        <option selected>-- Kode Layanan Syariah ---</option>
                        <option value="0">Ky</option>
                        <option value="1">Sy</option>
                    </select>
                </div>
                <div class="input-box space-y-3">
                    <label for="" class="uppercase">premi disetor<span class="text-theme-primary">*</span></label>
                    <input type="text" class="rupiah p-2 w-full border " id="" name="premi_disetor"/>
                    <small class="form-text text-red-600 error"></small>
                </div>
            </div>
            {{-- form data register 5 --}}
            <div class="lg:grid-cols-3 md:grid-cols-2 grid-cols-1 grid gap-5 justify-center">
                <div class="input-box space-y-3">
                    <label for="add-role" class="uppercase">Jenis PERTANGGUNGAN<span class="text-theme-primary">*</span> </label>
                    <select name="jenis_pertanggunan" class="w-full p-2 border">
                        <option selected>-- Pilih Jenis Pertanggungan ---</option>
                    </select>
                </div>
                <div class="input-box space-y-3">
                    <label for="" class="uppercase">Kode Layanan Syariah<span class="text-theme-primary">*</span></label>
                    <input type="text" class="p-2 w-full border " id="" name="kode_layanan_syariah"/>
                    <small class="form-text text-red-600 error"></small>
                </div>
                <div class="input-box space-y-3">
                    <label for="" class="uppercase">Tarif<span class="text-theme-primary">*</span></label>
                    <input type="text" class="rupiah p-2 w-full border " id="" name="tarif"/>
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
<script>

    $('#pengajuan').select2();

    $('#form-reset').on('click', function(){
        $('#form-asuransi-registrasi')[0].reset();
        if($('#form-asuransi-registrasi .datepicker')[0]){
            $('.datepicker').val('dd/mm/yyyy');
        }
    })

    $('#form-asuransi-registrasi .jenis-pengajuan').on('change', function(){
        console.log(parseInt($('.jenis-pengajuan').val()));
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
        var month_diff = Date.now() - tanggalLahir.getTime();
        var age_dt = new Date(month_diff);
        let date = new Date()
        let tahunSekarang = date.getUTCFullYear();
        var year = age_dt.getUTCFullYear();

        var age = Math.abs(year - tahunSekarang);


        $('[name="nama_debitur"]').val(data[key]['nama'])
        $('[name="tanggal_lahir"]').val(data[key]['tanggal_lahir'])
        $('[name="alamat_debitur"]').val(data[key]['alamat_rumah'])
        $('[name="no_ktp"]').val(data[key]['no_ktp'])
        $('[name="kode_cabang"]').val(data[key]['kode_cabang'])
        $('[name="jenis_kredit"]').val(data[key]['skema_kredit'])
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

        $.ajax({
            type: "GET",
            url: "{{ url('/asuransi/registrasi/jenis-asuransi') }}/"+data[key]['skema_kredit'],
            success: function(data) {
                $('#jenis_asuransi').empty()
                $('#jenis_asuransi').append(`<option selected>-- Pilih Jenis Asuransi ---</option>`)

                $.each(data, function (i, item) {
                    console.log(item)
                    $('#jenis_asuransi').append(`<option>${item.jenis}</option>`);
                });
            },
            error: function(e) {
                console.log(e)
            }
        })

    })

</script>
@endpush
