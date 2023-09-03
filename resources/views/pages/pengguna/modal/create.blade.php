<div class="layout-form hidden" id="add-pengguna">
    <div class="head-form p-4 border-b">
        <h2>TAMBAH PENGGUNA</h2>
    </div>
    <form action="" method="">
        <div class="p-4 space-y-8 mt-8">
            <div class="input-box space-y-3">
                <label for="" class="uppercase appearance-none">NIP</label>
                <input type="text" class="p-2 w-full border" id="add-nip" />
            </div>
            <div class="input-box space-y-3">
                <label for="" class="uppercase appearance-none">Email</label>
                <input type="text" class="p-2 w-full border" id="add-email" />
            </div>
            <div class="input-box space-y-3">
                <label for="" class="uppercase appearance-none">Password</label>
                <input type="password" class="p-2 w-full border" id="add-password" />
            </div>
            <div class="input-box space-y-3">
                <label for="add-role" class="uppercase">ROLE</label>
                <select name="" class="w-full p-2 border" id="add-role">
                    <option selected>-- Pilih Role ---</option>
                </select>
            </div>
            <button type="submit" id="add-button" class="bg-theme-primary px-8 rounded text-white py-2">
                Simpan
            </button>
            <button  data-dismiss-id="add-pengguna"  type="button" class="bg-white ml-2 px-8 rounded text-theme-text border py-2">
                Batal
            </button>
        </div>
    </form>
</div>

