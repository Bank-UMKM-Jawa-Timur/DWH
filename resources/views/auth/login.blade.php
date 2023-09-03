<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login - KKB Template</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@200;300;400;500;600;700&display=swap"
        rel="stylesheet">
</head>

<body>
    <div class="lg:flex justify-center w-full font-lexend">
        <div class="h-screen lg:block hidden w-full bg-gray-100 p-5">
            <div class="flex justify-center mt-24">
                <img class="w-[50rem]" src="{{ asset('template') }}/assets/img/news/login-ilustration.svg"
                    alt="ilustration" />
            </div>
        </div>
        <div class="bg-white p-5 shadow lg:max-w-xl max-w-full mx-auto mt-0 h-screen w-full rounded-sm">
            <div class="wrapping-form lg:mt-[14vh] mt-20 lg:p-10 p-3 space-y-5">
                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    <div class="form-head space-y-5">
                        <img src="{{ asset('template') }}/assets/img/news/logo.png" alt="logo bank umkm" />

                        <div class="content space-y-3">
                            <h2 class="lg:text-4xl text-3xl text-theme-text font-bold tracking-tighter">
                                Selamat datang
                            </h2>
                            <p class="lg:text-sm text-xs text-[#bababa]">
                                Silahkan login untuk melanjutkan
                            </p>
                        </div>
                    </div>
                    <div class="form-body mt-7 space-y-5">
                        @include('components.alert')
                        <div class="input-form space-y-3">
                            <label for="niporemail"
                                class="text-[#576B80] lg:text-sm text-xs font-semibold tracking-tighter">NIP atau
                                EMAIL</label>
                            <div class="input-box border border-[#E6E6E6] rounded-md">
                                <input type="text" autofocus name="input_type"
                                    class="p-2 px-4 w-full rounded-md outline-none text-theme-text caret-theme-primary bg-[#F9F9F9]"
                                    id="niporemail" />
                            </div>
                            @if ($errors->get('email'))
                                <span class="text-theme-primary">{{ $errors->get('email')[0] }}</span>
                            @endif
                            @if ($errors->get('nip'))
                                <span class="text-theme-primary">{{ $errors->get('nip')[0] }}</span>
                            @endif
                        </div>
                        <div class="input-form space-y-3">
                            <label for="niporemail"
                                class="text-[#576B80] lg:text-sm text-xs font-semibold tracking-tighter">PASSWORD</label>
                            <div class="input-box border border-[#E6E6E6] rounded-md">
                                <input type="password" name="password"
                                    class="p-2 px-4 text-theme-text w-full caret-theme-primary rounded-md outline-none bg-[#F9F9F9]"
                                    id="niporemail" />
                            </div>
                        </div>
                        <button type="submit"
                            class="bg-theme-primary focus:bg-red-700 py-3 lg:text-lg text-sm w-full text-white font-semibold rounded-md drop-shadow-md">
                            MASUK
                        </button>
                    </div>
                    <div class="copyright mt-8 text-xs text-center text-neutral-400">
                        &copy; Copyright <b>BANK UMKM Jatim</b> 2023
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
