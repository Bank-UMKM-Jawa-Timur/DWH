@extends('layout.master')

@section('modal')

@include('pages.mst-item-asuransi.modal.create')

@endsection

@section('content')

<div class="head-pages">
    <p class="text-sm">Asuransi</p>
    <h2 class="text-2xl font-bold text-theme-primary tracking-tighter">
        Master item
    </h2>
</div>
<div class="body-pages">
    <div class="bg-white w-full p-5 space-y-8">
        {{-- form data 1 --}}
        <div class="lg:grid-cols-3 md:grid-cols-2 grid-cols-1 grid gap-5 justify-center">
            <div class="input-box space-y-3">
                <label for="" class="uppercase">Nama<span class="text-theme-primary">*</span></label>
                <input type="text" class=" p-2 w-full border" id="add-label" name="label" />
                <small class="form-text text-red-600 error"></small>
            </div>
            <div class="input-box space-y-3">
                <label for="" class="uppercase">Level<span class="text-theme-primary">*</span></label>
                <select name="add-level" class="w-full p-2 border" id="add-level">
                    <option value="1">1</option>
                    <option value="2">2</option>
                </select>
            </div>
            <div class="input-box space-y-3 hidden" id="parent">
                <label for="" class="uppercase">Induk<span class="text-theme-primary">*</span></label>
                <select name="add-parent_id" class="w-full p-2 border" id="add-parent_id">
                    <option value="" selected>-- Pilih Parent --</option>
                    @foreach ($dataField as $item)
                        <option value="{{ $item->id }}">{{ $item->label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="input-box space-y-3">
                <label for="" class="uppercase">Urutan(terakhir {{$last_sequence}})<span class="text-theme-primary">*</span></label>
                <input type="number" class=" p-2 w-full border" id="add-sequence" name="add-sequence" />
                <small class="form-text text-red-600 error"></small>
            </div>
            <div class="input-box space-y-3">
                <label for="" class="uppercase">Tipe input<span class="text-theme-primary">*</span></label>
                <select name="add-type_input" class="w-full p-2 border" id="add-type_input">
                    <option value="">-- Pilih Tipe Input --</option>
                    <option value="text">Teks</option>
                    <option value="number">Angka</option>
                    <option value="radio">Opsi(radio)</option>
                    <option value="option">Pilihan</option>
                    <option value="file">Berkas</option>
                    <option value="email">Email</option>
                    <option value="password">Password</option>
                </select>
            </div>
            <div class="input-box space-y-3 only-accept-box">
                <label for="" class="uppercase">Hanya menerima<span class="text-theme-primary">*</span></label>
                <select name="add-only_accept" class="w-full p-2 border" id="add-only_accept">
                    <option value="text">Teks</option>
                    <option value="alpha">Huruf</option>
                    <option value="alphanumeric">Huruf & Angka</option>
                    <option value="numeric">Angka</option>
                </select>
            </div>
            <div class="input-box space-y-3">
                <label for="" class="uppercase">Function<span class="text-theme-primary">*</span></label>
                <select name="function" class="w-full p-2 border" id="add-function">
                    <option value="" selected>-- pilih function --</option>
                    <option value="jenisPengajuan(this.value)">jenisPengajuan</option>
                    <option value="jenisPertanggungan(this.value)">jenisPertanggungan</option>
                </select>
            </div>
        </div>
        <div class="extra-item mt-0 space-y-3 hidden">
            <h2 class="text-lg font-bold">Item</h2>
            <table id="extra_item_table" class="tables w-full">
                <thead>
                    <tr>
                        <th>Nilai</th>
                        <th>Nilai yang ditampilkan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <input type="text" class=" p-2 w-full border item-val" name="item_val[]" required/>
                        </td>
                        <td>
                            <input type="text" class=" p-2 w-full border item-display" name="item_display[]" required/>
                        </td>
                        <td class="flex justify-center">
                            <button class="btn-plus-item py-2 bg-green-500 px-5 rounded border text-sm text-white">+</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="lg:grid-cols-3 max-w-lg md:grid-cols-2 grid-cols-1 grid gap-5 justify-end">
            <div class="input-check-box space-y-3 rupiah-box">
                <div class="flex gap-5">
                    <input type="checkbox" name="add-rupiah" id="add-rupiah" class="accent-theme-primary">
                    <input type="hidden" id="add-rupiah-value" name="add-rupiah-value" />
                    <label for="add-rupiah">Rupiah</label>
                </div>
            </div>
            <div class="input-check-box space-y-3">
                <div class="flex gap-5">
                    <input type="checkbox" name="add-readonly" id="add-readonly" class="accent-theme-primary">
                    <input type="hidden" id="add-readonly-value" name="add-readonly-value" />
                    <label for="add-readonly">Read Only</label>
                </div>
            </div>
            <div class="input-check-box space-y-3">
                <div class="flex gap-5">
                    <input type="checkbox" name="add-hidden" id="add-hidden" class="accent-theme-primary">
                    <input type="hidden" id="add-hidden-value" name="add-hidden-value" />
                    <label for="add-hidden">Hidden</label>
                </div>
            </div>
            <div class="input-check-box space-y-3">
                <div class="flex gap-5">
                    <input type="checkbox" name="add-disabled" id="add-disabled" class="accent-theme-primary">
                    <input type="hidden" id="add-disabled-value" name="add-disabled-value" />
                    <label for="add-disabled">Disabled</label>
                </div>
            </div>
            <div class="input-check-box space-y-3">
                <div class="flex gap-5">
                    <input type="checkbox" name="add-type_require" id="add-type_require" class="accent-theme-primary">
                    <input type="hidden" id="add-type_require-value" name="add-type_require-value" />
                    <label for="add-type_require">Required</label>
                </div>
            </div>
        </div>
        <div class="flex gap-5 mt-8 w-full">
            <div class="input-box space-y-3 w-full">
                <label for="" class="uppercase">Rumus</label>
                <input type="text" class="p-2 w-full border bg-neutral-100" id="add-formula" name="formula" placeholder="Preview rumus total = (field - field)" readonly/>
                <small class="form-text text-red-600 error"></small>
            </div>
            <div class="mt-9 w-2/4">
                <button data-target-id="modal-formula" class="px-8 py-2 rounded toggle-modal bg-theme-primary text-white ">
                    Rumus
                </button>
            </div>
        </div>
        <div class="">
            <button id="btnSimpan" class="bg-theme-primary px-8 py-2 text-white rounded-md">Simpan</button>
        </div>
        </div>

    </div>

@endsection
@push('extraScript')
    <script>
        $('#add-parent_id').select2()
        // change checkbox value to 1 or 0
        $('#add-rupiah').on('change', function(){
            $('#add-rupiah-value').val(this.checked ? 1 : 0);
        });
        $('#add-readonly').on('change', function(){
            $('#add-readonly-value').val(this.checked ? 1 : 0);
        });
        $('#add-hidden').on('change', function(){
            $('#add-hidden-value').val(this.checked ? 1 : 0);
        });
        $('#add-disabled').on('change', function(){
            $('#add-disabled-value').val(this.checked ? 1 : 0);
        });
        $('#add-type_require').on('change', function(){
            $('#add-type_require-value').val(this.checked ? 1 : 0);
        });

        $('#add-level').on('change', function(){
            var level = document.getElementById('add-level');
            if (level.value == 2 ) {
                $('#parent').removeClass('hidden');
            }
            else {
                $('#parent').addClass('hidden');
            }
        })

        $('#add-type_input').on('change', function() {
            const selected = $(this).val()
            if (selected == 'option' || selected == 'radio') {
                $('.extra-item').removeClass('hidden')
                $('.only-accept-box').addClass('hidden')
                $('.rupiah-box').addClass('hidden')
            }
            else {
                $('.extra-item').addClass('hidden')
                $('.only-accept-box').removeClass('hidden')
                $('.rupiah-box').removeClass('hidden')
            }
        })

        var number = 1;
        $("#extra_item_table").on("click", ".btn-plus-item", function(e) {
            number = $("#extra_item_table tbody").children().length + 1
            var new_tr = `
            <tr>
                <td>
                    <input type="text" class=" p-2 w-full border item-val" name="item_val[]" required/>
                </td>
                <td>
                    <input type="text" class=" p-2 w-full border item-display" name="item_display[]" required/>
                </td>
                <td class="flex justify-center">
                    <button class="btn-plus-item py-2 bg-green-500 px-5 rounded border text-sm text-white">+</button>
                    <button class="btn-minus-item py-2 text-white rounded border-theme-primary px-5  border text-lg bg-theme-primary ">-</button>
                </td>
            </tr>
            `;
            $('#extra_item_table tbody').append(new_tr);
        })

        $("#extra_item_table").on("click", ".btn-minus-item", function() {
            var table = $(this).parent().parent().parent()
            if (number > 1) {
                number--;
                $(this).closest("tr").remove();
            }
        });

        function resetNoSequence(table) {
            var tr = table.find('tr')
            var table = document.getElementById('extra_item_table');

            var rowLength = table.rows.length;

            for(var i=0; i<rowLength; i+=1){
                number = i+1
            }
        }

        $('#btnSimpan').on('click', function (e) {
            //$('#preload-data').removeClass('hidden')
            e.preventDefault()
            const token = generateCsrfToken()
            const req_function = document.getElementById('add-function');
            const req_label = document.getElementById('add-label');
            const req_level = document.getElementById('add-level');
            const req_parent_id = document.getElementById('add-parent_id');
            const req_type_input = document.getElementById('add-type_input');
            const req_sequence = document.getElementById('add-sequence');
            const req_only_accept = document.getElementById('add-only_accept');
            const req_rupiah = $('#add-rupiah').is(':checked')
            const req_readonly = $('#add-readonly').is(':checked')
            const req_hidden = $('#add-hidden').is(':checked')
            const req_disabled = $('#add-disabled').is(':checked')
            const req_type_required = $('#add-type_require').is(':checked')
            const req_formula = document.getElementById('add-formula');
            var item_val = []
            $('input[name^="item_val"]').each(function(oneTag){
                item_val.push($(this).val());
            });
            var item_display_val = []
            $('input[name^="item_display"]').each(function(oneTag){
                item_display_val.push($(this).val());
            });

            $.ajax({
                type: "POST",
                url: "{{ route('mst-item-asuransi.store') }}",
                data: {
                    _token: token,
                    label: req_label.value,
                    level: req_level.value,
                    parent_id: req_parent_id.value,
                    type: req_type_input.value,
                    formula: req_formula.value,
                    sequence: req_sequence.value,
                    only_accept: req_only_accept.value,
                    rupiah: req_rupiah,
                    readonly: req_readonly,
                    hidden: req_hidden,
                    disabled: req_disabled,
                    required: req_type_required,
                    function: req_function.value,
                    item_val: item_val,
                    item_display_val: item_display_val,
                    item_display_val: item_display_val,
                },
                success: function(data) {
                    if (Array.isArray(data.error)) {
                        $('#preload-data').addClass('hidden')
                        for (var i = 0; i < data.error.length; i++) {
                            var message = data.error[i];
                            console.log(message);
                            if (message.toLowerCase().includes('label'))
                                showError(req_label, message)
                            if (message.toLowerCase().includes('sequence'))
                                showError(req_sequence, message)
                        }
                    } else {
                        if (data.status == 'success') {
                            Swal.fire({
                                title: 'Berhasil',
                                icon: 'success',
                                html: data.message,
                                closeOnClickOutside: false
                            }).then(() => {
                                setTimeout(function() {
                                    window.location = data.url
                                }, 3000);
                            });
                        } else {
                            $('#preload-data').addClass('hidden')
                            ErrorMessage(data.message)
                        }
                    }
                }
            })
        })

        function showError(input, message) {
            const formGroup = input.parentElement;
            const errorSpan = formGroup.querySelector('.error');

            formGroup.classList.add('has-error');
            errorSpan.innerText = message;
            input.focus();
        }
    </script>
@endpush
