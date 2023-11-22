
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
                <div class="flex gap-5 w-full mt-0">
                    <div class="input-box w-full space-y-3">
                        <p class="uppercase appearance-none" id="kategori_data"></p>
                    </div>
                </div>
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
                    <div class="content-penyerahan-unit h-[528px] w-full bg-gray-100">
                        <img id="preview_penyerahan_unit" class="w-full h-[528px]">
                    </div>
                    <div class="alert-penyerahan-unit hidden text-center">
                        <img src="{{asset('template/assets/img/news/not-uploaded.svg')}}" alt=""class="max-w-sm mx-auto" />
                        <p class="font-semibold tracking-tighter text-theme-text">
                                Gambar Penyerahan Unit Tidak ada di server.
                        </p>
                    </div>
                </div>
            </div>
        </div>
        @if (\Session::get(config('global.role_id_session')) == 2)
            <div class="modal-footer form-confirm">
                <form id="confirm-form-penyerahan-unit">
                    <input type="hidden" name="confirm_id" id="confirm_penyerahan_id">
                    <input type="hidden" name="confirm_id_category" id="confirm_penyerahan_id_category">
                    <button type="button" data-dismiss-id="modalConfirmPenyerahanUnit" class="border px-7 py-3 text-black rounded">
                        Tidak
                    </button>
                    <button type="submit" class="bg-theme-primary px-7 py-3 text-white rounded">
                        Ya
                    </button>
                </form>
            </div>
        @endif
    </div>
</div>

@push('extraScript')
    <script>
        function ConfirmPenyerahanUnitSuccessMessage(message) {
            Swal.fire({
                showConfirmButton: true,
                timer: 3000,
                closeOnClickOutside: true,
                title: 'Berhasil',
                message: message,
                icon: 'success',
            }).then((result) => {
                console.log('then')
                $("#modalConfirmPenyerahanUnit").addClass("hidden");
                $('#preload-data').removeClass("hidden")

                refreshTable()
                //location.reload();
            })
        }

        function ConfirmPenyerahanUnitErrorMessage(message) {
            Swal.fire({
                showConfirmButton: false,
                timer: 3000,
                closeOnClickOutside: true,
                title: 'Gagal',
                message: message,
                icon: 'error',
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#preload-data').removeClass("hidden")

                    refreshTable()
                    //location.reload();
                }
            })
        }

        $("[data-dismiss-id]").on("click", function () {
            const dismissId = $(this).data("dismiss-id");
            $("#" + dismissId).addClass("hidden");
            $(".layout-overlay-edit-form").addClass("hidden");
        });

        $('#confirm-form-penyerahan-unit').on('submit', function(e) {
            Swal.fire({
                showConfirmButton: false,
                closeOnClickOutside: false,
                title: 'Memuat...',
                html: 'Silahkan tunggu...',
                allowEscapeKey: false,
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading()
                }
            });
            e.preventDefault()
            const req_id = $('#confirm_penyerahan_id').val()
            const req_category_doc_id = $('#confirm_penyerahan_id_category').val()

            $.ajax({
                type: "POST",
                url: "{{ route('kredit.confirm_penyerahan_unit') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: req_id,
                    category_id: req_category_doc_id
                },
                success: function(data) {
                    Swal.close()

                    if (Array.isArray(data.error)) {
                        console.log(data.error)
                    } else {
                        if (data.status == 'success') {
                            ConfirmPenyerahanUnitSuccessMessage(data.message);
                        } else {
                            ConfirmPenyerahanUnitErrorMessage(data.message)
                        }
                    }
                },
                error: function(e) {
                    console.log('confirm error')
                    console.log(e)
                    Swal.close()
                }
            })
        })
    </script>
@endpush
