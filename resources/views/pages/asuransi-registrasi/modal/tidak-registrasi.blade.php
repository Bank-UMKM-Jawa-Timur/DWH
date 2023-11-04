<div class="modal-overlay p-5 hidden" id="modalTidakRegister">
    <div class="modal modal-tab">
        <div class="modal-head text-gray-500 text-lg">
            <div class="title-modal">
                Tidak registrasi
            </div>
            <button class="close-modal" data-dismiss-id="modalTidakRegister">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2" d="M17 7L7 17M7 7l10 10" />
                </svg>
            </button>
        </div>
        <form id="form-tidak-registrasi" /*action="{{ route('asuransi.registrasi.not_register') }}" method="post"*/>
            <input type="hidden" name="_token" id="modal_token">
            <input type="hidden" name="id" id="modal_id_pengajuan">
            <input type="hidden" name="jenis_asuransi_id" id="modal_jenis_asuransi_id">
            <div class="modal-body p-5">
                <div class="input-box space-y-3">
                    <label for="" class="uppercase">No PK</label>
                    <input type="text" class="disabled-input bg-disabled p-2 w-full border "
                        id="modal_no_pk" name="no_pk" readonly/>
                    <small class="form-text text-red-600 error"></small>
                </div>
                <div class="input-box space-y-3 mt-3">
                    <label for="" class="uppercase">Nama Debitur</label>
                    <input type="text" class="disabled-input bg-disabled p-2 w-full border "
                        id="modal_debitur" name="debitur" readonly/>
                    <small class="form-text text-red-600 error"></small>
                </div>
                <div class="input-box space-y-3 mt-3">
                    <label for="" class="uppercase">Jenis Asuransi</label>
                    <input type="text" class="disabled-input bg-disabled p-2 w-full border "
                        id="modal_jenis_asuransi" name="jenis_asuransi" readonly/>
                    <small class="form-text text-red-600 error"></small>
                </div>
            </div>
            <div class="modal-footer">
                <button data-dismiss-id="modalTidakRegister" class="border px-7 py-3 text-black rounded" type="button">
                    Tutup
                </button>
                <button type="button" class="bg-theme-primary px-7 py-3 text-white rounded" id="btn-tidak-register">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

@push('extraScript')
    <script>
        $('.modal-tidak-register').on('click', function() {
            const identifier = 'modalTidakRegister'
            const token = generateCsrfToken()
            const id = $(this).data('id');
            const no_pk = $(this).data('no_pk');
            const debitur = $(this).data('debitur');
            const jenis_asuransi_id = $(this).data('jenis_asuransi_id');
            const jenis_asuransi = $(this).data('jenis_asuransi');

            $(`#${identifier}`).removeClass("hidden");
            $(".layout-overlay-edit-form").removeClass("hidden");
            
            $(`#${identifier} #modal_token`).val(token)
            $(`#${identifier} #modal_id_pengajuan`).val(id)
            $(`#${identifier} #modal_no_pk`).val(no_pk)
            $(`#${identifier} #modal_debitur`).val(debitur)
            $(`#${identifier} #modal_jenis_asuransi_id`).val(jenis_asuransi_id)
            $(`#${identifier} #modal_jenis_asuransi`).val(jenis_asuransi)
        })

        $('#modalTidakRegister #btn-tidak-register').on('click', function() {
            $('#preload-data').removeClass("hidden")
            var token = $('#modalTidakRegister #modal_token').val()
            var id_pengajuan = $('#modalTidakRegister #modal_id_pengajuan').val()
            var jenis_asuransi_id = $('#modalTidakRegister #modal_jenis_asuransi_id').val()
            var no_pk = $('#modalTidakRegister #modal_no_pk').val()
            var nama_debitur = $('#modalTidakRegister #modal_debitur').val()
            $.ajax({
                url: "{{ route('asuransi.registrasi.not_register') }}",
                type: "POST",
                accept: "Application/json",
                data: {
                    '_token': token,
                    'id_pengajuan': id_pengajuan,
                    'no_pk': no_pk,
                    'jenis_asuransi_id': jenis_asuransi_id,
                    'nama_debitur': nama_debitur,
                },
                success: function(response) {
                    $('#preload-data').addClass("hidden")
                    if (response.status == 'success') {
                        alertSuccess('Berhasil')
                    } else {
                        console.log(response.message)
                        alertWarning(response.message)
                    }
                },
                error: function(response) {
                    $('#preload-data').addClass("hidden")
                    alertWarning(response)
                }
            })
        })

        function alertSuccess(message) {
            Swal.fire({
                tittle: 'Success!',
                html: message,
                icon: 'success',
                iconColor: '#DC3545',
                confirmButtonText: 'Ok',
                confirmButtonColor: '#DC3545'
            }).then(() => {
                location.reload()
            })
        }

        function alertWarning(message) {
            Swal.fire({
                tittle: 'Warning!',
                html: message,
                icon: 'warning',
                iconColor: '#DC3545',
                confirmButtonText: 'Ok',
                confirmButtonColor: '#DC3545'
            })
        }

        function alertError(message) {
            Swal.fire({
                tittle: 'Error!',
                html: message,
                icon: 'error',
                iconColor: '#DC3545',
                confirmButtonText: 'Ok',
                confirmButtonColor: '#DC3545'
            })
        }
    </script>
@endpush