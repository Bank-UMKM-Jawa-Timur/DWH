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
                        <div class="table-responsive">
                            <table class="table mt-3">
                                <thead>
                                    <tr class="bg-danger text-light">
                                        <th scope="col">No</th>
                                        <th scope="col">Nama</th>
                                        <th scope="col">PO</th>
                                        <th scope="col">Ketersediaan Unit</th>
                                        <th scope="col">Penyerahan Unit</th>
                                        <th scope="col">STNK</th>
                                        <th scope="col">Polis</th>
                                        <th scope="col">BPKB</th>
                                        <th scope="col">Imbal Jasa</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>Rio Ardiansyah</td>
                                        <td class="link-po">2AFda12j7s</td>
                                        <td>28 April 2023</td>
                                        <td>29 April 2023</td>
                                        <td>1 Mei 2023</td>
                                        <td>5 Mei 2023</td>
                                        <td>10 Mei 2023</td>
                                        <td>Rp.5000</td>
                                        <td class="text-success">Selesai</td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-info dropdown-toggle" type="button"
                                                    data-toggle="dropdown" aria-expanded="false">
                                                    Selengkapnya
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="#">Detai</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>1</td>
                                        <td>Rio Ardiansyah</td>
                                        <td class="link-po">2AFda12j7s</td>
                                        <td>28 April 2023</td>
                                        <td>29 April 2023</td>
                                        <td>1 Mei 2023</td>
                                        <td>5 Mei 2023</td>
                                        <td>10 Mei 2023</td>
                                        <td>Rp.5000</td>
                                        <td class="text-success">Selesai</td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-info dropdown-toggle" type="button"
                                                    data-toggle="dropdown" aria-expanded="false">
                                                    Selengkapnya
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="#">Detai</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>1</td>
                                        <td>Rio Ardiansyah</td>
                                        <td class="link-po">2AFda12j7s</td>
                                        <td>28 April 2023</td>
                                        <td>29 April 2023</td>
                                        <td>1 Mei 2023</td>
                                        <td>5 Mei 2023</td>
                                        <td>10 Mei 2023</td>
                                        <td>Rp.5000</td>
                                        <td class="text-success">Selesai</td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-info dropdown-toggle" type="button"
                                                    data-toggle="dropdown" aria-expanded="false">
                                                    Selengkapnya
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="#">Detai</a>
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

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                {{-- <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div> --}}
                <div class="modal-body">
                    <form method="POST" action="#" id="modal-form">
                        <div class="form-group name">
                            <label for="name">Nama Peran</label>
                            <input type="text" class="form-control" id="name" name="name">
                            <small class="form-text text-danger error"></small>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
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
