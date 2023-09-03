<div class="layout-form hidden" id="add-imbal-jasa">
    <div class="head-form p-4 border-b">
        <h2>TAMBAH IMBAL JASA</h2>
    </div>
    <form action="" method="">
        <div class="p-4 space-y-8 mt-8">
            <div class="space-y-4">
                <div class="input-box space-y-3">
                    <label for="" class="uppercase appearance-none">PLAFOND</label>
                    <input type="text" class="p-2 w-full border" />
                </div>
                <div class="input-box space-y-3">
                    <label for="" class="uppercase appearance-none">S/D</label>
                    <input type="text" class="p-2 w-full border" />
                </div>
            </div>
            <div class="flex gap-3">
                <div class="input-box space-y-3">
                    <label for="" class="uppercase appearance-none">Tenor</label>
                    <input type="text" disabled value="12 Bulan" class="p-2 w-full border" />
                    <input type="text" disabled value="24 Bulan" class="p-2 w-full border" />
                    <input type="text" disabled value="36 Bulan" class="p-2 w-full border" />
                </div>
                <div class="input-box space-y-3">
                    <label for="" class="uppercase appearance-none">Imbal Jasa</label>
                    <input type="text" class="p-2 w-full border" />
                    <input type="text" class="p-2 w-full border" />
                    <input type="text" class="p-2 w-full border" />
                </div>
            </div>

            <button class="bg-theme-primary px-8 rounded text-white py-2">
                Simpan
            </button>
            <button type="button" data-dismiss-id="add-imbal-jasa"
                class="bg-white ml-2 px-8 rounded text-theme-text border py-2 close-form-edit">
                Batal
            </button>
        </div>
    </form>
</div>
