@extends('layout.master')

@section('title', $title)

@section('content')

    <div class="head-pages">
        <p class="text-sm">Master</p>
        <h2 class="text-2xl font-bold text-theme-primary tracking-tighter">
          {{ $pageTitle }}
        </h2>
      </div>
      <div class="body-pages">
        <div class="table-wrapper bg-white border rounded-md w-full p-2">
            <div class="table-accessiblity lg:flex text-center lg:space-y-0 space-y-5 justify-between">
                <div class="title-table lg:p-3 p-2 text-center">
                    
                    <h2 class="font-bold text-lg text-theme-text tracking-tighter">
                        Dictionary
                    </h2>
                </div>
                <div class="table-action flex lg:justify-normal justify-center p-2 gap-2">
                    <a href="{{ route('dictionary.create') }}">
                    <button class="px-6 py-2 bg-theme-primary flex gap-3 rounded text-white">
                        <span class="lg:mt-0 mt-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 24 24">
                                <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="M5 12h14m-7-7v14" />
                            </svg>
                        </span>
                        <span class="lg:block hidden"> Tambah </span>
                    </button>
                    </a>
                </div>
            </div>
            <div class="lg:flex lg:space-y-0 space-y-5 lg:text-left text-center justify-between mt-2 p-2">
                <div class="sorty pl-1 w-full">
                    <form id="form" action="" method="GET">
                        <label for="" class="mr-3 text-sm text-neutral-400">show</label>
                        <select class="border px-4 py-1.5 cursor-pointer rounded appearance-none text-center"
                        name="page_length" id="page_length">
                            <option value="5" {{ Request::get('page_length') == '5' ? 'selected' : '' }}>5</option>
                            <option value="10" {{ Request::get('page_length') == '10' ? 'selected' : '' }}>10</option>
                            <option value="15" {{ Request::get('page_length') == '15' ? 'selected' : '' }}>15</option>
                            <option value="20" {{ Request::get('page_length') == '20' ? 'selected' : '' }}>20</option>
                            <option value="all" {{ Request::get('page_length') == 'all' ? 'selected' : '' }}>All</option>
                        </select>
                        <label for="" class="ml-3 text-sm text-neutral-400">entries</label>
                    </form>
                </div>
                <div class="search-table lg:w-96 w-full">
                    <form action="{{ route('dictionary.index') }}" method="GET">
                        <div class="input-search text-[#BFBFBF] rounded-md border flex gap-2">
                            <span class="mt-2 ml-3">
                                @include('components.svg.search')
                            </span>
                                <input type="hidden" name="search_by" value="field">
                                <input type="search" placeholder="Search" class="p-2 rounded-md w-full outline-none text-[#BFBFBF]"
                                    name="query" value="{{ old('query', Request()->query('query')) }}" autocomplete="off" />
                        </div>
                    </form>
                </div>
            </div>
            <div class="tables mt-2">
                <table class="table-auto w-full">
                    <tr>
                        <th>No.</th>
                        <th>File</th>
                        <th>Deskripsi</th>
    
                        <th>Aksi</th>
                    </tr>
                    <tbody>
                        @forelse ($data as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->filename }}</td>
                                <td>{{ $item->description }}</td>
        
                                <td>
                                    <div class="dropdown max-w-[280px]">
                                        <button class="px-4 py-2 bg-theme-btn/10 rounded text-theme-btn">
                                            Selengkapnya
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li class="">
                                                <a class="item-dropdown" href="{{route('dictionary.edit', $item->id)}}">Edit</a>
                                            </li>
                                            <li class="">
                                                <a class="item-dropdown" href="{{route('dictionary.show', $item->id)}}">Detail</a>
                                            </li>
                                            <li class="">
                                                <a class="item-dropdown" data-name="{{ $item->name }}"
                                                    data-id="{{ $item->id }}" href="#">Hapus</a>
                                            </li>
                                        </ul>
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
            <div class="footer-table p-3 text-theme-text lg:flex lg:space-y-0 space-y-10 justify-between">
                <div class="w-full">
                    <div class="pagination">
                        @if($data instanceof \Illuminate\Pagination\LengthAwarePaginator )
                        {{ $data->links('pagination::tailwind') }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Delete --}}
   
    @push('extraScript')
        // Datatable actions
        <script>
            $('#page_length').on('change', function() {
                $('#form').submit()
            })

            $(document).on("click", ".deleteModal", function() {
                var data_id = $(this).data('id');
                var url = "{{ url('/master/dictionary') }}/" + data_id;
                $('#konfirmasi').text("Apakah anda akan menghapus data ini?");
                $('#delete-form').attr("action", url);
                ("action", url);
            });
        </script>
        // End datatable actions
    @endpush
@endsection
