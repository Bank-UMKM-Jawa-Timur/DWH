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
                                        <p class="card-category">1020</p>
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
                                    <p class="card-category">1320</p>
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
                                    <p class="card-category">320</p>
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
                            <canvas id="chartCabang" height="100"></canvas>
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
                            <table class="table mt-3">
                                <thead>
                                    <tr class="bg-danger text-light">
                                        <th scope="col">No</th>
                                        <th scope="col">Nama</th>
                                        <th scope="col">PO</th>
                                        <th scope="col">Ketersediaan Unit</th>
                                        <th scope="col">Penyerahan Unit</th>
                                        <th scope="col">STNK</th>
                                        <th scope="col">Polis</th>
                                        <th scope="col">BPKB</th>
                                        <th scope="col">Imbal Jasa</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>Rio Ardiansyah</td>
                                        <td class="link-po">2AFda12j7s</td>
                                        <td>28 April 2023</td>
                                        <td>29 April 2023</td>
                                        <td>1 Mei 2023</td>
                                        <td>5 Mei 2023</td>
                                        <td>10 Mei 2023</td>
                                        <td>Rp.5000</td>
                                        <td class="text-success">Selesai</td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-info dropdown-toggle" type="button"
                                                    data-toggle="dropdown" aria-expanded="false">
                                                    Selengkapnya
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="#">Detai</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>1</td>
                                        <td>Rio Ardiansyah</td>
                                        <td class="link-po">2AFda12j7s</td>
                                        <td>28 April 2023</td>
                                        <td>29 April 2023</td>
                                        <td>1 Mei 2023</td>
                                        <td>5 Mei 2023</td>
                                        <td>10 Mei 2023</td>
                                        <td>Rp.5000</td>
                                        <td class="text-success">Selesai</td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-info dropdown-toggle" type="button"
                                                    data-toggle="dropdown" aria-expanded="false">
                                                    Selengkapnya
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="#">Detai</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>1</td>
                                        <td>Rio Ardiansyah</td>
                                        <td class="link-po">2AFda12j7s</td>
                                        <td>28 April 2023</td>
                                        <td>29 April 2023</td>
                                        <td>1 Mei 2023</td>
                                        <td>5 Mei 2023</td>
                                        <td>10 Mei 2023</td>
                                        <td>Rp.5000</td>
                                        <td class="text-success">Selesai</td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-info dropdown-toggle" type="button"
                                                    data-toggle="dropdown" aria-expanded="false">
                                                    Selengkapnya
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="#">Detai</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    @push('extraScript')
        <!-- Chart JS -->
        <script src="{{ asset('template') }}/assets/js/plugin/chart.js/chart.min.js"></script>

        <script>
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

            $(document).ready(function() {
                var doughnutChart = document.getElementById('chartCabang').getContext('2d');

                var myDoughnutChart = new Chart(doughnutChart, {
                    type: 'doughnut',
                    data: {
                        datasets: [{
                            data: [15, 5, 10],
                            backgroundColor: ['#146C94', '#19A7CE', '#AFD3E2']
                        }],

                        labels: [
                            'Realisasi',
                            'Belum Realisasi',
                            'Target',
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
    @endpush
@endsection
