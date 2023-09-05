<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0"
    />
    <title>Dashboard KKB</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@200;300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
    <link
      rel="stylesheet"
      href="{{ asset('css/app.css') }}"
    />
    <link rel="icon" type="image/x-icon" href="{{asset('template/assets/img/icon_title.ico')}}">
    @stack('extraStyle')
  </head>
  <body>
    @php
      $user = \Session::get(config('global.auth_session'));
      $token = \Session::get(config('global.user_token_session'));
      $name = \Session::get(config('global.role_id_session')) == 3 ? Auth::user()->email : $user['data']['nama'];
      $sub_name = \Session::get(config('global.role_id_session')) == 3 ? '' : $user['data']['nip'];
      $display_role = \Session::get(config('global.role_id_session')) == 3 ? 'Vendor' : $user['role'];
    @endphp
    <div class="layout-wrapper font-lexend">
      <!-- wrapping sidebar and pages -->
      <div class="layout-container">
        <!-- sidebar -->
        @include('components.sidebar')
        <!-- modal -->
        @yield('modal')

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
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
  <script src="https://code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
  <script src="{{ asset('js/app.js') }}"></script>

  <script>
  function SuccessMessage(message) {
    Swal.fire({
      title: 'Berhasil',
      icon: 'success',
      timer: 3000,
      closeOnClickOutside: false
    }).then(() => {
        location.reload();
    });
    setTimeout(function() {
        location.reload();
    }, 3000);
  }

  function ErrorMessage(message) {
    Swal.fire({
      title: 'Gagal',
      icon: 'error',
      timer: 3000,
      closeOnClickOutside: false
    }).then(() => {
        location.reload();
    });
    setTimeout(function() {
        location.reload();
    }, 3000);
  }

  function formatMoney(amount, decimalCount = 2, decimal = ".", thousands = ",") {
      try {
      decimalCount = Math.abs(decimalCount);
      decimalCount = isNaN(decimalCount) ? 2 : decimalCount;

      const negativeSign = amount < 0 ? "-" : "";

      let i = parseInt(amount = Math.abs(Number(amount) || 0).toFixed(decimalCount)).toString();
      let j = (i.length > 3) ? i.length % 3 : 0;

      return negativeSign +
          (j ? i.substr(0, j) + thousands : '') +
          i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands) +
          (decimalCount ? decimal + Math.abs(amount - i).toFixed(decimalCount).slice(2) : "");
      } catch (e) {
          console.log(e)
      }
  }

  $("#btn-logout").on('click', function() {
    Swal.fire({
        title: 'Konfirmasi',
        html: 'Anda yakin akan mengakhiri sesi ini?',
        icon: 'question',
        iconColor: '#DC3545',
        showCancelButton: true,
        confirmButtonText: 'Ya',
        cancelButtonText: `Batal`,
        confirmButtonColor: '#DC3545'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: "POST",
                url: "{{ route('logout') }}/",
                data: {
                    _token: "{{ csrf_token() }}",
                },
                success: function(data) {
                    console.log(data);
                    if (data.status == 'success') {
                      const url = "{{route('login')}}"
                      window.location.href = url;
                    } else {
                      ErrorMessage(data.message)
                  }
                }
            });
        }
    })
  })
  </script>
  @stack('extraScript')
</html>