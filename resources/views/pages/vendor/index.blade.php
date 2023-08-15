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
                        <button type="button" class="btn btn-primary btn-sm" id="open-add-modal">
                            Tambah {{ $pageTitle }}
                        </button>
                    </div>
                    <div class="card-body">
                        <form id="form" action="" method="get">
                            <input type="hidden" name="page" value="{{isset($_GET['page']) ? $_GET['page'] : 1}}">
                            <div class="d-flex justify-content-between" style="padding-left: 15px;padding-right: 15px;">
                                <div>
                                    <div class="form-inline">
                                        <label>Show</label>
                                        &nbsp;
                                        <select class="form-control form-control-sm" name="page_length" id="page_length" >
                                            <option value="5" {{ Request::get('page_length') == '5' ? 'selected' : '' }}>5</option>
                                            <option value="10" {{ Request::get('page_length') == '10' ? 'selected' : '' }}>10</option>
                                            <option value="15" {{ Request::get('page_length') == '15' ? 'selected' : '' }}>15</option>
                                            <option value="20" {{ Request::get('page_length') == '20' ? 'selected' : '' }}>20</option>
                                            <option value="all" {{ Request::get('page_length') == 'all' ? 'selected' : '' }}>All</option>
                                        </select>
                                        &nbsp;
                                        <label>entries</label>
                                    </div>
                                </div>
                                <div>
                                    <div class="form-inline">
                                        <label>Search : </label>
                                        &nbsp;
                                        <form action="{{ route('vendor.index') }}" method="GET">
                                            <input class="form-control form-control-sm" name="query" value="{{ old('query', Request()->query('query')) }}">
                                            <input type="hidden" name="search_by" value="field">
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="table-responsive">
                            <table class="table mt-2" id="basic-datatables">
                                <thead>
                                    <tr class="bg-danger text-light">
                                        <th scope="col">No</th>
                                        <th scope="col">Nama</th>
                                        <th scope="col">Alamat</th>
                                        <th scope="col">Nomor Hp</th>
                                        <th scope="col">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($data as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->name }}</td>
                                            <td>{{ $item->address }}</td>
                                            <td>{{ $item->phone }}</td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-info dropdown-toggle" type="button"
                                                        data-toggle="dropdown" aria-expanded="false">
                                                        Selengkapnya
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" data-toggle="modal"
                                                            data-target="#editModal" data-id="{{ $item->id }}"
                                                            data-name="{{ $item->name }}" data-phone="{{ $item->phone }}"
                                                            data-address="{{ $item->address }}"
                                                            data-cabang="{{ $item->cabang_id }}" href="#">Edit</a>
                                                        <a class="dropdown-item deleteModal" data-toggle="modal"
                                                            data-target="#deleteModal" data-name="{{ $item->name }}"
                                                            data-id="{{ $item->id }}" href="#">Hapus</a>
                                                    </div>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="#">Edit</a>
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
                        <div class="paginated">
                            @if($data instanceof \Illuminate\Pagination\LengthAwarePaginator )
                            {{ $data->links('pagination::bootstrap-5') }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal-tambah -->
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah {{ $pageTitle }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="modal-add-form">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="Nama">
                                        <label for="add-name">Nama</label>
                                        <input autofocus type="text" class="form-control" id="add-name" name="nama"
                                            required>
                                        <small class="form-text text-danger error"></small>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="Phone">
                                        <label for="add-phone">Nomor HP</label>
                                        <input type="text" class="form-control" id="add-phone" name="phone" required>
                                        <small class="form-text text-danger error"></small>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="Email">
                                        <label for="add-email">Email</label>
                                        <input type="email" class="form-control" id="add-email" name="email" required>
                                        <small class="form-text text-danger error"></small>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="role">
                                        <label for="add-cabang">NIP Cabang</label>
                                        <select class="form-control" id="add-cabang">
                                            <option value="0">-- Pilih NIP Cabang --</option>
                                        </select>
                                        <small class="form-text text-danger error"></small>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="Alamat">
                                        <label for="add-address">Alamat</label>
                                        <textarea name="alamat" class="form-control" id="add-address" rows="3" required></textarea>
                                        <small class="form-text text-danger error"></small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button id="simpanButton" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal-edit -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit {{ $pageTitle }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="modal-edit-form">
                        <input type="hidden" name="edit_id" id="edit-id">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="Nama">
                                        <label for="edit-name">Nama</label>
                                        <input autofocus type="text" class="form-control" id="edit-name"
                                            name="nama" required>
                                        <small class="form-text text-danger error"></small>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="Phone">
                                        <label for="edit-phone">Nomor HP</label>
                                        <input type="text" class="form-control" id="edit-phone" name="phone"
                                            required>
                                        <small class="form-text text-danger error"></small>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="Email">
                                        <label for="edit-email">Email</label>
                                        <input type="email" class="form-control" id="edit-email" name="email"
                                            required>
                                        <small class="form-text text-danger error"></small>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="role">
                                        <label for="edit-cabang">NIP Cabang</label>
                                        <select class="form-control" id="edit-cabang">
                                            <option value="0">-- Pilih NIP Cabang --</option>
                                        </select>
                                        <small class="form-text text-danger error"></small>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="Alamat">
                                        <label for="edit-address">Alamat</label>
                                        <textarea name="alamat" class="form-control" id="edit-address" rows="3" required></textarea>
                                        <small class="form-text text-danger error"></small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button id="edit-button" class="btn btn-primary">Simpan</button>
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
                {{-- <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Hapus {{ $pageTitle }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div> --}}
                <div class="modal-body">
                    <div class="form-group name" id="konfirmasi">
                        Apakah Anda yakin akan menghapus vendor ini?
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
            function store() {
                alert("helloworld");
                // const req_name = document.getElementById('add-name')
                // const req_phone = document.getElementById('add-phone')
                // const req_email = document.getElementById('add-email')
                // const req_address = document.getElementById('add-address')
                // const req_cabang_id = document.getElementById('add-cabang')

                // if (req_name == '') {
                //     showError(req_name, 'Nama harus diisi.');
                //     return false;
                // }
                // if (req_phone == '') {
                //     showError(req_phone, 'Nomor HP harus diisi.');
                //     return false;
                // }
                // if (req_email == '') {
                //     showError(req_email, 'Email harus diisi.');
                //     return false;
                // }
                // if (req_address == '') {
                //     showError(req_address, 'Alamat harus diisi.');
                //     return false;
                // }
                // if (req_cabang_id == '' || req_cabang_id == 0) {
                //     showError(req_cabang_id, 'Role harus dipilih.');
                //     return false;
                // }

                // $.ajax({
                //     type: "POST",
                //     url: "{{ route('vendor.store') }}",
                //     data: {
                //         _token: "{{ csrf_token() }}",
                //         name: req_name.value,
                //         phone: req_phone.value,
                //         email: req_email.value,
                //         address: req_address.value,
                //         cabang_id: req_cabang_id.value,
                //     },
                //     success: function(data) {
                //         console.log(data);
                //         if (Array.isArray(data.error)) {
                //             for (var i = 0; i < data.error.length; i++) {
                //                 var message = data.error[i];

                //                 if (message.toLowerCase().includes('name'))
                //                     showError(req_name, message)
                //                 if (message.toLowerCase().includes('nomor'))
                //                     showError(req_phone, message)
                //                 if (message.toLowerCase().includes('email'))
                //                     showError(req_email, message)
                //                 if (message.toLowerCase().includes('address'))
                //                     showError(req_address, message)
                //                 if (message.toLowerCase().includes('cabang'))
                //                     showError(req_cabang_id, message)
                //             }
                //         } else {
                //             if (data.status == 'success') {
                //                 SuccessMessage(data.message);
                //             } else {
                //                 ErrorMessage(data.message)
                //             }
                //             $('#addModal').modal().hide()
                //             $('body').removeClass('modal-open');
                //             $('.modal-backdrop').remove();
                //         }
                //     },
                //     error: function(e) {
                //         console.log(e)
                //     }
                // });
            }

            $("#simpanButton").on('click', function(e) {
                e.preventDefault();
                const req_name = document.getElementById('add-name')
                const req_phone = document.getElementById('add-phone')
                const req_email = document.getElementById('add-email')
                const req_address = document.getElementById('add-address')
                const req_cabang_id = document.getElementById('add-cabang')

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
                if (req_cabang_id == '' || req_cabang_id == 0) {
                    showError(req_cabang_id, 'Role harus dipilih.');
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
                        cabang_id: req_cabang_id.value,
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
                            $('#addModal').modal().hide()
                            $('body').removeClass('modal-open');
                            $('.modal-backdrop').remove();
                        }
                    },
                    error: function(e) {
                        console.log(e)
                    }
                });
            });
            // $('#simpanButton').click(function(e) {
            //     e.preventDefault()
            //     store();
            // })

            $('#edit-button').click(function(e) {
                e.preventDefault()
                const req_id = document.getElementById('edit-id')
                const req_name = document.getElementById('edit-name')
                const req_phone = document.getElementById('edit-phone')
                const req_email = document.getElementById('edit-email')
                const req_address = document.getElementById('edit-address')
                const req_cabang_id = document.getElementById('edit-cabang')

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
                if (req_cabang_id == '' || req_cabang_id == 0) {
                    showError(req_cabang_id, 'NIP cabang harus dipilih.');
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
                        cabang_id: req_cabang_id.value,
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
                            $('#editModal').modal().hide()
                            $('body').removeClass('modal-open');
                            $('.modal-backdrop').remove();
                        }
                    },
                    error: function(e) {
                        console.log(e)
                    }
                });
            })

            function update() {

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

            function ErrorMessage(message) {
                swal("Gagal!", message, {
                    icon: "error",
                    timer: 3000,
                    closeOnClickOutside: false
                }).then(() => {
                    location.reload();
                });
                setTimeout(function() {
                    location.reload();
                }, 3000);
            }

            function showError(input, message) {
                const formGroup = input.parentElement;
                const errorSpan = formGroup.querySelector('.error');

                formGroup.classList.add('has-error');
                errorSpan.innerText = message;
                input.focus();
            }

            // Modal
            $('#open-add-modal').click(function() {
                $.ajax({
                    type: "GET",
                    url: "{{ route('pengguna.list_cabang') }}",
                    success: function(data) {
                        console.log(data)
                        if (data) {
                            for (i in data) {
                                console.log(i)
                                $("#add-cabang").append(`<option value="` + data[i].id + `">` + data[i]
                                    .nip + `</option>`);
                            }
                        }
                    }
                })

                $('#edit-button').click(function(e) {
                    e.preventDefault()

                    update();
                })
            });

            function store() {
                const req_name = document.getElementById('add-name')
                const req_phone = document.getElementById('add-phone')
                const req_address = document.getElementById('add-address')
                const req_cabang_id = document.getElementById('add-cabang')

                if (req_name == '') {
                    showError(req_name, 'Nama harus diisi.');
                    return false;
                }
                if (req_phone == '') {
                    showError(req_phone, 'Nomor HP harus diisi.');
                    return false;
                }
                if (req_address == '') {
                    showError(req_address, 'Alamat harus diisi.');
                    return false;
                }
                if (req_cabang_id == '' || req_cabang_id == 0) {
                    showError(req_cabang_id, 'Role harus dipilih.');
                    return false;
                }

                $.ajax({
                    type: "POST",
                    url: "{{ route('vendor.store') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        name: req_name.value,
                        phone: req_phone.value,
                        address: req_address.value,
                        cabang_id: req_cabang_id.value,
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
                                if (message.toLowerCase().includes('address'))
                                    showError(req_address, message)
                                if (message.toLowerCase().includes('cabang'))
                                    showError(req_cabang_id, message)
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
                    },
                    error: function(e) {
                        console.log(e)
                    }
                });
            }

            function update() {
                const req_id = document.getElementById('edit-id')
                const req_name = document.getElementById('edit-name')
                const req_phone = document.getElementById('edit-phone')
                const req_address = document.getElementById('edit-address')
                const req_cabang_id = document.getElementById('edit-cabang')

                if (req_name == '') {
                    showError(req_name, 'Nama harus diisi.')
                    return false;
                }
                if (req_phone == '') {
                    showError(req_phone, 'Nomor HP harus diisi.')
                    return false;
                }
                if (req_address == '') {
                    showError(req_address, 'Alamat harus diisi.')
                    return false;
                }
                if (req_cabang_id == '' || req_cabang_id == 0) {
                    showError(req_cabang_id, 'NIP cabang harus dipilih.');
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
                        address: req_address.value,
                        cabang_id: req_cabang_id.value,
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
                                if (message.toLowerCase().includes('address'))
                                    showError(req_address, message)
                                if (message.toLowerCase().includes('cabang'))
                                    showError(req_cabang_id, message)
                            }
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
                    },
                    error: function(e) {
                        console.log(e)
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
            $('#open-add-modal').click(function() {
                $.ajax({
                    type: "GET",
                    url: "{{ route('pengguna.list_cabang') }}",
                    success: function(data) {
                        console.log(data)
                        if (data) {
                            for (i in data) {
                                $("#add-cabang").append(`<option value="` + data[i].id + `">` + data[i]
                                    .nip + `</option>`);
                            }
                        }
                    }
                })

                $('#addModal').modal('show')
            })

            $(document).ready(function() {
                $('a[data-toggle=modal], button[data-toggle=modal]').click(function() {
                    var data_id = '';
                    var data_name = '';
                    var data_phone = '';
                    var data_address = '';
                    var data_cabang = '';

                    if (typeof $(this).data('id') !== 'undefined') {
                        data_id = $(this).data('id');
                    }
                    if (typeof $(this).data('name') !== 'undefined') {
                        data_name = $(this).data('name');
                    }
                    if (typeof $(this).data('phone') !== 'undefined') {
                        data_phone = $(this).data('phone');
                    }
                    if (typeof $(this).data('address') !== 'undefined') {
                        data_address = $(this).data('address');
                    }
                    if (typeof $(this).data('cabang') !== 'undefined') {
                        data_cabang = $(this).data('cabang');
                    }
                    $('#edit-id').val(data_id);
                    $('#edit-name').val(data_name);
                    $('#edit-phone').val(data_phone);
                    $('#edit-address').val(data_address);
                    $('#edit-cabang').val(data_cabang);

                    $.ajax({
                        type: "GET",
                        url: "{{ route('pengguna.list_cabang') }}",
                        success: function(data) {
                            if (data) {
                                for (i in data) {
                                    if (data[i].id == data_cabang)
                                        $("#edit-cabang").append(`<option value="` + data[i].id +
                                            `" selected>` + data[i].nip + `</option>`);
                                    else
                                        $("#edit-cabang").append(`<option value="` + data[i].id +
                                            `">` + data[i].nip + `</option>`);
                                }
                            }
                        }
                    })

                    var url = "{{ url('/master/vendor') }}/" + data_id;
                    $('.edit-form').attr("action", url);
                })

            });

            $(document).on("click", ".deleteModal", function() {
                var data_id = $(this).data('id');
                var data_name = $(this).data('name');
                var url = "{{ url('/master/vendor') }}/" + data_id;
                console.log(url)
                $('#delete-form').attr("action", url);
                $('#konfirmasi').text("Apakah Kamu Ingin Menghapus Data " + data_name + "?");

                $('#deleteModal').modal('show');
            });
        </script>
    @endpush
@endsection
