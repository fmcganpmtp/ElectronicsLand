<!DOCTYPE html>

<html lang="en">
  <head>
    <base href="./">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta name="author" content="Łukasz Holeczek">
    <title>FMCG LAND</title>
    <link rel="apple-touch-icon" sizes="57x57" href="{{ url('/admin1/assets/favicon/apple-icon-57x57.png') }}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ url('/admin1/assets/favicon/apple-icon-60x60.png') }}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ url('/admin1/assets/favicon/apple-icon-72x72.png') }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ url('/admin1/assets/favicon/apple-icon-76x76.png') }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ url('/admin1/assets/favicon/apple-icon-114x114.png') }}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ url('/admin1/assets/favicon/apple-icon-120x120.png') }}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ url('/admin1/assets/favicon/apple-icon-144x144.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ url('/admin1/assets/favicon/apple-icon-152x152.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ url('/admin1/assets/favicon/apple-icon-180x180.png') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ url('/admin1/assets/favicon/android-icon-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ url('/admin1/assets/favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ url('/admin1/assets/favicon/favicon-96x96.png') }}">
    
    <link rel="icon" type="image/png" sizes="16x16" href="{{ url('/admin1/assets/favicon/favicon.png') }}">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ url('/admin1/assets/favicon/ms-icon-144x144.png') }}">
    <meta name="theme-color" content="#ffffff">
    <!-- Vendors styles-->
    <link rel="stylesheet" href="{{ url('/admin1/vendors/simplebar/admin1/css/simplebar.css') }}">
    <link rel="stylesheet" href="{{ url('/admin1/css/admin1/vendors/simplebar.css') }}">
    <!-- Main styles for this application-->
    <link href="{{ url('/admin1/css/style.css') }}" rel="stylesheet">
    <!-- We use those styles to show code examples, you should remove them in your application.-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/prismjs@1.23.0/themes/prism.css">
    <link href="admin/css/examples.css" rel="stylesheet">
    <!-- Global site tag (gtag.js) - Google Analytics-->
    <script async="" src="https://www.googletagmanager.com/gtag/js?id=UA-118965717-3"></script>
    <script>
      window.dataLayer = window.dataLayer || [];

      function gtag() {
        dataLayer.push(arguments);
      }
      gtag('js', new Date());
      // Shared ID
      gtag('config', 'UA-118965717-3');
      // Bootstrap ID
      gtag('config', 'UA-118965717-5');
    </script>
  </head>
  <body>
    <div class="bg-light min-vh-100 d-flex flex-row align-items-center login-scr">
    
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="login-Bx">
            
                <div class="logo"><img src="{{URL::asset('/admin1/assets/img/logo.png')}}"></div>
                
                <h1>Welcome Back!</h1>
                
 <div class="card-body">
                    @isset($url)
                    <form method="POST" action='{{ url("login/$url") }}' aria-label="{{ __('Login') }}">
                    @else
                    <form method="POST" action="{{ route('login') }}" aria-label="{{ __('Login') }}">
                    @endisset
                        @csrf

                        
                <div class="round-bx">
                    <label>USER NAME</label>
                    <input placeholder="Enter your email or user name" id="email" type="email" class="{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required {{ ($errors->has('email')|| old('email')=='' ) ? '' : '' }}>
                    @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert" style="text-align: center;font-size: 9px;">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                </div>



                <div class="round-bx">
                    <label>password</label>
                    <input  placeholder="Enter your password" id="password" type="password" class="{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required  {{ $errors->has('password') ? '' : '' }}>
                    @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert" style="text-align: center;font-size: 9px;" >
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                  </div>

                        <div class="form-group">
                            <div class=" ">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> <span class="t_white" style="color:#FFF !important">{{ __('Remember Me') }}</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-0">
                            <div class="">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Login') }}
                                </button>

                                <!--<a class="btn btn-link" href="{{ route('password.request') }}">
                                    {{ __('Forgot Your Password?') }}
                                </a>-->
                            </div>
                        </div>
                    </form>
                </div>

                
                
                <div class="log-copy-right"><p>Copyright © 2023 Fmcg land, All rights reserved. </p></div>
                
                </div>
                
            </div>
        </div>
    </div>
    
    
    </div>
    <!-- CoreUI and necessary plugins-->
    <script src="{{ url('/admin1/vendors/@coreui/coreui/js/coreui.bundle.min.js') }}"></script>
    <script src="{{ url('/admin1/vendors/simplebar/js/simplebar.min.js') }}"></script>
    <script>
    </script>

  </body>
</html>