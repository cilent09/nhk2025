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
                    <table id="dt" class="table table-bordered nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th  class="text-center" width="120">FTIR</th>
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
                                <td>FTIR data</td>
                                <td>{{$oqa_lab->name_model}}</td>
                                <td>{{$oqa_lab->part}}</td>
                                <td>{{$oqa_lab->lotnumber_lab}} </td>
                                <td>{{$spec->ftir}} </td>
                                <td>{{$ucl->ftir}} </td>
                                <td>{{$oqa_labfc->ftir_silicone}} </td>
                                <td><input type="hidden" name="id_oqa" value="{{$oqa_lab->id_createlab}}"><input onkeydown="return (event.keyCode!=13);"  class="form-control" type="text" name="ftir_silicone" value="{{$data_ftir->ftir_silicone}}"></td>
                                @php
                                if(!empty($data_ftir->last_ftir_silicone))
                                {
                                      $showdata =  $data_ftir->last_ftir_silicone;
                                }
                                else
                                {
                                    $showdata =  '-';
                                }
                            @endphp
                            <td>{{$showdata}} </td>
                                <td>
                                    <button type="button" onclick="editftir()" class="btn btn-primary"  ><i class="fa fa-save"></i></button>
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
    function editftir()
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
                    url: "{{ url('editdataftirsave') }}",
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