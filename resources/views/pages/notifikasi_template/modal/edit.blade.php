<div class="layout-form hidden" id="edit-template-notifikasi">
    <div class="head-form p-4 border-b">
        <h2>EDIT TEMPLATE NOTIFIKASI</h2>
    </div>
    <form action="" method="">
        <div class="p-4 space-y-8 mt-8">
            <div class="input-box space-y-3">
                <label for="" class="uppercase">Aksi</label>
                <select name="" class="w-full p-2 border" id="">
                    <option selected>-- Pilih Aksi ---</option>
                </select>
            </div>
            <div class="input-box space-y-3">
                <label for="" class="uppercase appearance-none">Judul</label>
                <input type="text" class="p-2 w-full border" />
            </div>
      
            <div class="input-box space-y-3">
                <label for="" class="uppercase">Konten</label>
                <input type="text" class="p-2 w-full border" />
            </div>
         
            <div class="input-box space-y-3">
                <label for="" class="uppercase">Role Peran</label>
                <select name="role[]" multiple="multiple" class="w-full p-2 border select-role" id="">
                    <option selected>-- Pilih Role ---</option>
                </select>
            </div>
            <button type="submit" class="bg-theme-primary px-8 rounded text-white py-2">
                Simpan
            </button>
            <button data-dismiss-id="edit-template-notifikasi" type="button"
                class="bg-white ml-2 px-8 rounded text-theme-text border py-2">
                Batal
            </button>
        </div>
    </form>
</div>