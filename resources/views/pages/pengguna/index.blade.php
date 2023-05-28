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
                        <div class="table-responsive">
                            <table class="table mt-2" id="basic-datatables">
                                <thead>
                                    <tr class="bg-danger text-light">
                                        <th scope="col">No</th>
                                        {{-- <th scope="col">Nama</th> --}}
                                        <th scope="col">NIP</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Role</th>
                                        <th scope="col">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($data as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            {{-- <td>Antoni</td> --}}
                                            <td>{{ $item->nip ? $item->nip : '-' }}</td>
                                            <td>{{ $item->email ? $item->email : '-' }}</td>
                                            <td>{{ $item->role }}</td>
                                            <td>
                                                <div class="dropdown">
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
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal-tambah -->
    <div class="modal fade" id="addModal">
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
                                <div class="col-sm-6">
                                    <div class="name">
                                        <label for="add-nip">Nip</label>
                                        <input autofocus type="text" class="form-control" id="add-nip" name="nip">
                                        <small class="form-text text-danger error"></small>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="email">
                                        <label for="add-email">Email</label>
                                        <input type="email" class="form-control" id="add-email" name="Email">
                                        <small class="form-text text-danger error"></small>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="password">
                                        <label for="add-password">Password</label>
                                        <input type="password" class="form-control" id="add-password" name="password"
                                            required>
                                        <small class="form-text text-danger error"></small>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="role">
                                        <label for="add-role">Role</label>
                                        <select class="form-control" id="add-role">
                                            <option value="0">-- Pilih role --</option>
                                        </select>
                                        <small class="form-text text-danger error"></small>
                                    </div>
                                </div>
                            </div>
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
                                <div class="col-sm-6">
                                    <div class="name">
                                        <label for="edit-nip">Nip</label>
                                        <input autofocus type="text" class="form-control" id="edit-nip"
                                            name="nip">
                                        <small class="form-text text-danger error"></small>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="email">
                                        <label for="edit-email">Email</label>
                                        <input type="email" class="form-control" id="edit-email" name="email">
                                        <small class="form-text text-danger error"></small>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="password">
                                        <label for="edit-password">Password</label>
                                        <input type="password" class="form-control" id="edit-password" name="password"
                                            required>
                                        <small class="form-text text-danger error"></small>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="role">
                                        <label for="exampleFormControlSelect1">Role</label>
                                        <select class="form-control" id="edit-role">
                                            <option value="0">-- Pilih role --</option>
                                        </select>
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

    <!-- Modal-reset password -->
    <div class="modal fade" id="resetPasswordModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
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
                    <div class="alert alert-success copyPesan" role="alert">
                    </div>
                    <form id="modal-reset-password-form">
                        <input type="hidden" name="reset_password_id" id="reset-password-id">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="name">
                                        <label for="reset-password">Password</label>
                                        <ul class="pl-3">
                                            <li class="text-light text-info">Masukkan password atau gunakan password acak
                                                dengan menekan tombol dadu</li>
                                            <li class="text-light text-info">Klik tombol papan klip untuk menyalin
                                                password.</li>
                                            <li class="text-light text-info">Klik tombol simpan klip untuk menyimpan
                                                password.</li>
                                        </ul>
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" placeholder="Masukkan password"
                                                id="reset-password" name="reset_password" autofocus required>
                                            <div class="input-group-append">
                                                <button class="btn btn-black btn-border" type="button"
                                                    id="button-generate">
                                                    <span class="fas fa-dice-six" id="basic-addon2"></span>
                                                </button>
                                            </div>
                                            <div class="input-group-append">
                                                <button onclick="copyToClipboard()" class="btn btn-black btn-border"
                                                    type="button" id="button-copy">
                                                    <span class="fas fa-clipboard" id="basic-addon2"></span>
                                                </button>
                                            </div>
                                        </div>
                                        <small class="form-text text-danger error"></small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" id="reset-password-button" class="btn btn-primary">Simpan</button>
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
                        Apakah Anda akan menghapus pengguna ini?
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
            $('#basic-datatables').DataTable({});
            $('#add-button').click(function(e) {
                e.preventDefault()

                store();
            })

            $('#edit-button').click(function(e) {
                e.preventDefault()

                update();
            })




            $("#button-generate").click(function(e) {
                var characters = "abcdefghijklmnopqrstuvwxyz";
                var randomCharacter = '';
                var number = 8;
                for (let i = 0; i < number; i++) {
                    var randomIndex = Math.floor(Math.random() * characters.length);
                    randomCharacter += characters.charAt(randomIndex);
                }
                $("#reset-password").val(randomCharacter);
            })

            $(".buttonresetpassword").click(function(e) {
                var id = $(this).data('id');
                $(".copyPesan").hide();
                $("#reset-password-id").val(id);
            });

            function copyToClipboard() {
                var pw = $("#reset-password").val();

                if (pw != '') {
                    navigator.clipboard.writeText(pw)
                        .then(function() {
                            // $("#copy-pesan").prop("hidden", true);
                            $(".copyPesan").show();
                            $(".copyPesan").html(pw + " Berhasil Di Copy");
                        })
                        .catch(function(error) {
                            console.error("Failed to copy text to clipboard:", error);
                        });
                }
            }


            $("#modal-reset-password-form").on('submit', function(e) {
                e.preventDefault();
                var form = $(this).serialize();
                var id = $("#reset-password-id").val();
                var password = $("#reset-password").val();
                $.ajax({
                    url: `{{ route('pengguna.reset_password') }}`,
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: id,
                        password: password
                    },
                    success: function(i) {
                        // console.log(i);
                        $('#resetPasswordModal').modal().hide();
                        $('body').removeClass('modal-open');
                        SuccessMessage("Password Berhasil Diubah");
                    },
                    error: function(e) {
                        console.log(e);
                    }
                })
            })



            function store() {
                const req_nip = document.getElementById('add-nip')
                const req_email = document.getElementById('add-email')
                const req_password = document.getElementById('add-password')
                const req_role_id = document.getElementById('add-role')

                if (req_password == '') {
                    showError(req_password, 'Password wajib diisi.');
                    return false;
                }
                if (req_role_id == '' || req_role_id == 0) {
                    showError(req_role_id, 'Role harus dipilih.');
                    return false;
                }

                $.ajax({
                    type: "POST",
                    url: "{{ route('pengguna.store') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        nip: req_nip.value,
                        email: req_email.value,
                        password: req_password.value,
                        role_id: req_role_id.value,
                    },
                    success: function(data) {
                        console.log(data);
                        if (Array.isArray(data.error)) {
                            for (var i = 0; i < data.error.length; i++) {
                                var message = data.error[i];

                                if (message.toLowerCase().includes('nip'))
                                    showError(req_nip, message)
                                if (message.toLowerCase().includes('email'))
                                    showError(req_email, message)
                                if (message.toLowerCase().includes('password'))
                                    showError(req_password, message)
                                if (message.toLowerCase().includes('role'))
                                    showError(req_role_id, message)
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
                const req_nip = document.getElementById('edit-nip')
                const req_email = document.getElementById('edit-email')
                const req_password = document.getElementById('edit-password')
                const req_role_id = document.getElementById('edit-role')

                if (req_role_id == '' || req_role_id == 0) {
                    showError(req_role_id, 'Role harus dipilih.');
                    return false;
                }

                $.ajax({
                    type: "POST",
                    url: "{{ url('/master/pengguna') }}/" + req_id.value,
                    data: {
                        _token: "{{ csrf_token() }}",
                        _method: 'PUT',
                        nip: req_nip.value,
                        email: req_email.value,
                        password: req_password.value,
                        role_id: req_role_id.value,
                    },
                    success: function(data) {
                        console.log(data);
                        if (Array.isArray(data.error)) {
                            for (var i = 0; i < data.error.length; i++) {
                                var message = data.error[i];

                                if (message.toLowerCase().includes('nip'))
                                    showError(req_nip, message)
                                if (message.toLowerCase().includes('email'))
                                    showError(req_email, message)
                                if (message.toLowerCase().includes('password'))
                                    showError(req_password, message)
                                if (message.toLowerCase().includes('role'))
                                    showError(req_role_id, message)
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
                    }
                });
            }

            // Modal
            $('#open-add-modal').click(function() {
                $.ajax({
                    type: "GET",
                    url: "{{ route('role.list_options') }}",
                    success: function(data) {
                        console.log(data)
                        if (data) {
                            for (i in data) {
                                $("#add-role").append(`<option value="` + data[i].id + `">` + data[i].name +
                                    `</option>`);
                            }
                        }
                    }
                });

                $('#addModal').modal('show')

                $(document).ready(function() {
                    $('a[data-toggle=modal], button[data-toggle=modal]').click(function() {
                        var data_id = '';
                        var data_nip = '';
                        var data_email = '';
                        var data_password = '';
                        var data_role = '';

                        if (typeof $(this).data('id') !== 'undefined') {
                            data_id = $(this).data('id');
                        }
                        if (typeof $(this).data('nip') !== 'undefined') {
                            data_nip = $(this).data('nip');
                        }
                        if (typeof $(this).data('email') !== 'undefined') {
                            data_email = $(this).data('email');
                        }
                        if (typeof $(this).data('password') !== 'undefined') {
                            data_password = $(this).data('password');
                        }
                        if (typeof $(this).data('role') !== 'undefined') {
                            data_role_id = $(this).data('role');
                        }
                        $('#edit-id').val(data_id);
                        $('#edit-nip').val(data_nip);
                        $('#edit-email').val(data_email);

                        $.ajax({
                            type: "GET",
                            url: "{{ route('role.list_options') }}",
                            success: function(data) {
                                if (data) {
                                    for (i in data) {
                                        if (data[i].id == data_role_id)
                                            $("#edit-role").append(`<option value="` +
                                                data[i].id +
                                                `" selected>` + data[i].name +
                                                `</option>`);
                                        else
                                            $("#edit-role").append(`<option value="` +
                                                data[i].id +
                                                `">` + data[i].name + `</option>`);
                                    }
                                }
                            }
                        });
                    })

                    var url = "{{ url('/master/pengguna') }}/" + data_id;
                    $('.edit-form').attr(
                        "action", url);
                })

            });


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

            $(document).on("click", ".editModal", function() {
                var data_id = $(this).data('id');
                var nip = $(this).data('nip');
                var email = $(this).data('email');
                var data_role_id = $(this).data('role');

                $('#edit-id').val(data_id);
                $('#edit-nip').val(nip);
                $('#edit-email').val(email);

                $.ajax({
                    type: "GET",
                    url: "{{ route('role.list_options') }}",
                    success: function(data) {
                        if (data) {
                            for (i in data) {
                                if (data[i].id == data_role_id)
                                    $("#edit-role").append(`<option value="` +
                                        data[i].id +
                                        `" selected>` + data[i].name +
                                        `</option>`);
                                else
                                    $("#edit-role").append(`<option value="` +
                                        data[i].id +
                                        `">` + data[i].name + `</option>`);
                            }
                        }
                    }
                });

                $('#editModal').modal('show');
            });

            $(document).on("click", ".deleteModal", function() {
                var data_id = $(this).data('id');
                var url = "{{ url('/master/pengguna') }}/" + data_id;
                $('#delete-form').attr("action", url);

                $('#deleteModal').modal('show');
            });
        </script>
    @endpush
@endsection
