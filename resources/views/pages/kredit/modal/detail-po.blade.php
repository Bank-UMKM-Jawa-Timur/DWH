<div class="modal fade" id="detailPO" tabindex="-1" aria-labelledby="detailPOLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title" id="detailPOLabel">Detail PO</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="text-light">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-6">
                        <h5 class="title-po">Nomor PO</h5>
                        <input type="text" class="form-control text-field" id="nomorPo" readonly>
                        {{-- <b class="content-po text-field">undifined</b> --}}
                        <h5 class="title-po">Tanggal PO</h5>
                        <input type="text" class="form-control text-field" id="tanggalPo" readonly>
                    </div>
                    <div class="col-sm-6">
                        <h5 class="title-po">File PO</h5>
                        <div class="form-inline mt-1 show-pdf">
                            <iframe id="filepo" src="" class="mt-2" width="100%" height="500"></iframe>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@push('extraScript')
    <script>
        $(document).on("click", ".open-po", function() {
            var nomorPo = $(this).data('nomorpo');
            var tanggalPo = $(this).data('tanggalpo');
            // var filePo = "https://www.africau.edu/images/default/sample.pdf#navpanes=0";
            var filePo = $(this).data('filepo') + "#navpanes=0";

            // example : https://www.africau.edu/images/default/sample.pdf

            var functionPrint = 'PrintPdfPO("' + $(this).data('filepo') + '")';
            $("#nomorPo").val(nomorPo);
            $("#tanggalPo").val(tanggalPo);
            $("#filepo").attr("src", filePo);
        });
    </script>
@endpush
