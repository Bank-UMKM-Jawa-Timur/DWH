@extends('layout.master')
@section('modal')
    @include('pages.asuransi-registrasi.modal.loading')
@endsection
@section('content')
    <div class="head-pages">
        <p class="text-sm">Asuransi</p>
        <h2 class="text-2xl font-bold text-theme-primary tracking-tighter">
            Detail Registrasi Asuransi
        </h2>
    </div>
    <div class="body-pages">
        <div class="bg-white w-full p-5">
            <form id="form-asuransi-registrasi" class="space-y-5 " accept="">
                <div class="title-form">
                    <h2 class="text-theme-primary font-bold text-lg">Data Debitur</h2>
                </div>

                {{-- form data debitur 1 --}}
                <div class="lg:grid-cols-3 md:grid-cols-2 grid-cols-1 grid gap-5 justify-center">
                    <div class="input-box space-y-3">
                        <label for="" class="uppercase">Nama<span class="text-theme-primary">*</span></label>
                        <input type="text" class="disabled-input bg-disabled p-2 w-full border" id=""
                            value="{{ $dataDebitur->nama_debitur }}" readonly />
                        <small class="form-text text-red-600 error"></small>
                    </div>
                    <div class="input-box-calendar space-y-3">
                        <label for="" class="uppercase">Tanggal lahir<span
                                class="text-theme-primary">*</span></label>
                        <input type="text" class="disabled-input bg-disabled  p-2 w-full border" id="tgl_lahir"
                            name="tgl_lahir" readonly />
                        <small class="form-text text-red-600 error"></small>
                    </div>
                    <div class="input-box space-y-3">
                        <label for="" class="uppercase">Alamat<span class="text-theme-primary">*</span></label>
                        <input type="text" class="disabled-input bg-disabled p-2 w-full border " id=""
                            name="alamat_debitur" readonly />
                        <small class="form-text text-red-600 error"></small>
                    </div>
                </div>
                {{-- form data debitur 2 --}}
                <div class="lg:grid-cols-3 md:grid-cols-2 grid-cols-1 grid gap-5 justify-center">
                    <div class="input-box space-y-3">
                        <label for="" class="uppercase">No KTP<span class="text-theme-primary">*</span></label>
                        <input type="text" class="disabled-input bg-disabled p-2 w-full border " id=""
                            name="no_ktp" readonly />
                        <small class="form-text text-red-600 error"></small>
                    </div>
                    <div class="input-box space-y-3">
                        <label for="" class="uppercase">No Aplikasi<span class="text-theme-primary">*</span></label>
                        <input type="text" class="p-2 w-full border disabled-input bg-disabled " id="no_aplikasi"
                            value="{{ $dataDebitur->no_aplikasi }}" readonly />
                        <small class="form-text text-red-600 error"></small>
                    </div>
                    <div class="input-box space-y-3">
                        <label for="" class="uppercase">Kode Cabang Bank<span
                                class="text-theme-primary">*</span></label>
                        <input type="text" class="disabled-input bg-disabled p-2 w-full border " id=""
                            name="kode_cabang" readonly />
                        <small class="form-text text-red-600 error"></small>
                    </div>
                </div>
                <div class="lg:grid-cols-3 md:grid-cols-2 grid-cols-1 grid gap-5 justify-center">
                    <div class="input-box-calendar space-y-3">
                        <label for="" class="uppercase">Tanggal Awal Kredit<span
                                class="text-theme-primary">*</span></label>
                        <input type="text" name="tanggal_awal_kredit" id="tanggal_awal_kredit"
                            class="disabled-input bg-disabled p-2 w-full border" readonly />
                        <small class="form-text text-red-600 error"></small>
                    </div>
                    <div class="input-box-calendar space-y-3">
                        <label for="" class="uppercase">Tanggal Akhir Kredit<span
                                class="text-theme-primary">*</span></label>
                        <input type="text" name="tanggal_akhir_kredit" id="tanggal_akhir_kredit"
                            class="disabled-input bg-disabled p-2 w-full border" readonly />
                        <small class="form-text text-red-600 error"></small>
                    </div>
                    <div class="input-box-calendar space-y-3">
                        <label for="" class="uppercase">Tanggal Jatuh Tempo<span
                                class="text-theme-primary">*</span></label>
                        <input type="text" name="tgl_jatuhtempo" id="tgl_jatuhtempo"
                            class="disabled-input bg-disabled  p-2 w-full border" readonly />
                        <small class="form-text text-red-600 error"></small>
                    </div>
                    <div class="input-box space-y-3">
                        <label for="" class="uppercase">Jumlah Bulan<span
                                class="text-theme-primary">*</span></label>
                        <input type="number" class="disabled-input bg-disabled p-2 w-full border " id="jumlah_bulan"
                            name="jumlah_bulan" readonly />
                        <small class="form-text text-red-600 error"></small>
                    </div>
                    <div class="input-box space-y-3">
                        <label for="add-role" class="uppercase">Jenis Kredit<span class="text-theme-primary">*</span>
                        </label>
                        <input type="text" class="disabled-input bg-disabled p-2 w-full border" id=""
                            name="jenis_kredit" readonly />
                    </div>
                    <div class="input-box space-y-3">
                        <label for="" class="uppercase">No PK<span class="text-theme-primary">*</span></label>
                        <input type="text" class="disabled-input bg-disabled p-2 w-full border " id="no_pk"
                            name="no_pk" readonly />
                        <small class="form-text text-red-600 error"></small>
                    </div>
                    <div class="input-box-calendar space-y-3">
                        <label for="" class="uppercase">Tanggal PK<span
                                class="text-theme-primary">*</span></label>
                        <input type="text" class="disabled-input bg-disabled p-2 w-full border" name="tgl_pk"
                            readonly />
                        <small class="form-text text-red-600 error"></small>
                    </div>
                    <div class="input-box space-y-3">
                        <label for="" class="uppercase">Tanggal Pengajuan<span
                                class="text-theme-primary">*</span></label>
                        <input type="text" class="disabled-input bg-disabled p-2 w-full border" name="tgl_pengajuan"
                            readonly />
                        <small class="form-text text-red-600 error"></small>
                    </div>
                    <div class="input-box space-y-3">
                        <label for="" class="uppercase">Plafon Kredit</label>
                        <input type="text" class="disabled-input bg-disabled p-2 w-full border " id="plafon_kredit"
                            name="plafon_kredit" readonly />
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
                        <input type="text" class="p-2 w-full border " id="no_rekening" name="no_rekening"
                            value="{{ old('no_rekening') }}" />
                        <small class="form-text text-red-600 error"></small>
                    </div>
                    <div class="input-box space-y-3">
                        <label for="add-role" class="uppercase">Jenis Asuransi<span class="text-theme-primary">*</span>
                        </label>
                        <select name="jenis_asuransi" class="w-full p-2 border" id="jenis_asuransi">
                            <option selected value="">-- Pilih Jenis Asuransi ---</option>
                            {{--  <option value="01">Jiwa</option>
                            <option value="02">Kerugian</option>  --}}
                        </select>
                    </div>
                    <div class="input-box space-y-3">
                        <label for="" class="uppercase">Jenis Pengajuan<span class="text-theme-primary">*</span>
                        </label>
                        <select name="jenis_pengajuan" class="jenis-pengajuan w-full p-2 border">
                            <option selected value="">-- Pilih Jenis Pengajuan ---</option>
                            <option @if (old('jenis_pengajuan') == '00') selected @endif value="00">Baru</option>
                            <option @if (old('jenis_pengajuan') == '01') selected @endif value="01">Top Up</option>
                        </select>
                    </div>
                    <div class="input-box space-y-3">
                        <label for="" class="uppercase">Kolektibilitas<span
                                class="text-theme-primary">*</span></label>
                        <select name="kolektibilitas" class="w-full p-2 border">
                            <option selected value="">-- Kolektibilitas ---</option>
                            <option @if (old('kolektibilitas') == '1') selected @endif value="1">1</option>
                            <option @if (old('kolektibilitas') == '2') selected @endif value="2">2</option>
                            <option @if (old('kolektibilitas') == '3') selected @endif value="3">3</option>
                            <option @if (old('kolektibilitas') == '4') selected @endif value="4">4</option>
                            <option @if (old('kolektibilitas') == '5') selected @endif value="5">5</option>
                        </select>
                        <small class="form-text text-red-600 error"></small>
                    </div>
                    <div class="input-box space-y-3">
                        <label for="add-role" class="uppercase">Jenis PERTANGGUNGAN<span
                                class="text-theme-primary">*</span> </label>
                        <select name="jenis_pertanggungan" id="jenis_pertanggungan" class="w-full p-2 border">
                            <option selected value="">-- Pilih Jenis Pertanggungan ---</option>
                            <option @if (old('jeniss_pertanggungan') == '01') selected @endif value="01">Pokok</option>
                            <option @if (old('jeniss_pertanggungan') == '02') selected @endif value="02">Sisa Kredit</option>
                        </select>
                    </div>
                    <div class="input-box space-y-3">
                        <label for="" class="uppercase">Tipe Premi<span class="text-theme-primary">*</span>
                        </label>
                        <select name="tipe_premi" class="w-full p-2 border">
                            <option selected value="">-- Pilih Tipe Premi ---</option>
                            <option @if (old('tipe_premi') == '0') selected @endif value="0">Biasa</option>
                            <option @if (old('tipe_premi') == '1') selected @endif value="1">Refund</option>
                        </select>
                    </div>
                </div>

                {{-- form data register 6 should be hidden when choosing baru in jenis pengajuan --}}
                <div class="form-6 hidden lg:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-5 justify-center">
                    <div class="input-box space-y-3">
                        <label for="add-role" class="uppercase">No Polis Sebelumya<span
                                class="text-theme-primary">*</span> </label>
                        <input type="text" value="old('no_polis_sebelumnya')" class="p-2 w-full border "
                            id="" name="no_polis_sebelumnya" />
                        <small class="form-text text-red-600 error"></small>
                    </div>
                    <div class="input-box space-y-3">
                        <label for="" class="uppercase">Baki Debet<span
                                class="text-theme-primary">*</span></label>
                        <input type="text" class="rupiah p-2 w-full border " id="" value="old('baki_debet')"
                            name="baki_debet" />
                        <small class="form-text text-red-600 error"></small>
                    </div>
                    <div class="input-box space-y-3">
                        <label for="" class="uppercase">Tunggakan<span
                                class="text-theme-primary">*</span></label>
                        <input type="text" class="rupiah p-2 w-full border " id="tunggakan" value="old('tunggakan')"
                            name="tunggakan" />
                        <small class="form-text text-red-600 error"></small>
                    </div>
                </div>
                {{-- form data register 5 --}}
                <div class="lg:grid-cols-3 md:grid-cols-2 grid-cols-1 grid gap-5 justify-center">
                    <div class="input-box space-y-3">
                        <label for="" class="uppercase">Premi</label>
                        <input type="hidden" id="rate_premi" name="rate_premi" />
                        <input type="text" class="rupiah p-2 w-full border disabled-input bg-disabled" id="premi"
                            name="premi" value="old('rate_premi')" readonly />
                        <small class="form-text text-red-600 error"></small>
                    </div>
                    <div class="input-box space-y-3">
                        <label for="" class="uppercase">Jenis Coverage<span class="text-theme-primary">*</span>
                        </label>
                        <select name="jenis_coverage" class="w-full p-2 border">
                            <option selected value="">-- Pilih jenis ---</option>
                        </select>
                    </div>
                    <div class="input-box space-y-3">
                        <label for="" class="uppercase">Tarif<span class="text-theme-primary">*</span></label>
                        <input type="text" class="disabled-input bg-disabled p-2 w-full border " id="tarif"
                            name="tarif" value="old('tarif')" readonly />
                        <small class="form-text text-red-600 error"></small>
                    </div>
                    <div class="input-box space-y-3 form-6 hidden">
                        <label for="" class="uppercase">Refund<span class="text-theme-primary">*</span></label>
                        <input type="text" class="rupiah p-2 w-full border" id="refund" name="refund"
                            onchange="hitungPremiDisetor()" value="old('refund')" />
                        <small class="form-text text-red-600 error"></small>
                    </div>
                    <div class="input-box space-y-3">
                        <label for="" class="uppercase">Kode Layanan Syariah</label>
                        <select name="kode_ls" class="w-full p-2 border">
                            <option selected value="">-- Kode Layanan Syariah ---</option>
                            <option @if (old('kode_is') == '0') selected @endif value="0">KV</option>
                            <option @if (old('kode_is') == '1') selected @endif value="1">SY</option>
                        </select>
                    </div>
                    <div class="input-box space-y-3">
                        <label for="" class="uppercase">Handling Fee<span
                                class="text-theme-primary">*</span></label>
                        <input type="text" class="rupiah p-2 w-full border " id="handling_fee" name="handling_fee"
                            onchange="hitungPremiDisetor()" value="old('handling_fee')" />
                        <small class="form-text text-red-600 error"></small>
                    </div>
                    <div class="input-box space-y-3">
                        <label for="" class="uppercase">Premi Disetor<span
                                class="text-theme-primary">*</span></label>
                        <input type="text" class="disabled-input bg-disabled p-2 w-full border " id="premi_disetor"
                            name="premi_disetor" readonly value="old('premi_disetor')" />
                        <small class="form-text text-red-600 error"></small>
                    </div>
                </div>
                <div class="flex gap-5">
                    <a  href="{{route('asuransi.registrasi.index')}}"
                        class="px-6 py-2 bg-primary flex gap-3 rounded text-white">
                        <span class="lg:mt-1.5 mt-0">
                            @include('components.svg.reset')
                        </span>
                        <span class="lg:block hidden"> Kembali </span>
                    </a>
                </div>
        </div>
        </form>
    </div>
@endsection
