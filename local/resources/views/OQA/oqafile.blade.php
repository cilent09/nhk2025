

@extends('layouts.main')
@section('title', 'Edit Incoming LAB')
@section('breadcrumb-title-href', '../incoming')
@section('breadcrumb-title', 'Incoming LAB')
@section('breadcrumb-sub', 'Edit Incoming LAB')

@section('content')
<div class="row">
    <div class="col-md-12 col-xl-4">
        <div class="card">
            <div class="card-header">
                <h4 class="d-inline-block">Model Name : <font color="red">{{$oqalab->name_model}}</font> <br> <br> Part Number : <font color="red">{{$oqalab->part_number}}</font></h4>
                <br><br><br><br>
            </div>   
        </div>
    </div>
    <!-- 8.Edit icon OQA -->
    <div class="col-md-12 col-xl-8">
        <div class="card">
            <div class="card-header">
                <table style=" width: 100%;">
                    <tr>
                        <td>
                            <h4 class="d-inline-block">ICON Status :</h4>
                        </td>
                        <td style=" text-align: center; width: 110px;">
                            <h4 class="d-inline-block"><img src="{{ asset('template/assets/images/item/btn_download.png')}}" alt="Download" style=" width: 100px; "></h4>
                        </td>
                        <td style="">
                            <h4 class="d-inline-block"> Download</h4>
                        </td>
                        <td style=" text-align: center; width: 110px;">
                            <h4 class="d-inline-block"><img src="{{ asset('template/assets/images/item/btn_upload_orange.png')}}" alt="Upload" style=" width: 100px; "></h4>
                        </td>
                        <td style="">
                            <h4 class="d-inline-block">Before Upload</h4>
                        </td>
                        <td style=" text-align: center; width: 110px;">
                            <h4 class="d-inline-block"><img src="{{ asset('template/assets/images/item/btn_con_orange.png')}}" alt="Confirm" style=" width: 100px; "></h4>
                        </td>
                        <td style="">
                            <h4 class="d-inline-block">Before Confirm</h4>
                        
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td style=" text-align: center; width: 110px;">
                            <h4 class="d-inline-block"><img src="{{ asset('template/assets/images/item/btn_upload_green.png')}}" alt="Upload" style=" width: 100px; "></h4>
                        </td>
                        <td style="">
                            <h4 class="d-inline-block"> After Upload</h4>
                        </td>
                        <td style=" text-align: center; width: 110px;">
                            <h4 class="d-inline-block"><img src="{{ asset('template/assets/images/item/btn_con_green.png')}}" alt="Confirm" style=" width: 100px; "></h4>
                        </td>
                        <td style="">
                            <h4 class="d-inline-block"> After Confirm</h4>
                        </td>
                    </tr>
                </table>
            </div>   
        </div>
    </div>
    <!-- 8.Edit icon OQA -->
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
                    <!-- * 4.SGT AML return -->
                    <p class="p-t-10 m-b-0 text-muted">AML : {{$oqalab->aml_lpc}}</p>
                    <!-- * 4.SGT AML return -->
                </div>
            </div>
            @php
               $filelpc ='local/storage/app/public/OQA/LPC/'.$oqafile->file_lpc; 
            @endphp
            <div class="card-block">
                <div class="text-left">
                    <h6 class="d-inline-block">Lot number : {{$oqalab->lotnumber_lab}}</h6>
                    @php	
                        $filemallpc ='local/storage/app/public/OQA/JNHK/LPC/'.@$aml->file_jnhk_lpc; 
                    @endphp
                    <!-- @php
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
                    <a href="{{url('OQA/uploadoqafile/'.$oqalab->id_createlab.'/'.'LPC')}}" title="upload">
                        <span style="font-size: 150%;" class="f-right {{$text}}"><i class="fa fa-upload" aria-hidden="true"></i></span> 
                    </a>
                    <span>&nbsp;&nbsp;&nbsp;</span>
                    <a href="{{asset($filelpc)}}" title="download" >
                        <span style="font-size: 150%;" class="f-right bg-success"><i class="fa fa-file-excel-o" aria-hidden="true"></i></span> 
                    </a>
                    <i class="fa fa-file-excel-o st-icon bg-primary"></i> -->
                </div>

                <!-- 8.Edit icon OQA -->
                <div class="text-center mt-3">
                    <a href="{{asset($filelpc)}}" >
                        <img src="{{ asset('template/assets/images/item/btn_download.png')}}" alt="Download" style=" width: 100px; ">
                    </a>
                    <a href="{{url('OQA/uploadoqafile/'.$oqalab->id_createlab.'/'.'LPC')}}" >
                        @if($oqalab->lpc_confirm_st  == 1)
                        <img src="{{ asset('template/assets/images/item/btn_upload_green.png')}}" alt="Download" style=" width: 100px; ">
                        @else
                        <img src="{{ asset('template/assets/images/item/btn_upload_orange.png')}}" alt="Download" style=" width: 100px; ">
                        @endif
                    </a>
                    <a href="#" onclick="modalelpc({{$oqalab->id_createlab}})" >
                    @if($oqalab->lpc_confirm_st == 1)
                    <img src="{{ asset('template/assets/images/item/btn_con_green.png')}}" style=" width: 100px; ">
                    @else
                    <img src="{{ asset('template/assets/images/item/btn_con_orange.png')}}" style=" width: 100px; ">
                    @endif
                    </a>
                    <a href="#" onclick="modalelpcJNHK({{$oqalab->id_createlab}})">
                        @if($aml)
                            @if($aml->file_jnhk_lpc)
                                <img src="{{ asset('template/assets/images/item/btn_JNHK_pass.png')}}" alt="JNHK" style=" width: 100px; ">
                            @else
                                <img src="{{ asset('template/assets/images/item/btn_JNHK_defualt.png')}}" alt="JNHK1" style=" width: 100px; ">
                            @endif
                        @else
                            <img src="{{ asset('template/assets/images/item/btn_JNHK_defualt.png')}}" alt="JNHK2" style=" width: 100px; ">
                        @endif
                    </a>
                </div>
                <!-- 8.Edit icon OQA -->

            </div>
            <!-- <div class="d-flex justify-content-center">
                <button onclick="modalelpc({{$oqalab->id_createlab}})" type="button" class="btn {{$text1}}">confirm</button>
            </div> -->
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
                            <a href="#" onclick="onclicklpc(1)"><button type="button" class="btn btn-primary">&nbsp;&nbsp;1st measurement&nbsp;&nbsp;</button></a>
                            <a href="#" onclick="onclicklpc(2)"><button type="button" class="btn btn-danger">&nbsp;&nbsp;2nd measurement&nbsp;&nbsp;</button></a>
                                
                        </div>
                  
                      </div></center>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect " data-dismiss="modal">Close</button>
                       
                </div>
            </div>
        </div>
    </div>

    <!-- 9.Submit AML to JNHK_r01 -->
    <form method="post" id="fm-lpcJNHK" >
    {{ csrf_field() }}
        <div class="modal fade" id="default-lpcJNHK" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">

                <input type="hidden" id="id_createlab" name="id_createlab" value="{{ $oqalab->id_createlab }}">

                <div class="modal-content">
                    <div class="modal-header"> 
                        <table style=" width: 100%;">
                            <tr>
                                <td style=" width: 40%;">
                                    <h4 class="d-inline-block">LPC AML</h4>
                                </td>
                                <td style=" width: 50%;">
                                    @if($aml)
                                        @if($aml->file_jnhk_lpc != null)
                                        <img src="{{ asset('template/assets/images/item/btn_upload_green.png')}}" alt="Upload" style=" width: 50px; ">&nbsp;&nbsp;Upload file <br>
                                        @else
                                        <img src="{{ asset('template/assets/images/item/btn_upload_orange.png')}}" alt="Upload" style=" width: 50px; ">&nbsp;&nbsp;Upload file <br>
                                        @endif
                                    @else
                                    <img src="{{ asset('template/assets/images/item/btn_upload_orange.png')}}" alt="Upload" style=" width: 50px; ">&nbsp;&nbsp;Upload file <br>
                                    @endif

                                    <img src="{{ asset('template/assets/images/item/btn_download.png')}}" alt="Download" style=" width: 50px; ">&nbsp;&nbsp;Download file
                                </td>
                                <td style=" width: 40%;">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <i class="fa fa-times" aria-hidden="true"></i>
                                    </button>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-3">
                                <center><h4 class="d-inline-block">AML file</h4></center>
                            </div>
                            <div class="col-9">
                                @if($aml)
                                    @if($aml->file_jnhk_lpc != null)
                                    <img id="clickableImageLPC" src="{{ asset('template/assets/images/item/btn_upload_green.png')}}" alt="Upload" style=" width: 80px; ">
                                    @else
                                    <img id="clickableImageLPC" src="{{ asset('template/assets/images/item/btn_upload_orange.png')}}" alt="Upload" style=" width: 80px; ">
                                    @endif
                                @else
                                <img id="clickableImageLPC" src="{{ asset('template/assets/images/item/btn_upload_orange.png')}}" alt="Upload" style=" width: 80px; ">
                                @endif

                                <a href="#" onclick="download_jnhk()">
                                    <img src="{{ asset('template/assets/images/item/btn_download.png')}}" alt="Download" style=" width: 80px; ">
                                </a>
                            </div>
                            <input type="file" id="fileInput_LPC" name="fileInput_LPC" style="display: none;"  accept=".xls,.xlsx,.xlsm">
                    </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal" onClick="window.location.reload();">Close</button>
                        <button type="submit" id="btnsave_LPC" style="display:none;">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <form method="post" id="fm-gcmsJNHK" >
    {{ csrf_field() }}
        <div class="modal fade" id="default-gcmsJNHK" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">

                <input type="hidden" id="id_createlab" name="id_createlab" value="{{ $oqalab->id_createlab }}">
                @php
                    $filemalGCMS ='local/storage/NHK-File/OQA/AML/GCMS/'.@$aml->file_gcms; 
                    $filemalGCMSzip ='local/storage/app/public/OQA/JNHK/GCMS/'.@$aml->gcms_zip_file; 
                @endphp
                <div class="modal-content">
                    <div class="modal-header"> 
                        <table style=" width: 100%;">
                            <tr>
                                <td style=" width: 40%;">
                                    <h4 class="d-inline-block">GCMS AML</h4>
                                </td>
                                <td style=" width: 50%;">
                                    @if($aml)
                                        @if($aml->file_gcms != null && $aml->gcms_zip_file != null)
                                        <img src="{{ asset('template/assets/images/item/btn_upload_green.png')}}" alt="Upload" style=" width: 50px; ">&nbsp;&nbsp;Upload file <br>
                                        @else
                                        <img src="{{ asset('template/assets/images/item/btn_upload_orange.png')}}" alt="Upload" style=" width: 50px; ">&nbsp;&nbsp;Upload file <br>
                                        @endif
                                    @else
                                    <img src="{{ asset('template/assets/images/item/btn_upload_orange.png')}}" alt="Upload" style=" width: 50px; ">&nbsp;&nbsp;Upload file <br>
                                    @endif
                                    <img src="{{ asset('template/assets/images/item/btn_download.png')}}" alt="Download" style=" width: 50px; ">&nbsp;&nbsp;Download file
                                </td>
                                <td style=" width: 40%;">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <i class="fa fa-times" aria-hidden="true"></i>
                                    </button>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-4">
                                <center><h4 class="d-inline-block">AML file</h4></center>
                            </div> 
                            <div class="col-8">
                                @if($aml)
                                    @if($aml->file_gcms != null)
                                    <img id="clickableImageGCMS" src="{{ asset('template/assets/images/item/btn_upload_green.png')}}" alt="Upload" style=" width: 80px; ">
                                    @else
                                    <img id="clickableImageGCMS" src="{{ asset('template/assets/images/item/btn_upload_orange.png')}}" alt="Upload" style=" width: 80px; ">
                                    @endif
                                @else
                                <img id="clickableImageGCMS" src="{{ asset('template/assets/images/item/btn_upload_orange.png')}}" alt="Upload" style=" width: 80px; ">
                                @endif

                                <a href="{{asset($filemalGCMS)}}">
                                    <img src="{{ asset('template/assets/images/item/btn_download.png')}}" alt="Download" style=" width: 80px; ">
                                </a>
                            </div>
                            <input type="file" id="fileInput_GCMS" name="fileInput_GCMS" style="display: none;"  accept=".xls,.xlsx,.xlsm">
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-4">
                                <center><h4 class="d-inline-block">MS Folder</h4></center>
                            </div> 
                            <div class="col-8">
                                @if($aml)
                                    @if($aml->gcms_zip_file != null)
                                    <img id="clickableImageGCMS2" src="{{ asset('template/assets/images/item/btn_upload_green.png')}}" alt="Upload" style=" width: 80px; ">
                                    @else
                                    <img id="clickableImageGCMS2" src="{{ asset('template/assets/images/item/btn_upload_orange.png')}}" alt="Upload" style=" width: 80px; ">
                                    @endif
                                @else
                                    <img id="clickableImageGCMS2" src="{{ asset('template/assets/images/item/btn_upload_orange.png')}}" alt="Upload" style=" width: 80px; ">
                                @endif

                                <a href="{{asset($filemalGCMSzip)}}">
                                    <img src="{{ asset('template/assets/images/item/btn_download.png')}}" alt="Download" style=" width: 80px; ">
                                </a>
                            </div>
                            <input type="file" id="zipFile" name="zipFile" style="display: none;"  accept=".zip">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                        <button type="button" id="btnsave_GCMS_check" class="btn btn-success waves-effect">Save</button>
                        <button type="submit" id="btnsave_gcms" style="display:none;">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!-- 9.Submit AML to JNHK_r01 -->
    
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
                    <p class="p-t-10 m-b-0" style="color:white;">AML</p>
                    
                </div>
            </div>
            @php
            $filesem ='local/storage/app/public/OQA/SEM/'.$oqafile->file_sem; 
            @endphp
            <div class="card-block"> 
                <div class="text-left">
                    <h6 class="d-inline-block">Lot number : {{$oqalab->lotnumber_lab}}</h6>
                    <!-- @php
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
                   
                    <a href="{{url('OQA/uploadoqafile/'.$oqalab->id_createlab.'/'.'SEM')}}"  title="upload" ><span style="font-size: 150%;" class="f-right {{$text}}"><i class="fa fa-upload" aria-hidden="true"></i></span></a>
                    <span>&nbsp;</span>
                    <a href="{{asset($filesem)}}" title="download" ><span style="font-size: 150%;" class="f-right bg-success"><i class="fa fa-file-excel-o" aria-hidden="true"></i></span></a> -->
                </div>
                <!-- 8.Edit icon OQA -->
                <div class="text-center mt-3">
                    <a href="{{asset($filesem)}}" >
                        <img src="{{ asset('template/assets/images/item/btn_download.png')}}" alt="Download" style=" width: 100px; ">
                    </a>
                    <a href="{{url('OQA/uploadoqafile/'.$oqalab->id_createlab.'/'.'SEM')}}" >
                        @if($oqalab->sem_confirm_st  == 1)
                        <img src="{{ asset('template/assets/images/item/btn_upload_green.png')}}" alt="Download" style=" width: 100px; ">
                        @else
                        <img src="{{ asset('template/assets/images/item/btn_upload_orange.png')}}" alt="Download" style=" width: 100px; ">
                        @endif
                    </a>
                    <a href="#" onclick="modalesem({{$oqalab->id_createlab}})" >
                        @if($oqalab->sem_confirm_st  == 1)
                        <img src="{{ asset('template/assets/images/item/btn_con_green.png')}}" style=" width: 100px; ">
                        @else
                        <img src="{{ asset('template/assets/images/item/btn_con_orange.png')}}" style=" width: 100px; ">
                        @endif
                    </a> 
                </div>
                <!-- 8.Edit icon OQA -->
            </div>
            <!-- <div class="d-flex justify-content-center">
                <button onclick="modalesem({{$oqalab->id_createlab}})" type="button" class="btn {{$text1}}">confirm</button>
            </div> -->
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
                            <a href="#" onclick="onclicksem(1)"><button type="button" class="btn btn-primary">&nbsp;&nbsp;1st measurement&nbsp;&nbsp;</button></a>
                            <a href="#" onclick="onclicksem(2)"><button type="button" class="btn btn-danger">&nbsp;&nbsp;2nd measurement&nbsp;&nbsp;</button></a>
                                
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
                    <p class="p-t-10 m-b-0" style="color:white;">AML</p> 
                </div>
            </div>
            @php
            $fileic ='local/storage/app/public/OQA/IC/'.$oqafile->file_ic; 
            @endphp
            <div class="card-block"> 
                <div class="text-left">
                    <h6 class="d-inline-block">Lot number : {{$oqalab->lotnumber_lab}}</h6>
                    <!-- @php
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
                   
                <a href="{{url('OQA/uploadoqafile/'.$oqalab->id_createlab.'/'.'IC')}}" title="upload"><span style="font-size: 150%;" class="f-right {{$text}}"><i class="fa fa-upload" aria-hidden="true"></i></span></a><span>&nbsp;</span><a href="{{asset($fileic)}}" title="download" ><span style="font-size: 150%;" class="f-right bg-success"><i class="fa fa-file-excel-o" aria-hidden="true"></i></span></a> -->
                </div>
                <!-- 8.Edit icon OQA -->
                <div class="text-center mt-3">
                    <a href="{{asset($fileic)}}" >
                        <img src="{{ asset('template/assets/images/item/btn_download.png')}}" alt="Download" style=" width: 100px; ">
                    </a>
                    <a href="{{url('OQA/uploadoqafile/'.$oqalab->id_createlab.'/'.'IC')}}" >
                        @if($oqalab->ic_confirm_st  == 1)
                        <img src="{{ asset('template/assets/images/item/btn_upload_green.png')}}" alt="Download" style=" width: 100px; ">
                        @else
                        <img src="{{ asset('template/assets/images/item/btn_upload_orange.png')}}" alt="Download" style=" width: 100px; ">
                        @endif
                    </a>
                    <a href="#" onclick="modaleic({{$oqalab->id_createlab}})" >
                        @if($oqalab->ic_confirm_st  == 1)
                        <img src="{{ asset('template/assets/images/item/btn_con_green.png')}}" style=" width: 100px; ">
                        @else
                        <img src="{{ asset('template/assets/images/item/btn_con_orange.png')}}" style=" width: 100px; ">
                        @endif
                    </a> 
                </div>
                <!-- 8.Edit icon OQA -->
            </div>
            <!-- <div class="d-flex justify-content-center">
                <button onclick="modaleic({{$oqalab->id_createlab}})" type="button" class="btn {{$text1}}">confirm</button>
            </div> -->
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
                            <a href="#" onclick="onclickic(1)"><button type="button" class="btn btn-primary">&nbsp;&nbsp;1st measurement&nbsp;&nbsp;</button></a>
                            <a href="#" onclick="onclickic(2)"><button type="button" class="btn btn-danger">&nbsp;&nbsp;2nd measurement&nbsp;&nbsp;</button></a>
                                
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
                    <!-- * 4.SGT AML return -->
                    <p class="p-t-10 m-b-0 text-muted">AML : {{$oqalab->aml_gcms}}</p>
                    <!-- * 4.SGT AML return -->
                </div>
            </div>
            @php
                $filegcms ='local/storage/app/public/OQA/GCMS/'.$oqafile->file_gcms;  
            @endphp
            <div class="card-block"> 
                <div class="text-left">
                    <h6 class="d-inline-block">Lot number : {{$oqalab->lotnumber_lab}}</h6>  
                    <!-- @php
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
                    <a  href="{{url('OQA/uploadoqafile/'.$oqalab->id_createlab.'/'.'GCMS')}}" title="upload"><span style="font-size: 150%;" class="f-right {{$text}}"><i class="fa fa-upload" aria-hidden="true"></i></span></a><span>&nbsp;</span><a href="{{asset($filegcms)}}" title="download" ><span style="font-size: 150%;" class="f-right bg-success"><i class="fa fa-file-excel-o" aria-hidden="true"></i></span></a> -->
                </div>
                <!-- 8.Edit icon OQA -->
                <div class="text-center mt-3">
                    <a href="{{asset($filegcms)}}" >
                        <img src="{{ asset('template/assets/images/item/btn_download.png')}}" alt="Download" style=" width: 100px; ">
                    </a>
                    <a href="{{url('OQA/uploadoqafile/'.$oqalab->id_createlab.'/'.'GCMS')}}" >
                        @if($oqalab->gcms_confirm_st  == 1)
                        <img src="{{ asset('template/assets/images/item/btn_upload_green.png')}}" alt="Download" style=" width: 100px; ">
                        @else
                        <img src="{{ asset('template/assets/images/item/btn_upload_orange.png')}}" alt="Download" style=" width: 100px; ">
                        @endif
                    </a>
                    <a href="#" onclick="modalegcms({{$oqalab->id_createlab}})" >
                        @if($oqalab->gcms_confirm_st  == 1)
                        <img src="{{ asset('template/assets/images/item/btn_con_green.png')}}" style=" width: 100px; ">
                        @else
                        <img src="{{ asset('template/assets/images/item/btn_con_orange.png')}}" style=" width: 100px; ">
                        @endif
                    </a> 
                    <a href="#" onclick="modalegcmsJNHK({{$oqalab->id_createlab}})">
                        @if($aml)
                            @if($aml->file_jnhk_gcms)
                                <img src="{{ asset('template/assets/images/item/btn_JNHK_pass.png')}}" alt="JNHK" style=" width: 100px; ">
                            @else
                                <img src="{{ asset('template/assets/images/item/btn_JNHK_defualt.png')}}" alt="JNHK" style=" width: 100px; ">
                            @endif
                        @else
                            <img src="{{ asset('template/assets/images/item/btn_JNHK_defualt.png')}}" alt="JNHK" style=" width: 100px; ">
                        @endif
                    </a>
                </div>
                <!-- 8.Edit icon OQA -->
            </div>
            <!-- <div class="d-flex justify-content-center">
                <button onclick="modalegcms({{$oqalab->id_createlab}})" type="button" class="btn {{$text1}}">confirm</button> 
            </div> -->
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
                            <a href="#" onclick="onclicknewgcms(1)"><button type="button" class="btn btn-primary">&nbsp;&nbsp;1st measurement&nbsp;&nbsp;</button></a>
                            <a href="#" onclick="onclicknewgcms(2)"><button type="button" class="btn btn-danger">&nbsp;&nbsp;2nd measurement&nbsp;&nbsp;</button></a>
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
                    <p class="p-t-10 m-b-0" style="color:white;">AML</p> 
                </div>
            </div>
            @php
            $fileftir ='local/storage/app/public/OQA/FTIR/'.$oqafile->file_ftir; 
            @endphp
            <div class="card-block"> 
                <div class="text-left">
                    <h6 class="d-inline-block">Lot number : {{$oqalab->lotnumber_lab}}</h6>  
                    <!-- @php
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
                    <a href="{{url('OQA/uploadoqafile/'.$oqalab->id_createlab.'/'.'FTIR')}}" title="upload"><span style="font-size: 150%;" class="f-right {{$text}}"><i class="fa fa-upload" aria-hidden="true"></i></span></a><span>&nbsp;</span><a href="{{asset($fileftir)}}" title="download" download><span style="font-size: 150%;" class="f-right bg-success"><i class="fa fa-file-excel-o" aria-hidden="true"></i></span></a> -->
                </div>
                <!-- 8.Edit icon OQA -->
                <div class="text-center mt-3">
                    <a href="{{asset($fileftir)}}" >
                        <img src="{{ asset('template/assets/images/item/btn_download.png')}}" alt="Download" style=" width: 100px; ">
                    </a>
                    <a href="{{url('OQA/uploadoqafile/'.$oqalab->id_createlab.'/'.'FTIR')}}" >
                        @if($oqalab->ftir_confirm_st  == 1)
                        <img src="{{ asset('template/assets/images/item/btn_upload_green.png')}}" alt="Download" style=" width: 100px; ">
                        @else
                        <img src="{{ asset('template/assets/images/item/btn_upload_orange.png')}}" alt="Download" style=" width: 100px; ">
                        @endif
                    </a>
                    <a href="#" onclick="modaleftir({{$oqalab->id_createlab}})" >
                        @if($oqalab->ftir_confirm_st  == 1)
                        <img src="{{ asset('template/assets/images/item/btn_con_green.png')}}" style=" width: 100px; ">
                        @else
                        <img src="{{ asset('template/assets/images/item/btn_con_orange.png')}}" style=" width: 100px; ">
                        @endif
                    </a> 
                </div>
                <!-- 8.Edit icon OQA -->
            </div>
            <!-- <div class="d-flex justify-content-center">
                <button onclick="modaleftir({{$oqalab->id_createlab}})" type="button" class="btn {{$text1}}">confirm</button>
            </div> -->
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
                            <a href="#" onclick="onclickftir(1)"><button type="button" class="btn btn-primary">&nbsp;&nbsp;1st measurement&nbsp;&nbsp;</button></a>
                            <a href="#" onclick="onclickftir(2)"><button type="button" class="btn btn-danger">&nbsp;&nbsp;2nd measurement&nbsp;&nbsp;</button></a>
                                
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
                    <p class="p-t-10 m-b-0" style="color:white;">AML</p> 
                </div>
            </div>
            @php
            $filesemtest ='local/storage/app/public/OQA/GhostTest/'.$oqafile->file_semtest; 
            @endphp
            <div class="card-block"> 
                <div class="text-left">
                    <h6 class="d-inline-block">Lot number : {{$oqalab->lotnumber_lab}}</h6>  
                    <!-- @php
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
                    <a href="{{url('OQA/uploadoqafile/'.$oqalab->id_createlab.'/'.'GhostTest')}}" title="upload"><span style="font-size: 150%;" class="f-right {{$text}}"><i class="fa fa-upload" aria-hidden="true"></i></span></a><span>&nbsp;</span><a href="{{asset($filesemtest)}}" title="download" download><span style="font-size: 150%;" class="f-right bg-success"><i class="fa fa-file-excel-o" aria-hidden="true"></i></span></a> -->
                </div>
                <!-- 8.Edit icon OQA -->
                <div class="text-center mt-3">
                    <a href="{{asset($filesemtest)}}" >
                        <img src="{{ asset('template/assets/images/item/btn_download.png')}}" alt="Download" style=" width: 100px; ">
                    </a>
                    <a href="{{url('OQA/uploadoqafile/'.$oqalab->id_createlab.'/'.'GhostTest')}}" >
                        @if($oqalab->semtest_confirm_st  == 1)
                        <img src="{{ asset('template/assets/images/item/btn_upload_green.png')}}" alt="Download" style=" width: 100px; ">
                        @else
                        <img src="{{ asset('template/assets/images/item/btn_upload_orange.png')}}" alt="Download" style=" width: 100px; ">
                        @endif
                    </a>
                    <a href="#" onclick="modalesemtest({{$oqalab->id_createlab}})" >
                        @if($oqalab->semtest_confirm_st  == 1)
                        <img src="{{ asset('template/assets/images/item/btn_upload_green.png')}}" style=" width: 100px; ">
                        @else
                        <img src="{{ asset('template/assets/images/item/btn_upload_orange.png')}}" style=" width: 100px; ">
                        @endif
                    </a> 
                </div>
                <!-- 8.Edit icon OQA -->
            </div>
            <!-- <div class="d-flex justify-content-center"> 
                <button onclick="modalesemtest({{$oqalab->id_createlab}})" type="button" class="btn {{$text1}}">confirm</button>
            </div> -->
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
                            <a href="#" onclick="onclicksemtest(1)"><button type="button" class="btn btn-primary">&nbsp;&nbsp;1st measurement&nbsp;&nbsp;</button></a>
                            <a href="#" onclick="onclicksemtest(2)"><button type="button" class="btn btn-danger">&nbsp;&nbsp;2nd measurement&nbsp;&nbsp;</button></a>
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
                            text: data.messenger,
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
                            text: "Please check data and Out UCL the sample",
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
                            text: "Please check data and Out UCL the sample",
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
                            text: "Please check data and Out UCL the sample",
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
                            text: "Please check data and Out UCL the sample",
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
                            text: "Please check data and Out UCL the sample",
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
                            text: "Please check data and Out UCL the sample",
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

