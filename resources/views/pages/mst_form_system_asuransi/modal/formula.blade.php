<div class="modal-overlay hidden" id="modal-formula">
    <div class="modal-full modal-tab">
        <div class="modal-head text-gray-500 text-lg">
            <div class="title-modal judul-formula"></div>
            <button class="close-modal" data-dismiss-id="modal-formula">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2" d="M17 7L7 17M7 7l10 10" />
                </svg>
            </button>
        </div>
        <form id="form-send" action="{{ route('asuransi.registrasi.send') }}" method="post" class="p-5">
           <div class="p-3 border">
             <p class="" id="text-formula"></p>
           </div>

        </form>
        <div class="modal-footer mt-3">
            <button data-dismiss-id="modal-formula" class="border px-7 py-3 text-black rounded" type="button">
                Tutup
            </button>
        </div>
    </div>
</div>

@push('extraScript')
    <script>
        $('#formula').on('click', function() {
            var judul = $(this).data('label');
            var formula = $(this).data('formula');
            $('.judul-formula').html('Detail Formula <span class="text-theme-primary">'+judul+'</span>' )
            $('#text-formula').html(formula)
        })
    </script>
@endpush
