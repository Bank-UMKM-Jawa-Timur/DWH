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
    {{--  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.5.0/semantic.min.css">  --}}
    <style>
      @import url(https://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic&subset=latin);
      i.icon.chevron.circle.down:before {
        content: "\f13a";
      }
      i.icon.chevron.circle.left:before {
        content: "\f137";
      }
      i.icon.chevron.circle.right:before {
        content: "\f138";
      }
      i.icon.chevron.circle.up:before {
        content: "\f139";
      }
      i.icon.chevron.down:before {
        content: "\f078";
      }
      i.icon.chevron.left:before {
        content: "\f053";
      }
      i.icon.chevron.right:before {
        content: "\f054";
      }
      i.icon.chevron.up:before {
        content: "\f077";
      }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.5.0/semantic.min.js"></script>

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
  </script>
  <script src="{{ asset('js/app.js') }}"></script>
  @stack('extraScript')
</html>