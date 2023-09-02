{{--  <div class="modal hidden" id="editModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit {{ $pageTitle }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="modal-edit-form" class="edit-form">
                    <input type="hidden" name="edit_id" id="edit-id">
                    <div class="form-group name">
                        <label for="edit-name">Nama Peran</label>
                        <input type="text" class="form-control edit-name" id="edit-name" name="name"
                            value="{{ old('name') }}">
                        <small class="form-text text-danger error"></small>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary" id="edit-button">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>  --}}
<div class="layout-form hidden" id="edit-layout-form">
    <div class="head-form p-4 border-b">
        <h2>Edit</h2>
    </div>
    <form id="edit-form">
        <input type="hidden" name="edit_id" id="edit-id">
        <div class="p-4 space-y-8 mt-8">
            <div class="input-box space-y-3">
                <label for="" class="uppercase appearance-none">NAMA PERAN</label>
                <input type="text" class="p-2 w-full border edit-name" id="edit-name" name="name" />
            </div>
            <button
                type="submit"
                id="edit-button"
                class="bg-theme-primary px-8 rounded text-white py-2">
                Simpan
            </button>
            <button id="form-close" data-form-id="edit-form" type="button" class="bg-white ml-2 px-8 rounded text-theme-text border py-2 close-form-edit">
                Batal
            </button>
        </div>
    </form>
</div>