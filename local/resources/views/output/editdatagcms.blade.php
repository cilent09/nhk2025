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
                    <form  method="post"  id="fm-editgcms" >
                        {{ csrf_field() }}
                    <table id="dt" class="table table-bordered nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th class="text-center" width="120">GCMS</th>
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
                                <td>Outgassing</td>
                                <td>{{$oqa_lab->name_model}}</td>
                                <td>{{$oqa_lab->part}}</td>
                                <td>{{$oqa_lab->lotnumber_lab}} </td>
                                <td>{{$spec->outgassing}} </td>
                                <td>{{$ucl->outgassing}} </td>
                                <td>{{$oqa_labfc->gcms_outgassing}}</td>
                                <td><input  class="form-control" type="text" name="gcms_outgassing" id="gcms_outgassing" value="{{$data_gcms->gcms_outgassing}}"></td>
                                @php
                                if(!empty($data_lpc->last_gcms_outgassing))
                                {
                                      $showdata =  $data_lpc->last_gcms_outgassing;
                                }
                                else
                                {
                                    $showdata =  '-';
                                }
                            @endphp
                            <td>{{$showdata}} </td>
                               
                            
                            </tr>
                            <tr class="text-center">
                                <td>Smear</td>
                                <td>{{$oqa_lab->name_model}}</td>
                                <td>{{$oqa_lab->part}}</td>
                                <td>{{$oqa_lab->lotnumber_lab}} </td>
                                <td>{{$spec->alkylacrylate}} </td>
                                <td>{{$ucl->alkylacrylate}} </td>
                                <td>{{$oqa_labfc->gcms_smear}}</td>
                                <td><input  class="form-control" type="text" name="gcms_smear" id="gcms_smear" value="{{$data_gcms->gcms_smear}}"></td>
                                @php
                                if(!empty($data_lpc->last_gcms_smear))
                                {
                                      $showdata =  $data_lpc->last_gcms_smear;
                                }
                                else
                                {
                                    $showdata =  '-';
                                }
                            @endphp
                            <td>{{$showdata}} </td>
                               
                                <td  rowspan="4">
                                    <input type="hidden" name="id_oqa" value="{{$oqa_lab->id_createlab}}">
                                    <button type="button" onclick="editgcms()" class="btn btn-primary"  ><i class="fa fa-save"></i></button>
                                </td>
                            </tr>
                            <tr class="text-center">
                                <td>Low MW</td>
                                <td>{{$oqa_lab->name_model}}</td>
                                <td>{{$oqa_lab->part}}</td>
                                <td>{{$oqa_lab->lotnumber_lab}} </td>
                                <td>{{$spec->low_MW}} </td>
                                <td>{{$ucl->low_MW}} </td>
                                <td>{{$oqa_labfc->gcms_low_mw}}</td>
                                <td><input  class="form-control" type="text" name="gcms_low_mw" id="gcms_low_mw" value="{{$data_gcms->gcms_low_mw}}"></td>
                                @php
                                if(!empty($data_lpc->last_gcms_low_mw))
                                {
                                      $showdata =  $data_lpc->last_gcms_low_mw;
                                }
                                else
                                {
                                    $showdata =  '-';
                                }
                            @endphp
                            <td>{{$showdata}} </td>
                               
                             
                            </tr>
                            <tr class="text-center">
                                <td>High MW</td>
                                <td>{{$oqa_lab->name_model}}</td>
                                <td>{{$oqa_lab->part}}</td>
                                <td>{{$oqa_lab->lotnumber_lab}} </td>
                                <td>{{$spec->high_MW}} </td>
                                <td>{{$ucl->high_MW}} </td>
                                <td>{{$oqa_labfc->gcms_high_mw}}</td>
                                <td><input  class="form-control" type="text" name="gcms_high_mw" id="gcms_high_mw" value="{{$data_gcms->gcms_high_mw}}"></td>
                                @php
                                if(!empty($data_lpc->last_gcms_high_mw))
                                {
                                      $showdata =  $data_lpc->last_gcms_high_mw;
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

function editgcms()
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
                var forms = $('#fm-editgcms')[0];
                var data = new FormData(forms);
                $.ajax({
                    type: 'POST',
                    url: "{{ url('editdatagcmssave') }}",
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