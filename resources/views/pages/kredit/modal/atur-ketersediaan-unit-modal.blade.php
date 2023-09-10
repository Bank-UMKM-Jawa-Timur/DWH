<div class="modal-overlay hidden" id="modalAturKetersedian">
    <div class="modal modal-tab">
        <div class="modal-head text-gray-500 text-lg">
            <div class="title-modal">Atur Tanggal Ketersediaan Unit</div>
            <button class="close-modal" data-dismiss-id="modalAturKetersedian">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2" d="M17 7L7 17M7 7l10 10" />
                </svg>
            </button>
        </div>
        <form id="modal-tgl-form">
            <div class="modal-body">
                <div class="input-box space-y-3">
                    <div class="p-5 space-y-4">
                        <input type="hidden" name="id_kkb" id="id_kkb">
                        <label for="" class="uppercase">Tanggal Ketersedian Unit</label>
                        <input type="text"  class="datepicker p-2 w-full border bg-gray-100" name="tgl_ketersediaan_unit"
                            id="tgl_ketersediaan_unit" />
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss-id="modalAturKetersedian" class="border px-7 py-3 text-black rounded">
                    Batal
                </button>
                <button type="submit" class="bg-theme-primary px-7 py-3 text-white rounded">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@push('extraScript')
    <script>
        function SuccessMessage(message) {
            Swal.fire({
                title: 'Berhasil',
                icon: 'success',
                timer: 3000,
                closeOnClickOutside: false
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#preload-data').removeClass("hidden")
                    $('[data-dismiss-id]').trigger('click')
                    refreshTable()
                }
            })
        }
        
        function ErrorMessage(message) {
            Swal.fire({
                title: 'Gagal',
                icon: 'error',
                timer: 3000,
                closeOnClickOutside: false
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#preload-data').removeClass("hidden")
                    $('[data-dismiss-id]').trigger('click')
                    refreshTable()
                }
            })
        }

        $(".toggle-modal").on("click", function () {
            const targetId = $(this).data("target-id");
            $("#" + targetId).removeClass("hidden");
            $(".layout-overlay-edit-form").removeClass("hidden");

            var id = $(this).data('id_kkb');

            $("#id_kkb").val(id);
        });
        $('#modal-tgl-form').on("submit", function(event) {
            Swal.fire({
                title: 'Memuat...',
                html: 'Silahkan tunggu...',
                allowEscapeKey: false,
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading()
                }
            });
            event.preventDefault();

            const req_id = document.getElementById('id_kkb')
            const req_date = document.getElementById('tgl_ketersediaan_unit')

            if (req_date == '') {
                showError(req_date, 'Tanggal ketersediaan unit harus dipilih.');
                return false;
            }

            $.ajax({
                type: "POST",
                url: "{{ route('kredit.set_tgl_ketersediaan_unit') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    id_kkb: req_id.value,
                    date: req_date.value,
                },
                success: function(data) {
                    Swal.close()
                    console.log(data);
                    if (Array.isArray(data.error)) {
                        showError(req_date, data.error[0])
                    } else {
                        if (data.status == 'success') {
                            SuccessMessage(data.message);
                        } else {
                            ErrorMessage(data.message)
                        }
                        $('#tglModal').modal().hide()
                        $('body').removeClass('modal-open');
                        $('.modal-backdrop').remove();
                    }
                },
                error: function(e) {
                    Swal.close()
                    console.log(e)
                    ErrorMessage('Terjadi kesalahan')
                }
            })
        })
    </script>
@endpush