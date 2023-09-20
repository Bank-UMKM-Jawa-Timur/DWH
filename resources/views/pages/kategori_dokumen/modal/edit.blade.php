<div class="layout-form hidden" id="edit-kd">
    <div class="head-form p-4 border-b">
        <h2>EDIT KATEGORI DOKUMEN</h2>
    </div>
    <form id="modal-edit-form" class="edit-form">
        <div class="p-4 space-y-8 mt-8">
            <div class="input-box space-y-3">
                <input type="hidden" name="edit_id" id="edit-id">
                <label for="" class="uppercase appearance-none">NAMA KATEGORI DOKUMEN</label>
                <input type="text" class="p-2 w-full border edit-name" id="edit-name" name="name" value="{{ old('name') }}"/>
            </div>
            <button class="bg-theme-primary px-8 rounded text-white py-2" id="edit-button">
                Simpan
            </button>
            <button data-dismiss-id="edit-kd" type="button"
                class="bg-white ml-2 px-8 rounded text-theme-text border py-2">
                Batal
            </button>
        </div>
    </form>
</div>