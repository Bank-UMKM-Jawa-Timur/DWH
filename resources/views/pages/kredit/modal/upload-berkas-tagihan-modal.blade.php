<div class="modal-overlay hidden" id="modalUploadBerkasTagihan">
    <div class="modal modal-tab">
        <div class="modal-head text-gray-500 text-lg">
            <div class="title-modal">Upload Berkas Tagihan</div>
            <button class="close-modal" data-dismiss-id="modalUploadBerkasTagihan">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2" d="M17 7L7 17M7 7l10 10" />
                </svg>
            </button>
        </div>
        <form id="modal-tagihan" enctype="multipart/form-data">
            <input type="hidden" name="_token" id="_token">
            <input type="hidden" name="id_kkb" id="id_kkb">
            <div class="modal-body">
                <div class="input-box space-y-3">
                    <div class="p-5 space-y-4">
                        <label for="" class="uppercase">Scan Berkas Tagihan(Pdf)</label>
                        <input type="file" class="p-2 w-full border bg-gray-100" id="tagihan_scan"
                        name="tagihan_scan" accept="application/pdf" />
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button data-dismiss-id="modalUploadBerkasTagihan" class="border px-7 py-3 text-black rounded">
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
        function UploadBerkasTagihanSuccessMessage(message) {
            Swal.fire({
                showConfirmButton: true,
                timer: 3000,
                closeOnClickOutside: true,
                title: 'Berhasil',
                icon: 'success',
            }).then((result) => {
                $("#modalUploadBerkasTagihan").addClass("hidden");
                //$('#preload-data').removeClass("hidden")
                
                refreshTable()
            })
        }
        
        function UploadBerkasTagihanErrorMessage(message) {
            Swal.fire({
                showConfirmButton: false,
                timer: 3000,
                closeOnClickOutside: true,
                title: 'Gagal',
                icon: 'error',
            }).then((result) => {
                if (result.isConfirmed) {
                    //$('#preload-data').removeClass("hidden")
                    
                    refreshTable()
                }
            })
        }

        /*$(".toggle-modal-upload-bukti-pembayaran").on("click", function () {
            const targetId = $(this).data("target-id");
            $("#" + targetId).removeClass("hidden");
            $(".layout-overlay-edit-form").removeClass("hidden");

            var id = $(this).data('id_kkb');

            $("#modal-bukti-pembayaran").find('#id_kkb').val(id);
        });*/

        $('#modal-tagihan').on("submit", function(e) {
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
            e.preventDefault();

            const req_id = document.getElementById('id_kkb')
            const req_file = document.getElementById('tagihan_scan')
            var formData = new FormData($(this)[0]);

            $.ajax({
                type: "POST",
                url: "{{ route('kredit.upload_tagihan') }}",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    Swal.close()
                    console.log(data)
                    if (Array.isArray(data.error)) {
                        for (var i = 0; i < data.error.length; i++) {
                            var message = data.error[i];
                            console.log(message)
                            /*if (message.toLowerCase().includes('bukti_pembayaran_scan'))
                                showError(req_image, message)
                            */
                        }
                    } else {
                        if (data.status == 'success') {
                            UploadBerkasTagihanSuccessMessage(data.message);
                        } else {
                            UploadBerkasTagihanErrorMessage(data.message)
                        }
                    }
                },
                error: function(e) {
                    Swal.close()
                    console.log(e)
                    UploadBerkasTagihanErrorMessage('Terjadi kesalahan')
                }
            })
        })

    </script>
@endpush