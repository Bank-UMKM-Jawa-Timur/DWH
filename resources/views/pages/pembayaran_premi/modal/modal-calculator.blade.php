<div class="modal-overlay p-5 hidden" id="modal-calculator">
    <div class="modal modal-tab">
        <div class="modal-head text-gray-500 text-lg">
            <div class="title-modal">
                <h2 class="font-bo">Kalkulator Premi </h2>
            </div>
            <button class="close-modal" data-dismiss-id="modal-calculator">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2" d="M17 7L7 17M7 7l10 10" />
                </svg>
            </button>
        </div>
        <div class="modal-body p-5">
            <div class="lg:grid-cols-2 md:grid-cols-2 grid-cols-1 grid gap-5 justify-center">
                <div class="input-box-calendar space-y-3">
                    <label for="" class="uppercase">Tanggal Awal Kredit<span class="text-theme-primary">*</span></label>
                    <div class="flex border justify-center tgl_awal_kredit">
                        <div class="flex justify-center p-2 "><span>@include('components.svg.calendar')</span></div>
                        <input type="text" class="datepicker p-2 w-full" value="dd/mm/yyyy" id="tgl_awal_kredit" name=""/>
                    </div>
                    {{-- <small class="form-text text-red-600 error"></small> --}}
                </div>
                <div class="input-box-calendar space-y-3">
                    <label for="" class="uppercase">Tanggal Akhir Kredit<span class="text-theme-primary">*</span></label>
                    <div class="flex border justify-center tgl_akhir_kredit">
                        <div class="flex justify-center p-2 "><span>@include('components.svg.calendar')</span></div>
                        <input type="text" class="datepicker p-2 w-full" value="dd/mm/yyyy" id="tgl_akhir_kredit" name=""/>
                    </div>
                    {{-- <small class="form-text text-red-600 error"></small> --}}
                </div>
                <div class="input-box-calendar space-y-3">
                    <label for="" class="uppercase">Tanggal Lahir<span class="text-theme-primary">*</span></label>
                    <div class="flex border justify-center tgl_lahir">
                        <div class="flex justify-center p-2 "><span>@include('components.svg.calendar')</span></div>
                        <input type="text" class="datepicker p-2 w-full" value="dd/mm/yyyy" id="tgl_lahir" name=""/>
                    </div>
                    {{-- <small class="form-text text-red-600 error"></small> --}}
                </div>
                <div class="input-box space-y-3">
                    <label class="uppercase">Jenis Covarage<span class="text-theme-primary">*</span></label>
                    <select name="" class="w-full p-2 border">
                        <option selected>-- Pilih Jenis Covarage ---</option>
                    </select>
                </div>
            </div>
            <div class="input-box space-y-3 mt-4">
                <label for="" class="uppercase">UP<span class="text-theme-primary">*</span></label>
                <input type="text" class="p-2 w-full border" id="up" name="" />
                {{-- <small class="form-text text-red-600 error"></small> --}}
            </div>
        </div>
        <div class="modal-footer flex gap-3 justify-end">
            <button id="btn-hitung" class="px-7 py-3 bg-theme-primary flex gap-3 rounded text-white">
                <span class="lg:mt-0 mt-0">
                    @include('components.svg.calculator')
                </span>
                <span class="lg:block hidden"> Hitung </span>
            </button>
            <button data-dismiss-id="modal-calculator" class="border px-7 py-3 text-black rounded">
                Tutup
            </button>
        </div>
    </div>
</div>
