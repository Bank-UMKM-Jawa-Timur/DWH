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
                <div class="row container">
                    <div class="col-sm-6">
                        <h5 class="title-po">Nomor PO</h5>
                        <b class="content-po" id="nomorPo">undifined</b>
                    </div>
                    <div class="col-sm-6">
                        <h5 class="title-po">Tanggal PO</h5>
                        <b class="content-po" id="tanggalPo">undifined</b>
                    </div>
                    <div class="col-sm-12 mt-4">
                        <h5 class="title-po">File PO</h5>
                        <div class="form-inline mt-1">
                            <button type="button" class="btn btn-primary mr-1 btn-sm">Unduh File PO</button>
                            <button onclick="printPDF()" class="btn btn-info btn-sm" id="printfile">Print File
                                PO</button>
                            <iframe id="filepo"
                                src="" class="mt-2"
                                width="100%" height="500"></iframe>
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
            var filePo = $(this).data('filepo') + "#toolbar=0";
            console.log("file : "+filePo)
            $("#nomorPo").text(nomorPo);
            $("#tanggalPo").text(tanggalPo);
            $("#filepo").attr("src", filePo);
        });
    </script>
@endpush