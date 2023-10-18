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
        <form id="form-asuransi-registrasi" action="" method="" class="space-y-5 " accept="">
        <div class="lg:grid-cols-3 md:grid-cols-2 grid-cols-1 grid gap-5 justify-center">
            <div class="input-box space-y-3">
                <label class="uppercase">Pilih Data Pengajuan</label>
                <select name="" class="w-full p-2 border">
                    <option selected>-- Pilih Data Pengajuan ---</option>
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
                    <input type="text" class="disabled-input p-2 w-full border" value="hello" id="" name="" disabled/>
                    <small class="form-text text-red-600 error"></small>
                </div>
                <div class="input-box-calendar space-y-3">
                    <label for="" class="uppercase">Tanggal lahir<span class="text-theme-primary">*</span></label>
                    <div class="flex border justify-center bg-disabled">
                        <div class="flex justify-center p-2 bg-disabled"><span>@include('components.svg.calendar')</span></div>
                        <input type="text" class="disabled-input bg-disabled  p-2 w-full" value="dd/mm/yyyy" id="" name="" disabled/>
                    </div>
                    <small class="form-text text-red-600 error"></small>
                </div>
                <div class="input-box space-y-3">
                    <label for="" class="uppercase">Alamat<span class="text-theme-primary">*</span></label>
                    <input type="text" class="disabled-input p-2 w-full border t" id="" name="" disabled/>
                    <small class="form-text text-red-600 error"></small>
                </div>
            </div>
            {{-- form data debitur 2 --}}
            <div class="lg:grid-cols-3 md:grid-cols-2 grid-cols-1 grid gap-5 justify-center">
                <div class="input-box space-y-3">
                    <label for="" class="uppercase">No KTP<span class="text-theme-primary">*</span></label>
                    <input type="text" class="p-2 w-full border " id="" name=""/>
                    <small class="form-text text-red-600 error"></small>
                </div>
                <div class="input-box space-y-3">
                    <label for="" class="uppercase">No Aplikasi<span class="text-theme-primary">*</span></label>
                    <input type="text" class="p-2 w-full border " id="" name=""/>
                    <small class="form-text text-red-600 error"></small>
                </div>
                <div class="input-box space-y-3">
                    <label for="" class="uppercase">Kode Cabang Bank<span class="text-theme-primary">*</span></label>
                    <input type="text" class="p-2 w-full border " id="" name=""/>
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
                    <select name="" class="w-full p-2 border">
                        <option selected>-- Pilih Jenis Asuransi ---</option>
                    </select>
                </div>
                <div class="input-box-calendar space-y-3">
                    <label for="" class="uppercase">Tanggal Pengajuan<span class="text-theme-primary">*</span></label>
                    <div class="flex  border justify-center">
                        <div class="flex justify-center p-2"><span>@include('components.svg.calendar')</span></div>
                        <input type="text" class="datepicker  p-2 w-full"/>
                    </div>
                    <small class="form-text text-red-600 error"></small>
                </div>
                <div class="input-box space-y-3">
                    <label for="" class="uppercase">Jenis Pengajuan<span class="text-theme-primary">*</span> </label>
                    <select name="" class="jenis-pengajuan w-full p-2 border">
                        <option selected>-- Pilih Jenis Pengajuan ---</option>
                        <option value="0">Baru</option>
                        <option value="1">Top Up</option>
                    </select>
                </div>
            </div>
            {{-- form data register 2 --}}
            <div class="lg:grid-cols-3 md:grid-cols-2 grid-cols-1 grid gap-5 justify-center">
                <div class="input-box space-y-3">
                    <label for="" class="uppercase">No PK<span class="text-theme-primary">*</span></label>
                    <input type="text" class="p-2 w-full border " id="" name=""/>
                    <small class="form-text text-red-600 error"></small>
                </div>
                <div class="input-box-calendar space-y-3">
                    <label for="" class="uppercase">Tanggal PK<span class="text-theme-primary">*</span></label>
                    <div class="flex border justify-center">
                        <div class="flex justify-center p-2"><span>@include('components.svg.calendar')</span></div>
                        <input type="text" class="datepicker  p-2 w-full"/>
                    </div>
                    <small class="form-text text-red-600 error"></small>
                </div>
                <div class="input-box space-y-3">
                    <label for="" class="uppercase">Plafon Kredit<span class="text-theme-primary">*</span></label>
                    <input type="text" class="p-2 w-full border " id="" name=""/>
                    <small class="form-text text-red-600 error"></small>
                </div>
            </div>
            {{-- form data register 3 --}}
            <div class="lg:grid-cols-3 md:grid-cols-2 grid-cols-1 grid gap-5 justify-center">
                <div class="input-box-calendar space-y-3">
                    <label for="" class="uppercase">Tanggal Awal Kredit<span class="text-theme-primary">*</span></label>
                    <div class="flex border justify-center">
                        <div class="flex justify-center p-2"><span>@include('components.svg.calendar')</span></div>
                        <input type="text" class="datepicker  p-2 w-full"/>
                    </div>
                    <small class="form-text text-red-600 error"></small>
                </div>
                <div class="input-box-calendar space-y-3">
                    <label for="" class="uppercase">Tanggal Akhir Kredit<span class="text-theme-primary">*</span></label>
                    <div class="flex border justify-center">
                        <div class="flex justify-center p-2"><span>@include('components.svg.calendar')</span></div>
                        <input type="text" class="datepicker  p-2 w-full"/>
                    </div>
                    <small class="form-text text-red-600 error"></small>
                </div>
                <div class="input-box space-y-3">
                    <label for="add-role" class="uppercase">Jenis Kredit<span class="text-theme-primary">*</span> </label>
                    <select name="" class="w-full p-2 border">
                        <option selected>-- Pilih Jenis Kredit ---</option>
                    </select>
                </div>
            </div>
            {{-- form data register 4 --}}
            <div class="lg:grid-cols-3 md:grid-cols-2 grid-cols-1 grid gap-5 justify-center">
                <div class="input-box space-y-3">
                    <label for="" class="uppercase">Jumlah Bulan<span class="text-theme-primary">*</span></label>
                    <input type="text" class="p-2 w-full border " id="" name=""/>
                    <small class="form-text text-red-600 error"></small>
                </div>
                <div class="input-box space-y-3">
                    <label for="" class="uppercase">Kolektibilitas<span class="text-theme-primary">*</span></label>
                    <input type="text" class="p-2 w-full border " id="" name=""/>
                    <small class="form-text text-red-600 error"></small>
                </div>
                <div class="input-box space-y-3">
                    <label for="" class="uppercase">Handling Fee<span class="text-theme-primary">*</span></label>
                    <input type="text" class="p-2 w-full border " id="" name=""/>
                    <small class="form-text text-red-600 error"></small>
                </div>
            </div>
            {{-- form data register 5 --}}
            <div class="lg:grid-cols-3 md:grid-cols-2 grid-cols-1 grid gap-5 justify-center">
                <div class="input-box space-y-3">
                    <label for="add-role" class="uppercase">Jenis PERTANGGUNGAN<span class="text-theme-primary">*</span> </label>
                    <select name="" class="w-full p-2 border">
                        <option selected>-- Pilih Jenis Pertanggungan ---</option>
                    </select>
                </div>
                <div class="input-box space-y-3">
                    <label for="" class="uppercase">Kode Layanan Syariah<span class="text-theme-primary">*</span></label>
                    <input type="text" class="p-2 w-full border " id="" name=""/>
                    <small class="form-text text-red-600 error"></small>
                </div>
                <div class="input-box space-y-3">
                    <label for="" class="uppercase">Tarif<span class="text-theme-primary">*</span></label>
                    <input type="text" class="p-2 w-full border " id="" name=""/>
                    <small class="form-text text-red-600 error"></small>
                </div>
            </div>
            {{-- form data register 6 should be hidden when choosing baru in jenis pengajuan --}}
            <div class="form-6 hidden lg:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-5 justify-center">
                <div class="input-box space-y-3">
                    <label for="add-role" class="uppercase">No Polis Sebelumya<span class="text-theme-primary">*</span> </label>
                    <select name="" class="w-full p-2 border">
                        <option selected>-- Pilih No Polis ---</option>
                    </select>
                </div>
                <div class="input-box space-y-3">
                    <label for="" class="uppercase">Baki Debet<span class="text-theme-primary">*</span></label>
                    <input type="text" class="p-2 w-full border " id="" name=""/>
                    <small class="form-text text-red-600 error"></small>
                </div>
                <div class="input-box space-y-3">
                    <label for="" class="uppercase">Refund<span class="text-theme-primary">*</span></label>
                    <input type="text" class="p-2 w-full border " id="" name=""/>
                    <small class="form-text text-red-600 error"></small>
                </div>
            </div>
            {{-- form data register 7 should be hidden when choosing baru in jenis pengajuan --}}
            <div class="form-7 lg:grid-cols-3 md:grid-cols-2 grid-cols-1 hidden gap-5 justify-end">
                <div class="input-box space-y-3">
                    <label for="" class="uppercase">Tunggakan<span class="text-theme-primary">*</span></label>
                    <input type="text" class="p-2 w-full border " id="" name=""/>
                    <small class="form-text text-red-600 error"></small>
                </div>
            </div>
            <div class="flex gap-5">
                <button class="px-6 py-2 bg-theme-primary flex gap-3 rounded text-white">
                    <span class="lg:mt-0 mt-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 24 24">
                            <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2" d="M5 12h14m-7-7v14" />
                        </svg>
                    </span>
                    <span class="lg:block hidden"> Simpan  </span>
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
            </form>
        </div>
    </div>
@endsection

@push('extraScript')
<script>
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
</script>
@endpush