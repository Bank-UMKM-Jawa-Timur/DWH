@extends('layout.master')
@section('modal')
<!-- Modal-tambah -->
@include('pages.baki_debet.modal.create')
<!-- Modal-edit -->
@include('pages.baki_debet.modal.edit')
@endsection
@section('content')
<div class="head-pages">
    <p class="text-sm">Master</p>
    <h2 class="text-2xl font-bold text-theme-primary tracking-tighter">
        Rate Premi Baki Debet
    </h2>
</div>
<div class="body-pages">
    <div class="table-wrapper bg-white border rounded-md w-full p-2">
        <div class="table-accessiblity lg:flex text-center lg:space-y-0 space-y-5 justify-between">
            <div class="title-table lg:p-3 p-2 text-center">
                <h2 class="font-bold text-lg text-theme-text tracking-tighter">
                    Rate Premi Baki Debet
                </h2>
            </div>
            <div class="table-action flex lg:justify-normal justify-center p-2 gap-2">
                <button data-target-id="add-baki-debet"
                    class="add-modal-baki-debet px-6 py-2 bg-theme-primary flex gap-3 rounded text-white">
                    <span class="lg:mt-0 mt-0">
                        @include('components.svg.plus')
                    </span>
                    <span class="lg:block hidden"> Tambah Baki Debet </span>
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
                <form action="{{ route('perusahaan-asuransi.index') }}" method="GET">
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
                    <th>Masa Asuransi(Bulan)</th>
                    <th>Jenis</th>
                    <th>Rate Premi(%o)</th>
                    <th>Aksi</th>
                </tr>
                <tbody>
                    @forelse ($data as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->masa_asuransi1 }} {{ $item->masa_asuransi2 != 0 ? 's/d '. $item->masa_asuransi2 : '' }}</td>
                            <td>{{ $item->jenis ? 'Baki Debet' : '' }}</td>
                            <td>{{ $item->rate }}</td>
                            <td>
                                <div class="dropdown max-w-[280px]">
                                    <button class="px-4 py-2 bg-theme-btn/10 rounded text-theme-btn">
                                        Selengkapnya
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li class="">
                                            <a class="item-dropdown edit-modal-baki-debet" data-target-id="edit-baki-debet" href="#"
                                            data-id="{{ $item->id }}"
                                            data-masa-asuransi1="{{ $item->masa_asuransi1 }}"
                                            data-masa-asuransi2="{{ $item->masa_asuransi2 }}"
                                            data-rate="{{ $item->rate }}">Edit</a>
                                        </li>
                                        <li class="">
                                            <a class="item-dropdown btn-delete-baki-debet"
                                            href="#"
                                            data-id="{{ $item->id }}">Hapus</a>
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

    $(".add-modal-baki-debet").on("click", function () {
        var targetId = 'add-baki-debet';
        $("#" + targetId).removeClass("hidden");
        if (targetId.slice(0, 5) !== "modal") {
            $(".layout-overlay-form").removeClass("hidden");
        }
    });

    $(".edit-modal-baki-debet").on("click", function () {
        // console.log('masuk coy')
        var targetId = 'edit-baki-debet';

        const data_id = $(this).data('id')
        const data_masa_asuransi1 = $(this).data('masa-asuransi1')
        const data_masa_asuransi2 = $(this).data('masa-asuransi2')
        const data_rate = $(this).data('rate')
        $(`#${targetId} #edit-id`).val(data_id)
        $(`#${targetId} #edit-masa-asuransi1`).val(data_masa_asuransi1)
        if (data_masa_asuransi2 === 0) {
            $(`#${targetId} #edit-masa-asuransi2`).val('');
        }else {
            $(`#${targetId} #edit-masa-asuransi2`).val(data_masa_asuransi2)
        }

        $(`#${targetId} #edit-rate`).val(data_rate)

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

    $("#add-button").on('click', function(e) {
        e.preventDefault();
        const req_masa_asuransi1 = document.getElementById('add_masa_asuransi1')
        const req_masa_asuransi2 = document.getElementById('add_masa_asuransi2')
        const req_jenis = document.getElementById('add_jenis')
        const req_rate = document.getElementById('add_rate')

        if (req_masa_asuransi1 == '') {
            showError(req_masa_asuransi1, 'Masa Asuransi(Bulan) harus diisi.');
            return false;
        }
        if (req_jenis == '') {
            showError(req_jenis, 'Jenis harus diisi.');
            return false;
        }
        if (req_rate == '') {
            showError(req_rate, 'Rate harus diisi.');
            return false;
        }


        $.ajax({
            type: "POST",
            url: "{{ route('baki-debet.store') }}",
            data: {
                _token: "{{ csrf_token() }}",
                masa_asuransi1: req_masa_asuransi1.value,
                masa_asuransi2: req_masa_asuransi2.value,
                jenis: req_jenis.value,
                rate: req_rate.value,
            },
            success: function(data) {
                console.log(data);
                if (Array.isArray(data.error)) {
                    for (var i = 0; i < data.error.length; i++) {
                        var message = data.error[i];
                        if (message.toLowerCase().includes('masa asuransi(bulan)'))
                            alertWarning(message)
                        if (message.toLowerCase().includes('rate'))
                            alertWarning(message)
                    }
                } else {
                  SuccessMessage(data.message);
                    // if (data.status == 'success') {
                    //     SuccessMessage(data.message);
                    // } else {
                    //     ErrorMessage(data.message)
                    // }
                    $('#add-baki-debet').addClass('hidden')
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
        const req_masa_asuransi1 = document.getElementById('edit-masa-asuransi1')
        const req_masa_asuransi2 = document.getElementById('edit-masa-asuransi2')
        const req_rate = document.getElementById('edit-rate')

        if (req_masa_asuransi1 == '') {
            showError(req_masa_asuransi1, 'Masa Asuransi(Bulan) harus diisi.');
            return false;
        }
        if (req_rate == '') {
            showError(req_rate, 'Rate harus diisi.');
            return false;
        }

        $.ajax({
            type: "POST",
            url: "{{ url('/master/baki-debet') }}/" + req_id.value,
            data: {
                _token: "{{ csrf_token() }}",
                _method: 'PUT',
                masa_asuransi1: req_masa_asuransi1.value,
                rate: req_rate.value,
            },
            success: function(data) {
                console.log(data);
                if (Array.isArray(data.error)) {
                    for (var i = 0; i < data.error.length; i++) {
                        var message = data.error[i];
                        if (message.toLowerCase().includes('masa asuransi(bulan)'))
                            alertWarning(message)
                        if (message.toLowerCase().includes('rate'))
                            alertWarning(message)
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

    $('.btn-delete-baki-debet').on('click', function(e) {
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
                    url: "{{ url('/master/baki-debet') }}/"+data_id,
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

    function alertWarning(message) {
        Swal.fire({
            title: 'Warning!',
            html: message,
            icon: 'warning',
            iconColor: '#DC3545',
            confirmButtonText: 'Ya',
            confirmButtonColor: '#DC3545'
        })
    }

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
