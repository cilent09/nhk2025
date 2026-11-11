@extends('layouts.main')
@section('title', 'Create Daily check set up')
@section('breadcrumb-title-href', 'incoming')
@section('breadcrumb-title', 'Daily check set up')
@section('breadcrumb-sub', 'Create Daily check set up')

@section('content')
<style>
    .mystyle {
      width: 100%;
      padding: 25px;
      background-color: coral;
      color: white;
      font-size: 25px;
      box-sizing: border-box;
    }
    </style>
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header"> 
            </div>
            <div class="card-block">
                <div class="j-wrapper j-wrapper-640">
                    <form  method="post" class="j-pro" id="fm-createlab" >
                    {{ csrf_field() }}
                        <!-- end /.header-->
                        <div class="j-content"> 
                            <div class="j-unit">
                                <div class="j-input">
                                    <label class="j-icon-right" for="txt_tester">
                                        <i class="icofont icofont-leaflet"></i>
                                    </label>
                                    <select class="mySelectNewTags" id="txt_tester" name="txt_tester" placeholder="Tester">
                                        @foreach ($unit as $value)
                                        <option value="{{ $value->id_dyunit }}">{{ $value->name_tester }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="j-unit">
                                <div class="cs-form">
                                  <!-- <input type="datetime-local" step=3600 id="txt_date" name="txt_date" class="form-control" placeholder="Due date" /> -->
                                  <input type="time" id="txt_date" name="txt_date" class="form-control" placeholder="Due date" require />
                                </div>
                            </div> 
                            
                            <div class="divider gap-bottom-25"></div>
                            <div class="j-response"></div>
                        </div>
                        <div class="j-footer">
                            <button type="button" id = "submitcreatelab" class="btn btn-success waves-effect m-b-10"  >SAVE</button>
                        </div>
                    </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $( document ).ready(function() {
        $(".mySelectNewTags").select2({
                tags: true
            });
    });
 
    $('#submitcreatelab').click(function () {

        var i = 0;
        if($('#txt_tester').val().length === 0)
        {
            i++;
        }
        if($('#txt_date').val().length === 0)
        {
            i++;
        }
       
      
        if(i > 0)
        {
            swal("warning", "Please fill in information", "warning");
            return;
        }

        if(i == 0)
        {

            var forms = $('#fm-createlab')[0];
            var data = new FormData(forms);
            swal({
                html: true,
                title: "Are you sure?",
                text: "Confirm Create Daily Check Setup?",
                type: "info",
                showCancelButton: true,
                closeOnConfirm: false,
                showLoaderOnConfirm: true
            },
            function () {
                var forms = $('#fm-createlab')[0];
                var data = new FormData(forms);
                $.ajax({
                    type: 'POST',
                    url: "{{ url('createDailycheckset') }}",
                    data: data,
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function (data) {
                        if (data.success == 1) {
                            swal("Successed", "Submit save data successed", "success");
                            setTimeout(function () {
                                window.location.href =
                                    "{{ url('master/dailycheckset') }}";
                            }, 1000);
                        } else if (data.success == 3) {
                            swal("Warning", "Please check the information. Data is duplicated.", "warning");
                        }else{
                            swal("Warning", "Please check whether the information is correct.", "warning");
                        }
                    }
                });
            });

        }
    });

</script>
@endsection
