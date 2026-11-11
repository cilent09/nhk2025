@extends('layouts.main')
@section('title', 'Alarm point')
@section('breadcrumb-title-href', 'Alarm point')
@section('breadcrumb-title', 'Alarm point')


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
                    <form  method="post" class="j-pro" id="fm-alarm" >
                    {{ csrf_field() }}
                        <!-- end /.header-->
                        <div class="j-content">
                            
                           <label><span style="font-size:20px">&nbsp;TEST#2</span> <span style="color: brown;font-size:14px">(K points are tops or bottoms of the center line.
                            (4 ≤ K ≤ 11))</span></label>
                            <div class="j-unit">
                                <div class="j-input">
                                    <label class="j-icon-right" for="address">
                                        <i class="icofont icofont-warning-alt"></i>
                                    </label>
                                <input type="text" id="test2" name="test2" placeholder="" value="{{$data->test2}}"  >
                      
                                </div>
                            </div>
                            <br>
                            <label><span style="font-size:20px">&nbsp;TEST#3</span> <span style="color: brown;font-size:14px">(K points are increased or decreased continuously.
                                (4 ≤ K ≤ 11))</span></label>
                            <div class="j-unit">
                                <div class="j-input">
                                    <label class="j-icon-right" for="address">
                                        <i class="icofont icofont-warning-alt"></i>
                                    </label>
                                    <input type="text" id="test3" name="test3" placeholder="" value="{{$data->test3}}" >
                                </div>
                            </div>
                            <div class="divider gap-bottom-25"></div>
                            <div class="j-response"></div>
                        </div>
                        <br>
                        <div class="j-footer">
                           
                            {{-- <button type="button" id="submitcreatelab" class="btn btn-primary">CREATE</button> --}}
                            {{-- <button type="button" onclick="xx()" id = "buttoncreate" class="btn btn-success waves-effect m-b-10" data-toggle="modal" data-target="#myModal" disabled>CREATE</button> --}}
                            <button type="button" onclick="buttonadd()" id = "buttoncreate" class="btn btn-success waves-effect m-b-10"  >SAVE</button>
                            <button type="reset" onclick="buttonreset()"  class="btn btn-secondary m-r-20">RESET</button>   
                            <br> 
                        </div>
                  


                    </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function buttonadd() {
        if(parseInt($('#test2').val()) < 4)
        {
          swal("warning", "Please specify: points are increased or decreased continuously.(4 ≤ K ≤ 11)", "warning");
          return false;
        } 
        if(parseInt($('#test2').val()) > 11 )
        {
          swal("warning", "Please specify: points are tops or bottoms of the center line. (4 ≤ K ≤ 11)", "warning");
          return false;
        }
        if(parseInt($('#test3').val()) < 4)
        {
          swal("warning", "Please specify: points are tops or bottoms of the center line. (4 ≤ K ≤ 11)", "warning");
          return false;
        }
        if(parseInt($('#test3').val()) > 11 )
        {
          swal("warning", "Please specify: points are tops or bottoms of the center line. (4 ≤ K ≤ 11)", "warning");
          return false;
        }
        if($('#test2').val() == "")
        {
            swal("warning", "Please specify: points are tops or bottoms of the center line. (4 ≤ K ≤ 11)", "warning");
            return false;
        }
        if($('#test3').val() == "")
        {
            swal("warning", "Please specify: points are tops or bottoms of the center line. (4 ≤ K ≤ 11)", "warning");
            return false;
        }
        swal({
                html: true,
                title: "Are you sure?",
                text: "Confirm Edit Alarm point!",
                type: "info",
                showCancelButton: true,
                closeOnConfirm: false,
                showLoaderOnConfirm: true
            },
            function () {
                var forms = $('#fm-alarm')[0];
                var data = new FormData(forms);
                $.ajax({
                    type: 'POST',
                    url: "{{ url('updatealarm') }}",
                    data: data,
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function (data) {

                        if (data.success == 1) {
                            swal("Successed", "Submit save data successed", "success");
                            setTimeout(function () {
                                window.location.href =
                                    "{{ url('master/manageralarm') }}";
                            }, 1000);
                        } else {

                        }
                    }
                });

            });
    }


$(function() {
    $('#test2').focusout(function() {
        if($('#test2').val() != "")
    {
        if(parseInt($('#test2').val()) < 4)
        {
          swal("warning", "Please specify: points are increased or decreased continuously.(4 ≤ K ≤ 11)", "warning");
        } 
        if(parseInt($('#test2').val()) > 11 )
        {
          swal("warning", "Please specify: points are tops or bottoms of the center line. (4 ≤ K ≤ 11)", "warning");
        }
    }
    });
});

$(function() {
    $('#test3').focusout(function() {
        if($('#test3').val() != "")
    {
        if(parseInt($('#test3').val()) < 4)
        {
          swal("warning", "Please specify: points are tops or bottoms of the center line. (4 ≤ K ≤ 11)", "warning");
        }
        if(parseInt($('#test3').val()) > 11 )
        {
          swal("warning", "Please specify: points are tops or bottoms of the center line. (4 ≤ K ≤ 11)", "warning");
        }
        //$('#test3').val(6);
   
    }
    });
});


   
</script>
@endsection
