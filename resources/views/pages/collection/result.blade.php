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
            <form action="{{route('collection.index')}}" method="get">
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

    @isset($result)
    <div class="row mt-2">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">{{$title}}</div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table" id="basic-datatables">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    @for($i = 0; $i < count($fields); $i++)
                                        <th>{{$fields[$i]}}</th>
                                    @endfor
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($result['data'] as $key => $item)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        @for($i = 0; $i < count($fields); $i++)
                                            <td>{{$result['data'][$key][$fields[$i]]}}</td>
                                        @endfor
                                    </tr>
                                @empty
                                    
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endisset
</div>
@push('extraScript')
<script src="{{ asset('template') }}/assets/js/plugin/datatables/datatables.min.js"></script>
<script>
    $('#basic-datatables').DataTable({});
</script>
@endpush
@endsection