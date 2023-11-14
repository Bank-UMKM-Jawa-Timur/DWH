<div class="layout-form hidden" id="filter-dashboard">
    <div class="head-form p-4 border-b">
        <h2>FILTER DATA</h2>
    </div>
    <form action="" method="">
        <div class="p-4 space-y-8 mt-8">
            <div class="input-box space-y-3">
                <label for="" class="uppercase appearance-none">Tanggal Awal</label>
                <input type="text"  class="datepicker p-2 w-full border" id="tAwals" name="tAwal" value="{{ Request()->tAwal != null ? Request()->tAwal : 'dd/mm/yyyy' }}" required/>
            </div>
            <div class="input-box space-y-3">
                <label for="" class="uppercase">Tanggal Akhir</label>
                <input type="text" class="datepicker p-2 w-full border" id="tAkhirs" name="tAkhir" value="{{ Request()->tAkhir != null ? Request()->tAkhir: 'dd/mm/yyyy' }}" required/>
                <small id="errorTakhirModal" class="hidden form-text text-primary">Tanggal akhir tidak boleh kurang dari tanggal awal</small>
            </div>
            <div class="input-box space-y-3">
                <label for="" class="uppercase">Cabang</label>
                <select name="cabang" class="w-full p-2 border" id="cabang">
                    <option value="" selected>-- Pilih Cabang --</option>
                    @if ($dataCabang)
                        @foreach ($dataCabang as $item)
                            <option value="{{$item['kode_cabang']}}">{{$item['kode_cabang']}} - {{$item['cabang']}}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            <button class="bg-theme-primary px-8 rounded text-white py-2">
                Filter
            </button>
            <button data-dismiss-id="filter-dashboard" type="button"
                class="bg-white ml-2 px-8 rounded text-theme-text border py-2">
                Batal
            </button>
        </div>
    </form>
</div>

@push('extraScript')
    <script>
        $("#tAwal").on("change", function() {
            var result = $(this).val();
            if (result != null) {
                $("#tAkhir").prop("required", true)
            }
        });

        $("#tAkhirs").on("change", function() {
            var tAkhir = $(this).val();
            var tAwal = $("#tAwals").val();
            if (Date.parse(tAkhir) < Date.parse(tAwal)) {
                $("#tAkhirs").val('');
                $("#errorTakhirModal").removeClass("hidden");
        }})
    </script>
@endpush
