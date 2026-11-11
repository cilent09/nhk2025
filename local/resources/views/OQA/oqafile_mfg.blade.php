

@extends('layouts.main')
@section('title', 'Edit Incoming LAB')
@section('breadcrumb-title-href', '../incoming')
@section('breadcrumb-title', 'Incoming LAB')
@section('breadcrumb-sub', 'Edit Incoming LAB')

@section('content')
<div class="card">
    <div class="card-header">
        <h4 class="d-inline-block">Model Name : <font color="red">{{$oqalab->name_model}}</font> <br> <br> Part Number : <font color="red">{{$oqalab->part_number}}</font></h4>
    </div>   
</div>
<div class="row">
    
    @if($oqafile->st_lpc == 1)
    <div class="col-md-12 col-xl-4">
        <div class="card widget-statstic-card borderless-card">
            <div class="card-header">
                <div class="card-header-left">
                <h3 class="d-inline-block">LPC</h3>
                   <p class="p-t-10 m-b-0 text-muted ">Start time : {{date('d-m-Y H:i:s',strtotime($oqafile->lpc_startime))}}</p>
                   @php
                    if ($oqafile->lpc_update != "") {
                      $update_lpc =  date('d-m-Y H:i:s',strtotime($oqafile->lpc_update));
                   }
                   else {
                       $update_lpc = "No Data Update !";
                   }
                  @endphp
                    <p class="p-t-10 m-b-0 text-muted ">Update time : {{$update_lpc}}</p>
                </div>
            </div>
            @php
               $filelpc ='local/storage/app/public/OQA/LPC/'.$oqafile->file_lpc; 
            @endphp
            <div class="card-block">
                
                <div class="text-left">
                    <h6 class="d-inline-block">Lot number : {{$oqalab->lotnumber_lab}}</h6>
                   @php
                   if($oqalab->lpc_confirm_st  == 1)
                   {
                          $text = "bg-warning";
                          $text1 = "btn-warning";
                   }
                   else{
                         $text = "bg-danger"; 
                         $text1 = "btn-primary";
                   }
                   @endphp
                   
                   <a href="{{asset($filelpc)}}" title="download" ><span style="font-size: 150%;" class="f-right bg-success"><i class="fa fa-download" aria-hidden="true"></i></span></a>
                </div> 
            </div>
 
            <br>
        </div>
    </div>
    @endif

    <div class="modal fade" id="default-Modalelpc" tabindex="-1" role="dialog">
        <div class="modal-dialog  modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">LPC Confirm Select</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <center><div class="row">
                        <div class="col-sm">
                            <a href="#" onclick="onclicklpc(1)"><button type="button" class="btn btn-primary">&nbsp;&nbsp;<i
                                class="fa fa-thumbs-up"></i> Confirm&nbsp;&nbsp;</button></a>
                            <a href="#" onclick="onclicklpc(2)"><button type="button" class="btn btn-danger">&nbsp;&nbsp;<i
                                class="fa fa-thumbs-up"></i> Re-test&nbsp;&nbsp;</button></a>
                                
                        </div>
                  
                      </div></center>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect " data-dismiss="modal">Close</button>
                       
                </div>
            </div>
        </div>
    </div>
    
    @if($oqafile->st_sem == 1)
     <div class="col-md-6 col-xl-4">
        <div class="card widget-statstic-card borderless-card">
            <div class="card-header">
                <div class="card-header-left">
                    <h3 class="d-inline-block">SEM</h3>
                    <p class="p-t-10 m-b-0 text-muted ">Start time : {{date('d-m-Y H:i:s',strtotime($oqafile->sem_startime))}}</p>
                    @php
                    if ($oqafile->sem_update != "") {
                      $update_sem =  date('d-m-Y H:i:s',strtotime($oqafile->sem_update));
                   }
                   else {
                       $update_sem = "No Data Update !";
                   }
                  @endphp
                    <p class="p-t-10 m-b-0 text-muted ">Update time : {{$update_sem}}</p>
                </div>
            </div>
            @php
            $filesem ='local/storage/app/public/OQA/SEM/'.$oqafile->file_sem; 
            @endphp
            <div class="card-block">
                <div class="text-left">
                    <h6 class="d-inline-block">Lot number : {{$oqalab->lotnumber_lab}}</h6>
                    @php
                    if($oqalab->sem_confirm_st  == 1)
                    {
                           $text = "bg-warning";
                           $text1 = "btn-warning";
                    }
                    else{
                          $text = "bg-danger"; 
                          $text1 = "btn-primary";
                    }
                    @endphp
                <a href="{{asset($filesem)}}" title="download" ><span style="font-size: 150%;" class="f-right bg-success"><i class="fa fa-download" aria-hidden="true"></i></span></a>
                </div>
            </div> 
            <br>
        </div>
    </div>
    @endif


    <div class="modal fade" id="default-Modalesem" tabindex="-1" role="dialog">
        <div class="modal-dialog  modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">SEM Confirm Select</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <center><div class="row">
                        <div class="col-sm">
                            <a href="#" onclick="onclicksem(1)"><button type="button" class="btn btn-primary">&nbsp;&nbsp;<i
                                class="fa fa-thumbs-up"></i> Confirm&nbsp;&nbsp;</button></a>
                            <a href="#" onclick="onclicksem(2)"><button type="button" class="btn btn-danger">&nbsp;&nbsp;<i
                                class="fa fa-thumbs-up"></i> Re-test&nbsp;&nbsp;</button></a>
                                
                        </div>
                  
                      </div></center>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect " data-dismiss="modal">Close</button>
                       
                </div>
            </div>
        </div>
    </div>

    @if($oqafile->st_ic == 1)
    <div class="col-md-6 col-xl-4">
        <div class="card widget-statstic-card borderless-card">
            <div class="card-header">
                <div class="card-header-left">
                    <h3 class="d-inline-block">IC</h3>
                    <p class="p-t-10 m-b-0 text-muted ">Start time : {{date('d-m-Y H:i:s',strtotime($oqafile->ic_startime))}}</p>

                    @php
                    if ($oqafile->ic_update != "") {
                      $update_ic =  date('d-m-Y H:i:s',strtotime($oqafile->ic_update));
                   }
                   else {
                       $update_ic = "No Data Update !";
                   }
                  @endphp
                    <p class="p-t-10 m-b-0 text-muted ">Update time : {{$update_ic}}</p>
                </div>
            </div>
            @php
            $fileic ='local/storage/app/public/OQA/IC/'.$oqafile->file_ic; 
            @endphp
            <div class="card-block">
                <div class="text-left">
                    <h6 class="d-inline-block">Lot number : {{$oqalab->lotnumber_lab}}</h6>
                    @php
                    if($oqalab->ic_confirm_st  == 1)
                    {
                           $text = "bg-warning";
                           $text1 = "btn-warning";
                    }
                    else{
                          $text = "bg-danger"; 
                          $text1 = "btn-primary";
                    }
                    @endphp
                   
                <a href="{{asset($fileic)}}" title="download" ><span style="font-size: 150%;" class="f-right bg-success"><i class="fa fa-download" aria-hidden="true"></i></span></a>
                </div>
            </div>
            
            <br>
        </div>
    </div>
    @endif
    <div class="modal fade" id="default-Modaleic" tabindex="-1" role="dialog">
        <div class="modal-dialog  modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">IC Confirm Select</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <center><div class="row">
                        <div class="col-sm">
                            <a href="#" onclick="onclickic(1)"><button type="button" class="btn btn-primary">&nbsp;&nbsp;<i
                                class="fa fa-thumbs-up"></i> Confirm&nbsp;&nbsp;</button></a>
                            <a href="#" onclick="onclickic(2)"><button type="button" class="btn btn-danger">&nbsp;&nbsp;<i
                                class="fa fa-thumbs-up"></i> Re-test&nbsp;&nbsp;</button></a>
                                
                        </div>
                  
                      </div></center>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect " data-dismiss="modal">Close</button>
                       
                </div>
            </div>
        </div>
    </div>

    @if($oqafile->st_gcms == 1)
    <div class="col-md-6 col-xl-4">
        <div class="card widget-statstic-card borderless-card">
            <div class="card-header">
                <div class="card-header-left">
                    <h3 class="d-inline-block">GCMS</h3>
                    <p class="p-t-10 m-b-0 text-muted ">Start time : {{date('d-m-Y H:i:s',strtotime($oqafile->gcms_startime))}}</p>
                    @php
                    if ($oqafile->gcms_update != "") {
                      $update_gcms =  date('d-m-Y H:i:s',strtotime($oqafile->gcms_update));
                   }
                   else {
                       $update_gcms = "No Data Update !";
                   }
                  @endphp
                    <p class="p-t-10 m-b-0 text-muted ">Update time : {{$update_gcms}}</p>
                </div>
            </div>
            @php
            $filegcms ='local/storage/app/public/OQA/GCMS/'.$oqafile->file_gcms; 
            @endphp
            <div class="card-block">
                <div class="text-left">
                    <h6 class="d-inline-block">Lot number : {{$oqalab->lotnumber_lab}}</h6>  
                    @php
                    if($oqalab->gcms_confirm_st  == 1)
                    {
                           $text = "bg-warning";
                           $text1 = "btn-warning";
                    }
                    else{
                          $text = "bg-danger"; 
                          $text1 = "btn-primary";
                    }
                    @endphp
                    <a href="{{asset($filegcms)}}" title="download" ><span style="font-size: 150%;" class="f-right bg-success"><i class="fa fa-download" aria-hidden="true"></i></span></a>
                </div>
            </div>
            
            <br>
        </div>
    </div>
    @endif 
    <div class="modal fade" id="default-Modalegcms" tabindex="-1" role="dialog">
        <div class="modal-dialog  modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">GCMS Confirm Select</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <center><div class="row">
                        <div class="col-sm">
                            <!-- <a href="#" onclick="onclickgcms(1)"><button type="button" class="btn btn-primary">&nbsp;&nbsp;<i
                                class="fa fa-thumbs-up"></i> Confirm&nbsp;&nbsp;</button></a>
                            <a href="#" onclick="onclickgcms(2)"><button type="button" class="btn btn-danger">&nbsp;&nbsp;<i
                                class="fa fa-thumbs-up"></i> Re-test&nbsp;&nbsp;</button></a> -->
                                
                            <a href="#" onclick="onclicknewgcms(1)"><button type="button" class="btn btn-primary">&nbsp;&nbsp;<i
                                class="fa fa-thumbs-up"></i> Confirm&nbsp;&nbsp;</button></a>
                            <a href="#" onclick="onclicknewgcms(2)"><button type="button" class="btn btn-danger">&nbsp;&nbsp;<i
                                class="fa fa-thumbs-up"></i> Re-test&nbsp;&nbsp;</button></a>
                        </div>
                  
                      </div></center>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect " data-dismiss="modal">Close</button>
                       
                </div>
            </div>
        </div>
    </div>


    @if($oqafile->st_ftir == 1)
    <div class="col-md-6 col-xl-4" aria-disabled="">
        <div class="card widget-statstic-card borderless-card">
            <div class="card-header">
                <div class="card-header-left">
                   
                    <h3 class="d-inline-block">FTIR</h3>
                    <p class="p-t-10 m-b-0 text-muted ">Start time : {{date('d-m-Y H:i:s',strtotime($oqafile->ftir_startime))}}</p>
                    @php
                      if ($oqafile->ftir_update != "") {
                        $update_frir =  date('d-m-Y H:i:s',strtotime($oqafile->ftir_update));
                     }
                     else {
                         $update_frir = "No Data Update !";
                     }
                    @endphp
                    <p class="p-t-10 m-b-0 text-muted ">Update time : {{$update_frir }}</p>
                </div>
            </div>
            @php
            $fileftir ='local/storage/app/public/OQA/FTIR/'.$oqafile->file_ftir; 
            @endphp
            <div class="card-block">
                <div class="text-left">
                    <h6 class="d-inline-block">Lot number : {{$oqalab->lotnumber_lab}}</h6>  
                    @php
                    if($oqalab->ftir_confirm_st  == 1)
                    {
                           $text = "bg-warning";
                           $text1 = "btn-warning";
                    }
                    else{
                          $text = "bg-danger"; 
                          $text1 = "btn-primary";
                    }
                    @endphp                 
                    <a href="{{asset($fileftir)}}" title="download" download><span style="font-size: 150%;" class="f-right bg-success"><i class="fa fa-download" aria-hidden="true"></i></span></a>
                </div>
            </div>
            <br>
        </div>
    </div> 
    @endif

    <div class="modal fade" id="default-Modaleftir" tabindex="-1" role="dialog">
        <div class="modal-dialog  modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">FTIR Confirm Select</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <center><div class="row">
                        <div class="col-sm">
                            <a href="#" onclick="onclickftir(1)"><button type="button" class="btn btn-primary">&nbsp;&nbsp;<i
                                class="fa fa-thumbs-up"></i> Confirm&nbsp;&nbsp;</button></a>
                            <a href="#" onclick="onclickftir(2)"><button type="button" class="btn btn-danger">&nbsp;&nbsp;<i
                                class="fa fa-thumbs-up"></i> Re-test&nbsp;&nbsp;</button></a>
                                
                        </div>
                  
                      </div></center>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect " data-dismiss="modal">Close</button>
                       
                </div>
            </div>
        </div>
    </div>

    @if($oqafile->st_semtest == 1)
    <div class="col-md-6 col-xl-4" aria-disabled="">
        <div class="card widget-statstic-card borderless-card">
            <div class="card-header">
                <div class="card-header-left">
                   
                    <h3 class="d-inline-block">SEM (Ghost) </h3>
                    <p class="p-t-10 m-b-0 text-muted ">Start time : {{date('d-m-Y H:i:s',strtotime($oqafile->semtest_startime))}}</p>
                    @php
                      if ($oqafile->semtest_update != "") {
                        $update_frir =  date('d-m-Y H:i:s',strtotime($oqafile->semtest_update));
                     }
                     else {
                         $update_frir = "No Data Update !";
                     }
                    @endphp
                    <p class="p-t-10 m-b-0 text-muted ">Update time : {{$update_frir }}</p>
                </div>
            </div>
            @php
            $filesemtest ='local/storage/app/public/OQA/GhostTest/'.$oqafile->file_semtest; 
            @endphp
            <div class="card-block">
                <div class="text-left">
                    <h6 class="d-inline-block">Lot number : {{$oqalab->lotnumber_lab}}</h6>  
                    @php
                    if($oqalab->semtest_confirm_st  == 1)
                    {
                           $text = "bg-warning";
                           $text1 = "btn-warning";
                    }
                    else{
                          $text = "bg-danger"; 
                          $text1 = "btn-primary";
                    }
                    @endphp            
                <a href="{{asset($filesemtest)}}" title="download" download><span style="font-size: 150%;" class="f-right bg-success"><i class="fa fa-download" aria-hidden="true"></i></span></a>
                </div>
            </div> 
            <br>
        </div>
    </div> 
    @endif

    <div class="modal fade" id="default-Modalesemtest" tabindex="-1" role="dialog">
        <div class="modal-dialog  modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">SEM (Ghost) Confirm Select</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <center><div class="row">
                        <div class="col-sm">
                            <a href="#" onclick="onclicksemtest(1)"><button type="button" class="btn btn-primary">&nbsp;&nbsp;<i
                                class="fa fa-thumbs-up"></i> Confirm&nbsp;&nbsp;</button></a>
                            <a href="#" onclick="onclicksemtest(2)"><button type="button" class="btn btn-danger">&nbsp;&nbsp;<i
                                class="fa fa-thumbs-up"></i> Re-test&nbsp;&nbsp;</button></a>
                                
                        </div>
                  
                      </div></center>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect " data-dismiss="modal">Close</button>
                       
                </div>
            </div>
        </div>
    </div>



    <input type="hidden" value="{{$oqalab->id_createlab}}" id="id_createlab" name ="id_createlab">
    
