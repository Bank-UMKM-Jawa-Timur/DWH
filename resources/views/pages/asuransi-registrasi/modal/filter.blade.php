<div class="layout-form hidden" id="filter">
    <div class="head-form p-4 border-b">
        <h2>FILTER DATA KKB</h2>
    </div>
    <form id="form-filter" method="get">
        <div class="p-4 space-y-8 mt-8">
            <div class="input-box space-y-3">
                <label for="" class="uppercase appearance-none">Tanggal Awal</label>
                <input type="text"  class="date1-picker p-2 w-full border"
                    id="tAwal" name="tAwal" value="{{ Request()->tAwal }}" required/>
            </div>
            <div class="input-box space-y-3">
                <label for="" class="uppercase">Tanggal Akhir</label>
                <input type="text" class="date2-picker p-2 w-full border"
                    id="tAkhir" name="tAkhir" value="{{ Request()->tAkhir }}" required/>
            </div>
            <div class="input-box space-y-3">
                <label for="" class="uppercase">Status</label>
                <select name="status" class="w-full p-2 border" id="status">
                    <option value="onprogress" @if(Request()->status == 'onprogress') selected @endif>Onprogres</option>
                    <option value="canceled" @if(Request()->status == 'canceled') selected @endif>Dibatalkan</option>
                </select>
            </div>
            @if (\Session::get(config('global.role_id_session')) == 4 || \Session::get(config('global.role_id_session')) == 1)
                <div class="input-box space-y-3">
                    <label for="" class="uppercase">Cabang</label>
                    <select name="cabang" class="w-full p-2 border" id="cabang">
                        <option value="">-- Pilih Cabang ---</option>
                        @isset($dataCabang)
                            @foreach ($dataCabang as $item)
                                <option {{ Request()->cabang == $item['kode_cabang'] ? 'selected' : '' }} value="{{ $item['kode_cabang'] }}">{{ $item['kode_cabang'] }} - {{ $item['cabang'] }}</option>
                            @endforeach
                        @endisset
                    </select>
                </div>
            @endif
            <button class="bg-theme-primary px-8 rounded text-white py-2">
                Filter
            </button>
            <button data-dismiss-id="filter" type="button"
                class="bg-white ml-2 px-8 rounded text-theme-text border py-2">
                Batal
            </button>
        </div>
    </form>
</div>
@push('extraScript')
    <script>
        $('.date1-picker').datepicker({
            dateFormat: 'dd-mm-yy'
        }).val("dd/mm/yyyy");
        $('.date2-picker').datepicker({
            dateFormat: 'dd-mm-yy'
        }).val("dd/mm/yyyy");

        const date1 = "{{Request()->tAwal}}"
        const date2 = "{{Request()->tAkhir}}"
        if (date1 != "dd/mm/yyyy") {
            $('.date1-picker').datepicker({
                dateFormat: 'dd-mm-yy'
            }).val(date1.replaceAll('-', '/'));
        }
        if (date2 != "dd/mm/yyyy") {
            $('.date2-picker').datepicker({
                dateFormat: 'dd-mm-yy'
            }).val(date2.replaceAll('-', '/'));
        }
    </script>
@endpush
