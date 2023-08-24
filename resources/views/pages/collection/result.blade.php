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
                                    value="{{old('filename', isset($filename) ? $filename : '')}}" readonly>
                                <input type="hidden" name="filenametime" id="filenametime" value="{{old('filenametime', isset($filenametime) ? $filenametime : '')}}">
                                <small class="form-text text-danger error"></small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-action">
                    <button type="button" class="btn btn-success" id="btn-process">Proses</button>
                    <button type="reset" class="btn btn-danger">Reset</button>
                </div>
            </div>
        </div>
    </div>

    @isset($result)
    <div class="row mt-2">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">{{$title}} Result</div>
                </div>
                <div class="card-body">
                    <div class="p-3">
                        <p class="h4">
                            Total Data : {{number_format($total_data, 0, ',', '.')}}(Data per halaman {{number_format($total_per_page, 0, ',', '.')}})
                        </p>
                        <div class="form-inline d-flex">
                            <label class="mr-2" for="page">Halaman: </label>
                            <select name="page" id="page">
                                @for($i = 1;$i <= ($total_data / $total_per_page); $i++)
                                <option value="{{$i}}" @if($i == $_GET['page']) selected @endif>{{$i}}</option>
                                @endfor
                            </select>
                            <button class="btn btn-sm btn-info ml-2">Tampilkan</button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table" id="basic-datatables">
                            {{--  <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Field</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>CYSTAT</td>
                                </tr>
                                <tr>
                                    <td>1</td>
                                    <td>CYSTAT</td>
                                </tr>
                                <tr>
                                    <td>1</td>
                                    <td>CYSTAT</td>
                                </tr>
                                <tr>
                                    <td>1</td>
                                    <td>CYSTAT</td>
                                </tr>
                                <tr>
                                    <td>1</td>
                                    <td>CYSTAT</td>
                                </tr>
                                <tr>
                                    <td>1</td>
                                    <td>CYSTAT</td>
                                </tr>
                                <tr>
                                    <td>1</td>
                                    <td>CYSTAT</td>
                                </tr>
                                <tr>
                                    <td>1</td>
                                    <td>CYSTAT</td>
                                </tr>
                                <tr>
                                    <td>1</td>
                                    <td>CYSTAT</td>
                                </tr>
                                <tr>
                                    <td>1</td>
                                    <td>CYSTAT</td>
                                </tr>
                                <tr>
                                    <td>1</td>
                                    <td>CYSTAT</td>
                                </tr>
                            </tbody>  --}}
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
    $('#basic-datatables').DataTable({});
    $('#page').select2();

    $('#btn-process').on('click', function(e) {
        e.preventDefault()
        const filenametime = "{{$filenametime}}"
        const file_url = "{{$file_url}}"
        var dictionary = "{{$dictionary}}"

        var request = {
            'file': filenametime,
            'file_url': file_url,
            'dictionary': dictionary
        }

        $.ajax({
            type: "POST",
            url: "{{env('COLLECTION_API_HOST')}}/extract",
            dataType: "json",
            data: JSON.stringify(request),
            headers: {
                'Access-Control-Request-Headers': 'x-requested-with',
            },
            success: function(data) {
                console.log(`Response : ${data}`)
                alert('berhasil')
            },
            error: function(error) {
                console.log(`Error : `)
                console.log(error)
                alert(error)
            }
        });
    })
</script>
@endpush
@endsection