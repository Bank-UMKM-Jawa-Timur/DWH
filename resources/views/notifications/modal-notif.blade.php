{{-- modal notif --}}
<div class="modal fade modal-notifikasi" id="notif" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-body">
                <h3 class="title" id="title-notif"> - </h3>
                <span class="time" id="time-notif"> - </span>
                <hr>
                <p class="no-po"><strong>Nama Debitur:</strong> Nama</p>
                <p class="no-po"><strong>NO PO:</strong> NOPO</p>
                <p class="no-po"><strong>Tanggal:</strong> TANGGAL</p><br>
                <p id="content-notif"</p>
                <div class="extra-notif">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="close" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

@push('extraScript')
    <script>
        $('#close').on('click', function(i) {
            location.reload();
        });
    </script>
@endpush
