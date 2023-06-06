<div class="modal fade" id="detailBpkb" tabindex="-1" role="dialog" aria-labelledby="previewBuktiPembayaranModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title" id="previewBuktiPembayaranModalLabel">File BPKB</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="text-light">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <iframe id="filebpkb" src="" class="mt-2" width="100%" height="500"></iframe>
            </div>
        </div>
    </div>
</div>
@push('extraScript')
    <script>
        $('.detailFileBpkb').on('click', function(e) {
            const file = $(this).data('file');
            var path_file = "{{ asset('storage') }}" + "/dokumentasi-bpkb/" + file + "#toolbar=0";
            $('#filebpkb').attr('src', path_file)
        })
    </script>
@endpush
