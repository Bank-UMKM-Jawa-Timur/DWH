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
    <div class="page-inner mt--5">
        <div class="row mt--2">
            <div class="col-md-12">
                <form action="{{ route('dictionary.store') }}" method="post">
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
                                        <input type="text" class="form-control" id="filename" name="filename" required>
                                        <small class="form-text text-danger error"></small>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-6">
                                    <div class="form-group name">
                                        <label for="description">Deskripsi</label>
                                        <input type="text" class="form-control" id="description" name="description">
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
                                                    <th>
                                                        <button type="button" class="btn btn-sm btn-icon btn-round btn-primary btn-plus">
                                                            <i class="fas fa-plus"></i>
                                                        </button>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <input type="hidden" name="numbers[]" id="numbers[]">
                                                        <span id="number[]">1</span>
                                                    </td>
                                                    <td>
                                                        <input type="text" name="input_field[]" id="input_field[]" class="form-control-sm">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="input_from[]" id="input_from[]" class="form-control-sm only-number">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="input_to[]" id="input_to[]" class="form-control-sm only-number">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="input_length[]" id="input_length[]" class="form-control-sm only-number">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="input_description[]" id="input_description[]" class="form-control-sm">
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-sm btn-icon btn-round btn-danger btn-minus">
                                                            <i class="fas fa-minus"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-action">
                            <button type="submit" class="btn btn-success">Simpan</button>
                            <button type="reset" class="btn btn-danger">Reset</button>
                        </div>
                    </div>
                </form>
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

            $('.btn-plus').on('click', function(e) {
                var number = $("#table_item tbody").children().length + 1
                var new_tr = `
                <tr>
                    <td><span id="number[]">${number}</span></td>
                    <td>
                        <input type="text" name="input_field[]" id="input_field[]" class="form-control-sm">
                    </td>
                    <td>
                        <input type="text" name="input_from[]" id="input_from[]" class="form-control-sm only-number">
                    </td>
                    <td>
                        <input type="text" name="input_to[]" id="input_to[]" class="form-control-sm only-number">
                    </td>
                    <td>
                        <input type="text" name="input_length[]" id="input_length[]" class="form-control-sm only-number">
                    </td>
                    <td>
                        <input type="text" name="input_description[]" id="input_description[]" class="form-control-sm">
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-icon btn-round btn-danger btn-minus">
                            <i class="fas fa-minus"></i>
                        </button>
                    </td>
                </tr>
                `;
                $('#table_item tbody tr:last').after(new_tr);
            })

            $("#table_item").on('click', '.btn-minus', function () {
                $(this).closest('tr').remove();
            })
        </script>
    @endpush
@endsection
