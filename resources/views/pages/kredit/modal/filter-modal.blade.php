<!-- Modal -->
<div class="modal fade" id="filter" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
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
                            <input type="date" class="form-control" name="tAwal" value="{{ Request()->tAkhir }}"
                                required>
                        </div>
                        <div class="col-sm-6">
                            <h5 class="modal-title penyerahan-unit-title">Tanggal Akhir</h5>
                            <input type="date" class="form-control" name="tAkhir" value="{{ Request()->tAwal }}"
                                required>
                        </div>
                        <div class="col-sm-6 mt-2">
                            <h5 class="modal-title penyerahan-unit-title">Status</h5>
                            <select class="custom-select form-control" name="status">
                                <option value="" selected>Pilih Status...</option>
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
