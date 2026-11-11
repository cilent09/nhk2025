@extends('layouts.userlogin')
@extends('layouts.main')
@section('title', 'Edit Results')
@section('breadcrumb-title', 'Output section')
@section('breadcrumb-sub', 'Edit Results')

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                
            </div>
            <div class="card-block">
                
                <div class="dt-responsive table-responsive">
                    <form  method="post"  id="fm-editic" >
                        {{ csrf_field() }}
                    <table id="dt" class="table table-bordered nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th class="text-center" width="120">IC</th>
                                <th class="text-center">Model</th>
                                <th class="text-center">Part</th>
                                <th class="text-center">Lot</th>
                                <th class="text-center">Spec.</th>
                                <th class="text-center">UCL</th>
                                <th class="text-center">Raw data</th>
                                <th class="text-center">Input Edit Data</th>
                                <th class="text-center">Last Edit Data</th>
                                <th class="text-center">Submit Edit Data</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="text-center">
                                <td>Anion Fluoride</td>
                                <td>{{$oqa_lab->name_model}}</td>
                                <td>{{$oqa_lab->part}}</td>
                                <td>{{$oqa_lab->lotnumber_lab}} </td>
                                <td>N/A</td>
                                <td>N/A</td>
                                <td>{{$oqa_labfc->ic_anion_fluoride}}</td>
                                <td><input  class="form-control" type="text" name="ic_anion_fluoride" id="ic_anion_fluoride" value="{{$data_ic->ic_anion_fluoride}}"></td>
                                @php
                                if(!empty($data_lpc->last_ic_anion_fluoride))
                                {
                                      $showdata =  $data_lpc->last_ic_anion_fluoride;
                                }
                                else
                                {
                                    $showdata =  '-';
                                }
                            @endphp
                            <td>{{$showdata}} </td>
                                
                                <td rowspan="16">
                                    <input type="hidden" name="id_oqa" value="{{$oqa_lab->id_createlab}}">
                                    <button type="button" onclick="editic()" class="btn btn-primary"  ><i class="fa fa-save"></i></button>
                                    {{-- <button class="btn btn-primary" onclick="editData();"><i class="fa fa-save"></i></button> --}}
                                </td>
                            </tr>
                            <tr class="text-center">
                                <td>Anion Chloride</td>
                                <td>{{$oqa_lab->name_model}}</td>
                                <td>{{$oqa_lab->part}}</td>
                                <td>{{$oqa_lab->lotnumber_lab}} </td>
                                <td>N/A</td>
                                <td>N/A</td>
                                <td>{{$oqa_labfc->ic_anion_chloride}}</td>
                                <td><input  class="form-control" type="text" name="ic_anion_chloride" id="ic_anion_chloride" value="{{$data_ic->ic_anion_chloride}}"></td>
                                @php
                                if(!empty($data_lpc->last_ic_anion_chloride))
                                {
                                      $showdata =  $data_lpc->last_ic_anion_chloride;
                                }
                                else
                                {
                                    $showdata =  '-';
                                }
                            @endphp
                            <td>{{$showdata}} </td>
                                
                            </tr>
                            <tr class="text-center">
                                <td>Anion Nitrile</td>
                                <td>{{$oqa_lab->name_model}}</td>
                                <td>{{$oqa_lab->part}}</td>
                                <td>{{$oqa_lab->lotnumber_lab}} </td>
                                <td>N/A</td>
                                <td>N/A</td>
                                <td>{{$oqa_labfc->ic_anion_nitrile}}</td>
                                <td><input  class="form-control" type="text" name="ic_anion_nitrile" id="ic_anion_nitrile" value="{{$data_ic->ic_anion_nitrile}}"></td>
                                @php
                                if(!empty($data_lpc->last_ic_anion_nitrile))
                                {
                                      $showdata =  $data_lpc->last_ic_anion_nitrile;
                                }
                                else
                                {
                                    $showdata =  '-';
                                }
                            @endphp
                            <td>{{$showdata}} </td>
                               
                            </tr>
                            <tr class="text-center">
                                <td>Anion Bromide</td>
                                <td>{{$oqa_lab->name_model}}</td>
                                <td>{{$oqa_lab->part}}</td>
                                <td>{{$oqa_lab->lotnumber_lab}} </td>
                                <td>N/A</td>
                                <td>N/A</td>
                                <td>{{$oqa_labfc->ic_anion_bromid}}</td>
                                <td><input  class="form-control" type="text" name="ic_anion_bromid" id="ic_anion_bromid" value="{{$data_ic->ic_anion_bromid}}"></td>
                                @php
                                if(!empty($data_lpc->last_ic_anion_bromid))
                                {
                                      $showdata =  $data_lpc->last_ic_anion_bromid;
                                }
                                else
                                {
                                    $showdata =  '-';
                                }
                            @endphp
                            <td>{{$showdata}} </td>
                               
                            </tr>

                            <tr class="text-center">
                                <td>Anion Nitrate</td>
                                <td>{{$oqa_lab->name_model}}</td>
                                <td>{{$oqa_lab->part}}</td>
                                <td>{{$oqa_lab->lotnumber_lab}} </td>
                                <td>N/A</td>
                                <td>N/A</td>
                                <td>{{$oqa_labfc->ic_anion_nitrate}}</td>
                                <td><input  class="form-control" type="text" name="ic_anion_nitrate" id="ic_anion_nitrate" value="{{$data_ic->ic_anion_nitrate}}"></td>
                                @php
                                if(!empty($data_lpc->last_ic_anion_nitrate))
                                {
                                      $showdata =  $data_lpc->last_ic_anion_nitrate;
                                }
                                else
                                {
                                    $showdata =  '-';
                                }
                            @endphp
                            <td>{{$showdata}} </td>
                                
                            </tr>

                            <tr class="text-center">
                                <td>Anion Sulphate</td>
                                <td>{{$oqa_lab->name_model}}</td>
                                <td>{{$oqa_lab->part}}</td>
                                <td>{{$oqa_lab->lotnumber_lab}} </td>
                                <td>N/A</td>
                                <td>N/A</td>
                                <td>{{$oqa_labfc->ic_anion_sulphate}}</td>
                                <td><input  class="form-control" type="text" name="ic_anion_sulphate" id="ic_anion_sulphate" value="{{$data_ic->ic_anion_sulphate}}"></td>
                                @php
                                if(!empty($data_lpc->last_ic_anion_sulphate))
                                {
                                      $showdata =  $data_lpc->last_ic_anion_sulphate;
                                }
                                else
                                {
                                    $showdata =  '-';
                                }
                            @endphp
                            <td>{{$showdata}} </td>
                                
                            </tr>

                            <tr class="text-center">
                                <td>Anion Phosphate</td>
                                <td>{{$oqa_lab->name_model}}</td>
                                <td>{{$oqa_lab->part}}</td>
                                <td>{{$oqa_lab->lotnumber_lab}} </td>
                                <td>N/A</td>
                                <td>N/A</td>
                                <td>{{$oqa_labfc->ic_anion_phosphate}}</td>
                                <td><input  class="form-control" type="text" name="ic_anion_phosphate" id="ic_anion_phosphate" value="{{$data_ic->ic_anion_phosphate}}"></td>
                                @php
                                if(!empty($data_lpc->last_ic_anion_phosphate))
                                {
                                      $showdata =  $data_lpc->last_ic_anion_phosphate;
                                }
                                else
                                {
                                    $showdata =  '-';
                                }
                            @endphp
                            <td>{{$showdata}} </td>
                                
                            </tr>

                            <tr class="text-center">
                                <td>Anion UP</td>
                                <td>{{$oqa_lab->name_model}}</td>
                                <td>{{$oqa_lab->part}}</td>
                                <td>{{$oqa_lab->lotnumber_lab}} </td>
                                <td>{{$spec->anion}} </td>
                                <td>{{$ucl->anion}} </td>
                                <td>{{$oqa_labfc->ic_anion_up}}</td>
                                <td><input  class="form-control" type="text" name="ic_anion_up" id="ic_anion_up" value="{{$data_ic->ic_anion_up}}"></td>
                                @php
                                if(!empty($data_lpc->last_ic_anion_up))
                                {
                                      $showdata =  $data_lpc->last_ic_anion_up;
                                }
                                else
                                {
                                    $showdata =  '-';
                                }
                            @endphp
                            <td>{{$showdata}} </td>
                                
                            </tr>
                            <tr class="text-center">
                                <td>Sulphate</td>
                                <td>{{$oqa_lab->name_model}}</td>
                                <td>{{$oqa_lab->part}}</td>
                                <td>{{$oqa_lab->lotnumber_lab}} </td>
                                <td>{{$spec->sulphate}} </td>
                                <td>{{$ucl->sulphate}} </td>
                                <td>{{$oqa_labfc->ic_sulphate}}</td>
                                <td><input  class="form-control" type="text" name="ic_sulphate" id="ic_sulphate" value="{{$data_ic->ic_sulphate}}"></td>
                                @php
                                if(!empty($data_lpc->last_ic_sulphate))
                                {
                                      $showdata =  $data_lpc->last_ic_sulphate;
                                }
                                else
                                {
                                    $showdata =  '-';
                                }
                            @endphp
                            <td>{{$showdata}} </td>
                                
                            </tr>
                            <tr class="text-center">
                                <td>Cation Lithium</td>
                                <td>{{$oqa_lab->name_model}}</td>
                                <td>{{$oqa_lab->part}}</td>
                                <td>{{$oqa_lab->lotnumber_lab}} </td>
                                <td>N/A</td>
                                <td>N/A</td>
                                <td>{{$oqa_labfc->ic_cation_lithium}}</td>
                                <td><input  class="form-control" type="text" name="ic_cation_lithium" id="ic_cation_lithium" value="{{$data_ic->ic_cation_lithium}}"></td>
                                @php
                                if(!empty($data_lpc->last_ic_cation_lithium))
                                {
                                      $showdata =  $data_lpc->last_ic_cation_lithium;
                                }
                                else
                                {
                                    $showdata =  '-';
                                }
                            @endphp
                            <td>{{$showdata}} </td>
                                
                            </tr>
                            <tr class="text-center">
                                <td>Cation Sodium</td>
                                <td>{{$oqa_lab->name_model}}</td>
                                <td>{{$oqa_lab->part}}</td>
                                <td>{{$oqa_lab->lotnumber_lab}} </td>
                                <td>N/A</td>
                                <td>N/A</td>
                                <td>{{$oqa_labfc->ic_cation_sodium}}</td>
                                <td><input  class="form-control" type="text" name="ic_cation_sodium" id="ic_cation_sodium" value="{{$data_ic->ic_cation_sodium}}"></td>
                                @php
                                if(!empty($data_lpc->last_ic_cation_sodium))
                                {
                                      $showdata =  $data_lpc->last_ic_cation_sodium;
                                }
                                else
                                {
                                    $showdata =  '-';
                                }
                            @endphp
                            <td>{{$showdata}} </td>
                                
                            </tr>
                            <tr class="text-center">
                                <td>Cation Ammonium</td>
                                <td>{{$oqa_lab->name_model}}</td>
                                <td>{{$oqa_lab->part}}</td>
                                <td>{{$oqa_lab->lotnumber_lab}} </td>
                                <td>N/A</td>
                                <td>N/A</td>
                                <td>{{$oqa_labfc->ic_cation_ammonium}}</td>
                                <td><input  class="form-control" type="text" name="ic_cation_ammonium" id="ic_cation_ammonium" value="{{$data_ic->ic_cation_ammonium}}"></td>
                                @php
                                if(!empty($data_lpc->last_ic_cation_ammonium))
                                {
                                      $showdata =  $data_lpc->last_ic_cation_ammonium;
                                }
                                else
                                {
                                    $showdata =  '-';
                                }
                            @endphp
                            <td>{{$showdata}} </td>
                                
                            </tr>
                            <tr class="text-center">
                                <td>Cation Potassium</td>
                                <td>{{$oqa_lab->name_model}}</td>
                                <td>{{$oqa_lab->part}}</td>
                                <td>{{$oqa_lab->lotnumber_lab}} </td>
                                <td>N/A</td>
                                <td>N/A</td>
                                <td>{{$oqa_labfc->ic_cation_potassium}}</td>
                                <td><input  class="form-control" type="text" name="ic_cation_potassium" id="ic_cation_potassium" value="{{$data_ic->ic_cation_potassium}}"></td>
                                @php
                                if(!empty($data_lpc->last_ic_cation_potassium))
                                {
                                      $showdata =  $data_lpc->last_ic_cation_potassium;
                                }
                                else
                                {
                                    $showdata =  '-';
                                }
                            @endphp
                            <td>{{$showdata}} </td>
                               
                            </tr>
                            <tr class="text-center">
                                <td>Cation Magnesium</td>
                                <td>{{$oqa_lab->name_model}}</td>
                                <td>{{$oqa_lab->part}}</td>
                                <td>{{$oqa_lab->lotnumber_lab}} </td>
                                <td>N/A</td>
                                <td>N/A</td>
                                <td>{{$oqa_labfc->ic_cation_magnesium}}</td>
                                <td><input  class="form-control" type="text" name="ic_cation_magnesium" id="ic_cation_magnesium" value="{{$data_ic->ic_cation_magnesium}}"></td>
                                @php
                                if(!empty($data_lpc->last_ic_cation_magnesium))
                                {
                                      $showdata =  $data_lpc->last_ic_cation_magnesium;
                                }
                                else
                                {
                                    $showdata =  '-';
                                }
                            @endphp
                            <td>{{$showdata}} </td>
                                
                            </tr>
                            <tr class="text-center">
                                <td>Cation Calcium</td>
                                <td>{{$oqa_lab->name_model}}</td>
                                <td>{{$oqa_lab->part}}</td>
                                <td>{{$oqa_lab->lotnumber_lab}} </td>
                                <td>N/A</td>
                                <td>N/A</td>
                                <td>{{$oqa_labfc->ic_cation_calcium}}</td>
                                <td><input  class="form-control" type="text" name="ic_cation_calcium" id="ic_cation_calcium" value="{{$data_ic->ic_cation_calcium}}"></td>
                                @php
                                if(!empty($data_lpc->last_ic_cation_calcium))
                                {
                                      $showdata =  $data_lpc->last_ic_cation_calcium;
                                }
                                else
                                {
                                    $showdata =  '-';
                                }
                            @endphp
                            <td>{{$showdata}} </td>
                               
                            </tr>
                            <tr class="text-center">
                                <td>Cation UP</td>
                                <td>{{$oqa_lab->name_model}}</td>
                                <td>{{$oqa_lab->part}}</td>
                                <td>{{$oqa_lab->lotnumber_lab}} </td>
                                <td>{{$spec->cation}} </td>
                                <td>{{$ucl->cation}} </td>
                                <td>{{$oqa_labfc->ic_cation_up}}</td>
                                <td><input  class="form-control" type="text" name="ic_cation_up" id="ic_cation_up" value="{{$data_ic->ic_cation_up}}"></td>
                                @php
                                if(!empty($data_lpc->last_ic_cation_up))
                                {
                                      $showdata =  $data_lpc->last_ic_cation_up;
                                }
                                else
                                {
                                    $showdata =  '-';
                                }
                            @endphp
                            <td>{{$showdata}} </td>
                                
                            </tr>            
                        </tbody>
                    </table>
                    </form>
                </div>

            </div>
        </div>
        {{-- <a href="{{ url('output/results') }}" class="btn btn-warning pull-right">Back</a> --}}
    </div>
</div>
@endsection

@section('scripts')
<script>

    function editic()
    {
        swal({
                html: true,
                title: "Are you sure?",
                text: "Submit Edit Data! ",
                type: "info",
                showCancelButton: true,
                closeOnConfirm: false,
                showLoaderOnConfirm: true
            },
            function () {
                var forms = $('#fm-editic')[0];
                var data = new FormData(forms);
                $.ajax({
                    type: 'POST',
                    url: "{{ url('editdataicsave') }}",
                    data: data,
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function (data) {
                        if (data.success == 1) {
                                swal("Success", "Submit edit data success", "success");
                                setTimeout(function () {
                                    window.location.href =
                                    "{{ url('output/results') }}";
                                }, 1000);
                            } else {

                            }
                         }
                    });
                 });
     }
    
</script>
@endsection