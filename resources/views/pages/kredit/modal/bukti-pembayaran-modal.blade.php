<div class="modal fade" id="previewBuktiPembayaranModal" tabindex="-1" role="dialog" aria-labelledby="previewBuktiPembayaranModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title" id="previewBuktiPembayaranModalLabel">Bukti Pembayaran</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="text-light">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="tanggal_pembayaran"></p>
                <p id="status_confirm"></p>
                <iframe id="bukti_pembayaran_img"
                    src="" class="mt-2"
                    width="100%" height="500"></iframe>
            </div>
        </div>
    </div>
</div>
@push('extraScript')
<script>
    $('.bukti-pembayaran-modal').on('click', function(e) {
        const file = $(this).data('file');
        const status = $(this).data('confirm') ? 'Sudah dikonfirmasi oleh vendor.' : 'Menunggu konfirmasi dari vendor.';
        const tanggal = $(this).data('tanggal');
        var path_file = "{{ asset('storage') }}" + "/dokumentasi-bukti-pembayaran/" + file + "#toolbar=0";

        $('#bukti_pembayaran_img').attr('src', path_file)
        $('#tanggal_pembayaran').html('Tanggal : ' + tanggal)
        $('#status_confirm').html('Status : ' + status)
    })
</script>
@endpush