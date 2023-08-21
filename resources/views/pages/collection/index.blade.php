@extends('layout.master')

@section('title', $title)
@section('content')
<div class="panel-header">
    <div class="page-inner py-5">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
            <div>
                <h2 class="text-primary pb-2 fw-bold">{{ $pageTitle }}</h2>
            </div>
        </div>
    </div>
</div>
<div class="page-inner">
    <div class="row">
        <div class="col-md-12">
            <form action="{{route('collection.upload')}}" enctype="multipart/form-data" method="POST">
                @csrf
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">{{$title}}</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 col-lg-6">
                                <div class="form-group name">
                                    <label for="file">File</label>
                                    <input type="file" class="form-control" id="file" name="file" required>
                                    <small class="form-text text-danger error"></small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-action">
                        <button type="submit" class="btn btn-success">Proses</button>
                        <button type="reset" class="btn btn-danger">Reset</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <form action="{{route('collection.store')}}" method="post">
                @csrf
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">{{$title}}</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 col-lg-6">
                                <div class="form-group name">
                                    <label for="filename">File</label>
                                    <input type="text" class="form-control" id="filename" name="filename"
                                        value="{{old('filename', isset($filename) ? $filename : '')}}" required>
                                    <small class="form-text text-danger error"></small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-action">
                        <button type="submit" class="btn btn-success">Proses</button>
                        <button type="reset" class="btn btn-danger">Reset</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection