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
                    <form action="{{ route('target.index') }}" method="GET">
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
                    <tr>
                        <th>No.</th>
                        <th>Total Unit</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                    <tbody>
                        @forelse ($data as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->total_unit }}</td>
                                <td>
                                    <div class="flex items-center justify-center w-full">
                                        <label for="toogleA" class="flex items-center cursor-pointer">
                                            <!-- toggle -->
                                            <div class="relative">
                                                <!-- input -->
                                                <input type="checkbox" class="toggle-button" data-id="{{ $item->id }}"
                                                    data-toggle="toggle" data-onstyle="primary" data-style="btn-round"
                                                    @if ($item->is_active) checked @endif>
                                                {{-- <input class="toggle-checkbox toggle-button mr-2 mt-[0.3rem] h-3.5 w-8 appearance-none rounded-[0.4375rem] bg-neutral-300 before:pointer-events-none before:absolute before:h-3.5 before:w-3.5 before:rounded-full before:bg-transparent before:content-[''] after:absolute after:z-[2] after:-mt-[0.1875rem] after:h-5 after:w-5 after:rounded-full after:border-none after:bg-neutral-100 after:shadow-[0_0px_3px_0_rgb(0_0_0_/_7%),_0_2px_2px_0_rgb(0_0_0_/_4%)] after:transition-[background-color_0.2s,transform_0.2s] after:content-[''] checked:bg-primary checked:after:absolute checked:after:z-[2] checked:after:-mt-[3px] checked:after:ml-[1.0625rem] checked:after:h-5 checked:after:w-5 checked:after:rounded-full checked:after:border-none checked:after:bg-primary checked:after:shadow-[0_3px_1px_-2px_rgba(0,0,0,0.2),_0_2px_2px_0_rgba(0,0,0,0.14),_0_1px_5px_0_rgba(0,0,0,0.12)] checked:after:transition-[background-color_0.2s,transform_0.2s] checked:after:content-[''] hover:cursor-pointer focus:outline-none focus:ring-0 focus:before:scale-100 focus:before:opacity-[0.12] focus:before:shadow-[3px_-1px_0px_13px_rgba(0,0,0,0.6)] focus:before:transition-[box-shadow_0.2s,transform_0.2s] focus:after:absolute focus:after:z-[1] focus:after:block focus:after:h-5 focus:after:w-5 focus:after:rounded-full focus:after:content-[''] checked:focus:border-primary checked:focus:bg-primary checked:focus:before:ml-[1.0625rem] checked:focus:before:scale-100 checked:focus:before:shadow-[3px_-1px_0px_13px_#3b71ca] checked:focus:before:transition-[box-shadow_0.2s,transform_0.2s] dark:bg-neutral-600 dark:after:bg-neutral-400 dark:checked:bg-primary dark:checked:after:bg-primary dark:focus:before:shadow-[3px_-1px_0px_13px_rgba(255,255,255,0.4)] dark:checked:focus:before:shadow-[3px_-1px_0px_13px_#3b71ca]"
                                                type="checkbox" role="switch" data-id="{{ $item->id }}" @if ($item->is_active) checked @endif id="flexSwitchChecked"/> --}}
                                                <!-- line -->
                                                {{-- <div class="line w-10 h-4 bg-gray-400 rounded-full shadow-inner transition">
                                                </div>
                                                <!-- dot -->
                                                <div
                                                    class="dot absolute w-6 h-6 bg-white rounded-full shadow -left-1 -top-1 transition">
                                                </div> --}}
                                            </div>
                                            <!-- label -->
                                            <div id="type-check" class="ml-3 text-gray-700 font-medium">
                                                @if ($item->is_active)
                                                    ON
                                                @else
                                                    OFF
                                                @endif
                                            </div>
                                        </label>
                                    </div>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button class="px-4 py-2 bg-theme-btn/10 rounded text-theme-btn">
                                            Selengkapnya
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li class="">
                                                <a class="item-dropdown toggle-modal edit" data-toggle="modal" data-target-id="edit-target" data-target="#edit-target" href="#" data-id="{{ $item->id }}" data-total_unit="{{ $item->total_unit }}">Edit</a>
                                            </li>
                                            <li class="">
                                                <a class="item-dropdown delete" data-id="{{ $item->id }}" 
                                                    href="#">Hapus</a>
                                            </li>
                                        </ul>
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
                            url: "{{ url('/target/') }}/"+data_id,
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
