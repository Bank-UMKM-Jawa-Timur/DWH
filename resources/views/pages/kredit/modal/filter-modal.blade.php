<div class="layout-form hidden" id="filter-kkb">
    <div class="head-form p-4 border-b">
        <h2>FILTER DATA KKB</h2>
    </div>
    <form action="" method="">
        <div class="p-4 space-y-8 mt-8">
            <div class="input-box space-y-3">
                <label for="" class="uppercase appearance-none">Tanggal Awal</label>
                <input type="date" class="p-2 w-full border" id="tAwal" name="tAwal" value="{{ Request()->tAkhir }}" />
            </div>
<<<<<<< HEAD
            <div class="input-box space-y-3">
                <label for="" class="uppercase">Tanggal Akhir</label>
                <input type="date" class="p-2 w-full border" id="tAkhir" name="tAkhir" value="{{ Request()->tAwal }}" />
            </div>
            <div class="input-box space-y-3">
                <label for="" class="uppercase">Status</label>
                <select name="status" class="w-full p-2 border" id="status">
=======
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
                        @if (\Session::get(config('global.role_id_session')) == 4 || \Session::get(config('global.role_id_session')) == 1)
                            <div class="col-sm-6 mt-2">
                                <h5 class="modal-title penyerahan-unit-title">Cabang</h5>
                                <select class="custom-select form-control" id="cabang" name="cabang">
                                </select>
                            </div>
                        @endif
>>>>>>> develop

                    @if (Request()->status == 'in progress')
                        <option value="in progress" selected>process</option>
                    @elseif(Request()->status == 'done')
                        <option value="done" selected>Done</option>
                    @else
                        <option selected>-- Pilih Status ---</option>
                    @endif
                </select>
            </div>
            @if (Auth::user()->role_id == 4 || Auth::user()->role_id == 1)
                <div class="input-box space-y-3">
                    <label for="" class="uppercase">Cabang</label>
                    <select name="cabang" class="w-full p-2 border" id="cabang">
                        <option selected>-- Pilih Cabang ---</option>
                    </select>
                </div>
            @endif
            <button class="bg-theme-primary px-8 rounded text-white py-2">
                Filter
            </button>
            <button data-dismiss-id="filter-kkb" type="button"
                class="bg-white ml-2 px-8 rounded text-theme-text border py-2">
                Batal
            </button>
        </div>
    </form>
</div>
{{-- 
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
                        $("#cabang").append(`<option value="" selected>Semua</option>`);
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
@endpush --}}