// 9.Submit AML to JNHK_r01
function modalelpcJNHK(id)
{
    $('#default-lpcJNHK').modal('show');
}
document.getElementById('clickableImageLPC').addEventListener('click', function() {
    document.getElementById('fileInput_LPC').click();
});
document.getElementById('fileInput_LPC').addEventListener('change', function(event) {
    checkfile_LPC();
}); 
function checkfile_LPC()
{
    if ($('#fileInput_LPC').get(0).files.length > 0) {
        swal({
            title: "Are you sure?",
            text: "Confirm save file !",
            background: 'red',
            type: "info",
            showCancelButton: true,
            closeOnConfirm: false,
            showLoaderOnConfirm: true
        },
        function () {
            $('#btnsave_LPC').click();
        });
    }
}
$('#fm-lpcJNHK').submit(function(e) {
    e.preventDefault();
    var formData = new FormData($(this)[0]);
    $.ajax({
        url: '{{ url('upload_lpc_jnhk') }}',
        method: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function(data) {
            console.log(data);
            if (data.success == 1) {
                swal("Successed", "save data successed", "success");
                setTimeout(function () {
                location.reload();
            }, 1000);
                } else {
                    swal("Error", data.success, "error");
                    setTimeout(function () {
                }, 1000);
            }
        }
    });
});


