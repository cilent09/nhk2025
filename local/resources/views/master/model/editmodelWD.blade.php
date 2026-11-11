@extends('layouts.userlogin')
@extends('layouts.main')
@php
if($chk == 1)
{
  $text = "Edit Model";
}
else {
    $text = "Duplicate Model";
}
@endphp
@section('title', $text)
@section('breadcrumb-title-href', '../../model')
@section('breadcrumb-title', 'Model')
@section('breadcrumb-sub', $text)
@section('content')

<style>

.wizard>.steps>ul>li {
    width: 33%;
}

</style>

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    
                    {{--<span>Add class of <code>.form-control</code> with <code>&lt;input&gt;</code> tag</span>--}}
                </div>
                <div class="card-block">
                    <div class="row">
                        <div class="col-md-12">
                            <div id="wizarda">
                                <section>
                                    {{-- id="basic-forms"  --}}
                                    <form class="wizard-form" method="post"  id="example-advanced-form" action="{{url('add_modelWD')}}">
                                        {{ csrf_field() }}
                                        <h3> PAGE 1</h3>
                                        <fieldset>
                                            <input type="hidden" id="chkedit" value="{{$chk}}">

                                            <div class="form-group row">
                                                <div class="col">
                                                    <div class="col-sm-12">
                                                        <label for="name-2" class="block">Model type <span style="font-size: 16px"  class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <select id="model_type" name="model_type" class="required form-control valid">
                                                            <option  value="" >Select State</option>
                                                            <option value="WD_TNHK" {{ ($nt_model->model_type=="WD_TNHK")? 'selected' : '' }}>WD_TNHK</option>
                                                            <option value="WD_Oversea" {{ ($nt_model->model_type=="WD_Oversea")? 'selected' : '' }}>WD_Oversea</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="col-sm-12">
                                                        <label for="name-2" class="block">WD Q'ty digit <span style="font-size: 16px" class="text-danger">*</span></label>
                                                    </div>
                                                    
                                                    <div class="col-sm-12">
                                                        <input id="txt_wd_qty" name="txt_wd_qty" type="number" class="required form-control valid" value="{{$nt_model->wdqty}}" aria-required="true">
                                                    </div> 
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <div class="col">
                                                    <div class="col-sm-12">
                                                        <label for="name-2" class="block">Model <span style="font-size: 16px"  class="text-danger">*</span></label>
                                                    </div>
                                                    <input type="hidden" name="chkid_model" id="chkid_model" value="{{$nt_model->id_model}}">
                                                    <div class="col-sm-12">
                                                        <input id="txt_name_model" name="txt_name_model" type="text"
                                                            class="required form-control valid" value="{{$nt_model->name_model}}">
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="col-sm-12">
                                                        <label for="surname-2" class="block">Part UP/DN <span style="font-size: 16px"  class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        @php
                                                        $up = "";
                                                        $dn = "";
                                                        if($nt_model->part == "UP")
                                                        {
                                                        $up = "selected";
                                                        }
                                                        else if($nt_model->part == "DN")
                                                        {
                                                        $dn = "selected";
                                                        }
                                                        @endphp
                                                        <select id="txt_part" name="txt_part" class="required form-control valid">
                                                            <option value="" >Select State</option>
                                                            <option value="UP" {{$up}}>UP</option>
                                                            <option value="DN" {{$dn}}>DN</option>
    
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
    
                                            <div class="form-group row">
                                                <div class="col">
                                                    <div class="col-sm-12">
                                                        <label for="name-2" class="block">Drive Name 
                                                            <span style="font-size: 16px"  class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <input id="txt_drive_name" name="txt_drive_name" type="text"
                                                            class="required form-control valid" value="{{$nt_model->drive_name}}">
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="col-sm-12">
                                                        <label for="surname-2" class="block">Line type <span style="font-size: 16px"  class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <input id="txt_line_type" name="txt_line_type" type="text"
                                                            class="required form-control valid" value="{{$nt_model->line_type}}">
                                                    </div>
                                                </div>
                                            </div>
    
                                            <div class="form-group row">
                                                <div class="col">
                                                    <div class="col-sm-12">
                                                        <label for="surname-2" class="block">VENDOR_ID
                                                            <span style="font-size: 16px"  class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <input id="txt_vendor_id" name="txt_vendor_id" type="text"
                                                            class="required form-control valid" value="{{$nt_model->vendor_id}}">
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    
                                                    <div class="col-sm-12">
                                                        <label for="surname-2" class="block">SUB_SUPPLIER_NAME
                                                            <span style="font-size: 16px"  class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <input id="txt_ssn" name="txt_ssn" type="text"
                                                            class="required form-control valid" value="{{$nt_model->sub_supplier_name}}">
                                                    </div>
                                                </div>
                                            </div>
    
                                            <div class="form-group row">
                                                <div class="col">
                                                    <div class="col-sm-12">
                                                        <label for="name-2" class="block">Part number  <span style="font-size: 16px"  class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <input id="txt_part_number" name="txt_part_number" type="text"
                                                            class="required form-control valid"  value="{{$nt_model->part_number}}">
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="col-sm-12">
                                                        <label for="surname-2" class="block">Part revision  <span style="font-size: 16px"  class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <input id="txt_part_revision" name="txt_part_revision" type="text"
                                                            class="required form-control valid" value="{{$nt_model->part_revision}}">
                                                    </div>
    
                                                </div>
                                            </div>
    
                                            <div class="form-group row">
                                                <div class="col">
                                                    <div class="col-sm-12">
                                                        <label for="name-2" class="block">Drawing number  <span style="font-size: 16px"  class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <input id="txt_drawing_number" name="txt_drawing_number" type="text"
                                                            class="required form-control valid" value="{{$nt_model->drawing_number}}">
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="col-sm-12">
                                                        <label for="surname-2" class="block">Drawing revision  <span style="font-size: 16px"  class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <input id="txt_drawing_revision" name="txt_drawing_revision"
                                                            type="text" class="required form-control valid"
                                                            value="{{$nt_model->drawing_revision}}">
                                                    </div>
    
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <div class="col">
                                                    <div class="col-sm-12">
                                                        <label for="name-2" class="block">Digit lot number  <span style="font-size: 16px"  class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        
                                                            <div class="row">
                                                                <div class="col-sm-4">
                                                                    <input id="txt_dln" name="txt_dln" type="text"
                                                            class="required form-control valid" value="{{$nt_model->digit_lot_number}}">
                                                                </div>
                                                                <div class="col-sm-4">
                                                                    <input id="txt_dln1" name="txt_dln1" type="text"
                                                            class="form-control valid" value="{{$nt_model->digit_lot_number1}}">
                                                                </div>
                                                                <div class="col-sm-4">
                                                                    <input id="txt_dln2" name="txt_dln2" type="text"
                                                            class="form-control valid" value="{{$nt_model->digit_lot_number2}}">
                                                                </div>
                                                            </div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                        <div class="col-sm-12">
                                                            <label for="surname-2" class="block">Surface area
                                                                <div class="btn-group mr-1 mt-2">
                                                                   
                                                                </div>  
                                                             
                                                                <span style="font-size: 16px"  class="text-danger">*</span></label>
                                                        </div>
                                                        <div class="col-sm-12">
                                                            <div class="row">
                                                                <div class="col-sm-8">
                                                                 <input id="txt_sa" name="txt_sa" type="text"
                                                                class="required form-control valid" value="{{$nt_model->surface_area}}">
                                                                </div>
                                                                <div class="col-sm-4">
                                                                
                                                                    <select  class="required form-control valid" name="surface_area_unit" id="surface_area_unit">
                                                                        <option value="">- Select -</option>
                                                                        @foreach($model_area as $modelunit)

                                                                          @php
                                                                          $chk = "";
                                                                          if($nt_model->surface_area_unit == $modelunit->name)
                                                                          {
                                                                            $chk = "selected";
                                                                          }
                                                                          else {
                                                                            $chk = "";
                                                                          }
                                                                          @endphp

                                                                        <option value="{{$modelunit->name}}" {{$chk}}>{{$modelunit->name}}</option>
                                                                        @endforeach
                                                                        </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                </div>
                                            </div>
    
                                            <div class="form-group row">
                                                <div class="col">
                                                    <div class="col-sm-12">
                                                        <label for="name-2" class="block">Lot digit Q'ty  <span style="font-size: 16px"  class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <input id="txt_lot_digit_q" name="txt_lot_digit_q" type="text"
                                                            class="required form-control valid" value="{{$nt_model->lot_digit}}">
                                                    </div>
                                                </div>
                                                <div class="col">
    
                                                </div>
                                            </div>
    
                                            <div class="form-group row">
                                                <div class="col">
                                                    <div class="col-sm-12">
                                                        <label for="name-2" class="block">LPC Date correlated <span style="font-size: 16px"  class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="col-sm-12">
    
                                                        <input type="date" name="txt_alpc_dc" id="txt_alpc_dc"
                                                        value="{{$nt_model->alpc_dc}}" class="required form-control valid">
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="col-sm-12">
                                                        <label for="name-2" class="block">LPC Water vol. in tank      
                                                            <span style="font-size: 16px"  class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="row">
                                                            <div class="col-sm-8">
                                                        <input id="txt_alpc_wv" name="txt_alpc_wv" type="text"
                                                            class="required form-control valid" value="{{$nt_model->alpc_water_vol}}">
                                                            </div>
    
                                                            <div class="col-sm-4">
                                                            <select  class="required form-control valid" name="alpc_water_vol_unit" id="alpc_water_vol_unit">
                                                                <option value="">- Select -</option>
                                                                @foreach($model_volume as $modelunit)
                                                                @php
                                                                $chk = "";
                                                                if($nt_model->alpc_water_vol_unit == $modelunit->name)
                                                                {
                                                                  $chk = "selected";
                                                                }
                                                                else {
                                                                  $chk = "";
                                                                }
                                                                @endphp
                                                                
                                                                <option value="{{$modelunit->name}}" {{$chk}}>{{$modelunit->name}}</option>
                                                                @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
    
                                                </div>
                                            </div>
    
                                            <div class="form-group row">
                                                <div class="col">
                                                    <div class="col-sm-12">
                                                        <label for="surname-2" class="block">LPC Correlation site
                                                            <span style="font-size: 16px"  class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <input id="txt_alpc_cs" name="txt_alpc_cs" type="text"
                                                            class="required form-control valid"
                                                            value="{{$nt_model->alpc_cs}}">
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="col-sm-12">
                                                        <label for="surname-2" class="block">LPC Extraction vol.      
                                                            
                                                            <span style="font-size: 16px"  class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="row">
                                                            <div class="col-sm-8">
                                                        <input id="txt_alpc_ev" name="txt_alpc_ev" type="text"
                                                            class="required form-control valid" value="{{$nt_model->alpc_extraction_vol}}">
                                                            </div>
                                                            <div class="col-sm-4">
                                                            <select  class="required form-control valid" name="alpc_extraction_vol_unit" id="alpc_extraction_vol_unit">
                                                                <option value="">- Select -</option>
                                                                @foreach($model_volume as $modelunit)
                                                                
                                                                @php
                                                                $chk = "";
                                                                if($nt_model->alpc_extraction_vol_unit == $modelunit->name)
                                                                {
                                                                  $chk = "selected";
                                                                }
                                                                else {
                                                                  $chk = "";
                                                                }
                                                                @endphp
                                                                <option value="{{$modelunit->name}}" {{$chk}}>{{$modelunit->name}}</option>
                                                                @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
    
                                            <div class="form-group row">
                                                <div class="col">
                                                    <div class="col-sm-12">
                                                        <label for="surname-2" class="block">LPC Document No./Revision
                                                            <span style="font-size: 16px"
                                                                class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <input id="txt_alpc_dr" name="txt_alpc_dr" type="text"
                                                            class="required form-control valid" value="{{$nt_model->alpc_dr}}">
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="col-sm-12">
                                                        <label for="surname-2" class="block">LPC US power
                                                            <span style="font-size: 16px"
                                                                class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="row">
                                                            <div class="col-sm-8">
                                                        <input id="txt_alpc_us" name="txt_alpc_us" type="text"
                                                            class="required form-control valid" value="{{$nt_model->alpc_us_power}}">
                                                            </div>
                                                            <div class="col-sm-4">
                                                            <select class="required form-control valid"
                                                                    name="alpc_us_power_unit" id="alpc_us_power_unit">
                                                                    <option value="">- Select -</option>
                                                                    @foreach($model_electric as $modelunit)
                                                                    @php
                                                                    $chk = "";
                                                                    if($nt_model->alpc_us_power_unit == $modelunit->name)
                                                                    {
                                                                      $chk = "selected";
                                                                    }
                                                                    else {
                                                                      $chk = "";
                                                                    }
                                                                    @endphp
                                                                    <option value="{{$modelunit->name}}" {{$chk}}>
                                                                        {{$modelunit->name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
    
                                            <div class="form-group row">
                                                <div class="col">
                                                    <div class="col-sm-12">
                                                        <label for="name-2" class="block">LPC Parts used 
                                                            
                                                              <span style="font-size: 16px"  class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="row">
                                                            <div class="col-sm-8">
                                                        <input id="txt_alpc_pcs" name="txt_alpc_pcs" type="text"
                                                            class="required form-control valid" value="{{$nt_model->alpc_parts_used}}">
                                                            </div>
                                                            <div class="col-sm-4">
                                                            <select  class="required form-control valid" name="alpc_parts_used_unit" id="alpc_parts_used_unit">
                                                                <option value="">- Select -</option>
                                                                @foreach($model_part as $modelunit)
                                                                @php
                                                                $chk = "";
                                                                if($nt_model->alpc_parts_used_unit == $modelunit->name)
                                                                {
                                                                  $chk = "selected";
                                                                }
                                                                else {
                                                                  $chk = "";
                                                                }
                                                                @endphp
                                                                <option value="{{$modelunit->name}}" {{$chk}}>{{$modelunit->name}}</option>
                                                                @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="col-sm-12">
                                                        <label for="surname-2" class="block">LPC Hang or Sink  <span style="font-size: 16px"  class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <input id="txt_alpc_hang" name="txt_alpc_hang" type="text"
                                                            class="required form-control valid" value="{{$nt_model->alpc_hang}}">
                                                    </div>
                                                </div>
                                            </div>
    
                                            <div class="form-group row">
                                                <div class="col">
                                                      <div class="col-sm-12">
                                                        <label for="name-2" class="block">LPC US frequency 
                                                            <span style="font-size: 16px"  class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="row">
                                                            <div class="col-sm-8">
                                                        <input id="txt_alpc_usf" name="txt_alpc_usf" type="text"
                                                            class="required form-control valid" value="{{$nt_model->alpc_us_frequency}}">
                                                            </div>
                                                            <div class="col-sm-4">
                                                            <select  class="required form-control valid" name="alpc_us_frequency_unit" id="alpc_us_frequency_unit">
                                                                <option value="">- Select -</option>
                                                                @foreach($model_electric as $modelunit)
                                                                @php
                                                                $chk = "";
                                                                if($nt_model->alpc_us_frequency_unit == $modelunit->name)
                                                                {
                                                                  $chk = "selected";
                                                                }
                                                                else {
                                                                  $chk = "";
                                                                }
                                                                @endphp
                                                                <option value="{{$modelunit->name}}" {{$chk}}>{{$modelunit->name}}</option>
                                                                @endforeach
                                                                </select> 
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="col-sm-12">
                                                        <label for="surname-2" class="block">LPC Extraction time 
                                                            <span style="font-size: 16px"  class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="row">
                                                            <div class="col-sm-8">
                                                        <input id="txt_alpc_et" name="txt_alpc_et" type="text"
                                                            class="required form-control valid" value="{{$nt_model->alpc_extraction_time}}">
                                                            </div>
                                                            <div class="col-sm-4">
                                                            <select  class="required form-control valid" name="alpc_extraction_time_unit" id="alpc_extraction_time_unit">
                                                                <option value="">- Select -</option>
                                                                @foreach($model_time as $modelunit)
                                                                @php
                                                                $chk = "";
                                                                if($nt_model->alpc_extraction_time_unit == $modelunit->name)
                                                                {
                                                                  $chk = "selected";
                                                                }
                                                                else {
                                                                  $chk = "";
                                                                }
                                                                @endphp
                                                                <option value="{{$modelunit->name}}" {{$chk}}>{{$modelunit->name}}</option>
                                                                @endforeach
                                                                </select> 
                                                            </div>
    
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <div class="col">
                                                      <div class="col-sm-12">
                                                        <label for="name-2" class="block">LPC MDL 
                                                            <span style="font-size: 16px"  class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="row">
                                                            <div class="col-sm-8">
                                                        <input id="txt_lpc_mdl" name="txt_lpc_mdl" type="text"
                                                            class="required form-control valid" value="{{$nt_model->lpc_mdl}}">
                                                            </div>
                                                            <div class="col-sm-4">
                                                            <select  class="required form-control valid" name="lpc_mdl_unit" id="lpc_mdl_unit">
                                                                <option value="">- Select -</option>
                                                                @foreach($model_part_mdl as $modelunit)
                                                                @php
                                                                $chk = "";
                                                                if($nt_model->lpc_mdl_unit == $modelunit->name)
                                                                {
                                                                  $chk = "selected";
                                                                }
                                                                else {
                                                                  $chk = "";
                                                                }
                                                                @endphp
                                                                <option value="{{$modelunit->name}}" {{$chk}}>{{$modelunit->name}}</option>
                                                                @endforeach
                                                                </select> 
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    
                                            </div>
    
                                        </fieldset>
                                         
                                        <!-- end  PAGE 1 -->
                                        <h3> PAGE 2 </h3>

                                        <fieldset>
                                            <div class="form-group row">
                                                <div class="col">
                                                    <div class="col-sm-12">
                                                        <label for="name-2" class="block">SEM Date correlated <span style="font-size: 16px"  class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="col-sm-12">
    
                                                        <input type="date" name="txt_sem_dc" id="txt_sem_dc"
                                                        value="{{$nt_model->sem_dc}}" class="required form-control valid">
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="col-sm-12">
                                                        <label for="name-2" class="block">GCMS Date correlated <span style="font-size: 16px"  class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="col-sm-12">
    
                                                        <input type="date" name="txt_gcms_dc" id="txt_gcms_dc"
                                                        value="{{$nt_model->gcms_dc}}" class="required form-control valid">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col">
                                                    <div class="col-sm-12">
                                                        <label for="surname-2" class="block">SEM Correlation site <span style="font-size: 16px"  class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <input id="txt_sem_cs" name="txt_sem_cs" type="text"
                                                            class="required form-control valid"
                                                            value="{{$nt_model->sem_cs}}">
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="col-sm-12">
                                                        <label for="surname-2" class="block">GCMS Correlation site
                                                            <span style="font-size: 16px"  class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <input id="txt_gcms_cs" name="txt_gcms_cs" type="text"
                                                            class="required form-control valid"
                                                            value="{{$nt_model->gcms_cs}}">
                                                    </div>
                                                </div>
                                            </div>
    
                                            <div class="form-group row">
                                                <div class="col">
                                                    <div class="col-sm-12">
                                                        <label for="surname-2" class="block">SEM Document No./Revision
                                                            <span style="font-size: 16px"  class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <input id="txt_sem_dr" name="txt_sem_dr" type="text"
                                                            class="required form-control valid" value="{{$nt_model->sem_dr}}">
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="col-sm-12">
                                                        <label for="surname-2" class="block">GCMS Document No./Revision
                                                            <span style="font-size: 16px"  class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <input id="txt_gcms_dr" name="txt_gcms_dr" type="text"
                                                            class="required form-control valid" value="{{$nt_model->gcms_dr}}">
                                                    </div>
                                                </div>
                                            </div>
    
                                            <div class="form-group row">
                                                <div class="col">
                                                    <div class="col-sm-12">
                                                        <label for="name-2" class="block">SEM Filtered Solution Vol. 
                                                            <span style="font-size: 16px"  class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="row">
                                                            <div class="col-sm-8">
                                                        <input id="txt_sem_fsv" name="txt_sem_fsv" type="text"
                                                            class="required form-control valid"
                                                            value="{{$nt_model->sem_filtered_solution}}">
                                                            </div>
                                                            <div class="col-sm-4">
                                                            <select   class="required form-control valid" name="sem_filtered_solution_unit" id="sem_filtered_solution_unit">
                                                                <option value="">- Select -</option>
                                                                @foreach($model_volume as $modelunit)
                                                                @php
                                                                    $chk = "";
                                                                    if($nt_model->sem_filtered_solution_unit == $modelunit->name)
                                                                    {
                                                                        $chk = "selected";
                                                                    }
                                                                    else {
                                                                         $chk = "";
                                                                    }
                                                                @endphp
                                                                <option value="{{$modelunit->name}}" {{$chk}}>{{$modelunit->name}}</option>
                                                                @endforeach
                                                                </select> 
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="col-sm-12">
                                                        <label for="name-2" class="block">GCMS Parts used 
                                                            
                                                              <span style="font-size: 16px"  class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="row">
                                                            <div class="col-sm-8">
                                                        <input id="txt_gcms_pcs" name="txt_gcms_pcs" type="text"
                                                            class="required form-control valid" value="{{$nt_model->gcms_parts_used}}">
                                                            </div>
                                                            <div class="col-sm-4">
                                                            <select   class="required form-control valid" name="gcms_parts_used_unit" id="gcms_parts_used_unit">
                                                                <option value="">- Select -</option>
                                                                @foreach($model_part as $modelunit)
                                                                @php
                                                                $chk = "";
                                                                if($nt_model->gcms_parts_used_unit == $modelunit->name)
                                                                {
                                                                    $chk = "selected";
                                                                }
                                                                else {
                                                                     $chk = "";
                                                                }
                                                                @endphp
                                                                <option value="{{$modelunit->name}}" {{$chk}}>{{$modelunit->name}}</option>
                                                                @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
    
                                            <div class="form-group row">
                                                <div class="col">
                                                    <div class="col-sm-12">
                                                        <label for="surname-2" class="block">SEM Filter Spot Area 
                                                            
                                                            <span style="font-size: 16px"  class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="row">
                                                            <div class="col-sm-8">
                                                        <input id="txt_sem_filter" name="txt_sem_filter" type="text"
                                                            class="required form-control valid"
                                                            value="{{$nt_model->sem_filter_spot_area}}">
                                                            </div>
                                                            <div class="col-sm-4">
                                                            <select   class="required form-control valid" name="sem_filter_spot_area_unit" id="sem_filter_spot_area_unit">
                                                                <option value="">- Select -</option>

                                                                @foreach($model_area as $modelunit)
                                                                @php
                                                                $chk = "";
                                                                if($nt_model->sem_filter_spot_area_unit == $modelunit->name)
                                                                {
                                                                    $chk = "selected";
                                                                }
                                                                else {
                                                                     $chk = "";
                                                                }
                                                                @endphp
                                                                <option value="{{$modelunit->name}}" {{$chk}}>{{$modelunit->name}}</option>
                                                                @endforeach
                                                                </select> 
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="col-sm-12">
                                                        <label for="surname-2" class="block">GCMS Sampling Time 
                                                           
                                                            <span style="font-size: 16px"  class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="col-sm-12">
    
                                                        <div class="row">
                                                            <div class="col-sm-8">
                                                        <input id="txt_gcms_st" name="txt_gcms_st" type="text"
                                                            class="required form-control valid" value="{{$nt_model->gcms_sampling_time}}">
    
                                                            </div>
                                                            <div class="col-sm-4">
                                                            <select   class="required form-control valid" name="gcms_sampling_time_unit" id="gcms_sampling_time_unit">
                                                                <option value="">- Select -</option>
                                                                @foreach($model_time as $modelunit)
                                                                @php
                                                                $chk = "";
                                                                if($nt_model->gcms_sampling_time_unit == $modelunit->name)
                                                                {
                                                                    $chk = "selected";
                                                                }
                                                                else {
                                                                     $chk = "";
                                                                }
                                                                @endphp
                                                                <option value="{{$modelunit->name}}" {{$chk}}>{{$modelunit->name}}</option>
                                                                @endforeach
                                                                </select>
                                                            </div>
                                                        </div> 
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="form-group row">
                                                <div class="col">
                                                    <div class="col-sm-12">
                                                        <label for="surname-2" class="block">SEM Parts used 
                                                           
                                                              <span style="font-size: 16px"  class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="col-sm-12">
    
                                                        <div class="row">
                                                            <div class="col-sm-8">
                                                        <input id="txt_sem_pcs" name="txt_sem_pcs" type="text"
                                                            class="required form-control valid" value="{{$nt_model->sem_parts_used}}">
                                                            </div>
                                                            <div class="col-sm-4">
                                                            <select  class="required form-control valid" name="sem_parts_used_unit" id="sem_parts_used_unit">
                                                                <option value="">- Select -</option>
                                                                @foreach($model_part as $modelunit)
                                                                @php
                                                                $chk = "";
                                                                if($nt_model->sem_parts_used_unit == $modelunit->name)
                                                                {
                                                                    $chk = "selected";
                                                                }
                                                                else {
                                                                     $chk = "";
                                                                }
                                                                @endphp
                                                                <option value="{{$modelunit->name}}" {{$chk}}>{{$modelunit->name}}</option>
                                                                @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col">

                                                    <div class="col-sm-12">
                                                        <label for="surname-2" class="block">GCMS MDL
                                                           
                                                              <span style="font-size: 16px"  class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="col-sm-12">
    
                                                        <div class="row">
                                                            <div class="col-sm-8">
                                                        <input id="txt_gcms_mdl" name="txt_gcms_mdl" type="text"
                                                            class="required form-control valid" value="{{$nt_model->gcms_mdl}}">
                                                            </div>
                                                            <div class="col-sm-4">
                                                            <select  class="required form-control valid" name="gcms_mdl_unit" id="gcms_mdl_unit">
                                                                <option value="">- Select -</option>
                                                                @foreach($model_part_mdl as $modelunit)
                                                                @php
                                                                $chk = "";
                                                                if($nt_model->gcms_mdl_unit == $modelunit->name)
                                                                {
                                                                    $chk = "selected";
                                                                }
                                                                else {
                                                                     $chk = "";
                                                                }
                                                                @endphp
                                                                <option value="{{$modelunit->name}}" {{$chk}}>{{$modelunit->name}}</option>
                                                                @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                   
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <div class="col">
                                                    <div class="col-sm-12">
                                                        <label for="surname-2" class="block">SEM MDL 
                                                           
                                                              <span style="font-size: 16px"  class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="col-sm-12">
    
                                                        <div class="row">
                                                            <div class="col-sm-8">
                                                        <input id="txt_sem_mdl" name="txt_sem_mdl" type="text"
                                                            class="required form-control valid" value="{{$nt_model->sem_mdl}}">
                                                            </div>
                                                            <div class="col-sm-4">
                                                            <select  class="required form-control valid" name="sem_mdl_unit" id="sem_mdl_unit">
                                                                <option value="">- Select -</option>
                                                                @foreach($model_part_mdl as $modelunit)
                                                                @php
                                                                $chk = "";
                                                                if($nt_model->sem_mdl_unit == $modelunit->name)
                                                                {
                                                                    $chk = "selected";
                                                                }
                                                                else {
                                                                     $chk = "";
                                                                }
                                                                @endphp
                                                                <option value="{{$modelunit->name}}" {{$chk}}>{{$modelunit->name}}</option>
                                                                @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                   
                                                </div>
                                            </div>
    
                                            <div class="form-group row">
                                                <div class="col">
                                                    <div class="col-sm-12">
                                                        <label for="name-2" class="block">IC Date correlated <span style="font-size: 16px"  class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="col-sm-12">
    
                                                        <input type="date" name="txt_ic_dc" id="txt_ic_dc"
                                                        value="{{$nt_model->ic_dc}}" class="required form-control valid">
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="col-sm-12">
                                                        <label for="surname-2" class="block">FTIR Date correlated <span style="font-size: 16px"  class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <input id="txt_ftir_dc" name="txt_ftir_dc" type="date"
                                                            class="required form-control valid" value="{{$nt_model->ftir_dc}}">
                                                    </div>
                                                </div>
                                            </div>
    
                                            <div class="form-group row">
                                                <div class="col">
                                                    <div class="col-sm-12">
                                                        <label for="surname-2" class="block">IC Correlation site
                                                            <span style="font-size: 16px"  class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <input id="txt_ic_cs" name="txt_ic_cs" type="text"
                                                            class="required form-control valid" value="{{$nt_model->ic_cs}}">
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="col-sm-12">
                                                        <label for="surname-2" class="block">FTIR Correlation site
                                                            <span style="font-size: 16px"  class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <input id="txt_ftir_cs" name="txt_ftir_cs" type="text"
                                                            class="required form-control valid" value="{{$nt_model->ftir_cs}}">
                                                    </div>
                                                </div>
                                            </div>
    
                                            <div class="form-group row">
                                                <div class="col">
                                                    <div class="col-sm-12">
                                                        <label for="surname-2" class="block">IC Document No./Revision
                                                            <span style="font-size: 16px"  class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <input id="txt_ic_dr" name="txt_ic_dr" type="text"
                                                            class="required form-control valid" value="{{$nt_model->ic_dr}}">
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="col-sm-12">
                                                        <label for="surname-2" class="block">FTIR Document No./Revision
                                                            <span style="font-size: 16px"  class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <input id="txt_ftir_dr" name="txt_ftir_dr" type="text"
                                                            class="required form-control valid" value="{{$nt_model->ftir_dr}}">
                                                    </div>
                                                </div>
                                            </div>
    
                                            <div class="form-group row">
                                                <div class="col">
                                                    <div class="col-sm-12">
                                                        <label for="name-2" class="block">IC Parts used 
                                                            
                                                            <span style="font-size: 16px"  class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="row">
                                                            <div class="col-sm-8">
                                                        <input id="txt_ic_pcs" name="txt_ic_pcs" type="text"
                                                            class="required form-control valid" value="{{$nt_model->ic_parts_used}}">
                                                            </div>
                                                            <div class="col-sm-4">
                                                            <select  class="required form-control valid" name="ic_parts_used_unit" id="ic_parts_used_unit">
                                                                <option value="">- Select -</option>
                                                                @foreach($model_part as $modelunit)
                                                                @php
                                                                $chk = "";
                                                                if($nt_model->ic_parts_used_unit == $modelunit->name)
                                                                {
                                                                    $chk = "selected";
                                                                }
                                                                else {
                                                                     $chk = "";
                                                                }
                                                                @endphp
                                                                <option value="{{$modelunit->name}}" {{$chk}}>{{$modelunit->name}}</option>
                                                                @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="col-sm-12">
                                                        <label for="surname-2" class="block">FTIR Parts used 
                                                            <span style="font-size: 16px"  class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="row">
                                                            <div class="col-sm-8">
                                                        <input id="txt_ftir_pcs" name="txt_ftir_pcs" type="text"
                                                            class="required form-control valid" value="{{$nt_model->ftir_parts_used}}">
                                                            </div>
                                                            <div class="col-sm-4">
                                                            <select   class="required form-control valid" name="ftir_parts_used_unit" id="ftir_parts_used_unit">
                                                                <option value="">- Select -</option>
                                                                @foreach($model_part as $modelunit)
                                                                @php
                                                                $chk = "";
                                                                if($nt_model->ftir_parts_used_unit == $modelunit->name)
                                                                {
                                                                    $chk = "selected";
                                                                }
                                                                else {
                                                                     $chk = "";
                                                                }
                                                                @endphp
                                                                <option value="{{$modelunit->name}}" {{$chk}}>{{$modelunit->name}}</option>
                                                                @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <div class="col">
                                                    <div class="col-sm-12">
                                                        <label for="name-2" class="block">IC Extraction vol. 
                                                          
                                                              <span style="font-size: 16px"  class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="row">
                                                            <div class="col-sm-8">
                                                        <input id="txt_ic_ev" name="txt_ic_ev" type="text"
                                                            class="required form-control valid" value="{{$nt_model->ic_extraction_vo}}">
                                                            </div>
                                                            <div class="col-sm-4">
                                                            <select   class="required form-control valid" name="ic_extraction_vo_unit" id="ic_extraction_vo_unit">
                                                                <option value="">- Select -</option>
                                                                @foreach($model_volume as $modelunit)
                                                                @php
                                                                $chk = "";
                                                                if($nt_model->ic_extraction_vo_unit == $modelunit->name)
                                                                {
                                                                    $chk = "selected";
                                                                }
                                                                else {
                                                                     $chk = "";
                                                                }
                                                                @endphp
                                                                <option value="{{$modelunit->name}}" {{$chk}}>{{$modelunit->name}}</option>
                                                                @endforeach
                                                                </select> 
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="col-sm-12">
                                                        <label for="surname-2" class="block">FTIR MDL
                                                           
                                                              <span style="font-size: 16px"  class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="col-sm-12">
    
                                                        <div class="row">
                                                            <div class="col-sm-8">
                                                        <input id="txt_ftir_mdl" name="txt_ftir_mdl" type="text"
                                                            class="required form-control valid" value="{{$nt_model->ftir_mdl}}">
                                                            </div>
                                                            <div class="col-sm-4">
                                                            <select  class="required form-control valid" name="ftir_mdl_unit" id="ftir_mdl_unit">
                                                                <option value="">- Select -</option>
                                                                @foreach($model_part_mdl as $modelunit)
                                                                @php
                                                                $chk = "";
                                                                if($nt_model->ftir_mdl_unit == $modelunit->name)
                                                                {
                                                                    $chk = "selected";
                                                                }
                                                                else {
                                                                     $chk = "";
                                                                }
                                                                @endphp
                                                                <option value="{{$modelunit->name}}" {{$chk}}>{{$modelunit->name}}</option>
                                                                @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <div class="col">
                                                    <div class="col-sm-12">
                                                        <label for="surname-2" class="block">IC MDL 
                                                           
                                                              <span style="font-size: 16px"  class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="col-sm-12">
    
                                                        <div class="row">
                                                            <div class="col-sm-8">
                                                        <input id="txt_ic_mdl" name="txt_ic_mdl" type="text"
                                                            class="required form-control valid" value="{{$nt_model->ic_mdl}}">
                                                            </div>
                                                            <div class="col-sm-4">
                                                            <select  class="required form-control valid" name="ic_mdl_unit" id="ic_mdl_unit">
                                                                <option value="">- Select -</option>
                                                                @foreach($model_part_mdl as $modelunit)
                                                                @php
                                                                $chk = "";
                                                                if($nt_model->ic_mdl_unit == $modelunit->name)
                                                                {
                                                                    $chk = "selected";
                                                                }
                                                                else {
                                                                     $chk = "";
                                                                }
                                                                @endphp
                                                                <option value="{{$modelunit->name}}" {{$chk}}>{{$modelunit->name}}</option>
                                                                @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                   
                                                </div>
                                            </div>
                                           
                                        </fieldset>
    

                                        <!-- end  PAGE 2 -->
 
                                        <h3> PAGE 3 </h3>
                                        <fieldset>
                                            <div class="container">
                                                <div class="row">
                                                    {{--  ---------------------------  END checkbox -------------------------------------------------}}


                                                        @foreach($model_parameter as $st)
                                                        @if( $st->name == "Input tester")
                                                        @php
                                                            if($st->lpc == 1){$lpc = "checked";}else {$lpc = "";}
                                                            if($st->sem == 1){$sem = "checked";}else {$sem = "";}
                                                            if($st->ic == 1){$ic = "checked";}else {$ic = "";}
                                                            if($st->gcms == 1){$gcms = "checked";}else {$gcms = "";}
                                                            if($st->ftir == 1){$ftir = "checked";}else {$ftir = "";}
                                                            if($st->semtest == 1){$semtest = "checked";}else {$semtest = "";}
                                                        @endphp
                                                        <div class="col-sm-4">
                                                            <div class="col-md-10 col-xl-12">
                                                                <div class="card seo-card-1">
                                                                    <div class="card-block seo-statustic">
                                                                        <i class="ti-server text-c-green"></i>
                                                                        <h5>{{$st->name}}</h5>
                                                                        <p>&nbsp;</p>
                                                                        <div class="to-do-list">
                                                                            <input type="hidden" id="ckidparameter{{$st->text}}" name="ckidparameter{{$st->text}}" value="{{$st->id_m_parameter}}">
                                                                            <div class="checkbox-fade fade-in-primary">
                                                                                <label>
                                                                                    <input type="checkbox" id="ck_lpc{{$st->text}}" name="ck_lpc{{$st->text}}" value="1"
                                                                                           style="display:none" {{$lpc}}>
                                                                                    <span class="cr">
                                                                                <i
                                                                                    class="cr-icon icofont icofont-ui-check txt-primary"></i>
                                                                            </span>
                                                                                    <span>LPC</span>
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                        <div class="to-do-list">
                                                                            <div class="checkbox-fade fade-in-primary">
                                                                                <label>
                                                                                    <input type="checkbox"id="ck_sem{{$st->text}}" name="ck_sem{{$st->text}}" value="1"
                                                                                           style="display:none" {{$sem}}>
                                                                                    <span class="cr">
                                                                                <i
                                                                                    class="cr-icon icofont icofont-ui-check txt-primary"></i>
                                                                            </span>
                                                                                    <span>SEM</span>
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                        <div class="to-do-list">
                                                                            <div class="checkbox-fade fade-in-primary">
                                                                                <label>
                                                                                    <input type="checkbox" id="ck_ic{{$st->text}}" name="ck_ic{{$st->text}}" value="1"
                                                                                           style="display:none" {{$ic}}>
                                                                                    <span class="cr">
                                                                                <i
                                                                                    class="cr-icon icofont icofont-ui-check txt-primary"></i>
                                                                            </span>
                                                                                    <span>IC</span>
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                        <div class="to-do-list">
                                                                            <div class="checkbox-fade fade-in-primary">
                                                                                <label>
                                                                                    <input type="checkbox" id="ck_gcms{{$st->text}}" name="ck_gcms{{$st->text}}" value="1"
                                                                                           style="display:none" {{$gcms}}>
                                                                                    <span class="cr">
                                                                                <i
                                                                                    class="cr-icon icofont icofont-ui-check txt-primary"></i>
                                                                            </span>
                                                                                    <span>GCMS Outgas</span>
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                        <div class="to-do-list">
                                                                            <div class="checkbox-fade fade-in-primary">
                                                                                <label>
                                                                                    <input type="checkbox" id="ck_ftir{{$st->text}}" name="ck_ftir{{$st->text}}" value="1"
                                                                                           style="display:none" {{$ftir}}>
                                                                                    <span class="cr">
                                                                                <i
                                                                                    class="cr-icon icofont icofont-ui-check txt-primary"></i>
                                                                            </span>
                                                                                    <span>GCMS Solvent</span>
                                                                                </label>
                                                                            </div>
                                                                        </div>

                                                                        <div class="to-do-list">
                                                                            <div class="checkbox-fade fade-in-primary">
                                                                                <label>
                                                                                    <input type="checkbox" id="ck_semtest{{$st->text}}" name="ck_semtest{{$st->text}}" value="1"
                                                                                           style="display:none" {{$semtest}}>
                                                                                    <span class="cr">
                                                                                <i
                                                                                    class="cr-icon icofont icofont-ui-check txt-primary"></i>
                                                                            </span>
                                                                                    <span>MESA</span>
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </div>

                                                        </div>
                                                     @endif
                                                     @endforeach
                                                    {{------------------------------  END checkbox -------------------------------------------------}}
                                                </div>
                                            </div>


                                        </fieldset>
                                    </form>
                                </section>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        var form = $("#example-advanced-form").show();
        form.steps({
            headerTag: "h3",
            bodyTag: "fieldset",
            transitionEffect: "slideLeft",
            onStepChanging: function(event, currentIndex, newIndex) {
                // Allways allow previous action even if the current form is not valid!
                if (currentIndex > newIndex) {
                    return true;
                }
                // Forbid next action on "Warning" step if the user is to young
                if (newIndex === 3 && Number($("#age-2").val()) < 18) {
                    return false;
                }
                // Needed in some cases if the user went back (clean up)
                if (currentIndex < newIndex) {
                    // To remove error styles
                    form.find(".body:eq(" + newIndex + ") label.error").remove();
                    form.find(".body:eq(" + newIndex + ") .error").removeClass("error");
                }
                form.validate().settings.ignore = ":disabled,:hidden";
                return form.valid();
            },
            onStepChanged: function(event, currentIndex, priorIndex) {

                // Used to skip the "Warning" step if the user is old enough.
                if (currentIndex === 2 && Number($("#age-2").val()) >= 18) {
                    form.steps("next");
                }
                // Used to skip the "Warning" step if the user is old enough and wants to the previous step.
                if (currentIndex === 2 && priorIndex === 3) {
                    form.steps("previous");
                }
            },
            onFinishing: function(event, currentIndex) {
                form.validate().settings.ignore = ":disabled";
                return form.valid();
            },
            onFinished: function(event, currentIndex) {
					swal({
						title: "Are you sure?",
						text: "Do you really want to update model ?",
						type: "info",
						showCancelButton: true,
						closeOnConfirm: false,
						showLoaderOnConfirm: true
					},
                    function(){
                        
                        var chkedit = $('#chkedit').val();
                        var forms = $('#example-advanced-form')[0];
                        var data = new FormData(forms);
                        if(chkedit == 1)
                        {
							$.ajax({
								type:'POST',
								url:"{{ url('editmodelWDSubmit') }}",
								data: data,
								
								processData: false,
								contentType: false,
								cache: false,
								success:function(data){
									if (data.success == 1) {
									swal("Success", "Submit save data success", "success");
									setTimeout(function () {
										window.location.href =
											"{{ url('master/model') }}";
									}, 1000);
								} else {

								}
								}
							});
						}
						else
						{
							$.ajax({
								type:'POST',
								url:"{{ url('add_modelWD') }}",
								data: data,
								
								processData: false,
								contentType: false,
								cache: false,
								success:function(data){
									if (data.success == 1) {
									swal("Success", "Submit save data success", "success");
									setTimeout(function () {
										window.location.href =
											"{{ url('master/model') }}";
									}, 1000);
								} else {

								}
								}
							});

						} 

                    });
            }
        }).validate({
            errorPlacement: function errorPlacement(error, element) {

                element.before(error);
            },
            rules: {
                confirm: {
                    equalTo: "#password-2"
                }
            }
        });

        $(function () {
            $("#dt").DataTable();
        });

       
   



    </script>

@endsection
