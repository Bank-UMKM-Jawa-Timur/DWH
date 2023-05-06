@extends('layout.master')

@section('title', $title)

@section('content')

    <div class="panel-header bg-primary-gradient">
        <div class="page-inner py-5">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                <div>
                    <h2 class="text-white pb-2 fw-bold">{{ $pageTitle }}</h2>
                </div>
            </div>
        </div>
    </div>
    <div class="page-inner mt--5">
        <div class="row mt--2">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="#" id="modal-form">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="passwordLama">
                                            <label for="passwordLama">Password Lama</label>
                                            <input autofocus type="password" class="form-control" id="passwordLama"
                                                name="passwordLama">
                                            <small class="form-text text-danger error"></small>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="passwordBaru">
                                            <label for="passwordBaru">Password Baru</label>
                                            <input autofocus type="password" class="form-control" id="passwordBaru"
                                                name="passwordBaru">
                                            <small class="form-text text-danger error"></small>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="konfirmasiPassword">
                                            <label for="konfirmasiPassword">Konfirmasi Password</label>
                                            <input autofocus type="password" class="form-control" id="konfirmasiPassword"
                                                name="konfirmasiPassword">
                                            <small class="form-text text-danger error"></small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal-tambah -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
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
                    <form method="POST" action="#" id="modal-form">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="Title">
                                        <label for="Title">Title</label>
                                        <input autofocus type="text" class="form-control" id="Title" name="Title">
                                        <small class="form-text text-danger error"></small>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="Content">
                                        <label for="Content">Content</label>
                                        <input type="email" class="form-control" id="Content" name="Content">
                                        <small class="form-text text-danger error"></small>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="role">
                                        <label for="exampleFormControlSelect1">Role Notifikasi</label>
                                        <select class="form-control" id="exampleFormControlSelect1">
                                            <option>Cabang</option>
                                            <option>Vendor</option>
                                        </select>
                                        <small class="form-text text-danger error"></small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal-edit -->
    <div class="modal fade" id="edit1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
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
                    <form method="POST" action="#" id="modal-form">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="Title">
                                        <label for="Title">Title</label>
                                        <input autofocus type="text" class="form-control" id="Title"
                                            name="Title">
                                        <small class="form-text text-danger error"></small>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="Content">
                                        <label for="Content">Content</label>
                                        <input type="email" class="form-control" id="Content" name="Content">
                                        <small class="form-text text-danger error"></small>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="role">
                                        <label for="exampleFormControlSelect1">Role Notifikasi</label>
                                        <select class="form-control" id="exampleFormControlSelect1">
                                            <option>Cabang</option>
                                            <option>Vendor</option>
                                        </select>
                                        <small class="form-text text-danger error"></small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Delete --}}
    <div class="modal fade" id="hapus1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
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
                    <div class="form-group name">
                        Apakah Anda Ingin Menghapus Role Cabang?
                    </div>
                    <div class="form-group">
                        <button data-dismiss="modal" class="btn btn-danger">Batal</button>
                        <button type="submit" class="btn btn-primary">Hapus</button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        const form = document.getElementById('modal-form');
        form.addEventListener('submit', (event) => {
            event.preventDefault();

            const nameInput = document.getElementById('name');

            // Lakukan validasi pada data yang diterima dari form
            if (nameInput.value === '') {
                showError(nameInput, 'Nama Peran Wajib Diisi');
                return false;
            }

            form.submit();
        });

        function showError(input, message) {
            const formGroup = input.parentElement;
            const errorSpan = formGroup.querySelector('.error');

            formGroup.classList.add('has-error');
            errorSpan.innerText = message;
            input.focus();
        }
    </script>
@endsection
