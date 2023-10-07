@extends('layout.master')
@section('modal')
<!-- Modal-tambah -->
@include('pages.vendor.modal.create')
<!-- Modal-edit -->
@include('pages.vendor.modal.edit')
@endsection
@section('content')
<div class="head-pages">
    <p class="text-sm">Master</p>
    <h2 class="text-2xl font-bold text-theme-primary tracking-tighter">
        Vendor
    </h2>
</div>
<div class="body-pages">
    <div class="table-wrapper bg-white border rounded-md w-full p-2">
        <div class="table-accessiblity lg:flex text-center lg:space-y-0 space-y-5 justify-between">
            <div class="title-table lg:p-3 p-2 text-center">
                <h2 class="font-bold text-lg text-theme-text tracking-tighter">
                    Vendor
                </h2>
            </div>
            <div class="table-action flex lg:justify-normal justify-center p-2 gap-2">
                <button data-target-id="add-vendor"
                    class="add-modal-vendor px-6 py-2 bg-theme-primary flex gap-3 rounded text-white">
                    <span class="lg:mt-0 mt-0">
                        @include('components.svg.plus')
                    </span>
                    <span class="lg:block hidden"> Tambah Vendor </span>
                </button>
            </div>
        </div>
        <div
            class="lg:flex lg:space-y-0 space-y-5 lg:text-left text-center justify-between mt-2 p-2">
            <div class="sorty pl-1 w-full">
                <form id="form" action="" method="GET">
                    <label for="" class="mr-3 text-sm text-neutral-400">show</label>
                    <select class="border px-4 py-1.5 cursor-pointer rounded appearance-none text-center"
                    name="page_length" id="page_length">
                        <option value="5" {{ Request::get('page_length') == '5' ? 'selected' : '' }}>5</option>
                        <option value="10" {{ Request::get('page_length') == '10' ? 'selected' : '' }}>10</option>
                        <option value="15" {{ Request::get('page_length') == '15' ? 'selected' : '' }}>15</option>
                        <option value="20" {{ Request::get('page_length') == '20' ? 'selected' : '' }}>20</option>
                        <option value="all" {{ Request::get('page_length') == 'all' ? 'selected' : '' }}>All</option>
                    </select>
                    <label for="" class="ml-3 text-sm text-neutral-400">entries</label>
                </form>
            </div>
            <div class="search-table lg:w-96 w-full">
                <form action="{{ route('vendor.index') }}" method="GET">
                    <div class="input-search text-[#BFBFBF] rounded-md border flex gap-2">
                        <span class="mt-2 ml-3">
                            @include('components.svg.search')
                        </span>
                            <input type="hidden" name="search_by" value="field">
                            <input type="search" placeholder="Search" class="p-2 rounded-md w-full outline-none text-[#BFBFBF]"
                                name="query" value="{{ old('query', Request()->query('query')) }}" autocomplete="off" />
                    </div>
                </form>
            </div>
        </div>
        <div class="tables mt-2">
            <table class="table-auto w-full">
                <tr>
                    <th>No.</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Alamat</th>
                    <th>Nomor HP</th>
                    <th>Aksi</th>
                </tr>
                <tbody>
                    @forelse ($data as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->email }}</td>
                        <td>{{ $item->address }}</td>
                        <td>{{ $item->phone }}</td>
                        <td>
                            <div class="dropdown max-w-[280px]">
                                <button class="px-4 py-2 bg-theme-btn/10 rounded text-theme-btn">
                                    Selengkapnya
                                </button>
                                <ul class="dropdown-menu">
                                    <li class="">
                                        <a class="item-dropdown edit-modal-vendor" data-target-id="edit-vendor" href="#"
                                        data-id="{{ $item->id }}"
                                        data-name="{{ $item->name }}"
                                        data-email="{{ $item->email }}"
                                        data-phone="{{ $item->phone }}"
                                        data-address="{{ $item->address }}">Edit</a>
                                    </li>
                                    <li class="">
                                        <a class="item-dropdown btn-delete-vendor"
                                        href="#"
                                        data-id="{{ $item->id }}"
                                        data-name="{{ $item->name }}">Hapus</a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">
                            <span class="text-danger">Maaf data belum tersedia.</span>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="footer-table p-3 text-theme-text lg:flex lg:space-y-0 space-y-10 justify-between">
            <div class="w-full">
                <div class="pagination">
                    @if($data instanceof \Illuminate\Pagination\LengthAwarePaginator )
                    {{ $data->links('pagination::tailwind') }}
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('extraScript')
<script>
    $('#page_length').on('change', function() {
        $('#form').submit()
    })

    $(".add-modal-vendor").on("click", function () {
        var targetId = 'add-vendor';
        $("#" + targetId).removeClass("hidden");
        // form.addClass("layout-form-collapse");
        if (targetId.slice(0, 5) !== "modal") {
            $(".layout-overlay-form").removeClass("hidden");
        }
    });

    $(".edit-modal-vendor").on("click", function () {
        var targetId = 'edit-vendor';

        const data_id = $(this).data('id')
        const data_email = $(this).data('email')
        const data_name = $(this).data('name')
        const data_phone = $(this).data('phone')
        const data_address = $(this).data('address')

        $(`#${targetId} #edit-id`).val(data_id)
        $(`#${targetId} #edit-name`).val(data_name)
        $(`#${targetId} #edit-email`).val(data_email)
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
        const req_email = document.getElementById('add-email')
        const req_address = document.getElementById('add-address')

        if (req_name == '') {
            showError(req_name, 'Nama harus diisi.');
            return false;
        }
        if (req_phone == '') {
            showError(req_phone, 'Nomor HP harus diisi.');
            return false;
        }
        if (req_email == '') {
            showError(req_email, 'Email harus diisi.');
            return false;
        }
        if (req_address == '') {
            showError(req_address, 'Alamat harus diisi.');
            return false;
        }

        $.ajax({
            type: "POST",
            url: "{{ route('vendor.store') }}",
            data: {
                _token: "{{ csrf_token() }}",
                name: req_name.value,
                phone: req_phone.value,
                email: req_email.value,
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
                        if (message.toLowerCase().includes('email'))
                            showError(req_email, message)
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
        const req_email = document.getElementById('edit-email')
        const req_address = document.getElementById('edit-address')
        const req_password = document.getElementById('edit-password')

        if (req_name == '') {
            showError(req_name, 'Nama harus diisi.')
            return false;
        }
        if (req_phone == '') {
            showError(req_phone, 'Nomor HP harus diisi.')
            return false;
        }
        if (req_email == '') {
            showError(req_email, 'Email harus diisi.')
            return false;
        }
        if (req_address == '') {
            showError(req_address, 'Alamat harus diisi.')
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
                email: req_email.value,
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
                        if (message.toLowerCase().includes('email'))
                            showError(req_email, message)
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