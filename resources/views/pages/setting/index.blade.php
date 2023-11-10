@extends('layout.master')
@section('modal')
<!-- Modal-tambah -->
@include('pages.vendor.modal.create')
<!-- Modal-edit -->
@include('pages.vendor.modal.edit')
@endsection
@section('content')
<div class="head-pages">
    <p class="text-sm">Setting</p>
    <h2 class="text-2xl font-bold text-theme-primary tracking-tighter">
        setting
    </h2>
</div>
<div class="body-pages">
    <form action="{{ route('setting.store') }}" class="p-8" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form p-4 border bg-white">
            <div class="form-group grid lg:grid-cols-3 md:grid-cols-2 grid-cols-1">
                <div class="input-box space-y-3">
                    <div class="p-5 space-y-4">
                        <label
                            for=""
                            class="uppercase"
                            >Pusher App Id</label
                        >
                        <input
                            type="text"
                            class="p-2 w-full border @error('pusher_app_id') border-theme-primary @enderror"
                            value="{{ old('pusher_app_id', $data->pusher_app_id ?? '') }}"
                            name="pusher_app_id"
                        />
                        @error('pusher_app_id')
                            <div class="text-theme-primary">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="input-box space-y-3">
                    <div class="p-5 space-y-4">
                        <label
                            for=""
                            class="uppercase"
                            >Pusher App key</label
                        >
                        <input
                            type="text"
                            class="p-2 w-full border @error('pusher_app_key') border-theme-primary @enderror"
                            value="{{ old('pusher_app_key', $data->pusher_app_key ?? '') }}"
                            name="pusher_app_key"
                        />
                        @error('pusher_app_key')
                            <div class="text-theme-primary">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="input-box space-y-3">
                    <div class="p-5 space-y-4">
                        <label
                            for=""
                            class="uppercase"
                            >Pusher App Secret</label
                        >
                        <input
                            type="text"
                            class="p-2 w-full border @error('pusher_app_secret') border-theme-primary @enderror"
                            value="{{ old('pusher_app_secret', $data->pusher_app_secret ?? '') }}"
                            name="pusher_app_secret"
                        />
                        @error('pusher_app_secret')
                            <div class="text-theme-primary">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="form-group grid lg:grid-cols-3 md:grid-cols-2 grid-cols-1">
                <div class="input-box space-y-3">
                    <div class="p-5 space-y-4">
                        <label
                            for=""
                            class="uppercase"
                            >Pusher Cluster</label
                        >
                        <input
                            type="text"
                            class="p-2 w-full border @error('pusher_cluster') border-theme-primary @enderror"
                            value="{{ old('pusher_cluster', $data->pusher_cluster ?? '') }}"
                            name="pusher_cluster"
                        />
                        @error('pusher_cluster')
                            <div class="text-theme-primary">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="input-box space-y-3">
                    <div class="p-5 space-y-4">
                        <label
                            for=""
                            class="uppercase"
                            >Los Host</label
                        >
                        <input
                            type="text"
                            class="p-2 w-full border @error('los_host') border-theme-primary @enderror"
                            value="{{ old('los_host', $data->los_host ?? '') }}"
                            name="los_host"
                        />
                        @error('los_host')
                            <div class="text-theme-primary">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="input-box space-y-3">
                    <div class="p-5 space-y-4">
                        <label
                            for=""
                            class="uppercase"
                            >Los Api Host</label
                        >
                        <input
                            type="text"
                            class="p-2 w-full border @error('los_api_host') border-theme-primary @enderror"
                            value="{{ old('los_api_host', $data->los_api_host ?? '') }}"
                            name="los_api_host"
                        />
                        @error('los_api_host')
                            <div class="text-theme-primary">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="form-group grid lg:grid-cols-3 md:grid-cols-2 grid-cols-1">
                <div class="input-box space-y-3">
                    <div class="p-5 space-y-4">
                        <label
                            for=""
                            class="uppercase"
                            >Los Assets Url</label
                        >
                        <input
                            type="text"
                            class="p-2 w-full border @error('los_asset_url') border-theme-primary @enderror"
                            value="{{ old('los_asset_url', $data->los_asset_url ?? '') }}"
                            name="los_asset_url"
                        />
                        @error('los_asset_url')
                            <div class="text-theme-primary">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="input-box space-y-3">
                    <div class="p-5 space-y-4">
                        <label
                            for=""
                            class="uppercase"
                            >Bio Interface Api host</label
                        >
                        <input
                            type="text"
                            class="p-2 w-full border @error('bio_interface_api_host') border-theme-primary @enderror"
                            value="{{ old('bio_interface_api_host', $data->bio_interface_api_host ?? '') }}"
                            name="bio_interface_api_host"
                        />
                        @error('bio_interface_api_host')
                            <div class="text-theme-primary">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="input-box space-y-3">
                    <div class="p-5 space-y-4">
                        <label
                            for=""
                            class="uppercase"
                            >Collection api host</label
                        >
                        <input
                            type="text"
                            class="p-2 w-full border @error('collection_api_host') border-theme-primary @enderror"
                            value="{{ old('collection_api_host', $data->collection_api_host ?? '') }}"
                            name="collection_api_host"
                        />
                        @error('collection_api_host')
                            <div class="text-theme-primary">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="input-box space-y-3">
                    <div class="p-5 space-y-4">
                        <label
                            for=""
                            class="uppercase"
                            >Microsft graph client id</label
                        >
                        <input
                            type="text"
                            class="p-2 w-full border @error('microsoft_graph_client_id') border-theme-primary @enderror"
                            value="{{ old('microsoft_graph_client_id', $data->microsoft_graph_client_id ?? '') }}"
                            name="microsoft_graph_client_id"
                        />
                        @error('microsoft_graph_client_id')
                            <div class="text-theme-primary">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="input-box space-y-3">
                    <div class="p-5 space-y-4">
                        <label
                            for=""
                            class="uppercase"
                            >microsoft graph client secret</label
                        >
                        <input
                            type="text"
                            class="p-2 w-full border @error('microsoft_graph_client_secret') border-theme-primary @enderror"
                            value="{{ old('microsoft_graph_client_secret', $data->microsoft_graph_client_secret ?? '') }}"
                            name="microsoft_graph_client_secret"
                        />
                        @error('microsoft_graph_client_secret')
                            <div class="text-theme-primary">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="input-box space-y-3">
                    <div class="p-5 space-y-4">
                        <label
                            for=""
                            class="uppercase"
                            >microsoft graph tenant id</label
                        >
                        <input
                            type="text"
                            class="p-2 w-full border @error('microsoft_graph_tenant_id') border-theme-primary @enderror"
                            value="{{ old('microsoft_graph_tenant_id', $data->microsoft_graph_tenant_id ?? '') }}"
                            name="microsoft_graph_tenant_id"
                        />
                        @error('microsoft_graph_tenant_id')
                            <div class="text-theme-primary">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="p-3">
            <button
                class="text-white bg-theme-primary px-8 py-3 rounded-md font-bold"
            >
                Simpan
            </button>
            </div>
        </div>
    </form>
