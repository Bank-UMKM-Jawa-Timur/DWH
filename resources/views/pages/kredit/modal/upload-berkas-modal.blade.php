<div class="modal-overlay hidden font-lexend overflow-auto" id="modalUploadBerkas">
    <div class="modal modal-tab">
        <div class="modal-head text-gray-500 text-lg">
            <div class="title-modal">Upload Berkas</div>
            <button data-dismiss-id="modalUploadBerkas">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2" d="M17 7L7 17M7 7l10 10" />
                </svg>
            </button>
        </div>

        <form id="modal-berkas">
            <input type="hidden" name="id_kkb" id="id_kkb">
            @csrf
            <div class="modal-body">
                <div class="overflow-x-auto">
                    <ul class="flex tab-wrapping w-full mt-5 border-b-2 p-[6px]">
                        <li class="tab-li">
                            <a data-tab="tab1"
                                class="tab-button cursor-pointer hover:border-b-2 hover:border-theme-primary hover:text-theme-primary bg-white text-gray-400 py-2 px-4">STNK</a>
                        </li>
                        <li class="tab-li">
                            <a data-tab="tab2"
                                class="tab-button cursor-pointer hover:border-b-2 hover:border-theme-primary hover:text-theme-primary bg-white text-gray-400 py-2 px-4">Polis</a>
                        </li>
                        <li class="tab-li">
                            <a data-tab="tab3"
                                class="tab-button cursor-pointer hover:border-b-2 hover:border-theme-primary hover:text-theme-primary bg-white text-gray-400 py-2 px-4">BPKB</a>
                        </li>
                    </ul>
                </div>
    
                <div class="p-2">
                    <div id="tab1" class="tab-content hidden">
                        <div class="input-box space-y-3">
                            <input type="hidden" name="id_stnk" id="id_stnk">
                            <div class="px-3 space-y-4">
                                <label for="" class="uppercase">Nomor</label>
                                <input type="text" class="p-2 w-full border bg-gray-100" id="no_stnk" name="no_stnk" @if (\Session::get(config('global.role_id_session')) == 2) readonly @endif />
                                <div class="form-group status-stnk">
                                    <p class="m-0" id="tanggal_upload_stnk"></p>
                                    <p class="m-0" id="tanggal_confirm_stnk"></p>
                                    <p class="m-0" id="status_confirm_stnk"></p>
                                </div>
                            </div>
                        </div>
                        <iframe id="preview_stnk" src="" width="100%" height="450px"></iframe>
                        @if (\Session::get(config('global.role_id_session')) == 3)
                            <div class="input-box space-y-3">
                                <div class="p-3 space-y-4">
                                    <label for="" class="uppercase">Scan Berkas (PDF)</label>
                                    <input type="file" class="p-2 w-full border bg-gray-100" id="stnk_scan" name="stnk_scan"
                                    accept="application/pdf" />
                                </div>
                            </div>
                        @endif
                    </div>
                    <div id="tab2" class="tab-content hidden">
                        <div class="input-box space-y-3">
                            <input type="hidden" name="id_polis" id="id_polis">
                            <div class="px-3 space-y-4">
                                <label for="" class="uppercase">Nomor</label>
                                <input type="text" class="p-2 w-full border bg-gray-100" id="no_polis" name="no_polis" @if (\Session::get(config('global.role_id_session')) == 2) readonly @endif />
                                <div class="form-group status-polis">
                                    <p class="m-0" id="tanggal_upload_polis"></p>
                                    <p class="m-0" id="tanggal_confirm_polis"></p>
                                    <p class="m-0" id="status_confirm_polis"></p>
                                </div>
                            </div>
                        </div>
                        <iframe id="preview_polis" src="" width="100%" height="450px"></iframe>
                        @if (\Session::get(config('global.role_id_session')) == 3)
                            <div class="input-box space-y-3">
                                <div class="p-3 space-y-4">
                                    <label for="" class="uppercase">Scan Berkas (PDF)</label>
                                    <input type="file" class="p-2 w-full border bg-gray-100" id="polis_scan" name="polis_scan"
                                    accept="application/pdf" />
                                </div>
                            </div>
                        @endif
                    </div>
                    <div id="tab3" class="tab-content hidden">
                        <div class="input-box space-y-3">
                            <input type="hidden" name="id_bpkb" id="id_bpkb">
                            <div class="px-3 space-y-4">
                                <label for="" class="uppercase">Nomor</label>
                                <input type="text" class="p-2 w-full border bg-gray-100" id="no_bpkb" name="no_bpkb" @if (\Session::get(config('global.role_id_session')) == 2) readonly @endif />
                                <div class="form-group status-bpkb">
                                    <p class="m-0" id="tanggal_upload_bpkb"></p>
                                    <p class="m-0" id="tanggal_confirm_bpkb"></p>
                                    <p class="m-0" id="status_confirm_bpkb"></p>
                                </div>
                            </div>
                        </div>
                        <iframe id="preview_bpkb" src="" width="100%" height="450px"></iframe>
                        @if (\Session::get(config('global.role_id_session')) == 3)
                            <div class="input-box space-y-3">
                                <div class="p-3 space-y-4">
                                    <label for="" class="uppercase">Scan Berkas (PDF)</label>
                                    <input type="file" class="p-2 w-full border bg-gray-100" id="bpkb_scan" name="bpkb_scan"
                                    accept="application/pdf" />
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="bg-theme-primary px-7 py-3 text-white rounded">
                    @if (\Session::get(config('global.role_id_session')) == 2)
                        Konfirmasi
                    @endif
                    @if (\Session::get(config('global.role_id_session')) == 3)
                        Kirim
                    @endif
                </button>
            </div>
        </form>
    </div>
