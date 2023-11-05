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
    @php
        function formatRupiah($num){
            return number_format($num, 0, '.', '.');
        }

        $penyebab_klaim = [
            "1" => 'Meninggal Dunia',
            "2" => 'PHK',
            "3" => 'Kecelakaan',
            "4" => 'Kolek 4',
            "5" => 'Jatuh Tempo',
            "6" => 'PAW'
        ];
    @endphp
    <div class="bg-white w-full p-5">
        <div class="review-penyelia space-y-5">
            <h2>Review dari Penyelia</h2>
            <div class="review-timeline bg-theme-primary/5 h-[300px] border overflow-y-auto p-5">

                <ol class="relative border-l border-gray-200">
                    @forelse ($pendapat as $item)
                        <li class="mb-10 ml-4">
                            <div class="absolute w-3 h-3  rounded-full mt-1.5 -left-1.5 border border-theme-primary bg-theme-primary "></div>
                            <time class="mb-1 text-sm font-normal leading-none text-gray-400 dark:text-gray-500">{{$item?->created_at}}</time>
                            <h3 class="text-lg font-semibold text-theme-primary ">{{$item?->pendapat}}</h3>
                            <p class="mb-4 text-base font-normal text-gray-500 dark:text-gray-400">{{$item?->status}}</p>
                        </li>

                    @empty
                        <li class="">
                            <h3 class="text-lg text-center font-semibold text-theme-primary ">Belum ada pendapat review dari penyelia.</h3>
                        </li>
                    @endforelse
                </ol>
            </div>
        </div>
        <form id="form-pengajuan-klaim" action="{{route('asuransi.pengajuan-klaim.update', $data->pengajuan_klaim_id)}}" method="POST" class="space-y-5 " accept="">
            @csrf
            @method('PUT')
            {{-- form data 1 --}}
            <div class="lg:grid-cols-3 md:grid-cols-2 grid-cols-1 grid gap-5 justify-center">
                <div class="input-box space-y-3">
                    <label for="" class="uppercase">No Aplikasi<span class="text-theme-primary">*</span></label>
                    <input type="text" value="{{ $data->no_aplikasi }}" class="disabled-input bg-disabled p-2 w-full border" id="" name="no_aplikasi" readonly/>
                    <div class="errorSpan hidden" id="errorNoAplikasi">
                        <p id="errorText">No Aplikasi Belum Di Pilih.</p>
                    </div>
                </div>
                <div class="input-box-calendar space-y-3">
                    <label for="" class="uppercase">Nomor Rekening<span class="text-theme-primary">*</span></label>
                    <input type="text" value="{{ $data->no_rek }}" class="disabled-input bg-disabled p-2 w-full border" id="" name="no_rekening" readonly/>
                    <small class="form-text text-red-600 error"></small>
                </div>
                <div class="input-box-calendar space-y-3">
                    <label for="" class="uppercase">No Polis<span class="text-theme-primary">*</span></label>
                    <input type="text" value="{{ $data->no_polis }}" class="disabled-input bg-disabled p-2 w-full border" id="" name="no_sp" readonly/>
                    <small class="form-text text-red-600 error"></small>
                </div>
            </div>
            {{-- form data  2 --}}
            <div class="lg:grid-cols-3 md:grid-cols-2 grid-cols-1 grid gap-5 justify-center">
                <div class="input-box space-y-3">
                    <label for="" class="uppercase">No Surat Peringatan Ke 3<span class="text-theme-primary">*</span></label>
                    <input type="text" class="p-2 w-full border" id="" value="{{old('no_sp3', $data->no_sp3)}}" name="no_sp3" />
                    <div class="errorSpan hidden" id="errorNoSurat">
                        <p id="errorText">No Surat Peringatan Ke 3 Belum Di Isi.</p>
                    </div>
                </div>
                <div class="input-box-calendar space-y-3">
                    <label for="" class="uppercase">Tanggal Surat Peringatan Ke 3<span class="text-theme-primary">*</span></label>
                    <div class="flex border justify-center ">
                        <div class="flex justify-center p-2 "><span>@include('components.svg.calendar')</span></div>
                        <input type="text" class="datepicker p-2 w-full" id="" value="{{old('tgl_sp3', date_format(date_create($data->tgl_sp3), 'd-m-Y'))}}" name="tgl_sp3"/>
                    </div>
                    <div class="errorSpan hidden" id="errorTglSurat">
                        <p id="errorText">Tanggal Surat Peringatan Ke 3 Belum Di Isi.</p>
                    </div>
                </div>
                <div class="input-box-calendar space-y-3">
                    <label for="" class="uppercase">Tunggakan Pokok<span class="text-theme-primary">*</span></label>
                    <input type="text" class="rupiah disabled-input p-2 w-full border" id="" value="{{old('tunggakan_pokok', formatRupiah($data->tunggakan_pokok))}}" name="tunggakan_pokok">
                    <div class="errorSpan hidden" id="errorTnggakanPokok">
                        <p id="errorText">Tunggakan Pokok Belum Di Isi.</p>
                    </div>
                </div>
            </div>
            {{-- form data 3 --}}
            <div class="lg:grid-cols-3 md:grid-cols-2 grid-cols-1 grid gap-5 justify-center">
                <div class="input-box space-y-3">
                    <label for="" class="uppercase">Tunggakan Bunga<span class="text-theme-primary">*</span></label>
                    <input type="text" class="rupiah p-2 w-full border" id="" value="{{old('tunggakan_bunga', formatRupiah($data->tunggakan_bunga))}}" name="tunggakan_bunga" />
                    @error('tunggakan_bunga')
                        <small class="form-text text-red-600 error">{{ $message }}</small>
                    @enderror
                </div>
                <div class="input-box space-y-3">
                    <label for="" class="uppercase">Tunggakan Denda<span class="text-theme-primary">*</span></label>
                    <input type="text" class="rupiah p-2 w-full border" id="" value="{{old('tunggakan_denda', formatRupiah($data->tunggakan_denda))}}" name="tunggakan_denda" />
                    @error('tunggakan_denda')
                        <small class="form-text text-red-600 error">{{ $message }}</small>
                    @enderror
                </div>
                <div class="input-box space-y-3">
                    <label for="" class="uppercase">Nilai Pengikatan<span class="text-theme-primary">*</span></label>
                    <input type="text" class="rupiah p-2 w-full border" id="" value="{{old('nilai_pengikatan', formatRupiah($data->nilai_pengikatan))}}" name="nilai_pengikatan" />
                    @error('nilai_pengikatan')
                        <small class="form-text text-red-600 error">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            {{-- form data 4 --}}
            <div class="lg:grid-cols-3 md:grid-cols-2 grid-cols-1 grid gap-5 justify-center">

                <div class="input-box space-y-3">
                    <label for="" class="uppercase">Nilai Tuntunan Klaim<span class="text-theme-primary">*</span></label>
                    <input type="text" class="rupiah p-2 w-full border" id="" value="{{old('nilai_tuntutan_klaim', formatRupiah($data->nilai_tuntutan_klaim))}}" name="nilai_tuntutan_klaim" />
                    @error('nilai_tuntutan_klaim')
                        <small class="form-text text-red-600 error">{{ $message }}</small>
                    @enderror
                </div>
                <div class="input-box space-y-3">
                    <label for="" class="uppercase">Penyebab Klaim<span class="text-theme-primary">*</span></label>
                    <select name="penyebab_klaim" class="w-full p-2 border">
                        <option selected value="">-- Penyebab Klaim ---</option>
                        <option @if (old('penyebab_klaim', $data->penyebab_klaim) == '1') selected @endif value="1">Meninggal Dunia</option>
                        <option @if (old('penyebab_klaim', $data->penyebab_klaim) == '2') selected @endif value="2">PHK</option>
                        <option @if (old('penyebab_klaim', $data->penyebab_klaim) == '3') selected @endif value="3">Kecelakaan</option>
                        <option @if (old('penyebab_klaim', $data->penyebab_klaim) == '4') selected @endif value="4">Kolek 4</option>
                        <option @if (old('penyebab_klaim', $data->penyebab_klaim) == '5') selected @endif value="5">Jatuh Tempo</option>
                        <option @if (old('penyebab_klaim', $data->penyebab_klaim) == '6') selected @endif value="6">PAW</option>
                    </select>
                    @error('penyebab_klaim')
                        <small class="form-text text-red-600 error">{{ $message }}</small>
                    @enderror
                </div>
                <div class="input-box space-y-3">
                    <label for="" class="uppercase">Jenis Agunan / Kode Jenis Anggunan<span class="text-theme-primary">*</span></label>
                    <input type="text" class="p-2 w-full border" id="" value="{{old('jenis_agunan', $data->kode_agunan)}}" name="jenis_agunan" />
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
    $('#no_aplikasi').select2();
    $('#form-reset').on('click', function(){
        $('#form-pengajuan-klaim')[0].reset();
        if($('#form-pengajuan-klaim .datepicker')[0]){
            $('.datepicker').val('dd/mm/yyyy');
        }
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

    $(".datepicker").datepicker("setDate", "{{ date_format(date_create($data->tgl_sp3), 'd-m-Y') }}")
</script>
@endpush
