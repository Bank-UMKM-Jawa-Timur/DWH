<!-- Modal -->
<div class="modal fade" id="filter" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Filter Data KKB</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="" method="get">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <h5 class="modal-title penyerahan-unit-title">Tanggal Awal</h5>
                            <input type="date" class="form-control" id="tAwal" name="tAwal"
                                value="{{ Request()->tAkhir }}">
                        </div>
                        <div class="col-sm-6">
                            <h5 class="modal-title penyerahan-unit-title">Tanggal Akhir</h5>
                            <input type="date" class="form-control" id="tAkhir" name="tAkhir"
                                value="{{ Request()->tAwal }}">
                        </div>
                        <div class="col-sm-6 mt-2">
                            <h5 class="modal-title penyerahan-unit-title">Status</h5>
                            <select class="custom-select form-control" name="status">
                                @if (Request()->status == 'in progress')
                                    <option value="in progress" selected>process</option>
                                @elseif(Request()->status == 'done')
                                    <option value="done" selected>Done</option>
                                @else
                                    <option value="" selected disabled>Pilih Status...</option>
                                @endif

                                <option value="in progress">process</option>
                                <option value="done">Done</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('extraScript')
    <script>
        $("#tAwal").on("change", function() {
            var result = $(this).val();
            if (result != null) {
                $("#tAkhir").prop("required", true)
            }
        });
    </script>
@endpush
