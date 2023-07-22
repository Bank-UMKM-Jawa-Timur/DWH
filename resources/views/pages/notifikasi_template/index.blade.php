@extends('layout.master')

@section('title', $title)

@section('content')

    <div class="panel-header">
        <div class="page-inner py-5">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                <div>
                    <h2 class="text-primary pb-2 fw-bold">{{ $pageTitle }}</h2>
                </div>
            </div>
        </div>
    </div>
    <div class="page-inner mt--5">
        <div class="row mt--2">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addModal">
                            Tambah {{ $title }}
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table mt-2" id="basic-datatables">
                                <thead>
                                    <tr class="bg-danger text-light">
                                        <th scope="col">No</th>
                                        <th scope="col">Judul</th>
                                        <th scope="col">Konten</th>
                                        <th scope="col">Role / Peran</th>
                                        <th scope="col">Aksi</th>
                                    </tr>
                                </thead>
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
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-info dropdown-toggle" type="button"
                                                        data-toggle="dropdown" aria-expanded="false">
                                                        Selengkapnya
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" id="modalEdit{{ $item->id }}"
                                                            data-toggle="modal" data-target="#editModal"
                                                            data-id="{{ $item->id }}" data-title="{{ $item->title }}"
                                                            data-content="{{ $item->content }}"
                                                            data-role="{{ $item->role_id }}"
                                                            data-all-role="{{ $item->all_role }}"
                                                            data-action="{{ $item->action_id }}" href="#"
                                                            onclick="edit({{ $item->id }})">Edit</a>
                                                        <a class="dropdown-item deleteModal" data-toggle="modal"
                                                            data-target="#deleteModal" data-id="{{ $item->id }}"
                                                            href="#">Hapus</a>
                                                    </div>
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
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal-tambah -->
    <div class="modal hide" id="addModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah {{ $pageTitle }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="modal-form">
                        <div class="form-group action">
                            <label for="add-action">Aksi</label>
                            <select name="action" id="add-action" class="form-control select2 add-action">
                                <option value="">---Pilih Aksi---</option>
                                @foreach ($actions as $action)
                                    <option value="{{ $action->id }}">{{ $action->name }}</option>
                                @endforeach
                            </select>
                            <small class="form-text text-danger error"></small>
                        </div>
                        <div class="form-group title">
                            <label for="add-title">Judul</label>
                            <input type="text" class="form-control add-title" id="add-title" name="title">
                            <small class="form-text text-danger error"></small>
                        </div>
                        <div class="form-group content">
                            <label for="add-content">Konten</label>
                            <input type="text" class="form-control add-content" id="add-content" name="content">
                            <small class="form-text text-danger error"></small>
                        </div>
                        <div class="form-group role">
                            <label for="add-role">Role / Peran</label>
                            <br>
                            <div class="w-100">
                                <select name="role" id="add-role" class="form-control select-role add-role"
                                    multiple="multiple" style="width: 100%">
                                    <option value="">---Pilih Role / Peran---</option>
                                    <option value="0">Semua</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>

                            </div>
                            <small class="form-text text-danger error"></small>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-primary" id="add-button">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal-edit -->
    <div class="modal hide" id="editModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit {{ $pageTitle }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="modal-edit-form" class="edit-form">
                        <input type="hidden" name="edit_id" id="edit-id">
                        <div class="form-group action">
                            <label for="edit-action">Aksi</label>
                            <select name="action" id="edit-action" class="form-control select2 edit-action">
                                <option value="">---Pilih Aksi---</option>
                                @foreach ($actions as $action)
                                    <option value="{{ $action->id }}">{{ $action->name }}</option>
                                @endforeach
                            </select>
                            <small class="form-text text-danger error"></small>
                        </div>
                        <div class="form-group title">
                            <label for="edit-title">Judul</label>
                            <input type="text" class="form-control edit-title" id="edit-title" name="title">
                            <small class="form-text text-danger error"></small>
                        </div>
                        <div class="form-group content">
                            <label for="edit-content">Konten</label>
                            <input type="text" class="form-control edit-content" id="edit-content" name="content">
                            <small class="form-text text-danger error"></small>
                        </div>
                        <div class="form-group role">
                            <label for="edit-role">Role / Peran</label>
                            <br>
                            <div class="w-100">
                                <select name="role" id="edit-role" class="form-control select-role edit-role"
                                    multiple="multiple" style="width: 100%">
                                    <option value="">---Pilih Role / Peran---</option>
                                    <option value="0">Semua</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>

                            </div>
                            <small class="form-text text-danger error"></small>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-primary" id="edit-button">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Delete --}}
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="form-group name">
                        Apakah Anda Ingin Menghapus {{ $title }}?
                    </div>
                    <div class="form-inline">
                        <button data-dismiss="modal" class="btn btn-danger mr-2">Batal</button>
                        <form id="delete-form" method="post">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-primary">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('extraScript')
        <script src="{{ asset('template') }}/assets/js/plugin/datatables/datatables.min.js"></script>
        <script>
            // In your Javascript (external .js resource or <script> tag)
            $(document).ready(function() {
                $('.select-role').select2();
            });
            $('#basic-datatables').DataTable({
                "pageLength": 5,
                lengthMenu: [5, 10, 20, 50, 100],
            });
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
