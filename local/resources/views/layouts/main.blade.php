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
  @php
  date_default_timezone_set("Asia/Bangkok");
  @endphp
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="Gradient Able Bootstrap admin template made using Bootstrap 4 and it has huge amount of ready made feature, UI components, pages which completely fulfills any dashboard needs." />
    <meta name="keywords" content="flat ui, admin Admin , Responsive, Landing, Bootstrap, App, Template, Mobile, iOS, Android, apple, creative app">
    <meta name="author" content="codedthemes" />
    <script src="{{ asset('template/bower_components/jquery/js/jquery.min.js')}}"></script>
    {{-- <script src = "https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script> --}}
    {{-- <script src="{{ asset('template/bower_components/jquery/as/jquery.min.js')}}"></script> --}}
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <!-- Favicon icon -->
    <link rel="icon" href="{{ asset('template/assets/images/favicon.ico')}}" type="image/x-icon">
    <!-- Google font-->
    {{-- <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600" rel="stylesheet">
    --}}
    <!-- Required Fremwork -->
    <link rel="stylesheet" type="text/css" href="{{ asset('template/bower_components/bootstrap/css/bootstrap.min.css') }}">
      <!-- jquery file upload Frame work -->
    <link rel="stylesheet" type="text/css" href="{{asset('template/assets/pages/jquery.filer/css/jquery.filer.css')}}" type="text/css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css"href="{{asset('template/assets/pages/jquery.filer/css/themes/jquery.filer-dragdropbox-theme.css')}}" type="text/css" rel="stylesheet" />
     <!-- jquery file upload Frame work -->
     <link href="{{asset('template/assets/pages/jquery.filer/css/jquery.filer.css')}}" type="text/css" rel="stylesheet" />
     <link href="{{asset('template/assets/pages/jquery.filer/css/themes/jquery.filer-dragdropbox-theme.css')}}" type="text/css" rel="stylesheet" />
    <!-- sweet alert framework -->
    <link rel="stylesheet" type="text/css" href="{{ asset('template/bower_components/sweetalert/css/sweetalert.css')}}">
    <!--forms-wizard css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('template/bower_components/jquery.steps/css/jquery.steps.css')}}">
    <!-- themify-icons line icon -->
    <link rel="stylesheet" type="text/css" href="{{ asset('template/assets/icon/themify-icons/themify-icons.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('template/assets/icon/icofont/css/icofont.css')}}">
    <!-- Font Awesome -->
    <link rel="stylesheet" type="text/css" href="{{ asset('template/assets/icon/font-awesome/css/font-awesome.min.css')}}">
    <!-- Data Table Css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('template/bower_components/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('template/assets/pages/data-table/css/buttons.dataTables.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('template/bower_components/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}">
    <!-- Select 2 css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('template/bower_components/select2/css/select2.min.css') }}" />
    <!-- Multi Select css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('template/bower_components/bootstrap-multiselect/css/bootstrap-multiselect.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('template/bower_components/multiselect/css/multi-select.css') }}" />
    <!-- Style.css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('template/assets/css/style.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('template/assets/css/jquery.mCustomScrollbar.css')}}">
    <!-- jpro forms css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('template/assets/pages/j-pro/css/demo.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('template/assets/pages/j-pro/css/font-awesome.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('template/assets/pages/j-pro/css/j-pro-modern.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('template/assets/js/sweeralert.js')}}">
     <!-- morris chart -->
     <link rel="stylesheet" type="text/css" href="{{ asset('template/bower_components/morris.js/css/morris.css')}}">
     <style>
         input::-webkit-input-placeholder {
         color: #555555 !important;
          }
     </style>
     @yield('style')
</head>
<body>
    <!-- Pre-loader start -->
    <div class="theme-loader">
        <div class="loader-track">
            <div class="loader-bar"></div>
        </div>
    </div>
    <!-- Pre-loader end -->
    <div id="pcoded" class="pcoded">
        <div class="pcoded-overlay-box"></div>
        <div class="pcoded-container navbar-wrapper">
            @include('layouts.navbartop')
            <div class="pcoded-main-container">
                <div class="pcoded-wrapper">
                    @include('layouts.navbarleft')
                    <div class="pcoded-content">
                        <div class="pcoded-inner-content">
                            <!-- Main-body start -->
                            <div class="main-body">
                                <div class="page-wrapper">
                                    <!-- Page-header start -->
                                    <div class="page-header card">
                                        <div class="card-block">
                                            <h5 class="m-b-10">@yield('title')</h5>
                                            <ul class="breadcrumb-title b-t-default p-t-10">
                                                <li class="breadcrumb-item">
                                                    <a href="{{ url('/OQA/incoming') }}"> <i class="fa fa-home"></i> </a>
                                                </li>
                                                <li class="breadcrumb-item"><a href="@yield('breadcrumb-title-href')">@yield('breadcrumb-title')</a>
                                                </li>
                                                <li class="breadcrumb-item"><a href="#!">@yield('breadcrumb-sub')</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <!-- Page-header end -->
                                    <div class="page-body">
                                        @yield('content')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Required Jquery -->
    
    <script src="{{ asset('template/bower_components/jquery-ui/js/jquery-ui.min.js')}}"></script>
    <script src="{{ asset('template/bower_components/popper.js/js/popper.min.js')}}"></script>
    <script src="{{ asset('template/bower_components/bootstrap/js/bootstrap.min.js')}}"></script>
    <!-- jquery slimscroll js -->
    <script  src="{{ asset('template/bower_components/jquery-slimscroll/js/jquery.slimscroll.js')}}"></script>
    <!--Forms - Wizard js-->
    <script src="{{ asset('template/bower_components/jquery.cookie/js/jquery.cookie.js')}}"></script>
    <script src="{{ asset('template/bower_components/jquery.steps/js/jquery.steps.js')}}"></script>
    <script src="{{ asset('template/bower_components/jquery-validation/js/jquery.validate.js')}}"></script>
    <!-- modernizr js -->
    <script src="{{ asset('template/bower_components/modernizr/js/modernizr.js')}}"></script>
    <script src="{{ asset('template/bower_components/modernizr/js/css-scrollbars.js')}}"></script>
    <!-- sweet alert js -->
    <script  src="{{ asset('template/bower_components/sweetalert/js/sweetalert.min.js') }}"></script>
    <!-- data-table js -->
    <script src="{{ asset('template/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('template/bower_components/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('template/assets/pages/data-table/js/jszip.min.js') }}"></script>
    <script src="{{ asset('template/assets/pages/data-table/js/pdfmake.min.js') }}"></script>
    <script src="{{ asset('template/assets/pages/data-table/js/vfs_fonts.js') }}"></script>
    <script src="{{ asset('template/bower_components/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('template/bower_components/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('template/bower_components/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('template/bower_components/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('template/bower_components/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>
    {{-- <script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.colVis.min.js"></script> --}}
    <!-- Select 2 js -->
    <script  src="{{ asset('template/bower_components/select2/js/select2.full.min.js')}}"></script>
        <!-- amchart js -->
        <script src="{{ asset('template/assets/pages/widget/amchart/amcharts.js') }}"></script>
        <script src="{{ asset('template/assets/pages/widget/amchart/gauge.js') }}"></script>
        <script src="{{ asset('template/assets/pages/widget/amchart/serial.js') }}"></script>
        <script src="{{ asset('template/assets/pages/widget/amchart/ammap.js') }}"></script>
        <script src="{{ asset('template/assets/pages/widget/amchart/continentsLow.js') }}"></script>
        <script src="{{ asset('template/assets/pages/widget/amchart/light.js') }}"></script>
    <!-- Multiselect js -->
    <script  src="{{ asset('template/bower_components/multiselect/js/jquery.multi-select.js')}}"></script>
    <script  src="{{ asset('template/assets/js/jquery.quicksearch.js')}}"></script>
     <!-- jquery file upload js -->
     <script src="{{ asset('template/assets/pages/jquery.filer/js/jquery.filer.min.js')}}"></script>
     <script src="{{ asset('template/assets/pages/filer/custom-filer.js')}}" ></script>
     <script src="{{ asset('template/assets/pages/filer/jquery.fileuploads.init.js')}}" ></script>
       <!-- Morris Chart js -->
    <script src="{{asset('template/bower_components/raphael/js/raphael.min.js')}}"></script>
    <script src="{{asset('template/bower_components/morris.js/js/morris.js')}}"></script>
    <!-- Custom js -->
    {{-- <script src="{{asset('template/assets/pages/chart/morris/morris-custom-chart.js')}}"></script> --}}
    <script src="{{ asset('template/assets/pages/advance-elements/select2-custom.js')}}"></script>
    <script src="{{ asset('template/assets/js/pcoded.min.js')}}"></script>
    <script src="{{ asset('template/assets/js/vertical/vertical-layout.min.js')}}"></script>
    <script src="{{ asset('template/assets/js/jquery.mCustomScrollbar.concat.min.js')}}"></script>
    <script  src="{{ asset('template/assets/js/script.js')}}"></script>
    <script  src="{{ asset('template/assets/pages/forms-wizard-validation/form-wizard.js')}}"></script>
    <!-- jquery file upload js -->
    <script src="{{ asset('template/assets/pages/jquery.filer/js/jquery.filer.min.js')}}"></script>
    <script src="{{ asset('template/assets/pages/filer/custom-filer.js')}}" ></script>
    <script src="{{ asset('template/assets/pages/filer/jquery.fileuploads.init.js')}}"></script>
  <!-- highcharts js -->
<script src="{{ asset('template/bower_components/highcharts/highcharts.js') }}"></script>
<script src="{{ asset('template/bower_components/highcharts/highcharts-3d.js') }}"></script>
<script src="{{ asset('template/bower_components/highcharts/highcharts-more.js') }}"></script>
<script src="{{ asset('template/bower_components/highcharts/modules/series-label.js') }}"></script>
<script src="{{ asset('template/bower_components/highcharts/modules/sunburst.js') }}"></script>
<script src="{{ asset('template/bower_components/highcharts/modules/cylinder.js') }}"></script>
<script src="{{ asset('template/bower_components/highcharts/modules/networkgraph.js') }}"></script>
<script src="{{ asset('template/bower_components/highcharts/modules/data.js') }}"></script>
<script src="{{ asset('template/bower_components/highcharts/modules/exporting.js') }}"></script>
<script src="{{ asset('template/bower_components/highcharts/modules/export-data.js') }}"></script>
<script src="{{ asset('template/bower_components/highcharts/modules/accessibility.js') }}"></script>
<script src="{{ asset('template/bower_components/highcharts/modules/no-data-to-display.js') }}"></script> 

<!-- Custom js -->  
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        function Getdate(date) {
            var dateobj = new Date(date);
            var date = dateobj.getDate();
            var month = dateobj.getMonth() + 1;
            var year = dateobj.getFullYear();
            var fmdate = date + '-' + month + '-' + year;
            return fmdate;
        }
        function Getdateyear(date) {
            var dateobj = new Date(date);
            var date = dateobj.getDate();
            var month = dateobj.getMonth() + 1;
            var year = dateobj.getFullYear();
            var fmdate = year+ '-' + month + '-' + date;
            return fmdate;
        }
        function Getdatetime(date) {
            var dateobj = new Date(date);
            var date = dateobj.getDate();
            var month = dateobj.getMonth() + 1;
            var year = dateobj.getFullYear();
            var time = dateobj.toLocaleTimeString();
            var hours = Number(time.match(/^(\d+)/)[1]);
            var minutes = Number(time.match(/:(\d+)/)[1]);
            var res = time.split(":");
            var ss = res[2].split(" ");
            var timeck ='';
           // var xx = Number(time.match(/:(\d+)/)[]);
            var timeen = '8:53:53';
            var ckpm = timeen.includes("PM")
            var ckam = timeen.includes("AM")
          // if()
           //var AMPMx = cc.match(/\s(.*)$/)[1];
            //console.log(cc);
            if(ckpm == true ||ckam == true )
            {
            var AMPM = time.match(/\s(.*)$/)[1];
            if (AMPM == "PM" && hours < 12) hours = hours + 12;
            if (AMPM == "AM" && hours == 12) hours = hours - 12;
            }
            var sHours = hours.toString();
            var sMinutes = minutes.toString();
            if (hours < 10) sHours = "0" + sHours;
            if (minutes < 10) sMinutes = "0" + sMinutes;
            var fmdate = date + '-' + month + '-' + year + ' ' + sHours + ":" + sMinutes+":"+ss[0];
            return fmdate;
        }
    </script>


    @yield('scripts')
</body>

</html>
