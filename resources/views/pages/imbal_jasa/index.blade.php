@extends('layout.master')

@section('title', $title)

@section('content')

    <div class="head-pages">
      <p class="text-sm">Master</p>
      <h2 class="text-2xl font-bold text-theme-primary tracking-tighter">
          {{ $pageTitle }}
      </h2>
    </div>
    <div class="body-pages">
      <div class="table-wrapper bg-white border rounded-md w-full p-2">
          <div class="table-accessiblity lg:flex text-center lg:space-y-0 space-y-5 justify-between">
              <div class="title-table lg:p-3 p-2 text-center">
                  <h2 class="font-bold text-lg text-theme-text tracking-tighter">
                    {{ $pageTitle }}
                  </h2>
              </div>
              <div class="table-action flex lg:justify-normal justify-center p-2 gap-2">
                  <button id="form-toggle" class="px-6 py-2 bg-theme-primary flex gap-3 rounded text-white">
                      <span class="lg:mt-0 mt-0">
                          <svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 24 24">
                              <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                  stroke-width="2" d="M5 12h14m-7-7v14" />
                          </svg>
                      </span>
                      <span class="lg:block hidden"> Tambah Imbal jasa </span>
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
              <table class="table-imbal-jasa table-auto w-full">
                  <thead>
                      <tr>
                          <th rowspan="2" scope="col">
                              No.
                          </th>
                          <th rowspan="2" scope="col">
                              Plafond
                          </th>
                          <th scope="col" colspan="3">
                              Imbal jasa
                          </th>
                          <th rowspan="2" scope="col">
                              Aksi
                          </th>
                      </tr>
                      <tr>
                          <th>12</th>
                          <th>24</th>
                          <th>36 s/d 60</th>
                      </tr>
                  </thead>
                  <tbody>
                      <tr>
                          <td>1</td>
                          <td>
                              < 10.000.000</td>
                          <td>100.000</td>
                          <td>200.000</td>
                          <td>300.000</td>
                          <td>
                              <div class="dropdown">
                                  <button class="px-4 py-2 bg-theme-btn/10 rounded text-theme-btn">
                                      Selengkapnya
                                  </button>
                                  <ul class="dropdown-menu">
                                      <li class="">
                                          <a class="item-dropdown toggle-form-edit" data-form-id="form-1"
                                              href="#">Edit</a>
                                      </li>
                                      <li class="">
                                          <a class="item-dropdown" href="#">Hapus</a>
                                      </li>
                                  </ul>
                              </div>
                          </td>
                      </tr>
                      <tr>
                          <td>2</td>
                          <td>10.000.000 s/d 15.000.000</td>
                          <td>150.000</td>
                          <td>300.000</td>
                          <td>400.000</td>
                          <td>
                              <div class="dropdown">
                                  <button class="px-4 py-2 bg-theme-btn/10 rounded text-theme-btn">
                                      Selengkapnya
                                  </button>
                                  <ul class="dropdown-menu">
                                      <li class="">
                                          <a class="item-dropdown toggle-form-edit" data-form-id="form-2"
                                              href="#">Edit</a>
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
              <div>
                  <p class="mt-3 text-sm">Menampilkan 1 - 5 dari 100 Data</p>
              </div>
              <div>
                  <div class="pagination">
                      <button class="btn-pagination">Previous</button>
                      <button class="btn-pagination is-active">1</button>
                      <button class="btn-pagination">2</button>
                      <button class="btn-pagination">3</button>
                      <button class="btn-pagination">4</button>
                      <button class="btn-pagination">5</button>
                      <button class="btn-pagination">...</button>
                      <button class="btn-pagination">100</button>
                      <button class="btn-pagination">Next</button>
                  </div>
              </div>
          </div>
      </div>
  </div>

    <!-- Modal-tambah -->
    

    <!-- Modal-edit -->
   

    {{-- Modal Delete --}}
    

    @push('extraScript')
        <script src="{{ asset('template') }}/assets/js/plugin/datatables/datatables.min.js"></script>
        <script>
            $('#page_length').on('change', function() {
                $('#form').submit()
            })
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