</div>
@endsection

@section('scripts')
<script>

function chkmodelst() {
    var id = $("#chkmodel").val();
    $.ajax({
        type:'GET',
        url:"{{ url('chkmodel') }}",
        data: {
            'id': id,
        },
        success:function(data){
            $("#txt_part").val(data.model.part);

        }
    });
}
function modalelpc(id)
{
    $('#default-Modalelpc').modal('show');
}
function modalesem(id)
{
    $('#default-Modalesem').modal('show');
}
function modaleic(id)
{
    $('#default-Modaleic').modal('show');
}
function modalegcms(id)
{
    $('#default-Modalegcms').modal('show');
}
function modaleftir(id)
{
    $('#default-Modaleftir').modal('show');
}
function modalesemtest(id)
{
    $('#default-Modalesemtest').modal('show');
}
function popupst()
{
    $(".sweet-alert").css('background-color', 'white');
    $(".sweet-alert h2").css('color', '#575757');
    $(".sweet-alert p").css('color', '#797979');
    swal({
            title: "Are you sure?",
            text: "Confirm Create Incoming LAB!",
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
                url: "{{ url('createlabSubmit') }}",
                data: data,
                processData: false,
                contentType: false,
                cache: false,
                success: function (data) {

                    if (data.success == 1) {
                        swal("Successed", "Submit save data successed", "success");
                        setTimeout(function () {
                            window.location.href =
                                "{{ url('OQA/incoming') }}";
                        }, 1000);
                    } else {

                    }
                }
            });


        });
}

                
                
                
                
