@extends('layout.master')

@section('modal')
    @include('pages.pembayaran_premi.modal.modal-calculator')
@endsection

@section('content')
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
        <div class="grid grid-rows-3 grid-flow-col gap-4">
            {{-- form pilih no_apk --}}
            <div class="bg-white row-span-3 p-5 border">
                <div class="input-box space-y-3">
                    <label for="add-role" class="uppercase">No Aplikasi<span class="text-theme-primary">*</span> </label>
                    <select name="" class="w-full p-2 border">
                        <option selected>-- Pilih No Aplikasi ---</option>
                    </select>
                </div>
                <div class="p-2 mt-3 mb-3 space-y-4">
                    <div class="input-checked flex gap-5">
                        <input type="checkbox" name="" id="jaminan" class="accent-theme-primary" checked>
                        <label for="jaminan">Jaminan</label>
                    </div>
                    <div class="input-checked flex gap-5">
                        <input type="checkbox" name="" id="jiwa" class="accent-theme-primary">
                        <label for="jiwa">Jiwa</label>
                    </div>
                    <div class="input-checked flex gap-5">
                        <input type="checkbox" name="" id="kredit" class="accent-theme-primary">
                        <label for="kredit">Kredit</label>
                    </div>
                </div>
                <div class="p-2">
                    <button href="{{route('pembayaran-premi.create')}}" class="px-6 py-2 bg-theme-primary flex gap-3 rounded text-white">
                        <span class="lg:mt-0 mt-0">
                            @include('components.svg.plus')
                        </span>
                        <span class="lg:block hidden"> Pilih data</span>
                    </button>
                </div>
            </div>
            <div class="row-span-3 col-span-7">
                <div class="table-wrapper bg-white border rounded-md w-full p-2">
                    <div class="table-accessiblity lg:flex text-center lg:space-y-0 space-y-5 justify-between">
                        <div class="title-table lg:p-3 p-2 text-center">
                            <h2 class="font-bold text-lg text-theme-text tracking-tighter">
                                Pembayaran Premi
                            </h2>
                        </div>
                    </div>

                    <div class="tables mt-2">
                        <table class="table-auto w-full">
                            <tr>
                                <th>No.</th>
                                <th>No Aplikasi.</th>
                                <th>No Rekening.</th>
                                <th>Premi</th>
                                <th>No PK</th>
                                <th>No Polis</th>
                                <th>Periode Bayar</th>
                                <th>Total Periode Bayar</th>
                            </tr>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>K21002022000010</td>
                                    <td><input type="text" placeholder="Input nilai disini.." class="bg-white border px-5 py-2"></td>
                                    <td>K21002022000010</td>
                                    <td>PK/0001/CU/73/0122-0132</td>
                                    <td>3/SP-02/JSB/630/VI-2022</td>
                                    <td><input type="text" placeholder="Input nilai disini.." class="bg-white border px-5 py-2"></td>
                                    <td><input type="text" placeholder="Input nilai disini.." class="bg-white border px-5 py-2"></td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>K21002022000010</td>
                                    <td><input type="text" placeholder="Input nilai disini.." class="bg-white  border px-5 py-2"></td>
                                    <td>K21002022000010</td>
                                    <td>PK/0001/CU/73/0122-0132</td>
                                    <td>3/SP-02/JSB/630/VI-2022</td>
                                    <td><input type="text" placeholder="Input nilai disini.." class="bg-white border px-5 py-2"></td>
                                    <td><input type="text" placeholder="Input nilai disini.." class="bg-white border px-5 py-2"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="footer-table p-3 text-theme-text lg:flex lg:space-y-0 space-y-10 justify-end">
                        <button href="{{route('pembayaran-premi.create')}}" class="px-6 py-2 bg-theme-primary flex gap-3 rounded text-white">
                            <span class="lg:mt-0 mt-0">
                                @include('components.svg.plus')
                            </span>
                            <span class=""> Simpan data</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('extraScript')
    <script>
        $('#form-reset').on('click', function() {
            $('#form-pengajuan-klaim')[0].reset();
            if ($('#form-pengajuan-klaim .datepicker')[0]) {
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