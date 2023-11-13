@extends('layout.master')
@section('modal')
@include('pages.pengajuan-klaim.modal.loading')
@endsection
@section('content')
    <div class="head-pages">
        <p class="text-sm">Asuransi</p>
        <h2 class="text-2xl font-bold text-theme-primary tracking-tighter">
            Review Pengajuan Klaim
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
        <form id="form-pengajuan-klaim" action="{{route('asuransi.pengajuan-klaim.approval', $data->pengajuan_klaim_id)}}" method="POST" class="space-y-5 " accept="">
            @csrf
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
                    <input type="text" class="disabled-input bg-disabled p-2 w-full border" id="" value="{{ $data->no_sp3 }}" name="no_sp3" readonly/>
                    <div class="errorSpan hidden" id="errorNoSurat">
                        <p id="errorText">No Surat Peringatan Ke 3 Belum Di Isi.</p>
                    </div>
                </div>
                <div class="input-box-calendar space-y-3">
                    <label for="" class="uppercase">Tanggal Surat Peringatan Ke 3<span class="text-theme-primary">*</span></label>
                    <div class="flex border justify-center ">
                        <div class="flex justify-center p-2 "><span>@include('components.svg.calendar')</span></div>
                        <input type="text" class="disabled-input bg-disabled p-2 w-full" id=""
                            value="{{ date_format(date_create($data->tgl_sp3), 'd-m-Y') }}" name="tgl_sp3" readonly/>
                    </div>
                    <div class="errorSpan hidden" id="errorTglSurat">
                        <p id="errorText">Tanggal Surat Peringatan Ke 3 Belum Di Isi.</p>
                    </div>
                </div>
                <div class="input-box-calendar space-y-3">
                    <label for="" class="uppercase">Tunggakan Pokok<span class="text-theme-primary">*</span></label>
                    <input type="text" class="rupiah disabled-input bg-disabled p-2 w-full border" id="" value="{{formatRupiah($data->tunggakan_pokok)}}" name="tunggakan_pokok" readonly>
                    <div class="errorSpan hidden" id="errorTnggakanPokok">
                        <p id="errorText">Tunggakan Pokok Belum Di Isi.</p>
                    </div>
                </div>
            </div>
            {{-- form data 3 --}}
            <div class="lg:grid-cols-3 md:grid-cols-2 grid-cols-1 grid gap-5 justify-center">
                <div class="input-box space-y-3">
                    <label for="" class="uppercase">Tunggakan Bunga<span class="text-theme-primary">*</span></label>
                    <input type="text" class="disabled-input bg-disabled rupiah p-2 w-full border" id="" value="{{formatRupiah($data->tunggakan_bunga)}}" name="tunggakan_bunga" readonly/>
                    @error('tunggakan_bunga')
                        <small class="form-text text-red-600 error">{{ $message }}</small>
                    @enderror
                </div>
                <div class="input-box space-y-3">
                    <label for="" class="uppercase">Tunggakan Denda<span class="text-theme-primary">*</span></label>
                    <input type="text" class="disabled-input bg-disabled rupiah p-2 w-full border" id="" value="{{formatRupiah($data->tunggakan_denda)}}" name="tunggakan_denda" readonly/>
                    @error('tunggakan_denda')
                        <small class="form-text text-red-600 error">{{ $message }}</small>
                    @enderror
                </div>
                <div class="input-box space-y-3">
                    <label for="" class="uppercase">Nilai Pengikatan<span class="text-theme-primary">*</span></label>
                    <input type="text" class="disabled-input bg-disabled rupiah p-2 w-full border" id="" value="{{formatRupiah($data->nilai_pengikatan)}}" name="nilai_pengikatan" readonly/>
                    @error('nilai_pengikatan')
                        <small class="form-text text-red-600 error">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            {{-- form data 4 --}}
            <div class="lg:grid-cols-3 md:grid-cols-2 grid-cols-1 grid gap-5 justify-center">

                <div class="input-box space-y-3">
                    <label for="" class="uppercase">Nilai Tuntunan Klaim<span class="text-theme-primary">*</span></label>
                    <input type="text" class="disabled-input bg-disabled rupiah p-2 w-full border" id="" value="{{formatRupiah($data->nilai_tuntutan_klaim)}}" name="nilai_tuntutan_klaim" readonly/>
                    @error('nilai_tuntutan_klaim')
                        <small class="form-text text-red-600 error">{{ $message }}</small>
                    @enderror
                </div>
                <div class="input-box space-y-3">
                    <label for="" class="uppercase">Penyebab Klaim<span class="text-theme-primary">*</span></label>
                    <input type="text" value="{{ $penyebab_klaim[$data->penyebab_klaim] }}" class="disabled-input bg-disabled p-2 w-full border" id="" name="no_sp" readonly/>
                    @error('penyebab_klaim')
                        <small class="form-text text-red-600 error">{{ $message }}</small>
                    @enderror
                </div>
                <div class="input-box space-y-3">
                    <label for="" class="uppercase">Jenis Agunan / Kode Jenis Anggunan<span class="text-theme-primary">*</span></label>
                    <input type="text" class="disabled-input bg-disabled p-2 w-full border" id="" value="{{$data->kode_agunan}}" name="jenis_agunan" readonly/>
                    @error('jenis_agunan')
                        <small class="form-text text-red-600 error">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <hr>
            <div class="mt-5 space-y-5 bg-white border p-5 w-auto">
                <h2 class="text-theme-primary font-bold">Pendapat dari Penyelia</h2>
                <p>Apakah form diatas yang diisi sudah benar atau ada kesalahan?.  berikan keterangan secara ringkas.</p>
                <p>Catatan!Kolom ini wajib diisi jika ingin mengembalikan data ke staf.</p>
                <textarea name="pendapat" class="w-full h-60 border p-4 resize-none hover:bg-theme-pages focus:bg-theme-pages"
                    placeholder="Tulis pendapat anda disini..." id="pendapat"></textarea>
            </div>
            <div class="flex gap-5">
                <button class="px-6 py-2 bg-theme-primary flex gap-3 rounded text-white" type="submit" id="simpan">
                    <iconify-icon icon="tabler:check" class="mt-1"></iconify-icon>
                    <span class="lg:block hidden"> Approve  </span>
                </button>
            <button type="button"
                id="btnKembalikan"
                class="px-6 py-2 bg-theme-primary/10 flex gap-3 rounded text-theme-primary">
                <span class="lg:mt-1.5 mt-0">
                    @include('components.svg.reset')
                </span>
                <span class="lg:block hidden"> Kembalikan Ke Staf </span>
            </button>
            </div>
            </form>
        </div>
    </div>
@endsection

@push('extraScript')
<script>
    $("#btnKembalikan").on("click", function(){
        var pendapat = $("[name=pendapat]").val()
        if(pendapat == ""){
            Swal.fire({
                icon: "error",
                title: "Gagal",
                text: "Pendapat harus diisi"
            })
        } else{
            var data = {
                _token: "{{ csrf_token() }}",
                pendapat: pendapat
            };
            $.ajax({
                type: "POST",
                url: "{{ route('asuransi.pengajuan-klaim.kembalikan-ke-staf', $data->pengajuan_klaim_id) }}",
                data: data,
                success: function(res){
                    Swal.fire({
                        tittle: 'Success!',
                        html: 'Berhasil melakukan review',
                        icon: 'success',
                        iconColor: '#DC3545',
                        confirmButtonText: 'Ok',
                        confirmButtonColor: '#DC3545'
                    }).then(() => {
                        window.location = "{{ route('asuransi.pengajuan-klaim.index') }}"
                    })
                },
                error: function(e){
                    Swal.fire({
                        icon: 'error',
                        text: res.message,
                        title: 'Gagal'
                    }, function(){
                        window.location.reload()
                    });
                }
            })
        }
    })
</script>
@endpush