</div>


@push('extraScript')
    <script>
        const user_role = "{{\Session::get(config('global.role_id_session'))}}";
        var id = '';
        var id_stnk = '';
        var id_polis = '';
        var id_bpkb = '';
        var no_stnk = ''
        var no_polis = ''
        var no_bpkb = ''
        var file_stnk = '';
        var file_polis = '';
        var file_bpkb = '';
        var tanggal_stnk = '';
        var tanggal_polis = '';
        var tanggal_bpkb = '';
        var confirm_at_sntk = '';
        var confirm_at_polis = '';
        var confirm_at_bpkb = '';
        var confirm_sntk = '';
        var confirm_polis = '';
        var confirm_bpkb = '';

        $(".toggle-modal").on("click", function () {
            const targetId = $(this).data("target-id");
            $("#" + targetId).removeClass("hidden");
            $(".layout-overlay-edit-form").removeClass("hidden");

            id = $(this).data('id_kkb')
            id_stnk = $(this).data('id-stnk') ? $(this).data('id-stnk') : '';
            id_polis = $(this).data('id-polis') ? $(this).data('id-polis') : '';
            id_bpkb = $(this).data('id-bpkb') ? $(this).data('id-bpkb') : '';
            no_stnk = $(this).data('no-stnk') ? $(this).data('no-stnk') : ''
            no_polis = $(this).data('no-polis') ? $(this).data('no-polis') : ''
            no_bpkb = $(this).data('no-bpkb') ? $(this).data('no-bpkb') : ''
            file_stnk = $(this).data('file-stnk') ? $(this).data('file-stnk') : ''
            file_polis = $(this).data('file-polis') ? $(this).data('file-polis') : ''
            file_bpkb = $(this).data('file-bpkb') ? $(this).data('file-bpkb') : ''
            tanggal_stnk = $(this).data('date-stnk') ? $(this).data('date-stnk') : ''
            tanggal_polis = $(this).data('date-polis') ? $(this).data('date-polis') : ''
            tanggal_bpkb = $(this).data('date-bpkb') ? $(this).data('date-bpkb') : ''
            confirm_at_stnk = $(this).data('confirm-at-stnk') ? $(this).data('confirm-at-stnk') : '-'
            confirm_at_polis = $(this).data('confirm-at-polis') ? $(this).data('confirm-at-polis') : '-'
            confirm_at_bpkb = $(this).data('confirm-at-bpkb') ? $(this).data('confirm-at-bpkb') : '-'
            confirm_stnk = $(this).data('confirm-stnk') ? $(this).data('confirm-stnk') : ''
            confirm_polis = $(this).data('confirm-polis') ? $(this).data('confirm-polis') : ''
            confirm_bpkb = $(this).data('confirm-bpkb') ? $(this).data('confirm-bpkb') : ''

            visibilityComponents();

            try {
                $('#modal-berkas #id_kkb').val(id);
                if (id_stnk != '')
                    $('#modal-berkas #id_stnk').val(id_stnk);
                if (id_polis != '')
                    $('#modal-berkas #id_polis').val(id_polis);
                if (id_bpkb != '')
                    $('#modal-berkas #id_bpkb').val(id_bpkb);
                if (no_stnk != '')
                    $('#modal-berkas #no_stnk').val(no_stnk);
                if (no_polis != '')
                    $('#modal-berkas #no_polis').val(no_polis);
                if (no_bpkb != '')
                    $('#modal-berkas #no_bpkb').val(no_bpkb);
                if (file_stnk != '')
                    $('#modal-berkas #stnk_scan').val(file_stnk);
                if (file_polis != '')
                    $('#modal-berkas #polis_scan').val(file_polis);
                if (file_bpkb != '')
                    $('#modal-berkas #bpkb_scan').val(file_bpkb);
            } catch (e) {
                console.log('error : '+e)
            }
            var path_polis = "{{ asset('storage') }}" + "/dokumentasi-polis/" + file_polis;
            var path_bpkb = "{{ asset('storage') }}" + "/dokumentasi-bpkb/" + file_bpkb;

            if (file_stnk != '') {
                var path_stnk = "{{ asset('storage') }}" + "/dokumentasi-stnk/" + file_stnk + "#navpanes=0";
                $("#preview_stnk").attr("src", path_stnk);
            } else {
                $("#preview_stnk").css("display", 'none');
            }

            if (file_polis != '') {
                var path_polis = "{{ asset('storage') }}" + "/dokumentasi-polis/" + file_polis + "#navpanes=0";
                $("#preview_polis").attr("src", path_polis);
            } else {
                $("#preview_polis").css("display", 'none');
            }

            if (file_bpkb != '') {
                var path_bpkb = "{{ asset('storage') }}" + "/dokumentasi-bpkb/" + file_bpkb + "#navpanes=0";
                $("#preview_bpkb").attr("src", path_bpkb);
            } else {
                $("#preview_bpkb").css("display", 'none');
            }
        });

        $("[data-dismiss-id]").on("click", function () {
            const dismissId = $(this).data("dismiss-id");
            $("#" + dismissId).addClass("hidden");
            $(".layout-overlay-edit-form").addClass("hidden");
        });

        $('#stnk-tab-menu').on('click', function() {
            if (file_stnk != '') {
                if (user_role == 2 && !confirm_stnk)
                    $('.form-submit-berkas').css('display', 'block')
                else
                    $('.form-submit-berkas').css('display', 'none')
                $('.input-stnk').css('display', 'none')
                $('#no_stnk').prop('readonly', true)
            }
            else {
                if (user_role == 3) {
                    $('.form-submit-berkas').css('display', 'block')
                }
                else {
                    $('.form-submit-berkas').css('display', 'none')
                    $('.input-stnk').css('display', 'block')
                    $('#no_stnk').prop('readonly', false)
                }
            }
        })
        $('#polis-tab-menu').on('click', function() {
            if (file_polis != '') {
                $('#tanggal_upload_polis').html('Tanggal Upload : '+tanggal_polis);
                $('#tanggal_confirm_polis').html('Tanggal Konfirmasi : '+confirm_at_polis);
                $('#status_confirm_polis').html('Status : '+(confirm_polis ? 'Sudah dikonfirmasi' : 'Belum dikonfirmasi'));
                if (user_role == 2 && !confirm_polis)
                    $('.form-submit-berkas').css('display', 'block')
                else
                    $('.form-submit-berkas').css('display', 'none')
                $('.input-polis').css('display', 'none')
                $('#no_polis').prop('readonly', true)
            }
            else {
                if (user_role == 3) {
                    $('.form-submit-berkas').css('display', 'block')
                }
                else {
                    $('.form-submit-berkas').css('display', 'none')
                    $('.input-polis').css('display', 'block')
                    $('#no_polis').prop('readonly', false)
                }
            }
        })
        $('#bpkb-tab-menu').on('click', function() {
            if (file_bpkb != '') {
                $('#tanggal_upload_bpkb').html('Tanggal Upload : '+tanggal_bpkb);
                $('#tanggal_confirm_bpkb').html('Tanggal Konfirmasi : '+confirm_at_bpkb);
                $('#status_confirm_bpkb').html('Status : '+(confirm_bpkb ? 'Sudah dikonfirmasi' : 'Belum dikonfirmasi'));
                if (user_role == 2 && !confirm_bpkb)
                    $('.form-submit-berkas').css('display', 'block')
                else
                    $('.form-submit-berkas').css('display', 'none')
                $('.input-bpkb').css('display', 'none')
                $('#no_bpkb').prop('readonly', true)
            }
            else {
                if (user_role == 3) {
                    $('.form-submit-berkas').css('display', 'block')
                }
                else {
                    $('.form-submit-berkas').css('display', 'none')
                    $('.input-bpkb').css('display', 'block')
                    $('#no_bpkb').prop('readonly', false)
                }
            }
        })

        function visibilityComponents() {
            var stnkActive = $('#stnk-tab-menu').hasClass('active')
            var polisActive = $('#polis-tab-menu').hasClass('active')
            var bpkbActive = $('#bpkb-tab-menu').hasClass('active')

            if (file_stnk != '') {
                if (user_role == 3)
                    $('.form-submit-berkas').css('display', 'none')
                if (user_role == 2 && !confirm_stnk && stnkActive)
                    $('.form-submit-berkas').css('display', 'block')
                else
                    $('.form-submit-berkas').css('display', 'none')
                $('.input-stnk').css('display', 'none')
                $('#no_stnk').prop('readonly', true)
                $('#tanggal_upload_stnk').html('Tanggal Upload : '+tanggal_stnk);
                $('#tanggal_confirm_stnk').html('Tanggal Konfirmasi : '+(confirm_at_stnk));
                $('#status_confirm_stnk').html('Status : '+(confirm_stnk ? 'Sudah dikonfirmasi' : 'Belum dikonfirmasi'));
            }
            else {
                if (user_role == 2) {
                    $('#stnk_belum_diunggah').html('Berkas belum diunggah.')
                    $('.input-no-stnk').css('display', 'none')
                    if (stnkActive)
                        $('.form-submit-berkas').css('display', 'none')
                }
                else {
                    if (stnkActive)
                        $('.form-submit-berkas').css('display', 'block')
                }
            }
        }

        $('#modal-berkas').on("submit", function(event) {
            event.preventDefault();
            var is_confirm = "{{ \Session::get(config('global.role_id_session')) }}" != 3;

            if (!is_confirm) {
                // Upload
                const req_id = document.getElementById('id_kkb')
                const req_no_stnk = document.getElementById('no_stnk')
                const req_file_stnk = document.getElementById('stnk_scan')
                const req_no_polis = document.getElementById('no_polis')
                const req_file_polis = document.getElementById('polis_scan')
                const req_no_bpkb = document.getElementById('no_bpkb')
                const req_file_bpkb = document.getElementById('bpkb_scan')
                var formData = new FormData($(this)[0]);

                $.ajax({
                    type: "POST",
                    url: "{{ route('kredit.upload_berkas') }}",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        if (Array.isArray(data.error)) {
                            console.log(data.error)
                            ErrorMessage('gagal')
                            /*for (var i = 0; i < data.error.length; i++) {
                                var message = data.error[i];
                                if (message.toLowerCase().includes('no_stnk'))
                                    showError(req_date, message)
                                if (message.toLowerCase().includes('stnk_scan'))
                                    showError(req_image, message)
                                if (message.toLowerCase().includes('no_polis'))
                                    showError(req_date, message)
                                if (message.toLowerCase().includes('polis_scan'))
                                    showError(req_image, message)
                                if (message.toLowerCase().includes('no_bpkb'))
                                    showError(req_date, message)
                                if (message.toLowerCase().includes('bpkb_scan'))
                                    showError(req_image, message)
                            }*/
                        } else {
                            if (data.status == 'success') {
                                SuccessMessage(data.message);
                            } else {
                                ErrorMessage(data.message)
                            }
                            $('#modalUploadBerkas').modal().hide()
                            $('body').removeClass('modal-open');
                            $('.modal-backdrop').remove();
                        }
                    },
                    error: function(e) {
                        console.log(e)
                        ErrorMessage('Terjadi kesalahan')
                    }
                })
            } else {
                // Confirm
                const req_id_stnk = document.getElementById('id_stnk')
                const req_id_polis = document.getElementById('id_polis')
                const req_id_bpkb = document.getElementById('id_bpkb')
                var formData = new FormData($(this)[0]);

                $.ajax({
                    type: "POST",
                    url: "{{ route('kredit.confirm_berkas') }}",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        if (Array.isArray(data.error)) {
                            for (var i = 0; i < data.error.length; i++) {
                                var message = data.error[i];
                                console.log(message)
                            }
                        } else {
                            if (data.status == 'success') {
                                SuccessMessage(data.message);
                            } else {
                                ErrorMessage(data.message)
                            }
                            $('#modalUploadBerkas').modal().hide()
                            $('body').removeClass('modal-open');
                            $('.modal-backdrop').remove();
                        }
                    },
                    error: function(e) {
                        console.log(e)
                        ErrorMessage('Terjadi kesalahan')
                    }
                })
            }
        })
    </script>
@endpush
