<div class="layout-form hidden" id="add-vendor">
    <div class="head-form p-4 border-b">
        <h2>TAMBAH VENDOR</h2>
    </div>
    <form id="modal-add-form">
        <div class="p-4 space-y-8 mt-8">
            <div class="input-box space-y-3">
                <label for="" class="uppercase appearance-none">Nama</label>
                <input type="text" class="p-2 w-full border" id="add-name" name="nama" 
                    required/>
            </div>
            <div class="input-box space-y-3">
                <label for="" class="uppercase appearance-none">Alamat</label>
                <textarea class="block w-full border" id="add-address" rows="3" required cols="30" rows="10"></textarea>
            </div>
            <div class="input-box space-y-3">
                <label for="" class="uppercase appearance-none">Nomor HP</label>
                <input type="text" class="p-2 w-full border" id="add-phone" name="phone" required />
            </div>
            <div class="input-box space-y-3">
                <label for="" class="uppercase appearance-none">Email</label>
                <input type="email" class="p-2 w-full border" id="add-email" name="email" required />
            </div>
            <button class="bg-theme-primary px-8 rounded text-white py-2"
                id="simpanButton">
                Simpan
            </button>
            <button data-dismiss-id="add-vendor" type="button"
                class="bg-white ml-2 px-8 rounded text-theme-text border py-2">
                Batal
            </button>
        </div>
    </form>
</div>