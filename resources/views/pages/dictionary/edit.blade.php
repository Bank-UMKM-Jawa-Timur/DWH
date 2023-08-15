@extends('layout.master')

@section('title', $title)
@push('extraStyle')
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.7.7/xlsx.core.min.js"></script>  
<script src="https://cdnjs.cloudflare.com/ajax/libs/xls/0.7.4-a/xls.core.min.js"></script>  
@endpush
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
                <form action="{{ route('dictionary.update', $fileDictionary->id) }}" method="post">
                    @csrf
                    @method('PUT')
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
                                            value="{{old('filename', $fileDictionary->filename)}}" required>
                                        <small class="form-text text-danger error"></small>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-6">
                                    <div class="form-group name">
                                        <label for="description">Deskripsi</label>
                                        <input type="text" class="form-control" id="description" name="description"
                                            value="{{old('filename', $fileDictionary->description)}}">
                                        <small class="form-text text-danger error"></small>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col">
                                    <span class="h4 ml-2">Item</span>
                                    <div class="form-group form-inline">
                                        <div class=" p-0">
                                            <input type="file" name="file" id="file" class="form-control-file"
                                                accept="application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                                        </div>
                                        <div>
                                            
                                            <button type="button" class="btn btn-sm btn-info btn-import">
                                                <i class="fas fa-file-excel"></i>
                                                Import
                                            </button>
                                        </div>
                                    </div>
                                    <a style="text-decoration: underline;" href="#formatExcelModal"
                                        class="ml-2" data-toggle="modal" data-target="#formatExcelModal">
                                        Lihat Contoh Format
                                    </a>
                                    <br>
                                    <span class="ml-2">Catatan! Jika menggunakan fitur import, maka data pada tabel akan dikosongkan terlebih dahulu.</span>
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
                                                @forelse ($itemDictionary as $item)
                                                    <tr>
                                                        <td>
                                                            <input type="hidden" name="item_id[]" id="item_id[]" value="{{$item->id}}">
                                                            <span id="number[]">{{$loop->iteration}}</span>
                                                        </td>
                                                        <td>
                                                            <input type="text" name="input_field[]" id="input_field[]"
                                                                class="form-control-sm" value="{{$item->field}}">
                                                        </td>
                                                        <td>
                                                            <input type="text" name="input_from[]" id="input_from[]"
                                                                class="form-control-sm only-number" value="{{$item->from}}">
                                                        </td>
                                                        <td>
                                                            <input type="text" name="input_to[]" id="input_to[]"
                                                                class="form-control-sm  only-number" value="{{$item->to}}">
                                                        </td>
                                                        <td>
                                                            <input type="text" name="input_length[]" id="input_length[]"
                                                                class="form-control-sm  only-number" value="{{$item->length}}">
                                                        </td>
                                                        <td>
                                                            <input type="text" name="input_description[]" id="input_description[]"
                                                                class="form-control-sm" value="{{$item->description}}">
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-sm btn-icon btn-round btn-danger btn-minus">
                                                                <i class="fas fa-minus"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="7">Tidak ada item.</td>
                                                    </tr>
                                                @endforelse
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
                    <td>
                        <input type="hidden" name="item_id[]" id="item_id[]" value="0">
                        <span id="number[]">${number}</span>
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
                `;
                $('#table_item tbody').append(new_tr);
            })

            $("#table_item").on('click', '.btn-minus', function () {
                $(this).closest('tr').remove();
            })

            $('.btn-import').on('click', function(e) {
                var file = $('#file').val()
                if (file)
                    importExcel();
                else
                    alert('please select the file')
            })

            function showToTable(data) {
                for (var i = 0; i < data.length; i++) {
                    var row = data[i]
                    var new_tr = `
                    <tr>
                        <td><span id="number[]">${(i+1)}</span></td>
                        <td>
                            <input type="text" name="input_field[]" id="input_field[]" class="form-control-sm" value="${row[0]}">
                        </td>
                        <td>
                            <input type="text" name="input_from[]" id="input_from[]" class="form-control-sm only-number" value="${row[[1]]}">
                        </td>
                        <td>
                            <input type="text" name="input_to[]" id="input_to[]" class="form-control-sm only-number" value="${row[[2]]}">
                        </td>
                        <td>
                            <input type="text" name="input_length[]" id="input_length[]" class="form-control-sm only-number" value="${row[[3]]}">
                        </td>
                        <td>
                            <input type="text" name="input_description[]" id="input_description[]" class="form-control-sm" value="${row[[4]]}">
                        </td>
                        <td>
                            <button type="button" class="btn btn-sm btn-icon btn-round btn-danger btn-minus">
                                <i class="fas fa-minus"></i>
                            </button>
                        </td>
                    </tr>
                    `;
                    $('#table_item tbody').append(new_tr);
                }
            }

            function importExcel() {
                $('#table_item tbody').empty()
                var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xlsx|.xls)$/;  
                /*Checks whether the file is a valid excel file*/  
                if (regex.test($("#file").val().toLowerCase())) {  
                    var xlsxflag = false; /*Flag for checking whether excel is .xls format or .xlsx format*/  
                    if ($("#file").val().toLowerCase().indexOf(".xlsx") > 0) {  
                        xlsxflag = true;  
                    }  
                    /*Checks whether the browser supports HTML5*/  
                    if (typeof (FileReader) != "undefined") {  
                        var reader = new FileReader();  
                        reader.onload = function (e) {  
                            var data = e.target.result;
                            /*Converts the excel data in to object*/  
                            if (xlsxflag) {  
                                var workbook = XLSX.read(data, { type: 'binary' });  
                            }
                            /*Gets all the sheetnames of excel in to a variable*/  
                            var sheet_name_list = workbook.SheetNames;
                            var cnt = 0; /*This is used for restricting the script to consider only first sheet of excel*/  
                            sheet_name_list.forEach(function (y) { /*Iterate through all sheets*/  
                                var exceljson = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[y]);
                                var excel = workbook.Sheets[y];
                                var cell_range = excel['!ref'] != '' ? excel['!ref'].split(':') : [];
                                var cell_from = cell_range.length == 2 ? cell_range[0] : ''
                                var cell_to = cell_range.length == 2 ? cell_range[1] : ''
                                var letterPattern = /[a-z]+/gi;
                                var cell_from_letter = cell_from.match(letterPattern)[0]
                                var cell_to_letter = cell_to.match(letterPattern)[0]
                                var numberPattern = /\d+/g;
                                var cell_from_number = cell_from.match(numberPattern)[0]
                                var cell_to_number = cell_to.match(numberPattern)[0]
                                var cell_range_letter = ['A', 'B', 'C', 'D', 'E']

                                var arr_data = [];

                                for (var i = 1; i <= cell_to_number; i++) {
                                    var arr_row = [];
                                    for (var j = 0; j < cell_range_letter.length; j++) {
                                        var index = `${cell_range_letter[j]}${i}`
                                        arr_row.push(excel[index].v)
                                    }
                                    arr_data.push(arr_row)
                                }

                                // Show excel data to html table
                                showToTable(arr_data)
                            }); 
                        }  
                        if (xlsxflag) {/*If excel file is .xlsx extension than creates a Array Buffer from excel*/  
                            reader.readAsArrayBuffer($("#file")[0].files[0]);  
                        }  
                        else {  
                            reader.readAsBinaryString($("#file")[0].files[0]);  
                        }  
                    }  
                    else {  
                        alert("Maaf! Browser Anda tidak mendukung HTML5!");  
                    }  
                }  
                else {  
                    alert("Unggah file Excel yang valid!");  
                }  
            }
        </script>
    @endpush
@endsection
