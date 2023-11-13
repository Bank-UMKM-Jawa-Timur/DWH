@extends('layout.master')
@section('modal')

@include('pages.perusahaan_asuransi.modal.detail-item')

@endsection
@section('content')
<div class="head-pages">
    <p class="text-sm">Master</p>
    <h2
      class="text-2xl font-bold text-theme-primary tracking-tighter"
    >
    Form Asuransi
    </h2>
  </div>
  <div class="body-pages">
    <div class="table-wrapper bg-white border rounded-md w-full p-2">
      <div
        class="table-accessiblity lg:flex text-center lg:space-y-0 space-y-5 justify-between"
      >
        <div class="title-table lg:p-3 p-2 text-left">
          <h2
            class="font-bold text-lg text-theme-text tracking-tighter"
          >
            Item asuransi
          </h2>
          <span>
            <p class="text-sm text-gray-500">Pilih item yang digunakan untuk form asuransi <b>{{$perusahaan->nama}}</b>.</p>
          </span>
        </div>
      </div>
      <div
        class="lg:flex lg:space-y-0 space-y-5 lg:text-left text-center justify-between mt-2 p-2"
      >
      </div>
      <div class="tables mt-2">
        <form action="{{route('perusahaan_asuransi.form-post')}}" method="POST">
        @csrf
            <table class="table-hak-akses table-auto w-full">
              <tr>
                <th>No.</th>
                <th>Label</th>
                <th>Type</th>
                <th>
                  <div class="flex items-center">
                    <input
                      id="check_all"
                      type="checkbox"
                      value=""
                      class="w-5 h-5 accent-current text-theme-primary bg-gray-100 border-gray-300 rounded focus:ring-theme-primary focus:ring-2"
                    />
                    <label
                      for="checked-checkbox"
                      class="ml-2 text-sm font-medium"
                      >Pilih Semua</label
                    >
                  </div>
                </th>
              </tr>
              <tbody>
                @forelse ($data as $item)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td><a  href="#" data-target-id="modal-detail-asuransi" class="toggle-modal underline">{{ $item->label }}</a></td>
                  <td>{{ $item->type }}</td>
                  <td>
                    <input
                      id="check_{{$item->id}}"
                      type="checkbox"
                      name="check[{{$item->id}}]"
                      value=""
                      class="check-item w-5 h-5 accent-current text-theme-primary bg-gray-100 border-gray-300 rounded focus:ring-theme-primary focus:ring-2"
                    />
                  </td>
                </tr>
                @empty
                    <tr>
                        <td>Data item tidak ada.</td>
                    </tr>
                @endforelse
              </tbody>
              <tfoot>
                <th></th>
                <th>
                    <div class="flex gap-5">
                        <a href="{{route('perusahaan-asuransi.index')}}" id="form-reset"
                            class="px-6 py-2 bg-theme-primary/10 flex gap-3 rounded text-theme-primary">
                            <span class="lg:mt-1.5 mt-0">
                                @include('components.svg.reset')
                            </span>
                            <span class="lg:block hidden"> Kembali </span>
                        </a>
                        <button class="px-6 py-2 bg-theme-primary flex gap-3 rounded text-white" type="submit"
                            id="simpan-asuransi">
                            <span class="lg:mt-0 mt-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 24 24">
                                    <path fill="none" stroke="currentColor" stroke-linecap="round"
                                        stroke-linejoin="round" stroke-width="2" d="M5 12h14m-7-7v14" />
                                </svg>
                            </span>
                            <span class="lg:block hidden"> Simpan </span>
                        </button>
                    </div>
                </th>
              </tfoot>
            </table>
        </form>
      </div>
      <div
        class="footer-table p-3 text-theme-text lg:flex lg:space-y-0 space-y-10 justify-between"
      >
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
