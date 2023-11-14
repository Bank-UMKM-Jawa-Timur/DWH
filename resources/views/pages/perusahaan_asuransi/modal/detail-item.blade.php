<div class="modal-overlay p-5 hidden" id="modal-detail-asuransi">
    <div class="modal modal-tab">
        <div class="modal-head text-gray-500 text-lg">
            <div class="title-modal">
                <h2 class="font-bo">Detail Form Asuransi</h2>
            </div>
            <button class="close-modal" data-dismiss-id="modal-detail-asuransi">
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
                        <th colspan="3"><h2 class="judul-modal text-lg"></h2></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            Label
                        </td>
                        <td>
                            :
                        </td>
                        <td>
                            <b class="label"></b>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Level
                        </td>
                        <td>
                            :
                        </td>
                        <td>
                            <b class="level"></b>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Item Induk
                        </td>
                        <td>
                            :
                        </td>
                        <td>
                            <b class="induk">2</b>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Tipe
                        </td>
                        <td>
                            :
                        </td>
                        <td>
                            <b class="type"></b>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Urutan
                        </td>
                        <td>
                            :
                        </td>
                        <td>
                            <b class="urutan"></b>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Hanya menerima
                        </td>
                        <td>
                            :
                        </td>
                        <td>
                            <b class="hanya"></b>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Rupiah
                        </td>
                        <td>
                            :
                        </td>
                        <td>
                            <b class="rupiah"></b>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Read Only
                        </td>
                        <td>
                            :
                        </td>
                        <td>
                            <b class="readonly">tidak</b>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Hidden
                        </td>
                        <td>
                            :
                        </td>
                        <td>
                            <b class="txt-modal-hidden"></b>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Disabled
                        </td>
                        <td>
                            :
                        </td>
                        <td>
                            <b class="disabled"></b>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Required
                        </td>
                        <td>
                            :
                        </td>
                        <td>
                            <b class="required"></b>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Formula
                        </td>
                        <td>
                            :
                        </td>
                        <td>
                         <b class="formula"></b>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="modal-footer flex gap-3 justify-end">
            <button data-dismiss-id="modal-detail-asuransi" class="border px-7 py-3 text-black rounded">
                Tutup
            </button>
        </div>
    </div>
</div>

@push('extraScript')
    <script>
        $('.toggle-modal').on('click', function() {
            var judul = $(this).data('label');
            var level = $(this).data('level');
            var induk = $(this).data('induk');
            var type = $(this).data('type');
            var urutan = $(this).data('urutan');
            var hanya = $(this).data('hanya');
            var rupiah = $(this).data('rupiah');
            var readonly = $(this).data('readonly');
            var hidden = $(this).data('hidden');
            console.log(hidden);
            var disabled = $(this).data('disabled');
            var required = $(this).data('required');
            var formula = $(this).data('rumus');


            $('.judul-modal').html(judul)
            $('.label').html(judul)
            $('.level').html(level)
            $('.induk').html(induk)
            $('.type').html(type)
            $('.urutan').html(urutan)
            $('.hanya').html(hanya)
            $('.rupiah').html(rupiah)
            $('.readonly').html(readonly)
            $('.txt-modal-hidden').html(hidden)
            $('.disabled').html(disabled)
            $('.required').html(required)
            $('.formula').html(formula)
        })
    </script>
@endpush

