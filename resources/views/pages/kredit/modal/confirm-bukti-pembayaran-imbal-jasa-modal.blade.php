<div class="modal-overlay hidden font-lexend overflow-auto" id="confirmImbalJasa">
    <div class="modal modal-tab">
        <div class="modal-head text-gray-500 text-lg">
            <div class="title-modal">
                Konfirmasi Bukti Imbal Jasa
                <p class="text-sm text-gray-400 font-normal">
                    Apakah yakin ingin mengonfirmasi data ini?
                </p>
            </div>
            <button data-dismiss-id="confirmImbalJasa">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" hu viewBox="0 0 24 24">
                    <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2" d="M17 7L7 17M7 7l10 10" />
                </svg>
            </button>
        </div>

        <form id="confirm-form-imbal-jasa">
            <input type="hidden" name="id_cat" id="id_cat">
            <div class="modal-body">
                <div class="gap-5 space-y-5 p-5">
                    <div class="flex gap-8">
                        <div class="input-box w-full space-y-3">
                            <label for="" class="uppercase appearance-none">Tanggal Upload</label>
                            <input type="text" disabled class="p-2 w-full border" id="tgl_upload_imbal_jasa"/>
                        </div>
                    </div>
                    <div class="input-box w-full space-y-3">
                        <label for="" class="uppercase appearance-none">Status Konfirmasi</label>
                        <input type="text" disabled class="p-2 w-full border" id="status_konfirmasi_imbal_jasa" />
                    </div>
                    <div class="space-y-3">
                        <div class="h-[528px] w-full bg-gray-100">
                            <img id="preview_imbal_jasa" src="" width="100%">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss-id="confirmImbalJasa"
                    class="bg-white px-7 py-3 text-gray-400 border rounded">
                    Tidak
                </button>
                <button type="submit" class="bg-theme-primary px-7 py-3 text-white rounded">
                    Iya
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
            const tanggal = $(this).data('tanggal')
            const is_confirm = $(this).data('confirm')
            const confirm = $(this).data('confirm') ? 'Sudah dikonfirmasi' : 'Belum dikonfirmasi'
            const file_bukti = $(this).data('file') ? $(this).data('file') : ''
            var path_file = "{{ asset('storage') }}" + "/dokumentasi-imbal-jasa/" + file_bukti;

            $("#preview_imbal_jasa").attr("src", path_file);
            $('#id_cat').val(data_id)
            $('#tgl_upload_imbal_jasa').val(tanggal)
            $('#status_konfirmasi_imbal_jasa').val(tanggal)

            if (is_confirm) {
                $('#confirmImbalJasa .title-modal').html('Bukti Imbal Jasa')
                $('#confirmImbalJasa .modal-footer').css('display', 'none')
            }
        });

        $("[data-dismiss-id]").on("click", function () {
            const dismissId = $(this).data("dismiss-id");
            $("#" + dismissId).addClass("hidden");
            $(".layout-overlay-edit-form").addClass("hidden");
        });

        $('#confirm-form-imbal-jasa').on('submit', function(e) {
            e.preventDefault()
            const req_id = $('#id_cat').val()

            $.ajax({
                type: "POST",
                url: "{{ route('kredit.confirm-imbal-jasa') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: req_id,
                },
                success: function(data) {
                    if (Array.isArray(data.error)) {
                        console.log(data.error)
                        ErrorMessage('Gagal')
                        /*for (var i = 0; i < data.error.length; i++) {
                            var message = data.error[i];
                            if (message.toLowerCase().includes('id'))
                                showError(req_id, message)
                            if (message.toLowerCase().includes('category_id'))
                                showError(req_category_doc_id, message)
                        }*/
                    } else {
                        if (data.status == 'success') {
                            SuccessMessage(data.message);
                        } else {
                            ErrorMessage(data.message)
                        }
                    }
                }
            })
        })
    </script>
@endpush