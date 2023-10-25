@extends('layout.master')

@section('modal')
    @include('pages.pembayaran_premi.modal.modal-calculator')
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
        <div class="lg:flex grid md:grid-cols-1 grid-cols-1 w-full gap-5">
            {{-- form pilih no_apk --}}
            <div class="bg-white form-selection lg:w-[20rem] w-full flex-none p-5 border" id="leftForm">
                <div class="input-box space-y-3" id="inputBoxNoAplikasi">
                    <label for="add-role" class="uppercase">Nomor Aplikasi<span class="text-theme-primary">*</span> </label>
                    <select name="no_aplikasi" class="w-full p-2 border">
                        <option selected>-- Pilih No Aplikasi ---</option>
                    </select>
                    <div class="errorSpan" id="errorNoAplikasi">
                        <p id="errorText">Nomor aplikasi harus diisi.</p>
                    </div>
                </div>
                <div class="p-2 mt-3 mb-3 space-y-4" id="inputBoxJenisAsuransi">
                    <h2 class="font-bold font-lexend">Jenis Asuransi</h2>
                    <div class="input-checked flex gap-5">
                        <input type="checkbox" name="jenis[]" id="jaminan" class="accent-theme-primary">
                        <label for="jaminan">Jaminan</label>
                    </div>
                    <div class="input-checked flex gap-5">
                        <input type="checkbox" name="jenis[]" id="jiwa" class="accent-theme-primary">
                        <label for="jiwa">Jiwa</label>
                    </div>
                    <div class="input-checked flex gap-5">
                        <input type="checkbox" name="jenis[]" id="kredit" class="accent-theme-primary">
                        <label for="kredit">Kredit</label>
                    </div>
                    <div class="errorSpan" id="errorJenisAsuransi">
                        <p id="errorText">Jenis asuransi harus diisi.</p>
                    </div>
                </div>
                <div class="p-2">
                    <button href="{{route('pembayaran-premi.create')}}" class="px-6 py-2 bg-theme-primary flex gap-3 rounded text-white" id="btnLeftForm">
                        <span class="lg:mt-0 mt-0">
                            @include('components.svg.plus')
                        </span>
                        <span class="lg:block hidden"> Pilih</span>
                    </button>
                </div>
            </div>
            <div class="flex-auto lg:w-[40rem] w-full">
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
                                <th>Jenis</th>
                                <th>No Aplikasi.</th>
                                <th>Premi</th>
                                <th>No PK</th>
                                <th>No Polis</th>
                                <th>No Rekening.</th>
                                <th>Periode Bayar</th>
                                <th>Total Periode Bayar</th>
                            </tr>
                            <tbody id="rightForm">
                                <tr>
                                    <td>1</td>
                                    <td>Jaminan</td>
                                    <td>K21002022000010</td>
                                    <td>K21002022000010</td>
                                    <td>PK/0001/CU/73/0122-0132</td>
                                    <td>3/SP-02/JSB/630/VI-2022</td>
                                    <td>
                                        <div class="input-box">
                                            <input type="text" placeholder="Input nilai disini.." class="bg-white border px-5 py-2" name="no_rekening[]">
                                            <div class="errorSpan hidden" id="errorNoRekening">
                                                <p id="errorText">No rekening harus diisi.</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-box">
                                            <input type="text" placeholder="Input nilai disini.." class="bg-white border px-5 py-2" name="periode_bayar[]">
                                            <div class="errorSpan hidden" id="errorPeriodeBayar">
                                                <p id="errorText">Periode bayar harus diisi.</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-box">
                                            <input type="text" placeholder="Input nilai disini.." class="bg-white border px-5 py-2" name="total_periode_bayar[]">
                                            <div class="errorSpan hidden" id="errorTotalPeriodeBayar">
                                                <p id="errorText">Total periode bayar harus diisi.</p>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Jiwa</td>
                                    <td>K21002022000010</td>
                                    <td>K21002022000010</td>
                                    <td>PK/0001/CU/73/0122-0132</td>
                                    <td>3/SP-02/JSB/630/VI-2022</td>
                                    <td>
                                        <div class="input-box">
                                            <input type="text" placeholder="Input nilai disini.." class="bg-white border px-5 py-2" name="no_rekening[]">
                                            <div class="errorSpan hidden" id="errorNoRekening">
                                                <p id="errorText">No rekening harus diisi.</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-box">
                                            <input type="text" placeholder="Input nilai disini.." class="bg-white border px-5 py-2" name="periode_bayar[]">
                                            <div class="errorSpan hidden" id="errorPeriodeBayar">
                                                <p id="errorText">Periode bayar harus diisi.</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-box">
                                            <input type="text" placeholder="Input nilai disini.." class="bg-white border px-5 py-2" name="total_periode_bayar[]">
                                            <div class="errorSpan hidden" id="errorTotalPeriodeBayar">
                                                <p id="errorText">Total periode bayar harus diisi.</p>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="footer-table p-3 text-theme-text lg:flex lg:space-y-0 space-y-10 justify-end">
                        <button href="{{route('pembayaran-premi.create')}}" class="px-6 py-2 bg-theme-primary flex gap-3 rounded text-white" id="btnRightForm">
                            <span class="lg:mt-0 mt-0">
                                @include('components.svg.plus')
                            </span>
                            <span class=""> Simpan</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('extraScript')
    <script>
        $("#errorNoAplikasi").hide();
        $("#errorJenisAsuransi").hide();

        // $('#form-reset').on('click', function() {
        //     $('#form-pengajuan-klaim')[0].reset();
        //     if ($('#form-pengajuan-klaim .datepicker')[0]) {
        //         $('.datepicker').val('dd/mm/yyyy');
        //     }
        // })
        $('#form-toggle').on('click', function(){
                $('.form-selection').toggleClass('hidden');
                if($('.form-selection').hasClass('hidden')){
                    $('.form-button-text').html('Tampilkan form')
                }else{
                    $('.form-button-text').html('Sembunyikan form')
                }
            })

        $("#btnLeftForm").on("click", function(e){
            var checked = $('input[name="jenis[]"]:checked').length;
            if($("input[name=no_aplikasi]").val() == null && checked < 1){
                e.preventDefault();

                $("#leftForm").find("#inputBoxNoAplikasi").find('select').css({"border": "2px solid red"});
                $("#leftForm").find("#inputBoxJenisAsuransi").css({"border": "2px solid red"});

                $("#errorNoAplikasi").show();
                $("#errorJenisAsuransi").show();
            } else if($("input[name=no_aplikasi]").val() == null){
                e.preventDefault();
                $("#leftForm").find("#inputBoxNoAplikasi").css({"border": "2px solid red"});

                $("#errorNoAplikasi").show();
            } else if(checked < 1){
                e.preventDefault();
                $("#leftForm").find("#inputBoxJenisAsuransi").css({"border": "2px solid red"});

                $("#errorJenisAsuransi").show();
            }
        })

        $("#btnRightForm").on("click", function(e){
            $("#rightForm tr").each(function(){
                if($(this).find('input[name="no_rekening[]"]').val() == ''){
                    e.preventDefault();
                    $(this).find("#errorNoRekening").show();
                    $(this).find('input[name="no_rekening[]"]').css({"border": "2px solid red"});
                } else {
                    $(this).find("#errorNoRekening").hide();
                }
                if($(this).find('input[name="periode_bayar[]"]').val() == ''){
                    e.preventDefault();
                    $(this).find("#errorPeriodeBayar").show();
                    $(this).find('input[name="periode_bayar[]"]').css({"border": "2px solid red"});
                } else {
                    $(this).find("#errorPeriodeBayar").hide();
                }
                if($(this).find('input[name="total_periode_bayar[]"]').val() == ''){
                    e.preventDefault();
                    $(this).find('input[name="total_periode_bayar[]"]').css({"border": "2px solid red"});
                    $(this).find("#errorTotalPeriodeBayar").show();
                } else {
                    $(this).find("#errorTotalPeriodeBayar").hide();
                }
            })
        })
    </script>
@endpush
