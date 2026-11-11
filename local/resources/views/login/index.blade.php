<!DOCTYPE html>
<html lang="en">

<head>
    <title>NHK</title>
    <!-- HTML5 Shim and Respond.js IE10 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 10]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
      <![endif]-->
    <!-- Meta -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="Gradient Able Bootstrap admin template made using Bootstrap 4 and it has huge amount of ready made feature, UI components, pages which completely fulfills any dashboard needs." />
    <meta name="keywords" content="bootstrap, bootstrap admin template, admin theme, admin dashboard, dashboard template, admin template, responsive" />
    <meta name="author" content="codedthemes" />
    <!-- Favicon icon -->
 {{-- <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600" rel="stylesheet"> --}}
    <link rel="icon" href="{{ asset('template/assets/images/favicon.ico')}}" type="image/x-icon">
    <!-- Google font-->
    
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
    <!-- Pre-loader end -->
    <section class="login p-fixed d-flex text-center bg-primary common-img-bg">
        <!-- Container-fluid starts -->

        
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <!-- Authentication card start -->
                    <div class="signin-card card-block auth-body mr-auto ml-auto">
                        <form action="chklogin" method="POST" class="md-float-material">
                            @csrf
                            <div class="auth-box">
                                <div class="text-center">
                                    <img width="30%" src="{{ asset('template/assets/images/logonhkh.png')}}" alt="logo.png">
                                </div>
                                <div class="row m-b-20">
                                   
                                </div>
                                <p class="text-inverse b-b-default text-left p-b-5"></p>
                                <br>
                                <h3>LOGIN</h3>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="username" placeholder="Username">
                                    <span class="md-line"></span>
                                </div>
                                <div class="input-group">
                                    <input type="password" class="form-control" name="password" placeholder="password">
                                    <span class="md-line"></span>
                                </div>
                                <div class="row m-t-25 text-left">
                                    <div class="col-12">
                                        <div class="checkbox-fade fade-in-primary">
                                            <label>
                                                <input type="checkbox" value="">
                                                <span class="cr"><i class="cr-icon icofont icofont-ui-check txt-primary"></i></span>
                                                <span class="text-inverse">Remember me</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row m-t-30">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary btn-md btn-block waves-effect text-center m-b-20">LOGIN</button>
                                    </div>
                                </div>

                            </div>
                        </form>
                        <!-- end of form -->
                    </div>
                    <!-- Authentication card end -->
                </div>
                <!-- end of col-sm-12 -->
            </div>
            <!-- end of row -->
        </div>
        <!-- end of container-fluid -->
    </section>
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
