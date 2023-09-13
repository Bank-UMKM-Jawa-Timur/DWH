<div class="layout-form hidden" id="edit-imbal-jasa">
    <div class="head-form p-4 border-b">
        <h2>EDIT IMBAL JASA</h2>
    </div>
    <form class="edit-form">
        <input type="hidden" name="edit_id" id="edit-id">
        <div class="p-4 space-y-8 mt-8">
            <div class="space-y-4">
                <div class="input-box space-y-3">
                    <label for="" class="uppercase appearance-none">PLAFOND</label>
                    <input type="text" class="p-2 w-full border edit-plafond1"
                        id="edit-plafond1" name="plafond1" required/>
                </div>
                <div class="input-box space-y-3">
                    <label for="" class="uppercase appearance-none">S/D</label>
                    <input type="text" class="p-2 w-full border edit-plafond2"
                        id="edit-plafond2" name="plafond2" required/>
                </div>
            </div>
            <div class="flex gap-3">
                <div class="input-box space-y-3">
                    <label for="" class="uppercase appearance-none">Tenor (Bulan)</label>
                    <input type="hidden" class="edit-id12" id="edit-id12" name="idtenor[]">
                    <input type="hidden" class="edit-id24" id="edit-id24" name="idtenor[]">
                    <input type="hidden" class="edit-id36" id="edit-id36" name="idtenor[]">
                    <input type="text" readonly value="12" class="p-2 w-full border edit-tenor12"
                        id="edit-tenor12" name="tenor[]"/>
                    <input type="text" readonly value="24" class="p-2 w-full border edit-tenor24"
                        id="edit-tenor24" name="tenor[]"/>
                    <input type="text" readonly value="36" class="p-2 w-full border edit-tenor36"
                        id="edit-tenor36" name="tenor[]"/>
                </div>
                <div class="input-box space-y-3">
                    <label for="" class="uppercase appearance-none">Imbal Jasa</label>
                    <input type="text" class="p-2 w-full border edit-imbal-jasa12"
                        id="edit-imbal-jasa12" name="imbaljasa[]" value="" required/>
                    <input type="text" class="p-2 w-full border edit-imbal-jasa24"
                        id="edit-imbal-jasa24" name="imbaljasa[]" value="" required/>
                    <input type="text" class="p-2 w-full border edit-imbal-jasa36"
                        id="edit-imbal-jasa36" name="imbaljasa[]" value="" required/>
                </div>
            </div>

            <button class="bg-theme-primary px-8 rounded text-white py-2"
                id="edit-button">
                Simpan
            </button>
            <button type="button" data-dismiss-id="edit-imbal-jasa"
                class="bg-white ml-2 px-8 rounded text-theme-text border py-2 close-form-edit">
                Batal
            </button>
        </div>
    </form>
</div>
