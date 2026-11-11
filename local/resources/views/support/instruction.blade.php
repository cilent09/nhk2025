
@extends('layouts.main')
@section('title', 'Instruction Manual')
@section('breadcrumb-title-href', '#')
@section('breadcrumb-title', 'Instruction Manual')

@section('content')
<div class="card">
    <div class="card-header">
      <br> 
        <br> 
        <center><h3>Instruction Manual</h3></center>
        <br> 
        <br> 
        <br> 

        <div class="row">
          <div class="col-md-12 col-lg-4">
            <div class="card">
              <div class="card-block text-center">
                  <h4><i class="fa fa-database text-c-blue d-block f-40"></i></h4>
                  <h4 class="m-t-20">Model</h4>
                  <p class="m-b-20">Instruction Manual</p>
                  <a href="{{asset('local/storage/NHK-File/Instruction/Instruction_model.pdf')}}" target="_blank"><button class="btn btn-primary btn-sm btn-round">Dowload File</button></a>
              </div>
          </div>
          </div>
          <div class="col-md-6 col-lg-4">
            <div class="card">
              <div class="card-block text-center">
                  <h4><i class="fa fa-desktop text-c-blue d-block f-40"></i></h4>
                  <h4 class="m-t-20">OQA Incoming</h4>
                  <p class="m-b-20">Instruction Manual</p>
                  <a href="{{asset('local/storage/NHK-File/Instruction/Instruction_OQA.pdf')}}" target="_blank"><button class="btn btn-primary btn-sm btn-round">Dowload File</button></a>
              </div>
          </div>
          </div>
          <div class="col-md-6 col-lg-4">
            <div class="card">
              <div class="card-block text-center">
                  <h4><i class="fa fa-flask text-c-blue d-block f-40"></i></h4>
                  <h4 class="m-t-20">Results</h4>
                  <p class="m-b-20">Instruction Manual</p>
                  <a href="{{asset('local/storage/NHK-File/Instruction/Instruction_Results.pdf')}}" target="_blank"><button  class="btn btn-primary btn-sm btn-round">Dowload File</button></a>
              </div>
          </div>
          </div>
      </div>

        <div class="row">
            <div class="col-md-12 col-lg-4">
                <div class="card">
                    <div class="card-block text-center">
                        <h4><i class="fa fa-file-text-o text-c-blue d-block f-40"></i></h4>
                        <h4 class="m-t-20">Report Raw</h4>
                        <p class="m-b-20">Instruction Manual</p>
                        <a href="{{asset('local/storage/NHK-File/Instruction/Instruction_ReportRaw.pdf')}}" target="_blank"><button  class="btn btn-primary btn-sm btn-round">Dowload File</button></a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
              <div class="card">
                <div class="card-block text-center">
                    <h4><i class="fa fa-file-text-o text-c-blue d-block f-40"></i></h4>
                    <h4 class="m-t-20">Report Customer</h4>
                    <p class="m-b-20">Instruction Manual</p>
                    <a href="{{asset('local/storage/NHK-File/Instruction/Instruction_ReportCustomer.pdf')}}" target="_blank"><button class="btn btn-primary btn-sm btn-round">Dowload File</button></a>
                </div>
            </div>
            </div>
            <div class="col-md-6 col-lg-4">
              <div class="card">
                <div class="card-block text-center">
                    <h4><i class="fa fa-calculator text-c-blue d-block f-40"></i></h4>
                    <h4 class="m-t-20">Calculation</h4>
                    <p class="m-b-20">Instruction Manual</p>
                    <a href="{{asset('local/storage/NHK-File/Instruction/Instruction_Calculation.pdf')}}" target="_blank"><button class="btn btn-primary btn-sm btn-round">Dowload File</button></a>
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
