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
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addModal">
                            Tambah Kategori Dokumen
                        </button>
                        <div class="table-responsive">
                            <table class="table mt-2">
                                <thead>
                                    <tr class="bg-danger text-light">
                                        <th scope="col">No</th>
                                        <th scope="col">Nama Dokumen</th>
                                        <th scope="col">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>Dokumen STNK</td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-info dropdown-toggle" type="button"
                                                    data-toggle="dropdown" aria-expanded="false">
                                                    Selengkapnya
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" data-toggle="modal" data-target="#editModal"
                                                        href="#">Edit</a>
                                                    <a class="dropdown-item deleteModal" data-toggle="modal"
                                                        data-target="#deleteModal" href="#">Hapus</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    {{-- @forelse ($data as $item)
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
                                                        <a class="dropdown-item" href="/hak_akses/1">Hak Akses</a>
                                                        <a class="dropdown-item" data-toggle="modal"
                                                            data-target="#editModal" data-id="{{ $item->id }}"
                                                            data-name="{{ $item->name }}" href="#">Edit</a>
                                                        <a class="dropdown-item deleteModal" data-toggle="modal"
                                                            data-target="#deleteModal" data-id="{{ $item->id }}"
                                                            href="#">Hapus</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3">
                                                <span class="text-danger">Maaf data belum tersedia.</span>
                                            </td>
                                        </tr>
                                    @endforelse --}}
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
                    <form method="POST" action="" id="modal-form">
                        @csrf
                        <div class="form-group name">
                            <label for="name">Nama Kategori Dokumen</label>
                            <input type="text" class="form-control add-name" id="name" name="name">
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
                    {{--  <form method="POST" action="{{ url('/master/role') }}" id="modal-form edit-form">  --}}
                    <form method="POST" id="modal-form" class="edit-form">
                        @csrf
                        @method('PUT')
                        <div class="form-group name">
                            <label for="name">Nama Kategori Dokumen</label>
                            <input type="text" class="form-control edit-name" id="name" name="name"
                                value="tes">
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
                    <div class="form-group name">
                        Apakah Anda Ingin Menghapus Role Cabang?
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
        <script>
            // Form
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

            function deleteModal(id) {
                console.log('delete :' + id)
                $('#deleteModal').show();
            }

            // Modal
            $(document).ready(function() {
                $('a[data-toggle=modal], button[data-toggle=modal]').click(function() {
                    var data_id = '';
                    var data_name = '';
                    if (typeof $(this).data('id') !== 'undefined') {
                        data_id = $(this).data('id');
                    }
                    if (typeof $(this).data('name') !== 'undefined') {
                        data_name = $(this).data('name');
                    }
                    $('.edit-name').val(data_name);

                    var url = "{{ url('/master/role') }}/" + data_id;
                    $('.edit-form').attr("action", url);
                })

            });
            $(document).on("click", ".deleteModal", function() {
                var data_id = $(this).data('id');
                var url = "{{ url('/master/role') }}/" + data_id;
                console.log(url)
                $('#delete-form').attr("action", url);

                $('#deleteModal').modal('show');
            });
        </script>
    @endpush

@endsection
