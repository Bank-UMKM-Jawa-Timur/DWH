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
            font-family: Helvetica, Verdana, sans-serif !important;
            -webkit-font-smoothing: antialiased;
        }
        .body {
            background-color: #f6f6f6;
            width: 100%;
            height: 100vh;
        }
        .email-wrapper{
            max-width: 600px;
            margin: auto;
            margin-top: 8rem;
            border: 1px solid #dcdcdc;
            background-color: #fff;
        }
        /* head */
        .email-wrapper .email-head{
        }
        .email-wrapper .email-head .title{
            letter-spacing: -1px;
            color: #525252;
            font-weight: 600;
        }
        /* body */
        .email-wrapper .body-email{
            margin-top: 4rem;
            padding-bottom: 2rem;
        }
        .email-wrapper .body-email .nama-debitur{
            letter-spacing: -1px;
            color: gray;
            font-weight: normal;
            font-size: 20px;
        }
        .email-wrapper .body-email .nama-debitur b{
            color: #525252;
        }
        .email-wrapper .body-email .no-po{
            color: #9e9a9a;
        }
        .email-wrapper .body-email .message{
            color: gray;
            font-size: 18px;
        }
        .wrapping{
            padding: 2rem;
        }
        /* footer */
        .footer{
            text-align: center;
            padding: 1.5rem;
            background-color: #DC3545;
        }
        .footer p{
            color: white;
            font-size: 14px;
        }
    </style>
  </head>
  <body>
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
                                            <img src="{{ asset('template/img') }}/news/logo.png" alt="logo">
                                            <h1 class="title">You have new message from dashboard kkb.</h1>
                                        </td>
                                    </tr>
                                </table>
                                {{-- body --}}
                                <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td class="body-email">
                                                <h3 class="nama-debitur">Hi, <b> Mohammad Sahrullah</b></h3>
                                                <p class="no-po">NO PO: 9086</p>
                                                <p class="message">
                                                    Lorem ipsum, dolor sit amet consectetur adipisicing elit. Velit sed nisi reprehenderit optio voluptas quisquam ipsa ipsum odit! Ipsum aliquid hic, facere ea voluptatem optio sint impedit earum animi labore?
                                                </p>
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
                                    <p>Please do not reply to this email. We cannot respond to questions sent to this address. For immediate answers to your questions, call our office 082349898</p>
                                    
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