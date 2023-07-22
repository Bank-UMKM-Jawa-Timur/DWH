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
                            Tambah Peran
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table mt-2" id="basic-datatables">
                                <thead>
                                    <tr class="bg-danger text-light">
                                        <th scope="col">No</th>
                                        <th scope="col">Nama Peran</th>
                                        <th scope="col">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody">
                                    @forelse ($data as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->name }}</td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-info dropdown-toggle" type="button"
                                                        data-toggle="dropdown" aria-expanded="false">
                                                        Selengkapnya
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item"
                                                            href="{{ route('role.permission.index', $item->id) }}">Hak
                                                            Akses</a>
                                                        @if ($item->id > 4)
                                                        <a class="dropdown-item editModal" data-toggle="modal"
                                                            data-target="#editModal" data-id="{{ $item->id }}"
                                                            data-name="{{ $item->name }}" href="#">Edit</a>
                                                        <a class="dropdown-item deleteModal" data-toggle="modal"
                                                            data-target="#deleteModal" data-name="{{ $item->name }}"
                                                            data-id="{{ $item->id }}" href="#">Hapus</a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center">
                                                <span class="text-danger">Maaf data belum tersedia.</span>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        {{-- <div class="paginated">
                            {{ $data->links('pagination::bootstrap-4') }}
                        </div> --}}
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
                    <form id="modal-add-form">
                        <div class="form-group name">
                            <label for="add-name">Nama Peran</label>
                            <input type="text" class="form-control add-name" id="add-name" name="name">
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
                        <div class="form-group name">
                            <label for="edit-name">Nama Peran</label>
                            <input type="text" class="form-control edit-name" id="edit-name" name="name"
                                value="{{ old('name') }}">
                            <small class="form-text text-danger error"></small>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary" id="edit-button">Simpan</button>
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
                    <div class="form-group name" id="konfirmasi">
                        Apakah Anda Ingin Menghapus Role?
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
            $('#basic-datatables').DataTable({
                lengthMenu: [
                    [5, 10, 15, 20, -1],
                    [5, 10, 15, 20, 'All']
                ]
            });

            // Form
            $('#add-button').click(function(e) {
                e.preventDefault()

                store();
            })

            $('#edit-button').click(function(e) {
                e.preventDefault()

                update();
            })

            $('#add-name').keypress(function(e) {
                var key = e.which;
                if (key == 13) // the enter key code
                {
                    store()
                    return false;
                }
            })

            $('#edit-name').keypress(function(e) {
                var key = e.which;
                if (key == 13) // the enter key code
                {
                    update()
                    return false;
                }
            })

            function store() {
                const req_name = document.getElementById('add-name')

                if (req_name == '') {
                    showError(req_name, 'Nama Peran Wajib Diisi');
                    return false;
                }

                $.ajax({
                    type: "POST",
                    url: "{{ route('role.store') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        name: req_name.value
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
                            $('#addModal').modal().hide()
                            $('body').removeClass('modal-open');
                            $('.modal-backdrop').remove();
                        }
                    }
                });
            }

            function update() {
                const req_id = document.getElementById('edit-id')
                const req_name = document.getElementById('edit-name')

                if (req_name == '') {
                    showError(req_name, 'Nama Peran Wajib Diisi');
                    return false;
                }

                $.ajax({
                    type: "POST",
                    url: "{{ url('/master/role') }}/" + req_id.value,
                    data: {
                        _token: "{{ csrf_token() }}",
                        _method: 'PUT',
                        name: req_name.value
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
            $(document).on("click", ".editModal", function() {
                var data_id = '';
                var data_name = '';
                if (typeof $(this).data('id') !== 'undefined') {
                    data_id = $(this).data('id');
                }
                if (typeof $(this).data('name') !== 'undefined') {
                    data_name = $(this).data('name');
                }
                $('#edit-id').val(data_id);
                $('.edit-name').val(data_name);

                var url = "{{ url('/master/role') }}/" + data_id;
                $('.edit-form').attr("action", url);
                // })
            });
            $(document).on("click", ".deleteModal", function() {
                var data_id = $(this).data('id');
                var data_name = $(this).data('name');
                var url = "{{ url('/master/role') }}/" + data_id;
                $('#konfirmasi').text("Apakah Kamu Ingin Menghapus Role " + data_name + "?");
                $('#delete-form').attr("action", url);
                ("action", url);
            });
        </script>
    @endPush
@endsection
