@extends('layouts.userlogin')
@extends('layouts.main')
@section('title', 'Create date')
@section('breadcrumb-title', 'Create date')

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
            </div>
            <div class="card-block">

                <form method="post" id="fm-addmodel">
                    {{ csrf_field() }}
                    <input type="hidden" id="model_type" name="model_type" value="{{ $model_type }}">
                    <div class="row">
                        <div class="col-sm-2">
                        </div>
                        <div style="text-align: center" class="col-sm-8">
                            <div class="row">
                                <div class="col-sm-5">
                                    <label> Start Date</label>
                                    <input type="date" class="form-control" name="date_start" id="date_start" value="" onchange="ckdate1()">
                                </div>
                                <div class="col-sm-5">
                                    <label> End Date</label>
                                    <input type="date" class="form-control" name="date_end" id="date_end" value="" onchange="ckdate2()">
                                </div>
                                <div class="col-sm-2" style="text-align: left">
                                    <label>&nbsp;</label>
                                    <div style="font-size: 18px" class="form-check">
                                        <input style="width: 18px; height: 18px;" class="form-check-input"  type="checkbox" value="0" id="checkchkdate" onchange="checkdate()">
                                        <label class="form-check-label" for="flexCheckDefault">
                                            ปัจจุบัน
                                        </label>
                                        <input type="hidden" id="chkbox1" name="chkbox1" value="0">
                                      </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-sm-2">
                        </div>
                        <div class="col-sm-8">
                            <div style="text-align: right;">
                                <button type="button" onclick="buttonadd()" id="buttoncreate" class="btn btn-success">SAVE</button>
                                <button type="reset" onclick="buttonreset()" class="btn btn-secondary">RESET</button>
                            </div>
                        </div>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>

    
    function buttonreset()
    {

        $("#fm-addmodel")[0].reset();
        $("#date_end").prop('disabled', false);
        $('#chkbox1').val(0);
    }

    function ckdate2()
    {
        var date_start = $('#date_start').val();
        var date_end = $('#date_end').val();
        if(date_start != "")
        {
        if(date_end <= date_start)
        {
            swal("warning", "Warning date_start > date_end !", "warning");
            $('#date_end').val('');
            return false;
        }
        }
    }
    
    function ckdate1()
    {
        var date_start = $('#date_start').val();
        var date_end = $('#date_end').val();
        if(date_end != "")
        {
        if(date_start  >= date_end)
        {
            swal("warning", "Warning date_start > date_end !", "warning");
            $('#date_start').val('');
            return false;
        }
        }
    }
    function checkdate()
    {
        chk = $('#chkbox1').val();
    
       if(chk == 0)
       {
         $('#chkbox1').val(1);
         $("#date_end").val('');
         $("#date_end").prop('disabled', true);
       }
       else
       {
        $('#chkbox1').val(0);
        
        $("#date_end").prop('disabled', false);
       }
        
    }
    function buttonadd()
    {
        var date_start = $('#date_start').val();
        var model_type = $('#model_type').val();
        var chkbox1 = $('#chkbox1').val();
        const text = ["T", "U", "L","C"];
        var text1 = '';
        var text2 = '';
        var text3 = '';
        var text4 = '';
        var ck = 0;
      
        
        if(chkbox1 == 1)
        {
            var date_end = "31-12-2060";
        }
        else
        {
            var date_end = $('#date_end').val();
        }
        if(date_start != "" && date_end != "")
        {
            
        swal({
                html: true,
                title: "Are you sure?",
                text: "Submit Create date Control limit ! ",
                type: "info",
                showCancelButton: true,
                closeOnConfirm: false,
                showLoaderOnConfirm: true
            },
            function () {
                var forms = $('#fm-addmodel')[0];
                var data = new FormData(forms);
                $.ajax({
                    type: 'POST',
                    url: "{{ url('addmodelcreatedate') }}",
                    data: data,
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function (data) {
                        if (data.success == 1) {
                            swal("Success", "Create date Control limit success", "success");
                                setTimeout(function () {
                                    window.location.href = "{{ url('master/createcontrol/') }}/"+model_type;
                                }, 1000);
                        }
                   
                    }
                });
            });
        }
        else
        {

            swal("warning", "Please select startdate and enddate  !!", "warning");
            return false;
        }
    }


</script>
@endsection