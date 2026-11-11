@extends('layouts.main')
@section('title', 'Edit Daily check')
@section('breadcrumb-title-href', '../output/dailycheck')
@section('breadcrumb-title', 'Daily check')
@section('breadcrumb-sub', 'Edit Daily check')

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
                    <form  method="post" class="j-pro" id="fm-daily" >
                    {{ csrf_field() }}
                        <!-- end /.header-->

                        <input type="hidden" id="due_date" name="due_date" value="{{ date('H:i:s', strtotime($info->due_date)) }}">
                        <input type="hidden" id="id_dailyinfo" name="id_dailyinfo" value="{{ $info->id_dailyinfo }}">

                        <div class="j-content" id="page_1"> 
                            <div class="j-unit">
                                <div class="j-input">
                                    <label class="j-icon-right" for="txt_tester">
                                        <i class="icofont icofont-leaflet"></i>
                                    </label>
                                    <input type="text" value="{{ $unit->name_tester }}" readonly>
                                </div>
                            </div>
                            <div class="j-unit">
                                <div class="j-input">
                                    <label class="j-icon-right" for="txt_operation">
                                        <i class="icofont icofont-leaflet"></i>
                                    </label>
                                    <input type="text" id="txt_operation" name="txt_operation" value="{{ $info->operation }}" placeholder="Operator">
                                </div>
                            </div> 
                            <div class="j-unit">
                                <div class="j-input">
                                    <label class="j-icon-right" for="txt_link">
                                        <i class="icofont icofont-tag"></i>
                                    </label>
                                    <select name="txt_result" id="txt_result">
                                        <option value="">Select Result</option>
                                        <option value="PASS" {{ ($info->results == "PASS")? 'selected' : '' }}>PASS</option>
                                        <option value="NOT_PASS" {{ ($info->results == "NOT_PASS")? 'selected' : '' }}>NOT PASS</option>
                                        <option value="DONOTUSE" {{ ($info->results == "DONOTUSE")? 'selected' : '' }}>DON'T USE</option>
                                    </select>
                                </div>
                            </div>
                            <div class="divider gap-bottom-25"></div>
                            <div class="j-response"></div>
                        </div>


                        <div class="j-content" id="page_2" style="display: none;"> 
                            <div class="j-unit" style=" text-align: center;">
                                Reason of <u>DELAY</u> time
                            </div>
                            <div class="j-unit">
                                <div class="j-input">
                                    <label class="j-icon-right" for="txt_problem">
                                        <i class="icofont icofont-leaflet"></i>
                                    </label>
                                    <input type="text" id="txt_problem" name="txt_problem" value="{{ $info->problem }}" placeholder="Problem">
                                </div>
                            </div>
                            <div class="j-unit">
                                <div class="j-input">
                                    <label class="j-icon-right" for="txt_rootcause">
                                        <i class="icofont icofont-leaflet"></i>
                                    </label>
                                    <input type="text" id="txt_rootcause" name="txt_rootcause" value="{{ $info->rootcause }}" placeholder="Rootcause">
                                </div>
                            </div> 
                            <div class="j-unit">
                                <div class="j-input">
                                    <label class="j-icon-right" for="txt_action">
                                        <i class="icofont icofont-leaflet"></i>
                                    </label>
                                    <input type="text" id="txt_action" name="txt_action" value="{{ $info->action }}" placeholder="Action">
                                </div>
                            </div>
                            <div class="divider gap-bottom-25"></div>
                            <div class="j-response"></div>
                        </div>

                        <div class="j-content" id="page_3" style="display: none;"> 
                            <div class="j-unit" style=" text-align: center;">
                                Reason of <u>NOT PASS</u> result
                            </div>
                            <div class="j-unit">
                                <div class="j-input">
                                    <label class="j-icon-right" for="txt_problem_result">
                                        <i class="icofont icofont-leaflet"></i>
                                    </label>
                                    <input type="text" id="txt_problem_result" name="txt_problem_result" value="{{ $info->problem_result }}" placeholder="Problem">
                                </div>
                            </div>
                            <div class="j-unit">
                                <div class="j-input">
                                    <label class="j-icon-right" for="txt_rootcause_result">
                                        <i class="icofont icofont-leaflet"></i>
                                    </label>
                                    <input type="text" id="txt_rootcause_result" name="txt_rootcause_result" value="{{ $info->rootcause_result }}" placeholder="Rootcause">
                                </div>
                            </div> 
                            <div class="j-unit">
                                <div class="j-input">
                                    <label class="j-icon-right" for="txt_action_result">
                                        <i class="icofont icofont-leaflet"></i>
                                    </label>
                                    <input type="text" id="txt_action_result" name="txt_action_result" value="{{ $info->action_result }}" placeholder="Action">
                                </div>
                            </div>
                            <div class="divider gap-bottom-25"></div>
                            <div class="j-response"></div>
                        </div>
                        <div class="j-footer">
                            <button type="button" id = "btncheck-1" class="btn btn-success waves-effect m-b-10">SAVE</button>
                            <button type="button" id = "btncheck-2" class="btn btn-success waves-effect m-b-10" style="display: none;">SAVE</button>
                            <button type="button" id = "btncheck-3" class="btn btn-success waves-effect m-b-10" style="display: none;">SAVE</button>
                            <button type="button" id = "btncheck-4" class="btn btn-success waves-effect m-b-10" style="display: none;">SAVE</button>
                        </div>
                    </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>

 
    $('#btncheck-1').click(function () {

        var operation = $("#txt_operation").val();
        var result = $("#txt_result").val();

        if( operation.length > 0 ) {
            if( result.length > 0 ) {

            
                var dt = new Date(); 

                var nowhours = dt.getHours();
                var nowminutes = dt.getMinutes();
                var nowseconds = dt.getSeconds();

                nowhours = (nowhours < 10 ? "0" : "") + nowhours;
                nowminutes = (nowminutes < 10 ? "0" : "") + nowminutes;
                nowseconds = (nowseconds < 10 ? "0" : "") + nowseconds;

                var currentTime = nowhours + ":" + nowminutes + ":" + nowseconds;

                console.log("เวลาลงจริง : "+ currentTime);

                // var due_date = $("#due_date").val();
                var due_date = '{{$info->due_date}}';
                var currDate = new Date(due_date);

                var targethours = currDate.getHours();
                var targetminutes = currDate.getMinutes();
                var targetseconds = currDate.getSeconds();

                targethours = (targethours < 10 ? "0" : "") + targethours;
                targetminutes = (targetminutes < 10 ? "0" : "") + targetminutes;
                targetseconds = (targetseconds < 10 ? "0" : "") + targetseconds;

                var targetTime = targethours + ":" + targetminutes + ":" + targetseconds;

                console.log("กำหนดส่ง : "+ targetTime);


                if(currentTime > targetTime) // ส่งช้า
                {
                    $("#page_2").css("display", "block");
                    $("#page_1").css("display", "none"); 

                    $("#btncheck-2").css("display", "block");
                    $("#btncheck-1").css("display", "none");

                    if(result == "PASS"){

                        $("#txt_alert").text("DELAY");
                        $("#txt_alertresult").text("time");

                    }else{

                        var newresult = result.replace("_", " ");

                        // NOT PASS
                        $("#txt_alert").text("DELAY");
                        $("#txt_alertresult").text("time and");

                        $("#txt_alert2").text(newresult);
                        $("#txt_alertresult2").text("result");

                        $("#btncheck-3").css("display", "block");
                        $("#btncheck-2").css("display", "none");

                    }

                }else{ // ตรงเวลา หรือ ก่อนเวลาที่กำหนด

                    if(result == "NOT_PASS"){

                        $("#page_3").css("display", "block");
                        $("#page_1").css("display", "none"); 

                        $("#btncheck-4").css("display", "block");
                        $("#btncheck-1").css("display", "none");


                    }else{

                        var forms = $('#fm-daily')[0];
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
                            var forms = $('#fm-daily')[0];
                            var data = new FormData(forms);
                            $.ajax({
                                type: 'POST',
                                url: "{{ url('updateDailycheck') }}",
                                data: data,
                                processData: false,
                                contentType: false,
                                cache: false,
                                success: function (data) {
                                    if (data.success == 1) {
                                        swal("Successed", "Submit save data successed", "success");
                                        setTimeout(function () {
                                            window.location.href =
                                                "{{ url('output/dailycheck') }}";
                                        }, 1000);
                                    } else {

                                    }
                                }
                            });
                        });

                    } 

                }

                // if( timenow < due_date){ // ตรงเวลา หรือ ก่อนเวลาที่กำหนด


                //     if(result == "NOT_PASS"){

                //         $("#page_3").css("display", "block");
                //         $("#page_1").css("display", "none"); 

                //         $("#btncheck-4").css("display", "block");
                //         $("#btncheck-1").css("display", "none");
                       

                //     }else{
                        
                //         var forms = $('#fm-daily')[0];
                //         var data = new FormData(forms);
                //         swal({
                //             html: true,
                //             title: "Are you sure?",
                //             text: "Confirm Create Daily Check Setup?",
                //             type: "info",
                //             showCancelButton: true,
                //             closeOnConfirm: false,
                //             showLoaderOnConfirm: true
                //         },
                //         function () {
                //             var forms = $('#fm-daily')[0];
                //             var data = new FormData(forms);
                //             $.ajax({
                //                 type: 'POST',
                //                 url: "{{ url('updateDailycheck') }}",
                //                 data: data,
                //                 processData: false,
                //                 contentType: false,
                //                 cache: false,
                //                 success: function (data) {
                //                     if (data.success == 1) {
                //                         swal("Successed", "Submit save data successed", "success");
                //                         setTimeout(function () {
                //                             window.location.href =
                //                                 "{{ url('output/dailycheck') }}";
                //                         }, 1000);
                //                     } else {

                //                     }
                //                 }
                //             });
                //         });

                //     } 

                // }else{ // ส่งช้า

                //     $("#page_2").css("display", "block");
                //     $("#page_1").css("display", "none"); 

                //     $("#btncheck-2").css("display", "block");
                //     $("#btncheck-1").css("display", "none");

                //     if(result == "PASS"){

                //         $("#txt_alert").text("DELAY");
                //         $("#txt_alertresult").text("time");

                //     }else{

                //         var newresult = result.replace("_", " ");

                //         // NOT PASS
                //         $("#txt_alert").text("DELAY");
                //         $("#txt_alertresult").text("time and");

                //         $("#txt_alert2").text(newresult);
                //         $("#txt_alertresult2").text("result");

                //         $("#btncheck-3").css("display", "block");
                //         $("#btncheck-2").css("display", "none");

                //     }

                // }

            }else{
                swal("Warning", "Please select the result.", "warning");
            }
        }else{
            swal("Warning", "Please enter the operation.", "warning");
        } 
    });


    $('#btncheck-2').click(function () {

        var problem = $("#txt_problem").val();
        var rootcause = $("#txt_rootcause").val();
        var action = $("#txt_action").val();

        if( problem.length > 0 && rootcause.length > 0 && action.length > 0 ) {

            var forms = $('#fm-daily')[0];
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
                var forms = $('#fm-daily')[0];
                var data = new FormData(forms);
                $.ajax({
                    type: 'POST',
                    url: "{{ url('updateDailycheck') }}",
                    data: data,
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function (data) {
                        if (data.success == 1) {
                            swal("Successed", "Submit save data successed", "success");
                            setTimeout(function () {
                                window.location.href =
                                    "{{ url('output/dailycheck') }}";
                            }, 1000);
                        } else {

                        }
                    }
                });
            });

        }else{
            // กรอกข้อมูลไม่ครบ | เลือก NOT PASS แต่ส่งงานตรงเวลา
            swal("Warning", "Please fill in all the information", "warning");
        }

    });

    $('#btncheck-3').click(function () {

        var result = $("#txt_result").val();

        if(result == "NOT_PASS"){

            $("#page_3").css("display", "block");
            $("#page_2").css("display", "none"); 

            $("#btncheck-4").css("display", "block");
            $("#btncheck-3").css("display", "none");
                           
        }else{

            var forms = $('#fm-daily')[0];
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
                var forms = $('#fm-daily')[0];
                var data = new FormData(forms);
                $.ajax({
                    type: 'POST',
                    url: "{{ url('updateDailycheck') }}",
                    data: data,
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function (data) {
                        if (data.success == 1) {
                            swal("Successed", "Submit save data successed", "success");
                            setTimeout(function () {
                                window.location.href =
                                    "{{ url('output/dailycheck') }}";
                            }, 1000);
                        } else {

                        }
                    }
                });
            });

        }

        
    });


    $('#btncheck-4').click(function () {
        
        var forms = $('#fm-daily')[0];
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
            var forms = $('#fm-daily')[0];
            var data = new FormData(forms);
            $.ajax({
                type: 'POST',
                url: "{{ url('updateDailycheck') }}",
                data: data,
                processData: false,
                contentType: false,
                cache: false,
                success: function (data) {
                    if (data.success == 1) {
                        swal("Successed", "Submit save data successed", "success");
                        setTimeout(function () {
                            window.location.href ="{{ url('output/dailycheck') }}";
                        }, 1000);
                    } else {

                    }
                }
            });
        });

    });

</script>
@endsection
