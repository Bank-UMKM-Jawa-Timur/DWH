@extends('layout.master')
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
        <div class="title-table lg:p-3 p-2 text-center">
          <h2
            class="font-bold text-lg text-theme-text tracking-tighter"
          >
           Data Form Asuransi
          </h2>
        </div>
      </div>
      <div
        class="lg:flex lg:space-y-0 space-y-5 lg:text-left text-center justify-between mt-2 p-2"
      >
        <div class="sorty pl-1 w-full">
          <label
            for=""
            class="mr-3 text-sm text-neutral-400"
            >show</label
          >
          <select
            name=""
            class="border px-4 py-1.5 cursor-pointer rounded appearance-none text-center"
            id=""
          >
            <option value="">5</option>
            <option value="">10</option>
            <option value="">15</option>
            <option value="">20</option>
          </select>
          <label
            for=""
            class="ml-3 text-sm text-neutral-400"
            >entries</label
          >
        </div>
        <div class="search-table lg:w-96 w-full">
          <div
            class="input-search text-[#BFBFBF] rounded-md border flex gap-2"
          >
            <span class="mt-2 ml-3">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="22"
                height="22"
                viewBox="0 0 24 24"
              >
                <path
                  fill="currentColor"
                  d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0 0 16 9.5A6.5 6.5 0 1 0 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5S14 7.01 14 9.5S11.99 14 9.5 14z"
                />
              </svg>
            </span>
            <input
              type="search"
              placeholder="Search"
              class="p-2 rounded-md w-full outline-none text-[#BFBFBF]"
              autocomplete="off"
            />
          </div>
        </div>
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
              <td>No Rekening</td>
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

          </tbody>
        </table>
      </div>
      <div
        class="footer-table p-3 text-theme-text lg:flex lg:space-y-0 space-y-10 justify-between"
      >
        <div>
          <p class="mt-3 text-sm">Menampilkan 1 - 5 dari 100 Data</p>
        </div>
        <div>
          <div class="pagination">
            <button class="btn-pagination">Previous</button>
            <button class="btn-pagination is-active">1</button>
            <button class="btn-pagination">2</button>
            <button class="btn-pagination">3</button>
            <button class="btn-pagination">4</button>
            <button class="btn-pagination">5</button>
            <button class="btn-pagination">...</button>
            <button class="btn-pagination">100</button>
            <button class="btn-pagination">Next</button>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection