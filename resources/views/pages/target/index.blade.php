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
                            Tambah {{ $pageTitle }}
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="basic-datatables" class="table mt-2">
                                <thead>
                                    <tr class="bg-danger text-light">
                                        <th scope="col">No</th>
                                        <th scope="col">Total Unit</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($data as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            {{--  <td>Rp {{ number_format($item->nominal, 0, ',', '.') }}</td>  --}}
                                            <td>{{ $item->total_unit }}</td>
                                            <td>
                                                <input type="checkbox" class="toggle-button" data-id="{{ $item->id }}"
                                                    data-toggle="toggle" data-onstyle="primary" data-style="btn-round"
                                                    @if ($item->is_active) checked @endif>
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-info dropdown-toggle" type="button"
                                                        data-toggle="dropdown" aria-expanded="false">
                                                        Selengkapnya
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item edit" data-toggle="modal"
                                                            data-target="#editModal" data-id="{{ $item->id }}"
                                                            /*data-nominal="{{ $item->nominal }}"*/ data-total_unit="{{ $item->total_unit }}" href="#">Edit</a>
                                                        <a class="dropdown-item delete" data-toggle="modal"
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
                                            <td colspan="4" class="text-center">
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

    {{-- modal konfirmasi pin --}}
    <!-- Modal -->
    <div class="modal fade" id="pin" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    Apakah Kamu Yakin Ingin Mengaktifkan Target?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary btn-sm">Aktifkan</button>
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
                                <div class="col-sm-12">
                                    {{--  <div class="Nominal">
                                        <label for="nominal">Nominal</label>
                                        <input autofocus type="number" class="form-control" id="nominal" name="nominal"
                                            required>
                                        <small class="form-text text-danger error"></small>
                                    </div>  --}}
                                    <div class="TotalUnit">
                                        <label for="total_unit">Total Unit</label>
                                        <input autofocus type="number" class="form-control" id="total_unit" name="total_unit"
                                            required>
                                        <small class="form-text text-danger error"></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary" id="add-button">Simpan</button>
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
                        <input type="hidden" name="edit_id" id="edit_id">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-12">
                                    {{--  <div class="Nominal">
                                        <label for="edit_nominal">Nominal</label>
                                        <input autofocus type="number" class="form-control" id="edit_nominal"
                                            name="edit_nominal" required>
                                        <small class="form-text text-danger error"></small>
                                    </div>  --}}
                                    <div class="TotalUnit">
                                        <label for="edit_total_unit">Total Unit</label>
                                        <input autofocus type="number" class="form-control" id="edit_total_unit"
                                            name="edit_total_unit" required>
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
    <div class="modal fade " id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="form-group name">
                        Yakin akan menghapus data ini?
                    </div>
                    <div class="form-inline">
                        <button data-dismiss="modal" class="btn btn-danger mr-2">Batal</button>
                        <form id="delete-form" method="post">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-primary btn-delete">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    @push('extraScript')
        <script src="{{ asset('template') }}/assets/js/plugin/datatables/datatables.min.js"></script>

        <script>
            $('#modal-add-form').on('submit', function(e) {
                e.preventDefault()

                // const req_nominal = document.getElementById('nominal')
                /*if (req_nominal == '') {
                    showError(req_nominal, 'Nominal harus diisi.');
                    return false;
                }*/

                const req_total_unit = document.getElementById('total_unit')

                if (req_total_unit == '') {
                    showError(req_total_unit, 'Total unit harus diisi.');
                    return false;
                }

                $.ajax({
                    type: "POST",
                    url: "{{ route('target.store') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        // nominal: req_nominal.value,
                        total_unit: req_total_unit.value,
                    },
                    success: function(data) {
                        console.log(data);
                        if (Array.isArray(data.error)) {
                            for (var i = 0; i < data.error.length; i++) {
                                var message = data.error[i];

                                if (message.toLowerCase().includes('nominal'))
                                    showError(req_total_unit, message)
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
                })
            })

            $('#modal-edit-form').on('submit', function(e) {
                e.preventDefault()

                const req_id = document.getElementById('edit_id')
                const req_total_unit = document.getElementById('edit_total_unit')

                if (req_total_unit == '') {
                    showError(req_total_unit, 'Total unit harus diisi.');
                    return false;
                }
                /*const req_nominal = document.getElementById('edit_nominal')

                if (req_nominal == '') {
                    showError(req_nominal, 'Nominal harus diisi.');
                    return false;
                }*/

                $.ajax({
                    type: "POST",
                    url: "{{ url('/target') }}/" + req_id.value,
                    data: {
                        _token: "{{ csrf_token() }}",
                        _method: "PUT",
                        // nominal: req_nominal.value,
                        total_unit: req_total_unit.value,
                    },
                    success: function(data) {
                        console.log(data);
                        if (Array.isArray(data.error)) {
                            for (var i = 0; i < data.error.length; i++) {
                                var message = data.error[i];

                                if (message.toLowerCase().includes('nominal'))
                                    showError(req_total_unit, message)
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
                })
            })

            $('.toggle-button').change(function() {
                const data_id = $(this).data('id')
                var checked = this.checked

                $.ajax({
                    type: "POST",
                    url: "{{ url('/target-toggle') }}/" + data_id,
                    data: {
                        _token: "{{ csrf_token() }}",
                        _method: "PUT",
                        toggle: checked,
                    },
                    success: function(data) {
                        console.log(data);
                        if (Array.isArray(data.error)) {
                            /*for (var i=0; i < data.error.length; i++) {
                                var message = data.error[i];

                                if (message.toLowerCase().includes('nominal'))
                                    showError(req_nominal, message)
                            }*/
                        } else {
                            if (data.status == 'success') {
                                SuccessMessage(data.message);
                            } else {
                                ErrorMessage(data.message)
                            }
                        }
                    },
                    error: function(e) {
                        console.log(e)
                    }
                })
            })

            $(document).on("click", ".edit", function() {
                var data_id = $(this).data('id');
                // var data_nominal = $(this).data('nominal');
                var data_total_unit = $(this).data('total_unit');

                $('#edit_id').val(data_id)
                // $('#edit_nominal').val(data_total_unit)
                $('#edit_total_unit').val(data_total_unit)
            });

            $(document).on("click", ".delete", function() {
                var data_id = $(this).data('id');
                var url = "{{ route('target.destroy', '+data_id+') }}";

                $('#konfirmasi').text("Apakah yakin akan menghapus data?");
                $('#delete-form').attr("action", url);

                $('#deleteModal').modal('show');
            });

            function showError(input, message) {
                const formGroup = input.parentElement;
                const errorSpan = formGroup.querySelector('.error');

                formGroup.classList.add('has-error');
                errorSpan.innerText = message;
                input.focus();
            }

            $('#basic-datatables').DataTable({
                lengthMenu: [
                    [5, 10, 15, 20, -1],
                    [5, 10, 15, 20, 'All']
                ]
            });
        </script>
    @endpush
@endsection
