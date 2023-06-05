@extends('layout.master')

@section('title', $title)

@section('content')

    <div class="panel-header">
        <div class="page-inner py-5">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                <div>
                    <h2 class="text-primary fw-bold">{{ $pageTitle }}</h2>
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
                            <table id="basic-datatables" class="table mt-2" style="border-collapse: separate">
                                <thead>
                                    <tr class="bg-danger text-light">
                                        <th rowspan="2" scope="col">No</th>
                                        <th rowspan="2" scope="col">Plafond</th>
                                        <th scope="col" style="text-align: center" colspan="3">Imbal Jasa</th>
                                        <th rowspan="2" scope="col">Aksi</th>
                                    </tr>
                                    <tr class="bg-danger text-light">
                                        {{-- <th colspan="2"></th> --}}
                                        <th>12</th>
                                        <th>24</th>
                                        <th>36 s/d 60</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($data as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                @if ($item->plafond1 == 0)
                                                < @else @if ($item->plafond1 == 50000000)
                                                        > {{ number_format($item->plafond1, 0, '', '.') }}
                                                    @else
                                                        {{ number_format($item->plafond1, 0, '', '.') }} s/d
                                                @endif
                                    @endif
                                    @if ($item->plafond2 != 0)
                                        {{ number_format($item->plafond2, 0, '', '.') }}
                                    @endif
                                    </td>
                                    @php
                                        $imbaljasa = DB::table('tenor_imbal_jasas')
                                            ->where('imbaljasa_id', $item->id)
                                            ->get();
                                        $tenor12 = 0;
                                        $tenor24 = 0;
                                        $tenor36 = 0;
                                        $id12 = '';
                                        $id24 = '';
                                        $id36 = '';
                                    @endphp
                                    @foreach ($imbaljasa as $i)
                                        @php
                                            if ($i->tenor == 12) {
                                                $tenor12 = $i->imbaljasa;
                                                $id12 = $i->id;
                                            } elseif ($i->tenor == 24) {
                                                $tenor24 = $i->imbaljasa;
                                                $id24 = $i->id;
                                            } elseif ($i->tenor == 36) {
                                                $tenor36 = $i->imbaljasa;
                                                $id36 = $i->id;
                                            }
                                        @endphp
                                        <td>{{ number_format($i->imbaljasa, 0, '', '.') }}</td>
                                        {{-- @empty --}}
                                        {{-- <td colspan="3"><span class="text-danger">Maaf data belum
                                                        tersedia.</span></td> --}}
                                    @endforeach
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-info dropdown-toggle" type="button"
                                                data-toggle="dropdown" aria-expanded="false">
                                                Selengkapnya
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" data-toggle="modal" data-target="#editModal"
                                                    data-id="{{ $item->id }}"
                                                    data-plafond1="{{ number_format($item->plafond1, 0, '', '.') }}"
                                                    data-plafond2="{{ number_format($item->plafond2, 0, '', '.') }}"
                                                    data-imbaljasa12="{{ number_format($tenor12, 0, '', '.') }}"
                                                    data-imbaljasa24="{{ number_format($tenor24, 0, '', '.') }}"
                                                    data-imbaljasa36="{{ number_format($tenor36, 0, '', '.') }}"
                                                    data-idimbaljasa12="{{ $id12 }}"
                                                    data-idimbaljasa24="{{ $id24 }}"
                                                    data-idimbaljasa36="{{ $id36 }}" href="#">Edit</a>
                                                <a class="dropdown-item deleteModal" data-toggle="modal"
                                                    data-target="#deleteModal" data-name="{{ $item->name }}"
                                                    data-id="{{ $item->id }}" href="#">Hapus</a>
                                            </div>
                                        </div>
                                    </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6">
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
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah {{ $pageTitle }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="modal-form">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group plafond1">
                                    <label for="add-plafond1">Plafond</label>
                                    <input type="text" class="form-control add-plafond1" id="add-plafond1"
                                        name="plafond1" required>
                                    <small class="form-text text-danger error"></small>
                                </div>
                            </div>
                            <div class="col-md-2" style="margin-top:45px;text-align:center">
                                s/d
                            </div>
                            <div class="col-md-5">
                                <div class="form-group plafond2">
                                    <label for=""></label>
                                    <input type="text" class="form-control add-plafond2" id="add-plafond2"
                                        name="plafond2" style="margin-top:7px;" required>
                                    <small class="form-text text-danger error"></small>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table mt-2" style="border-collapse: separate">
                                <thead>
                                    <tr class="bg-danger text-light">
                                        <th scope="col">Tenor</th>
                                        <th scope="col">Imbal Jasa</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <input type="number" class="form-control add-tenor12" id="add-tenor12"
                                                name="tenor[]" value="12" readonly>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control add-imbal-jasa"
                                                id="add-imbal-jasa12" name="imbaljasa[]" value="" required>
                                            <small class="form-text text-danger error"></small>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="number" class="form-control add-tenor24" id="add-tenor24"
                                                name="tenor[]" value="24" readonly>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control add-imbal-jasa"
                                                id="add-imbal-jasa24" name="imbaljasa[]" value="" required>
                                            <small class="form-text text-danger error"></small>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="number" class="form-control add-tenor36" id="add-tenor36"
                                                name="tenor[]" value="36" readonly>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control add-imbal-jasa"
                                                id="add-imbal-jasa36" name="imbaljasa[]" value="" required>
                                            <small class="form-text text-danger error"></small>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
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
        <div class="modal-dialog modal-lg" role="document">
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
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group plafond1">
                                    <label for="edit-plafond1">Plafond</label>
                                    <input type="text" class="form-control edit-plafond1" id="edit-plafond1"
                                        name="plafond1" required>
                                    <small class="form-text text-danger error"></small>
                                </div>
                            </div>
                            <div class="col-md-2" style="margin-top:45px;text-align:center">
                                s/d
                            </div>
                            <div class="col-md-5">
                                <div class="form-group plafond2">
                                    <label for=""></label>
                                    <input type="text" class="form-control edit-plafond2" id="edit-plafond2"
                                        name="plafond2" style="margin-top:7px;" required>
                                    <small class="form-text text-danger error"></small>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table mt-2" style="border-collapse: separate">
                                <thead>
                                    <tr class="bg-danger text-light">
                                        <th scope="col">Tenor</th>
                                        <th scope="col">Imbal Jasa</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <input type="hidden" class="form-control edit-id12" id="edit-id12"
                                                name="idtenor[]"readonly>
                                            <input type="number" class="form-control edit-tenor12" id="edit-tenor12"
                                                name="tenor[]" value="12" readonly>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control edit-imbal-jasa12"
                                                id="edit-imbal-jasa12" name="imbaljasa[]" value="" required>
                                            <small class="form-text text-danger error"></small>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="hidden" class="form-control edit-id24" id="edit-id24"
                                                name="idtenor[]"readonly>
                                            <input type="number" class="form-control edit-tenor24" id="edit-tenor24"
                                                name="tenor[]" value="24" readonly>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control edit-imbal-jasa24"
                                                id="edit-imbal-jasa24" name="imbaljasa[]" value="" required>
                                            <small class="form-text text-danger error"></small>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>

                                            <input type="hidden" class="form-control edit-id36" id="edit-id36"
                                                name="idtenor[]"readonly>
                                            <input type="number" class="form-control edit-tenor36" id="edit-tenor36"
                                                name="tenor[]" value="36" readonly>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control edit-imbal-jasa36"
                                                id="edit-imbal-jasa36" name="imbaljasa[]" value="" required>
                                            <small class="form-text text-danger error"></small>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>

                <div class="form-group">
                    <button class="btn btn-primary" id="edit-button">Simpan</button>
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
            $('#basic-datatables').DataTable({});
            var plafond1 = document.getElementById('add-plafond1');
            plafond1.addEventListener('keyup', function(e) {
                plafond1.value = formatRupiah(this.value);
            });
            var plafond2 = document.getElementById('add-plafond2');
            plafond2.addEventListener('keyup', function(e) {
                plafond2.value = formatRupiah(this.value);
            });
            var imbalJasa12 = document.getElementById('add-imbal-jasa12');
            imbalJasa12.addEventListener('keyup', function(e) {
                imbalJasa12.value = formatRupiah(this.value);
            });
            var imbalJasa24 = document.getElementById('add-imbal-jasa24');
            imbalJasa24.addEventListener('keyup', function(e) {
                imbalJasa24.value = formatRupiah(this.value);
            });
            var imbalJasa36 = document.getElementById('add-imbal-jasa36');
            imbalJasa36.addEventListener('keyup', function(e) {
                imbalJasa36.value = formatRupiah(this.value);
            });

            // Edit
            var editPlafond1 = document.getElementById('edit-plafond1');
            editPlafond1.addEventListener('keyup', function(e) {
                editPlafond1.value = formatRupiah(this.value);
            });
            var editPlafond2 = document.getElementById('edit-plafond2');
            editPlafond2.addEventListener('keyup', function(e) {
                editPlafond2.value = formatRupiah(this.value);
            });
            var editImbalJasa12 = document.getElementById('edit-imbal-jasa12');
            editImbalJasa12.addEventListener('keyup', function(e) {
                editImbalJasa12.value = formatRupiah(this.value);
            });
            var editImbalJasa24 = document.getElementById('edit-imbal-jasa24');
            editImbalJasa24.addEventListener('keyup', function(e) {
                editImbalJasa24.value = formatRupiah(this.value);
            });
            var editImbalJasa36 = document.getElementById('edit-imbal-jasa36');
            editImbalJasa36.addEventListener('keyup', function(e) {
                editImbalJasa36.value = formatRupiah(this.value);
            });

            /* Fungsi formatRupiah */
            function formatRupiah(angka) {
                var number_string = angka.replace(/[^,\d]/g, '').toString(),
                    split = number_string.split(','),
                    sisa = split[0].length % 3,
                    rupiah = split[0].substr(0, sisa),
                    ribuan = split[0].substr(sisa).match(/\d{3}/gi);

                // tambahkan titik jika yang di input sudah menjadi angka ribuan
                if (ribuan) {
                    separator = sisa ? '.' : '';
                    rupiah += separator + ribuan.join('.');
                }

                rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
                return rupiah;
            }
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
                const req_plafond1 = document.getElementById('add-plafond1');
                const req_plafond2 = document.getElementById('add-plafond2');
                const req_tenor12 = document.getElementById('add-tenor12');
                const req_tenor24 = document.getElementById('add-tenor24');
                const req_tenor36 = document.getElementById('add-tenor36');
                const req_imbaljasa12 = document.getElementById('add-imbal-jasa12');
                const req_imbaljasa24 = document.getElementById('add-imbal-jasa24');
                const req_imbaljasa36 = document.getElementById('add-imbal-jasa36');
                if (req_plafond1.value === '') {
                    showError(req_plafond1, 'Plafond wajib diisi');
                    return false;
                }
                if (req_plafond2.value === '') {
                    showError(req_plafond2, 'Plafond wajib diisi');
                    return false;
                }
                if (req_imbaljasa12.value === '') {
                    showError(req_imbaljasa12, 'Imbal jasa 12 bulan wajib diisi');
                    return false;
                }
                if (req_imbaljasa24.value === '') {
                    showError(req_imbaljasa24, 'Imbal jasa 24 bulan wajib diisi');
                    return false;
                }
                if (req_imbaljasa36.value === '') {
                    showError(req_imbaljasa36, 'Imbal jasa 36 bulan wajib diisi');
                    return false;
                }

                $.ajax({
                    type: "POST",
                    url: "{{ route('imbal-jasa.store') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        plafond1: req_plafond1.value,
                        plafond2: req_plafond2.value,
                        tenor: [req_tenor12.value, req_tenor24.value, req_tenor36.value],
                        imbaljasa: [req_imbaljasa12.value, req_imbaljasa24.value, req_imbaljasa36.value],
                    },
                    success: function(data) {
                        if (Array.isArray(data.error)) {
                            console.log(data.error);
                            for (var i = 0; i < data.error.length; i++) {
                                var message = data.error[i];
                                if (message.toLowerCase().includes('no_stnk'))
                                    showError(req_plafond1, message)
                                if (message.toLowerCase().includes('scan'))
                                    showError(req_plafond1, message)
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
                console.log('bisa');
                const req_id = document.getElementById('edit-id')
                const req_plafond1 = document.getElementById('edit-plafond1');
                const req_plafond2 = document.getElementById('edit-plafond2');
                const req_tenor12 = document.getElementById('edit-tenor12');
                const req_tenor24 = document.getElementById('edit-tenor24');
                const req_tenor36 = document.getElementById('edit-tenor36');
                const req_imbaljasa12 = document.getElementById('edit-imbal-jasa12');
                const req_imbaljasa24 = document.getElementById('edit-imbal-jasa24');
                const req_imbaljasa36 = document.getElementById('edit-imbal-jasa36');
                const req_imbaljasaid12 = document.getElementById('edit-id12');
                const req_imbaljasaid24 = document.getElementById('edit-id24');
                const req_imbaljasaid36 = document.getElementById('edit-id36');
                if (req_plafond1.value === '') {
                    showError(req_plafond1, 'Plafond wajib diisi');
                    return false;
                }
                if (req_plafond2.value === '') {
                    showError(req_plafond2, 'Plafond wajib diisi');
                    return false;
                }
                if (req_imbaljasa12.value === '') {
                    showError(req_imbaljasa12, 'Imbal jasa 12 bulan wajib diisi');
                    return false;
                }
                if (req_imbaljasa24.value === '') {
                    showError(req_imbaljasa24, 'Imbal jasa 24 bulan wajib diisi');
                    return false;
                }
                if (req_imbaljasa36.value === '') {
                    showError(req_imbaljasa36, 'Imbal jasa 36 bulan wajib diisi');
                    return false;
                }
                console.log(req_imbaljasaid12.value);
                console.log(req_imbaljasaid24.value);
                console.log(req_imbaljasaid36.value);
                $.ajax({
                    type: "POST",
                    url: "{{ url('/master/imbal-jasa') }}/" + req_id.value,
                    data: {
                        _token: "{{ csrf_token() }}",
                        _method: 'PUT',
                        plafond1: req_plafond1.value,
                        plafond2: req_plafond2.value,
                        id_imbaljasa: [req_imbaljasaid12.value, req_imbaljasaid24.value, req_imbaljasaid36.value],
                        tenor: [req_tenor12.value, req_tenor24.value, req_tenor36.value],
                        imbaljasa: [req_imbaljasa12.value, req_imbaljasa24.value, req_imbaljasa36.value],
                    },
                    success: function(data) {
                        if (Array.isArray(data.error)) {
                            console.log(data.error);
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
            $(document).ready(function() {
                $('a[data-toggle=modal], button[data-toggle=modal]').click(function() {
                    var data_id = '';
                    var data_plafond1 = '';
                    var data_plafond2 = '';
                    var data_tenor12 = '';
                    var data_tenor24 = '';
                    var data_tenor36 = '';
                    var data_id12 = '';
                    var data_id24 = '';
                    var data_id36 = '';
                    if (typeof $(this).data('id') !== 'undefined') {
                        data_id = $(this).data('id');
                    }
                    if (typeof $(this).data('plafond1') !== 'undefined') {
                        data_plafond1 = $(this).data('plafond1');
                    }
                    if (typeof $(this).data('plafond2') !== 'undefined') {
                        data_plafond2 = $(this).data('plafond2');
                    }
                    if (typeof $(this).data('imbaljasa12') !== 'undefined') {
                        data_tenor12 = $(this).data('imbaljasa12');
                    }
                    if (typeof $(this).data('imbaljasa24') !== 'undefined') {
                        data_tenor24 = $(this).data('imbaljasa24');
                    }
                    if (typeof $(this).data('imbaljasa36') !== 'undefined') {
                        data_tenor36 = $(this).data('imbaljasa36');
                    }
                    if (typeof $(this).data('idimbaljasa12') !== 'undefined') {
                        data_id12 = $(this).data('idimbaljasa12');
                    }
                    if (typeof $(this).data('idimbaljasa24') !== 'undefined') {
                        data_id24 = $(this).data('idimbaljasa24');
                    }
                    if (typeof $(this).data('idimbaljasa36') !== 'undefined') {
                        data_id36 = $(this).data('idimbaljasa36');
                    }
                    $('#edit-id').val(data_id);
                    $('.edit-plafond1').val(data_plafond1);
                    $('.edit-plafond2').val(data_plafond2);
                    $('.edit-imbal-jasa12').val(data_tenor12);
                    $('.edit-imbal-jasa24').val(data_tenor24);
                    $('.edit-imbal-jasa36').val(data_tenor36);
                    $('.edit-id12').val(data_id12);
                    $('.edit-id24').val(data_id24);
                    $('.edit-id36').val(data_id36);

                    var url = "{{ url('/master/kategori-dokumen') }}/" + data_id;
                    $('.edit-form').attr("action", url);
                })

            });
            $(document).on("click", ".deleteModal", function() {
                var data_id = $(this).data('id');
                var data_name = $(this).data('name');
                var url = "{{ url('/master/imbal-jasa') }}/" + data_id;
                $('#delete-form').attr("action", url);
                $('#konfirmasi').text("Apakah Apakah Anda Ingin Menghapus Imbal Jasa Ini ?");
                $('#deleteModal').modal('show');
            });
        </script>
    @endpush

@endsection
