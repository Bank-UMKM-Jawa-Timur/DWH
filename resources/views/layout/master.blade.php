<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard KKB</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="{{ asset('template/assets/css/new-font.css') }}" rel="stylesheet">
    <link href="{{ asset('template/assets/css/select2.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('template/assets/css/jquery-ui.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}" />
    <link rel="icon" type="image/x-icon" href="{{ asset('template/assets/img/icon_title.ico') }}">
    @stack('extraStyle')
</head>

<body>
    @include('sweetalert::alert')
    @php
        if (\Session::get(config('global.role_id_session')) == 3) {
            $name_vendor = DB::table('users')
                ->where('users.id', Auth::user()->id)
                ->select('vendors.name')
                ->join('vendors', 'vendors.id', '=', 'users.vendor_id')
                ->first();
        }
        $user = \Session::get(config('global.auth_session'));
        $token = \Session::get(config('global.user_token_session'));
        $name = \Session::get(config('global.role_id_session')) == 3 ? Auth::user()->email : $user['data']['nama'];
        $sub_name = \Session::get(config('global.role_id_session')) == 3 ? strval($name_vendor->name) : $user['data']['nip'];
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

            <div class="modal-overlay hidden" id="preload-data">
                <div class="flex justify-center mt-[35vh]">
                    <div class="text-center space-y-5">
                        <img src="{{ asset('template/assets/img/news/loading.svg') }}" class="max-w-[120px] mx-auto"
                            alt="">
                        <p class="text-white">Updating data...</p>
                    </div>
                </div>
            </div>

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
<script src="{{ asset('template/assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('template/assets/js/sweetalert2.js') }}"></script>
<script src="{{ asset('template/assets/js/select2.min.js') }}"></script>
<script src="{{ asset('template/assets/js/apexcharts.js') }}"></script>
<script src="{{ asset('template/assets/js/jquery-ui.js') }}"></script>
<script src="{{ asset('js/app.js') }}"></script>

<script>
    function generateCsrfToken() {
        var token = "{{ csrf_token() }}"
        if (token == '') {
            generateCsrfToken();
        } else {
            return token;
        }
    }

    function monthDiff(d1, d2) {
        var months;
        months = (d2.getFullYear() - d1.getFullYear()) * 12;
        months -= d1.getMonth();
        months += d2.getMonth();
        return months <= 0 ? 0 : months;
    }

    /*window.onbeforeunload = function(e) {
        $.ajax({
            type: "POST",
            url: "{{ route('logout') }}",
            data: {
                _token: "{{ csrf_token() }}",
            },
            success: function(data) {
                console.log(data);
                if (data.status == 'success') {
                    const url = "{{ route('login') }}"
                    window.location.href = url;
                } else {
                    ErrorMessage(data.message)
                }
            },
            error: function(e) {
                console.log(e)
                ErrorMessage('Terjadi kesalahan. Harap muat ulang halaman terlebih dahulu.')
            }
        });
    }*/

    function SuccessMessage(message) {
        Swal.fire({
            title: 'Berhasil',
            icon: 'success',
            text: message,
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
            message: message,
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

    $('.rupiah').keyup(function(event) {
        if (event.which >= 37 && event.which <= 40) {
            event.preventDefault();
        }

        $(this).val(function(index, value) {
            return value
                .replace(/\D/g, "")
                .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ".");
        });
    });

    function formatRupiah(angka) {
        var number_string = angka.replace(/[^,\d]/g, '').toString(),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        // tambahkan titik jika yang di input sudah menjadi angka ribuan
        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return rupiah;
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
                $('#preload-data').removeClass('hidden')
                $.ajax({
                    type: "POST",
                    url: "{{ route('logout') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                    },
                    success: function(data) {
                        if (data.status == 'success') {
                            const url = "{{route('login')}}"
                            window.location.href = url;
                        } else {
                            ErrorMessage(data.message)
                        }
                    },
                    error: function(e) {
                        console.log(e)
                        ErrorMessage(
                            'Terjadi kesalahan. Harap muat ulang halaman terlebih dahulu.'
                            )
                    }
                });
            }
        })
    })
</script>
@stack('extraScript')

</html>
