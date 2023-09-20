@extends('layout.master')

@section('title', $title)
@section('content')

<div class="page-inner">
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
        <div>
            <h2 class="text-primary pb-2 fw-bold">{{ $pageTitle }}</h2>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <a href="{{route('collection.index')}}" class="btn btn-sm btn-warning">
                <i class="fas fa-arrow-left"></i>
                Kembali
            </a>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">{{$pageTitle}}</div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 col-lg-6">
                            <div class="form-group name">
                                <label for="filename">File</label>
                                <input type="text" class="form-control" id="filename" name="filename"
                                    value="{{old('filename', isset($file) ? $file : '')}}" readonly>
                                <input type="hidden" name="filenametime" id="filenametime" value="{{old('filenametime', isset($filenametime) ? $filenametime : '')}}">
                                <small class="form-text text-danger error"></small>
                            </div>
                        </div>
                    </div>
                </div>
                @empty($result)
                <div class="card-action">
                    <button type="button" class="btn btn-success" id="btn-process">Proses</button>
                    <button type="reset" class="btn btn-danger">Reset</button>
                </div>
                @endempty
            </div>
        </div>
    </div>

    @isset($result)
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">{{$title}}</div>
                </div>
                <div class="card-body">
                    <div class="p-3">
                        <p class="h4">
                            Total Data : {{number_format($total_all_data, 0, ',', '.')}}(Data per halaman {{number_format($total_per_page, 0, ',', '.')}})
                        </p>
                        @if ($total_all_data > $total_per_page)
                            <form id="show-by-page-form" action="{{route('collection.page')}}" method="POST">
                                @csrf
                                <div class="form-inline d-flex">
                                    <input type="hidden" name="file" id="file" value="{{$file}}">
                                    <input type="hidden" name="result_filename" id="result_filename" value="{{$result_filename}}">
                                    <input type="hidden" name="total_all_data" value="{{$total_all_data}}">
                                    <input type="hidden" name="total_page" value="{{$total_page}}">
                                    <label class="mr-2" for="page">Halaman: </label>
                                    <select name="page" id="page" class="pl-2 pr-2">
                                        @for($i = 1;$i <= $total_page; $i++)
                                            <option value="{{$i}}" @if($i == $page) selected @endif>{{$i}}</option>
                                        @endfor
                                    </select>
                                    <button type="submit" class="btn btn-sm btn-info ml-2">Tampilkan</button>
                                </div>
                            </form>
                        @endif
                    </div>
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
                                @forelse ($result as $key => $item)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        @for($i = 0; $i < count($fields); $i++)
                                            <td>{{$result[$key][$fields[$i]]}}</td>
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
    $('#page').select2();
    $('#show-by-page-form').on('submit', function() {
        $('#loadingModal').modal('show')
    })
</script>
@endpush
@endsection