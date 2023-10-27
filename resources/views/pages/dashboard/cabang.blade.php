
<div class="lg:flex grid grid-cols-1 gap-5 mb-5">
    <div class="w-full grid lg:grid-cols-2 md:grid-cols-2 grid-cols-1 justify-center gap-5">
        <div class="card bg-white w-full p-5 border">
            <div class="flex justify-between gap-5">
                <h2 class="mt-3">Pengajuan KKB Diprosess</h2>
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
                <h2 class="mt-3">Pengajuan KKB Bulan Ini</h2>
                <div class="bg-[#FFCED3] p-3 text-theme-primary rounded-full">
                    @include('components.svg.calendar')
                </div>
            </div>
            <div class="p-3">
                <h2 class="text-5xl font-bold">13</h2>
            </div>
        </div>
        <div class="card bg-white w-full p-5 border">
            <div class="flex justify-between gap-5">
                <h2 class="mt-3">Pengajuan KKB Selesai</h2>
                <div class="bg-[#CEF9CE] text-[#16DA12] p-3 rounded-full">
                    @include('components.svg.check')
                </div>
            </div>
            <div class="p-3">
                <h2 class="text-5xl font-bold">43</h2>
            </div>
        </div>
        <div class="card bg-white w-full p-5 border">
            <div class="box-border">
                <div class="bg-[#C4E6D6] text-[#169B5B] float-right -mt-2 p-3 rounded-full">
                    @include('components.svg.google-spreadsheet')
                </div>
                <h2 class="mt-3 max-w-[250px]">Total import KKB Google Spreadsheet</h2>
            </div>
            <div class="p-3">
                <h2 class="text-5xl font-bold">63</h2>
            </div>
        </div>
    </div>
    <div class="card w-full bg-white border rounded">
        <div class="head border-b p-2 font-lexend relative text-center">
            <p class="left-3 absolute">
                <svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 512 512">
                    <path fill="currentColor"
                        d="M124 136H36a20.023 20.023 0 0 0-20 20v320a20.023 20.023 0 0 0 20 20h88a20.023 20.023 0 0 0 20-20V156a20.023 20.023 0 0 0-20-20Zm-12 328H48V168h64Zm188-224h-88a20.023 20.023 0 0 0-20 20v216a20.023 20.023 0 0 0 20 20h88a20.023 20.023 0 0 0 20-20V260a20.023 20.023 0 0 0-20-20Zm-12 224h-64V272h64ZM476 16h-88a20.023 20.023 0 0 0-20 20v440a20.023 20.023 0 0 0 20 20h88a20.023 20.023 0 0 0 20-20V36a20.023 20.023 0 0 0-20-20Zm-12 448h-64V48h64Z" />
                </svg>
            </p>
            <p class="tracking-tighter font-semibold">Data realisasi</p>
        </div>
        <div class="card-body flex justify-center lg:p-5 p-2 gap-5 mt-8">
            <div class="lg:flex w-full">
                <div class="w-full lg:text-right text-center mt-5">
                    <p class="text-gray-500">Target:</p>
                    <h2 class="text-7xl font-bold text-black">
                        {{$target->total_unit}}
                    </h2>
                </div>
                <div class="chart w-full lg:mt-0 mt-10"></div>
                <div class="w-full mt-12">
                    <ul class="space-y-2">
                        <li class="flex">
                            <div class="rounded-md w-4 h-4 mt-[4px] bg-theme-primary"></div>
                            <p class="ml-2">Belum Terealisasi</p>
                        </li>
                        <li class="flex">
                            <div class="rounded-md w-4 h-4 mt-[4px] bg-theme-secondary"></div>
                            <p class="ml-2">Sudah Terealisasi</p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="lg:flex grid grid-cols-1 gap-5">
    <div class="card w-full bg-white border rounded-md">
        <div class="head border-b p-2 font-lexend relative text-center">
            <p class="left-3 absolute">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 16 16">
                    <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="1.5"
                        d="M8 1.75c-2.468 0-4.25 1.5-4.25 3.5v3l-2 3.5h12.5l-2-3.5v-3c0-2-1.166-3.5-4.25-3.5zm-2.25 10.5c0 3 4.5 3 4.5 0" />
                </svg>
            </p>
            <p class="tracking-tighter font-semibold">
                <span class="px-3 text-white py-1 bg-theme-primary rounded-full">{{count($notification)}}</span>
                <span class="ml-2">Notifikasi belum dibaca</span>
            </p>
        </div>
        <div class="card-list divide-y h-full overflow-auto">
            @forelse ($notification as $item)
                <div class="card flex p-2 bg-white w-full rounded-md">
                    <div class="overflow-auto">
                        <div class="mt-2 pl-2">
                            <div class="flex gap-3">
                                <div class="text-theme-primary">{{$item->read ? 'Sudah dibaca' : 'Belum dibaca'}}</div>
                                <div class="text-gray-400">{{date('Y-m-d H:i', strtotime($item->created_at))}}</div>
                            </div>
                            <h2 class="font-bold tracking-tighter text-lg text-theme-text">
                                {{$item->title}}
                            </h2>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-20 space-y-5 h-full border text-center">
                 <div class="mt-40">
                    <img src="{{asset('template/assets/img/empty.svg')}}" class="w-20 m-auto" alt="empty">
                    <p class="text-gray-600">Tidak ada notifikasi yang belum dibaca.</p>
                 </div>
                </div>
            @endforelse
        </div>
    </div>
    <div class="card w-full bg-white border rounded-md">
        <div class="head border-b p-2 font-lexend relative text-center">
            <h2 class="font-semibold tracking-tighter">Data Asuransi</h2>
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
                        <h2 class="text-lg font-semibold">Total Registrasi</h2>
                        <h2 class="font-semibold text-2xl">85</h2> 
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
                        <h2 class="text-lg font-semibold">Total Registrasi Dibatalkan</h2>
                        <h2 class="font-semibold text-2xl">93</h2>
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
                        <h2 class="text-lg font-semibold">Total Pengajuan</h2>
                        <h2 class="font-semibold text-2xl">23</h2>
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
                        <h2 class="text-lg font-semibold">Total Pengajuan Dibatalkan</h2>
                        <h2 class="font-semibold text-2xl">63</h2>
                    </div>
                </div>
            </div>
            <div class="card border w-full p-5">
                <div class="body-card flex gap-5">
                    <div class="bg-theme-primary px-5 text-white py-3 rounded-md">
                        <div class="mt-1">
                            @include('components.svg.jpp-icon')
                        </div>
                    </div>
                    <div class="head">
                        <h2 class="text-lg font-semibold">Jumlah Pelaporan Pelunsan</h2>
                        <h2 class="font-semibold text-2xl">73</h2>
                    </div>
                </div>
            </div>
        </div>


    </div>      
</div>

