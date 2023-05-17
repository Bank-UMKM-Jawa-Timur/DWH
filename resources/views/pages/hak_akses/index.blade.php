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
                        <a href="{{ route('role.index') }}" class="btn btn-primary btn-sm">
                            <i class="fa fa-arrow-left"></i>
                            Kembali
                        </a>
                        <form action="{{ route('role.permission.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="role_id" value="{{explode('/',Request::url())[count(explode('/',Request::url()))-1]}}">
                            <div class="table-responsive">
                                <table class="table mt-2">
                                    <thead>
                                        <tr class="bg-danger text-light">
                                            <th scope="col">No</th>
                                            <th scope="col">Aksi</th>
                                            <th scope="col">
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                        <input name="check_all" id="check_all" class="form-check-input" type="checkbox">
                                                        <span class="form-check-sign text-white" for="check_all">Pilih Semua</span>
                                                    </label>
                                                </div>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($data as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <label for="check_{{$item->id}}" style="cursor: pointer;">{{ $item->name }}</label>
                                            </td>
                                            <td>
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                        {{--  <input type="hidden" name="action_id[]" value="{{ $item->id }}">  --}}
                                                        <input id="check_{{$item->id}}" name="check[{{$item->id}}]" class="form-check-input check-item" type="checkbox" @if ($item->status == 'checked') checked @endif>
                                                        <span class="form-check-sign"></span>
                                                    </label>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="3" class="text-center">
                                                <span class="text-danger">Maaf, data belum tersedia.</span>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="2"></th>
                                            <th>
                                            <button type="submit" class="btn btn-primary btn-sm">
                                                Simpan
                                            </button>
                                            </th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal-tambah -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah {{ $pageTitle }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="#" id="modal-form">
                        <div class="form-group name">
                            <label for="name">Nama Peran</label>
                            <input type="text" class="form-control" id="name" name="name">
                            <small class="form-text text-danger error"></small>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal-edit -->
    <div class="modal fade" id="edit1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit {{ $pageTitle }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="#" id="modal-form">
                        <div class="form-group name">
                            <label for="name">Nama Peran</label>
                            <input type="text" class="form-control" id="name" name="name">
                            <small class="form-text text-danger error"></small>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Delete --}}
    <div class="modal fade" id="hapus1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                {{-- <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Hapus {{ $pageTitle }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div> --}}
                <div class="modal-body">
                    <div class="form-group name">
                        Apakah Anda Ingin Menghapus Role Cabang?
                    </div>
                    <div class="form-group">
                        <button data-dismiss="modal" class="btn btn-danger">Batal</button>
                        <button type="submit" class="btn btn-primary">Hapus</button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    @push('extraScript')
        <script>
            $('#check_all').click(function() {
                $('.check-item').prop('checked', this.checked)
            })
        </script>
    @endpush
@endsection
