<div class="modal-overlay hidden" id="modalUploadImbalJasa">
    <div class="modal modal-tab">
        <div class="modal-head text-gray-500 text-lg">
            <div class="title-modal">Bukti Transfer Imbal Jasa</div>
            <button class="close-modal" data-dismiss-id="modalUploadImbalJasa">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2" d="M17 7L7 17M7 7l10 10" />
                </svg>
            </button>
        </div>
        <form id="modal-imbal-jasa-form">
            <input type="hidden" name="_token" id="_token">
            <input type="hidden" name="id_kkbimbaljasa" id="id_kkbimbaljasa">
            <div class="modal-body">
                <div class="input-box">
                    <div class="px-8 pt-6">
                        <label for="" class="uppercase">Nominal yang harus dibayarkan</label>
                        <br>
                        <input type="text"  class="p-2 mt-2 w-full border bg-gray-100" name="nominal_imbal_jasa"
                            id="nominal_imbal_jasa" disabled/>
                    </div>
                </div>
                <div class="input-box">
                    <div class="px-8 py-6">
                        <label for="" class="uppercase">Upload Bukti Transfer Imbal Jasa</label>
                        <br>
                        <span class="text-red-500 m-0">Maksimal 2mb.</span>
                        <input type="file" class="p-2 mt-2 w-full border bg-gray-100" accept="image/*" id="file_imbal_jasa"
                        name="file_imbal_jasa" />
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
        function UploadImbalJasaSuccessMessage(message) {
            Swal.fire({
                showConfirmButton: true,
                timer: 3000,
                closeOnClickOutside: true,
                title: 'Berhasil',
                text: message,
                icon: 'success',
            }).then((result) => {
                $("#modalUploadImbalJasa").addClass("hidden");
                $('#preload-data').removeClass("hidden")
                
                refreshTable()
                //location.reload();
            })
        }
        
        function UploadImbalJasaErrorMessage(message) {
            Swal.fire({
                showConfirmButton: false,
                timer: 3000,
                closeOnClickOutside: true,
                title: 'Gagal',
                message: message,
                icon: 'error',
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#preload-data').removeClass("hidden")
                    
                    refreshTable()
                    //location.reload();
                }
            })
        }

        $("[data-dismiss-id]").on("click", function () {
            const dismissId = $(this).data("dismiss-id");
            $("#" + dismissId).addClass("hidden");
            $(".layout-overlay-edit-form").addClass("hidden");
        });

        $('#modal-imbal-jasa-form').submit(function(e) {
            Swal.fire({
                showConfirmButton: false,
                closeOnClickOutside: false,
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
                        Swal.fire({
                            showConfirmButton: false,
                            timer: 3000,
                            closeOnClickOutside: true,
                            title: 'Gagal',
                            text: 'Harap lengkapi form terlebih dahulu',
                            icon: 'error',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $('#preload-data').removeClass("hidden")
                                
                                refreshTable()
                                //location.reload();
                            }
                        })
                        for (var i = 0; i < data.error.length; i++) {
                            var message = data.error[i];
                            if (message.toLowerCase().includes('no_bpkb'))
                                showError(req_date, message)
                            if (message.toLowerCase().includes('bpkb_scan'))
                                showError(req_image, message)
                        }
                    } else {
                        if (data.status == 'success') {
                            UploadImbalJasaSuccessMessage(data.message);
                        } else {
                            UploadImbalJasaErrorMessage(data.message)
                        }
                    }
                },
                error: function(e) {
                    Swal.close()
                    console.log(e)
                    UploadImbalJasaErrorMessage('Terjadi kesalahan')
                }
            });
        });
    </script>
@endpush