@extends('layouts.userlogin')
@extends('layouts.main')
@section('title', 'View user')
@section('breadcrumb-title', 'User')

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-block" style="margin-left: 5%;margin-right: 5%">
                <br>
                <br>
                <form id="mainform" method="post"  novalidate>
                    {{ csrf_field() }}
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Name :</label>
                        <input type="hidden" id="userid" name="userid" value="{{$users->id}}">
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="name" id="name" value="{{$users->name}}" readonly> 
                            <span class="messages"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Email :</label>
                        <div class="col-sm-10">
                            <input type="email" class="form-control" id="email" name="email" value="{{$users->email}}" readonly>
                            <span class="messages"></span>
                        </div>
                    </div>
                 
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Password : </label>
                        <div class="col-sm-10">
                        <span style="font-size: 15px" class="label label-danger">******</span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Phone :</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="phone" id="phone" value="{{$users->phone}}" readonly>
                            <span class="messages"></span>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-2 col-form-label">Status :</label>
                        <div class="col-sm-10">
                        
                            @if ($users->status == 1)
                            <span style="font-size: 15px" class="label label-danger">ADMIN</span>
                            @endif

                            @if ($users->status == 2)
                            <span style="font-size: 15px" class="label label-primary">QA</span>
                            @endif

                            @if ($users->status == 3)
                            <span style="font-size: 15px" class="label label-warning">MFG</span>
                            @endif
                            
                          
                        </div>
                    </div>
                    <br>
                        <br>
                  
                </form>
            </div>
        </div>
    </div>
</div>
</div>

@endsection

@section('scripts')

<script>



</script>

@endsection
