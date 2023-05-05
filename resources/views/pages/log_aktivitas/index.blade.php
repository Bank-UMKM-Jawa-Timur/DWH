@extends('layout.master')

@section('title', $title)

@section('content')

    <div class="panel-header bg-primary-gradient">
        <div class="page-inner py-5">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                <div>
                    <h2 class="text-white pb-2 fw-bold">{{ $pageTitle }}</h2>
                </div>
            </div>
        </div>
    </div>
    <div class="page-inner mt--5">
        <div class="row mt--2">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table mt-2">
                                <thead>
                                    <tr class="bg-danger text-light">
                                        <th scope="col">No</th>
                                        <th scope="col">Nama Pengguna</th>
                                        <th scope="col">Content</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>Andriansyah</td>
                                        <td>Melakukan Pengiriman STNK</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
