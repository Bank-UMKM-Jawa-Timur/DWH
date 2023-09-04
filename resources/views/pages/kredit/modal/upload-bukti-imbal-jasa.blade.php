<div class="modal-overlay hidden" id="modalUploadImbalJasa">
    <div class="modal modal-tab">
        <div class="modal-head text-gray-500 text-lg">
            <div class="title-modal">Bukti Transfer</div>
            <button class="close-modal" data-dismiss-id="modalUploadImbalJasa">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2" d="M17 7L7 17M7 7l10 10" />
                </svg>
            </button>
        </div>
        <form id="modal-imbal-jasa-form">
            @csrf
            <input type="hidden" name="id_kkbimbaljasa" id="id_kkbimbaljasa">
            <div class="modal-body">
                <div class="input-box space-y-3">
                    <div class="p-5 space-y-4">
                        <label for="" class="uppercase">Upload Bukti Transfer Imbal Jasa</label>
                        <br>
                        <span class="text-red-500 m-0">Maksimal 2mb.</span>
                        <input type="file" class="p-2 w-full border bg-gray-100" accept="image/*" id="file_imbal_jasa"
                        name="file_imbal_jasa" required />
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button data-dismiss-id="modalUploadImbalJasa" class="border px-7 py-3 text-black rounded">
                    Batal
                </button>
                <button type="submit" class="bg-theme-primary px-7 py-3 text-white rounded">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@push('extraScript')
    <script>
        $(".toggle-modal").on("click", function () {
            const targetId = $(this).data("target-id");
            $("#" + targetId).removeClass("hidden");
            $(".layout-overlay-edit-form").removeClass("hidden");
            const data_id = $(this).data('id')
            $('#id_kkbimbaljasa').val(data_id)
        });

        $("[data-dismiss-id]").on("click", function () {
            const dismissId = $(this).data("dismiss-id");
            $("#" + dismissId).addClass("hidden");
            $(".layout-overlay-edit-form").addClass("hidden");
        });

        $('#modal-imbal-jasa-form').submit(function(e) {
            Swal.fire({
                title: 'Memuat...',
                html: 'Silahkan tunggu...',
                allowEscapeKey: false,
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading()
                }
            });
            e.preventDefault()
            const req_id = document.getElementById('id_kkbimbaljasa')
            const req_file = document.getElementById('file_imbal_jasa')
            var formData = new FormData($(this)[0]);
            $.ajax({
                type: "POST",
                url: "{{ route('kredit.upload_imbal_jasa') }}",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    Swal.close()
                    if (Array.isArray(data.error)) {
                        console.log(data.error)
                        ErrorMessage('Gagal')
                        /*for (var i = 0; i < data.error.length; i++) {
                            var message = data.error[i];
                            if (message.toLowerCase().includes('no_bpkb'))
                                showError(req_date, message)
                            if (message.toLowerCase().includes('bpkb_scan'))
                                showError(req_image, message)
                        }*/
                    } else {
                        if (data.status == 'success') {
                            SuccessMessage(data.message);
                        } else {
                            ErrorMessage(data.message)
                        }
                    }
                },
                error: function(e) {
                    Swal.close()
                    console.log(e)
                    // ErrorMessage('Terjadi kesalahan')
                }
            });
        });
    </script>
@endpush