function onclicklpc(id)
{
    $(".sweet-alert").css('background-color', 'white');
    $(".sweet-alert h2").css('color', '#575757');
    $(".sweet-alert p").css('color', '#797979');
    $('#default-Modalelpc').modal('hide');
    var id_createlab =  $('#id_createlab').val();
    swal({
            title: "Are you sure?",
            text: "Confirm save file !",
            type: "info",
            showCancelButton: true,
            closeOnConfirm: false,
            showLoaderOnConfirm: true
        },
        function () {

            var forms = $('#fm-createlab')[0];
            var data = new FormData(forms);
            $.ajax({
                type: 'get',
                url: "{{ url('chkconfirmlpc') }}",
                data: 
                {
                    'id' : id_createlab,
                    'chkid' : id
                },
                success: function (data) {
                    console.log(data);
                    if (data.success == 1) {
                        swal("Successed", "Submit save data successed", "success");
                        setTimeout(function () {
                            window.location.href = "../../OQA/oqafile/"+data.id;
                        }, 1000);
                    } 
                    else if(data.success == 2)
                    {
                        swal({
                            title: "Data NG!!",
                            text: "Please check data and re-test the sample",
                            type: "warning",
                            showCancelButton: true,
                            closeOnConfirm: false,
                            showLoaderOnConfirm: true
                        },
                        function () {
                            window.location.href = "../../OQA/oqafile/"+data.id;
                        });
                        $(".sweet-alert").css('background-color', 'red');
                        $(".sweet-alert h2").css('color', '#ffffff');
                        $(".sweet-alert p").css('color', '#ffffff');
                    }
                    else {
                        swal("warning", "Please complete all information (sheet :Seagate O19)", "warning");
                    }
                }
            });
        });
}

