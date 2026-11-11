
@extends('layouts.userlogin')
@extends('layouts.main')
@section('title', 'Warning')
@section('breadcrumb-title-href', '#')
@section('breadcrumb-title', 'Warning')

@section('content')
<div class="card">
    <div class="card-header">
      <br>
      <br>
      <br>
      <br>
       <center><h4>Model :{{$name_model}}</h4><br><h3 style="color: red">Not Data ! </h3></center>
       <br>
       <br>
       <br>
       <br>
    </div>   
</div>
<div class="row">
    
    
</div>
@endsection

@section('scripts')
@endsection
