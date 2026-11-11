@extends('layouts.main')
@section('title', 'Create Monitoring set up')
@section('breadcrumb-title-href', 'incoming')
@section('breadcrumb-title', 'Monitoring set up')
@section('breadcrumb-sub', 'Create Monitoring set up')

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
                                        @foreach ($unit_tester as $value)
                                        <option value="{{ $value->id_mnu }}">{{ $value->name_tester }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="j-unit">
                                <div class="j-input">
                                    <label class="j-icon-right" for="txt_topic">
                                        <i class="icofont icofont-tag"></i>
                                    </label>
                                    <input type="text" id="txt_topic" name="txt_topic" placeholder="Topic" />
                                </div>
                            </div>
                            <!-- <div class="j-unit">
                                <div class="j-input">
                                    <label class="j-icon-right" for="txt_frequency">
                                        <i class="icofont icofont-leaflet"></i>
                                    </label>
                                    <select id="txt_frequency" name="txt_frequency" placeholder="Frequency">
                                        <option value="">Select Frequency</option>
                                        @foreach ($unit_frequency as $frequency)
                                        <option value="{{ $frequency->id_frequency }}">{{ $frequency->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div> -->

                            <div class="form-group row">
                                <label for="txt_startdate" class="col-sm-2 col-form-label">Start date</label>
                                <div class="col-sm-10">
                                <input type="datetime-local" id="txt_startdate" name="txt_startdate" class="form-control" placeholder="start date" />
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="repeat" class="col-sm-2 col-form-label">Repeat</label>
                                <div class="col-sm-5">
                                    <select id="repeat" name="repeat">
                                        <option value="">Select</option>
                                        @for($i = 1; $i<= 31; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-sm-5">
                                    <select id="repeatunit" name="repeatunit" >
                                        <option value="">Select</option>
                                        <option value="daily">daily</option>
                                        <option value="weeks">weeks</option>
                                        <option value="months">months</option>
                                        <option value="years">years</option>
                                    </select>
                                </div>
                            </div>
                            <!-- <div class="form-group row">
                                <label for="staticEmail" class="col-sm-2"></label>
                                <div class="col-sm-10">
                                    <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
                                      <div class="btn-group mr-2" role="group" aria-label="First group">
                                        <button type="button" class="selectday btn btn-secondary" data-id="0">Mon</button>
                                        <button type="button" class="selectday btn btn-secondary" data-id="0">Tue</button>
                                        <button type="button" class="selectday btn btn-secondary" data-id="0">Wed</button>
                                        <button type="button" class="selectday btn btn-secondary" data-id="0">Thu</button>
                                        <button type="button" class="selectday btn btn-secondary" data-id="0">Fri</button>
                                        <button type="button" class="selectday btn btn-secondary" data-id="0">Sat</button>
                                        <button type="button" class="selectday btn btn-secondary" data-id="0">Sun</button>
                                      </div>
                                    </div>
                                </div>
                            </div> -->

                            <div class="form-group row">
                                <label for="txt_enddate" class="col-sm-2 col-form-label">End date</label>
                                <div class="col-sm-10">
                                <input type="date" id="txt_enddate" name="txt_enddate" class="form-control" placeholder="end date" />
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

    $( "#txt_frequency" ).on( "change", function() {
            
        $("#divdate").children().remove();

        if ($( this ).val() == 1) {
            $("#divdate").append($('<input type="time" id="txt_date" name="txt_date" class="form-control" placeholder="Due date" />'));
        }else{
            $("#divdate").append($('<input type="datetime-local" step=3600 id="txt_date" name="txt_date" class="form-control" placeholder="Due date" />'));
        }

    } );

    
    $( ".selectday" ).on( "click", function() {
        var dataid = $(this).attr("data-id");
        if(dataid == 0){
            $(this).attr('data-id', 1);
            $(this).css('background-color','#2ed8b6');
        }else{
            $(this).attr('data-id', 0);
            $(this).css('background-color','#727b84');
        }
    });

 
    $('#submitcreatelab').click(function () {

        // var array = []; // value from => Repeat on the day
        // $(".selectday").each(function(){ 
        //     array.push($(this).attr("data-id"));
        // });

        var i = 0;
        if($('#txt_tester').val().length === 0)
        {
            i++;
        }
        if($('#txt_startdate').val().length === 0)
        {
            i++;
        }
        if($('#txt_enddate').val().length === 0)
        {
            i++;
        }
        if($('#txt_topic').val().length === 0)
        {
            i++;
        }

        
        var topic = $('#txt_topic').val();
        var tester = $('#txt_tester').val();
        var startdate = $('#txt_startdate').val();
        var enddate = $('#txt_enddate').val();
        var repeat = $('#repeat').val();
        var repeatunit = $('#repeatunit').val();

        if(i > 0)
        {
            swal("warning", "Please fill in information", "warning");
            return;
        }

        if(i == 0)
        {

            swal({
                html: true,
                title: "Are you sure?",
                text: "Confirm Monitoring Set up?",
                type: "info",
                showCancelButton: true,
                closeOnConfirm: false,
                showLoaderOnConfirm: true
            },
            function () {
                $.ajax({
                    type: 'POST',
                    url: "{{ url('createMonitorset') }}",
                    data: {
                        "tester": tester,
                        "topic": topic,
                        "startdate": startdate,
                        "enddate": enddate,
                        "repeats": repeat,
                        "repeatsunit": repeatunit,
                        // "repeatsday": array,
                        "_token": '{{ csrf_token() }}',
                    },
                    dataType: "JSON",
                    success: function (data) {
                        if (data.success == 1) {
                            swal("Successed", "Submit save data successed", "success");
                            setTimeout(function () {
                                window.location.href =
                                    "{{ url('master/monitorset') }}";
                            }, 1000);
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
