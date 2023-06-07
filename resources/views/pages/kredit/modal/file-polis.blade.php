<div class="modal fade" id="detailPolis" tabindex="-1" role="dialog" aria-labelledby="previewBuktiPembayaranModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title" id="previewBuktiPembayaranModalLabel">File Polis</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="text-light">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-6">
                        <h5 class="title-po">Tanggal : </h5>
                        <b id="tanggal_polis" class="content-po"></b>
                    </div>
                    <div class="col-sm-6">
                        <h5 class="title-po">Tanggal Konfirmasi : </h5>
                        <b id="tanggal_confirm_polis" class="content-po"></b>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <h5 class="title-po">Status : </h5>
                        <b id="status_confirm_polis" class="content-po"></b>
                    </div>
                </div>
                <iframe id="filepolis" src="" class="mt-2" width="100%" height="500"></iframe>
            </div>
        </div>
    </div>
</div>
@push('extraScript')
    <script>
        $('.detailFilePolis').on('click', function(e) {
            const file = $(this).data('file');
            const status = $(this).data('confirm') ? 'Sudah dikonfirmasi.' :
                'Menunggu konfirmasi.';
            const tanggal = $(this).data('tanggal');
            const confirm_at = $(this).data('confirm_at');
            var path_file = "{{ asset('storage') }}" + "/dokumentasi-polis/" + file + "#toolbar=0";
            $('#filepolis').attr('src', path_file)
            $('#tanggal_polis').html(tanggal)
            $('#tanggal_confirm_polis').html(confirm_at)
            $('#status_confirm_polis').html(status)
        })
    </script>
@endpush
