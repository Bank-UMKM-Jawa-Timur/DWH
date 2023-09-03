{{--  <div class="modal fade" id="detailPO" tabindex="-1" aria-labelledby="detailPOLabel" aria-hidden="true">
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
</div>  --}}
<div class="modal-overlay hidden font-lexend overflow-auto" id="modalPO">
    <div class="modal modal-tab">
        <div class="modal-head text-gray-500 text-lg">
            <div class="title-modal">PO</div>
            <button data-dismiss-id="modalPO">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2" d="M17 7L7 17M7 7l10 10" />
                </svg>
            </button>
        </div>

        <div class="modal-body">
            <div class="container mx-auto border-b-2">
                <div class="p-4">
                    <div class="gap-5 space-y-5">
                        <div class="flex gap-5 w-full mt-0">
                            <div class="input-box w-full space-y-3">
                                <label for="" class="uppercase appearance-none">Nomor PO</label>
                                <input type="text" disabled class="p-2 w-full border" id="nomorPo"  />
                            </div>
                            <div class="input-box w-full space-y-3">
                                <label for="" class="uppercase appearance-none">Tanggal PO</label>
                                <input type="text" disabled class="p-2 w-full border" id="tanggalPo" />
                            </div>
                        </div>

                        <div class="space-y-3">
                            <label for="" class="uppercase appearance-none">File PO</label>
                            <div class="h-[528px] w-full bg-gray-100">
                                <iframe id="filepo" src="" class="mt-2" width="100%" height="500"></iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- <div class="modal-footer"></div> -->
    </div>
</div>

@push('extraScript')
    <script>
        $(".toggle-modal").on("click", function () {
            const targetId = $(this).data("target-id");
            $("#" + targetId).removeClass("hidden");
            $(".layout-overlay-edit-form").removeClass("hidden");
            var nomorPo = $(this).data('nomorpo');
            var tanggalPo = $(this).data('tanggalpo');
            var filePo = $(this).data('filepo') + "#navpanes=0";

            var functionPrint = 'PrintPdfPO("' + $(this).data('filepo') + '")';
            $("#nomorPo").val(nomorPo);
            $("#tanggalPo").val(tanggalPo);
            $("#filepo").attr("src", filePo);
        });

        $("[data-dismiss-id]").on("click", function () {
            const dismissId = $(this).data("dismiss-id");
            $("#" + dismissId).addClass("hidden");
            $(".layout-overlay-edit-form").addClass("hidden");
        });
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
