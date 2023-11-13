@extends('layout.master')
@section('modal')
@include('pages.pengajuan-klaim.modal.loading')
@endsection
@section('content')
    <div class="head-pages">
        <p class="text-sm">Asuransi</p>
        <h2 class="text-2xl font-bold text-theme-primary tracking-tighter">
            Tambah Pengajuan Klaim
        </h2>
    </div>
<div class="body-pages">
    <div class="bg-white w-full p-5">
        <form id="form-pengajuan-klaim" action="{{route('asuransi.pengajuan-klaim.store')}}" method="POST" class="space-y-5 " accept="">
            @csrf
            {{-- form data 1 --}}
            <div class="lg:grid-cols-3 md:grid-cols-2 grid-cols-1 grid gap-5 justify-center">
                <input type="hidden" name="no_aplikasi" value="{{ $dataNoRek[0]->no_aplikasi }}">
                <div class="input-box space-y-3">
                    <label for="" class="uppercase">No Aplikasi<span class="text-theme-primary">*</span></label>
                    <select name="no_aplikasi" id="no_aplikasi" class="w-full p-2 border" disabled>
                        {{-- <option selected>-- Pilih No Aplikasi ---</option> --}}
                        @forelse ($dataNoRek as $key => $item)
                            <option selected value="{{$item->no_aplikasi}}" data-key="{{$key}}">{{$item->no_aplikasi}} - {{$item->nama_debitur}} </option>
                        @empty
                            <option>Data Belum Ada.</option>
                        @endforelse
                    </select>
                    <div class="errorSpan hidden" id="errorNoAplikasi">
                        <p id="errorText">No Aplikasi Belum Di Pilih.</p>
                    </div>
                </div>
                <div class="input-box-calendar space-y-3">
                    <label for="" class="uppercase">Nomor Rekening<span class="text-theme-primary">*</span></label>
                    <input type="text" value="{{old('no_rekening')}}" class="disabled-input bg-disabled p-2 w-full border" id="" name="no_rekening" readonly/>
                    <small class="form-text text-red-600 error"></small>
                </div>
                <div class="input-box-calendar space-y-3">
                    <label for="" class="uppercase">No Polis<span class="text-theme-primary">*</span></label>
                    <input type="text" value="{{old('no_sp')}}" class="disabled-input bg-disabled p-2 w-full border" id="" name="no_sp" readonly/>
                    <small class="form-text text-red-600 error"></small>
                </div>
            </div>
            {{-- form data  2 --}}
            <div class="lg:grid-cols-3 md:grid-cols-2 grid-cols-1 grid gap-5 justify-center">
                <div class="input-box space-y-3">
                    <label for="" class="uppercase">No Surat Peringatan Ke 3<span class="text-theme-primary">*</span></label>
                    <input type="text" class="p-2 w-full border" id="" value="{{old('no_sp3')}}" name="no_sp3" />
                    <div class="errorSpan hidden" id="errorNoSurat">
                        <p id="errorText">No Surat Peringatan Ke 3 Belum Di Isi.</p>
                    </div>
                </div>
                <div class="input-box-calendar space-y-3">
                    <label for="" class="uppercase">Tanggal Surat Peringatan Ke 3<span class="text-theme-primary">*</span></label>
                    <div class="flex border justify-center ">
                        <div class="flex justify-center p-2 "><span>@include('components.svg.calendar')</span></div>
                        <input type="text" class="disabled-input bg-disabled p-2 w-full"
                            id="" value="{{old('tgl_sp3', date('d-m-Y'))}}" name="tgl_sp3" readonly/>
                    </div>
                    <div class="errorSpan hidden" id="errorTglSurat">
                        <p id="errorText">Tanggal Surat Peringatan Ke 3 Belum Di Isi.</p>
                    </div>
                </div>
                <div class="input-box-calendar space-y-3">
                    <label for="" class="uppercase">Tunggakan Pokok<span class="text-theme-primary">*</span></label>
                    <input type="text" class="rupiah disabled-input p-2 w-full border" id="" value="{{old('tunggakan_pokok')}}" name="tunggakan_pokok">
                    <div class="errorSpan hidden" id="errorTnggakanPokok">
                        <p id="errorText">Tunggakan Pokok Belum Di Isi.</p>
                    </div>
                </div>
            </div>
            {{-- form data 3 --}}
            <div class="lg:grid-cols-3 md:grid-cols-2 grid-cols-1 grid gap-5 justify-center">
                <div class="input-box space-y-3">
                    <label for="" class="uppercase">Tunggakan Bunga<span class="text-theme-primary">*</span></label>
                    <input type="text" class="rupiah p-2 w-full border" id="" value="{{old('tunggakan_bunga')}}" name="tunggakan_bunga" />
                    @error('tunggakan_bunga')
                        <small class="form-text text-red-600 error">{{ $message }}</small>
                    @enderror
                </div>
                <div class="input-box space-y-3">
                    <label for="" class="uppercase">Tunggakan Denda<span class="text-theme-primary">*</span></label>
                    <input type="text" class="rupiah p-2 w-full border" id="" value="{{old('tunggakan_denda')}}" name="tunggakan_denda" />
                    @error('tunggakan_denda')
                        <small class="form-text text-red-600 error">{{ $message }}</small>
                    @enderror
                </div>
                <div class="input-box space-y-3">
                    <label for="" class="uppercase">Nilai Pengikatan<span class="text-theme-primary">*</span></label>
                    <input type="text" class="rupiah p-2 w-full border" id="" value="{{old('nilai_pengikatan')}}" name="nilai_pengikatan" />
                    @error('nilai_pengikatan')
                        <small class="form-text text-red-600 error">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            {{-- form data 4 --}}
            <div class="lg:grid-cols-3 md:grid-cols-2 grid-cols-1 grid gap-5 justify-center">

                <div class="input-box space-y-3">
                    <label for="" class="uppercase">Nilai Tuntunan Klaim<span class="text-theme-primary">*</span></label>
                    <input type="text" class="rupiah p-2 w-full border" id="" value="{{old('nilai_tuntutan_klaim')}}" name="nilai_tuntutan_klaim" />
                    @error('nilai_tuntutan_klaim')
                        <small class="form-text text-red-600 error">{{ $message }}</small>
                    @enderror
                </div>
                <div class="input-box space-y-3">
                    <label for="" class="uppercase">Penyebab Klaim<span class="text-theme-primary">*</span></label>
                    <select name="penyebab_klaim" class="w-full p-2 border">
                        <option selected value="">-- Penyebab Klaim ---</option>
                        <option @if (old('penyebab_klaim') == '1') selected @endif value="1">Meninggal Dunia</option>
                        <option @if (old('penyebab_klaim') == '2') selected @endif value="2">PHK</option>
                        <option @if (old('penyebab_klaim') == '3') selected @endif value="3">Kecelakaan</option>
                        <option @if (old('penyebab_klaim') == '4') selected @endif value="4">Kolek 4</option>
                        <option @if (old('penyebab_klaim') == '5') selected @endif value="5">Jatuh Tempo</option>
                        <option @if (old('penyebab_klaim') == '6') selected @endif value="6">PAW</option>
                    </select>
                    @error('penyebab_klaim')
                        <small class="form-text text-red-600 error">{{ $message }}</small>
                    @enderror
                </div>
                <div class="input-box space-y-3">
                    <label for="" class="uppercase">Jenis Agunan / Kode Jenis Anggunan<span class="text-theme-primary">*</span></label>
                    <input type="text" class="p-2 w-full border" id="" value="{{old('jenis_agunan')}}" name="jenis_agunan" />
                    @error('jenis_agunan')
                        <small class="form-text text-red-600 error">{{ $message }}</small>
                    @enderror
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
    getNoRek();
    function getNoRek(){
        var key = $('#no_aplikasi').children("option:selected").data('key');
        var data = @json($dataNoRek);

        $('[name="no_rekening"]').val(data[key]['no_rek'])
        $('[name="no_sp"]').val(data[key]['no_polis'])
    }
    $('#no_aplikasi').select2();
    $('#form-reset').on('click', function(){
        $('#form-pengajuan-klaim')[0].reset();
        // if($('#form-pengajuan-klaim .datepicker')[0]){
        //     $('.datepicker').val('dd/mm/yyyy');
        // }
    })

    $('#no_aplikasi').on('change', function(){
        var key = $(this).children("option:selected").data('key');
        var data = @json($dataNoRek);

        $('[name="no_rekening"]').val(data[key]['no_rek'])
        $('[name="no_sp"]').val(data[key]['no_polis'])
    })

    $('#simpan').on('click', function(){
       $("#preload-data").removeClass("hidden");
        // no surat ke 3
        // if($(this).find('input[name="no_sp3"]').val() == ''){
        //     e.preventDefault();
        //     $(this).find("#errorNoSurat").show();
        //     $(this).find('input[name="no_sp3"]').css({"border": "2px solid red"});
        // } else {
        //     $(this).find("#errorNoSurat").hide();
        // }
        // // tanggal surat ke 3
        // if($(this).find('input[name="tgl_sp3"]').val() == 'dd/mm/yyyy'){
        //     e.preventDefault();
        //     $(this).find("#errorTglSurat").show();
        //     $(this).find('input[name="tgl_sp3"]').css({"border": "2px solid red"});
        // } else {
        //     $(this).find("#errorTglSurat").hide();
        // }
        // // Tunggakan Pokok
        // if($(this).find('input[name="tunggakan_pokok"]').val() == 'dd/mm/yyyy'){
        //     e.preventDefault();
        //     $(this).find("#errorTnggakanPokok").show();
        //     $(this).find('input[name="tunggakan_pokok"]').css({"border": "2px solid red"});
        // } else {
        //     $(this).find("#errorTnggakanPokok").hide();
        // }
    })


</script>
@endpush
