<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0"
    />
    <title>404 Not Found</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}" />
  </head>
  <body class="">
    <div class="flex justify-center font-lexend">
        <div class="mt-[8vh] p-5">
            <img src="{{ asset('template') }}/assets/img/news/404-notfound.svg" alt="not-found-page">
            <div class="content mt-2 text-center space-y-8">
                <h1 class="text-center font-bold lg:text-5xl text-4xl text-[#FF4F5B] tracking-tighter">HALAMAN TIDAK DITEMUKAN</h1>
                <p class="max-w-lg mx-auto text-gray-400">Upps sepertinya anda menemukan halaman yang belum ada atau dipindahkan.</p>
                <div>
                    <a href="{{ url()->previous() }}">
                        <button class="bg-[#ee424d] text-white px-10 rounded-md py-3 shadow-md">Kembali</button>
                    </a>
                </div>
            </div>
        </div>
    </div>
  </body>
  <style>
    .text-shadow{
        text-shadow: 2px 4px #A32B34;
    }
  </style>

</html>
