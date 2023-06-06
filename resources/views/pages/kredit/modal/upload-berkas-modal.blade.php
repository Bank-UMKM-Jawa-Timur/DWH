<div class="modal fade" id="uploadBerkasModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title" id="uploadBerkasModalLabel">
                    @if (Auth::user()->role_id == 3)
                        Upload Berkas
                    @else
                        Konfirmasi Berkas
                    @endif
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="text-light">&times;</span>
                </button>
            </div>
            <form id="modal-berkas" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                        <input type="hidden" name="id_kkb" id="id_kkb">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="stnk-tab-menu" data-toggle="tab" href="#stnk_tab"
                                    role="tab" aria-controls="stnk" aria-selected="true">STNK</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="polis-tab-menu" data-toggle="tab" href="#polis_tab" role="tab"
                                    aria-controls="polis" aria-selected="false">Polis</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="bpkb-tab-menu" data-toggle="tab" href="#bpkb_tab" role="tab"
                                    aria-controls="bpkb" aria-selected="false">BPKB</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            {{--  STNK  --}}
                            <div class="tab-pane fade show active" id="stnk_tab" role="tabpanel"
                                aria-labelledby="stnk-tab">
                                <input type="hidden" name="id_stnk" id="id_stnk">
                                <p class="mt-2" id="stnk_belum_diunggah"></p>
                                <div class="form-group input-no-stnk">
                                    <label>Nomor</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="no_stnk" name="no_stnk" @if (Auth::user()->role_id == 2) readonly @endif>
                                    </div>
                                    <small class="form-text text-danger error"></small>
                                </div>
                                <div class="form-group status-stnk">
                                    <p class="m-0" id="tanggal_upload_stnk"></p>
                                    <p class="m-0" id="tanggal_confirm_stnk"></p>
                                    <p class="m-0" id="status_confirm_stnk"></p>
                                </div>
                                <iframe id="preview_stnk" src="" width="100%" height="450px"></iframe>
                                @if (Auth::user()->role_id == 3)
                                    <div class="form-group input-stnk">
                                        <label>Scan Berkas (pdf)</label>
                                        <div class="input-group">
                                            <input type="file" class="form-control" id="stnk_scan" name="stnk_scan"
                                                accept="application/pdf">
                                            <div class="input-group-append">
                                                <span class="input-group-text">
                                                    <i class="fa fa-file"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <small class="form-text text-danger error"></small>
                                    </div>
                                @endif
                            </div>
                            {{--  Polis  --}}
                            <div class="tab-pane fade" id="polis_tab" role="tabpanel" aria-labelledby="polis-tab">
                                <input type="hidden" name="id_polis" id="id_polis">
                                <p class="mt-2" id="polis_belum_diunggah"></p>
                                <div class="form-group input-no-polis">
                                    <label>Nomor</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="no_polis" name="no_polis" @if (Auth::user()->role_id == 2) readonly @endif>
                                    </div>
                                    <small class="form-text text-danger error"></small>
                                </div>
                                <div class="form-group status-polis">
                                    <p class="m-0" id="tanggal_upload_polis"></p>
                                    <p class="m-0" id="tanggal_confirm_polis"></p>
                                    <p class="m-0" id="status_confirm_polis"></p>
                                </div>
                                <iframe id="preview_polis" src="" width="100%" height="450px"></iframe>
                                @if (Auth::user()->role_id == 3)
                                    <div class="form-group input-polis">
                                        <label>Scan Berkas (pdf)</label>
                                        <div class="input-group">
                                            <input type="file" class="form-control" id="polis_scan" name="polis_scan"
                                                accept="application/pdf">
                                            <div class="input-group-append">
                                                <span class="input-group-text">
                                                    <i class="fa fa-file"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <small class="form-text text-danger error"></small>
                                    </div>
                                @endif
                            </div>
                            {{--  BPKB  --}}
                            <div class="tab-pane fade" id="bpkb_tab" role="tabpanel" aria-labelledby="bpkb-tab">
                                <input type="hidden" name="id_bpkb" id="id_bpkb">
                                <p class="mt-2" id="bpkb_belum_diunggah"></p>
                                <div class="form-group input-no-bpkb">
                                    <label>Nomor</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="no_bpkb" name="no_bpkb" @if (Auth::user()->role_id == 2) readonly @endif>
                                    </div>
                                    <small class="form-text text-danger error"></small>
                                </div>
                                <div class="form-group status-bpkb">
                                    <p class="m-0" id="tanggal_upload_bpkb"></p>
                                    <p class="m-0" id="tanggal_confirm_bpkb"></p>
                                    <p class="m-0" id="status_confirm_bpkb"></p>
                                </div>
                                <iframe id="preview_bpkb" src="" width="100%" height="450px"></iframe>
                                @if (Auth::user()->role_id == 3)
                                    <div class="form-group input-bpkb">
                                        <label>Scan Berkas (pdf)</label>
                                        <div class="input-group">
                                            <input type="file" class="form-control" id="bpkb_scan" name="bpkb_scan"
                                                accept="application/pdf">
                                            <div class="input-group-append">
                                                <span class="input-group-text">
                                                    <i class="fa fa-file"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <small class="form-text text-danger error"></small>
                                    </div>
                                @endif
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <div class="form-group form-submit-berkas">
                        <button type="submit" class="btn btn-primary">
                            @if (Auth::user()->role_id == 2)
                                Konfirmasi
                            @endif
                            @if (Auth::user()->role_id == 3)
                                Kirim
                            @endif
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('extraScript')
    <script>
        const user_role = "{{Auth::user()->role_id}}";
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

        $('.upload-berkas').on('click', function(e) {
            e.preventDefault()
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
                var path_stnk = "{{ asset('storage') }}" + "/dokumentasi-stnk/" + file_stnk + "#toolbar=0";
                $("#preview_stnk").attr("src", path_stnk);
            } else {
                $("#preview_stnk").css("display", 'none');
            }

            if (file_polis != '') {
                var path_polis = "{{ asset('storage') }}" + "/dokumentasi-polis/" + file_polis + "#toolbar=0";
                $("#preview_polis").attr("src", path_polis);
            } else {
                $("#preview_polis").css("display", 'none');
            }

            if (file_bpkb != '') {
                var path_bpkb = "{{ asset('storage') }}" + "/dokumentasi-bpkb/" + file_bpkb + "#toolbar=0";
                $("#preview_bpkb").attr("src", path_bpkb);
            } else {
                $("#preview_bpkb").css("display", 'none');
            }
        })

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
            
            /*if (file_polis != '') {
                if (user_role == 3)
                    $('.form-submit-berkas').css('display', 'none')
                if (user_role == 2 && !confirm_polis && polisActive)
                    $('.form-submit-berkas').css('display', 'block')
                $('.input-polis').css('display', 'none')
                $('#no_polis').prop('readonly', true)
                $('#tanggal_upload_polis').html('Tanggal Upload : '+tanggal_polis);
                $('#tanggal_confirm_polis').html('Tanggal Konfirmasi : '+confirm_at_polis);
                $('#status_confirm_polis').html('Status : '+(confirm_polis ? 'Sudah dikonfirmasi' : 'Belum dikonfirmasi'));
            }
            else {
                if (user_role == 2) {
                    $('#polis_belum_diunggah').html('Berkas belum diunggah.')
                    $('.input-no-polis').css('display', 'none')
                    if (polisActive)
                        $('.form-submit-berkas').css('display', 'none')
                }
                else {
                    if (polisActive)
                        $('.form-submit-berkas').css('display', 'block')
                }
            }
            if (file_bpkb != '') {
                if (user_role == 3)
                    $('.form-submit-berkas').css('display', 'none')
                if (user_role == 2 && !confirm_bpkb && bpkbActive)
                    $('.form-submit-berkas').css('display', 'block')
                $('.input-bpkb').css('display', 'none')
                $('#no_bpkb').prop('readonly', true)
                $('#tanggal_upload_bpkb').html('Tanggal Upload : '+tanggal_bpkb);
                $('#tanggal_confirm_bpkb').html('Tanggal Konfirmasi : '+confirm_at_bpkb);
                $('#status_confirm_bpkb').html('Status : '+(confirm_bpkb ? 'Sudah dikonfirmasi' : 'Belum dikonfirmasi'));
            }
            else {
                if (user_role == 2) {
                    $('#bpkb_belum_diunggah').html('Berkas belum diunggah.')
                    $('.input-no-bpkb').css('display', 'none')
                    if (bpkbActive)
                        $('.form-submit-berkas').css('display', 'none')
                }
                else {
                    if (bpkbActive)
                        $('.form-submit-berkas').css('display', 'block')
                }
            }*/
        }

        $('#modal-berkas').on("submit", function(event) {
            event.preventDefault();
            var is_confirm = "{{ Auth::user()->role_id }}" == 2;

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
                            for (var i = 0; i < data.error.length; i++) {
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
                            }
                        } else {
                            if (data.status == 'success') {
                                SuccessMessage(data.message);
                            } else {
                                ErrorMessage(data.message)
                            }
                            $('#uploadBerkasModal').modal().hide()
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
                            $('#uploadBerkasModal').modal().hide()
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