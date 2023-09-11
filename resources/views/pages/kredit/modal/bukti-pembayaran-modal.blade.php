<div class="modal-overlay hidden" id="modalBuktiPembayaran">
    <div class="modal modal-tab">
        <div class="modal-head text-gray-500 text-lg">
            <div class="title-modal">Bukti Pembayaran</div>
            <button class="close-modal" data-dismiss-id="modalBuktiPembayaran">
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
                            <label for="" class="uppercase appearance-none">Tanggal</label>
                            <input type="text" disabled class="p-2 w-full border" id="tanggal_pembayaran"  />
                        </div>
                        <div class="input-box w-full space-y-3">
                            <label for="" class="uppercase appearance-none">Tanggal Konfirmasi</label>
                            <input type="text" disabled class="p-2 w-full border" id="tanggal_confirm_pembayaran" />
                        </div>
                    </div>

                    <div class="space-y-3">
                        <label for="" class="uppercase appearance-none">Bukti Pembayaran</label>
                        <div class="h-[528px] w-full bg-gray-100">
                            <iframe id="bukti_pembayaran_img" src="" class="mt-2" width="100%" height="500"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('extraScript')
    <script>
        
        /*$(".toggle-modal-bukti-pembayaran").on("click", function () {
            const targetId = $(this).data("target-id");
            $("#" + targetId).removeClass("hidden");
            $(".layout-overlay-edit-form").removeClass("hidden");

            const file = $(this).data('file');
            const status = $(this).data('confirm') ? 'Sudah dikonfirmasi oleh vendor.' :
                'Menunggu konfirmasi dari vendor.';
            const tanggal = $(this).data('tanggal');
            const confirm_at = $(this).data('confirm_at');
            var path_file = "{{ asset('storage') }}" + "/dokumentasi-bukti-pembayaran/" + file + "#navpanes=0";

            $('#bukti_pembayaran_img').attr('src', path_file)
            $('#tanggal_pembayaran').val(tanggal)
            $('#tanggal_confirm_pembayaran').val(confirm_at)
            $('#status_confirm').val(status)
        });*/

        $("[data-dismiss-id]").on("click", function () {
            const dismissId = $(this).data("dismiss-id");
            $("#" + dismissId).addClass("hidden");
            $(".layout-overlay-edit-form").addClass("hidden");
        });

    </script>
@endpush
