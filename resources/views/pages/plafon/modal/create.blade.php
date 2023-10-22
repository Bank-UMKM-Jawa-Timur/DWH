<div class="layout-form hidden" id="add-plafon">
    <div class="head-form p-4 border-b">
        <h2>TAMBAH RATE PREMI PLAFON</h2>
    </div>
    <form id="modal-add-form">
        <div class="p-4 space-y-8 mt-8">
            <div class="input-box space-y-3">
                <label for="" class="uppercase appearance-none">Masa Asuransi(Bulan)</label>
                <input type="text" class="p-2 w-full border" id="add-masa-asuransi1" name="masa_asuransi1" 
                    required/>
            </div>
            <div class="input-box space-y-3">
                <label for="" class="uppercase appearance-none">Sampai dengan</label>
                <input type="text" class="p-2 w-full border" id="add-masa-asuransi2" name="masa_asuransi2" 
                    required/>
            </div>
            <div class="input-box space-y-3">
                <label for="" class="uppercase appearance-none">Jenis</label>
                <input type="text" class="p-2 w-full border" id="add-jenis" name="jenis" 
                    readonly value="plafon" required/>
            </div>
            <div class="input-box space-y-3">
                <label for="" class="uppercase appearance-none">Rate</label>
                <input type="text" class="p-2 w-full border" id="add-rate" name="rate" required />
            </div>
            <button class="bg-theme-primary px-8 rounded text-white py-2"
                id="simpanButton">
                Simpan
            </button>
            <button data-dismiss-id="add-plafon" type="button"
                class="bg-white ml-2 px-8 rounded text-theme-text border py-2">
                Batal
            </button>
        </div>
    </form>
</div>