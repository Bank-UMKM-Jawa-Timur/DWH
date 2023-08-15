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
                                        <form action="{{ route('log_aktivitas.index') }}" method="GET">
                                            <input class="form-control form-control-sm" name="query" value="{{ old('query', Request()->query('query')) }}">
                                            <input type="hidden" name="search_by" value="field">
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="table-responsive">
                            <table class="table mt-2" id="basic-datatables">
                                <thead>
                                    <tr class="bg-danger text-light">
                                        <th scope="col">No</th>
                                        <th scope="col">Nama Pengguna</th>
                                        <th scope="col">Content</th>
                                        <th scope="col">Waktu</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($data as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->nip ? $item->nip : $item->email }}</td>
                                            <td>{{ $item->content }}</td>
                                            <td>{{ date('Y-m-d H:i', strtotime($item->created_at)) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">
                                                <span class="text-danger">Maaf data belum tersedia.</span>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                       <div class="paginated">
                            @if($data instanceof \Illuminate\Pagination\LengthAwarePaginator)
                                {{ $data->appends(['page_length' => $page_length])->links('pagination::bootstrap-5') }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('extraScript')
        <script>
            $('#page_length').on('change', function() {
                $('#form').submit()
            })
        </script>
    @endpush
@endsection
