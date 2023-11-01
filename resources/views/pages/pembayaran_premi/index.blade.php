@extends('layout.master')
@push('extraStyle')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.7.7/xlsx.core.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xls/0.7.4-a/xls.core.min.js"></script>
@endpush
@section('modal')
    <!-- Modal-tambah -->
    @include('pages.perusahaan_asuransi.modal.create')
    <!-- Modal-edit -->
    @include('pages.perusahaan_asuransi.modal.edit')
    <!-- Modal-Rincian bayar -->
    @include('pages.pembayaran_premi.modal.modal-rincian-bayar')
@endsection
@section('content')
<div class="head-pages">
    <p class="text-sm">Master</p>
    <h2 class="text-2xl font-bold text-theme-primary tracking-tighter">
        Pembayaran Premi
    </h2>
</div>
<div class="body-pages">
    <div class="table-wrapper bg-white border rounded-md w-full p-2">
        <div class="table-accessiblity lg:flex text-center lg:space-y-0 space-y-5 justify-between">
            <div class="title-table lg:p-3 p-2 text-center">
                <h2 class="font-bold text-lg text-theme-text tracking-tighter">
                    Pembayaran Premi
                </h2>
            </div>
            <div class="table-action flex lg:justify-normal justify-center p-2 gap-2">
                <a href="{{route('asuransi.pembayaran-premi.create')}}" class="px-6 py-2 bg-theme-primary flex gap-3 rounded text-white">
                    <span class="lg:mt-0 mt-0">
                        @include('components.svg.plus')
                    </span>
                    <span class="lg:block hidden"> Tambah Pembayaran Premi </span>
                </a>
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
                <form action="{{ route('asuransi.pembayaran-premi.index') }}" method="GET">
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
                    <th>No Aplikasi</th>
                    <th>No Polis</th>
                    <th>No Bukti Pembayaran</th>
                    <th>Tanggal Bayar</th>
                    <th>Total Premi</th>
                    <th>No Rekening</th>
                    <th>No PK</th>
                    <th>Periode Bayar</th>
                    <th>Total Periode Dalam Tahun</th>
                    <th>Aksi</th>
                </tr>
                <tbody>
                    @forelse ($data as $item)
                        <form action="{{ route('asuransi.pembayaran_premi.inquery') }}" method="post">
                            @csrf
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <input type="hidden" name="row_no_aplikasi" value="{{ $item->no_aplikasi }}">
                                    {{ $item->no_aplikasi }}
                                </td>
                                <td>
                                    <input type="hidden" name="row_no_polis" value="{{ $item->no_polis }}">
                                    {{ $item->no_polis }}
                                </td>
                                <td>
                                    <input type="hidden" name="row_nobukti_pembayaran" value="{{ $item->nobukti_pembayaran }}">
                                    {{ $item->nobukti_pembayaran }}
                                </td>
                                <td>{{ $item->tgl_bayar }}</td>
                                <td>
                                    <input type="hidden" name="row_outstanding" value="{{ $item->total_premi }}">
                                    {{ number_format((int)$item->total_premi, 0, ',', '.') }}
                                </td>
                                <td>
                                    <input type="hidden" name="row_no_rek" value="{{ $item->no_rek }}">
                                    {{ $item->no_rek }}
                                </td>
                                <td>{{ $item->no_pk }}</td>
                                <td>
                                    <input type="hidden" name="row_periode_premi" value="{{ $item->periode_bayar }}">
                                    {{ $item->periode_bayar }}
                                </td>
                                <td>{{ $item->total_periode }}</td>

                                <td>
                                    <div class="dropdown">
                                        <button class="px-4 py-2 bg-theme-btn/10 rounded text-theme-btn">
                                            Selangkapnya
                                        </button>
                                        <ul class="dropdown-menu">
                                            <button type="submit" class="item-dropdown">
                                                Inquery
                                            </button>
                                            {{-- <li class="">
                                                <a class="item-dropdown" href="#" onclick="alertWarning()">Inquery</a>
                                            </li> --}}
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        </form>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center">
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
                    {{-- @if($data instanceof \Illuminate\Pagination\LengthAwarePaginator )
                    {{ $data->links('pagination::tailwind') }}
                    @endif --}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('extraScript')
<script>
    $('#page_length').on('change', function() {
        $('#form').submit()
    })

    $(".add-modal-perusahaan-asuransi").on("click", function () {
        var targetId = 'add-perusahaan-asuransi';
        $("#" + targetId).removeClass("hidden");
        form.addClass("layout-form-collapse");
        if (targetId.slice(0, 5) !== "modal") {
            $(".layout-overlay-form").removeClass("hidden");
        }
    });

    $(".edit-modal-perusahaan-asuransi").on("click", function () {
        var targetId = 'edit-perusahaan-asuransi';

        const data_id = $(this).data('id')
        const data_nama = $(this).data('nama')
        const data_telp = $(this).data('telp')
        const data_alamat = $(this).data('alamat')

        $(`#${targetId} #edit-id`).val(data_id)
        $(`#${targetId} #edit-nama`).val(data_nama)
        $(`#${targetId} #edit-telp`).val(data_telp)
        $(`#${targetId} #edit-alamat`).val(data_alamat)

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
        const req_nama = document.getElementById('add-nama')
        const req_alamat = document.getElementById('add-alamat')
        const req_telp = document.getElementById('add-telp')

        if (req_nama == '') {
            showError(req_nama, 'Nama harus diisi.');
            return false;
        }
        if (req_alamat == '') {
            showError(req_alamat, 'Alamat harus diisi.');
            return false;
        }
        if (req_telp == '') {
            showError(req_telp, 'Nomor HP harus diisi.');
            return false;
        }

        $.ajax({
            type: "POST",
            url: "{{ route('perusahaan-asuransi.store') }}",
            data: {
                _token: "{{ csrf_token() }}",
                nama: req_nama.value,
                alamat: req_alamat.value,
                telp: req_telp.value,
            },
            success: function(data) {
                console.log(data.message);
                if (Array.isArray(data.error)) {
                    for (var i = 0; i < data.error.length; i++) {
                        var message = data.error[i];

                        if (message.toLowerCase().includes('nama'))
                            showError(req_nama, message)
                        if (message.toLowerCase().includes('alamat'))
                            showError(req_alamat, message)
                        if (message.toLowerCase().includes('telp'))
                            showError(req_telp, message)
                    }
                } else {
                  SuccessMessage(data.message);
                    // if (data.status == 'success') {
                    //     SuccessMessage(data.message);
                    // } else {
                    //     ErrorMessage(data.message)
                    // }
                    $('#add-perusahaan-asuransi').addClass('hidden')
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
        const req_nama = document.getElementById('edit-nama')
        const req_telp = document.getElementById('edit-telp')
        const req_alamat = document.getElementById('edit-alamat')

        if (req_nama == '') {
            showError(req_nama, 'Nama harus diisi.')
            return false;
        }
        if (req_telp == '') {
            showError(req_telp, 'Nomor HP harus diisi.')
            return false;
        }
        if (req_alamat == '') {
            showError(req_alamat, 'Alamat harus diisi.')
            return false;
        }

        $.ajax({
            type: "POST",
            url: "{{ url('/master/perusahaan-asuransi') }}/" + req_id.value,
            data: {
                _token: "{{ csrf_token() }}",
                _method: 'PUT',
                nama: req_nama.value,
                alamat: req_alamat.value,
                telp: req_telp.value,
            },
            success: function(data) {
                console.log(data.message);
                if (Array.isArray(data.error)) {
                    for (var i = 0; i < data.error.length; i++) {
                        var message = data.error[i];

                        if (message.toLowerCase().includes('nama'))
                            showError(req_nama, message)
                        if (message.toLowerCase().includes('alamat'))
                            showError(req_alamat, message)
                        if (message.toLowerCase().includes('telp'))
                            showError(req_telp, message)
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

    $('.btn-delete-perusahaan-asuransi').on('click', function(e) {
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
                    url: "{{ url('/master/perusahaan-asuransi') }}/"+data_id,
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

    //$('.add-modal-pembayaran-premi').on('click', function (e) {
    //  alertWarning()
    //});

    function alertWarning() {
        Swal.fire({
            title: 'Warning',
            html: 'Data Masih Belum Dilengkapi!',
            icon: 'warning',
            iconColor: '#DC3545',
            confirmButtonText: 'OK',
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
