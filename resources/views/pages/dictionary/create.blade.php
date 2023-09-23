@extends('layout.master')

@section('title', $title)

@section('content')
<div class="head-pages">
    <p class="text-sm">Master</p>
    <h2 class="text-2xl font-bold text-theme-primary tracking-tighter">
        Tambah Dictionary
    </h2>
</div>
<div class="body-pages">
    <div class="form-dictionary bg-white w-full space-y-5 p-4 rounded-md border">
        <div class="form-input lg:flex grid grid-cols-1 gap-5">
            <div class="input-box space-y-3 w-full">
                <label for="" class="">File</label>
                <input type="text" class="p-2 w-full border bg-gray-100" />
            </div>
            <div class="input-box space-y-3 w-full">
                <label for="" class="">Deskripsi</label>
                <input type="text" class="p-2 w-full border bg-gray-100" />
            </div>
        </div>
        <div class="table-wrapper bg-white border rounded-md w-full p-2">
            <div class="table-accessiblity lg:flex text-center lg:space-y-0 space-y-5 justify-between">
                <div class="title-table lg:p-3 p-2 text-center">
                    <h2 class="font-bold text-lg text-theme-text tracking-tighter">
                        Item
                    </h2>
                </div>
            </div>
            <div class="lg:flex lg:space-y-0 space-y-5 lg:text-left text-center justify-between mt-2 p-2">
                <div class="sorty pl-1">
                    <a href="#" class="font-bold tracking-tighter">Lihat Contoh Format</a>
                    <p>
                        Catatan! Jika menggunakan fitur import, maka data pada
                        tabel akan dikosongkan terlebih dahulu.
                    </p>
                </div>
                <div class="table-action flex lg:justify-normal justify-center p-2 gap-2">
                    <input type="file" name="file" id="file" class="form-control-file"
                        accept="application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                    <button id="form-toggle" class="px-6 py-2 border flex gap-3 rounded text-gray-600 btn-import">
                        <span class="lg:block hidden"> Import </span>
                    </button>
                </div>
            </div>
            <div class="tables mt-2">
                <table class="table-dictionary table-auto w-full">
                    <tr>
                        <th>No.</th>
                        <th>Field</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Length</th>
                        <th>
                            <button class="w-10 h-10 text-center text-lg rounded-full bg-gray-600 text-white">
                                <span class="flex justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24">
                                        <path fill="currentColor" d="M19 12.998h-6v6h-2v-6H5v-2h6v-6h2v6h6z" />
                                    </svg>
                                </span>
                            </button>
                        </th>
                    </tr>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>
                                <input type="text" class="border p-2 bg-gray-100" />
                            </td>
                            <td>
                                <input type="text" class="border p-2 bg-gray-100" />
                            </td>
                            <td>
                                <input type="text" class="border p-2 bg-gray-100" />
                            </td>
                            <td>
                                <input type="text" class="border p-2 bg-gray-100" />
                            </td>

                            <td>
                                <button class="w-10 h-10 text-center text-lg rounded-full bg-theme-primary text-white">
                                    <span class="flex justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24">
                                            <path fill="currentColor" d="M19 12.998H5v-2h14z" />
                                        </svg>
                                    </span>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="footer-table p-3 text-theme-text lg:flex lg:space-y-0 space-y-10 justify-between"></div>
        </div>
    </div>
</div>
@endsection
@push('extraScript')
    <script>
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