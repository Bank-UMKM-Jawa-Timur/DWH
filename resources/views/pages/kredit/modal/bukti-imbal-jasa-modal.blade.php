<div class="modal fade" id="previewImbalJasaModal" tabindex="-1" role="dialog" aria-labelledby="previewImbalJasaModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title" id="previewImbalJasaModalLabel">Bukti Pembayaran Imbal Jasa</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="text-light">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-6">
                        <h5 class="title-po">Tanggal : </h5>
                        <input type="text" class="form-control text-field" id="tanggal_pembayaran_imbal_jasa"
                            readonly>
                        <h5 class="title-po">Tanggal Konfirmasi : </h5>
                        <input type="text" class="form-control text-field" id="tanggal_confirm_imbal_jasa" readonly>
                        <h5 class="title-po">Status : </h5>
                        <input type="text" class="form-control text-field" id="status_confirm_imbal_jasa" readonly>
                    </div>
                    <div class="col-sm-6">
                        <h5 class="title-po">Bukti Pembayaran Imbal Jasa : </h5>
                        <div class="form-inline mt-1 show-pdf">
                            <img id="bukti_pembayaran_imbal_jasa" src="" class="mt-2" width="100%">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('extraScript')
    <script>
        $('.bukti-pembayaran-modal').on('click', function(e) {
            const file = $(this).data('file');
            console.log(file);
            const status = $(this).data('confirm') ? 'Sudah dikonfirmasi oleh vendor.' :
                'Menunggu konfirmasi dari vendor.';
            const tanggal = $(this).data('tanggal');
            const confirm_at = $(this).data('confirm_at');
            var path_file = "{{ asset('storage') }}" + "/dokumentasi-imbal-jasa/" + file + "#navpanes=0";

            $('#bukti_pembayaran_imbal_jasa').attr('src', path_file)
            $('#tanggal_pembayaran_imbal_jasa').val(tanggal)
            $('#tanggal_confirm_imbal_jasa').val(confirm_at)
            $('#status_confirm_imbal_jasa').val(status)
        })
    </script>
@endpush
