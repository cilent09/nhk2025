@extends('layouts.userlogin')
@extends('layouts.main')
@section('title', 'Need Support')
@section('breadcrumb-title-href', '#')
@section('breadcrumb-title', 'Need Support')

@section('content')
<div class="card">
    <div class="card-header">
      <br> 
        <br> 
        <center><h3>Browser support</h3></center>
        <br> 
        <br> 
        <br> 

        <div class="row">
          <div class="col-md-12 col-lg-4">
            <div class="card">
              <div class="card-block text-center">
                  <h4><img class="img-fluid" src="{{ asset('template/assets/images/support/2.jpg')}}" width="33%" alt="Theme-Logo" /></h4>
                  <h4 class="m-t-20">Microsoft edge</h4>
                  <p class="m-b-20">Browser support</p>
                  <a href="https://www.microsoft.com/th-th/edge" target="_blank"><button class="btn btn-primary btn-sm btn-round">URL Link</button></a>
              </div>
          </div>
          </div>
          <div class="col-md-6 col-lg-4">
            <div class="card">
              <div class="card-block text-center">
                <h4><img class="img-fluid" src="{{ asset('template/assets/images/support/1.png')}}" width="30%" alt="Theme-Logo" /></h4>
                <h4 class="m-t-20">Google chrome</h4>
                <p class="m-b-20">Browser support</p>
                <a href="https://www.google.com/chrome/?brand=BNSD&gclid=Cj0KCQjw7pKFBhDUARIsAFUoMDZf_wESdPXv5EvH5680D8N6cO31SgGPCYHR8utjuas4BBy5AvbYnrAaAn1kEALw_wcB&gclsrc=aw.ds" target="_blank"><button class="btn btn-primary btn-sm btn-round">URL Link</button></a>
            </div>
          </div>
          </div>
          <div class="col-md-6 col-lg-4">
            <div class="card">
              <div class="card-block text-center">
                <h4><img class="img-fluid" src="{{ asset('template/assets/images/support/3.jpg')}}" width="30%" alt="Theme-Logo" /></h4>
                <h4 class="m-t-20">Firefox </h4>
                <p class="m-b-20">Browser support</p>
                <a href="https://www.mozilla.org/th/firefox/new/" target="_blank"><button class="btn btn-primary btn-sm btn-round">URL Link</button></a>
            </div>
          </div>
          </div>
      </div>
   
        <br> 
        <br> 
     
    </div>  
    
    
</div>

<div class="card">
  <div class="card-header">
    <br> 
      <br> 
      <center><h3>Developer</h3></center>
      <br> 
      <br> 
      <br> 

      <div class="row">
        <div class="col-md-12 col-lg-4">
          <div class="card">
            <div class="card-block text-center">
              <h4><img class="img-fluid" src="{{ asset('template/assets/images/support/4.jpg')}}" width="30%" alt="Theme-Logo" /></h4>
              <h4 class="m-t-20">PHP 7.1.3</h4>
              <p class="m-b-20">Developer</p>
              <a href="https://www.php.net/" target="_blank"><button class="btn btn-danger btn-sm btn-round">URL Link</button></a>
          </div>
        </div>
        </div>
        <div class="col-md-6 col-lg-4">
          <div class="card">
            <div class="card-block text-center">
              <h4><img class="img-fluid" src="{{ asset('template/assets/images/support/5.jpg')}}" width="30%" alt="Theme-Logo" /></h4>
              <h4 class="m-t-20">Laravel framework 5.6  </h4>
              <p class="m-b-20">Developer</p>
              <a href="https://laravel.com/" target="_blank"><button class="btn btn-danger btn-sm btn-round">URL Link</button></a>
          </div>
        </div>
        </div>
        <div class="col-md-6 col-lg-4">
          <div class="card">
            <div class="card-block text-center">
              <h4><img class="img-fluid" src="{{ asset('template/assets/images/support/6.png')}}" width="37%" alt="Theme-Logo" /></h4>
              <h4 class="m-t-20">Bootstrap </h4>
              <p class="m-b-20">Developer</p>
              <a href="https://getbootstrap.com/" target="_blank"><button class="btn btn-danger btn-sm btn-round">URL Link</button></a>
          </div>
        </div>
        </div>
    </div>

      <div class="row">
          <div class="col-md-12 col-lg-4">
              <div class="card">
                <div class="card-block text-center">
                  <h4><img class="img-fluid" src="{{ asset('template/assets/images/support/7.jpg')}}" width="30%" alt="Theme-Logo" /></h4>
                  <h4 class="m-t-20">Highcharts </h4>
                  <p class="m-b-20">Developer</p>
                  <a href="https://www.highcharts.com/" target="_blank"><button class="btn btn-danger btn-sm btn-round">URL Link</button></a>
              </div>
              </div>
          </div>
          <div class="col-md-6 col-lg-4">
            <div class="card">
              <div class="card-block text-center">
                <h4><img class="img-fluid" src="{{ asset('template/assets/images/support/8.png')}}" width="30%" alt="Theme-Logo" /></h4>
                <h4 class="m-t-20">My SQL </h4>
                <p class="m-b-20">Developer</p>
                <a href="https://www.mysql.com/" target="_blank"><button class="btn btn-danger btn-sm btn-round">URL Link</button></a>
            </div>
          </div>
          </div>
          <div class="col-md-6 col-lg-4">
            <div class="card">
              <div class="card-block text-center">
                <h4><img class="img-fluid" src="{{ asset('template/assets/images/support/9.png')}}" width="30%" alt="Theme-Logo" /></h4>
                <h4 class="m-t-20">Xampp mail </h4>
                <p class="m-b-20">Developer</p>
                <a href="https://www.apachefriends.org/index.html" target="_blank"><button class="btn btn-danger btn-sm btn-round">URL Link</button></a>
            </div>
          </div>
          </div>
      </div>

 
      
      <br> 
      <br> 
   
  </div>  
  
  
</div>

@endsection

@section('scripts')
<script>
            function view()
            {
              swal("warning", "อยู่ในระหว่างการดำเนินการ ..!", "warning");
            }  

</script>
@endsection
