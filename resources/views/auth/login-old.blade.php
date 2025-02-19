<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Dashboard KKB | Login</title>
    <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
    <link rel="icon" href="{{ asset('template') }}/assets/img/icon_title.png" type="image/x-icon" />

    <!-- Fonts and icons -->
    <script src="{{ asset('template') }}/assets/js/plugin/webfont/webfont.min.js"></script>
    <script>
        WebFont.load({
            google: {
                "families": ["Lato:300,400,700,900"]
            },
            custom: {
                "families": ["Flaticon", "Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands",
                    "simple-line-icons"
                ],
                urls: ['{{ asset('template') }}/assets/css/fonts.min.css']
            },
            active: function() {
                sessionStorage.fonts = true;
            }
        });
    </script>

    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('template') }}/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('template') }}/assets/css/atlantis.css">
</head>

<body class="login">
    <div class="wrapper wrapper-login">
        <div class="container container-login animated fadeIn">
            <img src="{{ asset('template') }}/assets/img/logo.png" alt="navbar brand" class="login-logo">
            <form class="login-form" action="{{ route('login') }}" method="POST">
                @csrf
                <div class="form-group form-floating-label">
                    <input id="input_type" autofocus name="input_type" type="text"
                        class="form-control input-border-bottom" required>
                    <label for="input_type" class="placeholder">NIP / Email</label>
                    @if ($errors->get('email'))
                        <span class="text-danger">{{ $errors->get('email')[0] }}</span>
                    @endif
                    @if ($errors->get('nip'))
                        <span class="text-danger">{{ $errors->get('nip')[0] }}</span>
                    @endif
                </div>
                <div class="form-group form-floating-label">
                    <input id="password" name="password" type="password" class="form-control input-border-bottom"
                        required>
                    <label for="password" class="placeholder">Password</label>
                    <div class="show-password">
                        <i class="icon-eye"></i>
                    </div>
                    @if ($errors->get('password'))
                        <span class="text-danger">{{ $errors->get('password')[0] }}</span>
                    @endif
                </div>
                {{-- <div class="row form-sub m-0">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="rememberme">
                        <label class="custom-control-label" for="rememberme">Remember Me</label>
                    </div>

                    <a href="#" class="link float-right">Forget Password ?</a>
                </div> --}}
                <div class="form-group form-floating-label">
                    <button type="submit" class="btn btn-danger btn-login">Login</button>
                </div>
                {{-- <div class="login-account">
                    <span class="msg">Don't have an account yet ?</span>
                    <a href="#" id="show-signup" class="link">Sign Up</a>
                </div> --}}
            </form>
        </div>

        <div class="container container-signup animated fadeIn">
            <h3 class="text-center">Sign Up</h3>
            <form class="login-form">
                <div class="form-group form-floating-label">
                    <input id="fullname" name="fullname" type="text" class="form-control input-border-bottom"
                        required>
                    <label for="fullname" class="placeholder">Fullname</label>
                </div>
                <div class="form-group form-floating-label">
                    <input id="email" name="email" type="email" class="form-control input-border-bottom"
                        required>
                    <label for="email" class="placeholder">Email</label>
                </div>
                <div class="form-group form-floating-label">
                    <input id="passwordsignin" name="passwordsignin" type="password"
                        class="form-control input-border-bottom" required>
                    <label for="passwordsignin" class="placeholder">Password</label>
                    <div class="show-password">
                        <i class="icon-eye"></i>
                    </div>
                </div>
                <div class="form-group form-floating-label">
                    <input id="confirmpassword" name="confirmpassword" type="password"
                        class="form-control input-border-bottom" required>
                    <label for="confirmpassword" class="placeholder">Confirm Password</label>
                    <div class="show-password">
                        <i class="icon-eye"></i>
                    </div>
                </div>
                <div class="row form-sub m-0">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" name="agree" id="agree">
                        <label class="custom-control-label" for="agree">I Agree the terms and conditions.</label>
                    </div>
                </div>
                <div class="form-action">
                    <a href="#" id="show-signin" class="btn btn-danger btn-link btn-login mr-3">Cancel</a>
                    <a href="#" class="btn btn-primary btn-rounded btn-login">Sign Up</a>
                </div>
            </form>
        </div>
    </div>
    <script src="{{ asset('template') }}/assets/js/core/jquery.3.2.1.min.js"></script>
    <script src="{{ asset('template') }}/assets/js/plugin/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
    <script src="{{ asset('template') }}/assets/js/core/popper.min.js"></script>
    <script src="{{ asset('template') }}/assets/js/core/bootstrap.min.js"></script>
    <script src="{{ asset('template') }}/assets/js/atlantis.min.js"></script>
</body>

</html>
