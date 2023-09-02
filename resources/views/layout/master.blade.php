<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0"
    />
    <title>Dashboard - Superadmin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@200;300;400;500;600;700&display=swap" rel="stylesheet">
    <link
      rel="stylesheet"
      href="{{ asset('css/app.css') }}"
    />
    @stack('extraStyle')
  </head>
  <body>
    <div class="layout-wrapper font-lexend">
      <!-- wrapping sidebar and pages -->
      <div class="layout-container">
        <!-- sidebar -->
        @include('components.sidebar')
        <div class="layout-form hidden">
          <div class="head-form p-4 border-b">
            <h2>FILTER DATA KKB</h2>
          </div>
          <form
            action=""
            method=""
          >
            <div class="p-4 space-y-8 mt-8">
              <div class="input-box space-y-3">
                <label
                  for=""
                  class="uppercase appearance-none"
                  >Tanggal Awal</label
                >
                <input
                  type="date"
                  class="p-2 w-full border"
                />
              </div>
              <div class="input-box space-y-3">
                <label
                  for=""
                  class="uppercase"
                  >Tanggal Akhir</label
                >
                <input
                  type="date"
                  class="p-2 w-full border"
                />
              </div>
              <div class="input-box space-y-3">
                <label
                  for=""
                  class="uppercase"
                  >Status</label
                >
                <select
                  name=""
                  class="w-full p-2 border"
                  id=""
                >
                  <option selected>-- Pilih Status ---</option>
                </select>
              </div>
              <div class="input-box space-y-3">
                <label
                  for=""
                  class="uppercase"
                  >Cabang</label
                >
                <select
                  name=""
                  class="w-full p-2 border"
                  id=""
                >
                  <option selected>-- Pilih Cabang ---</option>
                </select>
              </div>
              <button class="bg-theme-primary px-8 rounded text-white py-2">
                Filter
              </button>
              <button
                id="form-close"
                type="button"
                class="bg-white ml-2 px-8 rounded text-theme-text border py-2"
              >
                Batal
              </button>
            </div>
          </form>
        </div>
        <!-- layout overlay -->
        <div class="layout-overlay lg:hidden hidden"></div>
        <div class="layout-overlay-form hidden"></div>
        <!-- pages -->
        <div class="layout-pages box-border">
            <div class="p-5 space-y-10">
                <!-- top navigation -->
                @include('components.top-navigation')
                <!-- Body -->
                @yield('content')
            </div>
        </div>
      </div>
    </div>
  </body>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="{{ asset('js/app.js') }}"></script>
  @stack('extraScript')
</html>