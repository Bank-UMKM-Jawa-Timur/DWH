@extends('layout.master')

@section('title', $title)

@section('content')

    <div class="panel-header">
        <div class="page-inner py-5">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                <div>
                    <h2 class="text-primary pb-2 fw-bold">{{ $pageTitle }}</h2>
                    <h5 class="text-primary mb-2">Kamu mempunyai {{ $total_belum_dibaca }} notifikasi belum dibaca</h5>
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
                @forelse ($data as $item)
                <div class="card card-notif">
                    <div class="card-body @if ($item->read) reading @endif">
                        <div class="notif ">
                            <span class="alert-notif text-success">@if ($item->read) Sudah Dibaca @else Belum Dibaca @endif</span>
                            <h4>
                                {{ $item->title }} - 
                                {{ strlen($item->content) >= 100 ? substr($item->content,0,100).'...' : $item->content }}
                            </h4>
                            <p class="lead-notif">{{ date('Y-m-d H:i', strtotime($item->created_at)) }}</p>
                        </div>
                    </div>
                </div>
                @empty
                    <p>Belum ada notifikasi.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
