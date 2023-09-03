@extends('layout.master')

@section('title', $title)

@section('modal')
<!-- Modal-tambah -->
@include('pages.target.modal.create')
<!-- Modal-edit -->
@include('pages.target.modal.edit')
@endsection


@section('content')

    <div class="head-pages">
        <p class="text-sm">Target</p>
        <h2 class="text-2xl font-bold text-theme-primary tracking-tighter">
            {{ $pageTitle }}
        </h2>
    </div>
    <div class="body-pages">
        <div class="table-wrapper bg-white border rounded-md w-full p-2">
            <div class="table-accessiblity lg:flex text-center lg:space-y-0 space-y-5 justify-between">
                <div class="title-table lg:p-3 p-2 text-center">
                    <h2 class="font-bold text-lg text-theme-text tracking-tighter">
                        Target
                    </h2>
                </div>
                <div class="table-action flex lg:justify-normal justify-center p-2 gap-2">
                    <button data-target-id="add-target" class="toggle-modal px-6 py-2 bg-theme-primary flex gap-3 rounded text-white">
                        <span class="lg:mt-0 mt-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 24 24">
                                <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="M5 12h14m-7-7v14" />
                            </svg>
                        </span>
                        <span class="lg:block hidden"> Tambah Target </span>
                    </button>
                </div>
            </div>
            <div class="lg:flex lg:space-y-0 space-y-5 lg:text-left text-center justify-between mt-2 p-2">
                <div class="sorty pl-1 w-full pr-5">
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
                    <div class="input-search text-[#BFBFBF] rounded-md border flex gap-2">
                        <span class="mt-2 ml-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24">
                                <path fill="currentColor"
                                    d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0 0 16 9.5A6.5 6.5 0 1 0 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5S14 7.01 14 9.5S11.99 14 9.5 14z" />
                            </svg>
                        </span>
                        <input type="search" placeholder="Search" class="p-2 rounded-md w-full outline-none text-[#BFBFBF]"
                            autocomplete="off" />
                    </div>
                </div>
            </div>
            <div class="tables mt-2">
                <table class="table-auto w-full">
                    <tr>
                        <th>No.</th>
                        <th>Total Unit</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>20</td>
                            <td>
                                <div class="flex items-center justify-center w-full">
                                    <label for="toogleA" class="flex items-center cursor-pointer">
                                        <!-- toggle -->
                                        <div class="relative">
                                            <!-- input -->
                                            <input id="toogleA" type="checkbox" class="sr-only" />
                                            <!-- line -->
                                            <div class="line w-10 h-4 bg-gray-400 rounded-full shadow-inner transition">
                                            </div>
                                            <!-- dot -->
                                            <div
                                                class="dot absolute w-6 h-6 bg-white rounded-full shadow -left-1 -top-1 transition">
                                            </div>
                                        </div>
                                        <!-- label -->
                                        <div class="ml-3 text-gray-700 font-medium">
                                            OFF
                                        </div>
                                    </label>
                                </div>
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button class="px-4 py-2 bg-theme-btn/10 rounded text-theme-btn">
                                        Selangkapnya
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li class="">
                                            <a class="item-dropdown toggle-modal" data-target-id="edit-target"  href="#">Edit</a>
                                        </li>
                                        <li class="">
                                            <a class="item-dropdown" href="#">Hapus</a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="footer-table p-3 text-theme-text lg:flex lg:space-y-0 space-y-10 justify-between">
                <div class="w-full">
                    <div class="pagination">
                        @if ($data instanceof \Illuminate\Pagination\LengthAwarePaginator)
                            {{ $data->links('pagination::tailwind') }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- modal konfirmasi pin --}}
    <!-- Modal -->

    <!-- Modal-tambah -->


    <!-- Modal-edit -->

    {{-- Modal Delete --}}



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

            $('#page_length').on('change', function() {
                $('#form').submit()
            })
        </script>
    @endpush
@endsection
