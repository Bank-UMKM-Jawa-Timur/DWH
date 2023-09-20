<div class="layout-form hidden" id="edit-vendor">
    <div class="head-form p-4 border-b">
        <h2>EDIT VENDOR</h2>
    </div>
    <form id="modal-edit-form">
        <input type="hidden" name="id" id="edit-id">
        <div class="p-4 space-y-8 mt-8">
            <div class="input-box space-y-3">
                <label for="" class="uppercase appearance-none">Nama</label>
                <input type="text" class="p-2 w-full border" id="edit-name"
                    name="nama" required/>
            </div>
            <div class="input-box space-y-3">
                <label for="" class="uppercase appearance-none">Alamat</label>
                <textarea class="block w-full border" name="" id="edit-address" cols="30" rows="10"></textarea>
            </div>
            <div class="input-box space-y-3">
                <label for="" class="uppercase appearance-none">Nomor HP</label>
                <input type="text" class="p-2 w-full border" name="phone" id="edit-phone"
                    required />
            </div>
            <div class="input-box space-y-3">
                <label for="" class="uppercase appearance-none">Email</label>
                <input type="text" class="p-2 w-full border"  id="edit-email" name="email"
                required/>
            </div>
            <div class="input-box space-y-3">
                <label for="" class="uppercase appearance-none">Password</label>
                <input type="password" class="p-2 w-full border" id="edit-password" name="password" />
            </div>
            <button class="bg-theme-primary px-8 rounded text-white py-2" id="edit-button">
                Simpan
            </button>
            <button data-dismiss-id="edit-vendor" type="button"
                class="bg-white ml-2 px-8 rounded text-theme-text border py-2">
                Batal
            </button>
        </div>
    </form>
</div>