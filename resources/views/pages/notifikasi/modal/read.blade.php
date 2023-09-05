<div class="modal-overlay p-5 hidden" id="modalNotifikasi">
    <div class="modal modal-tab">
        <div class="modal-head text-gray-500 text-lg">
            <div class="title-modal">
             <span id="title-notif"></span>
                <p class="text-sm text-gray-400"> <span id="time-notif"></span></p>
            </div>
            <button class="close-modal" data-dismiss-id="modalNotifikasi">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2" d="M17 7L7 17M7 7l10 10" />
                </svg>
            </button>
        </div>
        <div class="modal-body">
            <div class="input-box space-y-3">
                <div class="p-5 space-y-4">
                    <p id="content-notif">Polis Telah Di Upload</p>
                    <p class="extra-notif"></p>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button data-dismiss-id="modalNotifikasi" class="border px-7 py-3 text-black rounded">
                Tutup
            </button>
        </div>
    </div>
</div>
