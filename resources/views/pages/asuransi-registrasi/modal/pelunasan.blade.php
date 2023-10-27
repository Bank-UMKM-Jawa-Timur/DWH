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
        <form id="form-pelunasan" action="{{ route('asuransi.registrasi.pelunasan') }}" method="post">
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
                    <label for="" class="uppercase">No Rekening</label>
                    <input type="text" class="disabled-input bg-disabled p-2 w-full border "
                        id="modal_no_rek" name="no_rek" readonly/>
                    <small class="form-text text-red-600 error"></small>
                </div>
                <div class="input-box space-y-3 mt-3">
                    <label for="" class="uppercase">No Polis</label>
                    <input type="text" class="disabled-input bg-disabled p-2 w-full border "
                        id="modal_no_polis" name="no_polis" readonly/>
                    <small class="form-text text-red-600 error"></small>
                </div>
                <div class="input-box space-y-3 mt-3">
                    <label for="" class="uppercase">Refund</label>
                    <input type="text" class="disabled-input bg-disabled p-2 w-full border "
                        id="modal_refund" name="refund" readonly/>
                    <small class="form-text text-red-600 error"></small>
                </div>
                <div class="input-box space-y-3 mt-3">
                    <label for="" class="uppercase">Tanggal Pelunasan<span class="text-theme-primary">*</span></label>
                    <input type="hidden" id="modal_tgl_awal" name="tgl_awal">
                    <input type="hidden" id="modal_tgl_akhir" name="tgl_akhir">
                    <div class="flex border justify-center tgl-pelunasan-box">
                        <div class="flex justify-center p-2 "><span>@include('components.svg.calendar')</span></div>
                        <input type="text" class="datepicker p-2 w-full border" id="modal_tgl_lunas" name="tgl_lunas" />
                    </div>
                    <small class="form-text text-red-600 error tgl-lunas-error"></small>
                </div>
                <div class="input-box space-y-3 mt-3">
                    <label for="" class="uppercase">Sisa Jangka Waktu</label>
                    <input type="text" class="disabled-input bg-disabled p-2 w-full border "
                        id="modal_sisa_jangka_waktu" name="sisa_jangka_waktu" readonly/>
                    <small class="form-text text-red-600 error"></small>
                </div>
            </div>
            <div class="modal-footer">
                <button data-dismiss-id="modalPelunasan" class="border px-7 py-3 text-black rounded">
                    Tutup
                </button>
                <button type="submit" class="bg-theme-primary px-7 py-3 text-white rounded" id="btn-submit">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

@push('extraScript')
    <script>
        $('.modal-pelunasan').on('click', function() {
            const identifier = 'modalPelunasan'
            const token = generateCsrfToken()
            const id = $(this).data('id');
            const no_aplikasi = $(this).data('no_aplikasi');
            const no_rek = $(this).data('no_rek');
            const no_polis = $(this).data('no_polis');
            const refund = $(this).data('refund');
            const tgl_awal = $(this).data('tgl_awal');
            const tgl_akhir = $(this).data('tgl_akhir');

            $(`#${identifier}`).removeClass("hidden");
            $(".layout-overlay-edit-form").removeClass("hidden");
            
            $(`#${identifier} #modal_token`).val(token)
            $(`#${identifier} #modal_id`).val(id)
            $(`#${identifier} #modal_no_aplikasi`).val(no_aplikasi)
            $(`#${identifier} #modal_no_rek`).val(no_rek)
            $(`#${identifier} #modal_no_polis`).val(no_polis)
            $(`#${identifier} #modal_refund`).val(refund)
            $(`#${identifier} #modal_tgl_awal`).val(tgl_awal)
            $(`#${identifier} #modal_tgl_akhir`).val(tgl_akhir)
        })

        $('#modal_tgl_lunas').on('change', function() {
            var tgl_lunas = $(this).val()
            var date = tgl_lunas.split('-')
            const tgl_lunas_formated = `${date[2]}-${date[1]}-${date[0]}`
            const tgl_awal = $('#modal_tgl_awal').val()
            const tgl_akhir = $('#modal_tgl_akhir').val()
            
            var date_lunas = new Date(tgl_lunas_formated)
            var date_awal = new Date(tgl_awal)
            var date_akhir = new Date(tgl_akhir)

            var date_dif_awal_akhir = monthDiff(date_awal, date_akhir)
            var date_dif_lunas_akhir = monthDiff(date_lunas, date_akhir)

            $(`#modalPelunasan #modal_sisa_jangka_waktu`).val(`${date_dif_lunas_akhir} bulan`)
        })

        $('#btn-submit').on('click', function(e) {
            e.preventDefault()
            const identifier = 'modalPelunasan'

            var tgl_lunas = $('#modal_tgl_lunas').val()
            if (tgl_lunas != 'dd/mm/yyyy') {
                $(`#${identifier} .tgl-pelunasan-box`).removeClass('border-2 border-rose-600')
                $(`#${identifier} .tgl-lunas-error`).html('')
                $('#form-pelunasan').submit()
            }
            else {
                $(`#${identifier} .tgl-pelunasan-box`).addClass('border-2 border-rose-600')
                $(`#${identifier} .tgl-lunas-error`).html('Harap pilih tanggal lunas')
            }
        })
    </script>
@endpush