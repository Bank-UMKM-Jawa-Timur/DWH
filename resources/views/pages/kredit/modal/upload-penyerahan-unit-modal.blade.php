<div class="modal-overlay hidden" id="modalUploadBuktiPenyerahanUnit">
    <div class="modal modal-tab">
        <div class="modal-head text-gray-500 text-lg">
            <div class="title-modal">Penyerahan Unit</div>
            <button class="close-modal" data-dismiss-id="modalUploadBuktiPenyerahanUnit">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2" d="M17 7L7 17M7 7l10 10" />
                </svg>
            </button>
        </div>
        <form id="modal-tgl-penyerahan" enctype="multipart/form-data">
            <input type="hidden" name="_token" id="_token">
            <input type="hidden" name="id_kkb" id="id_kkb">
            <div class="modal-body">
                <p class="uppercase appearance-none" id="kategori_data"></p>
                <div class="input-box space-y-3">
                    <div class="p-5 space-y-4">
                        <label for="" class="uppercase">Tanggal Pengiriman</label>
                        <input type="text" class="datepicker p-2 w-full border bg-gray-100"
                            id="tgl_pengiriman" name="tgl_pengiriman"/>
                    </div>
                </div>
                <div class="input-box space-y-3">
                    <div class="p-5 space-y-4">
                        <label for="" class="uppercase">Foto Bukti Penyerahan Unit</label>
                        <input type="file" class="p-2 w-full border bg-gray-100"
                            id="upload_penyerahan_unit"
                            name="upload_penyerahan_unit" accept="image/png, image/jpeg, image/jpeg"/>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button data-dismiss-id="modalUploadBuktiPenyerahanUnit" class="border px-7 py-3 text-black rounded">
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
        function UploadBuktiPenyerahanUnitSuccessMessage(message) {
            Swal.fire({
                showConfirmButton: true,
                timer: 3000,
                closeOnClickOutside: true,
                title: 'Berhasil',
                icon: 'success',
            }).then((result) => {
                $("#modalUploadBuktiPenyerahanUnit").addClass("hidden");
                //$('#preload-data').removeClass("hidden")

                refreshTable()
            })
        }

        function UploadBuktiPenyerahanUnitErrorMessage(message) {
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

        /*$(".toggle-modal").on("click", function () {
            const targetId = $(this).data("target-id");
            $("#" + targetId).removeClass("hidden");
            $(".layout-overlay-edit-form").removeClass("hidden");

            const id = $(this).data('id_kkb');

            $('#modalUploadBuktiPenyerahanUnit #id_kkb').val(id)
        });*/

        $("[data-dismiss-id]").on("click", function () {
            const dismissId = $(this).data("dismiss-id");
            $("#" + dismissId).addClass("hidden");
            $(".layout-overlay-edit-form").addClass("hidden");
        });

        $('#modal-tgl-penyerahan').on("submit", function(event) {
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
            event.preventDefault();

            const req_id = document.getElementById('id_kkb')
            const req_date = document.getElementById('tgl_pengiriman')
            const req_image = document.getElementById('upload_penyerahan_unit')
            var formData = new FormData($(this)[0]);

            $.ajax({
                type: "POST",
                url: "{{ route('kredit.set_tgl_penyerahan_unit') }}",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    Swal.close()
                    if (Array.isArray(data.error)) {
                        for (var i = 0; i < data.error.length; i++) {
                            var message = data.error[i];
                            console.log(message)
                            /*if (message.toLowerCase().includes('tanggal'))
                                UploadBuktiPenyerahanUnitErrorMessage(message)
                            if (message.toLowerCase().includes('gambar'))
                                UploadBuktiPenyerahanUnitErrorMessage(message)
                            */
                        }
                    } else {
                        if (data.status == 'success') {
                            UploadBuktiPenyerahanUnitSuccessMessage(data.message);
                            //alert(data.message)
                        } else {
                            UploadBuktiPenyerahanUnitErrorMessage(data.message)
                        }
                    }
                },
                error: function(e) {
                    Swal.close()
                    console.log(e)
                    UploadBuktiPenyerahanUnitErrorMessage('Terjadi kesalahan')
                }
            })
        })
    </script>
@endpush
