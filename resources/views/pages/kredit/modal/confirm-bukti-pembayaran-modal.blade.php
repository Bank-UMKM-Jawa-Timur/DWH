<div class="modal-overlay hidden" id="modalConfirmBuktiPembayaran">
    <div class="modal modal-tab">
        <div class="modal-head text-gray-500 text-lg">
            <div class="title-modal">Bukti Pembayaran</div>
            <button class="close-modal" data-dismiss-id="modalConfirmBuktiPembayaran">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2" d="M17 7L7 17M7 7l10 10" />
                </svg>
            </button>
        </div>
        <div class="modal-body">
            <div class="p-4">
                <div class="gap-5 space-y-5">
                    <div class="flex gap-5 w-full mt-0">
                        <div class="input-box w-full space-y-3">
                            <label for="" class="uppercase appearance-none">Tanggal Upload</label>
                            <input type="text" disabled class="p-2 w-full border" id="confirm_tanggal_pembayaran"  />
                        </div>
                    </div>
                    <div class="space-y-3">
                        <label for="" class="uppercase appearance-none">Bukti Pembayaran</label>
                        <div class="h-[528px] w-full bg-gray-100">
                            <iframe id="confirm_bukti_pembayaran_img" src="" class="mt-2" width="100%" height="500"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <form id="confirm-form-vendor">
                <input type="hidden" name="confirm_id" id="confirm_id">
                <input type="hidden" name="confirm_id_category" id="confirm_id_category">
                <button type="button" data-dismiss-id="modalConfirmBuktiPembayaran" class="border px-7 py-3 text-black rounded">
                    Tidak
                </button>
                <button type="submit" class="bg-theme-primary px-7 py-3 text-white rounded">
                    Ya
                </button>
            </form>
        </div>
    </div>
</div>
@push('extraScript')
    <script>
        function ConfirmPembayaranSuccessMessage(message) {
            Swal.fire({
                showConfirmButton: true,
                timer: 3000,
                closeOnClickOutside: true,
                title: 'Berhasil',
                icon: 'success',
            }).then((result) => {
                console.log('then')
                $("#modalConfirmBuktiPembayaran").addClass("hidden");
                $('#preload-data').removeClass("hidden")
                
                refreshTable()
            })
        }
        
        function ConfirmPembayaranErrorMessage(message) {
            Swal.fire({
                showConfirmButton: false,
                timer: 3000,
                closeOnClickOutside: true,
                title: 'Gagal',
                icon: 'error',
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#preload-data').removeClass("hidden")
                    
                    refreshTable()
                }
            })
        }

        /*$(".toggle-modal-confirm-bukti-pembayaran").on("click", function () {
            const targetId = $(this).data("target-id");
            $("#" + targetId).removeClass("hidden");
            $(".layout-overlay-edit-form").removeClass("hidden");

            const confirm_id = $(this).data('id-doc')
            const is_confirm = $(this).data('confirm')
            const confirm_category_id = $(this).data('id-category')
            const file = $(this).data('file');
            const status = $(this).data('confirm') ? 'Sudah dikonfirmasi oleh vendor.' :
                'Menunggu konfirmasi dari vendor.';
            const tanggal = $(this).data('tanggal');
            var path_file = "{{ asset('storage') }}" + "/dokumentasi-bukti-pembayaran/" + file + "#navpanes=0";

            $('#confirm_bukti_pembayaran_img').attr('src', path_file)
            $('#confirm_tanggal_pembayaran').val(tanggal)
            $('#status_confirm').val(status)
            $('#confirm_id').val(confirm_id)
            $('#confirm_id_category').val(confirm_category_id)

            if (is_confirm) {
                $('#modalConfirmBuktiPembayaran .modal-footer').css('display', 'none')
            }
        });*/

        $("[data-dismiss-id]").on("click", function () {
            const dismissId = $(this).data("dismiss-id");
            $("#" + dismissId).addClass("hidden");
            $(".layout-overlay-edit-form").addClass("hidden");
        });

        $('#confirm-form-vendor').on('submit', function(e) {
            e.preventDefault()
            const req_id = $('#confirm_id').val()
            const req_category_doc_id = $('#confirm_id_category').val()

            $.ajax({
                type: "POST",
                url: "{{ route('kredit.confirm_document_vendor') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: req_id,
                    category_id: req_category_doc_id
                },
                success: function(data) {
                    if (Array.isArray(data.error)) {
                        console.log(data.error)
                    } else {
                        if (data.status == 'success') {
                            ConfirmPembayaranSuccessMessage(data.message);
                        } else {
                            ConfirmPembayaranErrorMessage(data.message)
                        }
                    }
                },
                error: function(e) {
                    ConfirmPembayaranErrorMessage(e)
                }
            })
        })
    </script>
@endpush
