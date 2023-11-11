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
            <p class="text-sm text-gray-500">Pilih item yang digunakan untuk form asuransi <b>Ekalloyd</b>.</p>
          </span>
        </div>
      </div>
      <div
        class="lg:flex lg:space-y-0 space-y-5 lg:text-left text-center justify-between mt-2 p-2"
      >
      </div>
      <div class="tables mt-2">
        <table class="table-hak-akses table-auto w-full">
          <tr>
            <th>No.</th>
            <th>Label</th>
            <th>Type</th>
            <th>
              <div class="flex items-center">
                <input
                  checked
                  id="checked-checkbox"
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
            <tr>
              <td>1</td>
              <td><a  href="#" data-target-id="modal-detail-asuransi" class="toggle-modal underline">No Rekening</a></td>
              <td>Text</td>

              <td>
                <input
                  checked
                  id="checked-checkbox"
                  type="checkbox"
                  value=""
                  class="w-5 h-5 accent-current text-theme-primary bg-gray-100 border-gray-300 rounded focus:ring-theme-primary focus:ring-2"
                />
              </td>
            </tr>
            <tr>
              <td>2</td>
              <td><a  href="#" data-target-id="modal-detail-asuransi" class="toggle-modal underline">Jenis Pengajuan</a></td>
              <td>Select</td>

              <td>
                <input
                  checked
                  id="checked-checkbox"
                  type="checkbox"
                  value=""
                  class="w-5 h-5 accent-current text-theme-primary bg-gray-100 border-gray-300 rounded focus:ring-theme-primary focus:ring-2"
                />
              </td>
            </tr>

          </tbody>
        </table>
      </div>
      <div
        class="footer-table p-3 text-theme-text lg:flex lg:space-y-0 space-y-10 justify-between"
      >

      </div>
    </div>
  </div>
@endsection