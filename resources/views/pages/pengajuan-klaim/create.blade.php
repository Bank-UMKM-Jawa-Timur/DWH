@extends('layout.master')
@section('content')
    <div class="head-pages">
        <p class="text-sm">Asuransi</p>
        <h2 class="text-2xl font-bold text-theme-primary tracking-tighter">
            Tambah Pengajuan Klaim
        </h2>
    </div>
<div class="body-pages">
    <div class="bg-white w-full p-5">
        <form id="form-pengajuan-klaim" action="" method="" class="space-y-5 " accept="">
            {{-- form data 1 --}}
            <div class="lg:grid-cols-3 md:grid-cols-2 grid-cols-1 grid gap-5 justify-center">
                <div class="input-box space-y-3">
                    <label for="" class="uppercase">No Aplikasi<span class="text-theme-primary">*</span></label>
                    <input type="text" class="disabled-input p-2 w-full border" id="" name="" disabled/>
                    <small class="form-text text-red-600 error"></small>
                </div>
                <div class="input-box-calendar space-y-3">
                    <label for="" class="uppercase">Nomor Rekening<span class="text-theme-primary">*</span></label>
                    <input type="text" class="disabled-input p-2 w-full border" id="" name="" disabled/>
                    <small class="form-text text-red-600 error"></small>
                </div>
                <div class="input-box-calendar space-y-3">
                    <label for="" class="uppercase">No Polis<span class="text-theme-primary">*</span></label>
                    <input type="text" class="disabled-input p-2 w-full border" id="" name="" disabled/>
                    <small class="form-text text-red-600 error"></small>
                </div>
            </div>
            {{-- form data  2 --}}
            <div class="lg:grid-cols-3 md:grid-cols-2 grid-cols-1 grid gap-5 justify-center">
                <div class="input-box space-y-3">
                    <label for="" class="uppercase">No Surat Peringatan Ke 3<span class="text-theme-primary">*</span></label>
                    <input type="text" class="p-2 w-full border" id="" name="" />
                    <small class="form-text text-red-600 error"></small>
                </div>
                <div class="input-box-calendar space-y-3">
                    <label for="" class="uppercase">Tanggal Surat Peringatan Ke 3<span class="text-theme-primary">*</span></label>
                    <div class="flex border justify-center ">
                        <div class="flex justify-center p-2 "><span>@include('components.svg.calendar')</span></div>
                        <input type="text" class="datepicker p-2 w-full" value="dd/mm/yyyy" id="" name=""/>
                    </div>
                    <small class="form-text text-red-600 error"></small>
                </div>
                <div class="input-box-calendar space-y-3">
                    <label for="" class="uppercase">Sisa Tunggakan Pokok<span class="text-theme-primary">*</span></label>
                    <input type="text" class="disabled-input p-2 w-full border" id="" name="" disabled/>
                    <small class="form-text text-red-600 error"></small>
                </div>
            </div>
            {{-- form data 3 --}}
            <div class="lg:grid-cols-3 md:grid-cols-2 grid-cols-1 grid gap-5 justify-center">
                <div class="input-box space-y-3">
                    <label for="" class="uppercase">Tunggakan Pokok<span class="text-theme-primary">*</span></label>
                    <input type="text" class="p-2 w-full border" id="" name="" />
                    <small class="form-text text-red-600 error"></small>
                </div>
                <div class="input-box space-y-3">
                    <label for="" class="uppercase">Tunggukan Bunga<span class="text-theme-primary">*</span></label>
                    <input type="text" class="p-2 w-full border" id="" name="" />
                    <small class="form-text text-red-600 error"></small>
                </div>
                <div class="input-box space-y-3">
                    <label for="" class="uppercase">Tunggukan Denda<span class="text-theme-primary">*</span></label>
                    <input type="text" class="p-2 w-full border" id="" name="" />
                    <small class="form-text text-red-600 error"></small>
                </div>
            </div>
            {{-- form data 4 --}}
            <div class="lg:grid-cols-3 md:grid-cols-2 grid-cols-1 grid gap-5 justify-center">
                <div class="input-box space-y-3">
                    <label for="" class="uppercase">Nilai Pengikatan<span class="text-theme-primary">*</span></label>
                    <input type="text" class="p-2 w-full border" id="" name="" />
                    <small class="form-text text-red-600 error"></small>
                </div>
                <div class="input-box space-y-3">
                    <label for="" class="uppercase">Nilai Tuntunan Klaim<span class="text-theme-primary">*</span></label>
                    <input type="text" class="p-2 w-full border" id="" name="" />
                    <small class="form-text text-red-600 error"></small>
                </div>
                <div class="input-box space-y-3">
                    <label for="" class="uppercase">Penyebab Klaim<span class="text-theme-primary">*</span></label>
                    <input type="text" class="p-2 w-full border" id="" name="" />
                    <small class="form-text text-red-600 error"></small>
                </div>
            </div>
            {{-- form data 4 --}}
            <div class="lg:grid-cols-3 md:grid-cols-2 grid-cols-1 grid gap-5 justify-center">
                <div class="input-box space-y-3">
                    <label for="" class="uppercase">Jenis Agunan<span class="text-theme-primary">*</span></label>
                    <input type="text" class="p-2 w-full border" id="" name="" />
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
        $('#form-pengajuan-klaim')[0].reset();
        if($('#form-pengajuan-klaim .datepicker')[0]){
            $('.datepicker').val('dd/mm/yyyy');
        }
    })
</script>
@endpush