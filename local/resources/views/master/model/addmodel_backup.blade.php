@extends('layouts.userlogin')
@extends('layouts.main')
@section('title', 'Add Model')
@section('breadcrumb-title-href', 'model')
@section('breadcrumb-title', 'Model')
@section('breadcrumb-sub', 'Add Model')
     
@section('content')

<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">

                {{--                <span>Add class of <code>.form-control</code> with <code>&lt;input&gt;</code> tag</span>--}}
            </div>
            <div class="card-block">
                <div class="row">
                    <div class="col-md-12">
                        <div id="wizarda">
                            <section>
                                {{-- id="basic-forms" --}}
                                <form class="wizard-form" method="post"   id="example-advanced-form">
                                    {{ csrf_field() }}
                                    
                                    <h3> PAGE 1</h3>
                                    <fieldset>

                                        <div class="form-group row">
                                            <div class="col">
                                                <div class="col-sm-12">
                                                    <label for="name-2" class="block">Model <span style="font-size: 16px"  class="text-danger">*</span></label>
                                                </div>
                                                
                                                <div class="col-sm-12">
                                                    <input id="txt_name_model" name="txt_name_model" type="text"
                                                        class="required form-control valid" value="">
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="col-sm-12">
                                                    <label for="surname-2" class="block">Part UP/DN <span style="font-size: 16px"  class="text-danger">*</span></label>
                                                </div>
                                                <div class="col-sm-12">
                                                    <select id="txt_part" name="txt_part"
                                                        class="required form-control valid">
                                                        <option  value="" >Select State</option>
                                                        <option value="UP">UP</option>
                                                        <option value="DN">DN</option>
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
                                                        class="required form-control valid" value="">
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="col-sm-12">
                                                    <label for="surname-2" class="block">Line type <span style="font-size: 16px"  class="text-danger">*</span></label>
                                                </div>
                                                <div class="col-sm-12">
                                                    <input id="txt_line_type" name="txt_line_type" type="text"
                                                        class="required form-control valid" value="">
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
                                                        class="required form-control valid" value="">
                                                </div>
                                            </div>
                                            <div class="col">
                                                
                                                <div class="col-sm-12">
                                                    <label for="surname-2" class="block">SUB_SUPPLIER_NAME
                                                        <span style="font-size: 16px"  class="text-danger">*</span></label>
                                                </div>
                                                <div class="col-sm-12">
                                                    <input id="txt_ssn" name="txt_ssn" type="text"
                                                        class="required form-control valid" value="">
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
                                                        class="required form-control valid" value="">
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="col-sm-12">
                                                    <label for="surname-2" class="block">Part revision  <span style="font-size: 16px"  class="text-danger">*</span></label>
                                                </div>
                                                <div class="col-sm-12">
                                                    <input id="txt_part_revision" name="txt_part_revision" type="text"
                                                        class="required form-control valid" value="">
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
                                                        class="required form-control valid" value="">
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="col-sm-12">
                                                    <label for="surname-2" class="block">Drawing revision  <span style="font-size: 16px"  class="text-danger">*</span></label>
                                                </div>
                                                <div class="col-sm-12">
                                                    <input id="txt_drawing_revision" name="txt_drawing_revision"
                                                        type="text" class="required form-control valid"
                                                        value="">
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
                                                        class="required form-control valid" value="">
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <input id="txt_dln1" name="txt_dln1" type="text"
                                                        class="form-control valid" value="">
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <input id="txt_dln1" name="txt_dln1" type="text"
                                                        class="form-control valid" value="">
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
                                                            class="required form-control valid" value="">
                                                            </div>
                                                            <div class="col-sm-4">
                                                                <select  class="required form-control valid" name="surface_area_unit" id="surface_area_unit">
                                                                    <option value="">- Select -</option>
                                                                    @foreach($model_area as $modelunit)
                                                                    <option value="{{$modelunit->name}}">{{$modelunit->name}}</option>
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
                                                        class="required form-control valid" value="">
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
                                                        value="" class="required form-control valid">
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
                                                        class="required form-control valid" value="">
                                                        </div>

                                                        <div class="col-sm-4">
                                                        <select class="required form-control valid" name="alpc_water_vol_unit" id="alpc_water_vol_unit">
                                                            <option value="">- Select -</option>
                                                            @foreach($model_volume as $modelunit)
                                                            <option value="{{$modelunit->name}}">{{$modelunit->name}}</option>
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
                                                        value="">
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
                                                        class="required form-control valid" value="">
                                                        </div>
                                                        <div class="col-sm-4">
                                                        <select  class="required form-control valid" name="alpc_extraction_vol_unit" id="alpc_extraction_vol_unit">
                                                            <option value="">- Select -</option>
                                                            @foreach($model_volume as $modelunit)
                                                            <option value="{{$modelunit->name}}">{{$modelunit->name}}</option>
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
                                                        class="required form-control valid" value="">
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
                                                        class="required form-control valid" value="">
                                                        </div>
                                                        <div class="col-sm-4">
                                                        <select class="required form-control valid"
                                                                name="alpc_us_power_unit" id="alpc_us_power_unit">
                                                                <option value="">- Select -</option>
                                                                @foreach($model_electric as $modelunit)
                                                                <option value="{{$modelunit->name}}">
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
                                                        class="required form-control valid" value="">
                                                        </div>
                                                        <div class="col-sm-4">
                                                        <select  class="required form-control valid" name="alpc_parts_used_unit" id="alpc_parts_used_unit">
                                                            <option value="">- Select -</option>
                                                            @foreach($model_part as $modelunit)
                                                            <option value="{{$modelunit->name}}">{{$modelunit->name}}</option>
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
                                                        class="required form-control valid" value="">
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
                                                        class="required form-control valid" value="">
                                                        </div>
                                                        <div class="col-sm-4">
                                                        <select  class="required form-control valid" name="alpc_us_frequency_unit" id="alpc_us_frequency_unit">
                                                            <option value="">- Select -</option>
                                                            @foreach($model_electric as $modelunit)
                                                            <option value="{{$modelunit->name}}">{{$modelunit->name}}</option>
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
                                                        class="required form-control valid" value="">
                                                        </div>
                                                        <div class="col-sm-4">
                                                        <select  class="required form-control valid" name="alpc_extraction_time_unit" id="alpc_extraction_time_unit">
                                                            <option value="">- Select -</option>
                                                            @foreach($model_time as $modelunit)
                                                            <option value="{{$modelunit->name}}">{{$modelunit->name}}</option>
                                                            @endforeach
                                                            </select> 
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                       



                                    </fieldset>

                                    <h3> PAGE 2 </h3>
                                    <fieldset>
                                        <div class="form-group row">
                                            <div class="col">
                                                <div class="col-sm-12">
                                                    <label for="name-2" class="block">SEM Date correlated <span style="font-size: 16px"  class="text-danger">*</span></label>
                                                </div>
                                                <div class="col-sm-12">

                                                    <input type="date" name="txt_sem_dc" id="txt_sem_dc"
                                                        value="" class="required form-control valid">
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="col-sm-12">
                                                    <label for="name-2" class="block">GCMS Date correlated <span style="font-size: 16px"  class="text-danger">*</span></label>
                                                </div>
                                                <div class="col-sm-12">

                                                    <input type="date" name="txt_gcms_dc" id="txt_gcms_dc"
                                                        value="" class="required form-control valid">
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
                                                        value="">
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
                                                        value="">
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
                                                        class="required form-control valid" value="">
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="col-sm-12">
                                                    <label for="surname-2" class="block">GCMS Document No./Revision
                                                        <span style="font-size: 16px"  class="text-danger">*</span></label>
                                                </div>
                                                <div class="col-sm-12">
                                                    <input id="txt_gcms_dr" name="txt_gcms_dr" type="text"
                                                        class="required form-control valid" value="">
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
                                                        value="">
                                                        </div>
                                                        <div class="col-sm-4">
                                                        <select  class="required form-control valid" name="sem_filtered_solution_unit" id="sem_filtered_solution_unit">
                                                            <option value="">- Select -</option>
                                                            @foreach($model_volume as $modelunit)
                                                            <option value="{{$modelunit->name}}">{{$modelunit->name}}</option>
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
                                                        class="required form-control valid" value="">
                                                        </div>
                                                        <div class="col-sm-4">
                                                        <select  class="required form-control valid" name="gcms_parts_used_unit" id="gcms_parts_used_unit">
                                                            <option value="">- Select -</option>
                                                            @foreach($model_part as $modelunit)
                                                            <option value="{{$modelunit->name}}">{{$modelunit->name}}</option>
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
                                                        value="">
                                                        </div>
                                                        <div class="col-sm-4">
                                                        <select  class="required form-control valid" name="sem_filter_spot_area_unit" id="sem_filter_spot_area_unit">
                                                            <option value="">- Select -</option>
                                                            @foreach($model_area as $modelunit)
                                                            <option value="{{$modelunit->name}}">{{$modelunit->name}}</option>
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
                                                        class="required form-control valid" value="">

                                                        </div>
                                                        <div class="col-sm-4">
                                                        <select  class="required form-control valid" name="gcms_sampling_time_unit" id="gcms_sampling_time_unit">
                                                            <option value="">- Select -</option>
                                                            @foreach($model_time as $modelunit)
                                                            <option value="{{$modelunit->name}}">{{$modelunit->name}}</option>
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
                                                        class="required form-control valid" value="">
                                                        </div>
                                                        <div class="col-sm-4">
                                                        <select  class="required form-control valid" name="sem_parts_used_unit" id="sem_parts_used_unit">
                                                            <option value="">- Select -</option>
                                                            @foreach($model_part as $modelunit)
                                                            <option value="{{$modelunit->name}}">{{$modelunit->name}}</option>
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
                                                        value="" class="required form-control valid">
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="col-sm-12">
                                                    <label for="surname-2" class="block">FTIR Date correlated <span style="font-size: 16px"  class="text-danger">*</span></label>
                                                </div>
                                                <div class="col-sm-12">
                                                    <input id="txt_ftir_dc" name="txt_ftir_dc" type="date"
                                                        class="required form-control valid" value="">
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
                                                        class="required form-control valid" value="">
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="col-sm-12">
                                                    <label for="surname-2" class="block">FTIR Correlation site
                                                        <span style="font-size: 16px"  class="text-danger">*</span></label>
                                                </div>
                                                <div class="col-sm-12">
                                                    <input id="txt_ftir_cs" name="txt_ftir_cs" type="text"
                                                        class="required form-control valid" value="">
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
                                                        class="required form-control valid" value="">
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="col-sm-12">
                                                    <label for="surname-2" class="block">FTIR Document No./Revision
                                                        <span style="font-size: 16px"  class="text-danger">*</span></label>
                                                </div>
                                                <div class="col-sm-12">
                                                    <input id="txt_ftir_dr" name="txt_ftir_dr" type="text"
                                                        class="required form-control valid" value="">
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
                                                        class="required form-control valid" value="">
                                                        </div>
                                                        <div class="col-sm-4">
                                                        <select  class="required form-control valid" name="ic_parts_used_unit" id="ic_parts_used_unit">
                                                            <option value="">- Select -</option>
                                                            @foreach($model_part as $modelunit)
                                                            <option value="{{$modelunit->name}}">{{$modelunit->name}}</option>
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
                                                        class="required form-control valid" value="">
                                                        </div>
                                                        <div class="col-sm-4">
                                                        <select  class="required form-control valid" name="ftir_parts_used_unit" id="ftir_parts_used_unit">
                                                            <option value="">- Select -</option>
                                                            @foreach($model_part as $modelunit)
                                                            <option value="{{$modelunit->name}}">{{$modelunit->name}}</option>
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
                                                        class="required form-control valid" value="">
                                                        </div>
                                                        <div class="col-sm-4">
                                                        <select class="required form-control valid" name="ic_extraction_vo_unit" id="ic_extraction_vo_unit">
                                                            <option value="">- Select -</option>
                                                            @foreach($model_volume as $modelunit)
                                                            <option value="{{$modelunit->name}}">{{$modelunit->name}}</option>
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

                                    <h3> PAGE 3 </h3>
                                    <fieldset>
                                        <div class="dt-responsive table-responsive">
                                            <table id="form-input-table"
                                                class="table table-striped table-bordered nowrap ">

                                                <thead>
                                                    <tr>
                                                        <th rowspan="2">Name</th>
                                                        <th bgcolor="#FFFF99" colspan="1" class="text-center">LPC</th>
                                                        <th bgcolor="#00FFFF" colspan="10" class="text-center">SEM</th>
                                                        <th bgcolor="#00FF7F" colspan="3" class="text-center">IC</th>
                                                        <th bgcolor="#FFB6C1" colspan="4" class="text-center">GCMS</th>
                                                        <th bgcolor="#EE82EE" colspan="1" class="text-center">FTIR</th>
                                                        <th rowspan="2">Name</th>
                                                    </tr>

                                                    <tr>

                                                        <th bgcolor="#FFFF99">&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
                                                        </th>
                                                        <th bgcolor="#00FFFF">&emsp;&emsp;Class1&emsp;&emsp;</th>
                                                        <th bgcolor="#00FFFF">&emsp;&emsp;Class2&emsp;&emsp;</th>
                                                        <th bgcolor="#00FFFF">&emsp;&emsp;Class3&emsp;&emsp;</th>
                                                        <th bgcolor="#00FFFF">&emsp;&ensp;Class3.1&emsp;&ensp;</th>
                                                        <th bgcolor="#00FFFF">&emsp;&ensp;Class3.2&emsp;&ensp;</th>
                                                        <th bgcolor="#00FFFF">&emsp;&ensp;Class3.3&emsp;&ensp;</th>
                                                        <th bgcolor="#00FFFF">&emsp;&emsp;Class4&emsp;&emsp;</th>
                                                        <th bgcolor="#00FFFF">&emsp;&emsp;Class5&emsp;&emsp;</th>
                                                        <th bgcolor="#00FFFF">&emsp;&ensp;Class5.1&emsp;&ensp;&nbsp;
                                                        </th>
                                                        <th bgcolor="#00FFFF">&emsp;&emsp;Class6&emsp;&emsp;</th>
                                                        <th bgcolor="#00FF7F">&emsp;&emsp;Anion&emsp;&emsp;</th>
                                                        <th bgcolor="#00FF7F">&emsp;&ensp;Sulphate&emsp;&nbsp;</th>
                                                        <th bgcolor="#00FF7F">&emsp;&emsp;Cation&emsp;&emsp;</th>
                                                        <th bgcolor="#FFB6C1">&emsp;&nbsp;Outgassing&emsp;&nbsp;</th>
                                                        <th bgcolor="#FFB6C1">&emsp;Smear&emsp;&nbsp;</th>
                                                        <th bgcolor="#FFB6C1">&emsp;&nbsp;Low MW&emsp;&nbsp;</th>
                                                        <th bgcolor="#FFB6C1">&emsp;&nbsp;High MW&emsp;&nbsp;</th>
                                                        <th bgcolor="#EE82EE">&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($modeltype as $item )
                                                    @php
                                                    
                                                    if($item->id_model_type == 2 || $item->id_model_type == 3 || $item->id_model_type
                                                    == 4 || $item->id_model_type == 5)
                                                    {
                                                      $readonlyck = "disabled";
                                                    }
                                                    else {
                                                        
                                                        $readonlyck = "";
                                                    }
                                                     
                                                    @endphp              
                                                    <tr>
                                                        <td>{{$item->name}} :</td>
                                                        <input type="hidden" class="form-control"
                                                            name="typeID{{$item->text}}" id="typeID{{$item->text}}"
                                                    value="{{$item->id_model_type}}">
                                                        <td bgcolor="#FFFF99">
                                                            <input type="text" class="form-control"
                                                                name="alpc{{$item->text}}" placeholder="N/A"
                                                                id="alpc{{$item->text}}"
                                                                value="" {{$readonlyck}}>


                                                                <input type="hidden" class="form-control"
                                                                name="alpc{{$item->text}}" placeholder="N/A"
                                                                id="alpc{{$item->text}}"
                                                                value="" >
                                                                
                                                        </td>
                                                        <td bgcolor="#00FFFF">
                                                            <input type="text" class="form-control"
                                                                name="class1{{$item->text}}" placeholder="N/A"
                                                                id="class1{{$item->text}}"
                                                                value="" {{$readonlyck}} >


                                                                <input type="hidden" class="form-control"
                                                                name="alpc{{$item->text}}" placeholder="N/A"
                                                                id="alpc{{$item->text}}"
                                                                value="" >
                                                        </td>
                                                        <td bgcolor="#00FFFF">
                                                            <input type="text" class="form-control"
                                                                name="class2{{$item->text}}" placeholder="N/A"
                                                                id="class2{{$item->text}}"
                                                                value="" {{$readonlyck}}>

                                                                <input type="hidden" class="form-control"
                                                                name="alpc{{$item->text}}" placeholder="N/A"
                                                                id="alpc{{$item->text}}"
                                                                value="" >
                                                        </td>
                                                        <td bgcolor="#00FFFF">
                                                            <input type="text" class="form-control"
                                                                name="class3{{$item->text}}" placeholder="N/A"
                                                                id="class3{{$item->text}}"
                                                                value="" {{$readonlyck}}>

                                                                <input type="hidden" class="form-control"
                                                                name="alpc{{$item->text}}" placeholder="N/A"
                                                                id="alpc{{$item->text}}"
                                                                value="" >
                                                        </td>
                                                        <td bgcolor="#00FFFF">
                                                            <input type="text" class="form-control"
                                                                name="class3_1{{$item->text}}" placeholder="N/A"
                                                                id="class3_1{{$item->text}}"
                                                                value="" {{$readonlyck}}>

                                                                <input type="hidden" class="form-control"
                                                                name="alpc{{$item->text}}" placeholder="N/A"
                                                                id="alpc{{$item->text}}"
                                                                value="" >
                                                        </td>
                                                        <td bgcolor="#00FFFF">
                                                            <input type="text" class="form-control"
                                                                name="class3_2{{$item->text}}" placeholder="N/A"
                                                                id="class3_2{{$item->text}}"
                                                                value="" {{$readonlyck}}>


                                                                <input type="hidden" class="form-control"
                                                                name="alpc{{$item->text}}" placeholder="N/A"
                                                                id="alpc{{$item->text}}"
                                                                value="" >
                                                        </td>
                                                        <td bgcolor="#00FFFF">
                                                            <input type="text" class="form-control"
                                                                name="class3_3{{$item->text}}" placeholder="N/A"
                                                                id="class3_3{{$item->text}}"
                                                                value="" {{$readonlyck}}>

                                                                <input type="hidden" class="form-control"
                                                                name="alpc{{$item->text}}" placeholder="N/A"
                                                                id="alpc{{$item->text}}"
                                                                value="" >
                                                        </td>
                                                        <td bgcolor="#00FFFF">
                                                            <input type="text" class="form-control"
                                                                name="class4{{$item->text}}" placeholder="N/A"
                                                                id="class4{{$item->text}}"
                                                                value="" {{$readonlyck}}>


                                                                <input type="hidden" class="form-control"
                                                                name="alpc{{$item->text}}" placeholder="N/A"
                                                                id="alpc{{$item->text}}"
                                                                value="" >
                                                        </td>
                                                        <td bgcolor="#00FFFF">
                                                            <input type="text" class="form-control"
                                                                name="class5{{$item->text}}" placeholder="N/A"
                                                                id="class5{{$item->text}}"
                                                                value="" {{$readonlyck}}>


                                                                <input type="hidden" class="form-control"
                                                                name="alpc{{$item->text}}" placeholder="N/A"
                                                                id="alpc{{$item->text}}"
                                                                value="" >
                                                        </td>
                                                        <td bgcolor="#00FFFF">
                                                            <input type="text" class="form-control"
                                                                name="class5_1{{$item->text}}" placeholder="N/A"
                                                                id="class5_1{{$item->text}}"
                                                                value="" {{$readonlyck}}>


                                                                <input type="hidden" class="form-control"
                                                                name="alpc{{$item->text}}" placeholder="N/A"
                                                                id="alpc{{$item->text}}"
                                                                value="" >
                                                        </td>
                                                        <td bgcolor="#00FFFF">
                                                            <input type="text" class="form-control"
                                                                name="class6{{$item->text}}" placeholder="N/A"
                                                                id="class6{{$item->text}}"
                                                                value="" {{$readonlyck}}>


                                                                <input type="hidden" class="form-control"
                                                                name="alpc{{$item->text}}" placeholder="N/A"
                                                                id="alpc{{$item->text}}"
                                                                value="" >
                                                        </td>
                                                        <td bgcolor="#00FF7F">
                                                            <input type="text" class="form-control"
                                                                name="anion{{$item->text}}" placeholder="N/A"
                                                                id="anion{{$item->text}}"
                                                                value="" {{$readonlyck}}>


                                                                <input type="hidden" class="form-control"
                                                                name="alpc{{$item->text}}" placeholder="N/A"
                                                                id="alpc{{$item->text}}"
                                                                value="" >
                                                        </td>
                                                        <td bgcolor="#00FF7F">
                                                            <input type="text" class="form-control"
                                                                name="sulphate{{$item->text}}" placeholder="N/A"
                                                                id="sulphate{{$item->text}}"
                                                                value="" {{$readonlyck}}>


                                                                <input type="hidden" class="form-control"
                                                                name="alpc{{$item->text}}" placeholder="N/A"
                                                                id="alpc{{$item->text}}"
                                                                value="">
                                                        </td>
                                                        <td bgcolor="#00FF7F">
                                                            <input type="text" class="form-control"
                                                                name="cation{{$item->text}}" placeholder="N/A"
                                                                id="cation{{$item->text}}"
                                                                value="" {{$readonlyck}}>


                                                                <input type="hidden" class="form-control"
                                                                name="alpc{{$item->text}}" placeholder="N/A"
                                                                id="alpc{{$item->text}}"
                                                                value="" >
                                                        </td>
                                                        <td bgcolor="#FFB6C1">
                                                            <input type="text" class="form-control"
                                                                name="outgassing{{$item->text}}" placeholder="N/A"
                                                                id="outgassing{{$item->text}}"
                                                                value="" {{$readonlyck}}>


                                                                <input type="hidden" class="form-control"
                                                                name="alpc{{$item->text}}" placeholder="N/A"
                                                                id="alpc{{$item->text}}"
                                                                value="" >
                                                        </td>
                                                        <td bgcolor="#FFB6C1">
                                                            <input type="text" class="form-control"
                                                                name="alkylacrylate{{$item->text}}" placeholder="N/A"
                                                                id="alkylacrylate{{$item->text}}"
                                                                value="" {{$readonlyck}}>

                                                                <input type="hidden" class="form-control"
                                                                name="alpc{{$item->text}}" placeholder="N/A"
                                                                id="alpc{{$item->text}}"
                                                                value="" >
                                                        </td>
                                                        <td bgcolor="#FFB6C1">
                                                            <input type="text" class="form-control"
                                                                name="low_MW{{$item->text}}" placeholder="N/A"
                                                                id="low_MW{{$item->text}}"
                                                                value="" {{$readonlyck}}>

                                                                <input type="hidden" class="form-control"
                                                                name="alpc{{$item->text}}" placeholder="N/A"
                                                                id="alpc{{$item->text}}"
                                                                value="" >
                                                        </td>
                                                        <td bgcolor="#FFB6C1">
                                                            <input type="text" class="form-control"
                                                                name="high_MW{{$item->text}}" placeholder="N/A"
                                                                id="high_MW{{$item->text}}"
                                                                value="" {{$readonlyck}}>


                                                                <input type="hidden" class="form-control"
                                                                name="alpc{{$item->text}}" placeholder="N/A"
                                                                id="alpc{{$item->text}}"
                                                                value="" >
                                                        </td>
                                                        <td bgcolor="#EE82EE">
                                                            <input type="text" class="form-control"
                                                                name="ftir{{$item->text}}" placeholder="N/A"
                                                                id="ftir{{$item->text}}"
                                                                value="">

                                                                <input type="hidden" class="form-control"
                                                                name="alpc{{$item->text}}" placeholder="N/A"
                                                                id="alpc{{$item->text}}"
                                                                value="">
                                                        </td>
                                                        <td>: {{$item->name}}</td>
                                                    </tr>
                                                    @endforeach
                                                    <tr>
                                                        <td rowspan="2">Data Q'ty in View</td>
                                                        <td bgcolor="#FFFF99" colspan="1" class="text-center">
                                                            <input type="text" class="form-control" name="txt_alpc_sum"
                                                                placeholder="N/A" id="txt_alpc_sum" value="">
                                                        </td>
                                                        <td bgcolor="#00FFFF" colspan="10" class="text-center">
                                                            <div class="row">
                                                                <div class="col-sm-5">&nbsp;</div>
                                                                <div class="col-sm-2">
                                                                    <input type="text" class="form-control"
                                                                        name="txt_sem_sum" placeholder="N/A"
                                                                        id="txt_sem_sum" value="">
                                                                </div>
                                                                <div class="col-sm-5">&nbsp;</div>
                                                            </div>
                                                        </td>
                                                        <td bgcolor="#00FF7F" colspan="3" class="text-center">
                                                            <div class="row">
                                                                <div class="col-sm-3">&nbsp;</div>
                                                                <div class="col-sm-6">
                                                                    <input type="text" class="form-control"
                                                                        name="txt_ic_sum" placeholder="N/A"
                                                                        id="txt_ic_sum" value="">
                                                                </div>
                                                                <div class="col-sm-2">&nbsp;</div>
                                                            </div>
                                                        </td>
                                                        <td bgcolor="#FFB6C1" colspan="4" class="text-center">
                                                            <div class="row">
                                                                <div class="col-sm-4">&nbsp;</div>
                                                                <div class="col-sm-4">
                                                                    <input type="text" class="form-control"
                                                                        name="txt_gcms_sum" placeholder="N/A"
                                                                        id="txt_gcms_sum" value="">
                                                                </div>
                                                                <div class="col-sm-2">&nbsp;</div>
                                                            </div>
                                                        </td>
                                                        <td bgcolor="#EE82EE" colspan="1" class="text-center">
                                                            <input type="text" class="form-control" name="txt_ftir_sum"
                                                                placeholder="N/A" id="txt_ftir_sum" value="">
                                                        </td>
                                                        <td rowspan="2">Data Q'ty in View</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </fieldset>

                                    <h3> PAGE 4 </h3>
                                    <fieldset>
                                        <div class="container">
                                            <div class="row">
                                                {{--  ---------------------------  END checkbox -------------------------------------------------}}
                                                @foreach($parameterType as $st)
                                                <div class="col-sm-4">
                                                    <div class="col-md-10 col-xl-12">
                                                        <div class="card seo-card-1">
                                                            <div class="card-block seo-statustic">
                                                                <i class="ti-server text-c-green"></i>
                                                                <h5>{{$st->name}}</h5>
                                                                <p>&nbsp;</p>
                                                                <div class="to-do-list">
                                                                    <div class="checkbox-fade fade-in-primary">
                                                                        <label>
                                                                            <input type="checkbox"
                                                                                id="ck_lpc{{$st->text}}"
                                                                                name="ck_lpc{{$st->text}}" value="1"
                                                                                style="display:none">
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
                                                                            <input type="checkbox"
                                                                                id="ck_sem{{$st->text}}"
                                                                                name="ck_sem{{$st->text}}" value="1"
                                                                                style="display:none">
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
                                                                            <input type="checkbox"
                                                                                id="ck_ic{{$st->text}}"
                                                                                name="ck_ic{{$st->text}}" value="1"
                                                                                style="display:none">
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
                                                                            <input type="checkbox"
                                                                                id="ck_gcms{{$st->text}}"
                                                                                name="ck_gcms{{$st->text}}" value="1"
                                                                                style="display:none">
                                                                            <span class="cr">
                                                                                <i
                                                                                    class="cr-icon icofont icofont-ui-check txt-primary"></i>
                                                                            </span>
                                                                            <span>GCMS</span>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="to-do-list">
                                                                    <div class="checkbox-fade fade-in-primary">
                                                                        <label>
                                                                            <input type="checkbox"
                                                                                id="ck_ftir{{$st->text}}"
                                                                                name="ck_ftir{{$st->text}}" value="1"
                                                                                style="display:none">
                                                                            <span class="cr">
                                                                                <i
                                                                                    class="cr-icon icofont icofont-ui-check txt-primary"></i>
                                                                            </span>
                                                                            <span>FTIR</span>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                               
                                                                <div class="to-do-list">
                                                                    <div class="checkbox-fade fade-in-primary">
                                                                        <label>
                                                                            <input type="checkbox"
                                                                                id="ck_semtest{{$st->text}}"
                                                                                name="ck_semtest{{$st->text}}" value="1"
                                                                                style="display:none">
                                                                            <span class="cr">
                                                                                <i
                                                                                    class="cr-icon icofont icofont-ui-check txt-primary"></i>
                                                                            </span>
                                                                            <span>SEM (Ghost)</span>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                
                                                            </div>

                                                        </div>
                                                    </div>

                                                </div>

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
    function xx() {
        swal({
            title: "Ajax request example",
            text: "Submit to run ajax request",
            type: "info",
            showCancelButton: true,
            closeOnConfirm: false,
            showLoaderOnConfirm: true
        }, function () {
            setTimeout(function () {
                swal("Ajax request finished!");
            }, 10000);
        });
    }

    var form = $("#example-advanced-form").show();
    form.steps({
        headerTag: "h3",
        bodyTag: "fieldset",
        transitionEffect: "slideLeft",
        onStepChanging: function (event, currentIndex, newIndex) {
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
        onStepChanged: function (event, currentIndex, priorIndex) {

            // Used to skip the "Warning" step if the user is old enough.
            if (currentIndex === 2 && Number($("#age-2").val()) >= 18) {
                form.steps("next");
            }
            // Used to skip the "Warning" step if the user is old enough and wants to the previous step.
            if (currentIndex === 2 && priorIndex === 3) {
                form.steps("previous");
            }
        },
        onFinishing: function (event, currentIndex) {
            form.validate().settings.ignore = ":disabled";
            return form.valid();
        },
        onFinished: function (event, currentIndex) {
            
            swal({
                    title: "Are you sure?",
                    text: "Confirm Add Model!",
                    type: "info",
                    showCancelButton: true,
                    closeOnConfirm: false,
                    showLoaderOnConfirm: true
                },
                function () {

                    var forms = $('#example-advanced-form')[0];
                    var data = new FormData(forms);
                    $.ajax({
                        type: 'POST',
                        url: "{{ url('add_model') }}",
                        data: data,
                        processData: false,
                        contentType: false,
                        cache: false,
                        success: function (data) {

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
