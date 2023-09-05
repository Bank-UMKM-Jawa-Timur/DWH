
<div class="modal-overlay hidden font-lexend overflow-auto" id="modalConfirmPenyerahanUnit">
    <div class="modal modal-tab">
        <div class="modal-head text-gray-500 text-lg">
            <div class="title-modal">Konfirmasi Penyerahan Unit</div>
            <button data-dismiss-id="modalConfirmPenyerahanUnit">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" hu viewBox="0 0 24 24">
                    <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2" d="M17 7L7 17M7 7l10 10" />
                </svg>
            </button>
        </div>

        <div class="modal-body">
            <div class="gap-5 space-y-5 p-5">
                <div class="flex gap-5 w-full mt-2">
                    <div class="input-box w-full space-y-3">
                        <label for="" class="uppercase appearance-none">Tanggal
                        </label>
                        <input type="text" disabled class="p-2 w-full border" id="tanggal_penyerahan_unit" />
                    </div>
                    <div class="input-box w-full space-y-3">
                        <label for="" class="uppercase appearance-none">Tanggal Konfirmasi</label>
                        <input type="text" disabled class="p-2 w-full border" id="tanggal_confirm_penyerahan_unit" />
                    </div>
                </div>
                <div class="input-box w-full space-y-3">
                    <label for="" class="uppercase appearance-none">Status</label>
                    <input type="text" disabled class="p-2 w-full border" id="status_confirm_penyerahan_unit" />
                </div>

                <div class="space-y-3">
                    <label for="" class="uppercase appearance-none">Foto Penyerahan Unit</label>
                    <div class="h-[528px] w-full bg-gray-100">
                        <img id="preview_penyerahan_unit" width="100%" height="500px">
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer form-confirm">
            <form id="confirm-form-penyerahan-unit">
                <input type="hidden" name="confirm_id" id="confirm_id">
                <input type="hidden" name="confirm_id_category" id="confirm_id_category">
                <button type="button" data-dismiss-id="modalConfirmPenyerahanUnit" class="border px-7 py-3 text-black rounded">
                    Tidak
                </button>
                <button type="submit" class="bg-theme-primary px-7 py-3 text-white rounded">
                    Ya
                </button>
            </form>
        </div>
    </div>
</div>

@push('extraScript')
    <script>
        $(".toggle-modal").on("click", function () {
            const targetId = $(this).data("target-id");
            $("#" + targetId).removeClass("hidden");
            $(".layout-overlay-edit-form").removeClass("hidden");

            const id_kkb = $(this).data('id_kkb');
            const data_id = $(this).data('id-doc')
            const data_category_doc_id = $(this).data('id-category')
            const tanggal = $(this).data('tanggal');
            const is_confirm = $(this).data('confirm');
            const confirm_at = $(this).data('confirm_at');
            const id_doc = $(this).data('id-doc');
            const status = $(this).data('confirm') ? 'Sudah dikonfirmasi oleh cabang.' :
                'Belum dikonfirmasi cabang.';
            const file = $(this).data('file');
            var path_file = "{{ asset('storage') }}" + "/dokumentasi-peyerahan/" + file;

            $("#modalConfirmPenyerahanUnit #preview_penyerahan_unit").attr("src", path_file);
            $('#modalConfirmPenyerahanUnit #confirm_id').val(data_id)
            $('#modalConfirmPenyerahanUnit #confirm_id_category').val(data_category_doc_id)
            $('#modalConfirmPenyerahanUnit #status_confirm_penyerahan_unit').val(status)
            $('#modalConfirmPenyerahanUnit #tanggal_penyerahan_unit').val(tanggal)
            $('#modalConfirmPenyerahanUnit #tanggal_confirm_penyerahan_unit').val(confirm_at)
            if (is_confirm) {
                $('#modalConfirmPenyerahanUnit .title-modal').html('Penyerahan Unit')
                $('.form-confirm').css('display', 'none');
                $('.penyerahan-unit-title').html('Penyerahan Unit');
            }
            else {
                var role_id = "{{\Session::get(config('global.role_id_session'))}}"
                var role_name = "{{\Session::get(config('global.user_role_session'))}}"
                if (role_id == 2 && role_name == 'Staf Analis Kredit') {
                    $('#modalConfirmPenyerahanUnit .title-modal').html('Konfirmasi Penyerahan Unit')
                    $('.form-confirm').css('display', 'block');
                    $('.penyerahan-unit-title').html('Konfirmasi Penyerahan Unit');
                }
                else {
                    $('#modalConfirmPenyerahanUnit .title-modal').html('Penyerahan Unit')
                    $('.form-confirm').css('display', 'none');
                    $('.penyerahan-unit-title').html('Penyerahan Unit');
                }
            }
        });

        $("[data-dismiss-id]").on("click", function () {
            const dismissId = $(this).data("dismiss-id");
            $("#" + dismissId).addClass("hidden");
            $(".layout-overlay-edit-form").addClass("hidden");
        });

        $('#confirm-form-penyerahan-unit').on('submit', function(e) {
            e.preventDefault()
            const req_id = $('#confirm_id').val()
            const req_category_doc_id = $('#confirm_id_category').val()

            $.ajax({
                type: "POST",
                url: "{{ route('kredit.confirm_penyerahan_unit') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: req_id,
                    category_id: req_category_doc_id
                },
                success: function(data) {
                    if (Array.isArray(data.error)) {
                        console.log(data.error)
                    } else {
                        if (data.status == 'success') {
                            SuccessMessage(data.message);
                        } else {
                            ErrorMessage(data.message)
                        }
                    }
                }
            })
        })
    </script>
@endpush
