@extends('layouts.userlogin')
@extends('layouts.main')
@section('title', 'Edit Team Help')
@section('breadcrumb-title-href', '../help')
@section('breadcrumb-title', 'Help')
@section('breadcrumb-sub', 'Edit Team Help')

@section('content') 

<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-block" style="margin-left: 5%;margin-right: 5%">
                <br>
                <br>
                <form id="mainform" method="post" enctype="multipart/form-data"  novalidate>
                    {{ csrf_field() }}
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Name <span style="color: red">*</span></label>
                        <input type="hidden" id="teamid" name="teamid" value="{{ $id }}">
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="name" id="name" value="{{ $team->name_members }}">
                            <span class="messages"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">image </label>
                        <div class="col-sm-10">
                            <input type="file" class="form-control" id="image" name="image" >
                            <span class="messages"></span>
                        </div>
                    </div>
                     
                    <br>
                        <br>
                    <div class="form-group row">
                        
                        <label class="col-sm-2"></label>
                        <div class="col-sm-10">
                            <button id="saveteam" type="button" class="btn btn-primary m-b-0">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>

@endsection

@section('scripts')
<script>

    $('#saveteam').click(function () {

        var teamid = $('#teamid').val();
        var name = $('#name').val();
         
        if (name == "" ) {
            swal("warning", "This field is required !", "warning");
            return false;
        }

        var forms = $('#mainform')[0];
        var data = new FormData(forms);
        swal({
                html: true,
                title: "Are you sure?",
                text: "Update Team Help!",
                type: "info",
                showCancelButton: true,
                closeOnConfirm: false,
                showLoaderOnConfirm: true
            },
            function () {
                $.ajax({
                    type: 'POST',
                    url: "{{ url('support/saveeditteam') }}",
                    data: data,
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function (data) {
  
                        if (data.success == 1) {
                            swal("Success", "Submit save data success", "success");
                            setTimeout(function () {
                                window.location.href =
                                    "{{ url('support/help') }}";
                            }, 1000);
                        } else {
  
                        }
                    }
                });
            });
             
             
  
    });
  
  </script>
  
@endsection