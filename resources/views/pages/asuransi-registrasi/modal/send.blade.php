<div class="modal-overlay p-5 hidden" id="modalSend">
    <div class="modal modal-tab">
        <div class="modal-head text-gray-500 text-lg">
            <div class="title-modal">
                Anda yakin akan mengirim data ini
            </div>
            <button class="close-modal" data-dismiss-id="modalSend">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2" d="M17 7L7 17M7 7l10 10" />
                </svg>
            </button>
        </div>
        <form id="form-send" action="{{ route('asuransi.registrasi.send') }}" method="post">
            <input type="hidden" name="_token" id="modal_token">
            <input type="hidden" name="id" id="modal_id">
            <div class="modal-body p-5">
                <div class="input-box space-y-3">
                    <label for="" class="uppercase">No Aplikasi</label>
                    <input type="text" class="disabled-input bg-disabled p-2 w-full border "
                        id="modal_no_aplikasi" name="no_aplikasi" readonly/>
                    <small class="form-text text-red-600 error"></small>
                </div>
                <div class="input-box space-y-3 mt-3">
                    <label for="" class="uppercase">Nama Debitur</label>
                    <input type="text" class="disabled-input bg-disabled p-2 w-full border "
                        id="modal_debitur" name="debitur" readonly/>
                    <small class="form-text text-red-600 error"></small>
                </div>
            </div>
            <div class="modal-footer">
                <button data-dismiss-id="modalSend" class="border px-7 py-3 text-black rounded" type="button">
                    Tutup
                </button>
                <button type="submit" class="bg-theme-primary px-7 py-3 text-white rounded" id="btn-send">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

@push('extraScript')
    <script>
        $('.modal-kirim').on('click', function() {
            const identifier = 'modalSend'
            const token = generateCsrfToken()
            const id = $(this).data('id');
            const no_aplikasi = $(this).data('no_aplikasi');
            const debitur = $(this).data('debitur');

            $(`#${identifier}`).removeClass("hidden");
            $(".layout-overlay-edit-form").removeClass("hidden");
            
            $(`#${identifier} #modal_token`).val(token)
            $(`#${identifier} #modal_id`).val(id)
            $(`#${identifier} #modal_no_aplikasi`).val(no_aplikasi)
            $(`#${identifier} #modal_debitur`).val(debitur)
        })
    </script>
@endpush