</div>

@push('extraScript')
<script>
    $('#page_length').on('change', function() {
        $('#form').submit()
    })

    $(".add-modal-vendor").on("click", function () {
        var targetId = 'add-vendor';
        $("#" + targetId).removeClass("hidden");
        form.addClass("layout-form-collapse");
        if (targetId.slice(0, 5) !== "modal") {
            $(".layout-overlay-form").removeClass("hidden");
        }
    });

    $(".edit-modal-vendor").on("click", function () {
        var targetId = 'edit-vendor';

        const data_id = $(this).data('id')
        const data_pusher_app_secret = $(this).data('pusher_app_secret')
        const data_name = $(this).data('name')
        const data_phone = $(this).data('phone')
        const data_address = $(this).data('address')

        $(`#${targetId} #edit-id`).val(data_id)
        $(`#${targetId} #edit-name`).val(data_name)
        $(`#${targetId} #edit-pusher_app_secret`).val(data_pusher_app_secret)
        $(`#${targetId} #edit-phone`).val(data_phone)
        $(`#${targetId} #edit-address`).val(data_address)

        $("#" + targetId).removeClass("hidden");
        $(".layout-form").addClass("layout-form-collapse");
        if (targetId.slice(0, 5) !== "modal") {
            $(".layout-overlay-form").removeClass("hidden");
        }
    });

    $("[data-dismiss-id]").on("click", function () {
        var dismissId = $(this).data("dismiss-id");
        $("#" + dismissId).addClass("hidden");
        if (dismissId.slice(0, 5) !== "modal") {
            $(".layout-overlay-form").addClass("hidden");
        }
    });

    $("#simpanButton").on('click', function(e) {
        e.preventDefault();
        const req_name = document.getElementById('add-name')
        const req_phone = document.getElementById('add-phone')
        const req_pusher_app_secret = document.getElementById('add-pusher_app_secret')
        const req_address = document.getElementById('add-address')

        $.ajax({
            type: "POST",
            url: "{{ route('vendor.store') }}",
            data: {
                _token: "{{ csrf_token() }}",
                name: req_name.value,
                phone: req_phone.value,
                pusher_app_secret: req_pusher_app_secret.value,
                address: req_address.value,
            },
            success: function(data) {
                console.log(data);
                if (Array.isArray(data.error)) {
                    for (var i = 0; i < data.error.length; i++) {
                        var message = data.error[i];

                        if (message.toLowerCase().includes('name'))
                            showError(req_name, message)
                        if (message.toLowerCase().includes('nomor'))
                            showError(req_phone, message)
                        if (message.toLowerCase().includes('pusher_app_secret'))
                            showError(req_pusher_app_secret, message)
                        if (message.toLowerCase().includes('address'))
                            showError(req_address, message)
                    }
                } else {
                    if (data.status == 'success') {
                        SuccessMessage(data.message);
                    } else {
                        ErrorMessage(data.message)
                    }
                    $('#add-vendor').addClass('hidden')
                }
            },
            error: function(e) {
                console.log(e)
            }
        });
    });

    $('#edit-button').click(function(e) {
        e.preventDefault()
        const req_id = document.getElementById('edit-id')
        const req_name = document.getElementById('edit-name')
        const req_phone = document.getElementById('edit-phone')
        const req_pusher_app_secret = document.getElementById('edit-pusher_app_secret')
        const req_address = document.getElementById('edit-address')
        const req_password = document.getElementById('edit-password')

        if (req_name == '') {
            showError(req_name, 'pusher_app_key harus diisi.')
            return false;
        }
        if (req_phone == '') {
            showError(req_phone, 'Nomor HP harus diisi.')
            return false;
        }
        if (req_pusher_app_secret == '') {
            showError(req_pusher_app_secret, 'pusher_app_secret harus diisi.')
            return false;
        }
        if (req_address == '') {
            showError(req_address, 'pusher_cluster harus diisi.')
            return false;
        }

        $.ajax({
            type: "POST",
            url: "{{ url('/master/vendor') }}/" + req_id.value,
            data: {
                _token: "{{ csrf_token() }}",
                _method: 'PUT',
                name: req_name.value,
                phone: req_phone.value,
                pusher_app_secret: req_pusher_app_secret.value,
                address: req_address.value,
                password: req_password.value,
            },
            success: function(data) {
                console.log(data);
                if (Array.isArray(data.error)) {
                    for (var i = 0; i < data.error.length; i++) {
                        var message = data.error[i];

                        if (message.toLowerCase().includes('name'))
                            showError(req_name, message)
                        if (message.toLowerCase().includes('nomor'))
                            showError(req_phone, message)
                        if (message.toLowerCase().includes('pusher_app_secret'))
                            showError(req_pusher_app_secret, message)
                        if (message.toLowerCase().includes('address'))
                            showError(req_address, message)
                        if (message.toLowerCase().includes('cabang'))
                            showError(req_cabang_id, message)
                    }
                } else {
                    if (data.status == 'success') {
                        SuccessMessage(data.message);
                    } else {
                        ErrorMessage(data.message)
                    }
                }
            },
            error: function(e) {
                console.log(e)
            }
        });
    })

    $('.btn-delete-vendor').on('click', function(e) {
        const data_id = $(this).data('id')
        Swal.fire({
            title: 'Konfirmasi',
            html: 'Anda yakin akan menghapus data ini?',
            icon: 'question',
            iconColor: '#DC3545',
            showCancelButton: true,
            confirmButtonText: 'Ya',
            cancelButtonText: `Batal`,
            confirmButtonColor: '#DC3545'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "POST",
                    url: "{{ url('/master/vendor') }}/"+data_id,
                    data: {
                        _token: "{{ csrf_token() }}",
                        _method: 'DELETE',
                    },
                    success: function(data) {
                        console.log(data)
                        if (data.status == 'success') {
                            SuccessMessage(data.message);
                        } else {
                            ErrorMessage(data.message)
                        }
                    }
                });
            }
        })
    })

    function showError(input, message) {
        /*
        const formGroup = input.parentElement;
        const errorSpan = formGroup.querySelector('.error');

        formGroup.classList.add('has-error');
        errorSpan.innerText = message;
        input.focus();
        */
    }
</script>
@endpush
@endsection
