<!-- Detail Modal -->
<div class="modal-overlay hidden font-lexend overflow-auto" id="modalDetailPo">
    <div class="modal modal-tab">
        <div class="modal-head text-gray-500 text-lg">
            <div class="title-modal">Detail</div>
            <button data-dismiss-id="modalDetailPo">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2" d="M17 7L7 17M7 7l10 10" />
                </svg>
            </button>
        </div>

        <div class="modal-body">
            <div class="container mx-auto border-b-2">
                <div class="p-4">
                    <div>
                        <p class="uppercase appearance-none" id="kategori_data"></p>
                        <label for="" class="text-2xl tracking-tighter font-semibold">Data Pengajuan</label>
                        <div class="space-y-3 p-1">
                            <div class="flex gap-5 w-full mt-5">
                                <div class="input-box w-full space-y-3">
                                    <label for="" class="uppercase appearance-none">Nama</label>
                                    <input type="text" disabled class="p-2 w-full border"  id="detail_nama_pengaju" />
                                </div>
                                <div class="input-box w-full space-y-3">
                                    <label for="" class="uppercase appearance-none">Alamat</label>
                                    <input type="text" disabled class="p-2 w-full border" id="detail_alamat_pengaju"/>
                                </div>
                            </div>
                            <div class="input-box w-full space-y-3">
                                <label for="" class="uppercase appearance-none">Cabang</label>
                                <input type="text" disabled class="p-2 w-full border" id="detail_cabang" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <ul class="flex tab-wrapping w-full mt-5 border-b-2 p-[6px]">
                        <li class="tab-li">
                            <a data-tab="tab-po"
                                class="tab-button active-tab cursor-pointer hover:border-b-2 hover:border-theme-primary hover:text-theme-primary py-2 px-4 bg-white text-gray-400">File PO</a>
                        </li>
                        <li class="tab-li lg:block hidden">
                            <a data-tab="tab-bukti"
                                class="tab-button cursor-pointer hover:border-b-2 hover:border-theme-primary hover:text-theme-primary bg-white text-gray-400 py-2 px-4">Bukti Pembayaran</a>
                        </li>
                        <li class="tab-li lg:hidden block">
                            <a data-tab="tab-bukti"
                                class="tab-button cursor-pointer hover:border-b-2 hover:border-theme-primary hover:text-theme-primary bg-white text-gray-400 py-2 px-4">BP</a>
                        </li>
                        <li class="tab-li">
                            <a data-tab="tab-stnk"
                                class="tab-button cursor-pointer hover:border-b-2 hover:border-theme-primary hover:text-theme-primary bg-white text-gray-400 py-2 px-4">STNK</a>
                        </li>
                        <li class="tab-li">
                            <a data-tab="tab-bpkb"
                                class="tab-button cursor-pointer hover:border-b-2 hover:border-theme-primary hover:text-theme-primary bg-white text-gray-400 py-2 px-4">BPKB</a>
                        </li>
                        <li class="tab-li">
                            <a data-tab="tab-polis"
                                class="tab-button cursor-pointer hover:border-b-2 hover:border-theme-primary hover:text-theme-primary bg-white text-gray-400 py-2 px-4">Polis</a>
                        </li>
                    </ul>
                </div>

                <div class="mt-2 p-5">
                    <div id="tab-po" class="tab-content">
                        <div class="gap-5 space-y-3">
                            <div class="flex gap-5 w-full mt-0">
                                <div class="input-box w-full space-y-3">
                                    <label for="" class="uppercase appearance-none">Nomor PO</label>
                                    <input type="text" disabled class="p-2 w-full border" id="detail_nomorPo"/>
                                </div>
                                <div class="input-box w-full space-y-3">
                                    <label for="" class="uppercase appearance-none">Tanggal PO</label>
                                    <input type="text" disabled class="p-2 w-full border" id="detail_tanggalPo" />
                                </div>
                            </div>

                            <div class="space-y-3">
                                <label for="" class="uppercase appearance-none">File PO</label>
                                <div class="content-po h-[528px] w-full bg-gray-100">
                                    <iframe id="new_detail_filepo" src="" class="mt-2" width="100%"
                                    height="500"></iframe>
                                </div>
                                <div class="alert-po hidden text-center">
                                    <img src="{{asset('template/assets/img/news/not-uploaded.svg')}}" alt=""class="max-w-sm mx-auto" />
                                    <p class="font-semibold tracking-tighter text-theme-text">
                                        File PO Tidak ada di server.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="tab-bukti" class="tab-content hidden">
                        <div class="grid grid-cols-1 lg:space-y-5 gap-5">
                            <div class="space-y-5 w-full">
                                <div class="">
                                    <label for="" class="text-2xl tracking-tighter font-semibold">Data
                                        PO</label>
                                    <div class="space-y-5 mt-5">
                                        <div class="flex gap-5 w-full mt-2">
                                            <div class="input-box w-full space-y-3">
                                                <label for="" class="uppercase appearance-none">Nomor
                                                </label>
                                                <input type="text" disabled class="p-2 w-full border" id="detail_no_po" />
                                            </div>
                                            <div class="input-box w-full space-y-3">
                                                <label for="" class="uppercase appearance-none">Merk</label>
                                                <input type="text" disabled class="p-2 w-full border" id="detail_merk" />
                                            </div>
                                        </div>
                                        <div class="flex gap-5 w-full mt-2">
                                            <div class="input-box w-full space-y-3">
                                                <label for="" class="uppercase appearance-none">Tipe
                                                </label>
                                                <input type="text" disabled class="p-2 w-full border" id="detail_tipe" />
                                            </div>
                                            <div class="input-box w-full space-y-3">
                                                <label for="" class="uppercase appearance-none">Tahun</label>
                                                <input type="text" disabled class="p-2 w-full border" id="detail_tahun" />
                                            </div>
                                        </div>
                                        <div class="flex gap-5 w-full mt-2">
                                            <div class="input-box w-full space-y-3">
                                                <label for="" class="uppercase appearance-none">Harga
                                                </label>
                                                <input type="text" disabled class="p-2 w-full border" id="detail_harga" />
                                            </div>
                                            <div class="input-box w-full space-y-3">
                                                <label for="" class="uppercase appearance-none">Jumlah
                                                    Pesanan</label>
                                                <input type="text" disabled class="p-2 w-full border" id="detail_jumlah_pesanan" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="w-full">
                                <div class="space-y-3">
                                    <div class="content-dbp h-[528px] max-w-full mx-auto bg-gray-100">
                                        <iframe id="detail_bukti_pembayaran" src="" class="mt-2 w-full"
                                        height="500"></iframe>
                                    </div>
                                    <div class="alert-dbp hidden text-center">
                                        <img src="{{asset('template/assets/img/news/not-uploaded.svg')}}" alt=""class="max-w-sm mx-auto" />
                                        <p class="font-semibold tracking-tighter text-theme-text">
                                            File Bukti Pembayaran Tidak ada di server.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="tab-stnk" class="tab-content hidden">
                        <div class="flex justify-center">
                            <div class="text-center w-full space-y-5">
                                <div class="content-stnk-detail-modal space-y-5">
                                    <div class="input-box w-full space-y-3 text-left">
                                        <label for="" class="uppercase appearance-none">Nomor</label>
                                        <input type="text" disabled class="p-2 w-full border" id="detail_no_stnk" />
                                    </div>
                                    <div class="h-[528px] w-full bg-gray-100">
                                        <iframe id="detail_preview_stnk" src="" style="width: 100%" height="450px"></iframe>
                                    </div>
                                </div>
                                <div class="alert-stnk-detail-modal hidden">
                                    <img src="{{asset('template/assets/img/news/not-uploaded.svg')}}" alt=""
                                        class="max-w-sm mx-auto" />
                                    <p class="font-semibold tracking-tighter text-theme-text">
                                        File STNK belum di upload
                                    </p>
                                </div>
                                <div class="alert-stnk-not-found hidden text-center">
                                    <img src="{{asset('template/assets/img/news/not-uploaded.svg')}}" alt=""class="max-w-sm mx-auto" />
                                    <p class="font-semibold tracking-tighter text-theme-text">
                                        File STNK Tidak ada di server.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="tab-bpkb" class="tab-content hidden">
                        <div class="flex justify-center">
                            <div class="text-center w-full space-y-5">
                                <div class="content-bpkb-detail-modal space-y-5">
                                    <div class="input-box w-full space-y-3 text-left">
                                        <label for="" class="uppercase appearance-none">Nomor</label>
                                        <input type="text" disabled class="p-2 w-full border" id="detail_no_bpkb" />
                                    </div>
                                    <div class="h-[528px] w-full bg-gray-100">
                                        <iframe id="detail_preview_bpkb" src="" style="width: 100%" height="450px"></iframe>
                                    </div>
                                </div>
                                <div class="alert-bpkb-detail-modal hidden">
                                    <img src="{{asset('template/assets/img/news/not-uploaded.svg')}}" alt=""
                                        class="max-w-sm mx-auto" />
                                    <p class="font-semibold tracking-tighter text-theme-text">
                                        File BPKB belum di upload
                                    </p>
                                </div>
                                <div class="alert-bpkb-not-found hidden text-center">
                                    <img src="{{asset('template/assets/img/news/not-uploaded.svg')}}" alt=""class="max-w-sm mx-auto" />
                                    <p class="font-semibold tracking-tighter text-theme-text">
                                        File BPKB Tidak ada di server.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="tab-polis" class="tab-content hidden">
                        <div class="flex justify-center">
                            <div class="text-center w-full space-y-5">
                                <div class="content-polis-detail-modal space-y-5">
                                    <div class="input-box w-full space-y-3 text-left">
                                        <label for="" class="uppercase appearance-none">Nomor</label>
                                        <input type="text" disabled class="p-2 w-full border" id="detail_no_polis" />
                                    </div>
                                    <div class="h-[528px] w-full bg-gray-100">
                                        <iframe id="detail_preview_polis" src="" width="100%" height="450px"></iframe>
                                    </div>
                                </div>
                                <div class="alert-polis-detail-modal hidden">
                                    <img src="{{asset('template/assets/img/news/not-uploaded.svg')}}" alt=""
                                        class="max-w-sm mx-auto" />
                                    <p class="font-semibold tracking-tighter text-theme-text">
                                        File POLIS belum di upload
                                    </p>
                                </div>
                                <div class="alert-polis-not-found hidden text-center">
                                    <img src="{{asset('template/assets/img/news/not-uploaded.svg')}}" alt=""class="max-w-sm mx-auto" />
                                    <p class="font-semibold tracking-tighter text-theme-text">
                                        File POLIS Tidak ada di server.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('extraScript')
    <script>
        $("[data-dismiss-id]").on("click", function () {
            const dismissId = $(this).data("dismiss-id");
            $("#" + dismissId).addClass("hidden");
            $(".layout-overlay-edit-form").addClass("hidden");
            $('input').val('');
        });

        // tab function
        $(".tab-wrapping .tab-button").click(function (e) {
            e.preventDefault();
            var tabId = $(this).data("tab");

            $(".tab-content").addClass("hidden");
            $(".tab-wrapping .tab-button").removeClass(
            "bg-white border-b border-theme-primary"
            );
            $(".tab-wrapping .tab-button").removeClass("text-gray-400");
            $(".tab-wrapping .tab-button").removeClass("text-theme-primary");

            $(".tab-wrapping .tab-button").addClass("text-gray-400");
            $(".tab-wrapping .tab-button").addClass("border-b-2");

            $(this).addClass("bg-white border-b-2 border-theme-primary");
            $(this).addClass("text-theme-primary");

            if (tabId) {
            $(this).removeClass("text-gray-400");
            $(this).removeClass("bg-gray-100");
            }

            $("#" + tabId).removeClass("hidden");
        });
    </script>
@endpush
