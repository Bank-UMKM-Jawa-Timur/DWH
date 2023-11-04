<div class="bg-white p-3 border">
    <div class="mb-5 p-2">
        <h2 class="font-bold text-2xl tracking-tighter">Data Asuransi</h2>
    </div>
    <div class="lg:flex grid grid-cols-1 gap-5">
        <div class="card w-full bg-white border rounded-md">
            <div class="head border-b p-2 font-lexend relative text-center">
                <h2 class="font-semibold tracking-tighter">Registrasi</h2>
            </div>
            <div class="p-2 space-y-2">
                <div class="card border w-full p-5">
                    <div class="body-card flex gap-5">
                        <div class="bg-theme-primary px-5 text-white text-lg py-3 rounded-md">
                            <div class="mt-1">
                                @include('components.svg.tr-icon')
                            </div>
                        </div>
                        <div class="head">
                            <h2 class="text-lg font-semibold">Total Waiting </h2>
                            <h2 class="font-semibold text-2xl">{{ $total_registrasi }}</h2> 
                        </div>
                    </div>
                </div>
                <div class="card border w-full p-5">
                    <div class="body-card flex gap-5">
                        <div class="bg-theme-primary px-5 py-3 text-white rounded-md">
                            <div class="mt-1">
                                @include('components.svg.tr-dibatalkan')
                            </div>
                        </div>
                        <div class="head">
                            <h2 class="text-lg font-semibold">Total Approval</h2>
                            <h2 class="font-semibold text-2xl">{{ $total_registrasi_dibatalkan }}</h2>
                        </div>
                    </div>
                </div>
                <div class="card border w-full p-5">
                    <div class="body-card flex gap-5">
                        <div class="bg-theme-primary px-5 py-3 text-white rounded-md">
                            <div class="mt-1">
                                @include('components.svg.tp-icon')
                            </div>
                        </div>
                        <div class="head">
                            <h2 class="text-lg font-semibold">Total Revisi</h2>
                            <h2 class="font-semibold text-2xl">{{ $total_pengajuan_klaim }}</h2>
                        </div>
                    </div>
                </div>
                <div class="card border w-full p-5">
                    <div class="body-card flex gap-5">
                        <div class="bg-theme-primary text-white px-5 py-3 rounded-md">
                            <div class="mt-1">
                                @include('components.svg.tp-dibatalkan')
                            </div>
                        </div>
                        <div class="head">
                            <h2 class="text-lg font-semibold">Total Sended</h2>
                            <h2 class="font-semibold text-2xl">{{ $total_pengajuan_klaim_dibatalkan }}</h2>
                        </div>
                    </div>
                </div>
                <div class="card border w-full p-5">
                    <div class="body-card flex gap-5">
                        <div class="bg-theme-primary px-5 text-white py-3 rounded-md">
                            <div class  ="mt-1">
                                @include('components.svg.jpp-icon')
                            </div>
                        </div>
                        <div class="head">
                            <h2 class="text-lg font-semibold">Total Canceled</h2>
                            <h2 class="font-semibold text-2xl">73</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div> 
        <div class="card w-full bg-white border rounded-md">
            <div class="head border-b p-2 font-lexend relative text-center">
                <h2 class="font-semibold tracking-tighter">Pembayaran Premi</h2>
            </div>
            <div class="p-2 space-y-2 mt-5">
                <div class="pembayaran-permi"></div>
            </div>
        </div> 
        <div class="card w-full bg-white border rounded-md">
            <div class="head border-b p-2 font-lexend relative text-center">
                <h2 class="font-semibold tracking-tighter">Pelaporan Pelunasan</h2>
            </div>
            <div class="p-2 space-y-2 mt-5">
                <div class="pelaporan-pelunasan"></div>
            </div>
        </div> 
    </div>
    <div class="border p-3 mt-5">
        <div class="head border p-2 font-lexend relative text-center">
            <h2 class="font-semibold tracking-tighter">Pengajuan Klaim</h2>
        </div>
        <div class="w-full grid lg:grid-cols-3 md:grid-cols-2 grid-cols-1 mt-4 justify-center gap-5">
            <div class="card bg-white w-full p-5 border">
                <div class="flex justify-between gap-5">
                    <h2 class="mt-3">Belum Klaim</h2>
                    <div class="bg-[#FCF18F] p-3 rounded-full">
                        @include('components.svg.clock')
                    </div>
                </div>
                <div class="p-3">
                    <h2 class="text-5xl font-bold">23</h2>
                </div>
            </div>
            <div class="card bg-white w-full p-5 border">
                <div class="flex justify-between gap-5">
                    <h2 class="mt-3">Yang dibatalkan</h2>
                    <div class="bg-[#FFCED3] p-3 text-theme-primary rounded-full">
                        @include('components.svg.tr-dibatalkan')
                    </div>
                </div>
                <div class="p-3">
                    <h2 class="text-5xl font-bold">13</h2>
                </div>
            </div>
            <div class="card bg-white w-full p-5 border">
                <div class="flex justify-between gap-5">
                    <h2 class="mt-3">Sudah Klaim</h2>
                    <div class="bg-[#CEF9CE] text-[#16DA12] p-3 rounded-full">
                        @include('components.svg.check')
                    </div>
                </div>
                <div class="p-3">
                    <h2 class="text-5xl font-bold">43</h2>
                </div>
            </div>
        </div>
    </div>
</div>
