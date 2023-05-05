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
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                            data-target="#exampleModal">
                            Tambah {{ $pageTitle }}
                        </button>
                        <div class="table-responsive">
                            <table class="table mt-2">
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
                                    <tr>
                                        <td>1</td>
                                        <td>Antoni</td>
                                        <td>08312345232</td>
                                        <td>Cabang</td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-info dropdown-toggle" type="button"
                                                    data-toggle="dropdown" aria-expanded="false">
                                                    Selengkapnya
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" data-toggle="modal" data-target="#edit1"
                                                        href="#">Edit</a>
                                                    <a class="dropdown-item" data-toggle="modal" data-target="#hapus1"
                                                        href="#">Hapus</a>
                                                </div>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="#">Edit</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
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
                                    <div class="Nama">
                                        <label for="Nama">Nama</label>
                                        <input autofocus type="text" class="form-control" id="Nama" name="Nama">
                                        <small class="form-text text-danger error"></small>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="Phone">
                                        <label for="Phone">Nomor HP</label>
                                        <input type="Phone" class="form-control" id="Phone" name="Phone">
                                        <small class="form-text text-danger error"></small>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="Alamat">
                                        <label for="Alamat">Alamat Rumah</label>
                                        <textarea name="Alamat" class="form-control" id="Alamat" rows="3"></textarea>
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
                                    <div class="Nama">
                                        <label for="Nama">Nama</label>
                                        <input autofocus type="text" class="form-control" id="Nama"
                                            name="Nama">
                                        <small class="form-text text-danger error"></small>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="Phone">
                                        <label for="Phone">Nomor HP</label>
                                        <input type="Phone" class="form-control" id="Phone" name="Phone">
                                        <small class="form-text text-danger error"></small>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="Alamat">
                                        <label for="Alamat">Alamat Rumah</label>
                                        <textarea name="Alamat" class="form-control" id="Alamat" rows="3"></textarea>
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
