@extends('layout.master')
@push('extraStyle')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.7.7/xlsx.core.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xls/0.7.4-a/xls.core.min.js"></script>
@endpush
@section('content')
    <div class="head-pages">
        <p class="text-sm">Laporan</p>
        <h2 class="text-2xl font-bold text-theme-primary tracking-tighter">
            Registrasi Asuransi
        </h2>
    </div>
    <div class="body-pages">
        <form id="form-report" action="" method="get" class="mt-4 w-full">
            <div class="bg-white border rounded-md w-full p-2">
                <div class="table-accessiblity lg:flex text-center lg:space-y-0 justify-between">
                    <div class="title-table lg:p-3 p-2 text-left">
                        <h2 class="font-bold text-lg text-theme-text tracking-tighter">
                            Form Laporan Registrasi Asuransi
                        </h2>
                        @if (\Request::get('tAwal') && \Request::get('tAkhir'))
                            <p class="text-gray-600 text-sm">Menampilkan data mulai tanggal <b>{{date('d-m-Y', strtotime(\Request::get('tAwal')))}}</b> s/d <b>{{date('d-m-Y', strtotime(\Request::get('tAkhir')))}}</b> dengan status <b>{{Request()->status == 'canceled' ? 'dibatalkan' : 'onprogres'}}</b>.</p>
                        @endif
                    </div>
                </div>
                <div class="px-3 mb-5 lg:flex grid grid-cols-1 gap-5">
                    <div class="p-5 w-full">
                        <div class="lg:grid-cols-2 w-full md:grid-cols-2 grid-cols-1 grid gap-5 justify-center">
                            <div class="input-box-calendar space-y-3 col-span-1">
                                <label for="" class="uppercase">Dari<span class="text-theme-primary">*</span></label>
                                <input type="text" class="datepicker p-2 w-full border" id="dari"
                                    name="dari" value="@if(\Request::has('dari')) {{\Request::get('dari')}} @endif" required/>
                                <small class="form-text text-red-600 error dari-error"></small>
                            </div>
                            <div class="input-box-calendar space-y-3 col-span-1">
                                <label for="" class="uppercase">Sampai<span class="text-theme-primary">*</span></label>
                                <input type="text" class="datepicker p-2 w-full border" id="sampai"
                                    name="sampai" value="{{old('sampai')}}" required/>
                                <small class="form-text text-red-600 error sampai-error"></small>
                            </div>
                            <div class="input-box space-y-3 col-span-1">
                                <label for="" class="uppercase">Cabang</label>
                                <select name="cabang" id="cabang" class="w-full p-2 border" required>
                                    <option value="all" selected>-- Semua cabang ---</option>
                                    @foreach ($cabang as $item)
                                        <option value="{{$item['kode_cabang']}}" @if(\Request::has('cabang')){{$item['kode_cabang'] == \Request::get('cabang') ? 'selected' : ''}}@endif>{{$item['kode_cabang']}} - {{$item['cabang']}}</option>
                                    @endforeach
                                </select>
                                <small class="form-text text-red-600 error"></small>
                            </div>
                            <div class="input-box space-y-3 col-span-1">
                                <label for="" class="uppercase">NIP</label>
                                <select name="nip" id="nip" class="w-full p-2 border" required>
                                    <option value="all" selected>-- Semua nip ---</option>
                                    @if (\Request::has('cabang'))
                                        @if (\Request::get('cabang') != 'all')
                                            @foreach ($staf as $item)
                                                @if (is_array($item))
                                                    @if (array_key_exists('id', $item))
                                                        <option value="{{$item['id']}}" @if(\Request::has('nip')){{$item['id'] == \Request::get('nip') ? 'selected' : ''}}@endif>{{$item['nip']}} - {{$item['detail']['nama']}}</option>
                                                    @endif
                                                @endif
                                            @endforeach
                                        @endif
                                    @endif
                                </select>
                                <small class="form-text text-red-600 error"></small>
                            </div>
                        </div>
                        <div class="flex gap-5 justify-end mt-4">
                            <button class="px-6 py-2 bg-theme-primary flex gap-3 rounded text-white" type="button"
                                id="btn-show">
                                <span class=""> Tampilkan </span>
                            </button>
                            @if (\Request::has('dari') && \Request::has('sampai'))
                                <a href="{{route('asuransi.report.registrasi.registrasi')}}" id="btn-reset"
                                    class="px-6 py-2 bg-theme-primary/10 flex gap-3 rounded text-theme-primary">
                                    <span class=""> Reset </span>
                                </a>
                            @endif
                        </div>
                    </div>
                    <div class="p-2 space-y-2 mt-5 w-full">
                        <div class="registrasi-chart"></div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
