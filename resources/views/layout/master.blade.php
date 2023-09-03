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
                    /*console.log(data);
                    if (Array.isArray(data.error)) {
                        showError(req_name, data.error[0])
                    } else {
                        if (data.status == 'success') {
                            alert(data.message);
                            //SuccessMessage(data.message);
                            //Swal.fire('Saved!', '', 'success')
                        } else {
                            alert(data.message)
                            //ErrorMessage(data.message)
                        }
                    }*/
                }
            });
        }
    })
  })
  </script>
  @stack('extraScript')
</html>