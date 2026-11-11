@extends('layouts.userlogin')
@extends('layouts.main')
@section('title', 'Control limit')
@section('breadcrumb-title', 'Control limit')

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
            </div>
           <!--  <div class="card-block">
                <div class="dt-responsive table-responsive">
                    <table width="100%" class="table table-striped table-bordered nowrap" id="datatables">
                        <thead>
                        <tr>
                            <th class="text-center" width="150">#</th>
                            <th class="text-center">Model</th>
                            <th class="text-center">Part UP/DN</th>
                            <th class="text-center">Drive Name</th>
                            <th class="text-center">Line Type</th>
                            <th class="text-center">VENDOR ID</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div> -->

            <div class="card-block p-0">
               

                                <div class="form-group row">
                                    <div class="col">
                                        <div class="col-12">
                                                
                                                <div class="row">
                                                    <div class="col-md-12 col-lg-4">
                                                        <div class="card"> 
                                                          <!-- <div class="card-block text-center" onclick="dowloadfile('SGT')" style="cursor: pointer;"> 
                                                              <h4><img class="img-fluid" src="{{ asset('template/assets/images/item/3.png')}}" width="33%" alt="Theme-Logo" /></h4>
                                                              <h4 class="m-t-20">Master control</h4> 
                                                          </div> -->
                                                            <div class="card-block text-center">
                                                                <a href="#" style="cursor: pointer;" onclick="dowloadfile('SGT')">
                                                                    <h4><img class="img-fluid" src="{{ asset('template/assets/images/item/3.png')}}" width="33%" alt="Theme-Logo" /></h4>
                                                                    <h4 class="m-t-20">Master control limit</h4>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 col-lg-4">
                                                        <div class="card">
                                                          <div class="card-block text-center">
                                                            <!-- <a href="{{ url('/master/modeltabledata') }}"> -->
                                                            <a href="{{ url('/master/createcontrol/SGT') }}" style="cursor: pointer;">
                                                                <h4><img class="img-fluid" src="{{ asset('template/assets/images/item/2.png')}}" width="33%" alt="Theme-Logo" /></h4>
                                                                <h4 class="m-t-20">Create control limit</h4>
                                                            </a>
                                                          </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 col-lg-4">
                                                        <div class="card">
                                                          <div class="card-block text-center">
                                                            <!-- <a href="{{ url('/output/calculation') }}" style="cursor: pointer;"> -->
                                                            <!-- 6.Combline control limit -->
                                                            <a href="{{ url('/master/calculateoption') }}" style="cursor: pointer;">
                                                            <!-- 6.Combline control limit -->
                                                              <h4><img class="img-fluid" src="{{ asset('template/assets/images/item/1.png')}}" width="33%" alt="Theme-Logo" /></h4>
                                                              <h4 class="m-t-20">Calculation</h4>
                                                            </a>
                                                          </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12 col-lg-4">
                                                        <div class="card">
                                                          <div class="card-block text-center">
                                                            <a href="{{ url('/master/modeldata') }}" style="cursor: pointer;">
                                                              <h4><img class="img-fluid" src="{{ asset('template/assets/images/item/2.png')}}" width="33%" alt="Theme-Logo" /></h4>
                                                              <h4 class="m-t-20">Control limit (Old verstion)</h4>
                                                            </a>
                                                          </div>
                                                        </div>
                                                    </div>

                                                </div>
                                                  
                                            
                                        </div>
                                    </div>
                                </div>

              

            </div>
        </div>
    </div>
</div>
<input type="hidden" id="userstatus" value="{{Auth::user()->status}}">
@endsection

@section('scripts')

<script>
    var userstatus = $('#userstatus').val();


    function dowloadfile(type) {
            
        $.ajax({
            url: `{{ url('/master/downloadcontrolfile/${type}') }}`,
            type: 'GET',
            dataType: "text",
            data: {
                "type": type,
                "_token": '{{ csrf_token() }}',
            },
            success: function(data) {
                window.location.href = '../local/storage/NHK-File/report/controllimit/'+data; //Will take you to Google.
            }
        });
            
    }

</script>

@endsection
