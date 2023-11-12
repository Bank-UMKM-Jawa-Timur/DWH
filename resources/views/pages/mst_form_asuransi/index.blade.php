@extends('layout.master')
@section('modal')
<!-- Modal-tambah -->
@include('pages.mst_form_asuransi.modal.create')
<!-- Modal-edit -->
{{-- @include('pages.jenis_asuransi.modal.edit') --}}
@endsection
@section('content')
<div class="head-pages">
    <p class="text-sm">Asuransi</p>
    <h2
      class="text-2xl font-bold text-theme-primary tracking-tighter"
    >
      Master List Form Asuransi
    </h2>
  </div>
  <div class="body-pages">
    <div class="table-wrapper bg-white border rounded-md w-full p-2">
      <div
        class="table-accessiblity lg:flex text-center lg:space-y-0 space-y-5 justify-between"
      >
        <div class="title-table lg:p-3 p-2 text-center">
          <h2
            class="font-bold text-lg text-theme-text tracking-tighter"
          >
             Data Master List Form Asuransi
          </h2>
        </div>
        <div
          class="table-action flex lg:justify-normal justify-center p-2 gap-2"
        >
          <button
            id="form-toggle"
            class="add-modal-form-asuransi px-6 py-2 bg-theme-primary flex gap-3 rounded text-white"
          >
            <span class="lg:mt-0 mt-0">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="23"
                height="23"
                viewBox="0 0 24 24"
              >
                <path
                  fill="none"
                  stroke="currentColor"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M5 12h14m-7-7v14"
                />
              </svg>
            </span>
            <span class="lg:block hidden"> Tambah </span>
          </button>
        </div>
      </div>
      <div
        class="lg:flex lg:space-y-0 space-y-5 lg:text-left text-center justify-between mt-2 p-2"
      >
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
          <form action="{{ route('mst_form_asuransi.index') }}" method="GET">
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
            <th>Nama</th>
            <th>Label</th>
            <th>Level</th>
            <th>Type</th>
            <th>Formula</th>
            <th>Sequence</th>
            <th>Only Accept</th>
            <th>Aksi</th>
          </tr>
          <tbody>
            @forelse ($data as $item)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->perusahaanAsuransi->nama }}</td>
                <td>{{ $item->itemAsuransi->label }}</td>
                <td>{{ $item->itemAsuransi->level }}</td>
                <td>{{ $item->itemAsuransi->type }}</td>
                <td>{{ $item->itemAsuransi->formula ? $item->itemAsuransi->formula : "-" }}</td>
                <td>{{ $item->itemAsuransi->sequence }}</td>
                <td>{{ $item->itemAsuransi->only_accept }}</td>
                <td>
                  <div class="dropdown">
                    <button
                      class="px-4 py-2 bg-theme-btn/10 rounded text-theme-btn">
                      Detail
                    </button>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                  <td colspan="9">
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
                @if ($data instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    {{ $data->links('pagination::tailwind') }}
                @endif
            </div>
        </div>
      </div>
    </div>
  </div>

@endsection
@push('extraScript')
<script>
  $('#page_length').on('change', function() {
    $('#form').submit()
  })

  $(".add-modal-form-asuransi").on("click", function () {
      var targetId = 'add-form-asuransi';
      $("#" + targetId).removeClass("hidden");
      form.addClass("layout-form-collapse");
      if (targetId.slice(0, 5) !== "modal") {
          $(".layout-overlay-form").removeClass("hidden");
      }
  });

  $('#simpanButton').on('click', function (e) { 
    e.preventDefault()
    const req_perusahaan_id = document.getElementById('add-perusahaan_id');
    const req_form_item_id = document.getElementById('add-form_item_asuransi_id');

    console.log(req_perusahaan_id.value);
    console.log(req_form_item_id.value);

    $.ajax({
      type: "POST",
      url: "{{ route('mst_form_asuransi.store') }}",
      data: {
          _token: "{{ csrf_token() }}",
          perusahaan_id: req_perusahaan_id.value,
          form_item_asuransi_id: req_form_item_id.value,
      },
      success: function(data) {
          //console.log(data)
          if (Array.isArray(data.error)) {
              for (var i = 0; i < data.error.length; i++) {
                  var message = data.error[i];
                  console.log(message);
                  if (message.toLowerCase().includes('Perusahaan Asuransi'))
                      showError(req_perusahaan_id, message)
                  if (message.toLowerCase().includes('Item Asuransi'))
                      showError(req_form_item_id, message)
              }
          } else {
              if (data.status == 'success') {
                  SuccessMessage(data.message);
              } else {
                  ErrorMessage(data.message)
              }
          }
      }
    });
  })

  function showError(input, message) {
        // console.log(message);
        const formGroup = input.parentElement;
        const errorSpan = formGroup.querySelector('.error');

        formGroup.classList.add('has-error');
        errorSpan.innerText = message;
        input.focus();
    }
</script>
@endpush