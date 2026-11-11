@extends('layouts.main')
@section('title', 'View Monitoring')
@section('breadcrumb-title-href', 'incoming')
@section('breadcrumb-title', 'Monitoring')
@section('breadcrumb-sub', 'View Monitoring')

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
                        <input type="hidden" id="timenow" name="timenow" value="{{ date('H:i:s', strtotime($info->recoredtime)) }}">
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

                            @if( !empty($info->results) )

                                <button type="button" id = "btncheck-1" data-id="1" class="btncheck btn btn-secondary waves-effect m-b-10">NEXT</button>
                                <button type="button" id = "btncheck-2" data-id="2" class="btncheck btn btn-secondary waves-effect m-b-10" style="display: none;">NEXT</button>
                                <button type="button" id = "btncheck-3" data-id="3" class="btncheck btn btn-secondary waves-effect m-b-10" style="display: none;">NEXT</button>
                                <a href="{{ url('output/monitoring') }}"><button type="button" id = "btncheck-4" data-id="4" class="btncheck btn btn-light waves-effect m-b-10">HOME</button></a>

                            @else

                                <a href="{{ url('output/monitoring') }}"><button type="button" class="btn btn-light waves-effect m-b-10">HOME</button></a>

                            @endif

                        </div>
                    </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>

    $('.btncheck').click(function () {
        
        var values = $(this).attr("data-id");
        console.log(values);

        var delay = $("#txt_problem").val();
        var result = $("#txt_problem_result").val();

        if( values == 1 ) {
            if( delay.length !== 0 && result.length !== 0){
                
                $("#page_2").css("display", "block");
                $("#page_1").css("display", "none"); 

                $("#btncheck-2").css("display", "block");
                $("#btncheck-1").css("display", "none");

                $( ".j-footer" ).append( "<button type='button' onclick='btnback(2)' class='btnback btn btn-secondary waves-effect m-b-10'>BACK</button>" );

            }else if(delay.length === 0 && result.length !== 0){

                $("#page_3").css("display", "block");
                $("#page_1").css("display", "none"); 

                $("#btncheck-1").css("display", "none");

                $( ".j-footer" ).append( "<button type='button' onclick='btnback(2)' class='btnback btn btn-secondary waves-effect m-b-10'>BACK</button>" );

            }else if(delay.length !== 0 && result.length === 0){

                $("#page_2").css("display", "block");
                $("#page_1").css("display", "none"); 

                $("#btncheck-1").css("display", "none");

                $( ".j-footer" ).append( "<button type='button' onclick='btnback(2)' class='btnback btn btn-secondary waves-effect m-b-10'>BACK</button>" );

            }

        }else if(values == 2){

            if( delay.length !== 0 && result.length !== 0){
                
               console.log(2.1);

                $("#page_3").css("display", "block");
                $("#page_2").css("display", "none"); 

                $("#btncheck-2").css("display", "none");

                $('.btnback').remove();

                $( ".j-footer" ).append( "<button type='button' onclick='btnback(3)' class='btnback btn btn-secondary waves-effect m-b-10'>BACK</button>" );

            }

        }


    });

    function btnback(id){

        var delay = $("#txt_problem").val();
        var result = $("#txt_problem_result").val();

        console.log(id);

        if (id == 2) {

            if( delay.length !== 0 && result.length !== 0){
                
                $("#page_2").css("display", "none");
                $("#page_1").css("display", "block "); 

                $("#btncheck-2").css("display", "none");
                $("#btncheck-1").css("display", "block");

                $('.btnback').remove();

            }else if(delay.length === 0 && result.length !== 0){

                $("#page_3").css("display", "none");
                $("#page_1").css("display", "block"); 

                $("#btncheck-1").css("display", "block");

                $('.btnback').remove();

            }else if(delay.length !== 0 && result.length === 0){

                $("#page_2").css("display", "none");
                $("#page_1").css("display", "block"); 

                $("#btncheck-1").css("display", "block");

                $('.btnback').remove();

            }

        }else if(id == 3){

            $("#page_3").css("display", "none");
            $("#page_2").css("display", "block"); 

            $("#btncheck-2").css("display", "block");

            $('.btnback').remove();

            $( ".j-footer" ).append( "<button type='button' onclick='btnback(2)' class='btnback btn btn-secondary waves-effect m-b-10'>BACK</button>" );

        }

    }

   

  

                        

</script>
@endsection
