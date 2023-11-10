<div class="modal-overlay p-5 hidden" id="modalBatalKlaim">
    <div class="modal modal-tab">
        <div class="modal-head text-gray-500 text-lg">
            <div class="title-modal">
                Pembatalan Pengajuan Klaim
            </div>
            <button class="close-modal" data-dismiss-id="modalBatalKlaim">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2" d="M17 7L7 17M7 7l10 10" />
                </svg>
            </button>
        </div>
        <form id="form-batal" action="{{ route('asuransi.pengajuan-klaim.pembatalan-klaim') }}" method="post">
            @csrf
            <div class="modal-body p-5">
                <input type="hidden" name="_token" id="modal_token">
                <input type="hidden" name="id" id="modal_id">
                <div class="input-box space-y-3">
                    <label for="" class="uppercase">No Aplikasi</label>
                    <input type="text" class="bg-disabled disabled-input p-2 w-full border"
                        name="no_aplikasi" id="modal_no_aplikasi" readonly/>
                    <small class="form-text text-red-600 error no-aplikasi-error"></small>
                </div>
                <div class="input-box space-y-3">
                    <label for="" class="uppercase">No Rekening</label>
                    <input type="text" class="bg-disabled disabled-input p-2 w-full border"
                        name="no_rek" id="modal_no_rek" readonly/>
                    <small class="form-text text-red-600 error no-sp-error"></small>
                </div>
                <div class="input-box space-y-3">
                    <label for="" class="uppercase">No Polis</label>
                    <input type="text" class="bg-disabled disabled-input p-2 w-full border"
                        name="no_sp" id="modal_no_sp" readonly/>
                    <small class="form-text text-red-600 error no-sp-error"></small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss-id="modalBatalKlaim" class="border px-7 py-3 text-black rounded">
                    Tutup
                </button>
                <button type="submit" class="bg-theme-primary px-7 py-3 text-white rounded" id="btn-cancel-klaim">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

@push('extraScript')
    <script>
        $('.modal-batal-klaim').on('click', function() {
            const identifier = 'modalBatalKlaim'
            $(`#${identifier} #no_sp`).removeClass('border-2 border-rose-600')
            $(`#${identifier} .no-sp-error`).html('')

            const token = generateCsrfToken()
            const id = $(this).data('id');
            const no_aplikasi = $(this).data('no_aplikasi');
            const no_polis = $(this).data('no_polis');
            const no_rek = $(this).data('no_rekening');

            $(`#${identifier}`).removeClass("hidden");
            $(".layout-overlay-edit-form").removeClass("hidden");

            $(`#${identifier} #modal_token`).val(token)
            $(`#${identifier} #modal_id`).val(id)
            $(`#${identifier} #modal_no_aplikasi`).val(no_aplikasi)
            $(`#${identifier} #modal_no_sp`).val(no_polis)
            $(`#${identifier} #modal_no_rek`).val(no_rek)
        })

        $('#btn-cancel-klaim').on('click', function() {
            console.log('asdasdasdasdasd');
            $('#preload-data').removeClass('hidden')
        })
    </script>
@endpush
