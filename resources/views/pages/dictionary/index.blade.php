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
                <div class="card">
                    <div class="card-header">
                        <a href="{{ route('dictionary.create') }}">
                            <button type="button" class="btn btn-primary btn-sm">
                                Tambah
                            </button>
                        </a>
                    </div>
                    <div class="card-body">
                        <form id="form" action="" method="get">
                            <input type="hidden" name="page" value="{{isset($_GET['page']) ? $_GET['page'] : 1}}">
                            <div class="d-flex justify-content-between" style="padding-left: 15px;padding-right: 15px;">
                                <div>
                                    <div class="form-inline">
                                        <label>Show</label>
                                        &nbsp;
                                        <select class="form-control form-control-sm" name="page_length" id="page_length" >
                                            <option value="5" {{ Request::get('page_length') == '5' ? 'selected' : '' }}>5</option>
                                            <option value="10" {{ Request::get('page_length') == '10' ? 'selected' : '' }}>10</option>
                                            <option value="15" {{ Request::get('page_length') == '15' ? 'selected' : '' }}>15</option>
                                            <option value="20" {{ Request::get('page_length') == '20' ? 'selected' : '' }}>20</option>
                                            <option value="all" {{ Request::get('page_length') == 'all' ? 'selected' : '' }}>All</option>
                                        </select>
                                        &nbsp;
                                        <label>entries</label>
                                    </div>
                                </div>
                                <div>
                                    <div class="form-inline">
                                        <label>Search : </label>
                                        &nbsp;
                                        <input type="search" class="form-control form-control-sm"
                                            name="query" id="query" value="{{ old('query', Request::get('query')) }}">
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="table-responsive">
                            <table class="table mt-2" id="basic-datatables">
                                <thead>
                                    <tr class="bg-danger text-light">
                                        <th scope="col">No</th>
                                        <th scope="col">File</th>
                                        <th scope="col">Deskripsi</th>
                                        <th scope="col">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody">
                                    @forelse ($data as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->filename }}</td>
                                            <td>{{ $item->description }}</td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-info dropdown-toggle" type="button"
                                                        data-toggle="dropdown" aria-expanded="false">
                                                        Selengkapnya
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="#">Edit</a>
                                                        <a class="dropdown-item" href="#">Detail</a>
                                                        <a class="dropdown-item deleteModal" data-toggle="modal"
                                                            data-target="#deleteModal" data-name="{{ $item->name }}"
                                                            data-id="{{ $item->id }}" href="#">Hapus</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center">
                                                <span class="text-danger">Maaf data belum tersedia.</span>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="paginated">
                            @if($data instanceof \Illuminate\Pagination\LengthAwarePaginator )
                            {{ $data->links('pagination::bootstrap-5') }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal-tambah -->
    <div class="modal hide" id="addModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah {{ $pageTitle }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="modal-add-form">
                        <div class="form-group name">
                            <label for="add-name">Nama Peran</label>
                            <input type="text" class="form-control add-name" id="add-name" name="name">
                            <small class="form-text text-danger error"></small>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-primary" id="add-button">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Delete --}}
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="form-group name" id="konfirmasi">
                        Apakah Anda Ingin Menghapus Role?
                    </div>
                    <div class="form-inline">
                        <button data-dismiss="modal" class="btn btn-danger mr-2">Batal</button>
                        <form id="delete-form" method="post">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-primary">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('extraScript')
        // Datatable actions
        <script>
            $('#page_length').on('change', function() {
                $('#form').submit()
            })
        </script>
        // End datatable actions
    @endpush
@endsection