function onclicksem(id)
{
    $(".sweet-alert").css('background-color', 'white');
    $(".sweet-alert h2").css('color', '#575757');
    $(".sweet-alert p").css('color', '#797979');
    $('#default-Modalesem').modal('hide');
    var id_createlab =  $('#id_createlab').val();
    swal({
            title: "Are you sure?",
            text: "Confirm save file !",
            type: "info",
            showCancelButton: true,
            closeOnConfirm: false,
            showLoaderOnConfirm: true
        },
        function () {

            var forms = $('#fm-createlab')[0];
            var data = new FormData(forms);
            $.ajax({
                type: 'get',
                url: "{{ url('chkconfirmsem') }}",
                data: 
                {
                    'id' : id_createlab,
                    'chkid' : id
                },
                success: function (data) {
                    console.log(data);
                    if (data.success == 1) {
                        swal("Successed", "Submit save data successed", "success");
                        setTimeout(function () {
                            window.location.href = "../../OQA/oqafile/"+data.id;
                        }, 1000);
                    } 
                    else if(data.success == 2)
                    {

                        swal({
                            title: "Data NG!!",
                            text: "Please check data and re-test the sample",
                            type: "warning",
                            showCancelButton: true,
                            closeOnConfirm: false,
                            showLoaderOnConfirm: true
                        },
                        function () {
                            window.location.href = "../../OQA/oqafile/"+data.id;
                        });
                        $(".sweet-alert").css('background-color', 'red');
                        $(".sweet-alert h2").css('color', '#ffffff');
                        $(".sweet-alert p").css('color', '#ffffff');
                    }
                    else {
                        swal("warning", "Please complete all information (sheet :Seagate P9)", "warning");
                    }
                }
            });


        });
}


