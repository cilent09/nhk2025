@extends('layouts.main')
@section('title', 'Edit Monitoring')
@section('breadcrumb-title-href', '../output/monitoring')
@section('breadcrumb-title', 'Monitoring')
@section('breadcrumb-sub', 'Edit Monitoring')

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

                        <input type="hidden" id="due_date" name="due_date" value="{{ date('Y-m-d H:i:s', strtotime($info->due_date)) }}">
                        <input type="hidden" id="id_mnf" name="id_mnf" value="{{ $info->id_mnf }}">

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
                                    <label class="j-icon-right" for="txt_topic">
                                        <i class="icofont icofont-leaflet"></i>
                                    </label>
                                    <input type="text" value="{{ $set->topic }}" readonly>
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
                                    <label class="j-icon-right" for="txt_result">
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

                var timenow = new Date(Date.now());

                var due_date = $("#due_date").val();
                var due_day = new Date(due_date);

                console.log(due_day);
                console.log(timenow);

                if( timenow < due_day){ // ตรงเวลา

                    if(result == "NOT_PASS"){ // ไม่ผ่าน

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
                            text: "Confirm Create Monitoring Setup?",
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
                                url: "{{ url('updateMonitoring') }}",
                                data: data,
                                processData: false,
                                contentType: false,
                                cache: false,
                                success: function (data) {
                                    if (data.success == 1) {
                                        swal("Successed", "Submit save data successed", "success");
                                        setTimeout(function () {
                                            window.location.href =
                                                "{{ url('output/monitoring') }}";
                                        }, 1000);
                                    } else {

                                    }
                                }
                            });
                        });

                    } 

                }else{ // ส่งช้า

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

                }

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
                text: "Confirm Create Monitoring Setup?",
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
                    url: "{{ url('updateMonitoring') }}",
                    data: data,
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function (data) {
                        if (data.success == 1) {
                            swal("Successed", "Submit save data successed", "success");
                            setTimeout(function () {
                                window.location.href =
                                    "{{ url('output/monitoring') }}";
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

        var problem = $("#txt_problem").val();
        var rootcause = $("#txt_rootcause").val();
        var action = $("#txt_action").val();
        var result = $("#txt_result").val();

        if( problem.length > 0 && rootcause.length > 0 && action.length > 0 ) {

            if(result != "PASS"){

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
                    text: "Confirm Create Monitoring Setup?",
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
                        url: "{{ url('updateMonitoring') }}",
                        data: data,
                        processData: false,
                        contentType: false,
                        cache: false,
                        success: function (data) {
                            if (data.success == 1) {
                                swal("Successed", "Submit save data successed", "success");
                                setTimeout(function () {
                                    window.location.href =
                                        "{{ url('output/monitoring') }}";
                                }, 1000);
                            } else {

                            }
                        }
                    });
                });

            }

        }else{
            // กรอกข้อมูลไม่ครบ | เลือก NOT PASS แต่ส่งงานตรงเวลา
            swal("Warning", "Please fill in all the information", "warning");
        }

    });   

    $('#btncheck-4').click(function () {

        var problem = $("#txt_problem_result").val();
        var rootcause = $("#txt_rootcause_result").val();
        var action = $("#txt_action_result").val();

        if( problem.length > 0 && rootcause.length > 0 && action.length > 0 ) {

            var forms = $('#fm-daily')[0];
            var data = new FormData(forms);
            swal({
                html: true,
                title: "Are you sure?",
                text: "Confirm Create Monitoring Setup?",
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
                    url: "{{ url('updateMonitoring') }}",
                    data: data,
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function (data) {
                        if (data.success == 1) {
                            swal("Successed", "Submit save data successed", "success");
                            setTimeout(function () {
                                window.location.href =
                                    "{{ url('output/monitoring') }}";
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

</script>
@endsection
