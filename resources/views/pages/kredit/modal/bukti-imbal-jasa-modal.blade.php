<div class="modal fade" id="previewImbalJasaModal" tabindex="-1" role="dialog" aria-labelledby="previewImbalJasaModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title" id="previewImbalJasaModalLabel">Bukti Pembayaran Imbal Jasa</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="text-light">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-4">
                        <h5 class="title-po">Tanggal : </h5>
                        <b id="tanggal_pembayaran_imbal_jasa" class="content-po"></b>
                    </div>
                    <div class="col-sm-8">
                        <h5 class="title-po">Status : </h5>
                        <b id="status_confirm_imbal_jasa" class="content-po"></b>
                    </div>
                </div>
                <img id="bukti_pembayaran_imbal_jasa" src="" class="mt-2" width="150px">
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
            var path_file = "{{ asset('storage') }}" + "/dokumentasi-imbal-jasa/" + file + "#toolbar=0";

            $('#bukti_pembayaran_imbal_jasa').attr('src', path_file)
            $('#tanggal_pembayaran_imbal_jasa').html(tanggal)
            $('#status_confirm_imbal_jasa').html(status)
        })
    </script>
@endpush
