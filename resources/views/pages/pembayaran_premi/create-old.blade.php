@extends('layout.master')

@section('modal')

@include('pages.pembayaran_premi.modal.modal-calculator');

@endsection

@section('content')
    <div class="head-pages">
        <p class="text-sm">Asuransi</p>
        <h2 class="text-2xl font-bold text-theme-primary tracking-tighter">
            Tambah Pembayaran Premi
        </h2>
        <div class="mt-5 flex justify-start">
            <button data-target-id="modal-calculator" class="toggle-modal px-6 py-2 bg-white hover:bg-white/20 border flex gap-3 rounded text-gray-500">
                <span class="lg:mt-0 mt-0">
                    @include('components.svg.calculator')
                </span>
                <span class="block"> Kalkulator  </span>
            </button>
        </div>
    </div>
<div class="body-pages">
    <div class="bg-white w-full p-5">
        <form id="form-pengajuan-klaim" action="" method="" class="space-y-5 " accept="">
            {{-- form data 1 --}}
            <div class="lg:grid-cols-3 md:grid-cols-2 grid-cols-1 grid gap-5 justify-center">
                <div class="input-box space-y-3">
                    <label for="" class="uppercase">No Klaim<span class="text-theme-primary">*</span></label>
                    <input type="text" class=" p-2 w-full border" id="" name="" />
                    <small class="form-text text-red-600 error"></small>
                </div>
                <div class="input-box-calendar space-y-3">
                    <label for="" class="uppercase">Nomor Rekening<span class="text-theme-primary">*</span></label>
                    <input type="text" class=" p-2 w-full border" id="" name="" />
                    <small class="form-text text-red-600 error"></small>
                </div>
                <div class="input-box-calendar space-y-3">
                    <label for="" class="uppercase">No Aplikasi<span class="text-theme-primary">*</span></label>
                    <input type="text" class=" p-2 w-full border" id="" name="" />
                    <small class="form-text text-red-600 error"></small>
                </div>
            </div>
            {{-- form data  2 --}}
            <div class="lg:grid-cols-3 md:grid-cols-2 grid-cols-1 grid gap-5 justify-center">
                <div class="input-box space-y-3">
                    <label for="" class="uppercase">Nilai Persetujuan<span class="text-theme-primary">*</span></label>
                    <input type="text" class="p-2 w-full border" id="" name="" />
                    <small class="form-text text-red-600 error"></small>
                </div>
                <div class="input-box-calendar space-y-3">
                    <label for="" class="uppercase">Tanggal Klaim<span class="text-theme-primary">*</span></label>
                    <div class="flex border justify-center ">
                        <div class="flex justify-center p-2 "><span>@include('components.svg.calendar')</span></div>
                        <input type="text" class="datepicker p-2 w-full" value="dd/mm/yyyy" id="" name=""/>
                    </div>
                    <small class="form-text text-red-600 error"></small>
                </div>
                <div class="input-box-calendar space-y-3">
                    <label for="" class="uppercase">No Rekening Debet<span class="text-theme-primary">*</span></label>
                    <input type="text" class="disabled-input p-2 w-full border" id="" name=""/>
                    <small class="form-text text-red-600 error"></small>
                </div>
            </div>
            {{-- form data 3 --}}
            <div class="lg:grid-cols-3 md:grid-cols-2 grid-cols-1 grid gap-5 justify-center">
                <div class="input-box space-y-3">
                    <label for="" class="uppercase">No Polis<span class="text-theme-primary">*</span></label>
                    <input type="text" class="p-2 w-full border" id="" name="" />
                    <small class="form-text text-red-600 error"></small>
                </div>
            </div>
            <div class="title-form">
                <h2 class="text-theme-primary font-bold text-lg">Rincian Bayar</h2>
            </div>
            {{-- form rincian bayar 1 --}}
            <div class="lg:grid-cols-3 md:grid-cols-2 grid-cols-1 grid gap-5 justify-center">
                <div class="input-box space-y-3">
                    <label for="" class="uppercase">Premi<span class="text-theme-primary">*</span></label>
                    <input type="text" class=" p-2 w-full border" id="" name="" />
                    <small class="form-text text-red-600 error"></small>
                </div>
                <div class="input-box-calendar space-y-3">
                    <label for="" class="uppercase">Nomor Rekening<span class="text-theme-primary">*</span></label>
                    <input type="text" class=" p-2 w-full border" id="" name="" />
                    <small class="form-text text-red-600 error"></small>
                </div>
                <div class="input-box-calendar space-y-3">
                    <label for="" class="uppercase">No Pk<span class="text-theme-primary">*</span></label>
                    <input type="text" class=" p-2 w-full border" id="" name="" />
                    <small class="form-text text-red-600 error"></small>
                </div>
            </div>
            {{-- form rincian bayar 2 --}}
            <div class="lg:grid-cols-3 md:grid-cols-2 grid-cols-1 grid gap-5 justify-center">
                <div class="input-box space-y-3">
                    <label for="" class="uppercase">No Aplikasi<span class="text-theme-primary">*</span></label>
                    <input type="text" class=" p-2 w-full border" id="" name="" />
                    <small class="form-text text-red-600 error"></small>
                </div>
                <div class="input-box-calendar space-y-3">
                    <label for="" class="uppercase">No Polis<span class="text-theme-primary">*</span></label>
                    <input type="text" class=" p-2 w-full border" id="" name="" />
                    <small class="form-text text-red-600 error"></small>
                </div>
                <div class="input-box-calendar space-y-3">
                    <label for="" class="uppercase">Periode<span class="text-theme-primary">*</span></label>
                    <input type="text" class=" p-2 w-full border" id="" name="" />
                    <small class="form-text text-red-600 error"></small>
                </div>
            </div>
            {{-- form rincian bayar 3 --}}
            <div class="lg:grid-cols-3 md:grid-cols-2 grid-cols-1 grid gap-5 justify-center">
                <div class="input-box space-y-3">
                    <label for="" class="uppercase">Total Bayar<span class="text-theme-primary">*</span></label>
                    <input type="text" class=" p-2 w-full border" id="" name="" />
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

    // function addInputRincian(data) {
    //         for (var i = 0; i < data.length; i++) {
    //             var row = data[i]
    //             var new_tr = `
    //             <tr>
    //                 <td><span id="number[]">${(i+1)}</span></td>
    //                 <td>
    //                     <input type="text" name="input_field[]" id="input_field[]" class="form-control-sm" value="${row[0]}">
    //                 </td>
    //                 <td>
    //                     <input type="text" name="input_from[]" id="input_from[]" class="form-control-sm only-number" value="${row[[1]]}">
    //                 </td>
    //                 <td>
    //                     <input type="text" name="input_to[]" id="input_to[]" class="form-control-sm only-number" value="${row[[2]]}">
    //                 </td>
    //                 <td>
    //                     <input type="text" name="input_length[]" id="input_length[]" class="form-control-sm only-number" value="${row[[3]]}">
    //                 </td>
    //                 <td>
    //                     <input type="text" name="input_description[]" id="input_description[]" class="form-control-sm" value="${row[[4]]}">
    //                 </td>
    //                 <td>
    //                     <button type="button" class="btn btn-sm btn-icon btn-round btn-danger btn-minus">
    //                         <i class="fas fa-minus"></i>
    //                     </button>
    //                 </td>
    //             </tr>
    //             `;
    //             $('#table_item tbody').append(new_tr);
    //         }
    //     }
</script>
@endpush