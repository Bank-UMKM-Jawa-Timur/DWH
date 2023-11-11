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
                <input type="text" class=" p-2 w-full border" id="" name="" />
                <small class="form-text text-red-600 error"></small>
            </div>
            <div class="input-box space-y-3">
                <label for="" class="uppercase">Level<span class="text-theme-primary">*</span></label>
                <select name="" class="w-full p-2 border">
                    <option value="1">1</option>
                    <option value="2">2</option>
                </select>
            </div>
            <div class="input-box space-y-3">
                <label for="" class="uppercase">Parent<span class="text-theme-primary">*</span></label>
                <select name="" class="w-full p-2 border">
                    <option value="1">No Rekening</option>
                    <option value="2">Jenis Asuransi</option>
                </select>
            </div>
        </div>
        {{-- form data 1 --}}
        <div class="lg:grid-cols-3 md:grid-cols-2 grid-cols-1 grid gap-5 justify-center">
            <div class="input-box space-y-3">
                <label for="" class="uppercase">Type input<span class="text-theme-primary">*</span></label>
                <select name="" class="w-full p-2 border">
                    <option value="text">Text</option>
                    <option value="number">Number</option>
                    <option value="option">Option</option>
                    <option value="radio">radio</option>
                    <option value="file">file</option>
                </select>
            </div>
            <div class="input-box space-y-3">
                <label for="" class="uppercase">Sequence<span class="text-theme-primary">*</span></label>
                <input type="number" class=" p-2 w-full border" id="" name="" />
                <small class="form-text text-red-600 error"></small>
            </div>
            <div class="input-box space-y-3">
                <label for="" class="uppercase">Only Accept<span class="text-theme-primary">*</span></label>
                <select name="" class="w-full p-2 border">
                    <option value="text">Text</option>
                    <option value="number">Number</option>
                    <option value="option">Option</option>
                    <option value="radio">radio</option>
                    <option value="file">file</option>
                </select>
            </div>
        </div>
        {{-- form data 1 --}}
        <div class="lg:grid-cols-3 max-w-lg md:grid-cols-2 grid-cols-1 grid gap-5 justify-end">
            <div class="input-check-box space-y-3">
                <div class="flex gap-5">
                    <input type="checkbox" name="" id="rupiah" class="accent-theme-primary">
                    <label for="rupiah">Rupiah</label>
                </div>
            </div>
            <div class="input-check-box space-y-3">
                <div class="flex gap-5">
                    <input type="checkbox" name="" id="readonly" class="accent-theme-primary">
                    <label for="readonly">Read Only</label>
                </div>
            </div>
            <div class="input-check-box space-y-3">
                <div class="flex gap-5">
                    <input type="checkbox" name="" id="hidden" class="accent-theme-primary">
                    <label for="hidden">Hidden</label>
                </div>
            </div>
            <div class="input-check-box space-y-3">
                <div class="flex gap-5">
                    <input type="checkbox" name="" id="disabled" class="accent-theme-primary">
                    <label for="disabled">Disabled</label>
                </div>
            </div>
            <div class="input-check-box space-y-3">
                <div class="flex gap-5">
                    <input type="checkbox" name="" id="require" class="accent-theme-primary">
                    <label for="require">Required</label>
                </div>
            </div>
        </div>
        <div class="flex gap-5 mt-8 w-full">
            <div class="input-box space-y-3 w-full">
                <label for="" class="uppercase">Field FORMULA<span class="text-theme-primary">*</span></label>
                <input type="text" class="p-2 w-full border bg-neutral-100" id="" name="" placeholder="Preview formula total = (field - field)" readonly/>
                <small class="form-text text-red-600 error"></small>
            </div>
            <div class="mt-9 w-2/4">
                <button data-target-id="modal-formula" class="px-8 py-2 rounded toggle-modal bg-theme-primary text-white ">
                    Create Formula
                </button>
            </div>
        </div>
        <div class="">
            <button class="bg-theme-primary px-8 py-2 text-white rounded-md">Simpan</button>
        </div>
        </div>

    </div>

@endsection