function modalegcmsJNHK(id)
{
    $('#default-gcmsJNHK').modal('show');
}
document.getElementById('clickableImageGCMS').addEventListener('click', function() {
    document.getElementById('fileInput_GCMS').click();
});
document.getElementById('clickableImageGCMS2').addEventListener('click', function() {
    document.getElementById('zipFile').click();
});


$( "#btnsave_GCMS_check" ).on( "click", function() {
    swal({
        title: "Are you sure?",
        text: "Confirm save file !",
        background: 'red',
        type: "info",
        showCancelButton: true,
        closeOnConfirm: false,
        showLoaderOnConfirm: true
    },
    function () {
        $('#btnsave_gcms').click();
    });
} );

$('#fm-gcmsJNHK').submit(function(e) {
    e.preventDefault();
    var formData = new FormData($(this)[0]);
    $.ajax({
        url: '{{ url('upload_gcms_jnhk') }}',
        method: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function(data) {
            console.log(data);
            if (data.success == 1) {
                swal("Successed", "save data successed", "success");
                setTimeout(function () {
                location.reload();
            }, 1000);
                } else {
                    swal("Error", "The file is incorrect.", "error");
                    setTimeout(function () {
                    location.reload();
                }, 1000);
            }
        }
    });
});

</script>
@endsection
