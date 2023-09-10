<div class="tables mt-2">
    <table class="table-auto w-full">
        <thead>
            <tr>
                <th>No</th>
                <th width="150">Nama</th>
                @if ($role_id == 3)
                    <th>Cabang</th>
                @endif
                <th>PO</th>
                <th>Ketersediaan Unit</th>
                <th>Bukti Pembayaran</th>
                <th>Penyerahan Unit</th>
                {{-- @foreach ($documentCategories as $item)
                    <th>{{ $item->name }}</th>
                @endforeach --}}
                <th>STNK</th>
                <th>BPKB</th>
                <th>Polis</th>
                <th>Bukti Pembayaran Imbal Jasa</th>
                <th>Imbal Jasa + 2% Pajak</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody id="tbody">
            @include('pages.kredit.partial._tbody')
        </tbody>
    </table>
</div>
<div class="footer-table p-3 text-theme-text lg:flex lg:space-y-0 space-y-10 justify-between">
    <div class="w-full">
        <div class="pagination">
            @if($data instanceof \Illuminate\Pagination\LengthAwarePaginator )
            {{ $data->links('pagination::tailwind') }}
            @endif
        </div>
    </div>
</div>