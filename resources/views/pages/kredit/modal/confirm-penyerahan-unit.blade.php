<div class="modal fade" id="confirmModalPenyerahanUnit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title penyerahan-unit-title">Konfirmasi Penyerahan Unit</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="text-light">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-6">
                        <h5 class="title-po">Tanggal : </h5>
                        <input type="text" class="form-control text-field" id="tanggal_penyerahan_unit" readonly>
                        <h5 class="title-po">Tanggal Konfirmasi : </h5>
                        <input type="text" class="form-control text-field" id="tanggal_confirm_penyerahan_unit"
                            readonly>
                        <h5 class="title-po">Status : </h5>
                        <input type="text" class="form-control text-field" id="status_confirm_penyerahan_unit"
                            readonly>
                    </div>
                    <div class="col-sm-6">
                        <h5 class="title-po">Foto Penyerahan Unit : </h5>
                        <div class="form-inline mt-1 show-pdf">
                            <img id="preview_penyerahan_unit" width="100%" height="500px">
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer mt-2">
                <div class="form-inline form-confim">
                    <button data-dismiss="modal" class="btn btn-danger mr-2">Tidak</button>
                    <form id="confirm-form-penyerahan-unit">
                        <input type="hidden" name="confirm_id" id="confirm_id">
                        <input type="hidden" name="confirm_id_category" id="confirm_id_category">
                        <button type="submit" class="btn btn-primary">Ya</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('extraScript')
    <script>
        $('.confirm-penyerahan-unit').on('click', function(e) {
            const data_id = $(this).data('id-doc')
            const data_category_doc_id = $(this).data('id-category')
            const is_confirm = $(this).data('confirm');
            const status = $(this).data('confirm') ? 'Sudah dikonfirmasi oleh cabang.' :
                'Belum dikonfirmasi cabang.';
            const tanggal = $(this).data('tanggal');
            const confirm_at = $(this).data('confirm_at');
            const file_bukti = $(this).data('file') ? $(this).data('file') : ''
            var path_file = "{{ asset('storage') }}" + "/dokumentasi-peyerahan/" + file_bukti;

            $("#preview_penyerahan_unit").attr("src", path_file);
            $('#confirm_id').val(data_id)
            $('#confirm_id_category').val(data_category_doc_id)
            $('#tanggal_penyerahan_unit').val(tanggal)
            $('#tanggal_confirm_penyerahan_unit').val(confirm_at)
            $('#status_confirm_penyerahan_unit').val(status)
            if (is_confirm) {
                $('.form-confim').css('display', 'none');
                $('.penyerahan-unit-title').html('Penyerahan Unit');
            }
        })
    </script>
@endpush
