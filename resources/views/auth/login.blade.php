<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0"
    />
    <title>Login - KKB Template</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@200;300;400;500;600;700&display=swap" rel="stylesheet">
  </head>
  <body>
    <div class="lg:flex justify-center w-full font-lexend">
      <div class="h-screen lg:block hidden w-full bg-gray-100 p-5">
        <div class="flex justify-center mt-24">
          <img
            class="w-[50rem]"
            src="{{ asset('template') }}/assets/img/news/login-ilustration.svg"
            alt="ilustration"
          />
        </div>
      </div>
      <div
        class="bg-white p-5 shadow lg:max-w-xl max-w-full mx-auto mt-0 h-screen w-full rounded-sm"
      >
        <div class="wrapping-form lg:mt-[14vh] mt-20 lg:p-10 p-3 space-y-5">
          <form
            action="{{ route('login') }}"
            method="POST"
          >
             @csrf
            <div class="form-head space-y-5">
              <img
                src="{{ asset('template') }}/assets/img/news/logo.png"
                alt="logo bank umkm"
              />

              <div class="content space-y-3">
                <h2
                  class="lg:text-4xl text-3xl text-theme-text font-bold tracking-tighter"
                >
                  Selamat datang👋
                </h2>
                <p class="lg:text-sm text-xs text-[#bababa]">
                  Silahkan login untuk melanjutkan
                </p>
              </div>
            </div>
            <div class="form-body mt-7 space-y-5">
              <div
                class="alert hidden gap-5 bg-theme-primary/10 rounded-md p-3 px-4 font-semibold text-theme-primary border-theme-primary border"
              >
                <div class="flex">
                  <span>
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      width="20"
                      height="20"
                      viewBox="0 0 256 256"
                    >
                      <g fill="currentColor">
                        <path
                          d="M215.46 216H40.54c-12.62 0-20.54-13.21-14.41-23.91l87.46-151.87c6.3-11 22.52-11 28.82 0l87.46 151.87c6.13 10.7-1.79 23.91-14.41 23.91Z"
                          opacity=".2"
                        />
                        <path
                          d="M236.8 188.09L149.35 36.22a24.76 24.76 0 0 0-42.7 0L19.2 188.09a23.51 23.51 0 0 0 0 23.72A24.35 24.35 0 0 0 40.55 224h174.9a24.35 24.35 0 0 0 21.33-12.19a23.51 23.51 0 0 0 .02-23.72Zm-13.87 15.71a8.5 8.5 0 0 1-7.48 4.2H40.55a8.5 8.5 0 0 1-7.48-4.2a7.59 7.59 0 0 1 0-7.72l87.45-151.87a8.75 8.75 0 0 1 15 0l87.45 151.87a7.59 7.59 0 0 1-.04 7.72ZM120 144v-40a8 8 0 0 1 16 0v40a8 8 0 0 1-16 0Zm20 36a12 12 0 1 1-12-12a12 12 0 0 1 12 12Z"
                        />
                      </g>
                    </svg>
                  </span>
                  <span>
                    <p class="text-sm">
                      <b> Gagal login</b>, Akun sedang di gunakan orang lain
                    </p>
                  </span>
                </div>
              </div>
              <div class="input-form space-y-3">
                <label
                  for="niporemail"
                  class="text-[#576B80] lg:text-sm text-xs font-semibold tracking-tighter"
                  >NIP atau EMAIL</label
                >
                <div class="input-box border border-[#E6E6E6] rounded-md">
                  <input
                    type="text"
                    autofocus 
                    name="input_type"
                    class="p-2 px-4 w-full rounded-md outline-none text-theme-text caret-theme-primary bg-[#F9F9F9]"
                    id="niporemail"
                  />
                </div>
                @if ($errors->get('email'))
                <span class="text-theme-primary">{{ $errors->get('email')[0] }}</span>
            @endif
            @if ($errors->get('nip'))
                <span class="text-theme-primary">{{ $errors->get('nip')[0] }}</span>
            @endif
              </div>
              <div class="input-form space-y-3">
                <label
                  for="niporemail"
                  class="text-[#576B80] lg:text-sm text-xs font-semibold tracking-tighter"
                  >PASSWORD</label
                >
                <div class="input-box border border-[#E6E6E6] rounded-md">
                  <input
                    type="password"
                    name="password"
                    class="p-2 px-4 text-theme-text w-full caret-theme-primary rounded-md outline-none bg-[#F9F9F9]"
                    id="niporemail"
                  />
                </div>
              </div>
              <button
              type="submit"
                class="bg-theme-primary focus:bg-red-700 py-3 lg:text-lg text-sm w-full text-white font-semibold rounded-md drop-shadow-md"
              >
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
