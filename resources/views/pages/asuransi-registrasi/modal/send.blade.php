<div class="modal-overlay hidden" id="modalSend">
    <div class="modal-full modal-tab">
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
                 <div class="title-form">
                    <h2 class="text-theme-primary font-bold text-lg">Data Debitur</h2>
                </div>
                <div class="lg:grid-cols-2 grid grid-cols-1 gap-5">
                    <div class="input-box space-y-3">
                    <label for="" class="uppercase">No Aplikasi</label>
                    <input type="text" class="disabled-input bg-disabled p-2 w-full border "
                        id="modal_no_aplikasi" name="no_aplikasi" readonly/>
                    <small class="form-text text-red-600 error"></small>
                    </div>
                    <div class="input-box space-y-3">
                        <label for="" class="uppercase">Nama Debitur</label>
                        <input type="text" class="disabled-input bg-disabled p-2 w-full border "
                            id="modal_debitur" name="debitur" readonly/>
                        <small class="form-text text-red-600 error"></small>
                    </div>
                </div>

                 <div class="title-form mt-5">
                    <h2 class="text-theme-primary font-bold text-lg">Data Registrasi</h2>
                </div>

                <div class="lg:grid-cols-3 grid grid-cols-1 gap-5 mt-3">
                    <div class="input-box space-y-3 ">
                        <label for="" class="uppercase">No Rekening</label>
                        <input type="text" class="disabled-input bg-disabled p-2 w-full border "
                        id="modal_no_rek" name="no_rek" readonly/>
                        <small class="form-text text-red-600 error"></small>
                    </div>
                    <div class="input-box space-y-3 ">
                        <label for="" class="uppercase">Jenis Asuransi</label>
                        <input type="text" class="disabled-input bg-disabled p-2 w-full border "
                            id="modal_jenis_kredit" name="jenis_kredit" readonly/>
                        <small class="form-text text-red-600 error"></small>
                    </div>
                    <div class="input-box space-y-3 ">
                        <label for="" class="uppercase">Premi</label>
                        <input type="text" class="disabled-input bg-disabled p-2 w-full border "
                        id="modal_premi" name="premi" readonly/>
                        <small class="form-text text-red-600 error"></small>
                    </div>
                </div>
                <div class="lg:grid-cols-3 grid grid-cols-1 gap-5">
                    <div class="input-box space-y-3 mt-3">
                        <label for="" class="uppercase">Tarif</label>
                        <input type="text" class="disabled-input bg-disabled p-2 w-full border "
                            id="modal_tarif" name="tarif" readonly/>
                        <small class="form-text text-red-600 error"></small>
                    </div>
                    <div class="input-box space-y-3 mt-3">
                        <label for="" class="uppercase">Handling Fee</label>
                        <input type="text" class="disabled-input bg-disabled p-2 w-full border "
                            id="modal_fee" name="fee" readonly/>
                        <small class="form-text text-red-600 error"></small>
                    </div>
                    <div class="input-box space-y-3 mt-3">
                    <label for="" class="uppercase">Premi Disetor</label>
                    <input type="text" class="disabled-input bg-disabled p-2 w-full border "
                        id="modal_premi_disetor" name="premi_disetor" readonly/>
                    <small class="form-text text-red-600 error"></small>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button data-dismiss-id="modalSend" class="border px-7 py-3 text-black rounded" type="button">
                    Tutup
                </button>
                <button type="submit" class="bg-theme-primary px-7 py-3 text-white rounded" id="btn-send">
                    Kirim
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
            const tgl_pengajuan = $(this).data('tgl_pengajuan');
            const no_rek = $(this).data('no_rek');
            const jenis_kredit = $(this).data('jenis_kredit');
            var premi = $(this).data('premi');
            var intPremi = parseInt(premi);
            var formatPremi = formatRupiah(premi);

            var tarif = $(this).data('tarif');

            var fee = $(this).data('fee');
            var intFee = parseInt(fee);
            var formatFee = formatRupiah(intFee);

            var premi_disetor = $(this).data('premi_disetor');
            var intPremiDisetor = parseInt(premi_disetor);
            var formatPremiDisetor = formatRupiah(intPremiDisetor);

            $(`#${identifier}`).removeClass("hidden");
            $(".layout-overlay-edit-form").removeClass("hidden");

            $(`#${identifier} #modal_token`).val(token)
            $(`#${identifier} #modal_id`).val(id)
            $(`#${identifier} #modal_no_aplikasi`).val(no_aplikasi)
            $(`#${identifier} #modal_debitur`).val(debitur)
            $(`#${identifier} #modal_tgl_pengajuan`).val(tgl_pengajuan)
            $(`#${identifier} #modal_no_rek`).val(no_rek)
            $(`#${identifier} #modal_jenis_kredit`).val(jenis_kredit)
            $(`#${identifier} #modal_premi`).val(formatPremi)
            $(`#${identifier} #modal_tarif`).val(tarif)
            $(`#${identifier} #modal_fee`).val(formatFee)
            $(`#${identifier} #modal_premi_disetor`).val(formatPremiDisetor)
        })


        function formatRupiah(angka) {
            var reverse = angka.toString().split('').reverse().join('');
            var ribuan = reverse.match(/\d{1,3}/g);
            var formatted = ribuan.join('.').split('').reverse().join('');
            return formatted;
        }
    </script>
@endpush
