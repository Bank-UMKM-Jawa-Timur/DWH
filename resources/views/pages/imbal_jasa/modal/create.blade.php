<div class="layout-form hidden" id="add-imbal-jasa">
    <div class="head-form p-4 border-b">
        <h2>TAMBAH IMBAL JASA</h2>
    </div>
    <form action="" method="">
        <div class="p-4 space-y-8 mt-8">
            <div class="space-y-4">
                <div class="input-box space-y-3">
                    <label for="" class="uppercase appearance-none">PLAFOND</label>
                    <input type="text" class="p-2 w-full border add-plafond1" id="add-plafond1"
                        name="plafond1" required/>
                </div>
                <div class="input-box space-y-3">
                    <label for="" class="uppercase appearance-none">S/D</label>
                    <input type="text" class="p-2 w-full border add-plafond2" id="add-plafond2"
                    name="plafond2" style="margin-top:7px;" required/>
                </div>
            </div>
            <div class="flex gap-3">
                <div class="input-box space-y-3">
                    <label for="" class="uppercase appearance-none">Tenor (Bulan)</label>
                    <input type="text" readonly value="12" class="p-2 w-full border add-tenor12" id="add-tenor12"
                        name="tenor[]" value="12"/>
                    <input type="text" readonly value="24" class="p-2 w-full border add-tenor24" id="add-tenor24"
                        name="tenor[]" value="24"/>
                    <input type="text" readonly value="36" class="p-2 w-full border add-tenor36" id="add-tenor36"
                        name="tenor[]" value="36"/>
                </div>
                <div class="input-box space-y-3">
                    <label for="" class="uppercase appearance-none">Imbal Jasa</label>
                    <input type="text" class="p-2 w-full border add-imbal-jasa"
                        id="add-imbal-jasa12" name="imbaljasa[]" required/>
                    <input type="text" class="p-2 w-full border add-imbal-jasa"
                        id="add-imbal-jasa24" name="imbaljasa[]" required/>
                    <input type="text" class="p-2 w-full border add-imbal-jasa"
                        id="add-imbal-jasa36" name="imbaljasa[]" required/>
                </div>
            </div>

            <button class="bg-theme-primary px-8 rounded text-white py-2"
                id="add-button">
                Simpan
            </button>
            <button type="button" data-dismiss-id="add-imbal-jasa"
                class="bg-white ml-2 px-8 rounded text-theme-text border py-2 close-form-edit">
                Batal
            </button>
        </div>
    </form>
</div>