function onclickic(id)
{
    $(".sweet-alert").css('background-color', 'white');
    $(".sweet-alert h2").css('color', '#575757');
    $(".sweet-alert p").css('color', '#797979');
    $('#default-Modaleic').modal('hide');
    var id_createlab =  $('#id_createlab').val();
    swal({
            title: "Are you sure?",
            text: "Confirm save file !",
            type: "info",
            showCancelButton: true,
            closeOnConfirm: false,
            showLoaderOnConfirm: true
        },
        function () {

            var forms = $('#fm-createlab')[0];
            var data = new FormData(forms);
            $.ajax({
                type: 'get',
                url: "{{ url('chkconfirmic') }}",
                data: 
                {
                    'id' : id_createlab,
                    'chkid' : id
                },
                success: function (data) {
                    if (data.success == 1) {
                        swal("Successed", "Submit save data successed", "success");
                        setTimeout(function () {
                            window.location.href = "../../OQA/oqafile/"+data.id;
                        }, 1000);
                    }
                    else if(data.success == 2)
                    {

                        swal({
                            title: "Data NG!!",
                            text: "Please check data and re-test the sample",
                            type: "warning",
                            showCancelButton: true,
                            closeOnConfirm: false,
                            showLoaderOnConfirm: true
                        },
                        function () {
                            window.location.href = "../../OQA/oqafile/"+data.id;
                        });
                        $(".sweet-alert").css('background-color', 'red');
                        $(".sweet-alert h2").css('color', '#ffffff');
                        $(".sweet-alert p").css('color', '#ffffff');
                    }
                     else {
                        swal("warning", "Please complete all information (sheet :Seagate I23,I25,S24)", "warning");
                    }
                }
            });


        });
}


