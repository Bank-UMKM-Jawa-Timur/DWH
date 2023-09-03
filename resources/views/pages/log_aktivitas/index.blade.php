@extends('layout.master')

@section('title', $title)

@section('content')
    <div class="head-pages">
        <p class="text-sm">Log Aktivitas</p>
        <h2 class="text-2xl font-bold text-theme-primary tracking-tighter">
          {{ $pageTitle }}
        </h2>
    </div>
    <div class="body-pages">
      <div class="table-wrapper bg-white border rounded-md w-full p-2">
          <div class="table-accessiblity lg:flex text-center lg:space-y-0 space-y-5 justify-between">
              <div class="title-table lg:p-3 p-2 text-center">
                  <h2 class="font-bold text-lg text-theme-text tracking-tighter">
                      Log Aktivitas
                  </h2>
              </div>
          </div>
          <div class="lg:flex lg:space-y-0 space-y-5 lg:text-left text-center justify-between mt-2 p-2">
              <div class="sorty pl-1 w-full pr-5">
                  <label for="" class="mr-3 text-sm text-neutral-400">show</label>
                  <select name="" class="border px-4 py-1.5 cursor-pointer rounded appearance-none text-center"
                      id="">
                      <option value="">5</option>
                      <option value="">10</option>
                      <option value="">15</option>
                      <option value="">20</option>
                  </select>
                  <label for="" class="ml-3 text-sm text-neutral-400">entries</label>
              </div>
              <div class="search-table lg:w-96 w-full">
                  <div class="input-search text-[#BFBFBF] rounded-md border flex gap-2">
                      <span class="mt-2 ml-3">
                          <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24">
                              <path fill="currentColor"
                                  d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0 0 16 9.5A6.5 6.5 0 1 0 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5S14 7.01 14 9.5S11.99 14 9.5 14z" />
                          </svg>
                      </span>
                      <input type="search" placeholder="Search" class="p-2 rounded-md w-full outline-none text-[#BFBFBF]"
                          autocomplete="off" />
                  </div>
              </div>
          </div>
          <div class="tables mt-2">
              <table class="table-auto w-full">
                  <tr>
                      <th>No.</th>
                      <th>Nama Pengguna</th>
                      <th>Content</th>
                      <th>Waktu</th>
                  </tr>
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
    @push('extraScript')
        <script>
            $('#page_length').on('change', function() {
                $('#form').submit()
            })
        </script>
    @endpush
@endsection
