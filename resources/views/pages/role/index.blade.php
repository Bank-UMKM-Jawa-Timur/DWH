@extends('layout.master')

@section('modal')
<!-- Modal-tambah -->
@include('pages.role.modal.create')
<!-- Modal-edit -->
@include('pages.role.modal.edit')
<!-- Modal-delete -->
@include('pages.role.modal.delete')
@endsection

@section('content')
<div class="head-pages">
    <p class="text-sm">Role</p>
    <h2 class="text-2xl font-bold text-theme-primary tracking-tighter">
        {{\Session::get(config('global.user_name_session'))}}
    </h2>
</div>
<div class="body-pages">
    <div class="table-wrapper bg-white border rounded-md w-full p-2">
        <div class="table-accessiblity lg:flex text-center lg:space-y-0 space-y-5 justify-between">
            <div class="title-table lg:p-3 p-2 text-center">
                <h2 class="font-bold text-lg text-theme-text tracking-tighter">
                    {{$pageTitle}}
                </h2>
            </div>
            <div class="table-action flex lg:justify-normal justify-center p-2 gap-2">
                <button data-target-id="add-layout-form" type="button"
                    class="toggle-modal px-6 py-2 bg-theme-primary flex gap-3 rounded text-white">
                    <span class="mt-0">
                        @include('components.svg.plus')
                    </span>
                    <span class="lg:block hidden"> Tambah </span>
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
                <form action="{{ route('role.index') }}" method="GET">
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
        <div class="tables mt-2">
            <table class="table-auto w-full">
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
                                    <button class="px-4 py-2 bg-theme-btn/10 rounded text-theme-btn">
                                        Selengkapnya
                                    </button>
                                    <ul class="dropdown-menu w-full">
                                        <li>
                                            <a class="item-dropdown"
                                                href="{{ route('role.permission.index', $item->id) }}">
                                                Hak Akses
                                            </a>
                                        </li>
                                        @if ($item->id > 4)
                                            <li>
                                                <a class="item-dropdown btn-edit" data-id="{{ $item->id }}"
                                                    data-name="{{ $item->name }}">
                                                    Edit
                                                </a>
                                            </li>
                                            <li>
                                                <a class="item-dropdown btn-delete" data-id="{{ $item->id }}"
                                                    data-name="{{ $item->name }}">
                                                    Hapus
                                                </a>
                                            </li>
                                        @endif
                                    </ul>
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
        <div class="footer-table p-3 text-theme-text lg:flex lg:space-y-0 space-y-10 justify-between">
            <div>
                <p class="mt-3 text-sm">Menampilkan 1 - 5 dari 100 Data</p>
            </div>
            <div>
                <div class="pagination">
                    @if($data instanceof \Illuminate\Pagination\LengthAwarePaginator )
                    {{ $data->links('pagination::simple-tailwind') }}
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@push('extraScript')
    <script>
        // add form
        var form = $("#add-layout-form");

        // $("#btn-add").click(function () {
        //     // form.toggleClass("layout-form-collapse");
        //     $("#add-layout-form").removeClass('hidden')
        //     //toggleForm()
        // });

        // $("#form-close").click(function () {
        //     form.toggleClass("layout-form-collapse");
        //     //closeForm();
        //     toggleForm()
        // });

        function toggleForm() {
            if (form.hasClass("layout-form-collapse")) {
                form.removeClass("hidden");
                $(".layout-overlay-form").removeClass("hidden");
            } else {
                form.addClass("hidden");
                $(".layout-overlay-form").addClass("hidden");
            }
            }
        function closeForm() {
            form.addClass("hidden");
            $(".layout-overlay-form").addClass("hidden");
        }
        
        // end add form

        // edit form
        $(document).on("click", ".btn-edit", function() {

            $("#edit-layout-form").toggleClass("hidden")
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
            $('#edit-form').attr("action", url);
        })
        
        // toggle form edit
        $(".toggle-form-edit").on("click", function () {
            const formId = $(this).data("form-id");
            $("#" + formId).removeClass("hidden");
            $(".layout-overlay-edit-form").removeClass("hidden");
        });

        $(".close-form-edit").on("click", function () {
            const formId = $(this).data("form-id");
            $("#" + formId).addClass("hidden");
            $(".layout-overlay-edit-form").addClass("hidden");
        });
        // end edit form

        $('#page_length').on('change', function() {
            $('#form').submit()
        })

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
        $(document).on("click", ".btn-delete", function() {
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
                        url: "{{ url('/master/role') }}/"+data_id,
                        data: {
                            _token: "{{ csrf_token() }}",
                            _method: 'DELETE',
                        },
                        success: function(data) {
                            console.log(data);
                            if (Array.isArray(data.error)) {
                                showError(req_name, data.error[0])
                            } else {
                                if (data.status == 'success') {
                                    SuccessMessage(data.message);
                                    //Swal.fire('Saved!', '', 'success')
                                } else {
                                    ErrorMessage(data.message)
                                }
                            }
                        }
                    });
                }
            })
            /*var url = "{{ url('/master/role') }}/" + data_id;
            $('#konfirmasi').text("Apakah Kamu Ingin Menghapus Role " + data_name + "?");
            $('#delete-form').attr("action", url);
            ("action", url);*/
        });
    </script>
@endPush
@endsection
