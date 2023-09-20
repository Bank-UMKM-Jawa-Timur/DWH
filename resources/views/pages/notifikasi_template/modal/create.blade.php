<div class="layout-form hidden" id="add-template-notifikasi">
    <div class="head-form p-4 border-b">
        <h2>TAMBAH TEMPLATE NOTIFIKASI</h2>
    </div>
    <form action="" method="">
        <div class="p-4 space-y-8 mt-8">
            <div class="input-box space-y-3">
                <label for="" class="uppercase">Aksi</label>
                <select name="action" id="add-action" class="w-full p-2 border select-action add-action">
                    <option selected>-- Pilih Aksi ---</option>
                    @foreach ($actions as $action)
                        <option value="{{ $action->id }}">{{ $action->name }}</option>
                    @endforeach
                </select>
                <small class="form-text text-red-600 error"></small>
            </div>
            <div class="input-box space-y-3">
                <label for="" class="uppercase appearance-none">Judul</label>
                <input type="text" class="p-2 w-full border add-title" id="add-title" name="title"/>
                <small class="form-text text-red-600 error"></small>
            </div>

            <div class="input-box space-y-3">
                <label for="" class="uppercase">Konten</label>
                <input type="text" class="p-2 w-full border add-content" id="add-content" name="content"/>
                <small class="form-text text-red-600 error"></small>
            </div>

            <div class="input-box space-y-3">
                <label for="" class="uppercase">Role Peran</label>
                <div>
                    <select name="role" id="add-role" multiple="multiple"
                        class="w-full p-2 border select-role block add-role">
                        <option value="">---Pilih Role / Peran---</option>
                        <option value="0">Semua</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                    <small class="form-text text-red-600 error"></small>
                </div>
            </div>
            <button type="submit" class="bg-theme-primary px-8 rounded text-white py-2"
                id="add-button">
                Simpan
            </button>
            <button data-dismiss-id="add-template-notifikasi" type="button"
                class="bg-white ml-2 px-8 rounded text-theme-text border py-2">
                Batal
            </button>
        </div>
    </form>
</div>