function onclickgcms(id)
{
    $(".sweet-alert").css('background-color', 'white');
    $(".sweet-alert h2").css('color', '#575757');
    $(".sweet-alert p").css('color', '#797979');
    $('#default-Modalegcms').modal('hide');
    var id_createlab =  $('#id_createlab').val();
    swal({
            title: "Are you sure?",
            text: "Confirm save file !",
            type: "info",
            showCancelButton: true,
            closeOnConfirm: false,
            showLoaderOnConfirm: true
        },
        function () {

            var forms = $('#fm-createlab')[0];
            var data = new FormData(forms);
            $.ajax({
                type: 'get',
                url: "{{ url('chkconfirmgcms') }}",
                data: 
                {
                    'id' : id_createlab,
                    'chkid' : id
                },
                success: function (data) {
                    // console.log(data);
                    if (data.success == 1) {
                        swal("Successed", "Submit save data successed", "success");
                        setTimeout(function () {
                            window.location.href = "../../OQA/oqafile/"+data.id;
                        }, 1000);
                    } 
                    else if(data.success == 2)
                    {
                        swal({
                            title: "Data NG!!",
                            text: "Please check data and re-test the sample",
                            type: "warning",
                            showCancelButton: true,
                            closeOnConfirm: false,
                            showLoaderOnConfirm: true
                        },
                        function () {
                            window.location.href = "../../OQA/oqafile/"+data.id;
                        });
                        $(".sweet-alert").css('background-color', 'red');
                        $(".sweet-alert h2").css('color', '#ffffff');
                        $(".sweet-alert p").css('color', '#ffffff');
                    }
                    else {
                        swal("warning", "Please complete all information (sheet :Seagate E75)", "warning");
                    }
                }
            });


        });
}

// Edited to the new onclickgcms, starting use on 13/12/23
function onclicknewgcms(id)
{
    {
    $(".sweet-alert").css('background-color', 'white');
    $(".sweet-alert h2").css('color', '#575757');
    $(".sweet-alert p").css('color', '#797979');
    $('#default-Modalegcms').modal('hide');
    var id_createlab =  $('#id_createlab').val();
    swal({
            title: "Are you sure?",
            text: "Confirm save file !",
            type: "info",
            showCancelButton: true,
            closeOnConfirm: false,
            showLoaderOnConfirm: true
        },
        function () {

            var forms = $('#fm-createlab')[0];
            var data = new FormData(forms);

            $.ajax({
                type: 'get',
                url: "{{ url('newchkconfirmgcms') }}",
                data: 
                {
                    'id' : id_createlab,
                    'chkid' : id
                },
                success: function (data) {
                    // console.log(data);
                    if (data.success == 1) {
                        swal("Successed", "Submit save data successed", "success");
                        setTimeout(function () {
                            window.location.href = "../../OQA/oqafile/"+data.id;
                        }, 1000);
                    } 
                    else if(data.success == 2)
                    {
                        swal({
                            title: "Data NG!!",
                            text: "Please check data and re-test the sample",
                            type: "warning",
                            showCancelButton: true,
                            closeOnConfirm: false,
                            showLoaderOnConfirm: true
                        },
                        function () {
                            window.location.href = "../../OQA/oqafile/"+data.id;
                        });
                        $(".sweet-alert").css('background-color', 'red');
                        $(".sweet-alert h2").css('color', '#ffffff');
                        $(".sweet-alert p").css('color', '#ffffff');
                    }
                    else {
                        swal("warning", "Please complete all information (sheet :Seagate E75)", "warning");
                    }
                }
            });


        });
}

}

