<div class="layout-form hidden" id="edit-template-notifikasi">
    <div class="head-form p-4 border-b">
        <h2>EDIT TEMPLATE NOTIFIKASI</h2>
    </div>
    <form class="edit-form">
        <input type="hidden" name="edit_id" id="edit-id">
        <div class="p-4 space-y-8 mt-8">
            <div class="input-box space-y-3">
                <label for="edit-action" class="uppercase">Aksi</label>
                <select name="action" id="edit-action" class="w-full p-2 select-action border edit-action">
                    <option value="">-- Pilih Aksi ---</option>
                    @foreach ($actions as $action)
                        <option value="{{ $action->id }}">{{ $action->name }}</option>
                    @endforeach
                </select>
                <small class="form-text text-red-600 error"></small>
            </div>
            <div class="input-box space-y-3">
                <label for="edit-title" class="uppercase appearance-none">Judul</label>
                <input type="text" class="p-2 w-full border edit-title" id="edit-title" name="title"/>
                <small class="form-text text-red-600 error"></small>
            </div>

            <div class="input-box space-y-3">
                <label for="" class="uppercase">Konten</label>
                <input type="text" class="p-2 w-full border edit-content" id="edit-content" name="content"/>
                <small class="form-text text-red-600 error"></small>
            </div>

            <div class="input-box space-y-3">
                <label for="" class="uppercase">Role Peran</label>
                <select name="role" id="edit-role" multiple="multiple" class="w-full p-2 border select-role edit-role">
                    <option value="">-- Pilih Role ---</option>
                    <option value="0">Semua</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                    @endforeach
                </select>
                <small class="form-text text-red-600 error"></small>
            </div>
            <button type="submit" class="bg-theme-primary px-8 rounded text-white py-2"
                id="edit-button">
                Simpan
            </button>
            <button data-dismiss-id="edit-template-notifikasi" type="button"
                class="bg-white ml-2 px-8 rounded text-theme-text border py-2">
                Batal
            </button>
        </div>
    </form>
</div>
