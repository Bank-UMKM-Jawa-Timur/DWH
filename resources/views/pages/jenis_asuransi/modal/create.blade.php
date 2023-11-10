<div class="layout-form hidden" id="add-jenis-asuransi">
    <div class="head-form p-4 border-b">
        <h2>TAMBAH JENIS ASURANSI</h2>
    </div>
    <form id="modal-add-form">
        <div class="p-4 space-y-8 mt-8">
            {{-- <div class="input-box space-y-3">
                <label for="" class="uppercase appearance-none">Nama</label>
                <input type="text" class="p-2 w-full border" id="add-nama" name="nama" 
                    required/>
            </div> --}}
            <div class="input-box space-y-3">
                <label for="" class="uppercase">Jenis Kredit</label>
                <select name="jenis-kredit" id="add-jenis-kredit" class="w-full p-2 border select-action add-action">
                    <option value="" selected>-- Pilih Jenis Kredit --</option>
                    <option value="PKPJ">PKPJ</option>
                    <option value="KKB">KKB</option>
                    <option value="Talangan Umroh">Talangan Umroh</option>
                    <option value="Prokesra">Prokesra</option>
                    <option value="Kusuma">Kusuma</option>
                </select>
                <small class="form-text text-red-600 error"></small>
            </div>
            <div class="input-box space-y-3">
                <label for="" class="uppercase">Jenis</label>
                <div>
                    <select name="jenis" id="add-jenis" multiple="multiple"
                        class="w-full p-2 border select-jenis block add-jenis">
                        <option value="">---Pilih Jenis---</option>
                        <option value="1">Jaminan</option>
                        <option value="2">Jiwa</option>
                        <option value="3">Kredit(Penjaminan)</option>
                    </select>
                    <small class="form-text text-red-600 error"></small>
                </div>
            </div>
            {{-- <div class="input-box space-y-3">
                <label for="" class="uppercase appearance-none">Jenis</label>
                <input type="text" class="p-2 w-full border" id="add-jenis" name="jenis" required />
            </div> --}}
            <button class="bg-theme-primary px-8 rounded text-white py-2"
                id="simpanButton">
                Simpan
            </button>
            <button data-dismiss-id="add-jenis-asuransi" type="button"
                class="bg-white ml-2 px-8 rounded text-theme-text border py-2">
                Batal
            </button>
        </div>
    </form>
</div>