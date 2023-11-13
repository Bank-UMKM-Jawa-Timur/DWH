@extends('layout.master')

@section('modal')

@include('pages.mst_form_system_asuransi.modal.create')

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
                <label for="" class="uppercase">Label<span class="text-theme-primary">*</span></label>
                <input type="text" class=" p-2 w-full border" id="add-label" name="label" value="{{ $data->label }}" />
                <small class="form-text text-red-600 error"></small>
            </div>
            <div class="input-box space-y-3">
                <label for="" class="uppercase">Level<span class="text-theme-primary">*</span></label>
                <select name="add-level" class="w-full p-2 border" id="add-level">
                    <option value="">-- Pilih Level --</option>
                    <option value="1" {{ $data->level == 1 ? 'selected' : '' }}>1</option>
                    <option value="2" {{ $data->level == 2 ? 'selected' : '' }}>2</option>
                </select>
            </div>
            <div class="input-box space-y-3">
                {{-- @php
                    $dataField = \App\Models\MstFormItemAsuransi::orderBy('id', 'ASC')->get();
                @endphp --}}
                <label for="" class="uppercase">Parent<span class="text-theme-primary">*</span></label>
                <select name="add-parent_id" class="w-full p-2 border" id="add-parent_id">
                    <option value="">-- Pilih Parent --</option>
                    @foreach ($dataField as $item)
                        <option value="{{ $item->id }}" @if($data->parent_id != null) {{ $data->parent_id == $item->id ? 'selected' : '' }} @endif >{{ $item->label }}</option>  
                    @endforeach
                </select>
            </div>
        </div>
        {{-- form data 1 --}}
        <div class="lg:grid-cols-3 md:grid-cols-2 grid-cols-1 grid gap-5 justify-center">
            <div class="input-box space-y-3">
                <label for="" class="uppercase">Type input<span class="text-theme-primary">*</span></label>
                <select name="add-type_input" class="w-full p-2 border" id="add-type_input">
                    <option value="">-- Pilih Type Input --</option>
                    <option value="text" {{ $data->type == 'text' ? 'selected' : '' }}>Text</option>
                    <option value="number" {{ $data->type == 'number' ? 'selected' : '' }}>Number</option>
                    <option value="option" {{ $data->type == 'option' ? 'selected' : '' }}>Option</option>
                    <option value="radio" {{ $data->type == 'radio' ? 'selected' : '' }}>Radio</option>
                    <option value="file" {{ $data->type == 'file' ? 'selected' : '' }}>File</option>
                    <option value="email" {{ $data->type == 'email' ? 'selected' : '' }}>Email</option>
                    <option value="password" {{ $data->type == 'password' ? 'selected' : '' }}>Password</option>
                </select>
            </div>
            <div class="input-box space-y-3">
                <label for="" class="uppercase">Sequence<span class="text-theme-primary">*</span></label>
                <input type="number" class=" p-2 w-full border" id="add-sequence" name="add-sequence" value="{{ $data?->sequence ?? '' }}" />
                <small class="form-text text-red-600 error"></small>
            </div>
            <div class="input-box space-y-3">
                <label for="" class="uppercase">Only Accept<span class="text-theme-primary">*</span></label>
                <select name="add-only_accept" class="w-full p-2 border" id="add-only_accept">
                    <option value="" {{ $data->only_accept == null ? 'selected' : '' }}>-- Pilih Only Accept --</option>
                    <option value="text" {{ $data->only_accept == 'text' ? 'selected' : '' }}>Text</option>
                    <option value="alpha" {{ $data->only_accept == 'alpha' ? 'selected' : '' }}>Alpha</option>
                    <option value="alphanumeric" {{ $data->only_accept == 'aplphanumeric' ? 'selected' : '' }}>Alphanumeric</option>
                    <option value="numeric" {{ $data->only_accept == 'numeric' ? 'selected' : '' }}>Numeric</option>
                </select>
            </div>
        </div>
        {{-- form data 1 --}}
        @method('PUT')
        <div class="lg:grid-cols-3 max-w-lg md:grid-cols-2 grid-cols-1 grid gap-5 justify-end">
            <div class="input-check-box space-y-3">
                <div class="flex gap-5">
                    <input type="checkbox" value="{{ $data->rupiah }}" name="add-rupiah" id="add-rupiah" class="accent-theme-primary" @checked($data->rupiah)>
                    <input type="hidden" value="{{ $data->rupiah }}" id="add-rupiah-value" name="add-rupiah-value" />
                    <label for="rupiah">Rupiah</label>
                </div>
            </div>
            <div class="input-check-box space-y-3">
                <div class="flex gap-5">
                    <input type="checkbox" value="{{ $data->readonly }}" name="add-readonly" id="add-readonly" class="accent-theme-primary" @checked($data->readonly)>
                    <input type="hidden" value="{{ $data->readonly }}" id="add-readonly-value" name="add-readonly-value" />
                    <label for="readonly">Read Only</label>
                </div>
            </div>
            <div class="input-check-box space-y-3">
                <div class="flex gap-5">
                    <input type="checkbox" value="{{ $data->hidden }}" name="add-hidden" id="add-hidden" class="accent-theme-primary" @checked($data->hidden)>
                    <input type="hidden" value="{{ $data->hidden }}" id="add-hidden-value" name="add-hidden-value" />
                    <label for="hidden">Hidden</label>
                </div>
            </div>
            <div class="input-check-box space-y-3">
                <div class="flex gap-5">
                    <input type="checkbox" value="{{ $data->disabled }}" name="add-disabled" id="add-disabled" class="accent-theme-primary" @checked($data->disabled)>
                    <input type="hidden" value="{{ $data->disabled }}" id="add-disabled-value" name="add-disabled-value" />
                    <label for="disabled">Disabled</label>
                </div>
            </div>
            <div class="input-check-box space-y-3">
                <div class="flex gap-5">
                    <input type="checkbox" value="{{ $data->required }}" name="add-type_require" id="add-type_require" class="accent-theme-primary" @checked($data->required)>
                    <input type="hidden" value="{{ $data->required }}" id="add-type_require-value" name="add-type_require-value" />
                    <label for="require">Required</label>
                </div>
            </div>
        </div>
        <div class="flex gap-5 mt-8 w-full">
            <div class="input-box space-y-3 w-full">
                <label for="" class="uppercase">Field FORMULA<span class="text-theme-primary">*</span></label>
                <input type="text" class="p-2 w-full border bg-neutral-100" id="add-formula" name="formula" placeholder="Preview formula total = (field - field)" value="{{ $data->formula }}" readonly/>
                <small class="form-text text-red-600 error"></small>
            </div>
            <div class="mt-9 w-2/4">
                <button data-target-id="modal-formula" class="px-8 py-2 rounded toggle-modal bg-theme-primary text-white ">
                    Edit Formula
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
    $("#add-parent_id").select2();
    $("#add-type_input").select2();
    $("#add-only_accept").select2();
    $("#add-level").select2();

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

    $('#btnSimpan').on('click', function (e) { 
        e.preventDefault()
        const req_label = document.getElementById('add-label');
        const req_level = document.getElementById('add-level');
        const req_parent_id = document.getElementById('add-parent_id');
        const req_type_input = document.getElementById('add-type_input');
        const req_sequence = document.getElementById('add-sequence');
        const req_only_accept = document.getElementById('add-only_accept');

        const req_rupiah = document.getElementById('add-rupiah-value');
        const req_readonly = document.getElementById('add-readonly-value');
        const req_hidden = document.getElementById('add-hidden-value');
        const req_disabled = document.getElementById('add-disabled-value');
        const req_type_required = document.getElementById('add-type_require-value');

        const req_formula = document.getElementById('add-formula');

        // console.log("label : " + req_label.value);
        // console.log("level : " + req_level.value);
        // console.log("parent : " + req_parent_id.value);
        // console.log("type : " + req_type_input.value);
        // console.log("sequence : " + req_sequence.value);
        // console.log("rupiah : " + req_rupiah.value);
        // console.log("read only : " + req_readonly.value);
        // console.log("hidden : " + req_hidden.value);
        // console.log("disabled : " + req_disabled.value);
        // console.log("required : " + req_type_required.value);
        // console.log("only : " + req_only_accept.value);
        // console.log("parent : " + req_parent_id.value);

        $.ajax({
            type: "POST",
            url: "{{ route('mst_form_system_asuransi.update', $data->id) }}",
            data: {
                _method: 'PUT',
                _token: "{{ csrf_token() }}",
                label: req_label.value,
                level: req_level.value,
                parent_id: req_parent_id.value,
                type: req_type_input.value,
                formula: req_formula.value,
                sequence: req_sequence.value,
                only_accept: req_only_accept.value,
                // have_default_value: 
                rupiah: req_rupiah.value,
                readonly: req_readonly.value,
                hidden: req_hidden.value,
                disabled: req_disabled.value,
                required: req_type_required.value,
            },
            success: function(data) {
                console.log(data)
                if (Array.isArray(data.error)) {
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
                        SuccessMessage(data.message);
                    } else {
                        ErrorMessage(data.message)
                    }
                }
            },
            error: function(res){
                console.log(res);
            }
        });
     })

     function showError(input, message) {
        // console.log(message);
        const formGroup = input.parentElement;
        const errorSpan = formGroup.querySelector('.error');

        formGroup.classList.add('has-error');
        errorSpan.innerText = message;
        input.focus();
    }
  </script>
@endpush