@push('extraScript')
    {{--  <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>  --}}
    <script>
        $('#page_length').on('change', function() {
            $('#form-report').submit()
        })

        $('.datepicker').val('dd-mm-yyyy');
        $('#cabang').select2()
        $('#nip').select2()

        $('#cabang').on('change', function() {
            $('#preload-data').removeClass('hidden')
            const kode_cabang = $(this).val()
            if (kode_cabang != '') {
                $.ajax({
                    url: `{{url('/get-staf-by-cabang')}}/${kode_cabang}`,
                    method: "GET",
                    success: function(response) {
                        if (Array.isArray(response)) {
                            $('#nip').empty()
                            $('#nip').append(`<option value="all" selected>-- Semua nip ---</option>`)
                            for (var i=0; i < response.length; i++) {
                                $('#nip').append(`<option value="${response[i]['id']}">${response[i]['nip']} - ${response[i]['detail']['nama']}</option>`)
                            }
                            $('#preload-data').addClass('hidden')
                        }
                    },
                    error: function(e) {
                        $('#preload-data').addClass('hidden')
                        console.log(e)
                    }
                })
            }
        })

        $('#btn-show').on('click', function(e) {
            e.preventDefault()
            $('#preload-data').removeClass('hidden')

            var dari = $('#dari').val()
            var sampai = $('#sampai').val()

            if (dari != 'dd-mm-yyyy' && sampai != 'dd-mm-yyyy') {
                $('#form-report').submit()
            }
            else {
                $('#preload-data').addClass('hidden')

                var msg = 'Harap pilih tanggal terlebih dahulu.'
                $('.dari-error').html(msg)
                $('.sampai-error').html(msg)
                $('#dari').addClass('border-red-500')
                $('#sampai').addClass('border-red-500')
            }
        })

        $('#btn-reset').on('click', function(e) {
            $('#preload-data').removeClass('hidden')
        })

        $('#q').keypress(function (e) {
            if (e.which == 13) {
                $('#form-report').submit();
              return false;    //<---- Add this line
            }
        });
    </script>
    @if(\Request::has('dari'))
        <script>
            $('#dari').val("{{\Request::get('dari')}}")
        </script>
    @endif
    @if(\Request::has('sampai'))
        <script>
            $('#sampai').val("{{\Request::get('sampai')}}")
        </script>
    @endif

    @if (\Request::has('dari') && \Request::has('sampai'))
        <script>
            // chart donut
            var registered = parseInt("{{$registered}}")
            var not_register = parseInt("{{$not_register}}")
            var belum_registrasi = parseInt("{{$belum_registrasi}}")
            var total = registered + not_register + belum_registrasi
            var optionsRegistrasi = {
                labels: ['Registrasi', 'Tidak Registrasi', 'Belum Registrasi'],
                series: [registered, not_register, belum_registrasi],
                chart: {
                    type: 'donut',
                    width: '100%',
                    height: 480,
                },
                legend: {
                    position: 'bottom'
                },
                dataLabels: {
                    enabled: true,
                    formatter: function (val, opts) {
                        return opts.w.config.series[opts.seriesIndex]
                    },
                },

                plotOptions: {
                    pie: {
                        donut: {
                            labels: {
                                show: true,
                                name: {
                                    show: true,
                                    fontSize: '22px',
                                    fontFamily: 'Rubik',
                                    color: '#dfsda',
                                    offsetY: -10
                                },
                                value: {
                                    show: true,
                                    fontSize: '16px',
                                    fontFamily: 'Helvetica, Arial, sans-serif',
                                    color: undefined,
                                    offsetY: 16,
                                    formatter: function (val) {
                                        return val
                                    }
                                },
                                total: {
                                    show: true,
                                    label: 'Total',
                                    color: '#373d3f',
                                    formatter: function (w) {
                                        return total
                                    }
                                }
                            }
                        }
                    }
                },

                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                            width: 340,
                        },
                    }
                }]
            };

            var registrasiChart = new ApexCharts(document.querySelector(".registrasi-chart"), optionsRegistrasi);
            registrasiChart.render();
        </script>
    @endif
@endpush
