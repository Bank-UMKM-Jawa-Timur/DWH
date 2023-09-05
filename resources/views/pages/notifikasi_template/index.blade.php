@extends('layout.master')

@section('title', $title)

@section('modal')
    <!-- Modal-tambah -->
    @include('pages.notifikasi_template.modal.create')
    <!-- Modal-edit -->
    @include('pages.notifikasi_template.modal.edit')
@endsection

@section('content')

    <div class="head-pages">
        <p class="text-sm">Master</p>
        <h2 class="text-2xl font-bold text-theme-primary tracking-tighter">
            {{ $pageTitle }}
        </h2>
    </div>
    <div class="body-pages">
        <div class="table-wrapper bg-white border rounded-md w-full p-2">
            <div class="table-accessiblity lg:flex text-center lg:space-y-0 space-y-5 justify-between">
                <div class="title-table lg:p-3 p-2 text-center">
                    <h2 class="font-bold text-lg text-theme-text tracking-tighter">
                        Template Notifikasi
                    </h2>
                </div>
                <div class="table-action flex lg:justify-normal justify-center p-2 gap-2">
                    <button data-target-id="add-template-notifikasi"
                        class="toggle-modal px-6 py-2 bg-theme-primary flex gap-3 rounded text-white">
                        <span class="lg:mt-0 mt-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 24 24">
                                <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="M5 12h14m-7-7v14" />
                            </svg>
                        </span>
                        <span class="lg:block hidden">
                            Tambah Template Notifikasi
                        </span>
                    </button>
                </div>
            </div>
            <div class="lg:flex lg:space-y-0 space-y-5 lg:text-left text-center justify-between mt-2 p-2">
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
                    <form action="{{ route('template-notifikasi.index') }}" method="GET">
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
                        <th>Judul</th>
                        <th>Konten</th>
                        <th>Role / Peran</th>
                        <th>Aksi</th>
                    </tr>
                    <tbody>
                        @forelse ($data as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->title }}</td>
                                <td>{{ $item->content }}</td>
                                <td>
                                    @if (!$item->role_id && $item->all_role)
                                        <p>Semua</p>
                                    @else
                                        @php
                                            $exRole = explode(',', $item->role_id);
                                        @endphp
                                        @forelse ($exRole as $v)
                                            @php
                                                $getRole = \App\Models\Role::select('id', 'name')
                                                    ->where('id', $v)
                                                    ->orderBy('name')
                                                    ->first();
                                            @endphp
                                            {{ $getRole->name }}
                                        @empty
                                            <p>Role tidak dipilih</p>
                                        @endforelse
                                    @endif
                                </td>
                                <td>
                                    <div class="dropdown max-w-[280px]">
                                        <button class="px-4 py-2 bg-theme-btn/10 rounded text-theme-btn">
                                            Selangkapnya
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li class="">
                                                <a class="item-dropdown toggle-modal"
                                                    data-target-id="edit-template-notifikasi"
                                                    id="modalEdit{{ $item->id }}" data-id="{{ $item->id }}"
                                                    data-title="{{ $item->title }}" data-content="{{ $item->content }}"
                                                    data-role="{{ $item->role_id }}" data-all-role="{{ $item->all_role }}"
                                                    data-action="{{ $item->action_id }}" href="#"
                                                    onclick="edit({{ $item->id }})">Edit</a>
                                            </li>
                                            <li class="">
                                                <a class="item-dropdown" data-id="{{ $item->id }}"
                                                    href="#">Hapus</a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">
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
                        @if ($data instanceof \Illuminate\Pagination\LengthAwarePaginator)
                            {{ $data->links('pagination::tailwind') }}
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal-tambah -->


        <!-- Modal-edit -->


        {{-- Modal Delete --}}


        @push('extraScript')
            <script src="{{ asset('template') }}/assets/js/plugin/datatables/datatables.min.js"></script>
            <script>
                // In your Javascript (external .js resource or <script> tag)
                $(document).ready(function() {
                    $('.select-role').select2({
                        width: 'resolve',
                    });
                });
                $('#page_length').on('change', function() {
                    $('#form').submit()
                })
            </script>
            @if (session('status'))
                <script>
                    swal("Berhasil!", '{{ session('status') }}', {
                        icon: "success",
                        timer: 3000,
                        closeOnClickOutside: false
                    }).then(() => {
                        location.reload();
                    });
                    setTimeout(function() {
                        location.reload();
                    }, 3000);
                </script>
            @endif
            <script>
                // Form
                $('#add-button').click(function(e) {
                    e.preventDefault()

                    store();
                })

                $('#edit-button').click(function(e) {
                    e.preventDefault()

                    update();
                })

                function store() {
                    const req_title = document.getElementById('add-title');
                    const req_content = document.getElementById('add-content');
                    const req_role = document.getElementById('add-role');
                    const req_action = document.getElementById('add-action');

                    $.ajax({
                        type: "POST",
                        url: "{{ route('template-notifikasi.store') }}",
                        data: {
                            _token: "{{ csrf_token() }}",
                            title: req_title.value,
                            content: req_content.value,
                            role: $('#add-role').val().toString(),
                            action: req_action.value,
                        },
                        success: function(data) {
                            //console.log(data)
                            if (Array.isArray(data.error)) {
                                for (var i = 0; i < data.error.length; i++) {
                                    var message = data.error[i];
                                    console.log(message);
                                    if (message.toLowerCase().includes('judul'))
                                        showError(req_title, message)
                                    if (message.toLowerCase().includes('konten'))
                                        showError(req_content, message)
                                    if (message.toLowerCase().includes('role'))
                                        showError(req_role, message)
                                    if (message.toLowerCase().includes('aksi'))
                                        showError(req_action, message)
                                }
                            } else {
                                if (data.status == 'success') {
                                    SuccessMessage(data.message);
                                } else {
                                    alert(data.message)
                                }
                                $('#addModal').modal().hide()
                                $('body').removeClass('modal-open');
                                $('.modal-backdrop').remove();
                            }
                        }
                    });
                }

                function update() {
                    const req_id = document.getElementById('edit-id')
                    const req_title = document.getElementById('edit-title');
                    const req_content = document.getElementById('edit-content');
                    const req_role = document.getElementById('edit-role');
                    const req_action = document.getElementById('edit-action');

                    $.ajax({
                        type: "POST",
                        url: "{{ url('/master/template-notifikasi') }}/" + req_id.value,
                        data: {
                            _token: "{{ csrf_token() }}",
                            _method: 'PUT',
                            title: req_title.value,
                            content: req_content.value,
                            role: $('#edit-role').val().toString(),
                            action: req_action.value,
                        },
                        success: function(data) {
                            console.log(data);
                            if (Array.isArray(data.error)) {
                                showError(req_name, data.error[0])
                            } else {
                                if (data.status == 'success') {
                                    SuccessMessage(data.message);
                                } else {
                                    alert(data.message)
                                }
                                $('#editModal').modal().hide()
                                $('body').removeClass('modal-open');
                                $('.modal-backdrop').remove();
                            }
                        }
                    });
                }

                function showError(input, message) {
                    console.log(message);
                    const formGroup = input.parentElement;
                    const errorSpan = formGroup.querySelector('.error');

                    formGroup.classList.add('has-error');
                    errorSpan.innerText = message;
                    input.focus();
                }

                function SuccessMessage(message) {
                    swal("Berhasil!", message, {
                        icon: "success",
                        timer: 3000,
                        closeOnClickOutside: false
                    }).then(() => {
                        location.reload();
                    });
                    setTimeout(function() {
                        location.reload();
                    }, 3000);
                }

                // Modal
                function edit(id) {
                    var selector = '#modalEdit' + id
                    var arrayRole = [];
                    var data_id = '';
                    var data_title = '';
                    var data_content = '';
                    var data_role = '';
                    var data_all_role = '';
                    var data_action = '';
                    if (typeof $(selector).data('id') !== 'undefined') {
                        data_id = $(selector).data('id');
                    }
                    if (typeof $(selector).data('title') !== 'undefined') {
                        data_title = $(selector).data('title');
                    }
                    if (typeof $(selector).data('content') !== 'undefined') {
                        data_content = $(selector).data('content');
                    }
                    if (typeof $(selector).data('role') !== 'undefined') {
                        data_role = $(selector).data('role');
                    }
                    if (typeof $(selector).data('all-role') !== 'undefined') {
                        data_all_role = $(selector).data('all-role');
                    }
                    if (typeof $(selector).data('action') !== 'undefined') {
                        data_action = $(selector).data('action');
                    }
                    var checkArray = data_role.toString();
                    console.log(data_role);
                    $('#edit-id').val(data_id);
                    $('.edit-title').val(data_title);
                    $('.edit-content').val(data_content);
                    $('.edit-action').val(data_action);
                    if (data_all_role == 1)
                        $('.edit-role').val(0).change()
                    else
                    if (checkArray.includes(",") == true) {
                        $.each(data_role.split(","), function(i, v) {
                            arrayRole.push(parseInt(v));
                        });
                        $('.edit-role').val(arrayRole).change();
                    } else {
                        $('.edit-role').val(data_role).change();
                    }

                    var url = "{{ url('/master/template-notifikasi') }}/" + data_id;
                    $('.edit-form').attr("action", url);
                }
                $(document).on("click", ".deleteModal", function() {
                    var data_id = $(this).data('id');
                    var url = "{{ url('/master/template-notifikasi') }}/" + data_id;
                    console.log(url)
                    $('#delete-form').attr("action", url);

                    $('#deleteModal').modal('show');
                });
            </script>
        @endpush

    @endsection
