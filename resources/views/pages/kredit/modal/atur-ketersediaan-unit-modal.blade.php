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
        function AturSuccesMessage(message) {
            Swal.fire({
                showConfirmButton: true,
                timer: 3000,
                closeOnClickOutside: true,
                title: 'Berhasil',
                icon: 'success',
            }).then((result) => {
                console.log('then')
                $("#modalAturKetersedian").addClass("hidden");
                $('#preload-data').removeClass("hidden")
                
                refreshTable()
            })
        }
        
        function AturErrorMessage(message) {
            Swal.fire({
                showConfirmButton: false,
                timer: 3000,
                closeOnClickOutside: true,
                title: 'Gagal',
                icon: 'error',
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#preload-data').removeClass("hidden")
                    
                    refreshTable()
                }
            })
        }

        $('#modal-tgl-form').on("submit", function(event) {
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
            event.preventDefault();

            const req_id = document.getElementById('id_kkb')
            const req_date = document.getElementById('tgl_ketersediaan_unit')

            if (req_date == '') {
                AturErrorMessage(req_date, 'Tanggal ketersediaan unit harus dipilih.');
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
                    if (Array.isArray(data.error)) {
                        //AturErrorMessage(req_date, data.error[0])
                    } else {
                        if (data.status == 'success') {
                            AturSuccesMessage(data.message);
                        } else {
                            AturErrorMessage(data.message)
                        }
                    }
                },
                error: function(e) {
                    Swal.close()
                    console.log('qwerty')
                    console.log(e)
                    //AturErrorMessage('Terjadi kesalahan')
                }
            })
        })
    </script>
@endpush