<div class="modal-overlay hidden" id="modalTagihan">
    <div class="modal modal-tab">
        <div class="modal-head text-gray-500 text-lg">
            <div class="title-modal">Tagihan</div>
            <button class="close-modal" data-dismiss-id="modalTagihan">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2" d="M17 7L7 17M7 7l10 10" />
                </svg>
            </button>
        </div>
        <div class="modal-body">
            <div class="p-4">
                <div class="gap-5 space-y-5">
                    <div class="flex gap-5 w-full mt-0">
                        <div class="input-box w-full space-y-3">
                            <p class="uppercase appearance-none" id="kategori_data"></p>
                        </div>
                    </div>
                    <div class="flex gap-5 w-full mt-0">
                        <div class="input-box w-full space-y-3">
                            <label for="" class="uppercase appearance-none">Tanggal Upload</label>
                            <input type="text" disabled class="p-2 w-full border" id="tanggal_tagihan"  />
                        </div>
                    </div>
                    <div class="flex gap-5 w-full mt-0">
                        <div class="input-box w-full space-y-3">
                            <label for="" class="uppercase appearance-none">Status</label>
                            <input type="text" disabled class="p-2 w-full border" id="status_tagihan"  />
                        </div>
                    </div>
                    <div class="space-y-3">
                        <label for="" class="uppercase appearance-none">Tagihan</label>
                        <div class="content-tagihan h-[528px] w-full bg-gray-100">
                            <iframe id="tagihan_file" src="" class="mt-2" width="100%" height="500"></iframe>
                        </div>
                        <div class="alert-tagihan hidden text-center">
                            <img src="{{asset('template/assets/img/news/not-uploaded.svg')}}" alt=""class="max-w-sm mx-auto" />
                            <p class="font-semibold tracking-tighter text-theme-text">
                                    File Tagihan Tidak ada di server.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('extraScript')
    <script>
        $('.tagihan-modal').on('click', function(e) {
            const file = $(this).data('file');
            console.log(file);
            const status = $(this).data('confirm') ? 'Selesai' :
                'Menunggu pembayaran tagihan dari cabang.';
            const tanggal = $(this).data('tanggal');
            const kategori = ($(this).data('kategori') === 'data_import') ? 'Catatan! Data ini merupakan data import google spreadsheet' : '';
            console.log(kategori);
            const confirm_at = $(this).data('confirm_at');
            var path_file = "{{ asset('storage') }}" + "/tagihan/" + file + "#navpanes=0";

            fetch(path_file).then(function(response){
                    if(!response.ok){
                        $('.content-tagihan').addClass("hidden");
                        $('.alert-tagihan').removeClass("hidden");
                    }else{
                        $('.content-tagihan').removeClass("hidden");
                        $('.alert-tagihan').addlass("hidden");
                    }
                })

            $('#tagihan_file').attr('src', path_file)
            $('#kategori_data').text(kategori);
            $('#tanggal_tagihan').val(tanggal)
            $('#status_tagihan').val(status)
        })

        $("[data-dismiss-id]").on("click", function () {
            const dismissId = $(this).data("dismiss-id");
            $("#" + dismissId).addClass("hidden");
            $(".layout-overlay-edit-form").addClass("hidden");
        });
    </script>
@endpush
