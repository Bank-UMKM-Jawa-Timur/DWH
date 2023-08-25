@extends('layout.master')
@push('extraStyle')
    <style>
        #upload-form .card-action {
            display: none;
        }
    </style>
@endpush
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
            <form id="upload-form" action="{{route('collection.store')}}" method="POST">
                @csrf
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Upload {{$title}}</div>
                    </div>
                    <div class="card-body">
                        <div id="upload-container" class="text-center">
                            <button type="button" id="browse_file" class="btn btn-primary">Pilih Berkas</button>
                            <p class="text-filename">File : </p>
                            <input type="hidden" name="file" id="file">
                            <input type="hidden" name="result_filename" id="result_filename">
                        </div>
                        <div class="progress mt-3 position-relative" style="height: 25px; width: 100%;">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%; height: 100%"></div>
                        </div>
                    </div>
                    <div class="card-action">
                        <button type="submit" class="btn btn-success">Proses</button>
                        <button type="reset" class="btn btn-danger" id="btn-reset">Reset</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@push('extraScript')
<script src="https://cdnjs.cloudflare.com/ajax/libs/resumable.js/1.1.0/resumable.min.js"></script>
<script>
    let browseFile = $("#browse_file")
    var resumable = new Resumable({
        target: "{{route('collection.upload')}}",
        query: {_token: '{{csrf_token()}}'},
        filetype: ['txt'],
        headers: {
            'Accept': 'application/json'
        },
        testChunks: false,
        throttleProgressCallbacks: 1,
    });

    resumable.assignBrowse(browseFile[0]);

    resumable.on('fileAdded', function(file) { // trigger when file picked
        console.log('File picked')
        const filename = file.fileName
        const ext = file.fileName.split(".")[1]
        if (ext == 'txt') {
            $('.text-filename').html(`File : ${filename}`)
            $('#file').val(filename)
            showProgress();
            resumable.upload() // to actually start uploading
        }
        else {
            errorMessage('Hanya bisa memilih file berekstensi .txt')
        }
    })

    resumable.on('fileProgress', function(file) { // trigger when file progress update
        updateProgress(Math.floor(file.progress() * 100))
    })

    resumable.on('fileSuccess', function(file, response) { // trigger when file upload complete
        successMessage('Berhasil mengupload file')
        console.log('file uploaded')
        var res = JSON.parse(response)
        const filename = res.filename
        $('#result_filename').val(filename)
        $('#upload-form').find('.card-action').show()
    })

    resumable.on('fileError', function(file, response) { // trigger when file upload error
        console.log(`upload error : ${response}`)
        errorMessage('Terjadi kesalahan')
    })

    let progress = $('.progress')

    function showProgress() {
        progress.find('.progress-bar').css('width', '0%')
        progress.find('.progress-bar').html('0%')
        progress.find('.progress-bar').removeClass('bg-success')
        progress.show()
    }

    function updateProgress(value) {
        progress.find('.progress-bar').css('width', `${value}%`)
        progress.find('.progress-bar').html(`${value}%`)
    }

    function hideProgress() {
        progress.hide()
    }

    function successMessage(message) {
        swal("Berhasil!", message, {
            icon: "success",
            timer: 3000,
            closeOnClickOutside: false
        })
    }

    function errorMessage(message) {
        swal("Gagal!", message, {
            icon: "error",
            timer: 3000,
            closeOnClickOutside: false
        })
    }

    $('#btn-reset').on('click', function(e) {
        $('#file').val('')
        $('.text-filename').html('File : ')
        progress.find('.progress-bar').css('width', '0%')
        progress.find('.progress-bar').html('0%')
    })
</script>
@endpush
@endsection