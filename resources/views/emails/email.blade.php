<!doctype html>
<html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        .container {
            display: block;
            margin: 0 auto !important;
            /* makes it centered */
            max-width: 580px;
            padding: 10px;
            width: 580px; 
        }
        body{
            background-color: #F2F2F2;
            font-family:  Calibri, Tahoma, sans-serif !important;
            -webkit-font-smoothing: antialiased;
        }
        .body {
            background-color: #f6f6f6;
            width: 100%;
            height: 100vh;
        }
        .email-wrapper{
            max-width: 600px;
            width: 100%;
            margin: auto;
            margin-top: 8rem;
            /* border: 1px solid #dcdcdc; */
            background-color: #fff;
        }
        /* head */
        .email-wrapper .email-head{
        }
        .email-wrapper .email-head .title{
            letter-spacing: -1px;
            color: #525252;
            font-weight: 700;
        }
        /* body */
        .email-wrapper .body-email{
            margin-top: 4rem;
            padding-bottom: 2rem;
        }
        .email-wrapper .body-email .gretting{
            color: gray;
            font-weight: 400;
            font-size: 20px;
        }
        .email-wrapper .body-email .nama-debitur{
            color: gray;
            font-weight: 400;
            font-size: 18px;
        }
        .email-wrapper .body-email .nama-debitur b{
            color: #525252;
        }
        .email-wrapper .body-email .no-po{
            font-weight: 400;
            color: gray;
        }
        .email-wrapper .body-email .message{
            color: gray;
            margin-top: 2rem;
            font-size: 1rem;
        }
        .wrapping{
            padding: 2rem;
        }
        /* footer */
        .footer{
            text-align: center;
            padding: 1.2rem;
            background-color: #DC3545;
        }
        .footer p{
            color: white;
            font-family: Calibri, Tahoma, sans-serif !important;
            font-size: 14px;
        }
    </style>
  </head>
  <body>
    @php
        $hour = date( "G" );
        $year = date( "Y" );
        $greeting = '';

        if ( $hour >= 5 && $hour < 12 ) {
            $greeting = "Selamat pagi";
        } elseif ( $hour >= 12 && $hour < 15 ) {
            $greeting = "Selamat siang";
        } elseif ( $hour >= 15 && $hour < 18 ) {
            $greeting = "Selamat sore";
        } else {
            $greeting = "Selamat malam";
        }
    @endphp
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" class="body">
        <tr>
            <td>&nbsp;</td>
            <td class="container">
                <div class="content">
                    {{-- wrapping --}}
                    <table role="presentation" class="email-wrapper">
                        <tr>
                            <td class="wrapping">
                                <table>
                                    <tr>
                                        <td class="email-head">
                                            <img src="{{ asset('template/assets') }}/img/news/logo.png" alt="logonya masih belum ada coy">
                                            <h1 class="title">{{ $title }}</h1>
                                        </td>
                                    </tr>
                                </table>
                                {{-- body --}}
                                <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td class="body-email">
                                            <h3 class="gretting">{{$greeting}}, {{$to}}</h3>
                                            {{-- <table>
                                                <tr>
                                                    <td><p class="nama-debitur">Nama Debitur </p></td>
                                                    <td><p class="no-po">: {{$nama_debitur}}</p></td>
                                                </tr>
                                                <tr>
                                                    <td><p class="no-po">NO PO</p></td>
                                                    <td><p class="no-po">: {{$no_po}}</p></td>
                                                </tr>
                                            </table> --}}
                                                <p class="no-po"><strong>Nama Debitur:</strong> {{$nama_debitur}}</p>
                                                <p class="no-po"><strong>NO PO:</strong> {{$no_po}}</p><br>
                                                <p class="message">{{$body}}</p>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                    {{-- footer --}}
                    <div class="footer">
                        <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                            <tr>
                                <td class="content-block">
                                    <p>Tolong jangan balas ke email ini. Kami tidak dapat menanggapi pertanyaan yang dikirimkan ke alamat ini. Untuk jawaban segera atas pertanyaan Anda, hubungi kantor kami 082349898</p>
                                </td>
                            </tr>
                            <tr>
                                <td class="content-block powered-by">
                                    <p class="copyright">Copyright &copy; <b>BANKUMKM</b> 2023 - current</p>.
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </td>
            <td>&nbsp;</td>
        </tr>
    </table>
</body>

</html>