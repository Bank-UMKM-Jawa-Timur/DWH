@foreach ($data as $item)
    <div class="modal-overlay p-5 hidden" id="modalBatal-{{ $item->id }}">
        <div class="modal modal-tab">
            <div class="modal-head text-gray-500 text-lg">
                <div class="title-modal">
                    Pembatalan Registrasi
                </div>
                <button class="close-modal" data-dismiss-id="modalBatal-{{ $item->id }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                        <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2" d="M17 7L7 17M7 7l10 10" />
                    </svg>
                </button>
            </div>
            <form action="{{ route('asuransi.registrasi.batal') }}" method="get">
                <div class="modal-body p-5">
                    <input type="hidden" name="no_aplikasi" value="{{ $item->no_aplikasi }}">
                    <div class="input-box space-y-3">
                        <label for="" class="uppercase">No SP<span class="text-theme-primary">*</span></label>
                        <input type="text" class="disabled-input bg-disabled p-2 w-full border " id="no_sp" name="no_sp"/>
                        <small class="form-text text-red-600 error"></small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button data-dismiss-id="modalBatal-{{ $item->id }}" class="border px-7 py-3 text-black rounded">
                        Tutup
                    </button>
                    <button type="submit" class="bg-theme-primary px-7 py-3 text-white rounded">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endforeach