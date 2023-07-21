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
                        @if (Auth::user()->role_id == 4 || Auth::user()->role_id == 1)
                            <div class="col-sm-6 mt-2">
                                <h5 class="modal-title penyerahan-unit-title">Cabang</h5>
                                <select class="custom-select form-control" id="cabang" name="cabang">
                                </select>
                            </div>
                        @endif

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

        $(document).ready(function() {
            $("#buttonFilter").on("click", function() {
                $.ajax({
                    type: "GET",
                    url: "{{ env('LOS_API_HOST') }}/kkb/get-cabang",
                    headers: {
                        'token': "{{ env('LOS_API_TOKEN') }}",
                    },
                    success: function(data) {
                        for (i in data) {
                            if (data[i].kode_cabang == `{{ Request()->cabang }}`)
                                $("#cabang").append(`<option value="` +
                                    data[i].kode_cabang +
                                    `" selected>` + data[i].cabang +
                                    `</option>`);
                            else
                                $("#cabang").append(`<option value="` +
                                    data[i].kode_cabang + `">` + data[i]
                                    .cabang +
                                    `</option>`);
                        }
                    }
                });
            });
        });
    </script>
@endpush
