@extends('layout.master')
@section('modal')
<!-- Modal-tambah -->
@include('pages.jenis_asuransi.modal.create')
<!-- Modal-edit -->
@include('pages.jenis_asuransi.modal.edit')
@endsection
@section('content')
<div class="head-pages">
    <p class="text-sm">Master</p>
    <h2 class="text-2xl font-bold text-theme-primary tracking-tighter">
        Jenis Asuransi
    </h2>
</div>
<div class="body-pages">
    <div class="table-wrapper bg-white border rounded-md w-full p-2">
        <div class="table-accessiblity lg:flex text-center lg:space-y-0 space-y-5 justify-between">
            <div class="title-table lg:p-3 p-2 text-center">
                <h2 class="font-bold text-lg text-theme-text tracking-tighter">
                    Jenis Asuransi
                </h2>
            </div>
            <div class="table-action flex lg:justify-normal justify-center p-2 gap-2">
                <button data-target-id="add-jenis-asuransi"
                    class="add-modal-jenis-asuransi px-6 py-2 bg-theme-primary flex gap-3 rounded text-white">
                    <span class="lg:mt-0 mt-0">
                        @include('components.svg.plus')
                    </span>
                    <span class="lg:block hidden"> Tambah Jenis Asuransi </span>
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
                <form action="{{ route('jenis-asuransi.index') }}" method="GET">
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
                    <th>Produk Kredit Id</th>
                    <th>Jenis Kredit</th>
                    <th>Jenis</th>
                    <th>Aksi</th>
                </tr>
                <tbody>
                    @forelse ($data as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->produk_kredit_id != NULL ? $item->produk_kredit_id : "-" }}</td>
                        <td>{{ $item->jenis_kredit }}</td>
                        <td>{{ $item->jenis }}</td>
                        <td>
                            <div class="dropdown max-w-[280px]">
                                <button class="px-4 py-2 bg-theme-btn/10 rounded text-theme-btn">
                                    Selengkapnya
                                </button>
                                <ul class="dropdown-menu">
                                    <li class="">
                                        <a class="item-dropdown edit-modal-jenis-asuransi" data-target-id="edit-jenis-asuransi" href="#"
                                        data-id="{{ $item->id }}"
                                        data-jenis-kredit="{{ $item->jenis_kredit }}"
                                        data-jenis="{{ $item->jenis }}">Edit</a>
                                    </li>
                                    <li class="">
                                        <a class="item-dropdown btn-delete-jenis-asuransi"
                                        href="#"
                                        data-id="{{ $item->id }}"
                                        data-jenis-kredit="{{ $item->jenis_kredit }}">Hapus</a>
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
    $(document).ready(function() {
        $('.select-jenis-kredit').select2({
            width: 'resolve',
        });
    });
    $('#page_length').on('change', function() {
        $('#form').submit()
    })

    $(".add-modal-jenis-asuransi").on("click", function () {
        var targetId = 'add-jenis-asuransi';
        $("#" + targetId).removeClass("hidden");
        form.addClass("layout-form-collapse");
        if (targetId.slice(0, 5) !== "modal") {
            $(".layout-overlay-form").removeClass("hidden");
        }
    });

    $(".edit-modal-jenis-asuransi").on("click", function () {
        var targetId = 'edit-jenis-asuransi';

        var data_id = '';
        var data_jenis_kredit = '';
        var data_jenis = '';

        if (typeof $(this).data('id') !== 'undefined') {
            data_id = $(this).data('id');
        }
        if (typeof $(this).data('jenis-kredit') !== 'undefined') {
            data_jenis_kredit = $(this).data('jenis-kredit');
        }
        if (typeof $(this).data('jenis') !== 'undefined') {
            data_jenis = $(this).data('jenis');
        }

        $(`#${targetId} #edit-id`).val(data_id)
        $(`#${targetId} .edit-jenis-kredit`).val(data_jenis_kredit).change()
        $(`#${targetId} #edit-jenis`).val(data_jenis)
        
        $(`#${targetId}`).removeClass("hidden");
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
        const req_jenis_kredit = document.getElementById('add-jenis-kredit')
        const req_jenis = document.getElementById('add-jenis')

        if (req_jenis_kredit == '') {
            showError(req_jenis_kredit, 'Jenis Kredit harus diisi.');
            return false;
        }
        if (req_jenis == '') {
            showError(req_jenis, 'Jenis harus diisi.');
            return false;
        }

        $.ajax({
            type: "POST",
            url: "{{ route('jenis-asuransi.store') }}",
            data: {
                _token: "{{ csrf_token() }}",
                jenis_kredit: req_jenis_kredit.value,
                jenis: req_jenis.value,
            },
            success: function(data) {
                console.log(data.message);
                if (Array.isArray(data.error)) {
                    for (var i = 0; i < data.error.length; i++) {
                        var message = data.error[i];

                        if (message.toLowerCase().includes('jenis_kredit'))
                            showError(req_jenis_kredit, message)
                        if (message.toLowerCase().includes('jenis'))
                            showError(req_jenis, message)
                    }
                } else {
                  SuccessMessage(data.message);
                    // if (data.status == 'success') {
                    //     SuccessMessage(data.message);
                    // } else {
                    //     ErrorMessage(data.message)
                    // }
                    $('#add-jenis-asuransi').addClass('hidden')
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
        const req_jenis_kredit = document.getElementById('edit-jenis-kredit')
        const req_jenis = document.getElementById('edit-jenis')

        if (req_jenis_kredit == '') {
            showError(req_jenis_kredit, 'Jenis Kredit harus diisi.');
            return false;
        }
        if (req_jenis == '') {
            showError(req_jenis, 'Jenis harus diisi.');
            return false;
        }

        $.ajax({
            type: "POST",
            url: "{{ url('/master/jenis-asuransi') }}/" + req_id.value,
            data: {
                _token: "{{ csrf_token() }}",
                _method: 'PUT',
                jenis_kredit: req_jenis_kredit.value,
                jenis: req_jenis.value,
            },
            success: function(data) {
                console.log(data.message);
                if (Array.isArray(data.error)) {
                    for (var i = 0; i < data.error.length; i++) {
                        var message = data.error[i];

                        if (message.toLowerCase().includes('jenis_kredit'))
                            showError(req_jenis_kredit, message)
                        if (message.toLowerCase().includes('jenis'))
                            showError(req_jenis, message)
                    }
                } else {
                    // if (data.status == 'success') {
                    //     SuccessMessage(data.message);
                    // } else {
                    //     ErrorMessage(data.message)
                    // }
                    SuccessMessage(data.message);
                }
            },
            error: function(e) {
                console.log(e)
            }
        });
    })

    $('.btn-delete-jenis-asuransi').on('click', function(e) {
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
                    url: "{{ url('/master/jenis-asuransi') }}/"+data_id,
                    data: {
                        _token: "{{ csrf_token() }}",
                        _method: 'DELETE',
                    },
                    success: function(data) {
                        console.log(data)
                        SuccessMessage(data.message);
                        // if (data.status == 'success') {
                        //     SuccessMessage(data.message);
                        // } else {
                        //     ErrorMessage(data.message)
                        // }
                    }
                });
            }
        })
    })

    function showError(input, message) {
        console.log(message);
        const formGroup = input.parentElement;
        const errorSpan = formGroup.querySelector('.error');

        formGroup.classList.add('has-error');
        errorSpan.innerText = message;
        input.focus();
    }
</script>
@endpush
@endsection