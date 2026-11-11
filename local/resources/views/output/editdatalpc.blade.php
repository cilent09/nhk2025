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
                    <form  method="post"  id="fm-editlpc" >
                        {{ csrf_field() }}
                    <input type="hidden" value="{{$chk}}" id="chk" name="chk">
                    <table id="dt" class="table table-bordered nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th  class="text-center" width="120">LPC</th>
                                <th class="text-center">Model</th>
                                <th class="text-center">Part</th>
                                <th class="text-center">Lot</th>
                                <th class="text-center">Spec.</th>
                                <th class="text-center">UCL</th>
                                <th class="text-center">Raw data</th>
                                <th class="text-center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;LPC_1&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                <th class="text-center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;LPC_2&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                <th class="text-center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;LPC_3&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                <th class="text-center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Average&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                <th class="text-center">Last Data LPC_1</th>
                                <th class="text-center">Last Data LPC_2</th>
                                <th class="text-center">Last Data LPC_3</th>
                                <th class="text-center">Last Data Data</th>
                                <th class="text-center">Edit Data Data</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="text-center">
                                @php
                                if($showeditalam > 0)
                                {
                                   
                                   $lpctxtcolor =  'red';
                                   $lpcfontcolor =  'floralwhite';
                                }
                                else {
                                    $lpctxtcolor =  '';
                                    $lpcfontcolor =  '';
                                }
                                @endphp
                                <td>LPC data</td>
                                <td>{{$oqa_lab->name_model}}</td>
                                <td>{{$oqa_lab->part}}</td>
                                <td>{{$oqa_lab->lotnumber_lab}} </td>
                                <td>{{$spec->alpc}} </td>
                            <td style="background-color: {{$lpctxtcolor}};color: {{$lpcfontcolor}}">{{$ucl->alpc}} </td>
                      
                                <td>{{$oqa_labfc->lpc_data}} </td>
                                <td><input onkeydown="return (event.keyCode!=13);" class="form-control" type="text" name="data_lpc1" value="{{$data_lpc->lpc1}}"></td>
                                <td><input onkeydown="return (event.keyCode!=13);" class="form-control" type="text" name="data_lpc2" value="{{$data_lpc->lpc2}}"></td>
                                <td><input onkeydown="return (event.keyCode!=13);" class="form-control" type="text" name="data_lpc3" value="{{$data_lpc->lpc3}}"></td>
                                <td><input type="hidden" name="id_oqa" value="{{$data_lpc->id_createlab}}"><input onkeydown="return (event.keyCode!=13);" class="form-control" type="text" name="data_lpc" value="{{$data_lpc->lpc_data}}"></td>
                                @php
                                if(!empty($data_lpc->last_lpc1))
                                {
                                      $showdata1 =  $data_lpc->last_lpc1;
                                }
                                else
                                {
                                    $showdata1 =  '-';
                                }
                            @endphp
                            <td>{{$showdata1}} </td>
                                @php
                                if(!empty($data_lpc->last_lpc2))
                                {
                                      $showdata2 =  $data_lpc->last_lpc2;
                                }
                                else
                                {
                                    $showdata2 =  '-';
                                }
                            @endphp
                            <td>{{$showdata2}} </td>
                                @php
                                if(!empty($data_lpc->last_lpc3))
                                {
                                      $showdata3 =  $data_lpc->last_lpc3;
                                }
                                else
                                {
                                    $showdata3 =  '-';
                                }
                            @endphp
                            <td>{{$showdata3}} </td>
                               @php
                                    if(!empty($data_lpc->last_lpc_data))
                                    {
                                          $showdata =  $data_lpc->last_lpc_data;
                                    }
                                    else
                                    {
                                        $showdata =  '-';
                                    }
                                @endphp
                                <td>{{$showdata}} </td>
                                <td>
                                    <button type="button" onclick="editlpc()" class="btn btn-primary"  ><i class="fa fa-save"></i></button>
                                </td>
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
{{-- <form action="" id="fm-editlpc" >
    <button type="button" onclick="xx()" class="btn btn-primary" ><i class="fa fa-save"></i></button>
</form> --}}
@endsection

@section('scripts')
<script>
    function editlpc()
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
                var forms = $('#fm-editlpc')[0];
                var data = new FormData(forms);
                $.ajax({
                    type: 'POST',
                    url: "{{ url('editdatalpcsave') }}",
                    data: data,
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function (data) {
                        if (data.success == 1) {
                               if(data.chk == 1){
                                swal("Success", "Submit edit data success", "success");
                                setTimeout(function () {
                                    window.location.href =
                                        "{{ url('output/results') }}";
                                }, 1000);
                        }
                                else{
                                      
                                    swal("Success", "Submit edit data success", "success");
                                setTimeout(function () {
                                    window.location.href = '../../../../output/viewdata/'+data.id;
                                }, 1000);
                                }
                            } else {

                            }
                    }
                });
            });
    }


</script>
@endsection