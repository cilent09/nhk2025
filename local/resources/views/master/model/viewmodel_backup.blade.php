@extends('layouts.userlogin')
@extends('layouts.main')
@section('title', 'View Model')
@section('breadcrumb-title-href', '../model')
@section('breadcrumb-title', 'Model')
@section('breadcrumb-sub', 'View Model')

@section('content')

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h5>VIEW</h5>
                    {{--                <span>Add class of <code>.form-control</code> with <code>&lt;input&gt;</code> tag</span>--}}
                </div>
                <div class="card-block p-0">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs md-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#home3" role="tab"><i class="fa fa-table"></i>Detail (1/2)
                            </a>
                            <div class="slide"></div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#profile3" role="tab"><i class="fa fa-table"></i>Detail (2/2)
                            </a>
                            <div class="slide"></div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#messages3" role="tab"><i class="fa fa-database"></i>Specification
                            </a>
                            <div class="slide"></div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#settings3" role="tab"><i class="fa fa-key"></i>Parameter</a>
                            <div class="slide"></div>
                        </li>
                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content card-block" >
                        {{-- <div class="tab-pane active" style="margin-top: 50px;margin-left: 150px; margin-bottom: 50px;" id="home3" role="tabpanel">
                                <div class="form-group row">
                                    <div class="col">
                                        <div class="col-sm-12">
                                            <label for="name-2" class="block">Model : {{$nt_model->name_model}}</label>
                                        </div>

                                    </div>
                                    <div class="col">
                                        <div class="col-sm-12">
                                            <label for="surname-2" class="block">Part UP/DN : {{$nt_model->part}}</label>
                                        </div>


                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col">
                                        <div class="col-sm-12">
                                            <label for="name-2" class="block">Drive Name : {{$nt_model->drive_name}}</label>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="col-sm-12">
                                            <label for="surname-2" class="block">Line type : {{$nt_model->line_type}}</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col">
                                        <div class="col-sm-12">
                                            <label for="name-2" class="block">AlPC Date correlated : {{$nt_model->alpc_dc}}</label>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="col-sm-12">
                                            <label for="surname-2" class="block">ALPC Document No./Revision : {{$nt_model->alpc_dr}}
                                                </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col">
                                        <div class="col-sm-12">
                                            <label for="name-2" class="block">ALPC Correlation site : {{$nt_model->alpc_cs}}</label>
                                        </div>
                                        <div class="col-sm-12">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="col-sm-12">
                                            <label for="surname-2" class="block">SEM Date correlated : {{$nt_model->sem_dc}}
                                                </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col">
                                        <div class="col-sm-12">
                                            <label for="name-2" class="block">SEM Document No./Revision : {{$nt_model->sem_dr}}</label>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="col-sm-12">
                                            <label for="surname-2" class="block">SEM Correlation site : {{$nt_model->sem_cs}}
                                                </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col">
                                        <div class="col-sm-12">
                                            <label for="name-2" class="block">IC Date correlated : {{$nt_model->ic_dc}}</label>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="col-sm-12">
                                            <label for="surname-2" class="block">IC Document No./Revision : {{$nt_model->ic_dr}}
                                                </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col">
                                        <div class="col-sm-12">
                                            <label for="name-2" class="block">IC Correlation site : {{$nt_model->ic_cs}}</label>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="col-sm-12">
                                            <label for="surname-2" class="block">GCMS Date correlated : {{$nt_model->gcms_dc}}
                                                </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col">
                                        <div class="col-sm-12">
                                            <label for="name-2" class="block">GCMS Document No./Revision : {{$nt_model->gcms_dr}}</label>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="col-sm-12">
                                            <label for="surname-2" class="block">GCMS Correlation site : {{$nt_model->gcms_cs}}
                                                </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col">
                                        <div class="col-sm-12">
                                            <label for="name-2" class="block">FTIR Date correlated : {{$nt_model->ftir_dc}}</label>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="col-sm-12">
                                            <label for="surname-2" class="block">FTIR Document No./Revision : {{$nt_model->ftir_dr}}
                                                </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col">
                                        <div class="col-sm-12">
                                            <label for="name-2" class="block">FTIR Correlation site : {{$nt_model->ftir_cs}}</label>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="col-sm-12">
                                            <label for="surname-2" class="block">VENDOR_ID : {{$nt_model->vendor_id}}
                                                </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col">
                                        <div class="col-sm-12">
                                            <label for="name-2" class="block">SUB_SUPPLIRE_NAME : {{$nt_model->sub_supplier_name}}</label>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="col-sm-12">
                                           
                                        </div>
                                    </div>
                                </div>
                        </div> --}}
                        <div class="tab-pane active" style="margin-top: 50px;margin-left: 150px; margin-bottom: 50px;" id="home3" role="tabpanel">
                            

                                <div class="form-group row">
                                    <div class="col">
                                        <div class="col-sm-12">
                                            <label for="name-2" class="block">Model : <span  class="text-danger">{{$nt_model->name_model}}</span> </label>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="col-sm-12">
                                        <label for="surname-2" class="block">Part UP/DN : <span class="text-danger">{{$nt_model->part}}</span></label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col">
                                        <div class="col-sm-12">
                                            <label for="name-2" class="block"> 
                                                Drive Name : <span class="text-danger"> {{$nt_model->drive_name}}</span></label>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="col-sm-12">
                                            <label for="surname-2" class="block">Line type : <span  class="text-danger">{{$nt_model->line_type}}</span> </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col">
                                        <div class="col-sm-12">
                                            <label for="surname-2" class="block">VENDOR_ID : 
                                                <span class="text-danger"> {{$nt_model->vendor_id}}</span></label>
                                        </div>
                                    </div>
                                    <div class="col">
                                        
                                        <div class="col-sm-12">
                                            <label for="surname-2" class="block">SUB_SUPPLIER_NAME : 
                                                <span  class="text-danger">{{$nt_model->sub_supplier_name}}</span> </label>
                                        </div>
                                    </div>
                                </div>


                                <div class="form-group row">
                                    <div class="col">
                                        <div class="col-sm-12">Part number : 
                                            <label for="name-2" class="block"><span class="text-danger">{{$nt_model->part_number}}</span></label>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="col-sm-12">Part revision : 
                                            <label for="surname-2" class="block"><span class="text-danger">{{$nt_model->part_revision}}</span></label>
                                        </div>

                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col">
                                        <div class="col-sm-12">Drawing number : 
                                            <label for="name-2" class="block"><span  class="text-danger">{{$nt_model->drawing_number}}</span> </label>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="col-sm-12">Drawing revision : 
                                            <label for="surname-2" class="block"><span class="text-danger">{{$nt_model->drawing_revision}}</span></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col">
                                        <div class="col-sm-12">Digit lot number :
                                            @php 
                                               if (empty($nt_model->digit_lot_number1)) {
                                                         $digit_lot_number1 = '';
                                                }
                                                else {
                                                    $digit_lot_number1 = ' ,'.$nt_model->digit_lot_number1;
                                                }
                                                if (empty($nt_model->digit_lot_number2)) {
                                                         $digit_lot_number2 = '';
                                                }
                                                else {
                                                    $digit_lot_number2 = ' ,'.$nt_model->digit_lot_number2;
                                                }
                                            @endphp 
                                            <label for="name-2" class="block"><span class="text-danger">{{$nt_model->digit_lot_number}}{{$digit_lot_number1}}{{$digit_lot_number2}}</span></label>
                                        </div>
                                    </div>
                                    <div class="col">
                                            <div class="col-sm-12">
                                                <label for="surname-2" class="block">Surface area : 
                                                <span  class="text-danger">{{$nt_model->surface_area}} {{$nt_model->surface_area_unit}}</span> </label>
                                            </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col">
                                        <div class="col-sm-12">
                                            <label for="name-2" class="block">Lot digit Q'ty : <span class="text-danger">{{$nt_model->lot_digit}}</span></label>
                                        </div>
                                    </div>
                                    <div class="col">

                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col">
                                        <div class="col-sm-12">
                                            <label for="name-2" class="block">LPC Date correlated : <span  class="text-danger">{{date('d-M-Y', strtotime($nt_model->alpc_dc))}}</span></label>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="col-sm-12">
                                            <label for="name-2" class="block">LPC Water vol. in tank :      
                                            <span  class="text-danger">{{$nt_model->alpc_water_vol}} {{$nt_model->alpc_water_vol_unit}}</span></label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col">
                                        <div class="col-sm-12">
                                            <label for="surname-2" class="block">LPC Correlation site : 
                                                <span   class="text-danger">{{$nt_model->alpc_cs}}</span></label>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="col-sm-12">
                                            <label for="surname-2" class="block">LPC Extraction vol :      
                                            <span   class="text-danger">{{$nt_model->alpc_extraction_vol}} {{$nt_model->alpc_extraction_vol_unit}}</span></label>
                                        </div>
                                    </div>
                                </div>

                                
                                <div class="form-group row">
                                    <div class="col">
                                        <div class="col-sm-12">
                                            <label for="surname-2" class="block">LPC Document No./Revision : 
                                                <span 
                                                    class="text-danger">{{$nt_model->alpc_dr}}</span></label>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="col-sm-12">
                                            <label for="surname-2" class="block">LPC US power : 
                                                <span  class="text-danger">{{$nt_model->alpc_us_power}} {{$nt_model->alpc_us_power_unit}}</span> </label>
                                        </div>
                                    </div>
                                </div>

                                 
                                <div class="form-group row">
                                    <div class="col">
                                        <div class="col-sm-12">
                                            <label for="name-2" class="block"> 
                                                LPC Parts used  : 
                                            <span  class="text-danger">{{$nt_model->alpc_parts_used}} {{$nt_model->alpc_parts_used_unit}}</span></label>
                                        </div>

                                    </div>
                                    <div class="col">
                                        <div class="col-sm-12">LPC Hang or Sink : 
                                            <label for="surname-2" class="block"><span  class="text-danger">{{$nt_model->alpc_hang}}</span></label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col">
                                          <div class="col-sm-12">
                                            <label for="name-2" class="block">LPC US frequency : 
                                            <span  class="text-danger">{{$nt_model->alpc_us_frequency}} {{$nt_model->alpc_us_frequency_unit}}</span></label>
                                        </div>
            
                                    </div>
                                    <div class="col">
                                        <div class="col-sm-12">
                                            <label for="surname-2" class="block">LPC Extraction time : 
                                            <span class="text-danger">{{$nt_model->alpc_extraction_time}} {{$nt_model->alpc_extraction_time_unit}}</span></label>
                                        </div>
                                        
                                    </div>
                                </div>
                        </div>

                        {{-- <div class="tab-pane" id="profile3" style="margin-top: 50px;margin-left: 150px; margin-bottom: 50px;" role="tabpanel">
                                <div class="form-group row">
                                    <div class="col">
                                        <div class="col-sm-12">
                                            <label for="name-2" class="block">Part number : {{$nt_model->part_number}}</label>
                                        </div>

                                    </div>
                                    <div class="col">
                                        <div class="col-sm-12">
                                            <label for="surname-2" class="block">Part revision : {{$nt_model->part_revision}}</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col">
                                        <div class="col-sm-12">
                                            <label for="name-2" class="block">Drawing number : {{$nt_model->drawing_number}}</label>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="col-sm-12">
                                            <label for="surname-2" class="block">Drawing revision : {{$nt_model->drawing_revision}}</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col">
                                        <div class="col-sm-12">
                                            <label for="name-2" class="block">Digit lot number : {{$nt_model->digit_lot_number}}</label>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="col-sm-12">
                                            <label for="surname-2" class="block">Surface area (cm2) : {{$nt_model->surface_area}}</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col">
                                        <div class="col-sm-12">
                                            <label for="name-2" class="block">IC Parts used (pcs.) : {{$nt_model->ic_parts_used}}</label>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="col-sm-12">
                                            <label for="surname-2" class="block">FTIR Parts used (pcs.) : {{$nt_model->ftir_parts_used}}</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col">
                                        <div class="col-sm-12">
                                            <label for="name-2" class="block">ALPC Parts used (pcs.)  : {{$nt_model->alpc_parts_used}}</label>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="col-sm-12">
                                            <label for="surname-2" class="block">SEM Parts used (pcs.)  : {{$nt_model->sem_parts_used}}
                                                </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col">
                                        <div class="col-sm-12">
                                            <label for="name-2" class="block">GCMS Parts used (pcs.)  : {{$nt_model->gcms_parts_used}}
                                               </label>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="col-sm-12">
                                            <label for="surname-2" class="block">ALPC Extraction vol. (ml) : {{$nt_model->alpc_extraction_vol}}
                                                </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col">
                                        <div class="col-sm-12">
                                            <label for="name-2" class="block">ALPC Water vol. in tank (L) : {{$nt_model->alpc_water_vol}}
                                                </label>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="col-sm-12">
                                            <label for="surname-2" class="block">ALPC US power (W) : {{$nt_model->alpc_us_power}}</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col">
                                        <div class="col-sm-12">
                                            <label for="name-2" class="block">Lot digit Q'ty : {{$nt_model->lot_digit}}
                                                </label>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="col-sm-12">
                                            <label for="surname-2" class="block">ALPC Hang or Sink : {{$nt_model->alpc_hang}}</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col">
                                        <div class="col-sm-12">
                                            <label for="name-2" class="block">ALPC US frequency (kHz) : {{$nt_model->alpc_us_frequency}}
                                                </label>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="col-sm-12">
                                            <label for="surname-2" class="block">ALPC Extraction time (sec) : {{$nt_model->alpc_extraction_time}}</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col">
                                        <div class="col-sm-12">
                                            <label for="name-2" class="block">SEM Filtered Solution Vol. (ml) : {{$nt_model->sem_filtered_solution}}
                                                </label>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="col-sm-12">
                                            <label for="surname-2" class="block">SEM Filter Spot Area (mm2) : {{$nt_model->sem_filter_spot_area}}</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col">
                                        <div class="col-sm-12">
                                            <label for="name-2" class="block">IC Extraction vol. (ml) : {{$nt_model->ic_extraction_vo}}
                                                </label>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="col-sm-12">
                                            <label for="surname-2" class="block">GCMS Sampling Time (hr) : {{$nt_model->gcms_sampling_time}}</label>
                                        </div>
                                    </div>
                                </div>


                        </div> --}}

                        <div class="tab-pane" id="profile3" style="margin-top: 50px;margin-left: 150px; margin-bottom: 50px;" role="tabpanel">
                            
                                <div class="form-group row">
                                    <div class="col">
                                        <div class="col-sm-12">
                                            <label for="name-2" class="block">SEM Date correlated : <span class="text-danger">{{date('d-M-Y', strtotime($nt_model->sem_dc))}}</span></label>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="col-sm-12">
                                            <label for="name-2" class="block">GCMS Date correlated : <span class="text-danger">{{date('d-M-Y', strtotime($nt_model->gcms_dc)) }}</span></label>
                                        </div>
                    
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col">
                                        <div class="col-sm-12">
                                            <label for="surname-2" class="block">SEM Correlation site : <span class="text-danger">{{$nt_model->sem_cs}}</span></label>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="col-sm-12">
                                            <label for="surname-2" class="block">GCMS Correlation site : 
                                                <span  class="text-danger">{{$nt_model->gcms_cs}}</span></label>
                                        </div>
                                    </div>
                                </div>


                                <div class="form-group row">
                                    <div class="col">
                                        <div class="col-sm-12">
                                            <label for="surname-2" class="block">SEM Document No./Revision : 
                                                <span  class="text-danger">{{$nt_model->sem_dr}} </span></label>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="col-sm-12">
                                            <label for="surname-2" class="block">GCMS Document No./Revision : 
                                                <span class="text-danger">{{$nt_model->gcms_dr}}</span></label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col">
                                        <div class="col-sm-12">
                                            <label for="name-2" class="block">SEM Filtered Solution Vol. :  
                                            <span   class="text-danger"> {{$nt_model->sem_filtered_solution}} {{$nt_model->sem_filtered_solution_unit}}</span></label>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="col-sm-12">
                                            <label for="name-2" class="block">
                                                GCMS Parts used : 
                                            <span  class="text-danger"> {{$nt_model->gcms_parts_used}} {{$nt_model->gcms_parts_used_unit}}</span></label>
                                        </div>
            
                                    </div>
                                </div>


                                <div class="form-group row">
                                    <div class="col">
                                        <div class="col-sm-12">
                                            <label for="surname-2" class="block">SEM Filter Spot Area : 
                                                
                                            <span class="text-danger">{{$nt_model->sem_filter_spot_area}} {{$nt_model->sem_filter_spot_area_unit}}</span></label>
                                        </div>
                                    
                                    </div>
                                    <div class="col">
                                        <div class="col-sm-12">
                                            <label for="surname-2" class="block"> 
                                                GCMS Sampling Time : 
                                            <span   class="text-danger"> {{$nt_model->gcms_sampling_time}} {{$nt_model->gcms_sampling_time_unit}}</span></label>
                                        </div>
                                      
                                    </div>
                                </div>


                                
                                <div class="form-group row">
                                    <div class="col">
                                        <div class="col-sm-12">
                                            <label for="surname-2" class="block">
                                                SEM Parts used : 
                                            <span   class="text-danger">{{$nt_model->sem_parts_used}} {{$nt_model->sem_parts_used_unit}}</span></label>
                                        </div>
                    
                                    </div>
                                    <div class="col">
                                       
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col">
                                        <div class="col-sm-12">IC Date correlated : 
                                            <label for="name-2" class="block"> <span  class="text-danger">{{ date('d-M-Y', strtotime($nt_model->ic_dc)) }}</span> </label>
                                        </div>

                                    </div>
                                    <div class="col">
                                        <div class="col-sm-12">FTIR Date correlated : 
                                            <label for="surname-2" class="block"> <span   class="text-danger">{{date('d-M-Y', strtotime($nt_model->ftir_dc)) }}</span></label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col">
                                        <div class="col-sm-12">
                                            <label for="surname-2" class="block">
                                                IC Correlation site : 
                                                <span style="font-size: 16px"  class="text-danger">{{$nt_model->ic_cs}}</span></label>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="col-sm-12">
                                            <label for="surname-2" class="block">FTIR Correlation site : 
                                                <span  class="text-danger">{{$nt_model->ftir_cs}}</span></label>
                                        </div>
            
                                    </div>
                                </div>


                                <div class="form-group row">
                                    <div class="col">
                                        <div class="col-sm-12">IC Document No./Revision : 
                                            <label for="surname-2" class="block">
                                                <span class="text-danger">{{$nt_model->ic_dr}}</span></label>
                                        </div>
                         
                                    </div>
                                    <div class="col">
                                        <div class="col-sm-12">
                                            <label for="surname-2" class="block">FTIR Document No./Revision : 
                                                <span  class="text-danger">{{$nt_model->ftir_dr}}</span></label>
                                        </div>
                                   
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col">
                                        <div class="col-sm-12">
                                            <label for="name-2" class="block">IC Parts used : 
                                                
                                            <span class="text-danger">{{$nt_model->ic_parts_used}} {{$nt_model->ic_parts_used_unit}}</span></label>
                                        </div>
                                      
                                    </div>
                                    <div class="col">
                                        <div class="col-sm-12">
                                            <label for="surname-2" class="block">FTIR Parts used :  
                                            <span   class="text-danger">{{$nt_model->ftir_parts_used}} {{$nt_model->ftir_parts_used_unit}}</span></label>
                                        </div>
                                       
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col">
                                        <div class="col-sm-12">
                                            <label for="name-2" class="block">IC Extraction vol. : 
                                              
                                            <span   class="text-danger">{{$nt_model->ic_extraction_vo}} {{$nt_model->ic_extraction_vo_unit}}</span></label>
                                        </div>
                                      
                                    </div>
                                    <div class="col">
                                        
                                    </div>
                                </div>
                        </div>
                        <div class="tab-pane" id="messages3" role="tabpanel">

                            <fieldset>


                                <div class="dt-responsive table-responsive">
                                    <table id="form-input-table"
                                           class="table table-striped table-bordered nowrap">

                                        <thead>
                                        <tr>
                                            <th  rowspan="2">Name</th>
                                            <th bgcolor="#FFFF99" colspan="1" class="text-center">LPC</th>
                                            <th  bgcolor="#00FFFF" colspan="10" class="text-center">SEM</th>
                                            <th bgcolor="#00FF7F" colspan="3" class="text-center">IC</th>
                                            <th bgcolor="#FFB6C1"  colspan="4" class="text-center" >GCMS</th>
                                            <th bgcolor="#EE82EE" colspan="1" class="text-center">FTIR</th>
                                            <th  rowspan="2">Name</th>

                                        </tr>

                                        <tr>

                                            <th bgcolor="#FFFF99">&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</th>
                                            <th bgcolor="#00FFFF">&emsp;&emsp;Class1&emsp;&emsp;</th>
                                            <th bgcolor="#00FFFF">&emsp;&emsp;Class2&emsp;&emsp;</th>
                                            <th bgcolor="#00FFFF">&emsp;&emsp;Class3&emsp;&emsp;</th>
                                            <th bgcolor="#00FFFF">&emsp;&ensp;Class3.1&emsp;&ensp;</th>
                                            <th bgcolor="#00FFFF">&emsp;&ensp;Class3.2&emsp;&ensp;</th>
                                            <th bgcolor="#00FFFF">&emsp;&ensp;Class3.3&emsp;&ensp;</th>
                                            <th bgcolor="#00FFFF">&emsp;&emsp;Class4&emsp;&emsp;</th>
                                            <th bgcolor="#00FFFF">&emsp;&emsp;Class5&emsp;&emsp;</th>
                                            <th bgcolor="#00FFFF">&emsp;&ensp;Class5.1&emsp;&ensp;&nbsp;</th>
                                            <th bgcolor="#00FFFF">&emsp;&emsp;Class6&emsp;&emsp;</th>
                                            <th bgcolor="#00FF7F">&emsp;&emsp;Anion&emsp;&emsp;</th>
                                            <th bgcolor="#00FF7F">&emsp;&ensp;Sulphate&emsp;&nbsp;</th>
                                            <th bgcolor="#00FF7F">&emsp;&emsp;Cation&emsp;&emsp;</th>
                                            <th bgcolor="#FFB6C1">&emsp;&nbsp;Outgassing&emsp;&nbsp;</th>
                                            <th bgcolor="#FFB6C1">&emsp;Smear&emsp;&nbsp;</th>
                                            <th bgcolor="#FFB6C1">&emsp;&nbsp;Low MW&emsp;&nbsp;</th>
                                            <th bgcolor="#FFB6C1">&emsp;&nbsp;High MW&emsp;&nbsp;</th>
                                            <th bgcolor="#EE82EE">&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</th>
                                        </tr>

                                        </thead>

                                        <tbody>

                                        @foreach($model_spec as $item )
                                            <tr>
                                                <td>{{$item->name}} :</td>
                                                <td bgcolor="#FFFF99">
                                                    {{$item->alpc}}
                                                </td>
                                                <td bgcolor="#00FFFF">
                                                    {{$item->class1}}
                                                </td>
                                                <td bgcolor="#00FFFF">
                                                    {{$item->class2}}
                                                </td>
                                                <td bgcolor="#00FFFF">
                                                    {{$item->class3}}
                                                </td>
                                                <td bgcolor="#00FFFF">
                                                   {{$item->class3_1}}
                                                </td>
                                                <td bgcolor="#00FFFF">
                                                   {{$item->class3_2}}
                                                </td>
                                                <td bgcolor="#00FFFF">
                                                    {{$item->class3_3}}
                                                </td>
                                                <td bgcolor="#00FFFF">
                                                    {{$item->class4}}
                                                </td>
                                                <td bgcolor="#00FFFF">
                                                    {{$item->class5}}
                                                </td>
                                                <td bgcolor="#00FFFF">
                                                    {{$item->class5_1}}
                                                </td>
                                                <td bgcolor="#00FFFF">
                                                    {{$item->class6}}
                                                </td>
                                                <td bgcolor="#00FF7F">
                                                    {{$item->anion}}
                                                </td>
                                                <td bgcolor="#00FF7F">
                                                    {{$item->sulphate}}
                                                </td>
                                                <td bgcolor="#00FF7F">
                                                    {{$item->cation}}
                                                </td>
                                                <td bgcolor="#FFB6C1">
                                                    {{$item->outgassing}}
                                                </td>
                                                <td bgcolor="#FFB6C1">
                                                    {{$item->alkylacrylate}}
                                                </td>
                                                <td bgcolor="#FFB6C1">
                                                    {{$item->low_MW}}
                                                </td>
                                                <td bgcolor="#FFB6C1">
                                                    {{$item->high_MW}}
                                                </td>
                                                <td bgcolor="#EE82EE">
                                                    {{$item->ftir}}
                                                </td>
                                                <td>: {{$item->name}}</td>
                                            </tr>
                                        @endforeach

                                        <tr>
                                            <td  rowspan="2">Data Q'ty in View</td>
                                            <td bgcolor="#FFFF99" colspan="1" class="text-center">
                                               {{$nt_model->alpc_sum}}
                                            </td>
                                            <td  bgcolor="#00FFFF" colspan="10" class="text-center">
                                                <div class="row">
                                                    <div class="col-sm-5">&nbsp;</div>
                                                    <div class="col-sm-2">
                                                        {{$nt_model->sem_sum}}
                                                    </div>
                                                    <div class="col-sm-5">&nbsp;</div>
                                                </div>
                                            </td>
                                            <td bgcolor="#00FF7F" colspan="3" class="text-center">
                                                <div class="row">
                                                    <div class="col-sm-3">&nbsp;</div>
                                                    <div class="col-sm-6">
                                                        {{$nt_model->ic_sum}}
                                                    </div>
                                                    <div class="col-sm-2">&nbsp;</div>
                                                </div>
                                            </td>
                                            <td bgcolor="#FFB6C1"  colspan="4" class="text-center" >
                                                <div class="row">
                                                    <div class="col-sm-4">&nbsp;</div>
                                                    <div class="col-sm-4">
                                                       {{$nt_model->gcms_sum}}
                                                    </div>
                                                    <div class="col-sm-2">&nbsp;</div>
                                                </div>
                                            </td>
                                            <td bgcolor="#EE82EE" colspan="1" class="text-center">
                                                {{$nt_model->ftir_sum}}
                                            </td>
                                            <td  rowspan="2">Data Q'ty in View</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </fieldset>
                        </div>
                        <div class="tab-pane" id="settings3" style="margin-top: 50px; margin-bottom: 50px;" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table">
                                    <tr>
                                        <th>NAME</th>
                                        <th>LPC</th>
                                        <th>SEM</th>
                                        <th>IC</th>
                                        <th>GCMS</th>
                                        <th>FTIR</th>
                                        <th>Ghost</th>
                                    </tr>
                                    @foreach($model_parameter as $st)
                                        @php
                                            if($st->lpc == 1){$lpc = "green"; $lpcicon = "fa fa-check"; $filelpc ='local/storage/NHK-File/model-rowfile/'.$st->file_folder.'/'.$st->lpc_file; }else { $filelpc =''; $lpc = "red"; $lpcicon = "fa fa-times";}
                                            if($st->sem == 1){$sem = "green"; $semicon = "fa fa-check"; $filesem ='local/storage/NHK-File/model-rowfile/'.$st->file_folder.'/'.$st->sem_file;}else {$filesem =''; $sem = "red"; $semicon = "fa fa-times";}
                                            if($st->ic == 1){$ic= "green"; $icicon = "fa fa-check"; $fileic ='local/storage/NHK-File/model-rowfile/'.$st->file_folder.'/'.$st->ic_file;}else {$fileic =''; $ic = "red"; $icicon = "fa fa-times";}
                                            if($st->gcms == 1){$gcms = "green"; $gcmsicon = "fa fa-check"; $filegcms ='local/storage/NHK-File/model-rowfile/'.$st->file_folder.'/'.$st->gcms_file;}else {$filegcms =''; $gcms = "red"; $gcmsicon = "fa fa-times";}
                                            if($st->ftir == 1){$ftir = "green"; $ftiricon = "fa fa-check"; $fileftir ='local/storage/NHK-File/model-rowfile/'.$st->file_folder.'/'.$st->ftir_file;}else {$fileftir =''; $ftir = "red"; $ftiricon = "fa fa-times";}
                                            if($st->semtest == 1){$semtest = "green"; $semtesticon = "fa fa-check"; $filesemtest ='local/storage/NHK-File/model-rowfile/'.$st->file_folder.'/'.$st->semtest_file;}else {$filesemtest =''; $semtest = "red"; $semtesticon = "fa fa-times";}
                                        @endphp
                                    <tr>
                                        <td>{{$st->name}}</td>
                                        <td>@if($st->lpc == 1 && $st->id_model_p == 1)<a href="{{asset($filelpc)}}" class="fa fa-file-archive-o" style="font-size:20px"></a> @endif <font color='{{$lpc}}'><i class='{{$lpcicon}}' style="font-size:20px" aria-hidden='true'></i></font></td>
                                        <td>@if($st->sem == 1 && $st->id_model_p == 1)<a href="{{asset($filesem)}}" class="fa fa-file-archive-o" style="font-size:20px"></a> @endif <font color='{{$sem}}'><i class='{{$semicon}}' style="font-size:20px" aria-hidden='true'></i></font></td>
                                        <td>@if($st->ic == 1 && $st->id_model_p == 1)<a href="{{asset($fileic)}}" class="fa fa-file-archive-o" style="font-size:20px"></a> @endif <font color='{{$ic}}'><i class='{{$icicon}}'style="font-size:20px"  aria-hidden='true'></i></font></td>
                                        <td>@if($st->gcms == 1 && $st->id_model_p == 1)<a href="{{asset($filegcms)}}" class="fa fa-file-archive-o" style="font-size:20px"></a> @endif  <font color='{{$gcms}}'><i class='{{$gcmsicon}}' style="font-size:20px" aria-hidden='true'></i></font></td>
                                        <td>@if($st->ftir == 1 && $st->id_model_p == 1)<a href="{{asset($fileftir)}}" class="fa fa-file-archive-o" style="font-size:20px"></a> @endif  <font color='{{$ftir}}'><i class='{{$ftiricon}}' style="font-size:20px" aria-hidden='true'></i></font></td>
                                        <td>@if($st->semtest == 1 && $st->id_model_p == 1)<a href="{{asset($filesemtest)}}" class="fa fa-file-archive-o" style="font-size:20px"></a> @endif  <font color='{{$semtest}}'><i class='{{$semtesticon}}' style="font-size:20px" aria-hidden='true'></i></font></td>
                                    </tr>
                                    @endforeach
                                </table>
                            </div>
                            

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- tabs card start -->
@endsection

@section('scripts')

    <script>
    </script>

@endsection
