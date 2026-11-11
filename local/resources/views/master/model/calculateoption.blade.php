
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
                <div class="form-group row">
                    <div class="col-1"></div> 
                    <div class="col-10">
                        <div class="col-sm-12">
                            <a href="{{ url('/output/calculation') }}" style="cursor: pointer;">
                                <h2><img src="{{ asset('template/assets/images/item/cal01.JPG')}}" alt="Individual"> Individual data</h2>
                            </a>
                        </div>
                    </div>
                    <div class="col-1"></div> 
                </div> 
                <div class="form-group row">
                    <div class="col-1"></div> 
                    <div class="col-10">
                        <div class="col-sm-12">
                            <a href="{{ url('/master/combinedata') }}" style="cursor: pointer;">
                                <h2><img src="{{ asset('template/assets/images/item/cal02.JPG')}}" alt="Combine"> Combine data</h2>
                            </a>
                        </div>
                    </div>
                    <div class="col-1"></div> 
                </div> 
                 
                <br>
                <br>
                
            </div>
        </div>
    </div>
   
 


<input type="hidden" id="userstatus" value="{{Auth::user()->status}}">
@endsection

@section('scripts')
   
@endsection
