{{--  <div class="modal hidden" id="addModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah {{ $pageTitle }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="modal-add-form">
                    <div class="form-group name">
                        <label for="add-name">Nama Peran</label>
                        <input type="text" class="form-control add-name" id="add-name" name="name">
                        <small class="form-text text-danger error"></small>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary" id="add-button">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>  --}}
<div class="layout-form hidden" id="add-layout-form">
    <div class="head-form p-4 border-b">
        <h2>TAMBAH ROLE / PERAN</h2>
    </div>
    <form id="modal-add-form">
        <div class="p-4 space-y-8 mt-8">
            <div class="input-box space-y-3">
                <label for="" class="uppercase appearance-none">NAMA PERAN</label>
                <input type="text" class="p-2 w-full border add-name" id="add-name" name="name" />
            </div>
            <button
                id="add-button"
                class="bg-theme-primary px-8 rounded text-white py-2">
                Simpan
            </button>
            <button data-dismiss-id="add-layout-form" type="button" class="bg-white ml-2 px-8 rounded text-theme-text border py-2">
                Batal
            </button>
        </div>
    </form>
</div>
