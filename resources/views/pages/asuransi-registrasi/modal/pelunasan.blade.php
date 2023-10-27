<div class="modal-overlay p-5 hidden" id="modalPelunasan">
    <div class="modal modal-tab">
        <div class="modal-head text-gray-500 text-lg">
            <div class="title-modal">
                Pelaporan Pelunasan
            </div>
            <button class="close-modal" data-dismiss-id="modalPelunasan">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2" d="M17 7L7 17M7 7l10 10" />
                </svg>
            </button>
        </div>
        <form action="{{ route('asuransi.registrasi.batal') }}" method="post">
            @csrf
            <div class="modal-body p-5">
                <div class="input-box space-y-3">
                    <label for="" class="uppercase">No Aplikasi</label>
                    <input type="text" class="disabled-input bg-disabled p-2 w-full border "
                        id="no_aplikasi" name="no_aplikasi" readonly/>
                    <small class="form-text text-red-600 error"></small>
                </div>
                <div class="input-box space-y-3">
                    <label for="" class="uppercase">No Rekening</label>
                    <input type="text" class="disabled-input bg-disabled p-2 w-full border "
                        id="no_rek" name="no_rek" readonly/>
                    <small class="form-text text-red-600 error"></small>
                </div>
                <div class="input-box space-y-3">
                    <label for="" class="uppercase">No Polis</label>
                    <input type="text" class="disabled-input bg-disabled p-2 w-full border "
                        id="no_polis" name="no_polis" readonly/>
                    <small class="form-text text-red-600 error"></small>
                </div>
                <div class="input-box space-y-3">
                    <label for="" class="uppercase">Refund</label>
                    <input type="text" class="disabled-input bg-disabled p-2 w-full border "
                        id="refund" name="refund" readonly/>
                    <small class="form-text text-red-600 error"></small>
                </div>
                <div class="input-box space-y-3">
                    <label for="" class="uppercase">Tanggal Pelunasan<span class="text-theme-primary">*</span></label>
                    <div class="flex border justify-center ">
                        <div class="flex justify-center p-2 "><span>@include('components.svg.calendar')</span></div>
                        <input type="text" class="datepicker p-2 w-full" id="tgl_lunas" name="tgl_lunas" />
                    </div>
                    <small class="form-text text-red-600 error"></small>
                </div>
                <div class="input-box space-y-3">
                    <label for="" class="uppercase">Sisa Jangka Waktu</label>
                    <input type="text" class="disabled-input bg-disabled p-2 w-full border "
                        id="sisa_jangka_waktu" name="sisa_jangka_waktu" readonly/>
                    <small class="form-text text-red-600 error"></small>
                </div>
            </div>
            <div class="modal-footer">
                <button data-dismiss-id="modalPelunasan" class="border px-7 py-3 text-black rounded">
                    Tutup
                </button>
                <button type="submit" class="bg-theme-primary px-7 py-3 text-white rounded">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>