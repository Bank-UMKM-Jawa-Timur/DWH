@php
    $role_id = \Session::get(config('global.role_id_session'));
    $staf_analisa_kredit_role = 'Staf Analis Kredit';
    $is_kredit_page = request()->is('kredit');
@endphp
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
