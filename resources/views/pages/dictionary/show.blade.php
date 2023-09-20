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
                <a href="{{route('dictionary.index')}}" class="btn btn-sm btn-warning">
                    <i class="fas fa-arrow-left"></i>
                    Kembali
                </a>
            </div>
        </div>
        <div class="row mt-2">
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
                                        value="{{old('filename', $fileDictionary->filename)}}" readonly>
                                    <small class="form-text text-danger error"></small>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-6">
                                <div class="form-group name">
                                    <label for="description">Deskripsi</label>
                                    <input type="text" class="form-control" id="description" name="description"
                                        value="{{old('filename', $fileDictionary->description)}}" readonly>
                                    <small class="form-text text-danger error"></small>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col">
                                <span class="h4 ml-2">Item</span>
                                <div class="table-responsive">
                                    <table class="table mt-2" id="table_item">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Field</th>
                                                <th>From</th>
                                                <th>To</th>
                                                <th>Length</th>
                                                <th>Description</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($itemDictionary as $item)
                                                <tr>
                                                    <td>
                                                        <input type="hidden" name="item_id[]" id="item_id[]" value="{{$item->id}}">
                                                        <span id="number[]">{{$loop->iteration}}</span>
                                                    </td>
                                                    <td>
                                                        <input type="text" name="input_field[]" id="input_field[]"
                                                            class="form-control-sm" value="{{$item->field}}" readonly>
                                                    </td>
                                                    <td>
                                                        <input type="text" name="input_from[]" id="input_from[]"
                                                            class="form-control-sm only-number" value="{{$item->from}}" readonly>
                                                    </td>
                                                    <td>
                                                        <input type="text" name="input_to[]" id="input_to[]"
                                                            class="form-control-sm  only-number" value="{{$item->to}}" readonly>
                                                    </td>
                                                    <td>
                                                        <input type="text" name="input_length[]" id="input_length[]"
                                                            class="form-control-sm  only-number" value="{{$item->length}}" readonly>
                                                    </td>
                                                    <td>
                                                        <input type="text" name="input_description[]" id="input_description[]"
                                                            class="form-control-sm" value="{{$item->description}}" readonly>
                                                    </td
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6">Tidak ada item.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('extraScript')
        <script>
            $(".only-number").keyup(function (e) {
                this.value = this.value.replace(/[^\d]/, "");
            });

            function updateNumber() {
                var tbody = $("#table_item tbody").children().length
                for (var i = 0; i < tbody; i++) {
                    
                }
            }
        </script>
    @endpush
@endsection
