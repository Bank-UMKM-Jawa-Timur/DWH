@extends('layout.master')

@section('title', $title)

@section('content')
    <div class="panel-header">
        <div class="page-inner py-5">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                <div>
                    <h2 class="text-primary pb-2 fw-bold">{{ $pageTitle }} {{ $role }}</h2>
                    {{-- <h5 class="text-primary op-7">6819456 | Cabang Bondowoso</h5> --}}
                </div>
            </div>
        </div>
    </div>
    <div class="page-inner mt--5">
        {{-- <div class="card welcome">
            <div class="card-body ">
                <div class="d-flex justify-content-start align-items-end">
                    <img src="{{ asset('template') }}/assets/img/flat_welcome.png" class="img-fluid img-welcome">
                    <div class="box">
                        <h2 class="title-welcome">Hai,Selamat Datang SuperAdmin</h2>
                        <p class="lead-welcome">Senang Betermu dengan SuperAdmin,Selamat Datang di website
                            data
                            warehouse
                            kamu memiliki 3 notifikasi</p>
                    </div>
                </div>
            </div>
        </div> --}}
        <div class="row">
            @if (strtolower($role) != 'vendor')
                <div class="col-sm">
                    <div class="card card-stats card-round">
                        <div class="card-body ">
                            <div class="row align-items-center">
                                <div class="col-icon">
                                    <div class="icon-big text-center icon-primary bubble-shadow-small">
                                        <i class="icon-people"></i>
                                    </div>
                                </div>
                                <div class="col col-stats ml-3 ml-sm-0">
                                    <div class="numbers">
                                        <p class="card-category">{{$total_pengguna}}</p>
                                        <h4 class="card-title">Pengguna</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <div class="col-sm">
                <div class="card card-stats card-round">
                    <div class="card-body ">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-primary bubble-shadow-small">
                                    <i class="icon-briefcase"></i>
                                </div>
                            </div>
                            <div class="col col-stats ml-3 ml-sm-0">
                                <div class="numbers">
                                    <p class="card-category">{{$total_vendor}}</p>
                                    <h4 class="card-title">Vendor</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm">
                <div class="card card-stats card-round">
                    <div class="card-body ">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-primary bubble-shadow-small">
                                    <i class="icon-earphones-alt"></i>
                                </div>
                            </div>
                            <div class="col col-stats ml-3 ml-sm-0">
                                <div class="numbers">
                                    <p class="card-category">{{$total_cabang}}</p>
                                    <h4 class="card-title">Cabang</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if (strtolower($role) == 'cabang')
            {{-- <div class="col-sm-12"> --}}
            <div class="row">
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-header">
                            Chart Data Realisasi
                        </div>
                        <div class="card-body">
                            <span>Target : {{$target}}</span>
                            <canvas id="chartCabang" height="100"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-header">
                            Notifikasi
                        </div>
                        <div class="card-body">
                            @forelse ($notification as $item)
                            <div class="notif-app">
                                <span class="alert-notif text-success notif-status-{{$item->id}}">@if ($item->read) Sudah Dibaca @else Belum Dibaca @endif</span>
                                <h4>
                                    {{ $item->title }} - 
                                    {{ strlen($item->content) >= 100 ? substr($item->content,0,100).'...' : $item->content }}
                                </h4>
                                <p class="lead-notif">{{ date('Y-m-d H:i', strtotime($item->created_at)) }}</p>
                            </div>
                            @empty
                                <span>Belum ada notifikasi.</span>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
            {{-- </div> --}}
        @endif
        @if (strtolower($role) == 'pemasaran')
            <div class="card">
                <div class="card-header">
                    Chart Target Pencapaian
                </div>
                <div class="card-body">
                    <canvas id="chart" height="100"></canvas>
                </div>
            </div>
        @endif
        <div class="row mt--2">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        Data KKB
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table mt-3" id="basic-datatables">
                                <thead>
                                    <tr class="bg-danger text-light">
                                        <th scope="col">No</th>
                                        <th scope="col">Nama</th>
                                        <th scope="col">PO</th>
                                        <th scope="col">Ketersediaan Unit</th>
                                        <th scope="col">Penyerahan Unit</th>
                                        {{--  <th scope="col">STNK</th>
                                        <th scope="col">Polis</th>
                                        <th scope="col">BPKB</th>  --}}
                                        @foreach ($documentCategories as $item)
                                            <th scope="col">{{ $item->name }}</th>
                                        @endforeach
                                        <th scope="col">Imbal Jasa</th>
                                        <th scope="col">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($data as $item)
                                        @php
                                            $buktiPembayaran = \App\Models\Document::where('kredit_id', $item->id)
                                                ->where('document_category_id', 1)
                                                ->first();
                                            $penyerahanUnit = \App\Models\Document::where('kredit_id', $item->id)
                                                ->where('document_category_id', 2)
                                                ->first();
                                            $stnk = \App\Models\Document::where('kredit_id', $item->id)
                                                ->where('document_category_id', 3)
                                                ->first();
                                            $polis = \App\Models\Document::where('kredit_id', $item->id)
                                                ->where('document_category_id', 4)
                                                ->first();
                                            $bpkb = \App\Models\Document::where('kredit_id', $item->id)
                                                ->where('document_category_id', 5)
                                                ->first();
                                            $setImbalJasa = DB::table('imbal_jasas')
                                                ->join('tenor_imbal_jasas as ti', 'ti.imbaljasa_id', 'imbal_jasas.id')
                                                ->select('ti.*')
                                                ->where('plafond1', '>', 1200000)
                                                ->where('plafond2', '>', 1200000)
                                                ->where('tenor', 24)
                                                ->first();
                                        @endphp
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->id.'-'.$item->kode_cabang }}</td>
                                            <td class="link-po">
                                                @if ($buktiPembayaran)
                                                    @isset($item->detail)
                                                    <a class="open-po" data-toggle="modal" data-target="#detailPO" data-nomorPo="{{$item->detail['no_po']}}"
                                                        data-tanggalPo="20 April 2023"
                                                        data-filePo="{{config('global.los_host').$item->detail['po']}}">
                                                        {{$item->detail['nama']}}</a>
                                                    @endisset
                                                @else
                                                    @isset($item->detail)
                                                    <a class="open-po" data-toggle="modal" data-target="#detailPO" data-nomorPo="{{$item->detail['no_po']}}"
                                                        data-tanggalPo="20 April 2023"
                                                        data-filePo="{{config('global.los_host').$item->detail['po']}}">
                                                        {{$item->detail['no_po']}}</a>
                                                    @endisset
                                                @endif
                                            </td>
                                            <td>
                                                @if (Auth::user()->vendor_id)
                                                    @if (!$item->tgl_ketersediaan_unit)
                                                    <a style="text-decoration: underline;" data-toggle="modal"
                                                        data-target="#tglModal"
                                                        data-id_kkb="{{ $item->kkb_id }}"
                                                        href="#">Atur</a>
                                                    @else
                                                    {{ $item->tgl_ketersediaan_unit }}
                                                    @endif
                                                @elseif ($item->tgl_ketersediaan_unit)
                                                    {{ $item->tgl_ketersediaan_unit }}
                                                @else
                                                <span class="text-danger">Menunggu tanggal ketersediaan unit</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($item->tgl_ketersediaan_unit)
                                                    @if ($penyerahanUnit)
                                                        {{ $penyerahanUnit->date }}
                                                    @else
                                                        <span class="text-info">Maksimal tanggal penyerahan unit
                                                            {{ date('Y-m-d', strtotime($item->tgl_ketersediaan_unit . ' +1 days')) }}</span>
                                                    @endif
                                                @else
                                                    <span class="text-danger">Menunggu tanggal ketersediaan unit</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($penyerahanUnit)
                                                    @if ($stnk)
                                                        @if ($stnk->file && $stnk->is_confirm)
                                                            <a href="/storage/dokumentasi-stnk/{{ $stnk->file }}"
                                                                target="_blank">{{ $stnk->date }}</a>
                                                        @else
                                                            <span class="text-warning">Menunggu konfirmasi</span>
                                                        @endif
                                                    @else
                                                        <span class="text-info">Maksimal tanggal upload STNK
                                                            {{ date('Y-m-d', strtotime($penyerahanUnit->date . ' +1 month')) }}</span>
                                                    @endif
                                                @else
                                                    <span class="text-danger">Menunggu tanggal ketersediaan unit</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($penyerahanUnit)
                                                    @if ($polis)
                                                        @if ($polis->file && $polis->is_confirm)
                                                            <a href="/storage/dokumentasi-polis/{{ $polis->file }}"
                                                                target="_blank">{{ $polis->date }}</a>
                                                        @else
                                                            <span class="text-warning">Menunggu konfirmasi</span>
                                                        @endif
                                                    @else
                                                        <span class="text-info">Maksimal tanggal upload Polis
                                                            {{ date('Y-m-d', strtotime($penyerahanUnit->date . ' +1 month')) }}</span>
                                                    @endif
                                                @else
                                                    <span class="text-danger">Menunggu tanggal ketersediaan unit</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($penyerahanUnit)
                                                    @if ($bpkb)
                                                        @if ($polis->file && $polis->is_confirm)
                                                            <a href="/storage/dokumentasi-bpkb/{{ $bpkb->file }}"
                                                                target="_blank">{{ $bpkb->date }}</a>
                                                        @else
                                                            <span class="text-warning">Menunggu konfirmasi</span>
                                                        @endif
                                                    @else
                                                        <span class="text-info">Maksimal tanggal upload Polis
                                                            {{ date('Y-m-d', strtotime($penyerahanUnit->date . ' +3 month')) }}</span>
                                                    @endif
                                                @else
                                                    <span class="text-danger">Menunggu tanggal ketersediaan unit</span>
                                                @endif
                                            </td>
                                            <td>
                                                @isset($setImbalJasa)
                                                {{ number_format($setImbalJasa->imbaljasa), 0, '', '.' }}
                                                @endisset
                                            </td>
                                            <td
                                                class="@if ($item->status == 'done' && $setImbalJasa) text-success @else text-info @endif">
                                                @if ($setImbalJasa)
                                                {{ $item->status }}
                                                @else
                                                progress
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <td colspan="{{ 8 + count($documentCategories) }}" class="text-center">
                                            <span class="text-danger">Maaf data belum tersedia.</span>
                                        </td>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="paginated">
                            {{ $data->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="detailPO" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row container">
                        <div class="col-sm-6">
                            <h5 class="title-po">Nomor PO</h5>
                            <b class="content-po" id="nomorPo">12345678</b>
                        </div>
                        <div class="col-sm-6">
                            <h5 class="title-po">Tanggal PO</h5>
                            <b class="content-po" id="tanggalPo">21 Maret 2023</b>
                        </div>
                        <div class="col-sm-12 mt-4">
                            <h5 class="title-po">File PO</h5>
                            <div class="form-inline mt-1">
                                <button type="button" class="btn btn-primary mr-1 btn-sm">Unduh File PO</button>
                                <button onclick="printPDF()" class="btn btn-info btn-sm" id="printfile">Print File
                                    PO</button>
                                <iframe id="filePo"
                                    src="C:\Users\iqbalronii\Downloads\REv 16 Jan_Jadwal Genap 2023.pdf" class="mt-2"
                                    width="100%" height="500"></iframe>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary">Konfirmasi</button>
                </div> --}}
            </div>
        </div>
    </div>


    @push('extraScript')
        <!-- Chart JS -->
        <script src="{{ asset('template') }}/assets/js/plugin/chart.js/chart.min.js"></script>

        <script src="{{ asset('template') }}/assets/js/plugin/datatables/datatables.min.js"></script>
        <script>
            function printPDF() {
                const pdfURL = 'https://www.africau.edu/images/default/sample.pdf';
                const pdfWindow = window.open(pdfURL, '_blank');

                pdfWindow.onload = function() {
                    pdfWindow.print();
                };
            }

            $('#basic-datatables').DataTable({});
            $(document).ready(function() {
                const ctx = document.getElementById('chart').getContext('2d');

                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ['Cabang A', 'Cabang B', 'Cabang C', 'Cabang D', 'Cabang E', 'Cabang F',
                            'Cabang G', 'Cabang H'
                        ],
                        datasets: [{
                            label: 'Data Set',
                            data: [10, 20, 30, 40, 50, 35, 65, 75, 32],
                            backgroundColor: [
                                'rgba(255, 99, 132)',
                                'rgba(255, 159, 64)',
                                'rgba(255, 205, 86)',
                                'rgba(75, 192, 192)',
                                'rgba(54, 162, 235)',
                                'rgba(153, 102, 255)',
                                'rgba(201, 203, 207)'
                            ],
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 0
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        },
                        maintainAspectRatio: true,
                    }
                });
            });

            $(document).on("click", ".link-po", function() {
                var nomorPo = $(this).data('nomorpo');
                var tanggalPo = $(this).data('tanggalpo');
                var filePo = $(this).data('filepo') + "#toolbar=0";

                $("#nomorPo").text(nomorPo);
                $("#tanggalPo").text(tanggalPo);
                $("#filePo").attr("src", filePo);
            });
        </script>
        @if (Auth::user()->role_id != 3)
            <script>
                $(document).ready(function() {
                    var doughnutChart = document.getElementById('chartCabang').getContext('2d');
    
                    const target = parseInt("{{$target}}");
                    const done = parseInt("{{$total_kkb_done}}");
                    const undone = target - done;
    
                    var myDoughnutChart = new Chart(doughnutChart, {
                        type: 'doughnut',
                        data: {
                            datasets: [{
                                data: [undone, done],
                                backgroundColor: ['#AFD3E2', '#19A7CE']
                            }],
    
                            labels: [
                                'Belum Terealisasi',
                                'Sudah Terealisasi',
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: true,
                            legend: {
                                position: 'right'
                            },
                            layout: {
                                padding: {
                                    // left: 20,
                                    // right: 20,
                                    // top: 20,
                                    // bottom: 20
                                }
                            }
                        }
                    });
                });
            </script>
        @endif
    @endpush
@endsection
