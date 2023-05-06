@extends('layout.master')

@section('title', $title)

@section('content')

    <div class="panel-header bg-primary-gradient">
        <div class="page-inner py-5">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                <div>
                    <h2 class="text-white pb-2 fw-bold">{{ $pageTitle }}</h2>
                    <h5 class="text-white mb-2">Kamu Mempunyai 1 Notifikasi Belum Dibaca</h5>
                </div>
            </div>
        </div>
    </div>
    <div class="page-inner mt--5">
        <div class="row mt--2">
            <div class="col-md-12">
                {{-- <div class="card card-notif-title">
                    <div class="card-body">
                        <h3 style="font-weight: 800;line-height:10px;margin-top:10px;">Notifikasi</h3>
                        Kamu Mempunyai 3 Notifikasi
                    </div>
                </div> --}}

                {{-- foreach notif --}}
                <div class="card card-notif">
                    <div class="card-body reading">
                        <div class="notif ">
                            <span class="alert-notif text-success">Sudah Dibaca</span>
                            <h4>vendor dimohon untuk mengisikan Ketersediaan Unit Melalui Form Yang...</h4>
                            <p class="lead-notif">1 Menit Yang Lalu</p>
                        </div>
                    </div>
                </div>
                <div class="card card-notif ">
                    <div class="card-body">
                        <div class="notif ">
                            <span class="alert-notif text-danger">Belum Dibaca</span>
                            <h4>vendor dimohon untuk mengisikan Ketersediaan Unit Melalui Form Yang...</h4>
                            <p class="lead-notif">1 Jam Yang Lalu</p>
                        </div>
                    </div>
                </div>
                <div class="card card-notif ">
                    <div class="card-body reading">
                        <div class="notif">
                            <span class="alert-notif text-success">Sudah Dibaca</span>
                            <h4>vendor dimohon untuk mengisikan Ketersediaan Unit Melalui Form Yang...</h4>
                            <p class="lead-notif">1 Jam Yang Lalu</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
