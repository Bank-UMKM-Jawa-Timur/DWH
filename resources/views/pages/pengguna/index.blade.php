@extends('layout.master')
@section('modal')
<!-- Modal-tambah -->
@include('pages.pengguna.modal.create')
<!-- Modal-reset-password -->
@include('pages.pengguna.modal.reset-password')
<!-- Modal-edit -->
@include('pages.pengguna.modal.edit')
@endsection
@section('content')
<div class="head-pages">
    <p class="text-sm">Master</p>
    <h2 class="text-2xl font-bold text-theme-primary tracking-tighter">
        Pengguna
    </h2>
</div>
<div class="body-pages">
    <div class="table-wrapper bg-white border rounded-md w-full p-2">
        <div class="table-accessiblity lg:flex text-center lg:space-y-0 space-y-5 justify-between">
            <div class="title-table lg:p-3 p-2 text-center">
                <h2 class="font-bold text-lg text-theme-text tracking-tighter">
                    Pengguna
                </h2>
            </div>
            <div class="table-action flex lg:justify-normal justify-center p-2 gap-2">
                <button data-target-id="add-pengguna" id="open-add-modal" class="toggle-modal px-6 py-2 bg-theme-primary flex gap-3 rounded text-white">
                    <span class="lg:mt-0 mt-0">
                        @include('components.svg.plus')
                    </span>
                    <span class="lg:block hidden"> Tambah Pengguna </span>
                </button>
            </div>
        </div>
        <div class="lg:flex lg:space-y-0 space-y-5 lg:text-left text-center justify-between mt-2 p-2">
            <div class="sorty pl-1 w-full">
                <form id="form" action="" method="GET">
                    {{-- <input type="hidden" name="page" value="{{isset($_GET['page']) ? $_GET['page'] : 1}}"> --}}
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
                <form action="{{ route('pengguna.index') }}" method="GET">
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
                    <th>NIP</th>
                    <th>Email</th>
                    <th>Kantor</th>
                    <th>Nama Peran</th>
                    <th>Aksi</th>
                </tr>
                <tbody>
                    @forelse ($data as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->nip ? $item->nip : '-' }}</td>
                        <td>{{ $item->email ? $item->email : '-' }}</td>
                        <td>
                            @if ($item->detail != null || property_exists($item, 'detail'))
                                @if (array_key_exists('entitas', $item->detail))
                                    @if ($item->detail['entitas']['type'] == 1)
                                        Pusat
                                    @else
                                        {{$item->detail['entitas']['cab']['nama_cabang']}}
                                    @endif
                                @else
                                    undifined
                                @endif
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ $item->role }}</td>
                        <td>
                            <div class="dropdown ">
                                <button class="px-4 py-2 bg-theme-btn/10 rounded text-theme-btn">
                                    Selengkapnya
                                </button>
                                <ul class="dropdown-menu left-3">
                                    <li>
                                        <a class="item-dropdown toggle-modal btn-edit" data-id="{{ $item->id }}"
                                            data-name="{{ $item->name }}">
                                            Edit
                                        </a>
                                    </li>
                                    <li>
                                        <a class="item-dropdown btn-delete" data-id="{{ $item->id }}"
                                            data-name="{{ $item->name }}">
                                            Hapus
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            {{--  <div class="dropdown">
                                <button class="btn btn-sm btn-info dropdown-toggle" type="button"
                                    data-toggle="dropdown" aria-expanded="false">
                                    Selengkapnya
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item buttonresetpassword" data-toggle="modal"
                                        data-target="#resetPasswordModal" data-id="{{ $item->id }}"
                                        href="#" id="buttonresetpassword">Reset
                                        Password</a>
                                    <a class="dropdown-item editModal" data-toggle="modal"
                                        data-target="#editModal" data-id="{{ $item->id }}"
                                        data-nip="{{ $item->nip }}" data-email="{{ $item->email }}"
                                        data-role="{{ $item->role_id }}" href="#">Edit</a>
                                    <a class="dropdown-item deleteModal" data-toggle="modal"
                                        data-target="#deleteModal" data-id="{{ $item->id }}"
                                        href="#">Hapus</a>
                                </div>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="#">Edit</a>
                                </div>
                            </div>  --}}
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
    $('.pagination span').each(function() {
        var id = $(this).attr('aria-current', 'page')
        //var span = $(`#${id}`).find('relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default leading-5')
        console.log(id)
    })
    $('#page_length').on('change', function() {
        $('#form').submit()
    })

    // Modal
    // $('#open-add-modal').click(function() {
    //     console.log("open modal");
    //     $.ajax({
    //         type: "GET",
    //         url: "{{ route('role.list') }}",
    //         success: function(data) {
    //             console.log(data)
    //             if (data) {
    //                 for (i in data) {
    //                     $("#add-role").append(`<option value="` + data[i].id + `">` + data[i].name +
    //                         `</option>`);
    //                 }
    //             }
    //         }
    //     });
    // });

    // $('#add-button').click(function(e) {
    //     e.preventDefault()

    //     store();
    // })

    // function store() {
    //     const req_nip = document.getElementById('add-nip')
    //     const req_email = document.getElementById('add-email')
    //     const req_password = document.getElementById('add-password')
    //     const req_role_id = document.getElementById('add-role')

    //     if (req_password == '') {
    //         showError(req_password, 'Password wajib diisi.');
    //         return false;
    //     }
    //     if (req_role_id == '' || req_role_id == 0) {
    //         showError(req_role_id, 'Role harus dipilih.');
    //         return false;
    //     }

    //     $.ajax({
    //         type: "POST",
    //         url: "{{ route('pengguna.store') }}",
    //         data: {
    //             _token: "{{ csrf_token() }}",
    //             nip: req_nip.value,
    //             email: req_email.value,
    //             password: req_password.value,
    //             role_id: req_role_id.value,
    //         },
    //         success: function(data) {
    //             console.log(data);
    //             if (Array.isArray(data.error)) {
    //                 for (var i = 0; i < data.error.length; i++) {
    //                     var message = data.error[i];

    //                     if (message.toLowerCase().includes('nip'))
    //                         showError(req_nip, message)
    //                     if (message.toLowerCase().includes('email'))
    //                         showError(req_email, message)
    //                     if (message.toLowerCase().includes('password'))
    //                         showError(req_password, message)
    //                     if (message.toLowerCase().includes('role'))
    //                         showError(req_role_id, message)
    //                 }
    //             } else {
    //                 if (data.status == 'success') {
    //                     SuccessMessage(data.message);
    //                 } else {
    //                     alert(data.message)
    //                 }
    //                 $('#addModal').modal().hide()
    //                 $('body').removeClass('modal-open');
    //                 $('.modal-backdrop').remove();
    //             }
    //         }
    //     });
    // }
</script>
@endpush