function onclickftir(id)
{
    $(".sweet-alert").css('background-color', 'white');
    $(".sweet-alert h2").css('color', '#575757');
    $(".sweet-alert p").css('color', '#797979');
    $('#default-Modaleftir').modal('hide');
    var id_createlab =  $('#id_createlab').val();
    swal({
            title: "Are you sure?",
            text: "Confirm save file !",
            type: "info",
            showCancelButton: true,
            closeOnConfirm: false,
            showLoaderOnConfirm: true
        },
        function () {

            var forms = $('#fm-createlab')[0];
            var data = new FormData(forms);
            $.ajax({
                type: 'get',
                url: "{{ url('chkconfirmftir') }}",
                data: 
                {
                    'id' : id_createlab,
                    'chkid' : id
                },
                success: function (data) {
                    if (data.success == 1) {
                        swal("Successed", "Submit save data successed", "success");
                        setTimeout(function () {
                            window.location.href = "../../OQA/oqafile/"+data.id;
                        }, 1000);
                    } 
                    
                    else if(data.success == 2)
                    {

                        swal({
                            title: "Data NG!!",
                            text: "Please check data and re-test the sample",
                            type: "warning",
                            showCancelButton: true,
                            closeOnConfirm: false,
                            showLoaderOnConfirm: true
                        },
                        function () {
                            window.location.href = "../../OQA/oqafile/"+data.id;
                        });
                        $(".sweet-alert").css('background-color', 'red');
                        $(".sweet-alert h2").css('color', '#ffffff');
                        $(".sweet-alert p").css('color', '#ffffff');
                    }
                    else {
                        swal("warning", "Please complete all information (sheet :Seagate B42)", "warning");
                    }
                }
            });


        });
}

function onclicksemtest(id)
{
    $(".sweet-alert").css('background-color', 'white');
    $(".sweet-alert h2").css('color', '#575757');
    $(".sweet-alert p").css('color', '#797979');
    $('#default-Modalesemtest').modal('hide');
    var id_createlab =  $('#id_createlab').val();
    swal({
            title: "Are you sure?",
            text: "Confirm save file !",
            type: "info",
            showCancelButton: true,
            closeOnConfirm: false,
            showLoaderOnConfirm: true
        },
        function () {

            var forms = $('#fm-createlab')[0];
            var data = new FormData(forms);
            $.ajax({
                type: 'get',
                url: "{{ url('chkconfirmsemtest') }}",
                data: 
                {
                    'id' : id_createlab,
                    'chkid' : id
                },
                success: function (data) {
                    if (data.success == 1) {
                        swal("Successed", "Submit save data successed", "success");
                        setTimeout(function () {
                            window.location.href = "../../OQA/oqafile/"+data.id;
                        }, 1000);
                    } 
                    
                    else if(data.success == 2)
                    {

                        swal({
                            title: "Data NG!!",
                            text: "Please check data and re-test the sample",
                            type: "warning",
                            showCancelButton: true,
                            closeOnConfirm: false,
                            showLoaderOnConfirm: true
                        },
                        function () {
                            window.location.href = "../../OQA/oqafile/"+data.id;
                        });
                        $(".sweet-alert").css('background-color', 'red');
                        $(".sweet-alert h2").css('color', '#ffffff');
                        $(".sweet-alert p").css('color', '#ffffff');
                    }
                    else {
                        swal("warning", "Please complete all information (sheet :Seagate H10)", "warning");
                    }
                }
            });


        });
}
 

$('#submitcreatelab').click(function() {
    $(".sweet-alert").css('background-color', 'white');
    $(".sweet-alert h2").css('color', '#575757');
    $(".sweet-alert p").css('color', '#797979');
    swal({
            title: "Are you sure?",
            text: "Confirm Create Incoming LAB!",
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
                url: "{{ url('createlabSubmit') }}",
                data: data,
                processData: false,
                contentType: false,
                cache: false,
                success: function (data) {

                    if (data.success == 1) {
                        swal("Successed", "Submit save data successed", "success");
                        setTimeout(function () {
                            window.location.href =
                                "{{ url('OQA/incoming') }}";
                        }, 1000);
                    } else {

                    }
                }
            });
        });
});
</script>
@endsection
