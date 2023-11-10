<div class="tables mt-2">
    <table class="table-auto w-full">
        <thead>
            <tr>
                <th>No</th>
                <th width="150">Nama</th>
                @if ($role_id != 2)
                    <th>Cabang</th>
                @endif
                <th>Ketersediaan Unit</th>
                <th>Tagihan</th>
                <th>Bukti Pembayaran</th>
                <th>Penyerahan Unit</th>
                <th>Bukti Pembayaran Imbal Jasa</th>
                <th>Imbal Jasa + 2% Pajak</th>
                <th>STNK</th>
                <th>BPKB</th>
                <th>Polis</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody id="tbody">
            @if (Request::has('query'))
                @if (\Session::get(config('global.role_id_session')) != 3)
                    @include('pages.kredit.partial.imported._tbodySearch')
                @endif
            @else
                @include('pages.kredit.partial.imported._tbody')
            @endif
        </tbody>
    </table>
</div>
<div class="footer-table p-3 text-theme-text lg:flex lg:space-y-0 space-y-10 justify-between">
    <div class="w-full">
        <div class="pagination import-pagination">
            @if (Request::has('query'))
                @if (\Session::get(config('global.role_id_session')) != 3)
                    @if ($importedSearch instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        {{ $importedSearch->links('pagination::tailwindSearch') }}
                    @endif
                @endif
            @else
                @if ($imported instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    {{ $imported->links('pagination::tailwind') }}
                @endif
            @endif
        </div>
    </div>
</div>
