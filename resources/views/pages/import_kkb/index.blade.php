@extends('layout.master')
@section('content')
<div class="head-pages">
    <p class="text-sm">KKB</p>
    <h2 class="text-2xl font-bold text-theme-primary tracking-tighter">
        Import Table KKB
    </h2>
</div>
<div class="body-pages">
    <div class="form-dictionary bg-white w-full space-y-5 p-4 rounded-md border">
        <div class="table-wrapper bg-white border rounded-md w-full p-2">
            <div class="table-accessiblity lg:flex text-center lg:space-y-0 space-y-5 justify-between">
                <div class="title-table lg:p-3 p-2 text-center">
                    <h2 class="font-bold text-lg text-theme-text tracking-tighter">
                        Item
                    </h2>
                </div>
            </div>
            <div class="lg:flex lg:space-y-0 space-y-5 lg:text-left text-center justify-between mt-2 p-2">
                <div class="sorty pl-1">
                    <a href="#" class="font-bold tracking-tighter">Lihat Contoh Format</a>
                    <p>
                        Catatan! Jika menggunakan fitur import, maka data pada
                        tabel akan dikosongkan terlebih dahulu.
                    </p>
                </div>
                <div class="table-action flex lg:justify-normal justify-center p-2 gap-2">
                    <button id="form-toggle" class="px-6 py-2 bg-gray-600 border flex gap-3 rounded text-white">
                        <span class=""> Upload file</span>
                    </button>
                    <button id="form-toggle" class="px-6 py-2 border flex gap-3 rounded text-gray-600">
                        <span class=""> Import </span>
                    </button>
                </div>
            </div>
            <div class="tables mt-2">
                <table class="table-for-kkb-import table-auto w-full">
                    <tr>
                        <th rowspan="2" scope="col">No.</th>
                        <th rowspan="2" scope="col">Nama Debitur</th>
                        <th rowspan="2" scope="col">Tanggal PO</th>
                        <th rowspan="2" scope="col">Merk Kendaraan</th>
                        <th rowspan="2" scope="col">Tipe Kendaraan</th>
                        <th rowspan="2" scope="col">Tahun</th>
                        <th rowspan="2" scope="col">Warna</th>
                        <th rowspan="2" scope="col">Qty</th>
                        <th rowspan="2" scope="col">Harga</th>
                        <th rowspan="2" scope="col">Nama STNK</th>
                        <th rowspan="2" scope="col">Nominal Realisi</th>
                        <th rowspan="2" scope="col">Nominal Imbal Jasa</th>
                        <th rowspan="2" scope="col">Nominal DP</th>
                        <th rowspan="2" scope="col">Tanggal Realisasi</th>
                        <th rowspan="2" scope="col">Tanggal Pelunasan ke BJSC</th>
                        <th rowspan="2" scope="col">Tanggal Penyerahan Unit</th>
                        <th rowspan="2" scope="col">Tanggal Penyerahan STNK dan Plat Nomor</th>
                        <th rowspan="2" scope="col">Tanggal Penyerahan BPKB</th>
                        <th rowspan="2" scope="col">BPKB Via BJSC</th>
                        <th rowspan="2" scope="col">Tanggal Polis Asuransi</th>
                        <th rowspan="2" scope="col">Polis Via BJSC</th>
                        <th scope="col" colspan="2">
                            Pembayaran Imbal Jasa
                        </th>
                        <th rowspan="2" scope="col">
                            Keterangan (diisi kendala)
                        </th>
                        <tr>
                            <th>Tanggal</th>
                            <th>Nominal (Rp.)</th>
                        </tr>
                    </tr>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Farindra Firdiansyah</td>
                            <td>03/12/2021</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>14/12/2021</td>
                            <td>15/12/2021</td>
                            <td>21/12/2021</td>
                            <td>√</td>
                            <td>14/03/2022</td>
                            <td>sudah</td>
                            <td>sudah</td>
                            <td>sudah</td>
                            <td>20/05/2022</td>
                            <td>400000</td>
                            <td>sesuai konfirmasi dengan BJSC, BPKB sudah ada di dealer, namun lebih dari 3 bulan masih belum /diserahkan ke cabang</td>
                        </tr>
                        <tr>
                            <td>1</td>
                            <td>Hasim Asari</td>
                            <td>03/12/2021</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>14/12/2021</td>
                            <td>15/12/2021</td>
                            <td>21/12/2021</td>
                            <td>√</td>
                            <td>14/03/2022</td>
                            <td>sudah</td>
                            <td>sudah</td>
                            <td>sudah</td>
                            <td>20/05/2022</td>
                            <td>400000</td>
                            <td>sesuai konfirmasi dengan BJSC, BPKB sudah ada di dealer, namun lebih dari 3 bulan masih belum /diserahkan ke cabang</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="footer-table p-3 text-theme-text lg:flex lg:space-y-0 space-y-10 justify-between"></div>
        </div>
    </div>
</div>
@endsection