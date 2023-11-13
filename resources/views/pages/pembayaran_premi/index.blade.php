@extends('layout.master')
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
                @if ($role == 'Staf Analis Kredit')
                    <div class="table-action flex lg:justify-normal justify-center p-2 gap-2">
                        <a href="{{route('asuransi.pembayaran-premi.create')}}" class="px-6 py-2 bg-theme-primary flex gap-3 rounded text-white">
                            <span class="lg:mt-0 mt-0">
                                @include('components.svg.plus')
                            </span>
                            <span class="lg:block hidden"> Tambah Pembayaran Premi </span>
                        </a>
                    </div>
                @endif
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
                        <th>No Bukti Pembayaran</th>
                        <th>Tanggal Bayar</th>
                        <th>Total Premi</th>
                        <th>Aksi</th>
                    </tr>
                    <tbody>
                        @forelse ($data as $item)
                            <tr class="cursor-pointer">
                                <td>{{ $loop->iteration}}</td>
                                <td>
                                    {{ $item->nobukti_pembayaran }}
                                </td>
                                <td>{{ $item->tgl_bayar }}</td>
                                <td>
                                    {{ number_format((int)$item->total_premi, 0, ',', '.') }}
                                </td>
                                <td>
                                    <div class="flex gap-4 justify-center">
                                        <button type="button" id="btnAksi" class="view flex gap-2 hover:bg-slate-50 border px-3 py-2">
                                            <span class="caret-icon transform">
                                                @include('components.svg.caret')
                                            </span>
                                            <span id="text_collapse" class="collapse-text">Sembunyikan</span>
                                        </button>
                                        {{--  <button class="px-4 py-2 bg-theme-btn/10 rounded text-theme-btn">
                                            Detail
                                        </button>  --}}
                                    </div>
                                </td>
                            </tr>
                            <tr class="collapse-table">
                                <td class="bg-theme-primary/5"></td>
                                <td colspan="4" class="p-0">
                                    <div class="bg-theme-primary/5">
                                        <div>
                                            <div class="mt-0 p-3">
                                                <table class="table-collapse">
                                                    <thead>
                                                        <th>#</th>
                                                        <th>No Aplikasi</th>
                                                        <th>No Polis</th>
                                                        <th>No Rekening</th>
                                                        <th>No PK</th>
                                                        <th>Periode Bayar</th>
                                                        <th>Periode Tahun Bayar</th>
                                                        <th>Aksi</th>
                                                    </thead>
                                                    @php
                                                        $alpha = strtolower(chr(64+$loop->iteration));
                                                    @endphp
                                                    <tbody>
                                                        @foreach ($item->detail as $detail)
                                                            <tr>
                                                                <td>{{$alpha}}</td>
                                                                <td>
                                                                    {{ $detail->no_aplikasi }}
                                                                </td>
                                                                <td>
                                                                    {{ $detail->no_polis }}
                                                                </td>
                                                                <td>
                                                                    {{ $detail->no_rek }}
                                                                </td>
                                                                <td>{{ $detail->no_pk }}</td>
                                                                <td>{{ $detail->periode_bayar }}</td>
                                                                <td>
                                                                    {{ $detail->total_periode }}
                                                                </td>
                                                                <td>
                                                                    @if ($role == 'Staf Analis Kredit')
                                                                        <button class="px-4 py-2 bg-theme-btn/10 rounded text-theme-btn btn-inquery"
                                                                            data-id="{{ $detail->id }}"
                                                                            data-no_aplikasi="{{$detail->no_aplikasi}}" data-no_polis="{{$detail->no_polis}}"
                                                                            data-no_rek="{{$detail->no_rek}}" data-premi="{{$detail->premi}}"
                                                                            data-periode_premi="{{$detail->periode_bayar}}" data-nobukti_pembayaran="{{$item->nobukti_pembayaran}}">
                                                                            Inquery
                                                                        </button>
                                                                    @else
                                                                        {{--  <button class="px-4 py-2 bg-theme-btn/10 rounded text-theme-btn">
                                                                            Detail
                                                                        </button>  --}}
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
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
@endsection
@push('extraScript')
<script>
    $("table tbody tr .view").on("click", function(e) {
        const $collapseTable = $(this).parent().parent().parent().next(".collapse-table");
        $collapseTable.toggleClass("hidden");

        const $textCollapse = $(this).find('#text_collapse');

        if ($collapseTable.hasClass('hidden')) {
            $textCollapse.text("Tampilkan");
        } else {
            $textCollapse.text("Sembunyikan");
        }
    });
    $('.dropdown .dropdown-menu .item-dropdown').on('click', function(e){
        e.stopPropagation();
    })

    $('#page_length').on('change', function() {
        $('#form').submit()
    })

    $("[data-dismiss-id]").on("click", function () {
        var dismissId = $(this).data("dismiss-id");
        $("#" + dismissId).addClass("hidden");
        if (dismissId.slice(0, 5) !== "modal") {
            $(".layout-overlay-form").addClass("hidden");
        }
    });

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

    $(".table-auto").on("click", ".btn-inquery", function(e){
        $("#preload-data").removeClass("hidden");
        e.preventDefault()
        var token = generateCsrfToken()
        const id = $(this).data('id')
        const no_aplikasi = $(this).data('no_aplikasi')
        const nobukti_pembayaran = $(this).data('nobukti_pembayaran')
        const no_rek = $(this).data('no_rek')
        const no_polis = $(this).data('no_polis')
        const premi = parseInt($(this).data('premi'))
        const periode_premi = $(this).data('periode_premi')
        
        var data = {
            _token: token,
            id: id,
            no_aplikasi: no_aplikasi,
            nobukti_pembayaran: nobukti_pembayaran,
            no_rekening: no_rek,
            outstanding: premi,
            periode_premi: periode_premi,
            no_polis: no_polis
        }
        
        $.ajax({
            type: "POST",
            url: "{{ route('asuransi.pembayaran_premi.inquery') }}",
            data: data,
            success: function(res){
                if(res.status == 'Berhasil'){
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: `${res.response.keterangan}, Nilai Premi Rp. ${formatRupiah(res.response.nilai_premi)}`
                    });
                } else{
                    Swal.fire({
                        icon: "error",
                        title: "Gagal",
                        text: res.response
                    })
                }
                $("#preload-data").addClass("hidden");
            },
            error: function(res){
                $("#preload-data").addClass("hidden");
                Swal.fire({
                    icon: "error",
                    title: "Gagal",
                    text: "Terjadi kesalahan."
                })
            }
        })
    })
</script>
@endpush
