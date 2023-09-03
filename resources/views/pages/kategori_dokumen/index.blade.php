@extends('layout.master')
@section('modal')
    <!-- Modal-Tambah -->
    @include('pages.kategori_dokumen.modal.create')
    <!-- Modal-Edit -->
    @include('pages.kategori_dokumen.modal.edit')
@endsection
@section('content')
    <div class="head-pages">
        <p class="text-sm">Master</p>
        <h2 class="text-2xl font-bold text-theme-primary tracking-tighter">
            Kategori Dokumen
        </h2>
    </div>
    <div class="body-pages">
        <div class="table-wrapper bg-white border rounded-md w-full p-2">
            <div class="table-accessiblity lg:flex text-center lg:space-y-0 space-y-5 justify-between">
                <div class="title-table lg:p-3 p-2 text-center">
                    <h2 class="font-bold text-lg text-theme-text tracking-tighter">
                        Kategori Dokumen
                    </h2>
                </div>
                <div class="table-action flex lg:justify-normal justify-center p-2 gap-2">
                    <button data-target-id="add-kd" class="toggle-modal px-6 py-2 bg-theme-primary flex gap-3 rounded text-white">
                        <span class="lg:mt-0 mt-0">
                            @include('components.svg.plus')
                        </span>
                        <span class="lg:block hidden">
                            Tambah Kategori Dokumen
                        </span>
                    </button>
                </div>
            </div>
            <div class="lg:flex lg:space-y-0 space-y-5 lg:text-left text-center justify-between mt-2 p-2">
                <div class="sorty pl-1 w-full">
                    <label for="" class="mr-3 text-sm text-neutral-400">show</label>
                    <select name="" class="border px-4 py-1.5 cursor-pointer rounded appearance-none text-center"
                        id="">
                        <option value="">5</option>
                        <option value="">10</option>
                        <option value="">15</option>
                        <option value="">20</option>
                    </select>
                    <label for="" class="ml-3 text-sm text-neutral-400">entries</label>
                </div>
                <div class="search-table lg:w-96 w-full">
                    <form action="{{ route('kategori-dokumen.index') }}" method="GET">
                        <div class="input-search text-[#BFBFBF] rounded-md border flex gap-2">
                            <span class="mt-2 ml-3">
                                @include('components.svg.search')
                            </span>
                                <input type="hidden" name="search_by" value="field">
                                <input type="text" placeholder="Search" class="p-2 rounded-md w-full outline-none text-[#BFBFBF]"
                                    name="query" value="{{ old('query', Request()->query('query')) }}" autocomplete="off" />
                        </div>
                    </form>
                </div>
            </div>
            @include('pages.kategori_dokumen.partial._table')
            {{-- <div class="tables mt-2">
                <table class="table-auto w-full">
                    <tr>
                        <th>No.</th>
                        <th>Nama Dokumen</th>
                        <th>Aksi</th>
                    </tr>
                    <tbody>
                        @forelse ($data as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->name }}</td>

                                <td>
                                    <div class="dropdown">
                                        <button class="px-4 py-2 bg-theme-btn/10 rounded text-theme-btn">
                                            Selangkapnya
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li class="">
                                                <a class="item-dropdown toggle-modal" data-target-id="edit-kd" href="#"
                                                data-id="{{ $item->id }}" data-name="{{ $item->name }}">Edit</a>
                                            </li>
                                            <li class="">
                                                <a class="item-dropdown" href="#" data-name="{{ $item->name }}" data-id="{{ $item->id }}">Hapus</a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3">
                                    <span class="text-danger">Maaf data belum tersedia.</span>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div> --}}
            <div class="footer-table p-3 text-theme-text lg:flex lg:space-y-0 space-y-10 justify-between">
                <div class="w-full">
                    <div class="pagination">
                        @if($data instanceof \Illuminate\Pagination\LengthAwarePaginator )
                        {{ $data->links('pagination::tailwind') }}
                        @endif
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
                const req_name = document.getElementById('add-name');

                if (req_name == '') {
                    showError(req_name, 'Nama Dokumen Kategori Wajib Diisi');
                    return false;
                }

                $.ajax({
                    type: "POST",
                    url: "{{ route('kategori-dokumen.store') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        name: req_name.value
                    },
                    success: function(data) {
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
                    showError(req_name, 'Nama Dokumen Kategori Wajib Diisi');
                    return false;
                }

                $.ajax({
                    type: "POST",
                    url: "{{ url('/master/kategori-dokumen') }}/" + req_id.value,
                    data: {
                        _token: "{{ csrf_token() }}",
                        _method: 'PUT',
                        name: req_name.value
                    },
                    success: function(data) {
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
                    $('#edit-id').val(data_id);
                    $('.edit-name').val(data_name);

                    var url = "{{ url('/master/kategori-dokumen') }}/" + data_id;
                    $('.edit-form').attr("action", url);
                })

            });
            $(document).on("click", ".deleteModal", function(e) {
                console.log('test');
                var data_id = $(this).data('id');
                var data_name = $(this).data('name');
                Swal.fire({
                    title: 'Konfirmasi',
                    html: 'Anda yakin akan menghapus data ini?',
                    icon: 'question',
                    iconColor: '#DC3545',
                    showCancelButton: true,
                    confirmButtonText: 'Lanjutkan',
                    cancelButtonText: `Batal`,
                    confirmButtonColor: '#DC3545'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "POST",
                            url: "{{ url('/master/kategori-dokumen') }}/"+data_id,
                            data: {
                                _token: "{{ csrf_token() }}",
                                _method: 'DELETE',
                            },
                            success: function(data) {
                                console.log(data);
                                if (data.status == 'success') {
                                    SuccessMessage(data.message);
                                    //Swal.fire('Saved!', '', 'success')
                                } else {
                                    ErrorMessage(data.message)
                                }
                            }
                        });
                    }
                })
            });
        </script>
    @endpush
@endsection