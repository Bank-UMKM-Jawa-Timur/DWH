<div class="layout-form hidden" id="add-form-asuransi">
  <div class="head-form p-4 border-b">
      <h2>TAMBAH MASTER FORM ASURANSI</h2>
  </div>
  <form id="modal-add-form">
      <div class="p-4 space-y-8 mt-8">
          <div class="input-box space-y-3">
              <label for="" class="uppercase">Perusahaan Asuransi</label>
              <select name="perusahaan_id" id="add-perusahaan_id" class="w-full p-2 border select-action add-action">
                  <option value="">-- Pilih Perusahaan --</option>
                  @foreach ($data_perusahaan as $item)
                    <option value="{{ $item->id }}">{{ $item->nama }}</option>
                  @endforeach
              </select>
              <small class="form-text text-red-600 error"></small>
          </div>
          <div class="input-box space-y-3">
              <label for="" class="uppercase">Item Asuransi</label>
              <select name="form_item_asuransi_id" id="add-form_item_asuransi_id" class="w-full p-2 border select-action add-action">
                  <option value="">-- Pilih Item Asuransi --</option>
                  @foreach ($data_item as $item)
                    <option value="{{ $item->id }}">{{ $item->label }}</option>
                  @endforeach
              </select>
              <small class="form-text text-red-600 error"></small>
          </div>
          <button class="bg-theme-primary px-8 rounded text-white py-2"
              id="simpanButton">
              Simpan
          </button>
          <button data-dismiss-id="add-form-asuransi" type="button"
              class="bg-white ml-2 px-8 rounded text-theme-text border py-2">
              Batal
          </button>
      </div>
  </form>
</div>