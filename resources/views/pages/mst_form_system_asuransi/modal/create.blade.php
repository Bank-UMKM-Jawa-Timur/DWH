<div class="modal-overlay p-5 hidden" id="modal-formula">
    <div class="modal modal-tab">
        <div class="modal-head text-gray-500 text-lg">
            <div class="title-modal">
                <h2 class="font-bo">Formula Field</h2>
            </div>
            <button class="close-modal" data-dismiss-id="modal-formula">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2" d="M17 7L7 17M7 7l10 10" />
                </svg>
            </button>
        </div>
        <div class="modal-body p-5">
            <table id="formula" class="tables w-full">
                <thead>
                    <tr>
                        <th>Operator</th>
                        <th>Field</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <select name="" id="" class="border p-2 w-full">
                                <option value="+">+</option>
                                <option value="-">-</option>
                                <option value="*">*</option>
                                <option value="/">/</option>
                            </select>
                        </td>
                        <td>
                            <select name="" id="" class="border p-2 w-full"> 
                                <option value="">--- Pilih field ---</option>
                            </select>
                        </td>
                        <td class="flex justify-center">
                            <button class="btn-plus py-2 bg-green-500 px-5 rounded border text-sm text-white">+</button>
                            <button class="btn-minus py-2 text-white rounded border-theme-primary px-5  border text-lg bg-theme-primary ">-</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="modal-footer flex gap-3 justify-end">
            <button id="btn-hitung" class="px-7 py-3 bg-theme-primary flex gap-3 rounded text-white">
                <span class="lg:block hidden"> Create </span>
            </button>
            <button data-dismiss-id="modal-formula" class="border px-7 py-3 text-black rounded">
                Tutup
            </button>
        </div>
    </div>
</div>


@push('extraScript')
<script>
        $('.btn-plus').on('click', function(e) {
                var number = $("#formula tbody").children().length + 1
                var new_tr = `
                <tr>
                    <td>
                        <select name="operator[]" id="" class="border p-2 w-full">
                            <option value="+">+</option>
                            <option value="-">-</option>
                            <option value="*">*</option>
                            <option value="/">/</option>
                        </select>
                    </td>
                    <td>
                        <select name="field[]" id="" class="border p-2 w-full"> 
                            <option value="">--- Pilih field ---</option>
                        </select>
                    </td>
                    <td class="flex justify-center">
                        <button class="btn-plus py-2 bg-green-500 px-5 rounded border text-sm text-white">+</button>
                        <button class="btn-minus py-2 text-white rounded border-theme-primary px-5  border text-lg bg-theme-primary ">-</button>
                    </td>
                    </tr>
                `;
                $('#formula tbody').append(new_tr);
            })

            $("#formula").on('click', '.btn-minus', function () {
                $(this).closest('tr').remove();
            })
</script>
@endpush