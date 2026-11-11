<!DOCTYPE html>
<html lang="en">

<head>
    <title>NHK</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="Gradient Able Bootstrap admin template made using Bootstrap 4 and it has huge amount of ready made feature, UI components, pages which completely fulfills any dashboard needs." />
    <meta name="keywords" content="bootstrap, bootstrap admin template, admin theme, admin dashboard, dashboard template, admin template, responsive" />
    <meta name="author" content="codedthemes" />
    <!-- Favicon icon -->

    <link rel="icon" href="{{ asset('template/assets/images/favicon.ico')}}" type="image/x-icon">
    <!-- Google font-->.
    <!-- Required Fremwork -->
    <link rel="stylesheet" type="text/css" href="{{ asset('template/bower_components/bootstrap/css/bootstrap.min.css')}}">
    <!-- themify-icons line icon -->
    <link rel="stylesheet" type="text/css" href="{{ asset('template/assets/icon/themify-icons/themify-icons.css')}}">
    <!-- ico font -->
    <link rel="stylesheet" type="text/css" href="{{ asset('template/assets/icon/icofont/css/icofont.css')}}">
    <!-- Font Awesome -->
    <link rel="stylesheet" type="text/css" href="{{ asset('template/assets/icon/font-awesome/css/font-awesome.min.css')}}">
    <!-- Style.css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('template/assets/css/style.css')}}">
</head>

<body class="fix-menu">
    <!-- Pre-loader start -->
    <div class="theme-loader">
        <div class="loader-track">
            <div class="loader-bar"></div>
        </div>
    </div>
    
<div  class="container">
    <div class="row justify-content-center">
        {{-- <div class="col-md-8">
            <div class="card">
                <br>
                <div class="card-header" style="text-align: center">LOGIN</div>
                <div class="card-body">
                    <p style="text-align:left"><img class="img-fluid" width="78%"
                        src="{{ asset('local/img_g/nhkimg.jpg')}}" alt="Theme-Logo" /></p>
                </div>
            </div>
        </div> --}}
        <div class="col-md-8">
            <div class="card">
                <div class="text-center">
                    <img width="100%" src="{{ asset('local/img_g/bnnhk.png')}}">
                </div>
                <br>
                <h2><div  style="text-align: center">CAS PROGRAM</div></h2>
                <h2><div style="text-align: center">(<span style="color: crimson">C</span>leanliness <span style="color: crimson">A</span>nalysis <span style="color: crimson">S</span>ystem)</div></h2>
                <br>
                <h5><div style="text-align: center">LOGIN</div></h5>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}" aria-label="{{ __('Login') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="email" class="col-sm-4 col-form-label text-md-right">{{ __('E-Mail') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <br>
                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Login') }}
                                </button>

                            </div>
                        </div>
                        <br>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
    <script  src="{{ asset('template/bower_components/jquery/js/jquery.min.js')}}"></script>
    <script  src="{{ asset('template/bower_components/jquery-ui/js/jquery-ui.min.js')}}"></script>
    <script  src="{{ asset('template/bower_components/popper.js/js/popper.min.js')}}"></script>
    <script  src="{{ asset('template/bower_components/bootstrap/js/bootstrap.min.js')}}"></script>
    <!-- jquery slimscroll js -->
    <script  src="{{ asset('template/bower_components/jquery-slimscroll/js/jquery.slimscroll.js')}}"></script>
    <!-- modernizr js -->
    <script  src="{{ asset('template/bower_components/modernizr/js/modernizr.js')}}"></script>
    <script  src="{{ asset('template/bower_components/modernizr/js/css-scrollbars.js')}}"></script>
    <script  src="{{ asset('template/assets/js/common-pages.js')}}"></script>
</body>

</html>
