@extends('layout.master')
@section('content')
    <div>
        <div class="head-pages">
            <p class="text-sm">Asuransi</p>
            <h2 class="text-2xl font-bold text-theme-primary tracking-tighter">
                Pengajuan Klaim Detail
            </h2>
        </div>
        <div class="body-pages">
            <div class="lg:flex gap-5">
                <div class="table-wrapper bg-white border rounded-md w-full p-2">
                    <div class="table-accessiblity lg:flex text-center lg:space-y-0 space-y-5 justify-between">
                        <div class="title-table lg:p-3 p-2 text-center">
                            <h2 class="font-bold text-lg text-theme-text tracking-tighter">
                                Data Pengajuan Klaim
                            </h2>
                        </div>
                    </div>
                    <div class="tables mt-2">
                        <table class="table-auto w-full">
                            <tr>
                                <th rowspan="2">No.</th>
                                @if ($role == 'Pincab' || $role == 'PBP' || $role == 'PBO' || $role == 'Penyelia Kredit')
                                    <th rowspan="2">Nip</th>
                                @endif
                                @if ($role == 'Pincab' || $role == 'PBP' || $role == 'PBO')
                                    <th rowspan="2">@if (\Request::has('id_penyelia'))Staf @else Penyelia @endif</th>
                                @elseif ($role == 'Penyelia Kredit')
                                    <th rowspan="2">Staf</th>
                                @endif
                                @if ($role == 'Pincab' || $role == 'PBP' || $role == 'PBO' || $role == 'Penyelia Kredit')
                                    @if (\Request::has('id_penyelia'))
                                        <th rowspan="2">Debitur</th>
                                    @endif
                                @else
                                    <th rowspan="2">Debitur</th>
                                @endif
                                <th rowspan="2">Jumlah Asuransi</th>
                                <th colspan="2">Status Klaim</th>
                                @if (\Request::has('staf') || $role == 'Staf Analis Kredit')
                                    <th rowspan="2">Status</th>
                                @endif
                            </tr>
                            <tr>
                                <th>Sudah</th>
                                <th>Belum</th>
                            </tr>
                            <tbody>
                                @forelse ($result as $item)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        @if ($role == 'Pincab' || $role == 'PBP' || $role == 'PBO' || $role == 'Penyelia Kredit')
                                            <td>{{$item['nip']}}</td>
                                        @endif
                                        @if ($role == 'Pincab' || $role == 'PBP' || $role == 'PBO')
                                            <td>
                                                @if (\Request::has('id_penyelia'))
                                                    {{$item['nama']}}
                                                @else
                                                    <a href="?id_penyelia={{$item['id']}}" class="border-b border-black text-blue-500">
                                                    {{$item['nama']}}</a>
                                                @endif
                                            </td>
                                        @elseif ($role == 'Penyelia Kredit')
                                            <td>
                                                @if (\Request::has('id_penyelia'))
                                                    {{$item['nama']}}
                                                @else
                                                    <a href="?id_penyelia={{$item['id']}}" class="border-b border-black text-blue-500">
                                                    {{$item['nama']}}</a>
                                                @endif
                                            </td>
                                        @endif
                                        @if ($role == 'Pincab' || $role == 'PBP' || $role == 'PBO')
                                            @if (\Request::has('id_penyelia'))
                                                <td>{{$item['debitur']}}</td>
                                            @endif
                                        @elseif ($role == 'Staf Analis Kredit')
                                            <td>{{$item['debitur']}}</td>
                                        @elseif ($role == 'Penyelia Kredit')
                                            @if (\Request::has('id_penyelia'))
                                                <td>{{$item['debitur']}}</td>
                                            @endif
                                        @else
                                        <td>{{$item['debitur']}}</td>
                                        @endif
                                        <td>{{$item['jml_asuransi']}}</td>
                                        <td>{{$item['jml_diproses']}}</td>
                                        <td>{{($item['jml_asuransi'] - $item['jml_diproses'])}}</td>
                                        @if (\Request::has('staf') || $role == 'Staf Analis Kredit')
                                            <td>
                                                @if ($item['jml_diproses'] == 0)
                                                    Open
                                                @elseif ($item['jml_diproses'] > 0)
                                                    Process
                                                @else
                                                    Close
                                                @endif
                                            </td>
                                        @endif
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{\Request::has('id_penyelia') ? 7 : 6}}">Belum ada data.</td>
                                    </tr>
                                @endforelse
                        </table>
                    </div>
                </div>

                <div class="chart-wrapper bg-white border rounded-md w-2/4 p-2">
                    <div class="chart-klaim-detail"></div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('extraScript')
    <script>
        var total_sudah_klaim = @json($total_sudah_klaim);
        var total_belum_klaim = @json($total_belum_klaim);
        var total_klaim = total_sudah_klaim + total_belum_klaim

        console.log(total_sudah_klaim);
        console.log(total_belum_klaim);

        // chart Registrasi
        var optionsRegistrasi = {
            labels: ['Sudah', 'Belum (On Process)'],
            series: [total_sudah_klaim, total_belum_klaim],
            chart: {
                type: 'donut',
                width: '100%',
                height: 480,
            },
            legend: {
                position: 'bottom'
            },
            dataLabels: {
                enabled: true,
                formatter: function (val, opts) {
                    return opts.w.config.series[opts.seriesIndex]
                },
            },

            plotOptions: {
                pie: {
                    donut: {
                        labels: {
                            show: true,
                            name: {
                                show: true,
                                fontSize: '22px',
                                fontFamily: 'Rubik',
                                color: '#dfsda',
                                offsetY: -10
                            },
                            value: {
                                show: true,
                                fontSize: '16px',
                                fontFamily: 'Helvetica, Arial, sans-serif',
                                color: undefined,
                                offsetY: 16,
                                formatter: function (val) {
                                    return val
                                }
                            },
                            total: {
                                show: true,
                                label: 'Total',
                                color: '#373d3f',
                                formatter: function (w) {
                                    return total_klaim
                                }
                            }
                        }
                    }
                }
            },

            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        width: 340,
                    },
                }
            }]
        };

        var pengajuanKlaim = new ApexCharts(document.querySelector(".chart-klaim-detail"), optionsRegistrasi);
        pengajuanKlaim.render();
    </script>
@endpush