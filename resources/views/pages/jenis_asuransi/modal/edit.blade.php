<div class="layout-form hidden" id="edit-jenis-asuransi">
    <div class="head-form p-4 border-b">
        <h2>EDIT JENIS ASURANSI</h2>
    </div>
    <form id="modal-edit-form">
        <input type="hidden" name="id" id="edit-id">
        <div class="p-4 space-y-8 mt-8">
            <div class="input-box space-y-3">
                <label for="" class="uppercase">Jenis Kredit</label>
                <select name="jenis-kredit" id="edit-jenis-kredit" class="w-full p-2 border select-jenis-kredit edit-jenis-kredit">
                    <option value="">-- Pilih Jenis Kredit ---</option>
                    <option value="PKPJ">PKPJ</option>
                    <option value="KKB">KKB</option>
                    <option value="Talangan Umroh">Talangan Umroh</option>
                    <option value="Prokesra">Prokesra</option>
                    <option value="Kusuma">Kusuma</option>
                </select>
                <small class="form-text text-red-600 error"></small>
            </div>
            <div class="input-box space-y-3">
                <label for="" class="uppercase appearance-none">Jenis</label>
                <input type="text" class="p-2 w-full border" id="edit-jenis" name="jenis" required />
            </div>
            <button class="bg-theme-primary px-8 rounded text-white py-2" id="edit-button">
                Simpan
            </button>
            <button data-dismiss-id="edit-jenis-asuransi" type="button"
                class="bg-white ml-2 px-8 rounded text-theme-text border py-2">
                Batal
            </button>
        </div>
    </form>
</div>