@if (session('status'))
<div
    class="alert gap-5 bg-green-500/10 rounded-md p-3 px-4 font-semibold text-green-500 border-green-500 border">
    <div class="flex">
        <span>
            @include('components.svg.success')
        </span>
        <span>
            <p class="text-sm">
                <b> Berhasil</b>, {{ session('status') }}
            </p>
        </span>
    </div>
</div>
@endif
@if (session('error'))
<div
    class="alert gap-5 bg-theme-primary/10 rounded-md p-3 px-4 font-semibold text-theme-primary border-theme-primary border">
    <div class="flex">
        <span>
            @include('components.svg.warning')
        </span>
        <span>
            <p class="text-sm">
                <b> Gagal</b>, {{ session('error') }}
            </p>
        </span>
    </div>
</div>
@endif